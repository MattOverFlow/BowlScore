async function downloadTorneo(idTorneo) {
    const response = await fetch('../../PHP/Torneo/ScaricaTorneoSquadre.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `idTorneo=${idTorneo}`,
    });

    const data = await response.json();
    console.log(data);
    return data;
}

async function downloadTeamsTorneo(idTorneo) {
    const response = await fetch('../../PHP/Team/TeamsTorneo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `idTorneo=${idTorneo}`,
    });

    const data = await response.json();
    console.log(data);
    return data;
}

document.addEventListener('DOMContentLoaded', async function () {
    const mainTag = document.querySelector("main");

    function getUrlParams() {
        const params = {};
        const queryString = window.location.search.substring(1);
        const regex = /([^&=]+)=([^&]*)/g;
        let m;
        while ((m = regex.exec(queryString))) {
            params[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
        }
        return params;
    }

    const urlParams = getUrlParams();
    const isUser = urlParams.type === 'user';
    const sidebar = document.getElementById('mySidebar');
    const idTorneo = urlParams.idTorneo;

    const datiTorneo = await downloadTorneo(idTorneo);
    const torneo = datiTorneo.torneo;

    const TeamsTournament = await downloadTeamsTorneo(idTorneo);

    const standingFinale = {};

    if (isUser) {
        sidebar.innerHTML = `
            <a href="#" class="closebtn" id="closebtn">&times;</a>
            <a href="../UserProfile/cardAndSubscription.php" class="sidebarField">Carta e abbonamenti</a>
            <a href="../UserProfile/historyMatchPage.php" class="sidebarField">Storico partite</a>
            <a href="../UserProfile/historyTournaments.php" class="sidebarField">Storico tornei</a>
            <a href="../UserProfile/userpage.php" class="sidebarField">Profilo</a>
            <a href="../UserProfile/searchPage.php" class="sidebarField">Cerca utenti</a>    
            <a href="../Statistics/generalStatistic.php?type=user" class="sidebarField">Classifiche generali</a>
            <a href="../../PHP/Utils/Logout.php" class="sidebarField">Logout</a>
        `;
    }

    Object.keys(TeamsTournament).forEach((teamName) => {
        standingFinale[teamName] = {
            punti: 0,
            punteggioTotale: 0,
        };
    });

    const findTeamByUsername = (username) => {
        for (const [teamName, members] of Object.entries(TeamsTournament)) {
            if (members.includes(username)) {
                return teamName;
            }
        }
        console.warn(`Giocatore ${username} non trovato in nessun team.`);
        return null;
    };

    function calculateStandings() {
        const standingsArray = Object.entries(standingFinale).map(([teamName, teamData]) => ({
            teamName,
            points: teamData.punti,
            total: teamData.punteggioTotale,
        }));

        standingsArray.sort((a, b) => {
            if (b.points !== a.points) {
                return b.points - a.points;
            }
            return b.total - a.total;
        });

        const standings = {};
        standingsArray.forEach(({ teamName, points, total }) => {
            standings[teamName] = { points, total };
        });

        return standings;
    }

    function renderStandings(standings) {
        const standingsDiv = document.createElement('div');
        standingsDiv.id = "Standings";
        standingsDiv.classList.add('mb-4');
        standingsDiv.innerHTML = `
            <h2 class="text-center">Classifica finale</h2>
            <table class="table-bordered table-sm styled-table">
                <thead>
                    <tr>
                        <th>Squadra</th>
                        <th>Punti</th>
                        <th>Punteggio Totale</th>
                    </tr>
                </thead>
                <tbody>
                    ${Object.entries(standings).map(([teamName, teamData]) => `
                        <tr>
                            <td>${teamName}</td>
                            <td>${teamData.points}</td>
                            <td>${teamData.total}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
        mainTag.prepend(standingsDiv);
    }

    function renderResults(torneo) {
        // Funzione per trovare il team di un giocatore
        const findTeamByUsername = (username) => {
            for (const [teamName, members] of Object.entries(TeamsTournament)) {
                if (members.includes(username)) {
                    return teamName;
                }
            }
            return null;
        };

        // Struttura per salvare i team per ogni partita
        const partiteTeams = torneo.map((partita) => {
            const teamsPartita = {};

            // Analizza ogni giocatore della partita
            Object.values(partita).forEach((player) => {
                const team = findTeamByUsername(player.username);
                if (team) {
                    if (!teamsPartita[team]) {
                        teamsPartita[team] = [];
                    }
                    teamsPartita[team].push(player.username);
                }
            });

            return teamsPartita;
        });

        // Generazione delle tabelle per ogni partita
        partiteTeams.forEach((teamsPartita, index) => {
            const teamNames = Object.keys(teamsPartita);
            if (teamNames.length < 2) {
                console.error(`Partita ${index + 1}: dati incompleti per determinare i team.`);
                return;
            }

            const matchDiv = document.createElement('div');
            matchDiv.id = "MainBlock";
            matchDiv.classList.add('mb-4');

            const team1 = teamNames[0];
            const team2 = teamNames[1];

            const matchInfo = `Match ${index + 1}: ${team1} vs ${team2}`;
            const teamTotals = {
                [team1]: 0,
                [team2]: 0,
            };

            // Calcola i punteggi totali per i team
            Object.values(torneo[index]).forEach((player) => {
                const team = findTeamByUsername(player.username);
                if (team && teamTotals[team] !== undefined) {
                    const lastSession = player.sessioni[player.sessioni.length - 1];
                    teamTotals[team] += lastSession.punteggioSessione;
                }
            });

            let winnerInfo = "";
            if (teamTotals[team1] > teamTotals[team2]) {
                winnerInfo = `<strong>Vincitore: ${team1}</strong>`;
            } else if (teamTotals[team1] < teamTotals[team2]) {
                winnerInfo = `<strong>Vincitore: ${team2}</strong>`;
            } else {
                winnerInfo = `<strong>Pareggio!</strong>`;
            }

            matchDiv.innerHTML = `
            <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                ${matchInfo}
            </div>
            <div class="container mt-3">
                <table class="table-bordered table-sm styled-table">
                    <thead>
                        <tr>
                            <th rowspan="2">Giocatore</th>
                            ${Array.from({ length: 10 }, (_, roundIndex) => `<th colspan="2">Frame ${roundIndex + 1}</th>`).join('')}
                        </tr>
                        <tr>
                            ${Array.from({ length: 10 }, () => `<th>T. 1</th><th>T. 2</th>`).join('')}
                        </tr>
                    </thead>
                    <tbody>
                        ${Object.values(torneo[index]).map((player) => {
                const sessions = player.sessioni;
                return `
                            <tr>
                                <td>${player.username}</td>
                                ${sessions.map((session, index) => `
                                    <td colspan="2" style="font-weight: bolder;">${session.punteggioSessione}</td>
                                `).join('')}
                            </tr>
                            <tr>
                                <td></td>
                                ${sessions.map((session, index) => `
                                    <td>${session.lanci[0].punteggio === 10 ? 'X' : session.lanci[0].punteggio}</td>
                                    <td>
                                        ${session.lanci[0].punteggio === 10 ? '' : (session.lanci[0].punteggio + session.lanci[1].punteggio === 10 ? '/' : session.lanci[1].punteggio)}
                                    </td>
                                `).join('')}
                            </tr>
                        `;
            }).join('')}
                    </tbody>
                </table>
                <div class="mt-3 mb-3">
                    <strong>Totale ${team1}: ${teamTotals[team1]}</strong><br>
                    <strong>Totale ${team2}: ${teamTotals[team2]}</strong><br>
                    ${winnerInfo}
                </div>
            </div>
            `;

            standingFinale[team1].punteggioTotale += teamTotals[team1];
            standingFinale[team2].punteggioTotale += teamTotals[team2];


            if (teamTotals[team1] > teamTotals[team2]) {

                standingFinale[team1].punti += 3;

            } else if (teamTotals[team1] < teamTotals[team2]) {

                standingFinale[team2].punti += 3;

            } else {

                standingFinale[team1].punti += 1;
                standingFinale[team2].punti += 1;;
            }

            mainTag.appendChild(matchDiv);
        });
    }

    renderResults(torneo);
    const standings = calculateStandings();
    renderStandings(standings);
});

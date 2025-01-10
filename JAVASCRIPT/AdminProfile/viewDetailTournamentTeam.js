document.addEventListener('DOMContentLoaded', function() {
    var infoTorneo = {
        "nome": "CiccioCup",
        "data": "01/01/2021",
        "tipo": "Teams",
        "numElementiTeam": 3,
        "teams": [
            { "nome": "Team1", "players": ["Alberto 1", "Mario 2", "Luigi 3"] },
            { "nome": "Team2", "players": ["Luca 4", "Giovanni 5", "Paolo 6"] },
            { "nome": "Team3", "players": ["Stefano 7", "Andrea 8", "Giuseppe 9"] },
            { "nome": "Team4", "players": ["Giorgio 10", "Antonio 11", "Francesco 12"] }
        ]
    };

    var mainTag = document.querySelector("main");

    function getUrlParams() {
        const params = {};
        const queryString = window.location.search.substring(1);
        const regex = /([^&=]+)=([^&]*)/g;
        let m;
        while (m = regex.exec(queryString)) {
            params[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
        }
        return params;
    }

    // Ottieni i parametri dell'URL
    const urlParams = getUrlParams();
    const isUser = urlParams.type === 'user';
    const sidebar = document.getElementById('mySidebar');

    if (isUser) {

        sidebar.innerHTML = `
            <a href="#" class="closebtn" id="closebtn">&times;</a>
            <a href="../UserProfile/cardAndSubscription.php" class="sidebarField">Carte e abbonamenti</a>
            <a href="../UserProfile/historyMatchPage.php" class="sidebarField">Storico partite</a>
            <a href="../UserProfile/historyTournaments.php" class="sidebarField">Storico tornei</a>
            <a href="../UserProfile/matchPage.php" class="sidebarField">Partite</a>
            <a href="../UserProfile/userpage.php" class="sidebarField">Profilo</a>
            <a href="../UserProfile/searchPage.php" class="sidebarField">Cerca</a>    
            <a href="../Statistics/generalStatistic.php?type=user" class="sidebarField">Statistiche generali</a>
            <a href="../../PHP/Utils/Logout.php" class="sidebarField">Logout</a>
        `;

    }

    function generateScores() {
        let scores = [];
        for (let round = 1; round <= 10; round++) {
            let time1 = Math.floor(Math.random() * 11);
            let time2 = 0;
            let total = 0;
            if (time1 === 10) { // Strike
                time2 = '';
                total = 10;
            } else {
                time2 = Math.floor(Math.random() * (11 - time1));
                if (time1 + time2 === 10) { // Spare
                    total = 10;
                } else {
                    total = time1 + time2;
                }
            }
            scores.push({ time1, time2, total });
        }

        // Gestione dei lanci extra nell'ultimo frame
        let lastFrame = scores[9];
        if (lastFrame.time1 === 10) { // Strike
            lastFrame.extra1 = Math.floor(Math.random() * 11);
            lastFrame.extra2 = Math.floor(Math.random() * 11);
        } else if (lastFrame.time1 + lastFrame.time2 === 10) { // Spare
            lastFrame.extra1 = Math.floor(Math.random() * 11);
        }

        return scores;
    }

    function calculateTotal(scores) {
        let cumulativeTotal = 0;
        let totalScores = [];
        for (let i = 0; i < scores.length; i++) {
            if (scores[i].time1 === 10) { // Strike
                cumulativeTotal += 10 + (scores[i + 1] ? scores[i + 1].total : 0) + (scores[i + 2] ? scores[i + 2].total : 0);
            } else if (scores[i].time1 + scores[i].time2 === 10) { // Spare
                cumulativeTotal += 10 + (scores[i + 1] ? scores[i + 1].time1 : 0);
            } else {
                cumulativeTotal += scores[i].total;
            }
            totalScores.push(cumulativeTotal);
        }
        return totalScores;
    }

    function simulateMatch(team1, team2) {
        let team1Scores = team1.players.map(player => ({ name: player, scores: generateScores() }));
        let team2Scores = team2.players.map(player => ({ name: player, scores: generateScores() }));

        let team1Total = team1Scores.reduce((sum, player) => sum + calculateTotal(player.scores).pop(), 0);
        let team2Total = team2Scores.reduce((sum, player) => sum + calculateTotal(player.scores).pop(), 0);

        let result = {
            team1: { name: team1.nome, total: team1Total, players: team1Scores },
            team2: { name: team2.nome, total: team2Total, players: team2Scores },
            winner: team1Total > team2Total ? team1.nome : (team1Total < team2Total ? team2.nome : 'Draw')
        };

        return result;
    }

    function simulateTournament(teams) {
        let results = [];
        for (let i = 0; i < teams.length; i++) {
            for (let j = i + 1; j < teams.length; j++) {
                results.push(simulateMatch(teams[i], teams[j]));
            }
        }
        return results;
    }

    function calculateStandings(results) {
        let standings = {};
        results.forEach(result => {
            if (!standings[result.team1.name]) standings[result.team1.name] = { points: 0, total: 0 };
            if (!standings[result.team2.name]) standings[result.team2.name] = { points: 0, total: 0 };

            standings[result.team1.name].total += result.team1.total;
            standings[result.team2.name].total += result.team2.total;

            if (result.winner === 'Draw') {
                standings[result.team1.name].points += 1;
                standings[result.team2.name].points += 1;
            } else {
                standings[result.winner].points += 2;
            }
        });

        return Object.entries(standings).sort((a, b) => b[1].points - a[1].points);
    }

    function renderStandings(standings) {
        let standingsDiv = document.createElement('div');
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
                    ${standings.map(team => `
                        <tr>
                            <td>${team[0]}</td>
                            <td>${team[1].points}</td>
                            <td>${team[1].total}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
        mainTag.prepend(standingsDiv);
    }


    function renderResults(results) {
        mainTag.innerHTML = "";
        results.forEach((result, index) => {
            let matchDiv = document.createElement('div');
            matchDiv.id = "MainBlock"; // Imposta l'ID come "MainBlock"
            matchDiv.classList.add('mb-4'); // Aggiunge la classe per il margine inferiore
            matchDiv.innerHTML = `
                <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                    Match ${index + 1}: ${result.team1.name} vs ${result.team2.name}
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
                            ${result.team1.players.concat(result.team2.players).map(player => {
                                let totalScores = calculateTotal(player.scores);
                                return `
                                    <tr>
                                        <td>${player.name}</td>
                                        ${totalScores.map((total, index) => `
                                            <td colspan="2" style="font-weight: bolder;">${total}</td>
                                        `).join('')}
                                    </tr>
                                    <tr>
                                        <td></td>
                                        ${player.scores.map((score, index) => `
                                            <td>${score.time1 === 10 ? 'X' : score.time1}</td>
                                            <td>
                                                ${score.time1 === 10 ? '' : (index === 9 && score.time1 + score.time2 === 10 ? '' : (score.time1 + score.time2 === 10 ? '/' : score.time2))}
                                                ${index === 9 ? `
                                                    ${score.time1 === 10 ? `
                                                        <table>
                                                            <tr>
                                                                <td>${score.extra1}</td>
                                                                <td>${score.extra2}</td>
                                                            </tr>
                                                        </table>
                                                    ` : (score.time1 + score.time2 === 10 ? `
                                                        <table>
                                                            <tr>
                                                                <td>/</td>
                                                                <td>${score.extra1}</td>
                                                            </tr>
                                                        </table>
                                                    ` : '')}
                                                ` : ''}
                                            </td>
                                        `).join('')}
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                    <div class="mt-3 mb-3">
                        <strong>Totale ${result.team1.name}: ${result.team1.total}</strong><br>
                        <strong>Totale ${result.team2.name}: ${result.team2.total}</strong><br>
                        <strong>Vincitore: ${result.winner}</strong>
                    </div>
                </div>
            `;
            mainTag.appendChild(matchDiv);
        });
    }

    let results = simulateTournament(infoTorneo.teams);
    renderResults(results);
    let standings = calculateStandings(results);
    renderStandings(standings);
});
async function downloadTorneo(idTorneo) {
    const response = await fetch('../../PHP/Torneo/ScaricaTorneoSingolo.php', {
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

document.addEventListener('DOMContentLoaded', async function() {
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
    const idTorneo = urlParams.idTorneo;
    const isUser = urlParams.type === 'user';
    const sidebar = document.getElementById('mySidebar');

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

    const mainTag = document.querySelector("main");

    // Ottieni i dati del torneo
    const torneo = await downloadTorneo(idTorneo);

    // Funzione per simulare i giochi e generare la classifica finale
    function simulateGames(torneo) {
        mainTag.innerHTML = ""; // Reset della pagina

        // Inizializza l'array dei punti totali per i giocatori
        let totalPoints = [];
        let rankingPoints = []; // Punti di classifica (per determinare la posizione finale)

        for (let i = 0; i < torneo.length; i++) {
            let match = torneo[i];
            let users = Object.values(match);

            // Crea il blocco per il match
            var matchDiv = document.createElement("div");
            matchDiv.id = "MainBlock";
            matchDiv.className = "col-9 flex-column justify-content-center align-items-center mb-3 pb-4";
            matchDiv.innerHTML = `
                <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                    Partita ${i + 1}
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
                            ${users.map(player => {
                                 let sessions = player.sessioni;
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
                                                ${session.lanci[0].punteggio === 10 ? '' : (index === 9 && session.lanci[0].punteggio + session.lanci[1].punteggio === 10 ? '' : (session.lanci[0].punteggio + session.lanci[1].punteggio === 10 ? '/' : session.lanci[1].punteggio))}
                                                ${index === 9 ? `
                                                    ${session.lanci[0].punteggio === 10 ? `
                                                        <table>
                                                            <tr>
                                                                <td>${session.lanci[2].punteggio}</td>
                                                                <td>${session.lanci[3].punteggio}</td>
                                                            </tr>
                                                        </table>
                                                    ` : (session.lanci[0].punteggio + session.lanci[1].punteggio === 10 ? `
                                                        <table>
                                                            <tr>
                                                                <td>/</td>
                                                                <td>${session.lanci[2].punteggio}</td>
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
                </div>
            `;
            mainTag.appendChild(matchDiv);

            // Aggiungi i punti della partita al punteggio totale dei giocatori
            let matchPoints = [];

            users.forEach(player => {
                let totalSessionScore = player.sessioni[9].punteggioSessione; // Punteggio finale della partita
                let playerInTotal = totalPoints.find(p => p.name === player.username);
                if (playerInTotal) {
                    // Aggiungi il punteggio della partita al totale
                    playerInTotal.total += totalSessionScore;
                } else {
                    totalPoints.push({ name: player.username, total: totalSessionScore });
                }

                // Calcola i punti di classifica
                matchPoints.push({
                    name: player.username,
                    total: totalSessionScore
                });
            });

            // Ordina i giocatori per punti della partita corrente
            matchPoints.sort((a, b) => b.total - a.total);

            // Assegna i punti di classifica in base alla posizione
            let points = [10, 8, 6, 3, 1, 1, 1, 1, 1];
            matchPoints.forEach((player, index) => {
                // Trova il giocatore e aggiungi i punti di classifica
                let playerInRanking = rankingPoints.find(p => p.name === player.name);
                if (playerInRanking) {
                    playerInRanking.points += points[index] || 1;
                } else {
                    rankingPoints.push({ name: player.name, points: points[index] || 1 });
                }
            });
        }

        // Ordina i giocatori per punteggio totale
        totalPoints.sort((a, b) => b.total - a.total);

        // Ordina i giocatori per punti di classifica
        rankingPoints.sort((a, b) => b.points - a.points);

        // Crea la tabella di classifica finale
        var rankingTableDiv = document.createElement("div");
        rankingTableDiv.className = "ranking-table";
        rankingTableDiv.innerHTML = `
            <h3 style="text-align: center;">Classifica Finale</h3>
            <div class="ranking-container">
                <table class="table-bordered table-sm styled-table">
                    <thead>
                        <tr>
                            <th>Posizione</th>
                            <th>Giocatore</th>
                            <th>Punti di Classifica</th>
                            <th>Punti Totali</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${rankingPoints.map((player, index) => `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${player.name}</td>
                                <td>${player.points}</td>
                                <td>${totalPoints.find(p => p.name === player.name).total}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
        mainTag.insertBefore(rankingTableDiv, mainTag.firstChild);
    }

    // Esegui la simulazione delle partite
    simulateGames(torneo);
});

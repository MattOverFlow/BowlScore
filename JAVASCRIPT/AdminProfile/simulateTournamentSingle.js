document.addEventListener('DOMContentLoaded', function() {
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

    var mainTag = document.querySelector("main");

    var nMatch = 4;

    var players = [
        { name: "Alberto 1" },
        { name: "Mario 2" },
        { name: "Luigi 3" },
        { name: "Luca 4" },
        { name: "Giovanni 5" },
        { name: "Paolo 6" },
        { name: "Francesco 7" },
        { name: "Andrea 8" },
        { name: "Giuseppe 9" }
    ];

    function simulateGames() {
        mainTag.innerHTML = "";

        let totalPoints = players.map(player => ({ name: player.name, total: 0, points: 0 }));

        for (let i = 1; i <= nMatch; i++) {
            let match = {
                players: players.map(player => ({
                    name: player.name,
                    scores: generateScores()
                }))
            };
            var matchDiv = document.createElement("div");
            matchDiv.id = "MainBlock"; // Imposta l'ID come "MainBlock"
            matchDiv.className = "col-9 flex-column justify-content-center align-items-center mb-3 pb-4";
            matchDiv.innerHTML = `
                <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                    Match ${i}
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
                            ${match.players.map(player => {
                                let totalScores = calculateTotal(player.scores);
                                let playerTotal = totalScores[totalScores.length - 1];
                                totalPoints.find(p => p.name === player.name).total += playerTotal;
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
                </div>
            `;
            mainTag.appendChild(matchDiv);

            // Ordina i giocatori per punti totali della partita corrente
            let matchPoints = match.players.map(player => ({
                name: player.name,
                total: calculateTotal(player.scores).pop()
            }));
            matchPoints.sort((a, b) => b.total - a.total);

            // Assegna i punti in base alla posizione nella partita corrente
            let points = [10, 8, 6, 3, 1, 1, 1, 1, 1];
            matchPoints.forEach((player, index) => {
                totalPoints.find(p => p.name === player.name).points += points[index] || 1;
            });
        }

        // Ordina i giocatori per punti totali
        totalPoints.sort((a, b) => b.points - a.points);

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
                            <th>Punti</th>
                            <th>Punti Bowling Totali</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${totalPoints.map((player, index) => `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${player.name}</td>
                                <td>${player.points}</td>
                                <td>${player.total}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
        mainTag.insertBefore(rankingTableDiv, mainTag.firstChild);
    }

    // Aggiungi event listener al pulsante
    document.getElementById('simulateGameButton').addEventListener('click', function() {
        simulateGames();
    });
});
document.addEventListener('DOMContentLoaded', async function () {
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

    async function scaricaPartita(idPartita) {
        const response = await fetch('../../PHP/Partite/ScaricaPartita.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `idPartita=${idPartita}`,
        });

        const data = await response.json();
        console.log(data);
        return data;
    }

    const urlParams = getUrlParams();
    const idPartita = urlParams.matchId;

    const match = await scaricaPartita(idPartita);

    const mainTag = document.getElementById("mainMatch");

    function simulateGame(match) {
        mainTag.innerHTML = "";

        let users = Object.values(match);

        var matchDiv = document.createElement("div");
        matchDiv.id = "MainBlock";
        matchDiv.className = "col-9 flex-column justify-content-center align-items-center mb-3 pb-4";
        matchDiv.innerHTML = `
            <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                Partita
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
                                    ${sessions.map(session => `
                                        <td colspan="2" style="font-weight: bolder;">${session.punteggioSessione}</td>
                                    `).join('')}
                                </tr>
                                <tr>
                                    <td></td>
                                    ${sessions.map((session, index) => `
                                        <td>${session.lanci[0].punteggio === 10 ? 'X' : session.lanci[0].punteggio}</td>
                                        <td>
                                            ${session.lanci[0].punteggio === 10 ? '' : (index === 9 && session.lanci[0].punteggio + session.lanci[1].punteggio === 10 ? '' : (session.lanci[0].punteggio + session.lanci[1].punteggio === 10 ? '/' : session.lanci[1].punteggio))}
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

        // Calcola e mostra la classifica
        showRanking(users);
    }

    function showRanking(users) {
        // Prendi i punteggi dell'ultima sessione
        const totalPoints = users.map(user => {
            const lastSession = user.sessioni[user.sessioni.length - 1];
            return {
                name: user.username,
                total: lastSession.punteggioSessione,
            };
        });
    
        // Ordina i giocatori per punteggio decrescente
        totalPoints.sort((a, b) => b.total - a.total);
    
        // Crea dinamicamente i podi in base al numero di partecipanti
        const rankingDiv = document.createElement("div");
        rankingDiv.className = "ranking";
        rankingDiv.innerHTML = `
            <div class="ranking-container mt-5 mb-5">
                ${totalPoints[1] ? `
                    <div class="second-place">
                        <div class="name">${totalPoints[1].name}</div>
                        <div class="points">${totalPoints[1].total} p.</div>
                    </div>
                ` : ''}
                ${totalPoints[0] ? `
                    <div class="first-place">
                        <div class="name">${totalPoints[0].name}</div>
                        <div class="points">${totalPoints[0].total} p.</div>
                    </div>
                ` : ''}
                ${totalPoints[2] ? `
                    <div class="third-place">
                        <div class="name">${totalPoints[2].name}</div>
                        <div class="points">${totalPoints[2].total} p.</div>
                    </div>
                ` : ''}
            </div>
        `;
        mainTag.insertBefore(rankingDiv, mainTag.firstChild);
    }

    simulateGame(match);
});

document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const userType = urlParams.get('type');
    const sidebar = document.getElementById('mySidebar');

    if (userType === 'admin') {
        sidebar.innerHTML += `
            <a href="../AdminProfile/createGame.php" class="sidebarField">Crea partita</a>
            <a href="../AdminProfile/createTournament.php" class="sidebarField">Crea torneo</a>
            <a href="../AdminProfile/createTeam.php" class="sidebarField">Crea team</a>
            <a href="../AdminProfile/manageTournaments.php" class="sidebarField">Gestisci tornei</a>
            <a href="../AdminProfile/manageTeams.php" class="sidebarField">Gestisci teams</a>
            <a href="../Statistics/generalStatistic.php?type=admin" class="sidebarField">Statistiche generali</a>
            <a href="../UserProfile/searchPage.php?type=admin" class="sidebarField">Cerca giocatori</a>                    
            <a href="../../PHP/Utils/Logout.php" class="sidebarField">Logout</a>
        `;
    } else {
        sidebar.innerHTML += `
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

    const main = document.querySelector('main');
    if (main) {
        main.innerHTML = generateStatisticsHTML();
    }
});

function generateStatisticsHTML() {
    return `
        ${generateTableHTML('Classifica 10 giocatori con punteggio massimo', generateFakeData(10))}
        ${generateTableHTML('Classifica 10 giocatori migliori del mese', generateFakeData(10))}
        ${generateTableHTML('Classifica 10 giocatori migliori della settimana', generateFakeData(10))}
        ${generateTableHTML('Classifica 10 giocatori con maggior numero di vittorie', generateFakeData(10))}
        ${generateTableHTML('Classifica 10 giocatori con maggior numero di vittorie nel mese', generateFakeData(10))}
        ${generateTableHTML('Classifica 10 giocatori con maggior numero di vittorie della settimana', generateFakeData(10))}
        ${generateTableHTML('Lista con tutte le persone con partita perfetta', generateFakeData(10))}
        ${generateTableHTML('Classifica 10 giocatori con miglior media punti', generateFakeData(10))}
        ${generateTableHTML('10 squadre tornei più vittoriose', generateFakeData(10))}
        ${generateTableHTML('10 giocatori più vittoriosi nei tornei singoli', generateFakeData(10))}
        ${generateTableHTML('Strike Rate: 10 giocatori migliori', generateFakeData(10))}
        ${generateTableHTML('Spare Rate: 10 giocatori migliori', generateFakeData(10))}
        ${generateTableHTML('Pinfall Totale: 10 giocatori migliori', generateFakeData(10))}
        ${generateTableHTML('Gutter Balls: 10 giocatori peggiori', generateFakeData(10))}
        ${generateTableHTML('Miglioramenti del Punteggio Medio: 10 migliori giocatori', generateFakeData(10))}
        ${generateTableHTML('Tendenze di Strike e Spare: 10 migliori giocatori', generateFakeData(10))}
    `;
}

function generateTableHTML(title, data) {
    let tableRows = data.map((row, index) => `<tr><td>${index + 1}</td>${row.map(cell => `<td>${cell}</td>`).join('')}</tr>`).join('');
    return `
        <div class="col-9 flex-column justify-content-center align-items-center mb-3 pb-4">
            <div class="mb-3 d-flex justify-content-center align-items-center w-100" style="font-size: 1.5em; font-weight: bold;">
                ${title}
            </div>
            <table class="table">
                <thead>
                    <tr><th>Posizione</th>${data[0].map(cell => `<th>${cell}</th>`).join('')}</tr>
                </thead>
                <tbody>
                    ${tableRows}
                </tbody>
            </table>
        </div>
    `;
}

function generateFakeData(rows) {
    const data = [];
    for (let i = 0; i < rows; i++) {
        data.push([`Giocatore ${i + 1}`, Math.floor(Math.random() * 300)]);
    }
    return data;
}
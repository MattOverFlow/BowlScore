document.addEventListener("DOMContentLoaded",async function() {
    const urlParams = new URLSearchParams(window.location.search);
    const userType = urlParams.get('type');
    const sidebar = document.getElementById('mySidebar');

    if (userType === 'admin') {
        sidebar.innerHTML += `
            <a href="../AdminProfile/createGame.php" class="sidebarField">Crea partita</a>
            <a href="../AdminProfile/createTournament.php" class="sidebarField">Crea torneo</a>
            <a href="../AdminProfile/createTeam.php" class="sidebarField">Crea team</a>
            <a href="../AdminProfile/manageTournaments.php" class="sidebarField">Storico tornei</a>
            <a href="../AdminProfile/manageTeams.php" class="sidebarField">Gestione teams</a>
            <a href="../Statistics/generalStatistic.php?type=admin" class="sidebarField">Classifiche generali</a>
            <a href="../UserProfile/searchPage.php?type=admin" class="sidebarField">Cerca utenti</a>                    
            <a href="../../PHP/Utils/Logout.php" class="sidebarField">Logout</a>
        `;
    } else {
        sidebar.innerHTML += `
            <a href="../UserProfile/cardAndSubscription.php" class="sidebarField">Carta e abbonamenti</a>
            <a href="../UserProfile/historyMatchPage.php" class="sidebarField">Storico partite</a>
            <a href="../UserProfile/historyTournaments.php" class="sidebarField">Storico tornei</a>
            <a href="../UserProfile/userpage.php" class="sidebarField">Profilo</a>
            <a href="../UserProfile/searchPage.php" class="sidebarField">Cerca utenti</a>    
            <a href="../Statistics/generalStatistic.php?type=user" class="sidebarField">Classifiche generali</a>
            <a href="../../PHP/Utils/Logout.php" class="sidebarField">Logout</a>
        `;
    }

    const main = document.querySelector('main');
    if (main) {
        main.innerHTML = await generateStatisticsHTML();
    }
});

async function generateStatisticsHTML() {
    return `
        ${generateTableHTML('Classifica 10 giocatori con maggior numero di vittorie',await mostWinStatistic("all"))}
        ${generateTableHTML('Classifica 10 giocatori con maggior numero di vittorie nel mese', await mostWinStatistic("month"))}
        ${generateTableHTML('Classifica 10 giocatori con maggior numero di vittorie della settimana', await mostWinStatistic("week"))}
        ${generateTableHTML('Classifica 10 giocatori con miglior media punti', await bestAverageStatistic())}
        ${generateTableHTML('Strike Rate: 10 giocatori migliori',await bestStrikeRateStatistic())}
        ${generateTableHTML('Spare Rate: 10 giocatori migliori', await bestSpareRateStatistic())}
        ${generateTableHTML('Pinfall Medio: 10 giocatori migliori',await bestPinfallStatistic())}
    `;
}

function generateTableHTML(title, data) {

    let tableRows = Object.entries(data).map(([username, value], index) => {
        return `<tr><td>${index + 1}</td><td>${username}</td><td>${value}</td></tr>`;
    }).join('');

    return `
        <div class="col-9 flex-column justify-content-center align-items-center mb-3 pb-4">
            <div class="mb-3 d-flex justify-content-center align-items-center w-100" style="font-size: 1.5em; font-weight: bold;">
                ${title}
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Posizione</th>
                        <th>Username</th>
                        <th>Valore</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableRows}
                </tbody>
            </table>
        </div>
    `;
}



async function mostWinStatistic(period){
    const response = await fetch('../../PHP/Statistiche/winStatistic.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `period=${period}`,
    });

    var data = await response.json();
    console.log(data);
    return data;
}


async function bestAverageStatistic(){
    const response = await fetch('../../PHP/Statistiche/bestAverageStatistic.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
    });
    var data = await response.json();
    console.log(data);
    return data;
}

async function bestStrikeRateStatistic(){
    const response = await fetch('../../PHP/Statistiche/bestStrikeRates.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
    });
    var data = await response.json();
    console.log(data);
    return data;
}

async function bestSpareRateStatistic(){
    const response = await fetch('../../PHP/Statistiche/bestSpareRates.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
    });
    var data = await response.json();
    console.log(data);
    return data;
}

async function bestPinfallStatistic(){
    const response = await fetch('../../PHP/Statistiche/bestPinfallRate.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
    });
    var data = await response.json();
    console.log(data);
    return data;
}






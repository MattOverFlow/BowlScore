document.addEventListener("DOMContentLoaded", function() {
    
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
    const isAdmin = urlParams.type === 'admin';

    const sidebar = document.getElementById('mySidebar');

    if(isAdmin) {
        sidebar.innerHTML = `
            <a href="../AdminProfile/createGame.php" class="sidebarField">Crea partita</a>
            <a href="../AdminProfile/createTournament.php" class="sidebarField">Crea torneo</a>
            <a href="../AdminProfile/createTeam.php" class="sidebarField">Crea team</a>
            <a href="../AdminProfile/manageTournaments.php" class="sidebarField">Gestisci tornei</a>
            <a href="../AdminProfile/manageTeams.php" class="sidebarField">Gestisci teams</a>
            <a href="../Statistics/generalStatistic.php?type=admin" class="sidebarField">Statistiche generali</a>
            <a href="../UserProfile/searchPage.php?type=admin" class="sidebarField">Cerca giocatori</a>                    
            <a href="../../PHP/Utils/Logout.php" class="sidebarField">Logout</a>
        `;
    }
});
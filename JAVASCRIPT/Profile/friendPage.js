document.addEventListener("DOMContentLoaded", function() {
    var statistics = {
        "averageScore": 150,
        "strikeRate": "30%",
        "spareRate": "40%",
        "highGame": 200,
        "highSeries": 550,
        "firstBallAverage": 8.5,
        "cleanGame": 5,
        "cleanFramePercentage": "50%",
        "scoreDifferential": 10
    };

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
    var followButton = document.getElementById("followButtonNotFollow");

    if(isAdmin) {
        followButton.style.display = "none";
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
            <a href="cardAndSubscription.php" class="sidebarField">Carte e abbonamenti</a>
            <a href="historyMatchPage.php" class="sidebarField">Storico partite</a>
            <a href="historyTournaments.php" class="sidebarField">Storico tornei</a>
            <a href="matchPage.php" class="sidebarField">Partite</a>
            <a href="userpage.php" class="sidebarField">Profilo</a>
            <a href="searchPage.php" class="sidebarField">Cerca</a>    
            <a href="../Statistics/generalStatistic.php?type=user" class="sidebarField">Statistiche generali</a>
            <a href="../../PHP/Utils/Logout.php" class="sidebarField">Logout</a>
        `;

    document.getElementById("averageScore").innerHTML = statistics.averageScore;
    document.getElementById("strikeRate").innerHTML = statistics.strikeRate;
    document.getElementById("spareRate").innerHTML = statistics.spareRate;
    document.getElementById("highGame").innerHTML = statistics.highGame;
    document.getElementById("highSeries").innerHTML = statistics.highSeries;
    document.getElementById("firstBallAverage").innerHTML = statistics.firstBallAverage;
    document.getElementById("cleanGame").innerHTML = statistics.cleanGame;
    document.getElementById("cleanFramePercentage").innerHTML = statistics.cleanFramePercentage;
    document.getElementById("scoreDifferential").innerHTML = statistics.scoreDifferential;
}
});
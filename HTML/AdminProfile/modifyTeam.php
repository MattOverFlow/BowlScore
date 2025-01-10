<?php
include '../../PHP/Utils/auth_request.php';
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>BowlScore - Gestione Teams</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/d9b18796bb.js" crossorigin="anonymous"></script>
    <link href="../../CSS/Access/AccessStyleBase.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="../../CSS/Profile/userpage.css">
</head>

<body>
    <div class="container-fluid p-0 overflow-x-hidden overflow-y-auto" id="body_container">
        <header id="profile_header">
            <div id="mySidebar" class="sidebar justify-content-end">
                <a href="#" class="closebtn" id="closebtn">&times;</a>
                <a href="createGame.php" class="sidebarField">Crea partita</a>
                <a href="createTournament.php" class="sidebarField">Crea torneo</a>
                <a href="createTeam.php" class="sidebarField">Crea team</a>
                <a href="manageTournaments.php" class="sidebarField">Gestisci tornei</a>
                <a href="manageTeams.php" class="sidebarField">Gestisci teams</a>
                <a href="../Statistics/generalStatistic.php?type=admin" class="sidebarField">Statistiche generali</a>
                <a href="../UserProfile/searchPage.php?type=admin" class="sidebarField">Cerca giocatori</a>                    
                <a href="../../PHP/Utils/Logout.php" class="sidebarField">Logout</a>
            </div>
            <button class="openbtn" id="openbtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="48.6" height="32" viewBox="0 0 76 50" fill="none">
                    <path d="M3 2.71213H73" stroke="#000000" stroke-width="5" stroke-linecap="round" />
                    <path d="M3 46.5505H73" stroke="#000000" stroke-width="5" stroke-linecap="round" />
                    <path d="M3 24.6313H73" stroke="#000000" stroke-width="5" stroke-linecap="round" />
                </svg>
            </button>
        </header>
        <main class="pt-3 d-flex flex-column align-items-center">
        </main>
        <!-- Messaggio di errore -->
        <div id="error-message" style="display: none; color: white; background-color: red; padding: 10px; border-radius: 5px; margin: 10px 0; text-align: center;">
        </div>

        <!-- Messaggio popup -->
        <div id="popup-message" style="display: none; color: white; background-color: green; padding: 10px; border-radius: 5px; margin: 10px 0; text-align: center;">
        </div>
    </div>
</body>
<script src="../../JAVASCRIPT\Utils\sidenav.js" type="module"></script>
<script src="../../JAVASCRIPT/adminProfile/modifyTeam.js"></script>
</html>
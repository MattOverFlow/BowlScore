<?php
include '../../PHP/Utils/auth_request.php';
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>BowlScore - Storico tornei</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/d9b18796bb.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="../../CSS/Profile/userpage.css">
    <link href="../../CSS/Access/AccessStyleBase.css" rel="stylesheet" type="text/css">
    <link href="../../CSS/Search/SearchStyle.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="container-fluid p-0 overflow-x-hidden overflow-y-auto" id="body_container">
        <header id="profile_header">
            <div id="mySidebar" class="sidebar justify-content-end">
            <a href="#" class="closebtn" id="closebtn">&times;</a>
            <a href="createGame.php" class="sidebarField">Crea partita</a>
                <a href="createTournament.php" class="sidebarField">Crea torneo</a>
                <a href="createTeam.php" class="sidebarField">Crea team</a>
                <a href="manageTournaments.php" class="sidebarField">Storico tornei</a>
                <a href="manageTeams.php" class="sidebarField">Gestione teams</a>
                <a href="../Statistics/generalStatistic.php?type=admin" class="sidebarField">Classifiche generali</a>
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
        <div class="col-12 d-flex justify-content-center mt-4">
            <div class="col-9 d-flex flex-column justify-content-center align-items-center mb-3 p-4 border rounded shadow-sm bg-light">
                <div class="d-flex align-items-center w-100 mb-3">
                    <label for="searchInput" class="form-label me-3">Cerca torneo:</label>
                    <input class="input-grey-rounded form-control me-3" type="text" id="searchInput" placeholder="Cerca torneo...">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="toggleFilters" onclick="toggleFiltersVisibility()">
                        <label class="form-check-label" for="toggleFilters">Aggiungi filtri</label>
                    </div>
                </div>

                <!-- Contenitore dei filtri -->
                <div id="filtersContainer" class="col-7 mt-5" style="display: none;">
                    <div class="d-flex w-100 mb-3">
                        <label for="tournamentType" class="form-label mt-1 me-4">Tipo torneo:</label>
                        <select class="form-select form-select-sm w-50" id="tournamentType" onchange="toggleFilters()">
                            <option value="">Seleziona</option>
                            <option value="singolo">Singolo</option>
                            <option value="squadre">Squadre</option>
                        </select>
                    </div>

                    <!-- Filtri per il periodo -->
                    <div class="d-flex justify-content-between w-100 mb-3" id="dateFilters">
                        <div class="me-2 w-50">
                            <label for="startDate" class="form-label">Periodo inizio:</label>
                            <input type="date" class="form-control" id="startDate">
                        </div>
                        <div class="w-50">
                            <label for="endDate" class="form-label">Periodo fine:</label>
                            <input type="date" class="form-control" id="endDate">
                        </div>
                    </div>

                    <!-- Filtri per torneo squadre -->
                    <div class="w-100 mb-3" id="teamFilters" style="display: none;">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="me-2 w-50">
                                <label for="numTeams" class="form-label">Numero di team:</label>
                                <select class="form-control" id="numTeams">
                                    <option value="" disabled selected>Seleziona</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                            </div>
                            <div class="w-50">
                                <label for="teamSize" class="form-label">Dimensione dei team:</label>
                                <select class="form-control" id="teamSize">
                                    <option value="" disabled selected>Seleziona</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex mb-3 align-items-center">
                            <label for="teamName" class="form-label me-4 mt-2">Nome team:</label>
                            <input type="text" class="form-control input-grey-rounded" id="teamName">
                        </div>
                    </div>

                    <!-- Filtri per torneo singolo -->
                    <div class="w-100 mb-3" id="singleFilters" style="display: none;">
                        <label for="username" class="form-label">Cerca username:</label>
                        <input type="text" class="form-control input-grey-rounded" id="usernameField">
                    </div>
                </div>

                <button class="btn btn-primary w-50 mt-4" id="searchButton">Cerca</button>
            </div>
        </div>
        <main class="pt-3 d-flex flex-column align-items-center">
        </main>
    </div>
</body>
<script src="../../JAVASCRIPT/Utils/sidenav.js" type="module"></script>
<script src="../../JAVASCRIPT/AdminProfile/manageTournaments.js" type="module"></script>
<script>
    function toggleFiltersVisibility() {
        const filtersContainer = document.getElementById('filtersContainer');
        const toggleCheckbox = document.getElementById('toggleFilters');
        const searchButton = document.getElementById('searchButton');
        
        filtersContainer.style.display = toggleCheckbox.checked ? 'block' : 'none';
        if (toggleCheckbox.checked) {
        searchButton.innerHTML = 'Applica filtri';
        } else {
            searchButton.innerHTML = 'Cerca';
        }
    }

    function toggleFilters() {
        const tournamentType = document.getElementById('tournamentType').value;
        const teamFilters = document.getElementById('teamFilters');
        const singleFilters = document.getElementById('singleFilters');

        // Nascondi o mostra i filtri in base al tipo di torneo
        if (tournamentType === 'squadre') {
            teamFilters.style.display = 'block';
            singleFilters.style.display = 'none';
        } else if (tournamentType === 'singolo') {
            teamFilters.style.display = 'none';
            singleFilters.style.display = 'block';
        } else {
            teamFilters.style.display = 'none';
            singleFilters.style.display = 'none';
        }
    }
</script>
</html>
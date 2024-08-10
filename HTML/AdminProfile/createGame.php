<?php
include '../../PHP/Utils/auth_request.php';
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>BowlScore - Crea partita</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/d9b18796bb.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="../../CSS/Profile/userpage.css">
    <link href="../../CSS/Access/AccessStyleBase.css" rel="stylesheet" type="text/css">
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
                <a href="statisticPage.php" class="sidebarField">Statistiche generali</a>
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
            <div id="MainBlock" class="col-6 flex-column justify-content-center align-items-center mb-3 pb-4">
                <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                    Crea partita
                </div>
                <div class="col-12 d-flex flex-column justify-content-center align-items-center">
                    <div class="row w-100 mt-3">
                        <div class="col-2"></div>
                        <div class="col-4 d-flex justify-content-start align-items-center">
                            <label for="usernameHost">Host partita:</label>
                            <input type="text" id="usernameHost" name="usernameHost" class="form-control ml-2">
                        </div>
                        <div class="col-4 d-flex justify-content-end align-items-center">
                            <label for="participants">Partecipanti:</label>
                            <select id="participants" name="participants" class="form-control ml-2">
                                <?php for ($i = 2; $i <= 10; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-2"></div>
                    </div>
                    <div class="row w-100 mt-3">
                        <div class="col-2"></div>
                        <div class="col-4 d-flex justify-content-start align-items-center">
                            <label for="matches">Num match:</label>
                            <select id="matches" name="matches" class="form-control ml-2">
                                <?php for ($i = 1; $i <= 4; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-5 d-flex justify-content-start align-items-center">
                            <label for="visibility" style="padding-right: 40px;">Visibilità:</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="visibility" id="public" value="public" checked>
                                <label class="form-check-label" for="public">Pubblica</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="visibility" id="friends" value="friends">
                                <label class="form-check-label" for="friends">Amici</label>
                            </div>
                        </div>
                        <div class="col-2"></div>
                    </div>
                    
                    <div class="row w-100 mt-4">
                        <div class="col-12 d-flex justify-content-center">
                            <button type="button" class="btn btn-primary">Crea partita</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
<script src="../../JAVASCRIPT\Utils\sidenav.js" type="module"></script>
</html>
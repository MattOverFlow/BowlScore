<!DOCTYPE html>
<html lang="it">

<head>
    <title>BowlScore - Crea team</title>
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
                    Crea team
                </div>
                <div class="col-12 d-flex flex-column justify-content-center align-items-center">  
                    <div class="row w-100 mt-3">
                        <div class="col-6 d-flex justify-content-start align-items-center">
                            <label for="teamName">Nome del team:</label>
                            <input type="text" id="teamName" name="teamName" class="form-control ml-2">
                        </div>
                        <div class="col-6 d-flex justify-content-end align-items-center">
                            <label for="teamMembers">Numero di componenti:</label>
                            <select id="teamMembers" name="teamMembers" class="form-control ml-2">
                                <?php for ($i = 2; $i <= 4; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row w-100 mt-3">
                        <div class="col-12 d-flex flex-column justify-content-center align-items-center" id="teamMembersContainer">
                            <label for="member1">Username componente 1:</label>
                            <input type="text" id="member1" name="member1" class="form-control mb-2">
                            <label for="member2">Username componente 2:</label>
                            <input type="text" id="member2" name="member2" class="form-control mb-2">
                        </div>
                    </div>
                    <div class="row w-100 mt-4">
                        <div class="col-12 d-flex justify-content-center">
                            <button type="button" class="btn btn-primary">Crea team</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="../../JAVASCRIPT/Utils/sidenav.js" type="module"></script>
    <script src="../../JAVASCRIPT/AdminProfile/createTeam.js" type="module"></script>
</body>
</html>
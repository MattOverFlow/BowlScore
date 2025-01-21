<?php
    include '../../PHP/Utils/auth_request.php';
?>

<!DOCTYPE html>
<html lang="it">
<head>
        <meta charset="UTF-8">
        <title>BowlScore - Ricerca</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link href="../../CSS/Profile/userpage.css" rel="stylesheet" type="text/css">
        <link href="../../CSS/Access/AccessStyleBase.css" rel="stylesheet" type="text/css">
        <link href="../../CSS/Search/SearchStyle.css" rel="stylesheet" type="text/css">

    </head>
    <body>
        <div class="container-fluid p-0 overflow-x-hidden overflow-y-auto" id="body_container">
            <header id="profile_header">
                <div id="mySidebar" class="sidebar justify-content-end">
                    <a href="#" class="closebtn" id="closebtn">&times;</a>
                    <a href="cardAndSubscription.php" class="sidebarField">Carta e abbonamenti</a>
                    <a href="historyMatchPage.php" class="sidebarField">Storico partite</a>
                    <a href="historyTournaments.php" class="sidebarField">Storico tornei</a>
                    <a href="userpage.php" class="sidebarField">Profilo</a>
                    <a href="searchPage.php" class="sidebarField">Cerca utenti</a>
                    <a href="../Statistics/generalStatistic.php?type=user" class="sidebarField">Classifiche generali</a>                 
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
            <main class="col-12 pt-3 d-flex flex-column align-items-center">
                <div class="  col-4 d-flex align-items-start justify-content-center mt-4">
                    <h1>BowlScore</h1>
                </div>

                <div class=" col-6 d-flex align-items-center justify-content-center mt-4 px-3">
                    <label for ="searchInput" class="visually-hidden" >Cerca utente:</label>
                    <input class="input-grey-rounded" type="text" id="searchInput" placeholder="Cerca utente..." style="width: 84%;">
                </div>

                <div class=" col-6 d-flex flex-column align-items-center justify-content-center mt-4" id="searchList">
                    <div class=" col-12  d-flex align-items-center px-3">
                        <div>
                            <p class="pt-3 px-4 text-center" id="usernameLabel">Username non trovato</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
    <script src="../../JAVASCRIPT\Utils\sidenav.js" type="module"></script>
    <script src="../../JAVASCRIPT/Profile/searchPage.js" type="module"></script>
</html>




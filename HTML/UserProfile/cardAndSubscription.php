<?php
    if(isset($_COOKIE['token'])) {
        header('Location: ../../HTML/Profile/userpage.php');
    }
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>BowlScore - Carte e abbonamenti</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link href="../../CSS/Access/AccessStyleBase.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="../../CSS/Profile/userpage.css">
    </head>
    <body>
        <div class="container-fluid d-flex flex-column justify-content-center align-items-center">
            <header id="profile_header">
                <div id="mySidebar" class="sidebar justify-content-end">
                    <a href="#" class="closebtn" id="closebtn">&times;</a>
                    <a href="cardAndSubscription.php" class="sidebarField">Carte e abbonamenti</a>
                    <a href="historyMatchPage.php" class="sidebarField">Storico partite</a>
                    <a href="#" class="sidebarField">Storico tornei</a>
                    <a href="matchPage.php" class="sidebarField">Partite</a>
                    <a href="userpage.php" class="sidebarField">Profilo</a>
                    <a href="searchPage.php" class="sidebarField">Cerca</a>                    
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
            <div class="col-5">
                <div id="MainBlock" class="col-12 flex-column justify-content-center align-items-center mb-3 pb-4">
                    <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                        Gestione carta
                    </div>
                    <div class="d-flex flex-column justify-content-center align-items-center" id="manageCard">
                        </div>
                </div>
                <div id="MainBlock" class="col-12 flex-column justify-content-center align-items-center mb-3 pb-4">
                    <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                        Gestione abbonamento
                    </div>
                    <div class="d-flex flex-column justify-content-center align-items-center" id="manageSubscription">
                    </div>
                </div>
                <div id="MainBlock" class="col-12 flex-column justify-content-center align-items-center mb-3 pb-4">
                    <div id="BlockBanner" class="d-flex justify-content-center align-items-center w-100">
                        Pacchetti partite
                    </div>
                    <div class="d-flex flex-column justify-content-center align-items-center" id="manageMatchPack">
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
    <script src="../../JAVASCRIPT\Utils\sidenav.js" type="module"></script>
    <script src="../../JAVASCRIPT/Profile/cardAndSubscriptionJS.js"></script>
</html>
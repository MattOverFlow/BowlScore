<?php
    include '../../PHP/Utils/auth_request.php';

    include_once '../../PHP/Database/User.php';

    if(!isset($_GET['useridUtente'])){
        $users = listaFollowers($_SESSION['userid']);
        $username = $_SESSION['username'];
    }
    else{
        $users = listaFollowers($_GET['useridUtente']);
        $username = scaricaUtente($_GET['useridUtente'])['username'];
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title id="title"></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="../../CSS/Profile/FollowerFollowing.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="../../CSS/Profile/userpage.css">
    <link rel="stylesheet" type="text/css" href="../../CSS/Search/SearchStyle.css">
</head>
<body>
    <div class="container-fluid p-0 overflow-x-hidden overflow-y-auto">
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
            <div class="d-flex align-items-center justify-content-between mt-4">
                <p id="username"><?php echo $username;?></p>
            </div>
            <div class="col-4 d-flex flex-column align-items-center justify-content-center mt-4" id="usersList">
                <div class="col-6 d-flex align-items-center px-3">
                    <div>
                        <p class="pt-3 px-4 text-center" id="usernameLabel">Nessun utente</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
<script src="../../JAVASCRIPT\Utils\sidenav.js" type="module"></script>
<script src="../../JAVASCRIPT/Profile/followerPage.js" type="module"></script>
</html>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const users = <?php echo json_encode($users); ?>;
        const usersList = document.getElementById("usersList"); // Aggiungi questa riga per ottenere l'elemento corretto


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



        users.forEach(function(user) {
            var noUsers = document.getElementById("usernameLabel");
            noUsers.style.display = 'none';

            var row = document.createElement("div");
            row.className = "row col-9 friendlist-row";
            row.innerHTML = `
                <div class="col-md-3 username">${user.username}</div>
                <div class="col-md-3 fullname">${user.nome}</div>
                <div class="col-md-3 fullname">${user.cognome}</div>
            `;
            
            // Aggiungi un evento di click per navigare alla pagina dell'utente
            row.addEventListener("click", function() {
                var newUrl = `friendPage.php?useridUtente=${user.userid}`;
                if (isAdmin) {
                    newUrl += '&type=admin';
                }
                window.location.href = newUrl;
            });

            // Aggiungi la riga appena creata all'elemento usersList
            usersList.appendChild(row);
        });
    });
</script>
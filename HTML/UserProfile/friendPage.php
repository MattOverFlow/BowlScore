<?php
include '../../PHP/Utils/auth_request.php';
include '../../PHP/Database/User.php';

$useridUtente = $_GET['useridUtente'];
$nFollower = numeroFollower($useridUtente);
$nSeguiti = numeroSeguiti($useridUtente);
$info = scaricaUtente($useridUtente);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>BowlScore - Profile</title>
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
            </div>
            <button class="openbtn" id="openbtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="48.6" height="32" viewBox="0 0 76 50" fill="none">
                    <path d="M3 2.71213H73" stroke="#000000" stroke-width="5" stroke-linecap="round" />
                    <path d="M3 46.5505H73" stroke="#000000" stroke-width="5" stroke-linecap="round" />
                    <path d="M3 24.6313H73" stroke="#000000" stroke-width="5" stroke-linecap="round" />
                </svg>
            </button>
        </header>
        <main class="pt-3">
            <!--User info section-->
            <div id="user_info">
                <div class="row justify-content-center gy-2">
                    <div class="col-auto text-center">
                        <p id="username"><?php echo $info["username"] ?></p>
                        <div class="row justify-content-center" id="user_number">
                            <button type="button" class="btn col-auto pt-0 pb-0 text-center" id="followers">
                                <p>Followers</p>
                                <p id="nFollower"><?php echo $nFollower ?></p>
                            </button>
                            <button type="button" class="btn col-auto pt-0 pb-0 text-center" id="seguiti">
                                <p>Seguiti</p>
                                <p id="nSeguiti"><?php echo $nSeguiti ?></p>
                            </button>
                        </div>
                        <!-- Sposta il bottone qui sotto -->
                        <div class="row justify-content-center mt-2">
                            <button class="btn btn-primary" id="followButtonNotFollow" value="follow" style="width: 86%;">follow</button>
                        </div>
                    </div>
                    <div class="col-auto name_surname" id="user_name_surname_box">
                        <p id="name_surname"><?php echo $info['nome'] . " " . $info['cognome']; ?></p>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column align-items-center justify-content-center">
                <div class="col-8" id="playerStatistics" style="display: none;">
                    <h2>Statistiche del Giocatore</h2>
                    <ul>
                        <li>Punteggio Medio (Average Score): <span id="averageScore">N/A</span></li>
                        <li>Strike Rate (Percentuale di Strike): <span id="strikeRate">N/A</span></li>
                        <li>Spare Rate (Percentuale di Spare): <span id="spareRate">N/A</span></li>
                        <li>Punteggio Massimo in una Partita (High Game): <span id="highGame">N/A</span></li>
                        <li>First Ball Average: <span id="firstBallAverage">N/A</span></li>
                        <li>Clean Game: <span id="cleanGame">N/A</span></li>
                        <li>Percentuale di Frame Puliti (Clean Frame Percentage): <span id="cleanFramePercentage">N/A</span></li>
                    </ul>
                </div>
            </div>
        </main>
    </div>
</body>
<script src="../../JAVASCRIPT\Utils\sidenav.js" type="module"></script>


<!-- Script per bottoni dei numeri dei follower e dei seguiti -->
<script>
    const useridUtente = "<?php echo $useridUtente; ?>";

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

    document.getElementById("followers").addEventListener("click", function() {
        var newUrl = `followerPage.php?useridUtente=${useridUtente}`;
        if (isAdmin) {
            newUrl += '&type=admin';
        }
        window.location.href = newUrl;
    });

    document.getElementById("seguiti").addEventListener("click", function() {
        var newUrl = `followedPage.php?useridUtente=${useridUtente}`;
        if (isAdmin) {
            newUrl += '&type=admin';
        }
        window.location.href = newUrl;
    });
</script>
<script src="../../JAVASCRIPT/Profile/friendPage.js" type="module"></script>


<!-- Script per il bottone di follow/unfollow -->
<script>
    const userID = "<?php echo $_SESSION['userid']; ?>";

    async function checkFollow() {
        const button = document.getElementById("followButtonNotFollow");

        const response = await fetch('../../PHP/Utente/checkFollow.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `userId=${userID}&useridUtente=${useridUtente}`
        });

        const data = await response.json();

        if (data === true) {
            button.innerHTML = "unfollow";
            button.classList.remove("btn-primary");
            button.classList.add("btn-secondary");
            const statistics = document.getElementById("playerStatistics");
            statistics.style.display = "block";
        } else {
            button.innerHTML = "follow";
            button.classList.remove("btn-secondary");
            button.classList.add("btn-primary");
            const statistics = document.getElementById("playerStatistics");
            statistics.style.display = "none";
        }
    }

    checkFollow();

    document.addEventListener("DOMContentLoaded", async function() {
        const resultStatistics = await fetch('../../PHP/Statistiche/downloadPersonalStatistics.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `userid=${useridUtente}`
        });

        const statistics = await resultStatistics.json();

        document.getElementById("averageScore").innerText = statistics.averageScore || '0';
        document.getElementById("strikeRate").innerText = statistics.strikeRate || '0';
        document.getElementById("spareRate").innerText = statistics.spareRate || '0';
        document.getElementById("highGame").innerText = statistics.highGame || '0';
        document.getElementById("firstBallAverage").innerText = statistics.firstBallAverage || '0';
        document.getElementById("cleanGame").innerText = statistics.cleanGame || '0';
        document.getElementById("cleanFramePercentage").innerText = statistics.cleanFramePercentage || '0';

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

        if (isAdmin) {
            const statistics = document.getElementById("playerStatistics");
            statistics.style.display = "block";
        }
    });

    document.getElementById("followButtonNotFollow").addEventListener("click", async function() {
        const button = this;
        var type = button.innerHTML;

        if (type === "follow") {
            const response = await fetch('../../PHP/Utente/followUser.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `userId=${userID}&useridUtente=${useridUtente}`
            });

            if (response.ok) {
                button.innerHTML = "unfollow";
                button.classList.remove("btn-primary");
                button.classList.add("btn-secondary");
            } else {
                throw new Error('Errore');
            }
        } else {
            const response = await fetch('../../PHP/Utente/unfollowUser.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `userId=${userID}&useridUtente=${useridUtente}`
            });

            if (response.ok) {
                button.innerHTML = "follow";
                button.classList.remove("btn-secondary");
                button.classList.add("btn-primary");
            } else {
                throw new Error('Errore');
            }
        }
    });
</script>

</html>
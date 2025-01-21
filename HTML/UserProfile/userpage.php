<?php
include '../../PHP/Utils/auth_request.php';
include_once '../../PHP/Database/User.php';

$nFollower = numeroFollower($userid);
$nSeguiti = numeroSeguiti($userid);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <title>BowlScore - Profile</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../../CSS/Profile/userpage.css">
    <script src="https://kit.fontawesome.com/d9b18796bb.js" crossorigin="anonymous"></script>
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
        <main class="pt-3">
            <!--User info section-->
            <div id="user_info">
                <div class="row justify-content-center gy-2">
                    <div class="col-auto username text-center">
                        <p id="username"><?php echo $_SESSION['username'];?></p>
                        <div class="row justify-content-center" id="user_number">
                            <button type="button" class="btn col-auto pt-0 pb-0 text-center" id="followers">
                                <p>Follower</p>
                                <p id="nFollower"><?php echo $nFollower?></p>
                            </button>
                            <button type="button" class="btn col-auto pt-0 pb-0 text-center" id="seguiti">
                                <p>Seguiti</p>
                                <p id="nSeguiti"><?php echo $nSeguiti?></p>
                            </button>
                        </div>
                    </div>
                    <div class="col-auto name_surname" id="user_name_surname_box">
                        <p id="name_surname"><?php echo $_SESSION['nome'] . " " . $_SESSION['cognome']; ?></p>
                    </div>
                </div>
                <div class="row justify-content-center topic_row mt-4 mb-4" id="topic-container">
                </div>
            </div>
            <div class="d-flex flex-column align-items-center justify-content-center">
                <div class="col-8" id="playerStatistics">
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
<script>
    document.getElementById("followers").addEventListener("click", function() {
        window.location.href = "followerPage.php";
    });
    document.getElementById("seguiti").addEventListener("click", function() {
        window.location.href = "followedPage.php";
    });

    const userID = "<?php echo $_SESSION['userid']; ?>";

    document.addEventListener("DOMContentLoaded", async function() {
        const resultStatistics = await fetch('../../PHP/Statistiche/downloadPersonalStatistics.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `userid=${userID}`
        });

        const statistics = await resultStatistics.json();

        document.getElementById("averageScore").innerText = statistics.averageScore || '0';
        document.getElementById("strikeRate").innerText = statistics.strikeRate || '0';
        document.getElementById("spareRate").innerText = statistics.spareRate || '0';
        document.getElementById("highGame").innerText = statistics.highGame || '0';
        document.getElementById("firstBallAverage").innerText = statistics.firstBallAverage || '0';
        document.getElementById("cleanGame").innerText = statistics.cleanGame || '0';
        document.getElementById("cleanFramePercentage").innerText = statistics.cleanFramePercentage || '0';
    });

</script>
</html>
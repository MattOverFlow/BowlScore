<?php
include_once(__DIR__ . "/..//Utils/bootstrap.php");
sec_session_start();

include_once(__DIR__ . "/../Database/User.php");
include_once(__DIR__ . "/../Database/Admin.php");
include_once(__DIR__ . "/../Database/Torneo.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchInput = isset($_POST['searchInput']) && $_POST['searchInput'] !== 'null' ? $_POST['searchInput'] : null;
    $tournamentType = isset($_POST['tournamentType']) && $_POST['tournamentType'] !== 'null' ? $_POST['tournamentType'] : null;
    $startDate = isset($_POST['startDate']) && $_POST['startDate'] !== 'null' ? $_POST['startDate'] : null;
    $endDate = isset($_POST['endDate']) && $_POST['endDate'] !== 'null' ? $_POST['endDate'] : null;
    $numTeams = isset($_POST['numTeams']) && $_POST['numTeams'] !== 'null' ? $_POST['numTeams'] : null;
    $teamSize = isset($_POST['teamSize']) && $_POST['teamSize'] !== 'null' ? $_POST['teamSize'] : null;
    $teamName = isset($_POST['teamName']) && $_POST['teamName'] !== 'null' ? $_POST['teamName'] : null;
    $username = isset($_POST['username']) && $_POST['username'] !== 'null' ? $_POST['username'] : null;
} else {
    echo json_encode(array("error" => "Richiesta non valida"));
}

if ($_POST['username'] === '###') {
    $userid = $_SESSION['userid'];
    $utente = scaricaUtente($userid);
    $username = $utente['username'];
}

$torneiSingoli = [];
$torneiSquadre = [];

if ($_POST['username'] === '###') {



    if ($tournamentType === 'squadre') {
        $torneiSquadre = ricercaStoricoTorneiSquadreConPartecipazione($searchInput, $startDate, $endDate, $numTeams, $teamSize, $teamName, $username);
    } else if ($tournamentType === 'singolo') {
        $torneiSingoli = ricercaStoricoTorneiSingolo($searchInput, $startDate, $endDate, $username);
    } else {
        $torneiSingoli = ricercaStoricoTorneiSingolo($searchInput, $startDate, $endDate, $username);
        $torneiSquadre = ricercaStoricoTorneiSquadreConPartecipazione($searchInput, $startDate, $endDate, $numTeams, $teamSize, $teamName, $username);
    }

} else if ($tournamentType === 'squadre') {
    $torneiSquadre = ricercaStoricoTorneiSquadre($searchInput, $startDate, $endDate, $numTeams, $teamSize, $teamName);
} else if ($tournamentType === 'singolo') {
    $torneiSingoli = ricercaStoricoTorneiSingolo($searchInput, $startDate, $endDate, $username);
} else {
    $torneiSingoli = ricercaStoricoTorneiSingolo($searchInput, $startDate, $endDate, $username);
    $torneiSquadre = ricercaStoricoTorneiSquadre($searchInput, $startDate, $endDate, $numTeams, $teamSize, $teamName);
}

$response = [
    'torneiSingoli' => $torneiSingoli,
    'torneiSquadre' => $torneiSquadre,
];

echo json_encode($response);

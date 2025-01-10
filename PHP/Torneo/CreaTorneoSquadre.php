<?php
include_once(__DIR__ . "/../Database/Admin.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tournamentName = $_POST['tournamentName'];
    $numTeams = $_POST['numTeams'];
    $teamSize = $_POST['teamSize'];
    $teams = json_decode($_POST['teams']);
} else {
    echo json_encode(array("error" => "Richiesta non valida"));
}

$data = date('Y-m-d H:i:s');

$idTorneo = creaTorneoSquadre($tournamentName, $numTeams,$teamSize, $data);

if ($idTorneo){
    foreach ($teams as $team) {
        $idSquadra = creaIscrizioneTorneoTeam($team->teamName, $idTorneo);
    }
    echo json_encode($idTorneo);
} else {
    echo json_encode(null);
}

?>
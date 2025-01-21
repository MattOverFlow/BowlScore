<?php

include_once '../Database/Torneo.php';



$idTorneo = $_POST['idTorneo'];

$torneo = scaricaTorneoSquadre($idTorneo);

$teams = scaricaTeamsTorneoSquadre($idTorneo);

$response = [
    'torneo' => $torneo,
    'teams' => $teams,
];

echo json_encode($response);
?>
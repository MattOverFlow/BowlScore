<?php
include_once(__DIR__ . "/../Database/Admin.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tournamentName = $_POST['tournamentName'];
    $numParticipants = $_POST['numParticipants'];
} else {
    echo json_encode(array("error" => "Richiesta non valida"));
}

$data = date('Y-m-d H:i:s');

$idTorneo = creaTorneoSingolo($tournamentName, $numParticipants, $data);

echo $idTorneo ? json_encode($idTorneo) : json_encode(false);

?>
<?php
include_once(__DIR__ . "/../Database/Admin.php");
include_once(__DIR__ . "/../Database/User.php");
include_once(__DIR__ . "/../Database/Team.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $newComponents = json_decode($_POST['newComponents'], true);
    $oldComponents = json_decode($_POST['oldComponents'], true);
    $teamName = $_POST['teamName'];
    $numMembers = $_POST['numMembers'];
} else {
    echo json_encode(array("error" => "Richiesta non valida"));
}

$dataOggi= date("Y-m-d H:i:s");

foreach ($newComponents as $newComponent) {
    $datiUtente = datiUtenteDaUsername($newComponent);
    if ($datiUtente == null) {
        echo json_encode(array("error" => "L'utente $newComponent non esiste"));
        exit;
    } else {
        aggiungiUtenteStoricoTeam($datiUtente['userid'], $teamName, $dataOggi);
    }
}

foreach ($oldComponents as $oldComponent) {
    $datiUtente = datiUtenteDaUsername($oldComponent);
    if ($datiUtente == null) {
        echo json_encode(array("error" => "L'utente $oldComponent non esiste"));
        exit;
    } else {
        uscitaUtenteStoricoTeam($datiUtente['userid'], $teamName, $dataOggi);
    }
}

if(aggiornaNumeroMembriTeam($teamName, $numMembers)){
    echo json_encode(array("success" => "Modifica effettuata con successo"));
} else {
    echo json_encode(array("error" => "Errore durante la modifica"));
}

?>
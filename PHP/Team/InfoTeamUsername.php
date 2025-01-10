<?php

include_once(__DIR__ . "/../Database/Team.php");
include_once(__DIR__ . "/../Database/User.php");

$team = cercaInfoTeam($_POST['teamName']);

if ($team == null) {
    echo json_encode(["error" => "Team non trovato"]);
    exit();
}

$usernames = [];

foreach ($team["utenti"] as $utente) {
    $username = scaricaUtente($utente);
    if ($username != null) {
        $usernames[] = $username;
    } else {
        echo json_encode(["error" => "Errore nel caricamento degli utenti"]);
        exit();
    }
}

$team["utenti"] = $usernames;

echo json_encode($team);

?>
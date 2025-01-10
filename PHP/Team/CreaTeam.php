<?php

include_once(__DIR__ . "/../Database/Admin.php");
include_once(__DIR__ . "/../Database/User.php");

$data = json_decode(file_get_contents('php://input'), true);

$teamName = $data['teamName'];
$teamSize = $data['teamSize'];
$teamMembers = $data['teamMembers'];

$userIDs = [];

foreach ($teamMembers as $teamMember) {
    $datiUtente = datiUtenteDaUsername($teamMember);
    if ($datiUtente == null) {
        echo json_encode(array("error" => "L'utente $teamMember non esiste"));
        exit;
    } else {
        array_push($userIDs, $datiUtente['userid']);
        echo "L'utente $teamMember esiste";
    }
}

if (creaTeam($teamName, $teamSize)) {
    echo "Il team $teamName è stato creato con successo";
    $dataOggi = new DateTime();
    $dataOggi = $dataOggi->format('Y-m-d H:i:s');

    foreach ($userIDs as $userID) {
        if (!aggiungiUtenteStoricoTeam($userID, $teamName, $dataOggi)) {
            echo json_encode(array("error" => "Errore nell'aggiunta dell'utente $userID al team $teamName"));
            exit;
        }
        echo "L'utente $userID è stato aggiunto al team $teamName";
    }
}

?>
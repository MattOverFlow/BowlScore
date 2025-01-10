<?php

include_once(__DIR__ . "/../Database/Admin.php");
include_once(__DIR__ . "/../Database/User.php");

header('Content-Type: application/json');

$teams = scaricaTeams();

$teamsScaricati = array();

if($teams == null) {
    echo json_encode("Errore nel download dei team");
    exit();
}

foreach ($teams as $team) {
    // Accedi agli elementi del team
    $nomeTeam = $team['Nome'];
    $numeroComponenti = $team['NumeroComponenti'];
    $componenti = array();

    $codiceComponenti = scaricaComponentiTeam($nomeTeam);

    if($codiceComponenti == false) {
        echo json_encode("Errore nel download dei componenti del team");
        exit();
    }

    foreach ($codiceComponenti as $componente){
        $datiComponente = scaricaUtente($componente['CodiceUtente']);
        if($datiComponente == null) {
            echo json_encode("Errore nel download dei dati del componente : ");
            exit();
        }
        array_push($componenti, $datiComponente['username']);
    }

    $teamScaricato = array(
        'Nome' => $nomeTeam,
        'NumeroComponenti' => $numeroComponenti,
        'Componenti' => $componenti
    );

    array_push($teamsScaricati, $teamScaricato);
}

echo json_encode($teamsScaricati);
?>
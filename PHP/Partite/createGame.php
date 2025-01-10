<?php
include_once(__DIR__ . "/../Database/Admin.php");
include_once(__DIR__ . "/../Database/User.php");
include_once(__DIR__ . "/../Database/Carte.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gameName = $_POST['gameName'];
    $numParticipants = $_POST['numParticipants'];
    $participants = json_decode($_POST['participants'], true);
    $scoresList = json_decode($_POST['scoresList'], true);
    $totalScoresList = json_decode($_POST['totalScoresList'], true);
} else {
    echo json_encode(array("error" => "Richiesta non valida"));
}

$usersData = [];
foreach ($participants as $participant) {
    $datiUtente = datiUtenteDaUsername($participant);
    if ($datiUtente == null) {
        echo json_encode(array("error" => "L'utente $participant non esiste"));
        exit;
    }

    if (!isset($_POST['codiceTorneoSingolo']) && !isset($_POST['codiceTorneoSquadre'])) {

        $userid = $datiUtente['userid'];
        $datiCarta = datiCarta($userid);
        if ($datiCarta == null) {
            echo json_encode(array("error" => "L'utente $participant non ha una carta"));
            exit;
        }

        $datiAbbonamento = datiAbbonamento($userid);
        if ($datiAbbonamento != null) {
            $dataOdierna = new DateTime();
            $dataOdierna = $dataOdierna->format('Y-m-d');
            $dataScadenzaAbbonamento = new DateTime($datiAbbonamento['dataScadenza']);
            if ($dataOdierna > $dataScadenzaAbbonamento->format('Y-m-d')) {
                $partiteTotali = $datiCarta['partiteTotali'];
                if ($partiteTotali <= 0) {
                    echo json_encode(array("error" => "L'utente $participant non ha partite disponibili"));
                    exit;
                } else {
                    $usersData[] = ['userid' => $userid, 'partiteTotali' => $partiteTotali - 1];
                }
            }
        } else {
            $partiteTotali = $datiCarta['partiteTotali'];
            if ($partiteTotali <= 0) {
                echo json_encode(array("error" => "L'utente $participant non ha partite disponibili"));
                exit;
            } else {
                $usersData[] = ['userid' => $userid, 'partiteTotali' => $partiteTotali - 1];
            }
        }

        foreach ($usersData as $userData) {
            aggiornaPartiteTotali($userData['userid'], $userData['partiteTotali']);
        }
    }
}

if (isset($_POST['codiceTorneoSingolo'] )) {
    $codiceTorneoSingolo = $_POST['codiceTorneoSingolo'];
} else {
    $codiceTorneoSingolo = NULL;
}

if (isset($_POST['codiceTorneoSquadre'])) {
    $codiceTorneoSquadre = $_POST['codiceTorneoSquadre'];
} else {
    $codiceTorneoSquadre = NULL;
}

$dataOggi = new DateTime();
$dataOggiFormattata = $dataOggi->format('Y-m-d H:i:s');
$idPartita = creaPartita($gameName, $numParticipants, $dataOggiFormattata);

foreach ($participants as $index => $participant) {
    $datiUtente = datiUtenteDaUsername($participant);

    $idPartecipazione = creaPartecipazione($datiUtente['userid'], $idPartita, $codiceTorneoSingolo, $codiceTorneoSquadre);

    if ($idPartecipazione) {

        $participantScores = $scoresList[$index];
        $totalScores = $totalScoresList[$index];

        for ($i = 0; $i < 10; $i++) {

            $totalScore = $totalScores[$i];
            $sessionScores = $participantScores[$i];

            $idSessione = creaSessione($idPartecipazione, $datiUtente['userid'], $idPartita, $i + 1, $totalScore);
            if ($idSessione) {
                $indexScore = 0;
                foreach ($sessionScores as $position => $score) {

                    creaLancio($idPartecipazione, $datiUtente['userid'], $idPartita, $idSessione, $position + 1, $score['time'], $score['type']);
                }
            }
        }
    }
}

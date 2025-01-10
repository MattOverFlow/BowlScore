<?php

include_once (__DIR__ . "/../Utils/bootstrap.php");
sec_session_start();
include_once ("../Database/Carte.php");

$pacchetto = trovaPacchetto($_POST['codicePacchetto']);

if (aggiornaPartiteTotali($_SESSION['userid'], $pacchetto['numero_partite'])) {
    echo "Partite aggiornate con successo!";
} else {
    echo "Errore nell'aggiornamento delle partite!";
}

$dataOggi = new DateTime();
$dataOggiFormattata = $dataOggi->format('Y-m-d H:i:s');

if(registraTransazionePacchettoPartite($_SESSION['userid'], $_POST['codicePacchetto'],$dataOggiFormattata,0)){
    echo "Transazione pacchetto partite registrata con successo!";
} else {
    echo "Errore nella registrazione della transazione pacchetto partite!";
}

?>
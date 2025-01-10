<?php

include_once (__DIR__ . "/../Utils/bootstrap.php");
sec_session_start();
include_once ("../Database/Carte.php");

// Ottieni la data di oggi
$dataOggi = new DateTime();
$dataOggiFormattata = $dataOggi->format('Y-m-d'); // Formatta come 2025-01-06

// Calcola la data di scadenza (3 anni in più)
$dataScadenza = (clone $dataOggi)->modify('+3 years');
$dataScadenzaFormattata = $dataScadenza->format('Y-m-d'); // Formatta come 2028-01-06

if (creaCarta($_SESSION['userid'], $dataOggiFormattata, $dataScadenzaFormattata)) {
    echo "Carta creata con successo!";
} else {
    echo "Errore nella creazione della carta!";
}
?>
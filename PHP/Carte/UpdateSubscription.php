<?php
include_once (__DIR__ . "/../Utils/bootstrap.php");
sec_session_start();
include_once ("../Database/Carte.php");

$result = datiAbbonamento($_SESSION['userid']);

$datiCarta = datiCarta($_SESSION['userid']);

if($result != null){
    rimuoviAbbonamento($_SESSION['userid']);
}

$codiceTipo = $_POST['codiceTipo'];
$tipo = cercaTipoAbbonamento($codiceTipo);
if ($tipo!= null){
    $tipoDurata = $tipo['durata'];
    $dataOggi = new DateTime();
    $dataOggiFormattata = $dataOggi->format('Y-m-d');
    $dataScadenza = (clone $dataOggi)->modify("+$tipoDurata months");
    $dataScadenzaFormattata = $dataScadenza->format('Y-m-d');
    if(creaAbbonamento($_SESSION['userid'],$datiCarta['identificativo'],$dataOggiFormattata,$dataScadenzaFormattata,0,$codiceTipo)){
        echo "Abbonamento creato con successo!";
    } else {
        echo "Errore nella creazione dell'abbonamento!";
    }
} else {
    echo "Errore: tipo di abbonamento non trovato!";
}

?>
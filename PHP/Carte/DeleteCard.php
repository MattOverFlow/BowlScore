<?php
include_once (__DIR__ . "/../Utils/bootstrap.php");
sec_session_start();
include_once ("../Database/Carte.php");

rimuoviAbbonamento($_SESSION['userid']);
rimuoviTransazioni($_SESSION['userid']);

if(eliminaCarta($_SESSION['userid'])){
    echo "Carta eliminata con successo!";
} else {
    echo "Errore nell'eliminazione della carta!";
}

?>
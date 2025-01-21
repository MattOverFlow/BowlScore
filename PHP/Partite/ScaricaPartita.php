<?php

include_once(__DIR__ . "/../Database/Partita.php");

$partita = scaricaPartita($_POST['idPartita']);

echo json_encode($partita);

?>
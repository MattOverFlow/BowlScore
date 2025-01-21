<?php

include_once("../Database/Team.php");

$teams = scaricaTeamsDiUnTorneo($_POST['idTorneo']);

echo json_encode($teams);

?>
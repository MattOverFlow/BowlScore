<?php

include_once '../Database/Torneo.php';



$idTorneo = $_POST['idTorneo'];

$torneo = scaricaTorneoSingolo($idTorneo);

echo json_encode($torneo);

?>
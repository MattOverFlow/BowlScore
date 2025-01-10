<?php
include_once (__DIR__ . "/../Utils/bootstrap.php");
sec_session_start();
include_once "../Database/Carte.php";

echo json_encode(datiAbbonamento($_POST['userid'] ?? $_SESSION['userid']));

?>
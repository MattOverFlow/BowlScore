<?php

include_once "../Database/Statistiche.php";


$userID = $_POST['userid'];
$statistics = getUserStatistics($userID);

echo json_encode($statistics);

?>
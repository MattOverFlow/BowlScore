<?php

include_once "../Utils/bootstrap.php";
sec_session_start();
include_once "../Database/User.php";
include_once "../Database/Partita.php";

if($_POST['username'] = '###'){
    $userid = $_SESSION['userid'];
    $searchInput = isset($_POST['searchInput']) && $_POST['searchInput'] !== 'null' ? $_POST['searchInput'] : null;
    $startDate = isset($_POST['startDate']) && $_POST['startDate'] !== 'null' ? $_POST['startDate'] : null;
    $endDate = isset($_POST['endDate']) && $_POST['endDate'] !== 'null' ? $_POST['endDate'] : null;
}

$partite = ricercaPartite($searchInput, $startDate, $endDate, $userid);

echo json_encode($partite);

?>
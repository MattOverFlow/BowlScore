<?php
include_once("../Utils/bootstrap.php");
sec_session_start();
include_once("../Database/User.php"); 

echo json_encode(scaricaUtente($_POST['userid'] ?? $_SESSION["userid"]));

?>
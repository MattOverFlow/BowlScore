<?php
/*Raggruppiamo tutti gli elementi condivisi tra i vari php */
 require_once(__DIR__ . "/Session.php");
sec_session_start();

require_once(__DIR__ . "/../Database/DataBase.php");
require_once(__DIR__ . "/../../vendor/autoload.php");

$host = "localhost";
$user = "root";
$password = "";
$dbName = "bowling";

$database = DataBase::getInstance($host, $user, $password, $dbName);
$db = $database->getDatabase();

function getDb() {
    global $db;
    return $db;
}
?>
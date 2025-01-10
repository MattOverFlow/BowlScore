<?php
require_once '../Utils/bootstrap.php';
include_once (__DIR__ . "/../Database/User.php");
sec_session_start();
header('Content-Type: application/json');

if(isset($_POST['stringa'])){
    $users = cercaUtenti($_POST['stringa']);
    if ($users != null) {
        echo json_encode($users, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(array('error' => 'No users found'), JSON_PRETTY_PRINT);
    }
} else {
    return json_encode([]);
}
?>
<?php
include_once '../Database/User.php';
header('Content-Type: application/json');

$result = segue($_POST['userId'], $_POST['useridUtente']);
if ($result != null) {
    echo json_encode($result, JSON_PRETTY_PRINT);
} else {
    echo json_encode(array('error' => 'No users found'), JSON_PRETTY_PRINT);
}
?>
<?php
include_once (__DIR__ . "/../Utils/bootstrap.php");
sec_session_start();

$email = $_POST['email'];
$db = getDb();
$query = "SELECT Email FROM utente WHERE Email = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if($stmt->num_rows > 0)
    echo "Email_exist";
else
    echo "Email_available";
?>
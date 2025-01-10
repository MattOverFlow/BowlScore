<?php

include_once (__DIR__ . "/../Database/Login.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Metodo non consentito.";
    exit();
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']) ? true : false;

if (empty($email) || empty($password)) {
    header('Location: ../../HTML/Access/LoginPage.php?error=invalid_credentials');
    exit();
}
 $redirect = login($email, $password, $remember);

header($redirect);

?>
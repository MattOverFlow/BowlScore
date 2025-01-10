<?php
    include_once(__DIR__ . "/bootstrap.php");
    include_once(__DIR__ . "/../Database/Login.php");
    sec_session_start();

    if(!isset($_COOKIE['token'])) {
        header('Location: ../../HTML/Access/LoginPage.php');
        exit;
    } 
    try {
        $userid = $_COOKIE['token'];
        $_SESSION['userid'] = $userid;
        if (!isset($_SESSION['nome'])){
            scaricaInfoUser($_SESSION['userid']);
        }
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(array("error" => "Invalid token"));
        header('Location: ../../HTML/Access/LoginPage.php');
        exit();
    }
?>
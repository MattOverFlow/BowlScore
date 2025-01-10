<?php

    include_once(__DIR__ . "/../Utils/bootstrap.php");
    sec_session_start();

    session_unset();
    setcookie('token', '', time() - 3600, '/');
    header('Location: ../../HTML/Access/AccessPage.php');
    exit();
?>

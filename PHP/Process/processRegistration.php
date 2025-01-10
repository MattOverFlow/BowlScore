<?php

include_once (__DIR__ . "/../Database/User.php");
include_once (__DIR__ . "/../Database/Admin.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['name'];
    $cognome = $_POST['surname'];
    $username = $_POST['username'];
    $dataNascita = $_POST['birthDate'];
    $genere = $_POST['gender'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Controlla se l'utente vuole un account admin
    $isAdmin = isset($_POST['isAdmin']) && $_POST['isAdmin'] === 'true';

    if ($isAdmin) {
        $result=creaAmministratore($nome, $cognome, $username, $dataNascita, $genere, $email, $password);
        if($result){
            header('Location: ../../HTML/AdminProfile/createGame.php');
        } else {
            http_response_code(405);
            echo "Errore: l'utente non è stato creato.";
        }
    } else {
        $result=creaUtente($nome, $cognome, $username, $dataNascita, $genere, $email, $password);
        if($result){
            header('Location: ../../HTML/UserProfile/userpage.php');
        } else {
            http_response_code(405);
            echo "Errore: l'utente non è stato creato.";
        }
    }

} else {
    http_response_code(405);
    echo "Errore: il metodo HTTP deve essere POST.";
    exit;
}

?>
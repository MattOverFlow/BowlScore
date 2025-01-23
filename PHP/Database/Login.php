<?php
include_once (__DIR__ . "/../Utils/authUtilities.php");
include_once (__DIR__ . "/../Utils/bootstrap.php");
sec_session_start();

# Funzione di login 
function login($email, $password, $remember){
    $db = getDb();
    $tabelle = ['utente','amministratore'];

    foreach($tabelle as $tabella){
        $query = "SELECT Password,Username,UserID FROM $tabella WHERE Email = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $username="";
        $passwordDB = "";
        $userid = "";
        $stmt->bind_result($passwordDB,$username,$userid);
    
        if($stmt->num_rows > 0){
            $stmt->fetch();
    
            if($password === $passwordDB){ {
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['remember'] = $remember;
                $_SESSION['tipo'] = $tabella;
                $_SESSION['userid'] = $userid;
                
                set_token_cookie($userid, $remember);

                if($tabella == 'utente'){
                    return 'Location: ../../HTML/UserProfile/UserPage.php';
                } else {
                    return 'Location: ../../HTML/AdminProfile/createGame.php';
                    }
                }
            }
        }
    }
    header('Location: ../../HTML/Access/LoginPage.php?error=wrong_credentials');
}


# Funzione per scaricare le informazioni dell'utente solo quando non sono presenti nella sessione
function scaricaInfoUser($userid){
    $db = getDb();
    $tabelle = ['utente','amministratore'];

    foreach($tabelle as $tabella){
        $query = "SELECT Nome, Cognome, Username, DataNascita, Genere, Email FROM $tabella WHERE UserID = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $userid);
        $stmt->execute();
        $stmt->store_result();
    
        $nome = "";
        $cognome = "";
        $username = "";
        $dataNascita = "";
        $genere = "";
        $email = "";
    
        $stmt->bind_result($nome, $cognome, $username, $dataNascita, $genere, $email);
        $stmt->fetch();
        
        if($stmt->num_rows > 0){
            $_SESSION['nome'] = $nome;
            $_SESSION['cognome'] = $cognome;
            $_SESSION['username'] = $username;
            $_SESSION['dataNascita'] = $dataNascita;
            $_SESSION['genere'] = $genere;
            $_SESSION['email'] = $email;
            $_SESSION['tipo'] = $tabella;
            break;
        }
    }
}

?>
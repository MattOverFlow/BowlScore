<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


#Sistemare sistema di salvataggio e verifica dell'account
#Sistemare invio email e creazione token temporaneo
#Implementare il token dell'utente con jwt (chiave pubblica,crittografia,dati) per implementare meglio il fatto di salvare la sessione (anche li bisogna lavorarci)

require_once (__DIR__ . "/../Utils/bootstrap.php");
sec_session_start();
include_once (__DIR__ . "/../Utils/CheckInputForms.php");
// include_once (__DIR__ . "/../Utils/emailUtils.php");
include_once (__DIR__ . "/../Utils/authUtilities.php");


     function insertNewAccount(){
        /*Insert a new account in database*/

        if(\validateRegistrationInfo() && checkUsernameExistence($_POST['username']) == "Username_available" && checkEmailExistence($_POST['email']) == "Email_available"){
            $db = getDb();

            $query = "INSERT INTO utente (Nome, Cognome, Username, Email, Password, Data_nascita, Sesso, Descrizione, Foto_profilo, Foto_background, 2FA ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);

            $gender = isset($_POST['gender']) ? $_POST['gender'] : NULL;
            $hashedPassword =password_hash($_POST['password'],PASSWORD_DEFAULT);
            $desc="";
            $default2FA = 0;
            $void = NULL;

            $stmt->bind_param("ssssssssssi", $_POST['name'], $_POST['surname'], $_POST['username'], $_POST['email'], $hashedPassword, $_POST['birthDate'], $gender, $desc, $void, $void, $default2FA);
            $stmt->execute();

            $_SESSION['email'] = $_POST['email'];
            $_SESSION['username'] = $_POST['username'];

            /*Insert topic of interest related with the new account*/
            foreach ($_POST['topic'] as $topic) {
                $query = "INSERT INTO topic_utente (Nome_tag_Topic, Username_Utente) VALUES (?, ?)";
                $stmt = $db->prepare($query);
                $stmt->bind_param("ss", $topic, $_POST['username']);
                $stmt->execute();
            }
            return true;
        } else {
            return false;
        }
    }

     function checkUsernameExistence($username){
        $db = getDb();
        
        if(!\checkInputUsername()){
            return "Username_invalid";
        }
        $query = "SELECT Username FROM utente WHERE Username = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0)
            return "Username_exist";
        else
            return "Username_available";
    }

     function checkEmailExistence($email){
        $db = getDb();
        if(\checkInputEmail()){
            $query = "SELECT Email FROM utente WHERE Email = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows > 0)
                return "Email_exist";
            else
                return "Email_available";
        } else {
            return "Email_invalid";
        }
    }

     function checkPassword($password,$email){
        $db = getDb();
        $hashedPassword="";
        if( !\checkInputEmail() || !\checkInputPassword()){
            return "Password_invalid";
        }
        $query = "SELECT Password FROM utente WHERE Email = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        if(password_verify($password,$hashedPassword)){
            return "Password_correct";
        } else {
            return "Password_wrong";
        }
    }
    
     function processLogin($email){
        $db = getDb();
        $username="";
        $_SESSION['email'] = $email;
        $query="SELECT Username FROM utente WHERE Email = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($username);
        $stmt->fetch();
        $_SESSION['username'] = $username;
        $_SESSION['remember'] = isset($_POST['remember']) ? "on" : "off";
     }

    function retrieveUsername(){
        $db = getDb();
        $sql = "SELECT username FROM tfa_auth WHERE token = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $_SESSION['token']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["username"];
        } else {
            return null;
        }
    }

?>
<?php

require_once(__DIR__ . "/../Utils/bootstrap.php");
sec_session_start();
include_once(__DIR__ . "/../Utils/authUtilities.php");

# Aggiunge un utente al database
function creaUtente($nome, $cognome, $username, $dataNascita, $genere, $email, $password)
{
    $db = getDb();

    $query = "INSERT INTO utente (Nome, Cognome, Username, DataNascita, Genere, Email, Password)
          VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $db->prepare($query);
    $stmt->bind_param("sssssss", $nome, $cognome, $username, $dataNascita, $genere, $email, $password);

    if ($stmt->execute()) {
        echo "Utente creato con successo!";
        return true;
    } else {
        echo "Errore nell'inserimento: " . $stmt->error;
        return false;
    }

    $stmt->close();
}

# Scarica le informazioni principali di un utente
function scaricaUtente($userid)
{
    $db = getDb();
    $query = "SELECT Nome, Cognome, Username FROM utente WHERE UserID = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        return null;
    }

    $nome = "";
    $cognome = "";
    $username = "";
    $stmt->bind_result($nome, $cognome, $username);
    $stmt->fetch();

    $stmt->close();
    return [
        "nome" => $nome,
        "cognome" => $cognome,
        "username" => $username,
    ];
}

function datiUtenteDaUsername($username)
{
    $db = getDb();
    $query = "SELECT UserID, Nome, Cognome FROM utente WHERE Username = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        return null;
    }

    $userid = "";
    $nome = "";
    $cognome = "";
    $stmt->bind_result($userid, $nome, $cognome);
    $stmt->fetch();

    $stmt->close();
    return [
        "userid" => $userid,
        "nome" => $nome,
        "cognome" => $cognome
    ];
}

# Funzione per contare il numero di follower di un utente
function numeroFollower($userid)
{
    $db = getDb();
    $query = "SELECT COUNT(*) FROM amicizia WHERE CodiceUtenteSeguito = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $stmt->store_result();
    $nFollower = 0;
    $stmt->bind_result($nFollower);
    $stmt->fetch();

    $stmt->close();
    return $nFollower;
}

# Funzione per contare il numero di utenti seguiti da un utente
function numeroSeguiti($userid)
{
    $db = getDb();
    $query = "SELECT COUNT(*) FROM amicizia WHERE CodiceUtente = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $stmt->store_result();
    $nSeguiti = 0;
    $stmt->bind_result($nSeguiti);
    $stmt->fetch();

    $stmt->close();
    return $nSeguiti;
}

# Restituisce la lista dei follower di un utente
function listaFollowers($userid)
{
    $db = getDb();
    $query = "SELECT UserID,Nome,Cognome,Username FROM utente WHERE UserID IN (SELECT CodiceUtente FROM amicizia WHERE CodiceUtenteSeguito = ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userid);
    $success = $stmt->execute();

    if (!$success) {
        throw new \Exception("Error while querying the database: " . mysqli_error($db));
    }

    $result = $stmt->get_result();
    $followers = array();

    if ($result->num_rows > 0) {
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_array();
            $user = [
                "userid" => $row['UserID'],
                "nome" => $row['Nome'],
                "cognome" => $row['Cognome'],
                "username" => $row['Username']
            ];
            array_push($followers, $user);
        }
        return $followers;
    } else {
        return null;
    }
}

# Restituisce la lista degli utenti seguiti da un utente
function listaSeguiti($userid)
{
    $db = getDb();
    $query = "SELECT UserID,Nome,Cognome,Username FROM utente WHERE UserID IN (SELECT CodiceUtenteSeguito FROM amicizia WHERE CodiceUtente = ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userid);
    $success = $stmt->execute();

    if (!$success) {
        throw new \Exception("Error while querying the database: " . mysqli_error($db));
    }

    $result = $stmt->get_result();
    $seguiti = array();

    if ($result->num_rows > 0) {
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_array();
            $user = [
                "userid" => $row['UserID'],
                "nome" => $row['Nome'],
                "cognome" => $row['Cognome'],
                "username" => $row['Username']
            ];
            array_push($seguiti, $user);
        }
        return $seguiti;
    } else {
        return null;
    }
}

# Funzione per cercare utenti nel database
function cercaUtenti($stringa)
{
    $db = getDb();
    $query = "SELECT UserID, Nome, Cognome, Username FROM utente WHERE Nome LIKE ? OR Cognome LIKE ? OR Username LIKE ? LIMIT 7;";
    $stmt = $db->prepare($query);
    $stringa = "%$stringa%";
    $stmt->bind_param("sss", $stringa, $stringa, $stringa);
    $success = $stmt->execute();

    if (!$success) {
        throw new \Exception("Error while querying the database: " . mysqli_error($db));
    }

    $result = $stmt->get_result();
    $users = array();
    $userIds = []; // Array per tenere traccia degli ID utente già aggiunti

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            $useridUtente = $row['UserID'];

            // Controlla se l'utente è già stato aggiunto
            if (!in_array($useridUtente, $userIds)) {
                $user = [
                    "userid" => $useridUtente,
                    "nome" => $row['Nome'],
                    "cognome" => $row['Cognome'],
                    "username" => $row['Username']
                ];

                // Aggiungi l'utente all'array e registra l'ID per evitare duplicati
                $users[] = $user;
                $userIds[] = $useridUtente; // Registra l'ID utente come già aggiunto
            }
        }
        return $users;
    } else {
        return null;
    }
}


# Funzione per seguire un utente
function seguiUtente($userID, $useridUtente)
{
    $db = getDb();
    $query = "INSERT INTO amicizia (CodiceUtente, CodiceUtenteSeguito) VALUES (?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $userID, $useridUtente);
    $stmt->execute();
    $stmt->close();
}

# Funzione per smettere di seguire un utente
function smettiDiSeguire($userID, $useridUtente)
{
    $db = getDb();
    $query = "DELETE FROM amicizia WHERE CodiceUtente = ? AND CodiceUtenteSeguito = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $userID, $useridUtente);
    $stmt->execute();
    $stmt->close();
}

# Funzione per controllare se un utente segue un altro utente
function segue($userID, $useridUtente)
{
    $db = getDb();
    $query = "SELECT * FROM amicizia WHERE CodiceUtente = ? AND CodiceUtenteSeguito = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $userID, $useridUtente);
    $stmt->execute();
    $stmt->store_result();
    $stmt->fetch();
    $result = $stmt->num_rows > 0;
    $stmt->close();
    return $result;
}

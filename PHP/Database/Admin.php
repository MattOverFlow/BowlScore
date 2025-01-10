<?php

require_once(__DIR__ . "/../Utils/bootstrap.php");
sec_session_start();
include_once(__DIR__ . "/../Utils/authUtilities.php");


function creaAmministratore($nome, $cognome, $username, $dataNascita, $genere, $email, $password)
{
    $db = getDb();

    $query = "INSERT INTO amministratore (Nome, Cognome, Username, DataNascita, Genere, Email, Password)
          VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $db->prepare($query);
    $stmt->bind_param("sssssss", $nome, $cognome, $username, $dataNascita, $genere, $email, $password);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }

    $stmt->close();
}


function creaPartita($nomePartita, $numeroGiocatori, $data)
{

    $db = getDb();

    $query = "INSERT INTO partita (NomePartita, NumeroGiocatori, Data)
          VALUES (?, ?, ?)";

    $stmt = $db->prepare($query);
    $stmt->bind_param("sis", $nomePartita, $numeroGiocatori, $data);

    if ($stmt->execute()) {
        $query = "SELECT IdPartita FROM partita WHERE NomePartita = ? AND Data = ? AND NumeroGiocatori = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ssi", $nomePartita, $data, $numeroGiocatori);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $idPartita = $row['IdPartita'];
            return $idPartita;
        }
    } else {
        return false;
    }

    $stmt->close();
}

function creaPartecipazione($userid, $codicePartita, $codiceTorneoSingolo = NULL, $codiceTorneoSquadre = NULL)
{
    $db = getDb();

    $query = "INSERT INTO partecipazione (CodiceUtente, CodicePartita, CodiceTorneoSingolo, CodiceTorneoSquadre)
          VALUES (?, ?, ?, ?)";

    $stmt = $db->prepare($query);
    
    $stmt->bind_param("ssss", $userid, $codicePartita, $codiceTorneoSingolo, $codiceTorneoSquadre);

    if ($stmt->execute()) {

        $query = "SELECT IdPartecipazione FROM partecipazione 
              WHERE CodiceUtente = ? AND CodicePartita = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ss", $userid, $codicePartita);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $idPartecipazione = $row['IdPartecipazione'];
            return $idPartecipazione;
        }
    } else {
        return false;
    }

    $stmt->close();
}


function creaSessione($idPartecipazione, $idUtente, $idPartita, $nSessione, $totaleSessione)
{
    $db = getDb();

    $query = "INSERT INTO sessione (CodicePartecipazione, CodiceUtente, CodicePartita, NumeroSessione, PunteggioSessione)
          VALUES (?, ?, ?, ?, ?)";

    $stmt = $db->prepare($query);
    $stmt->bind_param("sssii", $idPartecipazione, $idUtente, $idPartita, $nSessione, $totaleSessione);

    if ($stmt->execute()) {

        $query = "SELECT IdSessione FROM sessione 
              WHERE CodicePartecipazione = ? AND CodiceUtente = ? AND CodicePartita = ? AND NumeroSessione = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sssi", $idPartecipazione, $idUtente, $idPartita, $nSessione);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $idSessione = $row['IdSessione'];
            return $idSessione;
        }
    } else {
        return false;
    }

    $stmt->close();
}

function creaLancio($idPartecipazione, $idUtente, $idPartita, $idSessione, $nLancio, $risultato, $tipoLancio)
{
    $db = getDb();

    $query = "INSERT INTO lancio (CodicePartecipazione, CodiceUtente, CodicePartita, CodiceSessione, NumeroLancio, Punteggio, TipoLancio)
          VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $db->prepare($query);
    $stmt->bind_param("ssssiis", $idPartecipazione, $idUtente, $idPartita, $idSessione, $nLancio, $risultato, $tipoLancio);
    $stmt->execute();
    $stmt->close();
}


function creaTeam($nomeTeam, $numeroComponenti)
{
    $db = getDb();

    $query = "INSERT INTO team (Nome, NumeroComponenti) VALUES (?, ?)";

    $stmt = $db->prepare($query);
    $stmt->bind_param("si", $nomeTeam, $numeroComponenti);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }

    $stmt->close();
}

function aggiungiUtenteStoricoTeam($idUtente, $nomeTeam, $dataIngresso)
{
    $db = getDb();

    $query = "INSERT INTO storico_team (CodiceUtente, NomeTeam, DataIngresso) VALUES (?, ?, ?)";

    $stmt = $db->prepare($query);
    $stmt->bind_param("sss", $idUtente, $nomeTeam, $dataIngresso);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }

    $stmt->close();
}

function uscitaUtenteStoricoTeam($idUtente, $nomeTeam, $dataUscita)
{
    $db = getDb();

    $query = "UPDATE storico_team SET DataUscita = ? WHERE CodiceUtente = ? AND NomeTeam = ? AND DataUscita IS NULL";

    $stmt = $db->prepare($query);
    $stmt->bind_param("sss", $dataUscita, $idUtente, $nomeTeam);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return true;
    } else {
        return false;
    }

    $stmt->close();
}

function aggiornaNumeroMembriTeam($nomeTeam, $numeroComponenti)
{
    $db = getDb();

    $query = "UPDATE team SET NumeroComponenti = ? WHERE Nome = ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param("is", $numeroComponenti, $nomeTeam);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return true;
    } else {
        return false;
    }

    $stmt->close();
}



function creaTorneoSingolo($nomeTorneo, $numeroPartecipanti, $dataCreazione)
{
    $db = getDb();

    $query = "INSERT INTO torneo_singolo (NomeTorneo,NumeroPartecipanti,DataCreazione) VALUES (?,?,?)";

    $stmt = $db->prepare($query);
    $stmt->bind_param("sis", $nomeTorneo, $numeroPartecipanti, $dataCreazione);

    if ($stmt->execute()) {
        $query = "SELECT IdTorneo FROM torneo_singolo WHERE NomeTorneo = ? AND DataCreazione = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ss", $nomeTorneo, $dataCreazione);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $codiceTorneoSingolo = $row['IdTorneo'];
            return $codiceTorneoSingolo;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function creaTorneoSquadre($nomeTorneo, $numeroSquadre, $numeroComponenti, $dataCreazione)
{
    $db = getDb();

    $query = "INSERT INTO torneo_squadre (NomeTorneo,NumeroTeamPartecipanti,DimensioneTeam,DataCreazione) VALUES (?,?,?,?)";

    $stmt = $db->prepare($query);
    $stmt->bind_param("siis", $nomeTorneo, $numeroSquadre, $numeroComponenti, $dataCreazione);

    if ($stmt->execute()) {
        $query = "SELECT IdTorneo FROM torneo_squadre WHERE NomeTorneo = ? AND DataCreazione = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ss", $nomeTorneo, $dataCreazione);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $codiceTorneoSquadre = $row['IdTorneo'];
            return $codiceTorneoSquadre;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function creaIscrizioneTorneoTeam($nomeTeam, $codiceTorneoSquadre)
{
    $db = getDb();

    $query = "INSERT INTO iscritto (NomeTeam,CodiceTorneoSquadre) VALUES (?,?)";

    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $nomeTeam, $codiceTorneoSquadre);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }

    $stmt->close();
}

function scaricaTeams(){
    $db = getDb();
    $query = "SELECT * FROM team";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $teams = array();
    while ($row = $result->fetch_assoc()) {
        $team = array(
            "Nome" => $row['Nome'],
            "NumeroComponenti" => $row['NumeroComponenti']
        );
        array_push($teams, $team);
    }
    return $teams;
}

function scaricaComponentiTeam($nomeTeam){
    $db = getDb();
    $query = "SELECT * FROM storico_team WHERE NomeTeam = ? AND DataUscita IS NULL";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $nomeTeam);
    $stmt->execute();

    $result = $stmt->get_result();
    $componenti = array();
    while ($row = $result->fetch_assoc()) {
        $componente = array(
            "CodiceUtente" => $row['CodiceUtente']
        );
        array_push($componenti, $componente);
    }

    if (count($componenti) == 0) {
        return false;
    }

    return $componenti;
}


function cercaTeams($querySearch){
    $db = getDb();
    $query = "SELECT * FROM team WHERE Nome LIKE ? LIMIT 7";
    $stmt = $db->prepare($query);
    $stringa = "%$querySearch%";
    $stmt->bind_param("s", $stringa);
    $stmt->execute();
    $result = $stmt->get_result();
    $teams = array();
    while ($row = $result->fetch_assoc()) {
        $team = array(
            "Nome" => $row['Nome'],
            "NumeroComponenti" => $row['NumeroComponenti']
        );
        array_push($teams, $team);
    }
    return $teams;
}
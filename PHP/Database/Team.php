<?php
require_once(__DIR__ . "/../Utils/bootstrap.php");
sec_session_start();
include_once(__DIR__ . "/../Utils/authUtilities.php");
require_once(__DIR__ . "/../Database/User.php");


function cercaInfoTeam($teamName)
{
    $db = getDb();
    $query = "SELECT Nome, NumeroComponenti FROM team WHERE Nome = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $teamName);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        return null;
    }

    $nome = "";
    $numeroMembri = "";
    $stmt->bind_result($nome, $numeroMembri);
    $stmt->fetch();

    $query = "SELECT CodiceUtente FROM storico_team WHERE NomeTeam = ? AND DataUscita IS NULL";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $teamName);
    $stmt->execute();
    $result = $stmt->get_result();

    $utenti = [];
    while ($row = $result->fetch_assoc()) {
        $utenti[] = $row['CodiceUtente'];
    }

    $stmt->close();
    return [
        "nome" => $nome,
        "numeroMembri" => $numeroMembri,
        "utenti" => $utenti
    ];
}


function verificaMembroTeam($userId){
    $db = getDb();
    $query = "SELECT NomeTeam FROM storico_team WHERE CodiceUtente = ? AND DataUscita IS NULL";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $stmt->store_result();

    $isMember = $stmt->num_rows > 0;

    $stmt->close();
    return $isMember;
}

function scaricaTeamsDiUnTorneo($idTorneo){

    global $db;

    $query = "SELECT DataCreazione FROM torneo_squadre WHERE IdTorneo = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $idTorneo);
    $stmt->execute();
    $stmt->store_result();
    
    $dataTorneo = "";
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($dataTorneo);
        $stmt->fetch();
    } else {
        echo "Nessun torneo trovato con l'IdTorneo: $idTorneo";
        $dataTorneo = "";
    }
    
    $queryPartecipazione = "SELECT * FROM partecipazione WHERE CodiceTorneoSquadre = ?";
    $stmtPartecipazione = $db->prepare($queryPartecipazione);
    $stmtPartecipazione->bind_param("s", $idTorneo);
    $stmtPartecipazione->execute();
    $result = $stmtPartecipazione->get_result();
    
    $queryTeams = "SELECT NomeTeam FROM iscritto WHERE CodiceTorneoSquadre = ?";
    $stmtTeams = $db->prepare($queryTeams);
    $stmtTeams->bind_param("s", $idTorneo);
    $stmtTeams->execute();
    $resultTeams = $stmtTeams->get_result();
    
    $teams = [];
    while ($row = $resultTeams->fetch_assoc()) {
        $teams[$row['NomeTeam']] = [];
    }
    
    while ($row = $result->fetch_assoc()) {
        $userid = $row['CodiceUtente'];
    
        $queryTeam = "
        SELECT DISTINCT NomeTeam
        FROM storico_team
        WHERE CodiceUtente = ? 
        AND (
            DataUscita IS NULL
            OR (DataIngresso <= ? AND DataUscita >= ?)
        )
        ";
        $stmtTeam = $db->prepare($queryTeam);
        $stmtTeam->bind_param("sss", $userid, $dataTorneo, $dataTorneo);
        $stmtTeam->execute();
        $resultTeam = $stmtTeam->get_result();
        $team = $resultTeam->fetch_assoc();
    
        if ($team) {
            $user = scaricaUtente($userid);
            $teamName = $team['NomeTeam'];
            
            // Verifica se l'utente è già presente nel team
            if (!in_array($user['username'], $teams[$teamName])) {
                $teams[$teamName][] = $user['username']; // Aggiungi solo se non è già presente
            }
        }
    }
    
    return $teams;
}

?>
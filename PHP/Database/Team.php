<?php
require_once(__DIR__ . "/../Utils/bootstrap.php");
sec_session_start();
include_once(__DIR__ . "/../Utils/authUtilities.php");


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

?>
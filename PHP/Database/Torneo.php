<?php

require_once(__DIR__ . "/../Utils/bootstrap.php");
sec_session_start();
include_once(__DIR__ . "/../Utils/authUtilities.php");
include_once(__DIR__ . "/../Database/Admin.php");
include_once(__DIR__ . "/../Database/User.php");
include_once(__DIR__ . "/../Database/Partita.php");

function ricercaStoricoTorneiSingolo($searchInput, $startDate, $endDate, $username)
{

    $db = getDb();

    $query = "
        SELECT DISTINCT ts.IdTorneo, ts.NomeTorneo, ts.DataCreazione, ts.NumeroPartecipanti, u.Username
        FROM torneo_singolo ts
        LEFT JOIN partecipazione p ON ts.IdTorneo = p.CodiceTorneoSingolo
        LEFT JOIN utente u ON p.CodiceUtente = u.Userid
        WHERE 1=1
    ";

    $params = [];

    if ($searchInput != null) {
        $query .= " AND ts.NomeTorneo LIKE ?";
        $params[] = "%$searchInput%";
    }

    if ($startDate != null) {
        $query .= " AND ts.DataCreazione >= ?";
        $params[] = $startDate;
    }

    if ($endDate != null) {
        $query .= " AND ts.DataCreazione <= ?";
        $params[] = $endDate;
    }

    $stmt = $db->prepare($query);

    if (count($params) > 0) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }

    $stmt->execute();

    $result = $stmt->get_result();

    $torneiFiltrati = [];
    while ($row = $result->fetch_assoc()) {
        $torneoId = $row['IdTorneo'];

        if (!isset($torneiFiltrati[$torneoId])) {
            $torneiFiltrati[$torneoId] = [
                'torneo' => [
                    'IdTorneo' => $row['IdTorneo'],
                    'NomeTorneo' => $row['NomeTorneo'],
                    'DataCreazione' => $row['DataCreazione'],
                    'NumeroPartecipanti' => $row['NumeroPartecipanti'],
                ],
                'partecipanti' => []
            ];
        }

        if ($row['Username']) {
            $torneiFiltrati[$torneoId]['partecipanti'][] = $row['Username'];
        }
    }

    if ($username != null) {
        $usernameLower = strtolower($username);

        foreach ($torneiFiltrati as $torneoId => $torneoData) {
            if (!in_array($usernameLower, array_map('strtolower', $torneoData['partecipanti']))) {
                unset($torneiFiltrati[$torneoId]);
            }
        }
    }

    return $torneiFiltrati;
}

function ricercaStoricoTorneiSquadre($searchInput, $startDate, $endDate, $numTeams, $teamSize, $teamName)
{
    $db = getDb();

    $query = "
        SELECT DISTINCT ts.IdTorneo, ts.NomeTorneo, ts.DataCreazione, ts.NumeroTeamPartecipanti, ts.DimensioneTeam, t.Nome AS TeamName
        FROM torneo_squadre ts
        LEFT JOIN partecipazione p ON ts.IdTorneo = p.CodiceTorneoSquadre
        LEFT JOIN iscritto i ON p.CodiceTorneoSquadre = i.CodiceTorneoSquadre
        LEFT JOIN team t ON i.NomeTeam = t.Nome
        WHERE 1=1
    ";

    $params = [];

    if ($searchInput != null) {
        $query .= " AND ts.NomeTorneo LIKE ?";
        $params[] = "%$searchInput%";
    }

    if ($startDate != null) {
        $query .= " AND ts.DataCreazione >= ?";
        $params[] = $startDate;
    }

    if ($endDate != null) {
        $query .= " AND ts.DataCreazione <= ?";
        $params[] = $endDate;
    }

    if ($numTeams != null) {
        $query .= " AND ts.NumeroTeamPartecipanti = ?";
        $params[] = $numTeams;
    }

    if ($teamSize != null) {
        $query .= " AND ts.DimensioneTeam = ?";
        $params[] = $teamSize;
    }

    $stmt = $db->prepare($query);

    if (count($params) > 0) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }

    $stmt->execute();

    $result = $stmt->get_result();

    $torneiFiltrati = [];
    while ($row = $result->fetch_assoc()) {
        $torneoId = $row['IdTorneo'];

        if (!isset($torneiFiltrati[$torneoId])) {
            $torneiFiltrati[$torneoId] = [
                'torneo' => [
                    'IdTorneo' => $row['IdTorneo'],
                    'NomeTorneo' => $row['NomeTorneo'],
                    'DataCreazione' => $row['DataCreazione'],
                    'NumeroTeamPartecipanti' => $row['NumeroTeamPartecipanti'],
                    'DimensioneTeam' => $row['DimensioneTeam'],
                ],
                'squadre' => []
            ];
        }

        if ($row['TeamName']) {
            $torneiFiltrati[$torneoId]['squadre'][] = $row['TeamName'];
        }
    }

    if ($teamName != null) {
        $torneiFiltrati = array_filter($torneiFiltrati, function ($torneoData) use ($teamName) {
            // Verifica se almeno una squadra corrisponde al nome cercato
            foreach ($torneoData['squadre'] as $team) {
                if (stripos($team, $teamName) !== false) {
                    return true; // Mantieni il torneo
                }
            }
            return false; // Escludi il torneo
        });
    }

    return $torneiFiltrati;
}



function ricercaStoricoTorneiSquadreConPartecipazione($searchInput, $startDate, $endDate, $numTeams, $teamSize, $teamName, $username)
{
    // Recupera l'id dell'utente tramite la funzione datiUtenteDaUsername
    $utente = datiUtenteDaUsername($username);
    if (!$utente || !$utente['userid']) {
        return []; // Se l'utente non esiste, restituisci un array vuoto
    }
    $utenteId = $utente['userid'];

    $db = getDb();

    $query = "
        SELECT DISTINCT ts.IdTorneo, ts.NomeTorneo, ts.DataCreazione, ts.NumeroTeamPartecipanti, ts.DimensioneTeam, t.Nome AS TeamName
        FROM torneo_squadre ts
        LEFT JOIN partecipazione p ON ts.IdTorneo = p.CodiceTorneoSquadre
        LEFT JOIN iscritto i ON p.CodiceTorneoSquadre = i.CodiceTorneoSquadre
        LEFT JOIN team t ON i.NomeTeam = t.Nome
        WHERE 1=1
    ";

    $params = [];

    if ($searchInput != null) {
        $query .= " AND ts.NomeTorneo LIKE ?";
        $params[] = "%$searchInput%";
    }

    if ($startDate != null) {
        $query .= " AND ts.DataCreazione >= ?";
        $params[] = $startDate;
    }

    if ($endDate != null) {
        $query .= " AND ts.DataCreazione <= ?";
        $params[] = $endDate;
    }

    if ($numTeams != null) {
        $query .= " AND ts.NumeroTeamPartecipanti = ?";
        $params[] = $numTeams;
    }

    if ($teamSize != null) {
        $query .= " AND ts.DimensioneTeam = ?";
        $params[] = $teamSize;
    }

    // Aggiungi il filtro per CodiceUtente
    $query .= " AND p.CodiceUtente = ?";
    $params[] = $utenteId;

    $stmt = $db->prepare($query);

    if (count($params) > 0) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }

    $stmt->execute();

    $result = $stmt->get_result();

    $torneiFiltrati = [];
    while ($row = $result->fetch_assoc()) {
        $torneoId = $row['IdTorneo'];

        if (!isset($torneiFiltrati[$torneoId])) {
            $torneiFiltrati[$torneoId] = [
                'torneo' => [
                    'IdTorneo' => $row['IdTorneo'],
                    'NomeTorneo' => $row['NomeTorneo'],
                    'DataCreazione' => $row['DataCreazione'],
                    'NumeroTeamPartecipanti' => $row['NumeroTeamPartecipanti'],
                    'DimensioneTeam' => $row['DimensioneTeam'],
                ],
                'squadre' => []
            ];
        }

        if ($row['TeamName']) {
            $torneiFiltrati[$torneoId]['squadre'][] = $row['TeamName'];
        }
    }

    // Filtra i tornei che non contengono squadre con il nome specificato
    if ($teamName != null) {
        foreach ($torneiFiltrati as $torneoId => &$torneoData) {
            $filteredSquad = array_filter($torneoData['squadre'], function ($team) use ($teamName) {
                return stripos($team, $teamName) !== false; // Controllo case-insensitive
            });

            if (empty($filteredSquad)) {
                unset($torneiFiltrati[$torneoId]); // Rimuovi il torneo se nessuna squadra corrisponde
            }
        }
    }

    return $torneiFiltrati;
}



function scaricaTorneoSingolo($idTorneo)
{

    $db = getDb();

    $query = "SELECT DISTINCT CodicePartita FROM partecipazione WHERE CodiceTorneoSingolo = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $idTorneo);
    $stmt->execute();
    $resultPartite = $stmt->get_result();

    $partite = [];

    while ($row = $resultPartite->fetch_assoc()) {

        $codicePartita = $row['CodicePartita'];
        $partita = scaricaPartita($codicePartita);
        $partite[] = $partita;
    }

    return $partite;
}

function scaricaTorneoSquadre($idTorneo)
{

    $db = getDb();

    $query = "SELECT DISTINCT CodicePartita FROM partecipazione WHERE CodiceTorneoSquadre = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $idTorneo);
    $stmt->execute();
    $resultPartite = $stmt->get_result();

    $partite = [];

    while ($row = $resultPartite->fetch_assoc()) {

        $codicePartita = $row['CodicePartita'];
        $partita = scaricaPartita($codicePartita);
        $partite[] = $partita;
    }

    return $partite;
}

function scaricaTeamsTorneoSquadre($idTorneo)
{
    $db = getDb();

    // Query per ottenere tutti i team iscritti al torneo
    $query = "SELECT NomeTeam FROM iscritto WHERE CodiceTorneoSquadre = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $idTorneo);
    $stmt->execute();
    $result = $stmt->get_result();

    $squadre = [];

    while ($row = $result->fetch_assoc()) {
        $squadra = $row['NomeTeam'];

        $queryMembri = "SELECT CodiceUtente FROM storico_team WHERE NomeTeam = ?";
        $stmtMembri = $db->prepare($queryMembri);
        $stmtMembri->bind_param("s", $squadra);
        $stmtMembri->execute();
        $resultMembri = $stmtMembri->get_result();

        $membri = [];
        while ($rowMembro = $resultMembri->fetch_assoc()) {
            $codiceMembro = $rowMembro['CodiceUtente'];
            $membro = scaricaUtente($codiceMembro);
            $membri[] = $membro["username"];
        }

        $squadre[$squadra] = [
            'team' => $squadra,
            'membri' => $membri
        ];

        $stmtMembri->close();
    }

    $stmt->close();

    return $squadre;
}

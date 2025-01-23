<?php

require_once(__DIR__ . "/../Utils/bootstrap.php");
sec_session_start();
include_once(__DIR__ . "/../Utils/authUtilities.php");
include_once(__DIR__ . "/../Database/Admin.php");
include_once(__DIR__ . "/../Database/User.php");
include_once(__DIR__ . "/../Database/Partita.php");


function downloadWinStatistic($dataInizio, $dataFine)
{
    $db = getDb();

    $query = "
        SELECT u.username, COUNT(*) AS vittorie
        FROM vittoria v
        JOIN partita p ON v.CodicePartita = p.IdPartita
        JOIN utente u ON v.CodiceUtente = u.UserID
        WHERE p.Data BETWEEN ? AND ?
        GROUP BY u.username
        ORDER BY vittorie DESC
        LIMIT 10
    ";

    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $dataInizio, $dataFine);

    $stmt->execute();

    $result = $stmt->get_result();

    $vincitoriTop10 = [];
    while ($row = $result->fetch_assoc()) {
        $vincitoriTop10[$row['username']] = (int)$row['vittorie'];
    }

    return $vincitoriTop10;
}


function downloadBestAverageStatistic()
{

    global $db;
    $maxPunteggioPossibile = 10;

    $query = "
        SELECT 
            u.username,
            ROUND(AVG(l.Punteggio) / ? * 100, 2) AS percentuale
        FROM 
            utente u
        LEFT JOIN 
            lancio l ON u.UserID = l.CodiceUtente
        GROUP BY 
            u.UserID
        HAVING 
            COUNT(l.Punteggio) > 0
        ORDER BY 
            percentuale DESC
        LIMIT 10
    ";

    $stmt = $db->prepare($query);
    $stmt->bind_param("d", $maxPunteggioPossibile);

    $stmt->execute();

    $result = $stmt->get_result();

    $utentiTop10 = [];
    while ($row = $result->fetch_assoc()) {
        $utentiTop10[$row['username']] = $row['percentuale'] . '%';
    }

    return $utentiTop10;

}


function downloadBestStrikeRateStatistic() {
    global $db;

    $query = "
        SELECT 
            u.username,
            ROUND(
                (SUM(CASE WHEN LOWER(l.TipoLancio) = 'strike' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 
                2
            ) AS percentualeStrike
        FROM 
            utente u
        LEFT JOIN 
            lancio l ON u.UserID = l.CodiceUtente
        GROUP BY 
            u.UserID
        HAVING 
            COUNT(l.TipoLancio) > 0
        ORDER BY 
            percentualeStrike DESC
        LIMIT 10
    ";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    $utentiTop10 = [];
    while ($row = $result->fetch_assoc()) {
        $utentiTop10[$row['username']] = $row['percentualeStrike'] . '%';
    }

    return $utentiTop10;
}

function downloadBestSpareRateStatistic() {
    global $db;

    $query = "
        SELECT 
            u.username,
            ROUND(
                (SUM(CASE WHEN l.TipoLancio = 'spare' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 
                2
            ) AS percentualeSpare
        FROM 
            utente u
        LEFT JOIN 
            lancio l ON u.UserID = l.CodiceUtente
        GROUP BY 
            u.UserID
        HAVING 
            COUNT(l.TipoLancio) > 0
        ORDER BY 
            percentualeSpare DESC
        LIMIT 10
    ";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    $utentiTop10 = [];
    while ($row = $result->fetch_assoc()) {
        $utentiTop10[$row['username']] = $row['percentualeSpare'] . '%';
    }

    return $utentiTop10;
}

function downloadBestPinfallRates(){
    global $db;

    $query = "
        SELECT 
            u.username,
            ROUND(SUM(l.Punteggio) / COUNT(*), 2) AS mediaPinfall
        FROM 
            utente u
        LEFT JOIN 
            lancio l ON u.UserID = l.CodiceUtente
        GROUP BY 
            u.UserID
        HAVING 
            COUNT(l.Punteggio) > 0
        ORDER BY 
            mediaPinfall DESC
        LIMIT 10
    ";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    $utentiTop10 = [];
    while ($row = $result->fetch_assoc()) {
        $utentiTop10[$row['username']] = $row['mediaPinfall'];
    }

    return $utentiTop10;
}



function getAverageScore($userID) {
    global $db;

    $query = "SELECT AVG(Punteggio) AS averageScore FROM lancio WHERE CodiceUtente = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return number_format($row['averageScore'], 2, '.', '') ?? 0;
}

function getStrikeRate($userID) {
    global $db;

    $query = "
        SELECT 
            COUNT(CASE WHEN TipoLancio = 'strike' THEN 1 END) AS NumStrike,
            COUNT(TipoLancio) AS NumTotali
        FROM lancio
        WHERE CodiceUtente = ?
    ";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $strikeRate = ($row['NumTotali'] > 0) ? ($row['NumStrike'] / $row['NumTotali']) * 100 : 0;

    return number_format($strikeRate, 2, '.', '') . '%';
}


function getSpareRate($userID) {
    global $db;

    $query = "
        SELECT 
            COUNT(CASE WHEN TipoLancio = 'spare' THEN 1 END) AS NumSpare,
            COUNT(TipoLancio) AS NumTotali
        FROM lancio
        WHERE CodiceUtente = ?
    ";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $spareRate = ($row['NumTotali'] > 0) ? ($row['NumSpare'] / $row['NumTotali']) * 100 : 0;

    return number_format($spareRate, 2, '.', '') . '%';
}


function getHighGame($userID) {
    global $db;

    $query = "SELECT MAX(PunteggioSessione) AS highGame FROM sessione WHERE CodiceUtente = ? AND NumeroSessione = 9";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['highGame'] ?? 0;
}


function getFirstBallAverage($userID) {
    global $db;

    $query = "
        SELECT AVG(Punteggio) AS firstBallAverage
        FROM lancio
        WHERE CodiceUtente = ? AND NumeroLancio = 1
    ";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return number_format($row['firstBallAverage'], 2, '.', '') ?? 0;
}



function getCleanGame($userID) {
    global $db;

    $query = "
        SELECT COUNT(*) AS cleanGames
        FROM partita p
        JOIN lancio l ON p.IdPartita = l.CodicePartita
        WHERE l.CodiceUtente = ? AND l.TipoLancio = 'strike'
        GROUP BY p.IdPartita
        HAVING COUNT(CASE WHEN l.TipoLancio = 'strike' THEN 1 END) = 10
    ";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows;
}


function getCleanFramePercentage($userID) {
    global $db;

    $query = "
        SELECT 
            COUNT(CASE WHEN TipoLancio = 'strike' THEN 1 END) AS CleanFrames,
            COUNT(*) AS TotalFrames
        FROM lancio
        WHERE CodiceUtente = ?
    ";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $cleanFramePercentage = ($row['TotalFrames'] > 0) ? ($row['CleanFrames'] / $row['TotalFrames']) * 100 : 0;

    return number_format($cleanFramePercentage, 2, '.', '') . '%';
}


function getUserStatistics($userID) {
    return [
        'averageScore' => getAverageScore($userID),
        'strikeRate' => getStrikeRate($userID),
        'spareRate' => getSpareRate($userID),
        'highGame' => getHighGame($userID),
        'firstBallAverage' => getFirstBallAverage($userID),
        'cleanGame' => getCleanGame($userID),
        'cleanFramePercentage' => getCleanFramePercentage($userID),
    ];
}

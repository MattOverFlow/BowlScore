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

    $query = "SELECT IdPartita FROM partita WHERE Data BETWEEN ? AND ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $dataInizio, $dataFine);

    $stmt->execute();

    $result = $stmt->get_result();

    $vincitori = [];

    foreach ($result as $row) {
        $idPartita = $row['IdPartita'];

        $queryPartite = "SELECT CodiceUtente FROM vittoria WHERE CodicePartita = ?";
        $stmtPartite = $db->prepare($queryPartite);
        $stmtPartite->bind_param("s", $idPartita);
        $stmtPartite->execute();
        $resultPartite = $stmtPartite->get_result();

        while ($rowPartite = $resultPartite->fetch_assoc()) {
            $codiceUtente = $rowPartite['CodiceUtente'];

            $datiUtente = scaricaUtente($codiceUtente);
            if ($datiUtente === null) {
                continue;
            }

            $username = $datiUtente['username'];

            if (isset($vincitori[$username])) {
                $vincitori[$username]++;
            } else {
                $vincitori[$username] = 1;
            }
        }
    }

    arsort($vincitori);

    $vincitoriTop10 = array_slice($vincitori, 0, 10, true);

    return $vincitoriTop10;
}


function downloadBestAverageStatistic()
{

    global $db;
    $query = "SELECT UserID FROM utente WHERE 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $utenti = [];
    $maxPunteggioPossibile = 10; // Cambia questo valore in base al massimo punteggio possibile per un lancio

    foreach ($result as $row) {
        $codiceUtente = $row['UserID'];

        // Query per ottenere i punteggi dei lanci dell'utente
        $queryLanci = "SELECT Punteggio FROM lancio WHERE CodiceUtente = ?";
        $stmtLanci = $db->prepare($queryLanci);
        $stmtLanci->bind_param("s", $codiceUtente);
        $stmtLanci->execute();
        $resultLanci = $stmtLanci->get_result();

        $punteggioTotale = 0;
        $numeroLanci = 0;

        // Calcola il punteggio totale e il numero di lanci
        while ($rowLanci = $resultLanci->fetch_assoc()) {
            $punteggioTotale += $rowLanci['Punteggio'];
            $numeroLanci++;
        }

        // Calcola la media
        if ($numeroLanci > 0) {
            $mediaPunteggio = $punteggioTotale / $numeroLanci;
        } else {
            $mediaPunteggio = 0;
        }

        // Calcola la percentuale rispetto al massimo punteggio possibile
        $percentuale = ($mediaPunteggio / $maxPunteggioPossibile) * 100;

        // Scarica lo username dell'utente
        $datiUtente = scaricaUtente($codiceUtente);
        if ($datiUtente === null) {
            continue; // Salta se l'utente non esiste
        }

        $username = $datiUtente['username'];

        // Salva i dati dell'utente
        $utenti[$username] = number_format($percentuale, 2, '.', '') . '%';
    }

    // Ordina l'array in modo decrescente per valore
    arsort($utenti);

    // Prendi i primi 10 risultati
    $utentiTop10 = array_slice($utenti, 0, 10, true);

    return $utentiTop10;

}


function downloadBestStrikeRateStatistic() {
    global $db;

    $query = "SELECT UserID FROM utente WHERE 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $utenti = [];

    foreach ($result as $row) {
        $codiceUtente = $row['UserID'];

        $queryStrike = "SELECT COUNT(*) AS NumStrike FROM lancio WHERE CodiceUtente = ? AND LOWER(TipoLancio) = 'strike'";
        $stmtStrike = $db->prepare($queryStrike);
        $stmtStrike->bind_param("s", $codiceUtente);
        $stmtStrike->execute();
        $resultStrike = $stmtStrike->get_result();
        $numStrike = $resultStrike->fetch_assoc()['NumStrike'] ?? 0;

        $queryTotali = "SELECT COUNT(*) AS NumTotali FROM lancio WHERE CodiceUtente = ?";
        $stmtTotali = $db->prepare($queryTotali);
        $stmtTotali->bind_param("s", $codiceUtente);
        $stmtTotali->execute();
        $resultTotali = $stmtTotali->get_result();
        $numTotali = $resultTotali->fetch_assoc()['NumTotali'] ?? 0;

        $percentualeStrike = ($numTotali > 0) ? ($numStrike / $numTotali) * 100 : 0;

        $datiUtente = scaricaUtente($codiceUtente);
        if ($datiUtente === null) {
            continue;
        }

        $username = $datiUtente['username'];

        // Formatta la percentuale con due cifre decimali e aggiungi il simbolo %
        $utenti[$username] = number_format($percentualeStrike, 2, '.', '') . '%';
    }

    // Ordina l'array in modo decrescente per valore
    arsort($utenti);

    // Prendi i primi 10 risultati
    $utentiTop10 = array_slice($utenti, 0, 10, true);

    return $utentiTop10;
}

function downloadBestSpareRateStatistic() {
    global $db;

    $query = "SELECT UserID FROM utente WHERE 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $utenti = [];

    foreach ($result as $row) {
        $codiceUtente = $row['UserID'];
    
        $querySpare = "SELECT COUNT(*) AS NumSpare FROM lancio WHERE CodiceUtente = ? AND TipoLancio = 'spare'";
        $stmtSpare = $db->prepare($querySpare);
        $stmtSpare->bind_param("s", $codiceUtente);
        $stmtSpare->execute();
        $resultSpare = $stmtSpare->get_result();
        $numSpare = $resultSpare->fetch_assoc()['NumSpare'] ?? 0;
    
        $queryTotali = "SELECT COUNT(*) AS NumTotali FROM lancio WHERE CodiceUtente = ?";
        $stmtTotali = $db->prepare($queryTotali);
        $stmtTotali->bind_param("s", $codiceUtente);
        $stmtTotali->execute();
        $resultTotali = $stmtTotali->get_result();
        $numTotali = $resultTotali->fetch_assoc()['NumTotali'] ?? 0;
    
        $percentualeSpare = ($numTotali > 0) ? ($numSpare / $numTotali) * 100 : 0;
    
        $datiUtente = scaricaUtente($codiceUtente);
        if ($datiUtente === null) {
            continue; 
        }
    
        $username = $datiUtente['username'];
    
        $utenti[$username] = number_format($percentualeSpare, 2, '.', ''). '%';
    }
    
    // Ordina l'array in modo decrescente per valore (media pinfall)
    arsort($utenti);

    // Prendi i primi 10 risultati
    $utentiTop10 = array_slice($utenti, 0, 10, true);

    return $utentiTop10;
}

function downloadBestPinfallRates(){
    global $db;

    $query = "SELECT UserID FROM utente WHERE 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $utenti = [];

    foreach ($result as $row) {
        $codiceUtente = $row['UserID'];
    
        $queryPinfall = "SELECT SUM(Punteggio) AS TotalPinfall, COUNT(*) AS NumTotali FROM lancio WHERE CodiceUtente = ?";
        $stmtPinfall = $db->prepare($queryPinfall);
        $stmtPinfall->bind_param("s", $codiceUtente);
        $stmtPinfall->execute();
        $resultPinfall = $stmtPinfall->get_result();
        $rowPinfall = $resultPinfall->fetch_assoc();

        $totalPinfall = $rowPinfall['TotalPinfall'] ?? 0;
        $numTotali = $rowPinfall['NumTotali'] ?? 0;

        $mediaPinfall = ($numTotali > 0) ? ($totalPinfall / $numTotali) : 0;

        $datiUtente = scaricaUtente($codiceUtente);
        if ($datiUtente === null) {
            continue; 
        }

        $username = $datiUtente['username'];

        $utenti[$username] = number_format($mediaPinfall, 2, '.', '');
    }
    
    // Ordina l'array in modo decrescente per valore (media pinfall)
    arsort($utenti);

    // Prendi i primi 10 risultati
    $utentiTop10 = array_slice($utenti, 0, 10, true);

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

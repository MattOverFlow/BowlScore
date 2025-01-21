<?php
include_once(__DIR__ . "/../Utils/bootstrap.php");
sec_session_start();
include_once(__DIR__ . "/../Database/User.php");

function scaricaPartita($idPartita) {
    global $db;

    $datiPartita = [];

    $query = "
        SELECT 
            u.Username, 
            s.IdSessione, 
            s.NumeroSessione, 
            s.PunteggioSessione, 
            l.NumeroLancio, 
            l.Punteggio, 
            l.TipoLancio
        FROM partecipazione p
        JOIN utente u ON p.CodiceUtente = u.UserID
        JOIN sessione s ON p.CodicePartita = s.CodicePartita 
                         AND p.CodiceUtente = s.CodiceUtente 
                         AND p.IdPartecipazione = s.CodicePartecipazione
        LEFT JOIN lancio l ON s.CodicePartita = l.CodicePartita 
                           AND s.CodiceUtente = l.CodiceUtente 
                           AND s.CodicePartecipazione = l.CodicePartecipazione 
                           AND s.IdSessione = l.CodiceSessione
        WHERE p.CodicePartita = ?
    ";

    $stmt = $db->prepare($query);
    
    if ($stmt === false) {
        die('Errore nella preparazione della query: ' . $db->error);
    }

    $stmt->bind_param("s", $idPartita);

    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $username = $row['Username'];
        $idSessione = $row['IdSessione'];
        $numeroSessione = $row['NumeroSessione'];
        $punteggioSessione = $row['PunteggioSessione'];
        $numeroLancio = $row['NumeroLancio'];
        $punteggioLancio = $row['Punteggio'];
        $tipoLancio = $row['TipoLancio'];

        if (!isset($datiPartita[$username])) {
            $datiPartita[$username] = [
                'username' => $username,
                'sessioni' => []
            ];
        }

        if (!isset($datiPartita[$username]['sessioni'][$idSessione])) {
            $datiPartita[$username]['sessioni'][$idSessione] = [
                'idSessione' => $idSessione,
                'numeroSessione' => $numeroSessione,
                'punteggioSessione' => $punteggioSessione,
                'lanci' => []
            ];
        }

        $datiPartita[$username]['sessioni'][$idSessione]['lanci'][] = [
            'numeroLancio' => $numeroLancio,
            'punteggio' => $punteggioLancio,
            'tipoLancio' => $tipoLancio
        ];
    }

    foreach ($datiPartita as $username => $userData) {
        usort($datiPartita[$username]['sessioni'], function($a, $b) {
            return $a['numeroSessione'] - $b['numeroSessione'];
        });

        foreach ($datiPartita[$username]['sessioni'] as $idSessione => $sessionData) {
            usort($datiPartita[$username]['sessioni'][$idSessione]['lanci'], function($a, $b) {
                return $a['numeroLancio'] - $b['numeroLancio'];
            });
        }
    }

    return $datiPartita;
}

function assegnaVincitorePartita($idPartita, $codiceUtente){
    global $db;

    $query = "INSERT INTO vittoria (CodicePartita, CodiceUtente) VALUES (?, ?)";

    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $idPartita, $codiceUtente);
    $stmt->execute();
    $stmt->close();
}


function ricercaPartite($searchInput, $startDate, $endDate, $userid) {
    global $db;

    // Query per ottenere le partecipazioni
    $query = "SELECT * FROM partecipazione WHERE CodiceUtente = ? AND CodiceTorneoSingolo IS NULL AND CodiceTorneoSquadre IS NULL";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    $partite = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            $codicePartita = $row['CodicePartita'];

            // Query per ottenere i dettagli della partita
            $queryPartita = "SELECT * FROM partita WHERE IdPartita = ?";
            $params = [$codicePartita];

            if (!empty($searchInput)) {
                $queryPartita .= " AND NomePartita LIKE ?";
                $params[] = "%$searchInput%";
            }
            if (!empty($startDate)) {
                $queryPartita .= " AND Data >= ?";
                $params[] = $startDate;
            }
            if (!empty($endDate)) {
                $queryPartita .= " AND Data <= ?";
                $params[] = $endDate;
            }

            $stmtPartita = $db->prepare($queryPartita);
            
            // Dinamico bind dei parametri
            $types = str_repeat("s", count($params));
            $stmtPartita->bind_param($types, ...$params);
            $stmtPartita->execute();

            $resultPartita = $stmtPartita->get_result();

            // Aggiungi i dettagli della partita alla lista
            while ($partita = $resultPartita->fetch_assoc()) {
                $partite[] = [
                    'nome' => $partita['NomePartita'],
                    'id' => $partita['IdPartita'],
                    'data' => $partita['Data'],
                    'numeroGiocatori' => $partita['NumeroGiocatori']
                ];
            }
        }
    } else {
        return null;
    }

    return $partite;
}

?>
<?php

require_once(__DIR__ . "/../Utils/bootstrap.php");
sec_session_start();

# Funzione per creare una carta
function creaCarta($userid, $dataCreazione, $dataScadenza)
{
    $db = getDb();

    $query = "INSERT INTO carta (CodiceUtente, DataCreazione, DataScadenza)
          VALUES (?, ?, ?)";

    $stmt = $db->prepare($query);
    $stmt->bind_param("sss", $userid, $dataCreazione, $dataScadenza);

    if ($stmt->execute()) {
        echo "Carta creata con successo!";
        return true;
    } else {
        echo "Errore nell'inserimento: " . $stmt->error;
        return false;
    }

    $stmt->close();
}

# Funzione per eliminare la carta di un utente
function eliminaCarta($userid)
{
    $db = getDb();

    $query = "DELETE FROM carta WHERE CodiceUtente = ? ";

    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userid);

    if ($stmt->execute()) {
        echo "Carta eliminata con successo!";
        return true;
    } else {
        echo "Errore nell'eliminazione: " . $stmt->error;
        return false;
    }

    $stmt->close();
}

# Funzione per recuperare i dati della carta di un utente
function datiCarta($userid)
{
    $db = getDb();
    $query = "SELECT Identificativo,DataCreazione,DataScadenza,PartiteTotali FROM carta WHERE CodiceUtente = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        return null;
    }

    $identificativo = "";
    $dataCreazione = "";
    $dataScadenza = "";
    $partiteTotali = "";
    $stmt->bind_result($identificativo, $dataCreazione, $dataScadenza, $partiteTotali);
    $stmt->fetch();

    $stmt->close();
    return [
        "identificativo" => $identificativo,
        "dataCreazione" => $dataCreazione,
        "dataScadenza" => $dataScadenza,
        "partiteTotali" => $partiteTotali
    ];
}

# Funzione per creare un abbonamento
function creaAbbonamento($userid, $identificativoCarta, $dataAcquisto, $dataScadenza, $sconto = NULL, $codiceTipo)
{
    $db = getDb();

    // Modifica la query per permettere un valore NULL per lo sconto
    $query = "INSERT INTO abbonamento (CodiceUtente, IdentificativoCarta,DataAcquisto, DataScadenza, Sconto, CodiceTipo)
              VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $db->prepare($query);

    // Controlla se lo sconto è stato fornito, altrimenti usa NULL
    if ($sconto === NULL) {
        $stmt->bind_param("ssssis", $userid, $identificativoCarta, $dataAcquisto, $dataScadenza, $sconto, $codiceTipo);
    } else {
        // Se lo sconto è stato fornito, lo leggi come stringa
        $stmt->bind_param("ssssis", $userid, $identificativoCarta, $dataAcquisto, $dataScadenza, $sconto, $codiceTipo);
    }

    if ($stmt->execute()) {
        echo "Abbonamento creato con successo!";
        return true;
    } else {
        echo "Errore nell'inserimento: " . $stmt->error;
        return false;
    }

    $stmt->close();
}

function rimuoviAbbonamento($userid)
{
    $db = getDb();

    $query = "DELETE FROM abbonamento WHERE CodiceUtente = ? ";

    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userid);

    if ($stmt->execute()) {
        echo "Abbonamento rimosso con successo!";
        return true;
    } else {
        echo "Errore nella rimozione: " . $stmt->error;
        return false;
    }

    $stmt->close();
}

# Funzione per scaricare i dati dell'abbonamento di un utente
function datiAbbonamento($userid)
{

    $db = getDb();
    $query = "SELECT IdentificativoCarta,DataAcquisto, DataScadenza, Sconto, CodiceTipo FROM abbonamento WHERE CodiceUtente = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        return null;
    }

    $identificativoCarta = "";
    $dataAcquisto = "";
    $dataScadenza = "";
    $sconto = "";
    $codiceTipo = "";
    $stmt->bind_result($identificativoCarta, $dataAcquisto, $dataScadenza, $sconto, $codiceTipo);
    $stmt->fetch();

    $stmt->close();
    return [
        "identificativoCarta" => $identificativoCarta,
        "dataAcquisto" => $dataAcquisto,
        "dataScadenza" => $dataScadenza,
        "sconto" => $sconto,
        "codiceTipo" => $codiceTipo
    ];
}

# registrare transazione pacchetto partite
function registraTransazionePacchettoPartite($userid, $codicePacchetto, $dataAcquisto, $sconto = NULL)
{
    $db = getDb();

    $datiCarta = datiCarta($userid);
    $identificativoCarta = $datiCarta['identificativo'];

    $query = "INSERT INTO acquisto_partite (CodiceUtente, CodicePacchetto, IdentificativoCarta, DataAcquisto, Sconto)
              VALUES (?, ?, ?, ?, ?)";

    $stmt = $db->prepare($query);
    $stmt->bind_param("ssssi", $userid, $codicePacchetto, $identificativoCarta, $dataAcquisto, $sconto);

    if ($stmt->execute()) {
        echo "Transazione pacchetto partite registrata con successo!";
        return true;
    } else {
        echo "Errore nell'inserimento: " . $stmt->error;
        return false;
    }

    $stmt->close();
}

function rimuoviTransazioni($userid)
{
    $db = getDb();

    $query = "DELETE FROM acquisto_partite WHERE CodiceUtente = ? ";

    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $userid);

    if ($stmt->execute()) {
        echo "Transazioni rimosse con successo!";
        return true;
    } else {
        echo "Errore nella rimozione: " . $stmt->error;
        return false;
    }

    $stmt->close();
}

# Funzione per aggiorna il numero di partite totali di un utente
function aggiornaPartiteTotali($userid, $partiteAggiuntive)
{
    $db = getDb();

    $datiCarta = datiCarta($userid);

    $partiteTotali = $datiCarta['partiteTotali'] + $partiteAggiuntive;


    $query = "UPDATE carta SET PartiteTotali = ? WHERE CodiceUtente = ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $partiteTotali, $userid);

    if ($stmt->execute()) {
        echo "Partite totali aggiornate con successo!";
        return true;
    } else {
        echo "Errore nell'aggiornamento: " . $stmt->error;
        return false;
    }

    $stmt->close();
}


# Funzione per scaricare i pacchetti partite
function downloadPacchetti()
{
    $db = getDb();
    $query = "SELECT * FROM pacchetto_partite";
    $stmt = $db->prepare($query);
    $success = $stmt->execute();

    if (!$success) {
        throw new \Exception("Error while querying the database: " . mysqli_error($db));
    }

    $result = $stmt->get_result();
    $pacchetti = array();

    if ($result->num_rows > 0) {
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_array();
            $pacchetto = [
                "codice" => $row['CodicePacchetto'],
                "numero_partite" => $row['NumeroPartite'],
                "prezzo" => $row['Prezzo']
            ];
            array_push($pacchetti, $pacchetto);
        }
        return $pacchetti;
    } else {
        return null;
    }
}

function trovaPacchetto($codicePacchetto)
{
    $db = getDb();
    $query = "SELECT * FROM pacchetto_partite WHERE CodicePacchetto = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $codicePacchetto);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        return null;
    }

    $codice = "";
    $numero_partite = "";
    $prezzo = "";
    $stmt->bind_result($codice, $numero_partite, $prezzo);
    $stmt->fetch();

    $stmt->close();
    return [
        "codice" => $codice,
        "numero_partite" => $numero_partite,
        "prezzo" => $prezzo
    ];
}


# Funzione per scaricare i tipi di abbonamento
function downloadTipoAbbonamenti()
{
    $db = getDb();
    $query = "SELECT * FROM tipo_abbonamento";
    $stmt = $db->prepare($query);
    $success = $stmt->execute();

    if (!$success) {
        throw new \Exception("Error while querying the database: " . mysqli_error($db));
    }

    $result = $stmt->get_result();
    $abbonamenti = array();

    if ($result->num_rows > 0) {
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_array();
            $abbonamento = [
                "codice" => $row['CodiceTipo'],
                "durata" => $row['Durata'],
                "prezzo" => $row['Prezzo']
            ];
            array_push($abbonamenti, $abbonamento);
        }
        return $abbonamenti;
    } else {
        return null;
    }
}

function cercaTipoAbbonamento($codiceTipo)
{
    $db = getDb();
    $query = "SELECT * FROM tipo_abbonamento WHERE CodiceTipo = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $codiceTipo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        return null;
    }

    $codice = "";
    $durata = "";
    $prezzo = "";
    $stmt->bind_result($codice, $prezzo, $durata);
    $stmt->fetch();

    $stmt->close();
    return [
        "codice" => $codice,
        "prezzo" => $prezzo,
        "durata" => $durata
    ];
}

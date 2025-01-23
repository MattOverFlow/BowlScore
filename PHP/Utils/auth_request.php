<?php
    include_once(__DIR__ . "/bootstrap.php");
    include_once(__DIR__ . "/../Database/Login.php");
    sec_session_start();

    if(!isset($_COOKIE['token'])) {
        header('Location: ../../HTML/Access/LoginPage.php');
        exit;
    } 
    try {
        $userid = $_COOKIE['token'];
        $_SESSION['userid'] = $userid;
        if (!isset($_SESSION['nome'])){
            scaricaInfoUser($_SESSION['userid']);
        }
        // Ottieni il tipo di utente dalla sessione
        $tipoUtente = $_SESSION['tipo'] ?? null;

        // Ottieni il nome del file corrente
        $currentFile = basename($_SERVER['PHP_SELF']);

        // File per il tipo "amministratore" che richiedono reindirizzamento
        $adminPagesRedirect = [
            "cardAndSubscription.php",
            "detailedMatchPage.php",
            "historyMatchPage.php",
            "historyTournaments.php",
            "userpage.php"
        ];

        // File per il tipo "utente" che richiedono reindirizzamento
        $userPagesRedirect = [
            "createGame.php",
            "createTournament.php",
            "createTeam.php",
            "manageTournaments.php",
            "manageTeams.php",
        ];

        // File che richiedono l'aggiunta di un parametro all'URL
        $parameterPages = [
            "generalStatistic.php",
            "searchPage.php"
        ];

        // Verifica se è amministratore e deve essere reindirizzato
        if ($tipoUtente === "amministratore" && in_array($currentFile, $adminPagesRedirect)) {
            header("Location: ../AdminProfile/createGame.php");
            exit;
        }

        // Verifica se è utente e deve essere reindirizzato
        if ($tipoUtente === "utente" && in_array($currentFile, $userPagesRedirect)) {
            header("Location: ../UserProfile/userpage.php");
            exit;
        }

        // Verifica se il file richiede l'aggiunta di un parametro all'URL
        if (in_array($currentFile, $parameterPages)) {
            $typeParam = ($tipoUtente === "amministratore") ? "admin" : "user";

            // Ottieni i parametri esistenti
            $queryString = $_SERVER['QUERY_STRING'];
            
            // Controlla se il parametro 'type' è già presente nell'URL
            if (strpos($queryString, 'type=') === false) {
                // Aggiungi o aggiorna il parametro "type"
                parse_str($queryString, $queryArray);
                $queryArray['type'] = $typeParam;
                
                // Ricostruisci l'URL con i parametri aggiornati
                $newQueryString = http_build_query($queryArray);
                $newUrl = $currentFile . "?" . $newQueryString;
                
                header("Location: $newUrl");
                exit;
            }
        }
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(array("error" => "Invalid token"));
        header('Location: ../../HTML/Access/LoginPage.php');
        exit();
    }
?>
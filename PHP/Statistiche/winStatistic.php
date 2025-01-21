<?php

include_once(__DIR__ . "/../Database/Statistiche.php");

$period = $_POST['period'];

// Ottieni la data corrente
$currentDate = new DateTime();

if ($period == "month") {
    $dataFine = $currentDate->format('Y-m-d H:i:s');
    $dataInizio = $currentDate->modify('-30 days')->format('Y-m-d H:i:s');
    $data = downloadWinStatistic($dataInizio, $dataFine);

} else if ($period == "week") {
    $dataFine = $currentDate->format('Y-m-d H:i:s');
    $dataInizio = $currentDate->modify('-7 days')->format('Y-m-d H:i:s');
    $data = downloadWinStatistic($dataInizio, $dataFine);

} else if ($period == "all") {
    $dataInizio = '1970-01-01 00:00:00'; 
    $dataFine = $currentDate->format('Y-m-d H:i:s');
    $data = downloadWinStatistic($dataInizio, $dataFine);

} else {
    echo json_encode(null);
    exit;
}

echo json_encode($data);

?>
<?php
include_once "../Database/Carte.php";

$result = downloadTipoAbbonamenti();

if ($result != null) {
    echo json_encode($result);
} else {
    echo json_encode(null);
}

?>
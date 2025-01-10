<?php

include_once(__DIR__ . "/../Database/Team.php");

echo json_encode(cercaInfoTeam($_POST['teamName']) ?? null);

?>
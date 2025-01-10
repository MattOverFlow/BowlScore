<?php

include_once("../Database/User.php");

echo json_encode(datiUtenteDaUsername($_POST["username"]));

?>
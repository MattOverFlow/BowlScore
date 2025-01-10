<?php

include_once("../Database/Team.php");

echo json_encode(verificaMembroTeam($_POST["userid"]));




?>
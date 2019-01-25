<?php
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");

$sql = "INSERT INTO trades VALUES(null, ".htmlspecialchars($_POST["selfuser"]).", "
        . "".htmlspecialchars($_POST["otheruser"]).", "
        . "".htmlspecialchars($_POST["selfcardid"]).", "
        . "".htmlspecialchars($_POST["othercardid"]).", "
        . "true)";

$statement = $GLOBALS['pdo']->prepare($sql);
$result = $statement->execute();
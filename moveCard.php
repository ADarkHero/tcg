<?php
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");


$sql = "UPDATE usersxcards SET UserID = ".htmlspecialchars($_POST["user"]).", "
        . "CardID = ".htmlspecialchars($_POST["cardid"]).", "
        . "StorageID = ".htmlspecialchars($_POST["idnew"])." "
        . "WHERE UserID = ".htmlspecialchars($_POST["user"])." "
        . "AND CardID = ".htmlspecialchars($_POST["cardid"])." "
        . "AND StorageID = ".htmlspecialchars($_POST["idold"])." "
        . "LIMIT 1";

$statement = $GLOBALS['pdo']->prepare($sql);
$result = $statement->execute();

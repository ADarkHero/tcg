<?php
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");

//Check, if the user was logged in
//You have to use check_user() on all internal pages
$user = check_user();

include("templates/header.inc.php");
?>

<div class="container main-container">

<h1><?php echo _("Cards"); ?></h1>

<div class="panel panel-default">
 

<?php 
$storageID = 6; //ID of "New"
if(isset($_GET["storage"])){
    $storageID = htmlspecialchars($_GET["storage"]);
}

?>
    


<ul class="nav nav-tabs">
<?php

    $sql = "SELECT * FROM storages";
    $statement = $pdo->prepare($sql);
    $result = $statement->execute();
    while($row = $statement->fetch()) {
    ?>
    <li class="nav-item <?php if($storageID == $row["StorageID"]){ echo "active"; }?>">
        <a class="nav-link" href="cards.php?storage=<?php echo $row["StorageID"]; ?>">
            <?php echo $row["StorageName"]; ?>
        </a>
    </li>
    <?php
    }

?>
</ul>

<div class="row">
    <?php
        $sql = "SELECT MasterShortName, CardMasterSubID "
            . "FROM usersxcards "
            . "INNER JOIN storages ON usersxcards.StorageID = storages.StorageID "
            . "INNER JOIN cards ON usersxcards.CardID = cards.CardID "
            . "INNER JOIN masters ON cards.MasterID = masters.MasterID "
            . "WHERE UserID = ".$user['id']." "
            . "AND usersxcards.StorageID = ".$storageID." ";
        $statement = $pdo->prepare($sql);
        $result = $statement->execute();
        while($row = $statement->fetch()) {
            displayCard($row['MasterShortName'], $row['CardMasterSubID']); 
        }
    ?> 
    
</div>
</div>
</div>
<?php 
include("templates/footer.inc.php")
?>

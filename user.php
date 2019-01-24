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

<?php
    $userNumber = $user['id'];
    if(isset($_GET["id"])){
        $userNumber = htmlspecialchars($_GET["id"]);
    }
?>
    
<h1><?php echo getUsername($userNumber)."'s "._("profile"); ?></h1>

<div class="panel panel-default">
    
<ul class="nav nav-tabs">
<?php 
    generateStorages($userNumber);
    $storageID = getStorageID();
?>
</ul>

<div class="container-fluid mt-2">
    <?php
        $sql = "SELECT cards.CardID, MasterShortName, CardMasterSubID "
            . "FROM usersxcards "
            . "INNER JOIN storages ON usersxcards.StorageID = storages.StorageID "
            . "INNER JOIN cards ON usersxcards.CardID = cards.CardID "
            . "INNER JOIN masters ON cards.MasterID = masters.MasterID "
            . "WHERE UserID = ".$userNumber." "
            . "AND usersxcards.StorageID = ".$storageID." ";
        $statement = $pdo->prepare($sql);
        $result = $statement->execute();
        while($row = $statement->fetch()) {
            displayCard($row['MasterShortName'], $row['CardMasterSubID'], $row['CardID']); 
        }
    ?> 
    
</div>
</div>
</div>
<?php 
include("templates/footer.inc.php")
?>

<script>
var dragcardid = ""; //Unique id of the card (z.B. 15)    
var cardname = ""; //Unique name of the card (z.B. bsfood10)    
    
function allowDrop(ev) {
  ev.preventDefault();
}

function drag(ev, cid, cname) {
  dragcardid = cid;
  cardname = cname;
}

function drop(ev, idnew, idold, userid) {
    ev.preventDefault(); //Prevents the browsers default drag/drop handling
  
    //Call moveCard, to move the card to a different storage
    $.post("moveCard.php", {cardid: dragcardid, idold: idold, idnew: idnew, user: userid}, function(data, status) {
        document.getElementById(cardname).remove(); //Remove the card from the ui
    }).fail(function(err, status) {
       <?php echo _("alert(\"There was a error while moving the card.\");"); ?>
    });

}
</script>
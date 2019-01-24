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
    if (isset($_GET["id"])) {
        $userNumber = htmlspecialchars($_GET["id"]);
    }
    ?>


    <h1><?php echo getUsername($userNumber) . "'s " . _("profile"); ?></h1>


    <?php
    generateStorages($userNumber);
    $storageID = getStorageID();
    ?>


    <div class="row">
        <?php
        $sql = "SELECT cards.CardID, MasterShortName, CardMasterSubID "
                . "FROM usersxcards "
                . "INNER JOIN storages ON usersxcards.StorageID = storages.StorageID "
                . "INNER JOIN cards ON usersxcards.CardID = cards.CardID "
                . "INNER JOIN masters ON cards.MasterID = masters.MasterID "
                . "WHERE UserID = " . $userNumber . " "
                . "AND usersxcards.StorageID = " . $storageID . " "
                . "ORDER BY MasterShortName, cards.CardID";
        $statement = $pdo->prepare($sql);
        $result = $statement->execute();
        while ($row = $statement->fetch()) {
            displayCard($row['MasterShortName'], $row['CardMasterSubID'], $row['CardID']);
        }
        ?> 
    </div>   
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                sdfa
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
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
        $.post("moveCard.php", {cardid: dragcardid, idold: idold, idnew: idnew, user: userid}, function (data, status) {
            document.getElementById(cardname).remove(); //Remove the card from the ui
        }).fail(function (err, status) {
<?php echo _("alert(\"There was a error while moving the card.\");"); ?>
        });

    }
</script>


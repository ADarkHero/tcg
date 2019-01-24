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
            displayStorageCards($userNumber, $storageID);
        ?> 
    </div>   
</div>

<!-- Modal -->
<div class="modal fade" id="tradeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo _("Trade card?"); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="col"><?php echo _("What do you want to give for ") ?><b id="tradeFor"></b>?</div>
          <div class="col">
              <?php listAllCards(); ?>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="tradeCard()"><?php echo _("Ask for trade"); ?></button>
      </div>
    </div>
  </div>
</div>

<?php
include("templates/footer.inc.php")
?>

<script>
    var cardid = ""; //Unique id of the card (z.B. 15)    
    var cardname = ""; //Unique name of the card (z.B. bsfood10)    

    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev, cid, cname) {
        cardid = cid;
        cardname = cname;
    }

    function drop(ev, idnew, idold, userid) {
        ev.preventDefault(); //Prevents the browsers default drag/drop handling

        //Call moveCard, to move the card to a different storage
        $.post("moveCard.php", {cardid: cardid, idold: idold, idnew: idnew, user: userid}, function (data, status) {
            document.getElementById(cardname).remove(); //Remove the card from the ui
        }).fail(function (err, status) {
            <?php echo _("alert(\"There was a error while moving the card.\");"); ?>
        });
    }
    
    //TODO: read selfcardid
    function tradeCard(){
        var selfcard = $( "#cardList option:selected" ).val();
    
        $.post("tradeCard.php", {selfcardid: selfcard, othercardid: cardid, selfuser: <?php echo $user["id"]; ?>, otheruser: <?php echo $userNumber; ?>}, function (data, status) {
            $('#tradeModal').modal('hide');
        }).fail(function (err, status) {
            <?php echo _("alert(\"There was a error while moving the card.\");"); ?>
        })
    }
    
    $('#tradeModal').on('show.bs.modal', function(e) {
        cardid = $(e.relatedTarget).data('card-id');
        cardname = $(e.relatedTarget).data('card-name');
        document.getElementById("tradeFor").innerHTML = cardname;
    });
</script>


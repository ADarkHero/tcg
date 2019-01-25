<?php
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");

//Check, if the user was logged in
//You have to use check_user() on all internal pages
$user = check_user();
$GLOBALS['user'] = $user;

include("templates/header.inc.php");
?>

<div class="container main-container">

    <h1><?php echo _("Your trades"); ?></h1>

    <?php
    if(isset($_GET["trade"]) && isset($_GET["msg"])){
        $tradeID = htmlspecialchars($_GET["trade"]);
        $tradeMSG = htmlspecialchars($_GET["msg"]);
        if($tradeMSG == "accept"){
            makeTrade($tradeID);
        }
        else if($tradeMSG == "cancel"){
            cancelTrade($tradeID);
        }
    }
    if (isset($_GET["msg"])) {
        ?>
            <?php 
                $msg = htmlspecialchars($_GET["msg"]);
                if($msg == "accept"){
                    echo '<div class="alert alert-success" role="alert">';
                    echo _("Trade successful!"); 
                }
                else if($msg == "cancel"){
                    echo '<div class="alert alert-warning" role="alert">';
                    echo _("Trade cancelled!"); 
                }
                
            ?>
        </div>
        <?php
    }
    ?>

    <div class="panel panel-default">
        <div class="container-fluid mt-2">
            <h2><?php echo _("Received trades"); ?></h2>
            <?php
            listYourTrades(false);
            ?>
            <h2><?php echo _("Sent trades"); ?></h2>
            <?php
            listYourTrades(true);
            ?>
        </div>
    </div>
</div>
<?php
include("templates/footer.inc.php")
?>

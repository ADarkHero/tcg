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

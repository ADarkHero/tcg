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

<h1><?php echo _("Memberlist"); ?></h1>

<div class="panel panel-default">
<div class="container-fluid mt-2">
    <?php
        listAllUsers();
    ?>
</div>
</div>
</div>
<?php 
include("templates/footer.inc.php")
?>

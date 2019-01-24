<?php
session_start();
session_destroy();
unset($_SESSION['userid']);

//Remove Cookies
setcookie("identifier", "", time() - (3600 * 24 * 365));
setcookie("securitytoken", "", time() - (3600 * 24 * 365));

require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");

include("templates/header.inc.php");
?>


<div class="container main-container">

    <br>    
    <h1>Logout</h1>

    <?php echo _("Logout successfull."); ?> <a href="index.php"><?php echo _("Back to homepage"); ?></a>.
</div>
<?php
include("templates/footer.inc.php")
?>
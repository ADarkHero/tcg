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

<h1><?php echo _("Games"); ?></h1>
<div class="panel panel-default">
<div class="container-fluid mt-2">
    
<?php
    getGames();
?>
    
</div>
</div>
</div>
<?php 
include("templates/footer.inc.php");

//Every game has a php file in the games directory
//This function displayes them
function getGames(){
    $files = scandir("games");
    
    
    foreach($files as $file){
        if(\strpos($file, '.php') !== false){
            ?>
                <a href="<?php echo "games/".$file; ?>"><?php echo _(substr($file, 0, -4)); ?></a>
                <br>
            <?php
        }
    }
}
?>

<?php 
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");
include("templates/header.inc.php")
?>

  

    

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>TCG</h1>
        <p><?php echo _("We trade cards"); ?></p>
        <p><a class="btn btn-primary btn-lg" href="register.php" role="button"><?php echo _("Register now"); ?></a>
        
        </p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>Features</h2>
          <ul>
          	<li><?php echo _("Play minigames to get cards"); ?></li> 
          	<li><?php echo _("Trade cards with other members"); ?></li>
          	<li><?php echo _("Collect card sets"); ?></li>
          </ul>
         
        </div>
        <div class="col-md-4">
          <h2><?php echo _("News"); ?></h2>
          <p></p>
          <p></p>
       </div>
        <div class="col-md-4">
          <h2>ADarkHero</h2>
          <p><?php echo _("You can find my GitHub <a href=\"https://github.com/ADarkHero/tcg\" target=\"_blank\">here</a>."); ?></p>
        </div>
      </div>
	</div> <!-- /container -->
      

  
<?php 
include("templates/footer.inc.php")
?>

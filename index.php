<?php 
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");
include("templates/header.inc.php")
?>

  

    

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Loginscript</h1>
        <p><?php echo _("Welcome to ADarkHeros version of the PHP-einfach loginscript."); ?>
        </p>
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
          	<li><?php echo _("Registration & login"); ?></li> 
          	<li><?php echo _("Internal area"); ?></li>
          	<li><?php echo _("New password per mail"); ?></li>
          	<li><?php echo _("Easy understandable and expandable"); ?></li>
          	<li><?php echo _("Responsive webdesign for pc, tablet and smartphone"); ?></li>
          </ul>
         
        </div>
        <div class="col-md-4">
          <h2>Dokumentation</h2>
          <p><?php echo _("You can find a documentation and beginner tips on the php-einfach website."); ?></p>
          <p><a class="btn btn-default" href="http://www.php-einfach.de/experte/php-codebeispiele/loginscript/" target="_blank" role="button"><?php echo _("Further information"); ?> &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>ADarkHero</h2>
          <p><?php echo _("You can find my GitHub <a href=\"https://github.com/ADarkHero/loginscript\" target=\"_blank\">here</a>."); ?></p>
        </div>
      </div>
	</div> <!-- /container -->
      

  
<?php 
include("templates/footer.inc.php")
?>

<?php 
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");

include("templates/header.inc.php");
?>
 <div class="container small-container-330">
	<h2><?php echo _("Forgot password"); ?></h2>


<?php 
$showForm = true;
 
if(isset($_GET['send']) ) {
	if(!isset($_POST['email']) || empty($_POST['email'])) {
		$error = "<b>"._("Please input your e-mail address!")."</b>";
	} else {
		$statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
		$result = $statement->execute(array('email' => $_POST['email']));
		$user = $statement->fetch();		
 
		if($user === false) {
			$error = "<b>". _("No user found!"). "</b>";
		} else {
			
			$passwordcode = random_string();
			$statement = $pdo->prepare("UPDATE users SET passwordcode = :passwordcode, passwordcode_time = NOW() WHERE id = :userid");
			$result = $statement->execute(array('passwordcode' => sha1($passwordcode), 'userid' => $user['id']));
			
			$empfaenger = $user['email'];
			$betreff = _("New password for your account!"); //Input the name of your domain here
			$from = _("From: ADarkHero <admin@adarkhero.de>"); //Add your Name and E-Mail
			$url_passwordcode = getSiteURL().'resetpassword.php?userid='.$user['id'].'&code='.$passwordcode; //Input domain here
			$text = 
                        _("Hello").' '.$user['username'].', '.
                        _("somebody asked for a new password. To change your password, visit the following website in the next 24 hours:")
                        .$url_passwordcode
                        ._("If you remember your password or if you didn't ask for this mail, you can ignore this.")
                        ._("Greetings,")
                        ."ADarkHero";
			
			//echo $text;
			 
			mail($empfaenger, $betreff, $text, $from);
 
			echo _("We sent a link to reset your password to your e-mail.");	
			$showForm = false;
		}
	}
}
 
if($showForm):
?> 
	<?php echo _("Input your e-mail adress, to get a new password."); ?><br><br>
	 
	<?php
	if(isset($error) && !empty($error)) {
		echo $error;
	}
	
	?>
	<form action="?send=1" method="post">
		<label for="inputEmail">E-Mail</label>
		<input class="form-control" placeholder="E-Mail" name="email" type="email" value="<?php echo isset($_POST['email']) ? htmlentities($_POST['email']) : ''; ?>" required>
		<br>
		<input  class="btn btn-lg btn-primary btn-block" type="submit" value="<?php echo _("New password"); ?>">
	</form> 
<?php
endif; //Endif von if($showForm)
?>

</div> <!-- /container -->
 

<?php 
include("templates/footer.inc.php")
?>
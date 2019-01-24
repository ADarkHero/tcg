<?php 
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");

$error_msg = "";
if(isset($_POST['emailusername']) && isset($_POST['passwort'])) {
	$emailusername = $_POST['emailusername'];
	$passwort = $_POST['passwort'];

	$statement = $pdo->prepare("SELECT * FROM users WHERE email = :emailusername OR username = :emailusername");
	$result = $statement->execute(array('emailusername' => $emailusername));
	$user = $statement->fetch();

	//Check the password
	if ($user !== false && password_verify($passwort, $user['password'])) {
		$_SESSION['userid'] = $user['id'];

		//Does the user want to remebered?
		if(isset($_POST['remember_me'])) {
			$identifier = random_string();
			$securitytoken = random_string();
				
			$insert = $pdo->prepare("INSERT INTO securitytokens (user_id, identifier, securitytoken) VALUES (:user_id, :identifier, :securitytoken)");
			$insert->execute(array('user_id' => $user['id'], 'identifier' => $identifier, 'securitytoken' => sha1($securitytoken)));
			setcookie("identifier",$identifier,time()+(3600*24*365)); //Valid for 1 year
			setcookie("securitytoken",$securitytoken,time()+(3600*24*365)); //Valid for 1 year
		}

		header("location: user.php");
		exit;
	} else {
		$error_msg =  _("E-mail or password incorrect")."<br><br>";
	}

}

$email_value = "";
if(isset($_POST['email']))
	$email_value = htmlentities($_POST['email']); 

include("templates/header.inc.php");
?>
 <div class="container small-container-330 form-signin">
  <form action="login.php" method="post">
	<h2 class="form-signin-heading">Login</h2>
	
<?php 
if(isset($error_msg) && !empty($error_msg)) {
	echo $error_msg;
}
?>
	<label for="inputEmail" class="sr-only">E-Mail</label>
	<input type="text" name="emailusername" id="inputEmail" class="form-control" placeholder="E-Mail" value="<?php echo $email_value; ?>" required autofocus>
	<label for="inputPassword" class="sr-only">Passwort</label>
	<input type="password" name="passwort" id="inputPassword" class="form-control" placeholder="Passwort" required>
	<div class="checkbox">
	  <label>
		<input type="checkbox" value="remember-me" name="remember_me" value="1" checked> <?php echo _("Remember me?"); ?>
	  </label>
	</div>
	<button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
	<br>
	<a href="passwortvergessen.php"><?php echo _("Forgot password?"); ?></a>
  </form>

</div> <!-- /container -->
 

<?php 
include("templates/footer.inc.php")
?>
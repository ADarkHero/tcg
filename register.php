<?php 
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");

include("templates/header.inc.php")
?>
<div class="container main-container registration-form">
<h1><?php echo _("Register"); ?></h1>
<?php
$showFormular = true; //Should the form be shown?
 
if(isset($_GET['register'])) {
	$error = false;
	$username = trim($_POST['username']);
	$email = trim($_POST['email']);
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
	
	if(empty($username) || empty($email)) {
		echo _("Please fill out every field.").'<br>';
		$error = true;
	}
  
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo _("Please input a valid e-mail.").'<br>';
		$error = true;
	} 	
	if(strlen($password) == 0) {
		echo _("Please input a password.").'<br>';
		$error = true;
	}
	if($password != $password2) {
		echo _("Both passwords must be the same.").'<br>';
		$error = true;
	}
	
	//Check, if the e-mail was already used
	if(!$error) { 
		$statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
		$result = $statement->execute(array('email' => $email));
		$user = $statement->fetch();
		
		if($user !== false) {
			echo _("The e-mail was already used.").'<br>';
			$error = true;
		}	
	}
	
	//No errors, user can be registered
	if(!$error) {	
		$password_hash = password_hash($password, PASSWORD_DEFAULT);
		
		$statement = $pdo->prepare("INSERT INTO users (email, password, username) VALUES (:email, :password, :username)");
		$result = $statement->execute(array('email' => $email, 'password' => $password_hash, 'username' => $username));
		
		if($result) {		
			echo _("You were successfully registered!").'<a href="login.php">Login</a>';
			$showFormular = false;
		} else {
			echo _("Error while saving your user.").'<br>';
		}
	} 
}
 
if($showFormular) {
?>

<form action="?register=1" method="post">

<div class="form-group">
<label for="inputUsername">Username:</label>
<input type="text" id="inputUsername" size="40" maxlength="250" name="username" class="form-control" required>
</div>

<div class="form-group">
<label for="inputEmail">E-Mail:</label>
<input type="email" id="inputEmail" size="40" maxlength="250" name="email" class="form-control" required>
</div>

<div class="form-group">
<label for="inputPasswort"><?php echo _("Your password:"); ?></label>
<input type="password" id="inputPasswort" size="40"  maxlength="250" name="password" class="form-control" required>
</div> 

<div class="form-group">
<label for="inputPasswort2"><?php echo _("Repeat password:"); ?></label>
<input type="password" id="inputPasswort2" size="40" maxlength="250" name="password2" class="form-control" required>
</div> 
<button type="submit" class="btn btn-lg btn-primary btn-block"><?php echo _("Register"); ?></button>
</form>
 
<?php
} //End of if($showFormular)
	

?>
</div>
<?php 
include("templates/footer.inc.php")
?>
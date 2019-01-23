<?php 
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");
if(!isset($_GET['userid']) || !isset($_GET['code'])) {
	error(_("No code to reset the password was transmitted."));
}



$showForm = true; 
$userid = $_GET['userid'];
$code = $_GET['code'];
 
//Check the user
$statement = $pdo->prepare("SELECT * FROM users WHERE id = :userid");
$result = $statement->execute(array('userid' => $userid));
$user = $statement->fetch();
 
//Check if the user was found and if he has a reset code.
if($user === null || $user['passwordcode'] === null) {
	error(_("The user was not found or they didn't request a password."));
}
 
if($user['passwordcode_time'] === null || strtotime($user['passwordcode_time']) < (time()-24*3600) ) {
	error(_("The code isn't valid anymore."));
}
 
 
//Check the password code
if(sha1($code) != $user['passwordcode']) {
	error("The code, you input, wasn't valid.");
}
 
//Der Code was correct
 
if(isset($_GET['send'])) {
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
	
	if($password != $password2) {
		$msg =  _("Please input identical passwords.");
	} else { //Speichere neues Passwort und lösche den Code
		$passwordhash = password_hash($password, PASSWORD_DEFAULT);
		$statement = $pdo->prepare("UPDATE users SET password = :passwordhash, passwordcode = NULL, passwordcode_time = NULL WHERE id = :userid");
		$result = $statement->execute(array('passwordhash' => $passwordhash, 'userid'=> $userid ));
		
		if($result) {
			$msg = "Your password was changed.";
			$showForm = false;
		}
	}
}

include("templates/header.inc.php");
?>

 <div class="container small-container-500">
 
<h1>Neues Passwort vergeben</h1>
<?php 
if(isset($msg)) {
	echo $msg;
}

if($showForm):
?>

<form action="?send=1&amp;userid=<?php echo htmlentities($userid); ?>&amp;code=<?php echo htmlentities($code); ?>" method="post">
<label for="password"><?php echo _("Please input your password:"); ?></label><br>
<input type="password" id="password" name="password" class="form-control" required><br>
 
<label for="password2"><?php echo _("Input password again:"); ?></label><br>
<input type="password" id="password2" name="password2" class="form-control" required><br>
 
<input type="submit" value="<?php echo _("Save password"); ?>" class="btn btn-lg btn-primary btn-block">
</form>
<?php 
endif;
?>

</div> <!-- /container -->
 

<?php 
include("templates/footer.inc.php")
?>
<?php
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");

//Check, if the user is logged in
$user = check_user();

include("templates/header.inc.php");

if(isset($_GET['save'])) {
	$save = $_GET['save'];
	
	if($save == 'personal_data') {
		$username = trim($_POST['username']);
		
		if($vorname == "") {
			$error_msg = _("Please input your username.");
		} else {
			$statement = $pdo->prepare("UPDATE users SET username = :username, updated_at=NOW() WHERE id = :userid");
			$result = $statement->execute(array('username' => $username,'userid' => $user['id'] ));
			
			$success_msg = _("Data successfully saved!");
		}
	} else if($save == 'email') {
		$password = $_POST['password'];
		$email = trim($_POST['email']);
		$email2 = trim($_POST['email2']);
		
		if($email != $email2) {
			$error_msg = _("The e-mail adresses are not identical.");
		} else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error_msg = _("Please use a vaild e-mail.");
		} else if(!password_verify($password, $user['password'])) {
			$error_msg = "Please input a correkt password.";
		} else {
			$statement = $pdo->prepare("UPDATE users SET email = :email WHERE id = :userid");
			$result = $statement->execute(array('email' => $email, 'userid' => $user['id'] ));
				
			$success_msg = _("E-mail adress successfully saved.");
		}
		
	} else if($save == 'password') {
		$passwordAlt = $_POST['passwordAlt'];
		$passwordNeu = trim($_POST['passwordNeu']);
		$passwordNeu2 = trim($_POST['passwordNeu2']);
		
		if($passwordNeu != $passwordNeu2) {
			$error_msg = _("The passwords don't match.");
		} else if($passwordNeu == "") {
			$error_msg = _("The password must be filled.");
		} else if(!password_verify($passwordAlt, $user['password'])) {
			$error_msg = _("Please input a correct password.");
		} else {
			$password_hash = password_hash($passwordNeu, PASSWORD_DEFAULT);
				
			$statement = $pdo->prepare("UPDATE users SET password = :password WHERE id = :userid");
			$result = $statement->execute(array('password' => $password_hash, 'userid' => $user['id'] ));
				
			$success_msg = _("Password successfully saved.");
		}
		
	}
}

$user = check_user();

?>

<div class="container main-container">

<h1><?php echo _("Settings"); ?></h1>

<?php 
if(isset($success_msg) && !empty($success_msg)):
?>
	<div class="alert alert-success">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	  	<?php echo $success_msg; ?>
	</div>
<?php 
endif;
?>

<?php 
if(isset($error_msg) && !empty($error_msg)):
?>
	<div class="alert alert-danger">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	  	<?php echo $error_msg; ?>
	</div>
<?php 
endif;
?>

<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#data" aria-controls="home" role="tab" data-toggle="tab"><?php echo _("Personal data"); ?></a></li>
    <li role="presentation"><a href="#email" aria-controls="profile" role="tab" data-toggle="tab">E-Mail</a></li>
    <li role="presentation"><a href="#password" aria-controls="messages" role="tab" data-toggle="tab"><?php echo _("Password"); ?></a></li>
  </ul>

  <!-- Persönliche Daten-->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="data">
    	<br>
    	<form action="?save=personal_data" method="post" class="form-horizontal">
    		<div class="form-group">
    			<label for="inputUsername" class="col-sm-2 control-label">Username</label>
    			<div class="col-sm-10">
    				<input class="form-control" id="inputUsername" name="username" type="text" value="<?php echo htmlentities($user['username']); ?>" required>
    			</div>
    		</div>
    		
    		
    		<div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" class="btn btn-primary"><?php echo _("Save"); ?></button>
			    </div>
			</div>
    	</form>
    </div>
    
    <!-- Änderung der E-Mail-Adresse -->
    <div role="tabpanel" class="tab-pane" id="email">
    	<br>
    	<p><?php _("To change your e-mail, please input your password and your e-mail address."); ?></p>
    	<form action="?save=email" method="post" class="form-horizontal">
    		<div class="form-group">
    			<label for="inputPasswort" class="col-sm-2 control-label"><?php echo _("Password"); ?></label>
    			<div class="col-sm-10">
    				<input class="form-control" id="inputPasswort" name="password" type="password" required>
    			</div>
    		</div>
    		
    		<div class="form-group">
    			<label for="inputEmail" class="col-sm-2 control-label">E-Mail</label>
    			<div class="col-sm-10">
    				<input class="form-control" id="inputEmail" name="email" type="email" value="<?php echo htmlentities($user['email']); ?>" required>
    			</div>
    		</div>
    		
    		
    		<div class="form-group">
    			<label for="inputEmail2" class="col-sm-2 control-label">E-Mail (<?php echo _("again"); ?>)</label>
    			<div class="col-sm-10">
    				<input class="form-control" id="inputEmail2" name="email2" type="email"  required>
    			</div>
    		</div>
    		
    		<div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" class="btn btn-primary"><?php echo _("Save"); ?></button>
			    </div>
			</div>
    	</form>
    </div>
    
    <!-- Änderung des Passworts -->
    <div role="tabpanel" class="tab-pane" id="password">
    	<br>
    	<p><?php _("To change your password, please input your old password and your new password."); ?></p>
    	<form action="?save=password" method="post" class="form-horizontal">
    		<div class="form-group">
    			<label for="inputPasswort" class="col-sm-2 control-label"><?php echo _("Old password"); ?></label>
    			<div class="col-sm-10">
    				<input class="form-control" id="inputPasswort" name="passwordAlt" type="password" required>
    			</div>
    		</div>
    		
    		<div class="form-group">
    			<label for="inputPasswortNeu" class="col-sm-2 control-label"><?php echo _("New password"); ?></label>
    			<div class="col-sm-10">
    				<input class="form-control" id="inputPasswortNeu" name="passwordNeu" type="password" required>
    			</div>
    		</div>
    		
    		
    		<div class="form-group">
    			<label for="inputPasswortNeu2" class="col-sm-2 control-label">Neues Passwort (<?php echo _("again"); ?>)</label>
    			<div class="col-sm-10">
    				<input class="form-control" id="inputPasswortNeu2" name="passwordNeu2" type="password"  required>
    			</div>
    		</div>
    		
    		<div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" class="btn btn-primary"><?php echo _("Save"); ?></button>
			    </div>
			</div>
    	</form>
    </div>
  </div>

</div>


</div>
<?php 
include("templates/footer.inc.php")
?>

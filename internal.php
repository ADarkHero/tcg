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

<h1><?php echo _("Welcome!"); ?></h1>

<?php echo _("Hello"); ?> <?php echo htmlentities($user['username']); ?>,<br>
<?php echo _("welcome to the internal area!"); ?><br><br>

<div class="panel panel-default">
 
<table class="table">
<tr>
	<th>#</th>
	<th>Username</th>
	<th>E-Mail</th>
</tr>
<?php 
$statement = $pdo->prepare("SELECT * FROM users ORDER BY id");
$result = $statement->execute();
$count = 1;
while($row = $statement->fetch()) {
	echo "<tr>";
	echo "<td>".$count++."</td>";
	echo "<td>".$row['username']."</td>";
	echo '<td><a href="mailto:'.$row['email'].'">'.$row['email'].'</a></td>';
	echo "</tr>";
}
?>
</table>
</div>


</div>
<?php 
include("templates/footer.inc.php")
?>

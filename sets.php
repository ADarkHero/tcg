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

<h1><?php echo _("Cards"); ?></h1>

<div class="panel panel-default">
 

<?php 
$categoryID = 1;
if(isset($_GET["category"])){
    $categoryID = htmlspecialchars($_GET["category"]);
}

?>
    


<ul class="nav nav-tabs">
<?php

    $sql = "SELECT * FROM categories";
    $statement = $pdo->prepare($sql);
    $result = $statement->execute();
    while($row = $statement->fetch()) {
    ?>
    <li class="nav-item <?php if($categoryID == $row["CategoryID"]){ echo "active"; }?>">
        <a class="nav-link" href="cards.php?category=<?php echo $row["CategoryID"]; ?>">
            <?php echo $row["CategoryName"]; ?>
        </a>
    </li>
    <?php
    }

?>
</ul>

<div class="container">
<div class="row">
    <?php
        $sql = "SELECT MasterID, MasterShortName FROM masters";
        $statement = $pdo->prepare($sql);
        $result = $statement->execute();
        while($row = $statement->fetch()) {
        ?>
            <div class="col-xs-3 col-sm-2 col-lg-1">
                <a href="#" target="_blank">
                    <figure class="figure">
                        <img width="100%" src="img/masters/<?php echo $row['MasterShortName']; ?>.jpg" 
                             class="figure-img img-fluid rounded" 
                             alt="<?php echo $row['MasterShortName'] ?>">
                        <figcaption class="figure-caption"><?php echo $row['MasterShortName']; ?></figcaption>
                    </figure>
                </a>
            </div>    
        <?php
        }
    ?> 
    
</div>
</div>
</div>
</div>
<?php 
include("templates/footer.inc.php")
?>

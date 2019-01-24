<?php
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");

//Check, if the user was logged in
//You have to use check_user() on all internal pages
$user = check_user();

include("templates/header.inc.php");
?>

<div class="container">

    <h1><?php echo _("Cardsets"); ?></h1>



    <?php
    $categoryID = 1;
    if (isset($_GET["category"])) {
        $categoryID = htmlspecialchars($_GET["category"]);
    }
    ?>
    <ul class="nav nav-tabs">
        <?php
        $sql = "SELECT * FROM categories";
        $statement = $pdo->prepare($sql);
        $result = $statement->execute();
        while ($row = $statement->fetch()) {
            ?>
            <li class="nav-item">
                <a class="nav-link <?php if ($categoryID == $row["CategoryID"]) {
            echo "active";
        } ?>" href="sets.php?category=<?php echo $row["CategoryID"]; ?>">
                    <?php echo $row["CategoryName"]; ?>
                </a>
            </li>
            <?php
        }
        ?>
    </ul>

    <div class="container-fluid mt-2">
        <?php
        $sql = "SELECT MasterID, MasterShortName FROM masters "
                . "WHERE CategoryID = " . $categoryID;
        $statement = $pdo->prepare($sql);
        $result = $statement->execute();
        while ($row = $statement->fetch()) {
            displayCardSet($row['MasterShortName'], $row['MasterID']);
        }
        ?> 
    </div>
</div>
<?php
include("templates/footer.inc.php")
?>

<?php
/**
 * A complete login script with registration and members area.
 *
 * @author: Nils Reimers / http://www.php-einfach.de/experte/php-codebeispiele/loginscript/
 * @license: GNU GPLv3
 */
include_once("password.inc.php");

/**
 * Checks that the user is logged in. 
 * @return Returns the row of the logged in user
 */
function check_user() {
    global $pdo;

    if (!isset($_SESSION['userid']) && isset($_COOKIE['identifier']) && isset($_COOKIE['securitytoken'])) {
        $identifier = $_COOKIE['identifier'];
        $securitytoken = $_COOKIE['securitytoken'];

        $statement = $pdo->prepare("SELECT * FROM securitytokens WHERE identifier = ?");
        $result = $statement->execute(array($identifier));
        $securitytoken_row = $statement->fetch();

        if (sha1($securitytoken) !== $securitytoken_row['securitytoken']) {
            //Vermutlich wurde der Security Token gestohlen
            //Hier ggf. eine Warnung o.ä. anzeigen
        } else { //Token war korrekt
            //Setze neuen Token
            $neuer_securitytoken = random_string();
            $insert = $pdo->prepare("UPDATE securitytokens SET securitytoken = :securitytoken WHERE identifier = :identifier");
            $insert->execute(array('securitytoken' => sha1($neuer_securitytoken), 'identifier' => $identifier));
            setcookie("identifier", $identifier, time() + (3600 * 24 * 365)); //1 Jahr Gültigkeit
            setcookie("securitytoken", $neuer_securitytoken, time() + (3600 * 24 * 365)); //1 Jahr Gültigkeit
            //Logge den Benutzer ein
            $_SESSION['userid'] = $securitytoken_row['user_id'];
        }
    }


    if (!isset($_SESSION['userid'])) {
        die('Bitte zuerst <a href="login.php">einloggen</a>');
    }


    $statement = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $result = $statement->execute(array('id' => $_SESSION['userid']));
    $user = $statement->fetch();
    return $user;
}

/**
 * Returns true when the user is checked in, else false
 */
function is_checked_in() {
    return isset($_SESSION['userid']);
}

/**
 * Returns a random string
 */
function random_string() {
    if (function_exists('openssl_random_pseudo_bytes')) {
        $bytes = openssl_random_pseudo_bytes(16);
        $str = bin2hex($bytes);
    } else if (function_exists('mcrypt_create_iv')) {
        $bytes = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
        $str = bin2hex($bytes);
    } else {
        //Replace your_secret_string with a string of your choice (>12 characters)
        $str = md5(uniqid('your_secret_string', true));
    }
    return $str;
}

/**
 * Returns the URL to the site without the script name
 */
function getSiteURL() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    return $protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
}

/**
 * Outputs an error message and stops the further exectution of the script.
 */
function error($error_msg) {
    include("templates/header.inc.php");
    include("templates/error.inc.php");
    include("templates/footer.inc.php");
    exit();
}

/**
 * Displays a card
 */
function displayCard($shortname, $masterid, $id) {
    $cardname = $shortname . $masterid;
    ?>
    <div class="col-xs-3 col-md-2 col-xl-1" id="<?php echo $cardname; ?>">
        <a <?php
        if (isset($_GET["id"])) {
            if ($GLOBALS["user"]["id"] !== $_GET["id"]) {
                echo 'href="#myModal" data-toggle="modal"';
            }
        }
        ?>  data-target="#tradeModal" 
            data-card-id="<?php echo $id; ?>"
            data-card-name="<?php echo $cardname; ?>">
            <figure class="figure">
                <img width="100%" draggable="true" 
                     ondragstart="drag(event, 
                     '<?php echo $id; ?>',
                     '<?php echo $cardname; ?>')" 
                     src="<?php echo $GLOBALS['basepath']; ?>img/cards/<?php echo $cardname; ?>.jpg" 
                     class="figure-img img-fluid rounded" 
                     alt="<?php echo $cardname; ?>">

                <figcaption class="figure-caption"><?php echo $cardname; ?></figcaption>

            </figure>
        </a>
    </div> 
    <?php
}

/**
 * Displays a cardset
 */
function displayCardSet($shortname) {
    ?>
    <div class="col-xs-3 col-md-2 col-xl-1" id="<?php echo $shortname; ?>">
        <figure class="figure">
            <img width="100%"  
                 src="img/masters/<?php echo $shortname; ?>.jpg" 
                 class="figure-img img-fluid rounded" 
                 alt="<?php echo $cardname; ?>">
            <a href="#">
                <figcaption class="figure-caption"><?php echo $shortname; ?></figcaption>
            </a>
        </figure>
    </div> 
    <?php
}

/**
 * Gives the user random cards
 */
function giveRandomCards($quantity) {
    $sql = "SELECT cards.CardID, cards.MasterID, cards.CardMasterSubID, cards.RarityID, masters.MasterShortName "
            . "FROM cards "
            . "INNER JOIN masters ON cards.MasterID = masters.MasterID "
            . "INNER JOIN rarities ON rarities.RarityID = cards.RarityID "
            . "ORDER BY RAND() LIMIT " . $quantity;
    $statement = $GLOBALS['pdo']->prepare($sql);
    $result = $statement->execute();
    ?>

    <div class="alert alert-success" role="alert">
        <?php echo _("You got ").$quantity.(" new random cards!"); ?>
    </div>

    <div class="row">
        <?php
        while ($row = $statement->fetch()) {
            displayCard($row['MasterShortName'], $row['CardMasterSubID'], $row['CardID']);

            $sql = "INSERT INTO usersxcards (UserID, CardID, StorageID) "
                    . "VALUES (" . $GLOBALS['user']['id'] . ", " . $row['CardID'] . ", " . $GLOBALS['basestorage'] . ")";
            $state = $GLOBALS['pdo']->prepare($sql);
            $result = $state->execute();
        }
        ?>
    </div>    
    <?php
}

/**
 * Gives the user random cards
 */
function generateStorages($uid) {
    $storageID = getStorageID();

    $sql = "SELECT * FROM storages";
    $statement = $GLOBALS['pdo']->prepare($sql);
    $result = $statement->execute();
    ?>
    <div class="nav flex-column nav-pills fixed-left gray-pill" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <?php
        while ($row = $statement->fetch()) {
            ?>
            <a class="nav-link 
            <?php
            if ($storageID == $row["StorageID"]) {
                echo "active";
            }
            ?>
               "  role="tab" aria-controls="v-pills-home" aria-selected="true"
               <?php
               //Only show drag/drop stuff, if you are on your own profile
               if ($uid === $GLOBALS["user"]["id"]) {
                   echo 'ondrop="drop(event, ';
                   echo "'" . $row["StorageID"] . "', ";
                   echo "'" . $storageID . "', ";
                   echo "'" . $GLOBALS['user']['id'] . "')\"";
                   echo ' ondragover="allowDrop(event)"';
               }
               ?>        
               href="user.php?storage=<?php echo $row["StorageID"]; ?>&id=<?php echo $uid; ?>">
                   <?php echo $row["StorageName"]; ?>
            </a>
            <?php
        }
        ?>
    </div>
    <?php
}

/**
 * Displays all card from a user, that are in a specific storage
 */
function displayStorageCards($userNumber, $storageID) {
    $sql = "SELECT cards.CardID, MasterShortName, CardMasterSubID "
            . "FROM usersxcards "
            . "INNER JOIN storages ON usersxcards.StorageID = storages.StorageID "
            . "INNER JOIN cards ON usersxcards.CardID = cards.CardID "
            . "INNER JOIN masters ON cards.MasterID = masters.MasterID "
            . "WHERE UserID = " . $userNumber . " "
            . "AND usersxcards.StorageID = " . $storageID . " "
            . "ORDER BY MasterShortName, cards.CardID";
    $statement = $GLOBALS['pdo']->prepare($sql);
    $result = $statement->execute();
    while ($row = $statement->fetch()) {
        displayCard($row['MasterShortName'], $row['CardMasterSubID'], $row['CardID']);
    }
}

function listAllCards() {
    ?>
    <form id="cardList">
        <div class="form-group">
            <select class="form-control" id="exampleFormControlSelect1">
                <?php
                $sql = "SELECT cards.CardID, MasterShortName, CardMasterSubID "
                        . "FROM usersxcards "
                        . "INNER JOIN storages ON usersxcards.StorageID = storages.StorageID "
                        . "INNER JOIN cards ON usersxcards.CardID = cards.CardID "
                        . "INNER JOIN masters ON cards.MasterID = masters.MasterID "
                        . "WHERE UserID = " . $GLOBALS["user"]["id"] . " "
                        . "ORDER BY MasterShortName, cards.CardID";
                $statement = $GLOBALS['pdo']->prepare($sql);
                $result = $statement->execute();
                while ($row = $statement->fetch()) {
                    echo '<option value="' . $row['CardID'] . '">' . $row['MasterShortName'] . $row['CardMasterSubID'] . "</option>";
                }
                ?>
            </select>
        </div>
    </form>
    <?php
}

/**
 * Displays all your trades
 * @param boolean $who true: Trade sent | false: Trade received
 */
function listYourTrades($who) {
    $uid = $GLOBALS["user"]["id"];

    $sr = "TradeUserSelf"; //You
    $srrev = "TradeUserOther"; //Them
    if (!$who) {
        $sr = "TradeUserOther";
        $srrev = "TradeUserSelf";
    }

    //Generate Headline
    ?>
    <div class="row">
        <div class="col"><h5>Trade with</h5></div>
        <div class="col"><h5>Your card</h5></div>
        <div class="col"><h5>Their card</h5></div>    
        <?php
        if ($who) {
            echo '<div class="col"><h5>' . _("Cancel Trade") . "</h5></div>";
        } else {
            echo '<div class="col"><h5>' . _("Accept Trade") . "</h5></div>";
        }
        echo "</div>";

        //This SQL query is pure hell.
        $sql = "SELECT a.TradeID, a.username, a.MasterShortName AS MyMaster, a.CardMasterSubID AS MyCard, "
                . "masters.MasterShortName AS YourMaster, cards.CardMasterSubID AS YourCard "
                . "FROM trades "
                . "INNER JOIN cards ON trades.TradeCardOther = cards.CardID "
                . "INNER JOIN masters ON masters.MasterID = cards.MasterID "
                . "INNER JOIN("
                . "SELECT TradeID, users.username, masters.MasterShortName, cards.CardMasterSubID "
                . "FROM trades "
                . "INNER JOIN users ON trades." . $srrev . " = users.id "
                . "INNER JOIN cards ON trades.TradeCardSelf = cards.CardID "
                . "INNER JOIN masters ON masters.MasterID = cards.MasterID "
                . "WHERE " . $sr . " = " . $uid . " AND TradeOpen = 1) a "
                . "ON a.TradeID = trades.TradeID "
                . "WHERE trades." . $sr . " = " . $uid . " AND trades.TradeOpen = 1";

        $statement = $GLOBALS['pdo']->prepare($sql);
        $result = $statement->execute();
        while ($row = $statement->fetch()) {
            echo '<div class="row">';
            //The fields are ordered differently, if you sent/received a trade
            if ($who) {
                echo '<div class="col">' . $row["username"] . "</div>";
                echo '<div class="col">' . $row["MyMaster"] . $row["MyCard"] . "</div>";
                echo '<div class="col">' . $row["YourMaster"] . $row["YourCard"] . "</div>";
                echo '<div class="col"><a href="trades.php?trade=' . $row["TradeID"] . '&msg=cancel">' . _("Cancel Trade") . "</a></div>";
            } else {
                echo '<div class="col">' . $row["username"] . "</div>";
                echo '<div class="col">' . $row["YourMaster"] . $row["YourCard"] . "</div>";
                echo '<div class="col">' . $row["MyMaster"] . $row["MyCard"] . "</div>";  
                echo '<div class="col"><a href="trades.php?trade=' . $row["TradeID"] . '&msg=accept">' . _("Accept Trade") . "</a></div>";
            }
            echo "</div>";
        }
    }
    
    /**
     * Make a trade
     * STEPS:
     * * Read trade information from database
     * * Move the card from the trade-requester to the trade-receivers card pool
     * * Move the card from the trade-receiver to the trade-requesters card pool
     * * Close the trade
     */
    function makeTrade($tradeID){
        $sql = "SELECT * FROM trades WHERE TradeID = ".$tradeID." LIMIT 1";
        $statement = $GLOBALS['pdo']->prepare($sql);
        $result = $statement->execute();
        while ($row = $statement->fetch()) {
            $sql = "UPDATE usersxcards "
                    . "SET UserID = ".$row["TradeUserOther"].", "
                    . "StorageID = ".$GLOBALS['basestorage']." "
                    . "WHERE CardID = ".$row["TradeCardSelf"]." "
                    . "AND UserID = ".$row["TradeUserSelf"]." "
                    . "LIMIT 1";
            $sta = $GLOBALS['pdo']->prepare($sql);
            $result = $sta->execute();
            
            $sql = "UPDATE usersxcards "
                    . "SET UserID = ".$row["TradeUserSelf"].", "
                    . "StorageID = ".$GLOBALS['basestorage']." "
                    . "WHERE CardID = ".$row["TradeCardOther"]." "
                    . "AND UserID = ".$row["TradeUserOther"]." "
                    . "LIMIT 1";
            $stat = $GLOBALS['pdo']->prepare($sql);
            $result = $stat->execute();
            
            cancelTrade($tradeID);
        }
    }
    
    /**
     * Canceles a trade
     */
    function cancelTrade($tradeID){
        $sql = "UPDATE trades "
                    . "SET TradeOpen = 0 "
                    . "WHERE TradeID = ".$tradeID." "
                    . "LIMIT 1";
            $statment = $GLOBALS['pdo']->prepare($sql);
            $result = $statment->execute();
    }
    
    /**
     * Lists all users
     */
    function listAllUsers() {
        $sql = "SELECT id, username FROM users";
        $statement = $GLOBALS['pdo']->prepare($sql);
        $result = $statement->execute();
        while ($row = $statement->fetch()) {
            echo '<a href="user.php?id=' . $row["id"] . '">' . $row["username"] . '</a>';
            echo "<br>";
        }
    }

    /**
     * Returns the current storage id
     */
    function getStorageID() {
        $storageID = $GLOBALS['basestorage']; //ID of "New"
        if (isset($_GET["storage"])) {
            $storageID = htmlspecialchars($_GET["storage"]);
        }

        return $storageID;
    }

    /**
     * Returns the username from given userid
     */
    function getUsername($id) {
        $sql = "SELECT username FROM users WHERE id = " . $id . " LIMIT 1";
        $statement = $GLOBALS['pdo']->prepare($sql);
        $result = $statement->execute();
        while ($row = $statement->fetch()) {
            return $row["username"];
        }

        return "";
    }

    
    ?>


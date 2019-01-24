<?php
error_reporting(-1);
if (!function_exists("_")) {
    echo "Gettext is not available! Please change that to enable multiple languages.";
}

$language = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

if ($language[0] == "de" || $language[0] == "de_DE") {
    $language = "de_DE";
    $domain = "messages";
} else {
    $language = "en_US";
    $domain = "none";
}

putenv("LANG=" . $language);
setlocale(LC_ALL, $language);
bindtextdomain($domain, "Locale");
textdomain($domain);

$GLOBALS['basepath'] = "/tcg/";
$cscript = basename($_SERVER["SCRIPT_FILENAME"]);
?>

<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>TCG</title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo $GLOBALS['basepath']; ?>css/bootstrap.min.css" rel="stylesheet">    
        <link href="<?php echo $GLOBALS['basepath']; ?>css/style.css" rel="stylesheet">    


    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="index.php">TCG</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <?php if (is_checked_in()) { $user = check_user();?>
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item <?php if($cscript == "user.php"){ echo "active"; } ?>">
                            <a class="nav-link" href="<?php echo $GLOBALS['basepath']; ?>user.php"><?php echo getUsername($user["id"]) . "'s " . _("profile"); ?></a>
                        </li>
                        <li class="nav-item <?php if($cscript == "trades.php"){ echo "active"; } ?>">
                            <a class="nav-link" href="<?php echo $GLOBALS['basepath']; ?>trades.php"><?php echo _("Your trades"); ?></a>
                        </li>
                        <li class="nav-item <?php if($cscript == "games.php"){ echo "active"; } ?>">
                            <a class="nav-link" href="<?php echo $GLOBALS['basepath']; ?>games.php"><?php echo _("Games"); ?></a>
                        </li>
                        <li class="nav-item <?php if($cscript == "members.php"){ echo "active"; } ?>">
                            <a class="nav-link" href="<?php echo $GLOBALS['basepath']; ?>members.php"><?php echo _("Memberlist"); ?></a>
                        </li>
                        <li class="nav-item <?php if($cscript == "sets.php"){ echo "active"; } ?>">
                            <a class="nav-link" href="<?php echo $GLOBALS['basepath']; ?>sets.php"><?php echo _("Cardsets"); ?></a>
                        </li>
                        
                        <li class="nav-item <?php if($cscript == "settings.php"){ echo "active"; } ?>">
                            <a class="nav-link" href="<?php echo $GLOBALS['basepath']; ?>settings.php"><?php echo _("Settings"); ?></a>
                        </li>
                        <li class="nav-item <?php if($cscript == "logout.php"){ echo "active"; } ?>">
                            <a class="nav-link" href="<?php echo $GLOBALS['basepath']; ?>logout.php">Logout</a>
                        </li>
                    </ul>
                <?php } else { ?>
                    <ul class="navbar-nav mr-auto"></ul>
                    <form action="login.php" method="post">
                        <div class="form-row">
                            <div class="col" action="login.php" method="post">
                                <input type="text" class="form-control" id="emailusername" placeholder="E-Mail/Username" name="emailusername">
                                <input type="checkbox" id="angemeldet_bleiben" name="angemeldet_bleiben" value="remember-me" title="Angemeldet bleiben"  checked="checked">
                                <label class="form-check-label text-white" for="angemeldet_bleiben"><?php echo _("Remember me?"); ?></label>
                            </div>
                            <div class="col">
                                <input type="password" class="form-control" placeholder="<?php echo _("Password"); ?>" name="passwort">
                                <small><a href="forgotpassword.php"><?php echo _("Forgot password?"); ?></a></small>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </nav>
<?php
    error_reporting(-1);
    if(!function_exists("_")){
        echo "Gettext is not available! Please change that to enable multiple languages.";
    }

    $language = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);    
    
    if($language[0] == "de" || $language[0] == "de_DE"){
        $language = "de_DE";  
        $domain = "messages";
    }
    else{
        $language = "en_US";
        $domain = "none";
    }
    
    putenv("LANG=".$language);
    setlocale(LC_ALL, $language);
    bindtextdomain($domain, "Locale");
    textdomain($domain); 
    
     $GLOBALS['basepath'] = "/tcg/";
?>

<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Loginscript</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo $GLOBALS['basepath']; ?>css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base; ?>css/bootstrap-theme.css"> 
    
    

    <!-- Custom styles for this template -->
    <link href="<?php echo $GLOBALS['basepath']; ?>css/style.css" rel="stylesheet">
  </head>
  <body>
  
  <nav class="navbar navbar-inverse navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Menu</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php"><i class="glyphicon glyphicon-leaf logo"></i> <?php echo _("Company icon"); ?></a>
        </div>
        <?php if(!is_checked_in()): ?>
        <div id="navbar" class="navbar-collapse collapse">
          <form class="navbar-form navbar-right" action="login.php" method="post">
			<table class="login" role="presentation">
				<tbody>
					<tr>
						<td>							
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
								<input class="form-control" placeholder="E-Mail/Username" name="emailusername" type="text" required>								
							</div>
						</td>
						<td>
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
								<input class="form-control" placeholder="<?php echo _("Password"); ?>" name="passwort" type="password" value="" required>							
							</div>
						</td>
						<td><button type="submit" class="btn btn-success">Login</button></td>
					</tr>
					<tr>
						<td><label style="margin-bottom: 0px; font-weight: normal;"><input type="checkbox" name="angemeldet_bleiben" value="remember-me" title="Angemeldet bleiben"  checked="checked" style="margin: 0; vertical-align: middle;" /> <small><?php echo _("Remember me?"); ?></small></label></td>
						<td><small><a href="forgotpassword.php"><?php echo _("Forgot password?"); ?></a></small></td>
						<td></td>
					</tr>					
				</tbody>
			</table>		
          
            
          </form>         
        </div><!--/.navbar-collapse -->
        <?php else: ?>
        <div id="navbar" class="navbar-collapse collapse">
         <ul class="nav navbar-nav navbar-right">    
            <li><a href="<?php echo $GLOBALS['basepath']; ?>cards.php"><?php echo _("Your cards"); ?></a></li> 
            <li><a href="<?php echo $GLOBALS['basepath']; ?>sets.php"><?php echo _("Cardset"); ?></a></li>
            <li><a href="<?php echo $GLOBALS['basepath']; ?>games.php"><?php echo _("Games"); ?></a></li>
            <li><a href="<?php echo $GLOBALS['basepath']; ?>settings.php"><?php echo _("Settings"); ?></a></li>
            <li><a href="<?php echo $GLOBALS['basepath']; ?>logout.php">Logout</a></li>
          </ul>   
        </div><!--/.navbar-collapse -->
        <?php endif; ?>
      </div>
    </nav>
<?php

/*
NAME validity.php is part of the voucher4guests Project
SYNOPSIS creates validity check site 
DESCRIPTION creates validity check site with validity check subroutine
validity_function.php and logout_function.php subroutine
AUTHORS Alexander Mueller, alexander_mueller at eva dot mpt dot de
VERSION 0.4
COPYRIGHT AND LICENSE 

(c) Alexander Mueller Lars Uhlemann

This software is released under GPLv2 license - see 
http://www.gnu.org/licenses/gpl-2.0.html
*/

session_start();
require_once 'language/Language.php';
require_once 'includes/VoucherService.php';

// load config
$config = include($_SERVER['DOCUMENT_ROOT'] . '/../config/config.php');

$voucherService = new \Voucher\UserInterface\VoucherService();

$languages = new Language();
$language = $languages->detectLanguage();

// set language in session
$_SESSION['language'] = $language;
$languageName = $languages->getLanguageName();
require_once('language/'.$language.'.php');

?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Captive Portal System">
	<meta name="author" content="voucher4guests">
	<link rel="icon" type="image/png" href="includes/images/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="includes/images/favicon-16x16.png" sizes="16x16">

	<title><?php echo $lang['PageTitle']; ?></title>

	<!-- Bootstrap CSS -->
	<link href="includes/css/bootstrap.min.css" rel="stylesheet">
	<link href="includes/css/ionicons.min.css" rel="stylesheet" type="text/css" />
	<!-- Custom styles -->
	<link href="includes/css/style.css" rel="stylesheet">
	<link href="includes/css/color.css" rel="stylesheet">
</head>

<body>
<div class="container main">
	<div class="header clearfix">
		<nav>
			<ul class="nav nav-pills pull-right">
				<li role="presentation" class="dropdown navbar-right nav-lang">

					<?php
					// language select menu
					print '<a class="dropdown-toggle" data-toggle="dropdown" href="?lang='.$language.'" role="button" aria-haspopup="true" aria-expanded="false">
                            '.$languageName[$language].' <span class="caret"></span></a>';

					print '<ul class="dropdown-menu">';
					foreach ($languageName as $key => $value){
						if ($key != $language){
							print '<li><a href="?lang='.$key.'">'.$value.'</a></li>';
						}
					}
					print '</ul>';

					?>

				</li>
			</ul>
		</nav>
		<!-- Text Logo -->
		<!--<h3 class="text-muted">LOGO</h3>-->

		<!-- Image Logo -->
		<img class="img-responsive" src="includes/images/Logo.png" alt="Logo" height="80" width="415">
	</div>
	<div class="row">
		<div class="col-sm-12">
			<p>
				<a class="btn btn-default res-btn" role="button" href="index.php"><i class="icon ion-chevron-left"></i>&nbsp; <?php echo $lang['GoToStart']; ?></a>
			</p>

			<div class="panel panel-default">
				<div class="panel-heading"><?php echo $lang['ValidityHeadline']; ?></div>
				<div class="panel-body">
					<p><?php echo $lang['ValidityDesc']; ?></p>

					<?php
					$validity = $voucherService->get_validity();

					if ($validity['activated']):
						?>
						<div class="well">
							<p><?php echo sprintf($lang['activatedTo'], '<strong>'.$validity['expiration_date'].'</strong>'); ?></p>
							<p><?php echo $lang['VoucherCode']; ?> <?php echo $validity['voucher_code']; ?></p>

							<?php  if ($validity['remaining'] == 0): ?>
								<p><?php echo $lang['noRemainingActivations']; ?></p>
							<?php else: ?>
								<p><?php echo sprintf($lang['remainingActivations'], '<strong>'.$validity['remaining'].'</strong>'); ?></p>
							<?php endif; ?>

						</div>

					<?php else: ?>

						<div class="alert alert-info" role="alert">
							<i class="icon ion-information-circled"></i>&nbsp;<?php echo $lang['NoValidity']; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading"><?php echo $lang['LogoutHeadline']; ?></div>
				<div class="panel-body">
					<p><?php echo $lang['LogoutDesc']; ?></p>


					<?php
					if ( isset($_POST["logout"])):
						$logout = $voucherService->logout();
						if($logout){
							?>
							<div class="alert alert-success" role="alert">
								<i class="icon ion-checkmark"></i>&nbsp;<?php echo $lang['LogoutWorks']; ?>
							</div>
						<?php } else { ?>
							<div class="alert alert-info" role="alert">
								<i class="icon ion-information-circled"></i>&nbsp;<?php echo $lang['NotActivated']; ?>
							</div>
						<?php } ?>
					<?php else: ?>
						<?php  if ($validity['activated'] ): ?>
							<form name="logout" id="form_logout" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
								<button class="btn btn-primary" type="submit" name="logout"><i class="icon ion-log-out"></i>&nbsp; <?php echo $lang['Logout']; ?></button>
							</form>
						<?php else: ?>
							<div class="alert alert-info" role="alert">
								<i class="icon ion-information-circled"></i>&nbsp;<?php echo $lang['NotActivated']; ?>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<footer class="footer text-center">
		<p><?php echo $lang['Footer']; ?></p>
	</footer>
</div>
<!-- /container -->

<script src="includes/js/jquery-1.11.3.min.js"></script>
<script src="includes/js/bootstrap.min.js"></script>

<!-- Custom script -->
<script src="includes/js/app.js"></script>
</body>
</html>

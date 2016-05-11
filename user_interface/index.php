<?php 

session_start();
require_once 'includes/redirect.php';
require_once 'includes/VoucherService.php';
require_once 'language/Language.php';

// load config
$config = include($_SERVER['DOCUMENT_ROOT'] . '/../config/config.php');


if(isset($_GET['origin_url'])) {
    $_SESSION['origin_url'] = $_GET['origin_url'];
}

if (isset($_POST['voucher_code']) AND !empty($_POST['voucher_code'])) {
    $voucherService = new \Voucher\UserInterface\VoucherService();
    $voucher_code = $voucherService->format_voucher_code($_POST['voucher_code']);
    $message = $voucherService->login($voucher_code);

    // redirect, if origin url is given
    if ($message == \Voucher\UserInterface\VoucherService::MESSAGE_ACTIVATION_SUCCESSFUL
        AND !empty($_SESSION['origin_url'])
        AND $config['redirect_user']){
        header("location:http://".$_SESSION['origin_url']);
        exit;
    }
}

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
        <div class="col-sm-5">

            <div class="box">
                <form name="voucher" id="form_voucher" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">

                    <div class="box-header text-center">
                        <h4 class="text-primary"><?php echo $lang['HeaderTitle']; ?></h4>
                        <hr>
                    </div>
                    <?php
                    // Error Messages
                    if (!empty($message)){
                        switch($message){
                            case(\Voucher\UserInterface\VoucherService::MESSAGE_ACTIVATION_SUCCESSFUL):
                                $alert_class = "alert-success";
                                $alert_icon = "ion-checkmark";
                                $message_text = $lang['ActivationCompleted'];
                                break;
                            case(\Voucher\UserInterface\VoucherService::MESSAGE_INVALID_VOUCHER):
                                $alert_class = "alert-warning";
                                $alert_icon = "ion-alert";
                                $message_text = $lang['InvalidVoucher'];
                                break;
                            case(\Voucher\UserInterface\VoucherService::MESSAGE_DEVICE_ALREADY_ACTIVATED):
                                $alert_class = "alert-success";
                                $alert_icon = "ion-checkmark";
                                $message_text = $lang['ActivatedDevice'];
                                break;
                            case(\Voucher\UserInterface\VoucherService::MESSAGE_EXPIRED_VOUCHER):
                                $alert_class = "alert-warning";
                                $alert_icon = "ion-alert";
                                $message_text = $lang['ExpiredVoucher'];
                                break;
                            case(\Voucher\UserInterface\VoucherService::MESSAGE_NOT_ACTIVATED_VOUCHER):
                                $alert_class = "alert-warning";
                                $alert_icon = "ion-alert";
                                $message_text = $lang['NotActivatedVoucher'];
                                break;
                            case(\Voucher\UserInterface\VoucherService::MESSAGE_MAX_DEVICES_ACTIVATED):
                                $alert_class = "alert-warning";
                                $alert_icon = "ion-alert";
                                $message_text = $lang['MaxDevicesActivated'];
                                break;
                        }
                        if (isset($alert_class) AND isset($alert_icon) AND isset($message_text)){
                            print '<div class="alert '.$alert_class.'">';
                            print '<i class="icon '.$alert_icon.'"></i>&nbsp;&nbsp;'.$message_text;
                            print '</div>';
                        }
                    }
                    ?>

                    <label><?php echo $lang['LableVoucherCode']; ?></label>&nbsp;&nbsp;<a class="pull-right pointer-link" data-toggle="modal" data-target="#myModal"><i class="icon ion-help-circled"></i>&nbsp;<?php echo $lang['Help']; ?></a>
                    <input type="text" class="form-control input_txt" name="voucher_code" id="voucher_code" />

                    <br/>
                    <p><?php echo $lang['Accept']; ?> <a href="policy.php" target="_blank"><?php echo $lang['Policy']; ?></a>.</p>
                    <div class="box-footer text-center">
                        <input class="btn btn-primary" type="submit" name="submit" value='<?php echo $lang['Submit']; ?>' />
                    </div>
                </form>
            </div>
        </div>


        <div class="col-sm-7">
            <?php echo $lang["IndexContent"]; ?>
            <p>
                <a class="btn btn-default res-btn" role="button" href="validity.php"><?php echo $lang['LinkValidity']; ?> &nbsp;<i class="icon ion-chevron-right"></i></a>
            </p>
            <?php echo $lang["IndexAddContent"]; ?>
        </div>
    </div>
    <footer class="footer text-center">
        <p><?php echo $lang['Footer']; ?></p>
    </footer>

</div>
<!-- /container -->

<!-- Help Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $lang['HelpTitle']; ?></h4>
            </div>
            <div class="modal-body">
                <?php echo $lang['HelpText']; ?>
                <img src="includes/images/sample.png" alt="sample voucher">
                <?php echo $lang['HelpText1']; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Close']; ?></button>
            </div>
        </div>
    </div>
</div>

<script src="includes/js/jquery-1.11.3.min.js"></script>
<script src="includes/js/bootstrap.min.js"></script>

<!-- Input mask with placeholder -->
<!--<script src="includes/js/jquery.maskedinput.min.js"></script>-->

<!-- Input mask without placeholder -->
<script src="includes/js/jquery.mask.min.js"></script>

<!-- Custom script -->
<script src="includes/js/app.js"></script>
</body>
</html>

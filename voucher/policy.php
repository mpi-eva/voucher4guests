<?php
session_start();
require_once 'language/Language.php';

// load config
$config = include($_SERVER['DOCUMENT_ROOT'] . '/../config/config.php');

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
            <h3 class="first-headline"><?php echo $lang["PolicyTitle"]; ?></h3>
            <p><?php echo $lang["Download"]; ?> <a href="pdf/<?php echo $lang['PolicyPDF']; ?>" ><i class="icon ion-document-text"></i>&nbsp;<?php echo $lang["PolicyPDF"]; ?></a></p>
            <?php echo $lang["PolicyContent"]; ?>
            <p><?php echo $lang["Download"]; ?> <a href="pdf/<?php echo $lang['PolicyPDF']; ?>" ><i class="icon ion-document-text"></i>&nbsp;<?php echo $lang["PolicyPDF"]; ?></a></p>

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

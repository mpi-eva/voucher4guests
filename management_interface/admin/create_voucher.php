<?php
/**
 * This file is part of voucher4guests.
 *
 * voucher4guests Project - An open source captive portal system
 * Copyright (C) 2016. Alexander Müller, Lars Uhlemann
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'includes/Db.php';
//load config
$config = include($_SERVER['DOCUMENT_ROOT'] . '/../config/config.php');
$dbConfig = include($_SERVER['DOCUMENT_ROOT'] . '/../config/database.config.php');
$db = new \Voucher\ManagementInterface\Db($dbConfig);
$voucher['data'] = array();
$result = $db->select('SELECT * FROM validities WHERE validity_id != "0"');


?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Captive Portal System">
    <meta name="author" content="voucher4guests">
    <link rel="icon" type="image/png" href="includes/images/favicon-32x32.png?v=1" sizes="32x32">
    <link rel="icon" type="image/png" href="includes/images/favicon-16x16.png?v=1" sizes="16x16">

    <title>Create Voucher</title>

    <!-- Bootstrap CSS -->
    <link href="includes/css/bootstrap.min.css" rel="stylesheet">
    <link href="includes/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <!-- daterange picker -->
    <link rel="stylesheet" href="includes/css/daterangepicker/daterangepicker.css">
    <!-- Custom styles -->
    <link href="includes/css/style.css" rel="stylesheet">
    <!-- <link href="includes/css/color.css" rel="stylesheet">-->

</head>

<body>
<div class="container">

    <!-- Static navbar -->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Voucher4guests - ControlCenter</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="create_voucher.php">Voucher erzeugen</a></li>
                    <li><a href="database.php">Datenbank</a></li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
        <!--/.container-fluid -->
    </nav>

    <!-- Begin page content -->
    <div class="container">
        <div class="page-header">
            <h1>Create Voucher</h1>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="box">
                <form id="create-form" method="post" action="pdf/create_pdf.php" target="_blank">
                    <div class="form-group">
                        <label for="number">Anzahl</label>
                        <input type="number" min="1" step="1" class="form-control" id="number" name="number" placeholder="Anzahl">
                    </div>


                    <div class="btn-group btn-toggle" data-toggle="buttons">
                        <label class="btn btn-primary active">
                            <input type="radio" name="options" id="option1" autocomplete="off" checked>Voucher mit<br> Gültigkeit in Tagen
                        </label>
                        <label class="btn btn-primary">
                            <input type="radio" name="options" id="option2" autocomplete="off">Voucher mit<br> festem Zeitraum
                        </label>
                    </div>



                    <div class="form-group">
                        <label for="validity">Gültigkeit</label>
                        <select id="validity" name="validity" class="form-control">
                            <?php
                            if (!empty($result)) {
                                foreach ($result as $entry) {
                                    print '<option value="'.$entry['validity_id'].'">'.$entry['description'].'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Date range -->
                    <div class="form-group">
                        <label for="daterange">Zeitspanne</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="icon ion-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right" id="daterange" name="daterange">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="create-btn" disabled>Create Voucher</button>
                </form>
                </div>
            </div>

            <div class="col-sm-8">
                <p>Hier können Sie neue Voucher erzeugen und ausdrucken.</p>
                <ol>
                    <li>Anzahl der Voucher angeben</li>
                    <li>Voucherart auswählen</li>
                    <li>Gültigkeit Auswählen oder Zeitspanne angeben</li>
                    <li>Eingabe bestätigen und PDF ausdrucken</li>
                </ol>

                <p>
                    PDF sofort speichern oder ausdrucken. PDF wird nicht auf dem Server gespeichert!
                </p>
                <p>
                    Für optimale Ergebnisse:
                <ul>
                    <li>PDF randlos drucken</li>
                    <li>min. 160g Papier (weiß)</li>
                </ul>
                </p>
            </div>
        </div>
    </div>


    <footer class="footer text-center">
        <p>voucher4guests</p>
    </footer>

</div>
<!-- /container -->
<script src="includes/js/jquery-1.12.2.min.js"></script>
<script src="includes/js/bootstrap.min.js"></script>
<script src="includes/js/daterangepicker/moment.min.js"></script>
<script src="includes/js/daterangepicker/daterangepicker.js"></script>
<script>

</script>
<!-- Custom script -->
<script src="includes/js/create_voucher.js"></script>
</body>
</html>

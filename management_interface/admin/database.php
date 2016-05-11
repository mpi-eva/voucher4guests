<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Captive Portal System">
    <meta name="author" content="voucher4guests">
    <link rel="icon" type="image/png" href="includes/images/favicon-32x32.png?v=1" sizes="32x32">
    <link rel="icon" type="image/png" href="includes/images/favicon-16x16.png?v=1" sizes="16x16">

    <title>Datenbank</title>

    <!-- Bootstrap CSS -->
    <link href="includes/css/bootstrap.min.css" rel="stylesheet">
    <link href="includes/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="includes/css/datatables.min.css"/>
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
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Voucher4guests - ControlCenter</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="create_voucher.php">Voucher erzeugen</a></li>
                    <li class="active"><a href="database.php">Datenbank</a></li>
                    <li><a href="../log/index.php">Logging</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
    </nav>

    <!-- Begin page content -->
    <div class="container">
        <!--<div class="page-header">
            <h1>Datenbank</h1>
        </div>-->

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#functionModal" data-option="vid" data-label="VID:">Voucher deaktivieren</button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#functionModal" data-option="mac" data-label="MAC-Adresse:">MAC-Adresse deaktivieren</button>

        <hr>

        <table id="database_table" class="display" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th></th>
                <th>VID</th>
                <th>Voucher-Code</th>
                <th>Gültigkeit</th>
                <th>Status</th>
                <th>Aktivierungsdatum</th>
                <th>Ablaufdatum</th>
                <th>Verfallsdatum</th>
                <th>#MACs</th>
                <th></th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th></th>
                <th>VID</th>
                <th>Voucher-Code</th>
                <th>Gültigkeit</th>
                <th>Status</th>
                <th>Aktivierungsdatum</th>
                <th>Ablaufdatum</th>
                <th>Verfallsdatum</th>
                <th>#MACs</th>
                <th></th>
            </tr>
            </tfoot>
        </table>

    </div>




    <footer class="footer text-center">
        <p>voucher4guests</p>
    </footer>

</div> <!-- /container -->

<div class="modal fade" id="functionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="functionModalLabel">Deaktivieren</h4>
            </div>
            <div class="modal-body" >
                <form class="form-inline">
                    <div class="form-group">
                        <label for="input-data" class="control-label">Eingabe:</label>
                        <input type="text" class="form-control" id="input-data">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                <button type="submit" id="submit-data" class="btn btn-primary">deaktivieren</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="infoModalLabel">Deaktivieren</h4>
            </div>
            <div class="modal-body" >
                <p> </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
            </div>
        </div>
    </div>
</div>


<script src="includes/js/jquery-1.12.2.min.js"></script>
<script type="text/javascript" src="includes/js/datatables.min.js"></script>
<script src="includes/js/bootstrap.min.js"></script>

<!-- Custom script -->
<script src="includes/js/database.js"></script>
</body>
</html>

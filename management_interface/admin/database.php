<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Captive Portal System">
    <meta name="author" content="voucher4guests">
    <link rel="icon" type="image/png" href="includes/images/favicon-32x32.png?v=1" sizes="32x32">
    <link rel="icon" type="image/png" href="includes/images/favicon-16x16.png?v=1" sizes="16x16">

    <title>Database View</title>

    <!-- Bootstrap CSS -->
    <link href="includes/css/bootstrap.min.css" rel="stylesheet">
    <link href="includes/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/dt/jqc-1.11.3,dt-1.10.10,af-2.1.0,b-1.1.0,b-colvis-1.1.0,r-2.0.0/datatables.min.css"/>
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
                    <li><a href="#">Logging</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
    </nav>

    <!-- Begin page content -->
    <div class="container">
        <div class="page-header">
            <h1>Database View</h1>
        </div>
        <table id="example" class="display" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th></th>
                <th>VID</th>
                <th>Voucher-Code</th>
                <th>Gültigkeit</th>
                <th>active</th>
                <th>Aktivierung</th>
                <th>Ablaufzeit</th>
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
                <th>active</th>
                <th>Aktivierung</th>
                <th>Ablaufzeit</th>
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
<!--<script src="includes/js/jquery-1.11.3.min.js"></script>-->
<script type="text/javascript" src="https://cdn.datatables.net/s/dt/jqc-1.11.3,dt-1.10.10,af-2.1.0,b-1.1.0,b-colvis-1.1.0,r-2.0.0/datatables.min.js"></script>
<script src="includes/js/bootstrap.min.js"></script>



<!-- Custom script -->
<script src="includes/js/database.js"></script>
</body>
</html>

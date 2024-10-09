<?php 
    ini_set("error_reporting", 1);
    session_start();
    require_once "koneksi.php";
?>
<?php
// Mulai session
session_start();

// Set nilai-nilai $_POST ke dalam session saat formulir disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['tgl'] = $_POST['tgl'];
    $_SESSION['time'] = $_POST['time'];
    $_SESSION['tgl2'] = $_POST['tgl2'];
    $_SESSION['time2'] = $_POST['time2'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>ORGATEX - Batch Report</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="#">
    <meta name="keywords"
        content="Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="#">
    <link rel="icon" href="files\assets\images\favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="files\bower_components\bootstrap\css\bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\icon\themify-icons\themify-icons.css">
    <link rel="stylesheet" type="text/css" href="files\assets\icon\icofont\css\icofont.css">
    <link rel="stylesheet" type="text/css" href="files\assets\icon\feather\css\feather.css">
    <link rel="stylesheet" type="text/css" href="files\assets\pages\prism\prism.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\style.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\jquery.mCustomScrollbar.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\pcoded-horizontal.min.css">
    <link rel="stylesheet" type="text/css"
        href="files\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\pages\data-table\css\buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="files\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
        href="files\assets\pages\data-table\extensions\buttons\css\buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\jquery.mCustomScrollbar.css">
</head>
<?php require_once 'header.php'; ?>

<body>
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header table-card-header">
                                                <h5>Batch Report Orgatex</h5>
                                            </div>
                                            <div class="card-block">
                                                <div class="dt-responsive table-responsive">
                                                    <table id="basic-btn"
                                                        class="table compact table-striped table-bordered nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th>Batch No</th>
                                                                <th>Komposisi Kain</th>
                                                                <th>No Item</th>
                                                                <th>LxG</th>
                                                                <th>Colour Name</th>

                                                                <th>Machine No</th>
                                                                <th>Jumlah Roll</th>
                                                                <th>Load</th>
                                                                <th>% Load</th>
                                                                <th>Set Time</th>

                                                                <th>Waktu Start</th>
                                                                <th>Waktu End</th>
                                                                <th>Run Time / Process Time</th>
                                                                <th>L:R</th>
                                                                <th>Water Con</th>

                                                                <th>RPM</th>
                                                                <th>Cycle Time</th>
                                                                <th>Nozzle</th>
                                                                <th>Pump</th>
                                                                <th>Blower</th>
                                                                
                                                                <th>Plaiter</th>
                                                                <th>Defect</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $sql_query = $pdo_orgatex_main->prepare("
                                                                SELECT [batch_ref_no], [batch_text_01],[machine_no], 
                                                                [batch_parameter_01], [started], [terminated], [times_02], 
                                                                [times_01], [consumption_01], [batch_parameter_03], 
                                                                [batch_parameter_09], [batch_parameter_07], [batch_parameter_08] 
                                                                FROM BatchDetail WHERE (([machine_no] IN (N'1401', N'1402', N'1406', 
                                                                N'1409', N'1410', N'1411', N'1412', N'1413', N'1419', N'1420', N'1421', 
                                                                N'1444', N'1445', N'1449', N'1450', N'1451', N'1452', N'1453', N'1454', 
                                                                N'1455', N'1456', N'1457', N'1458', N'1459', N'1465', N'1466', N'1467', 
                                                                N'1468', N'1469', N'1470', N'1471', N'1472', N'1473', N'1474', N'1475', 
                                                                N'1476', N'1477', N'1478', N'1479', N'1480', N'1481', N'1482', N'1483', 
                                                                N'1484', N'1505', N'2228', N'2229', N'2230', N'2231', N'2246', N'2247', 
                                                                N'2348', N'2622', N'2623', N'2624', N'2625', N'2626', N'2627', N'2632', 
                                                                N'2633', N'2634', N'2635', N'2636', N'2638', N'2639', N'2640', N'2641'))) 
                                                                AND (([batch_text_01] LIKE N'% %'))
                                                                ");

                                                                $sql_query->execute();
                                                                $db_orgatex = $sql_query->fetchAll(PDO::FETCH_ASSOC);

                                                                $no = 1;
                                                            ?>
                                                            <?php foreach ($db_orgatex as $row_data_orgatex): ?>
                                                            <tr>
                                                                <td><?php echo $row_data_orgatex['batch_ref_no'] ?></td>
                                                                <td>no data</td>
                                                                <td>no data</td>
                                                                <td>no data</td>
                                                                <td><?php echo $row_data_orgatex['batch_text_01'] ?></td>

                                                                <td><?php echo $row_data_orgatex['machine_no'] ?></td>
                                                                <td>no data</td>
                                                                <td>no data</td>
                                                                <td>no data</td>
                                                                <td>no data</td>

                                                                <td>no data</td>
                                                                <td>no data</td>
                                                                <td>no data</td>
                                                                <td>no data</td>
                                                                <td>no data</td>

                                                                <td>no data</td>
                                                                <td>no data</td>
                                                                <td>no data</td>
                                                                <td>no data</td>
                                                                <td>no data</td>

                                                                <td>no data</td>
                                                                <td>no data</td>

                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="files\bower_components\jquery\js\jquery.min.js"></script>
    <script type="text/javascript" src="files\bower_components\jquery-ui\js\jquery-ui.min.js"></script>
    <script type="text/javascript" src="files\bower_components\popper.js\js\popper.min.js"></script>
    <script type="text/javascript" src="files\bower_components\bootstrap\js\bootstrap.min.js"></script>
    <script type="text/javascript" src="files\bower_components\jquery-slimscroll\js\jquery.slimscroll.js"></script>
    <script type="text/javascript" src="files\bower_components\modernizr\js\modernizr.js"></script>
    <script type="text/javascript" src="files\bower_components\modernizr\js\css-scrollbars.js"></script>
    <script src="files\bower_components\datatables.net\js\jquery.dataTables.min.js"></script>
    <script src="files\bower_components\datatables.net-buttons\js\dataTables.buttons.min.js"></script>
    <script src="files\assets\pages\data-table\js\jszip.min.js"></script>
    <script src="files\assets\pages\data-table\js\pdfmake.min.js"></script>
    <script src="files\assets\pages\data-table\js\vfs_fonts.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\dataTables.buttons.min.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\buttons.flash.min.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\jszip.min.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\vfs_fonts.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\buttons.colVis.min.js"></script>
    <script src="files\bower_components\datatables.net-buttons\js\buttons.print.min.js"></script>
    <script src="files\bower_components\datatables.net-buttons\js\buttons.html5.min.js"></script>
    <script src="files\bower_components\datatables.net-bs4\js\dataTables.bootstrap4.min.js"></script>
    <script src="files\bower_components\datatables.net-responsive\js\dataTables.responsive.min.js"></script>
    <script src="files\bower_components\datatables.net-responsive-bs4\js\responsive.bootstrap4.min.js"></script>
    <script type="text/javascript" src="files\bower_components\i18next\js\i18next.min.js"></script>
    <script type="text/javascript" src="files\bower_components\i18next-xhr-backend\js\i18nextXHRBackend.min.js">
    </script>
    <script type="text/javascript"
        src="files\bower_components\i18next-browser-languagedetector\js\i18nextBrowserLanguageDetector.min.js"></script>
    <script type="text/javascript" src="files\bower_components\jquery-i18next\js\jquery-i18next.min.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\extension-btns-custom.js"></script>
    <script src="files\assets\js\pcoded.min.js"></script>
    <script src="files\assets\js\menu\menu-hori-fixed.js"></script>
    <script src="files\assets\js\jquery.mCustomScrollbar.concat.min.js"></script>
    <script type="text/javascript" src="files\assets\js\script.js"></script>
</body>

</html>
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
                                                            $sql_query = $pdo_orgatex_main->prepare("SELECT [batch_ref_no], 
                                                                [batch_text_01],
                                                                [batch_text_06],
                                                                [machine_no], 
                                                                [batch_parameter_01], 
                                                                [started], 
                                                                [terminated], 
                                                                [times_02], 
                                                                [times_01], 
                                                                [consumption_01], 
                                                                [batch_parameter_03], 
                                                                [batch_parameter_09], 
                                                                [batch_parameter_07], 
                                                                [batch_parameter_08] 
                                                                FROM BatchDetail WHERE [batch_text_01] not like '%[a-zA-Z]%' 
                                                                and [batch_text_01] <> '' ");

                                                            $sql_query->execute();
                                                            $db_orgatex = $sql_query->fetchAll(PDO::FETCH_ASSOC);
                                                            $no = 1;
                                                            ?>
                                                            <!-- Query untuk dborgatex integ1-->
                                                            <?php foreach ($db_orgatex as $row_data_orgatex): ?>
                                                                <?php $sql_query2 = $pdo_orgatex->prepare("SELECT 
                                                                                                                    *
                                                                                                                    FROM 
                                                                                                                        Dyelots 
                                                                                                                    WHERE 
                                                                                                                        DyelotRefNo ='$row_data_orgatex[batch_ref_no]' 
                                                                                                            ");

                                                                $sql_query2->execute();
                                                                $db_orgatex_integ = $sql_query2->fetchAll(PDO::FETCH_ASSOC);
                                                                foreach ($db_orgatex_integ as $row_integ):
                                                                    ?>
                                                                    <!-- End Query integ1-->

                                                                    <!-- Querry DB2 Untuk Row 1 -->
                                                                    <?php $query_db2 = "SELECT 
                                                                                            i.DEAMAND AS DEMAND,
                                                                                            TRIM(i.SUBCODE02)||'-'||TRIM(i.SUBCODE03) AS NO_HANGER,
                                                                                            i.SUBCODE01,
                                                                                            i.SUBCODE02,
                                                                                            i.SUBCODE03,
                                                                                            i.SUBCODE04,
                                                                                            i.SUBCODE05,
                                                                                            i.SUBCODE06,
                                                                                            i.SUBCODE07,
                                                                                            i.SUBCODE08,
                                                                                            i.PRODUCTIONORDERCODE,
                                                                                            DECIMAL(a.VALUEDECIMAL, 10,0)||' x '||DECIMAL(a2.VALUEDECIMAL, 10,0) AS LxG,
                                                                                            p2.LONGDESCRIPTION
                                                                                            FROM ITXVIEWKK i 
                                                                                            LEFT JOIN PRODUCT p ON p.ITEMTYPECODE  = i.ITEMTYPEAFICODE 
                                                                                            AND p.SUBCODE01 = i.SUBCODE01 
                                                                                            AND p.SUBCODE02 = i.SUBCODE02 
                                                                                            AND p.SUBCODE03 = i.SUBCODE03 
                                                                                            AND p.SUBCODE04 = i.SUBCODE04 
                                                                                            AND p.SUBCODE05 = i.SUBCODE05 
                                                                                            AND p.SUBCODE06 = i.SUBCODE06 
                                                                                            AND p.SUBCODE07 = i.SUBCODE07
                                                                                            LEFT JOIN PRODUCT p2 ON p2.ITEMTYPECODE  = 'KGF' 
                                                                                            AND p2.SUBCODE01 = i.SUBCODE01 
                                                                                            AND p2.SUBCODE02 = i.SUBCODE02 
                                                                                            AND p2.SUBCODE03 = i.SUBCODE03 
                                                                                            AND p2.SUBCODE04 = i.SUBCODE04
                                                                                            LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID 
                                                                                            AND a.FIELDNAME = 'Width'
                                                                                            LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID 
                                                                                            AND a2.FIELDNAME = 'GSM'
                                                                                            WHERE i.PRODUCTIONORDERCODE ='$row_integ[Dyelot]'";
                                                                            $db2exec1 = db2_exec($conn1, $query_db2);
                                                                            $db2_data = db2_fetch_assoc($db2exec1); ?>
                                                                    <!-- End Query Db2 -->

                                                                    <tr>
                                                                        <td><?php echo $row_data_orgatex['batch_ref_no'] ?></td>
                                                                        <td><?php echo $db2_data['LONGDESCRIPTION'] ?></td>
                                                                        <td><?php echo $db2_data['NO_HANGER'] ?></td>
                                                                        <td><?php echo $db2_data['LXG'] ?></td>
                                                                        <td><?php echo $row_data_orgatex['batch_text_06'] ?>
                                                                        </td>

                                                                        <td><?php echo $row_data_orgatex['machine_no'] ?></td>
                                                                        <td>ORGATEX</td>
                                                                        <td>ORGATEX</td>
                                                                        <td>ORGATEX</td>
                                                                        <td>ORGATEX</td>

                                                                        <td><?php echo $row_data_orgatex['started'] ?></td>
                                                                        <td><?php echo $row_data_orgatex['terminated'] ?></td>
                                                                        <td>
                                                                            <?php
                                                                            if ($row_data_orgatex['started'] != null && $row_data_orgatex['terminated'] != null) {
                                                                                $start_date = new DateTime($row_data_orgatex['started']);
                                                                                $end_date = new DateTime($row_data_orgatex['terminated']);

                                                                                $interval = $start_date->diff($end_date);

                                                                                echo $interval->format('%h hour %i minute %s second');
                                                                            } else {
                                                                                echo '';
                                                                            }

                                                                            ?>
                                                                        </td>
                                                                        <td><?php echo $row_integ['LiquorRatio']?></td>
                                                                        <td>ORGATEX</td>

                                                                        <td>ORGATEX</td>
                                                                        <td>ONLINE</td>
                                                                        <td>ONLINE</td>
                                                                        <td><?php echo $row_integ['Parameter8']?></td>
                                                                        <td><?php echo $row_integ['Parameter9']?></td>

                                                                        <td>ONLINE</td>
                                                                        <td>NOW</td>

                                                                    </tr>
                                                                    <?php endforeach; ?>
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
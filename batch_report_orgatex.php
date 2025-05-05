<?php
ini_set("error_reporting", 1);
session_start();
require_once "koneksi.php";
?>
<?php
// Mulai session
session_start();

// Set nilai-nilai $_POST ke dalam session saat formulir disubmit
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $_SESSION['tgl'] = $_POST['tgl'];
//     $_SESSION['time'] = $_POST['time'];
//     $_SESSION['tgl2'] = $_POST['tgl2'];
//     $_SESSION['time2'] = $_POST['time2'];
// }
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
<?php 
require_once 'header.php';

?>

<body>
    <?php 
    if (!empty($_POST['tgl1'])) {
        // Mengonversi tanggal dari string ke format yang tepat
        $dateTime = DateTime::createFromFormat('Y-m-d', $_POST['tgl1']);
        if ($dateTime) {
            // Format tanggal ke string dalam format yang sesuai untuk SQL Server
            $tanggal = $dateTime->format('Y-m-d');
            $filter_tanggal_utama = "AND CAST([started] AS DATE) = '$tanggal'";
        } else {
            // Jika tanggal tidak valid, Anda bisa mengatur filter default atau menangani kesalahan
            $filter_tanggal_utama = "AND CAST([started] AS DATE) = CAST(GETDATE() AS DATE)";
        }
    } else {
        $filter_tanggal_utama = "AND CAST([started] AS DATE) = CAST(GETDATE() AS DATE)";
    }

    // Menyiapkan query
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
                                                        FROM BatchDetail 
                                                        WHERE [batch_text_01] NOT LIKE '%[a-zA-Z]%' 
                                                        AND [batch_text_01] <> '' 
                                                        $filter_tanggal_utama
                                                    ");
    
    ?>
    <form method="POST" enctype="multipart/form-data"> <!-- Ganti dengan nama file PHP Anda -->
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
                                                    <h3>Batch Report Orgatex</h3>
                                                    <div class="col-sm-12 col-xl-2 m-b-30">
                                                        <h5>Waktu Start</h5>
                                                        <input type="date" name="tgl1" class="form-control" id="tgl1"
                                                        value="<?php if (isset($_POST['submit'])) { 
                                                            echo htmlspecialchars($_POST['tgl1'], ENT_QUOTES); } 
                                                            echo $_POST['tgl1'];?>">
                                                    </div>
                                                    <div class="col-sm-12 col-xl-12 m-b-30">
                                                        <button class="btn btn-success" type="submit"><i class="fa fa-search"></i> Search</button>
                                                    </div>
                                                </div>
                                                <div class="card-block">
                                                    <div class="dt-responsive table-responsive">
                                                        <table id="basic-btn"
                                                        class="table compact table-striped table-bordered nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th>Batch No</th>
                                                                <th>No KK</th>
                                                                <th>No Demand</th>
                                                                <th>Komposisi Kain</th>
                                                                <th>No Item</th>
                                                                <th>LxG</th>
                                                                <th>Colour Name</th>
                                                                
                                                                <th>Machine No</th>
                                                                <th>New-Machine No</th>
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

                                                                    <!-- Query Mysql untuk online dye -->
                                                                    <?php $resep = $row_integ['Dyelot'] . '-' . $row_integ['ReDye'];
                                                                    $query_dye = "SELECT 
                                                                                        m.rol,
                                                                                        m.nozzle,
                                                                                        m.rpm,
                                                                                        m.cycle_time,
                                                                                        m.plaiter,
                                                                                        m.bruto,
                                                                                        m.pakai_air,
                                                                                        s.kapasitas 
                                                                                        from tbl_schedule s 
                                                                                        left join tbl_montemp m on m.id_schedule = s.id 
                                                                                        where no_resep ='$resep'";
                                                                    $sql_exec = mysqli_query($con_db_dyeing, $query_dye);
                                                                    $data_dye = mysqli_fetch_assoc($sql_exec);
                                                                    ?>
                                                                    <?php
                                                                        $query_mesin = "SELECT 
                                                                                       * 
                                                                                        FROM tbl_mesin s
                                                                                        where no_mesin_lama ='$row_data_orgatex[machine_no]'";
                                                                        $sql_exec2 = mysqli_query($con_db_dyeing, $query_mesin);
                                                                        $data_mesin_baru = mysqli_fetch_assoc($sql_exec2);
                                                                        ?>
                                                                    <!-- End query -->

                                                                    <!-- Querry DB2 Untuk Row Last -->
                                                                    <?php
                                                                    $el10 =  TRIM($db2_data['DEMAND']).'0';
                                                                    $query2_db2 = "SELECT DISTINCT
                                                                                            LISTAGG(DISTINCT (TRIM(ELEMENTSINSPECTIONEVENT.ELEMENTSINSPECTIONELEMENTCODE)),',') AS ELEMENTCODE,
                                                                                            ELEMENTSINSPECTION.QUALITYCODE,
                                                                                            LISTAGG(DISTINCT(ELEMENTSINSPECTIONEVENT.CODEEVENTCODE||' - '||INSPECTIONEVENTTEMPLATE.LONGDESCRIPTION),
                                                                                            ', ') AS DEFECT,
                                                                                            LISTAGG(DISTINCT(INSPECTIONEVENTTEMPLATE.LONGDESCRIPTION),
                                                                                            ', ') AS DEFECT_NAME,
                                                                                            SUM(ELEMENTSINSPECTIONEVENT.POINTS) AS TOT_POINT,
                                                                                            SUM(ELEMENTSINSPECTIONEVENT.CREDITS) AS TOT_CREDIT
                                                                                        FROM
                                                                                            ELEMENTSINSPECTIONEVENT
                                                                                        LEFT JOIN ELEMENTSINSPECTION ON
                                                                                            ELEMENTSINSPECTIONEVENT.ELEMENTSINSPECTIONELEMENTCODE = ELEMENTSINSPECTION.ELEMENTCODE
                                                                                        LEFT JOIN ADSTORAGE ADSTORAGE ON
                                                                                            ADSTORAGE.UNIQUEID = ELEMENTSINSPECTION.ABSUNIQUEID
                                                                                            AND ADSTORAGE.FIELDNAME = 'GSM'
                                                                                        LEFT JOIN ELEMENTS ON
                                                                                            ELEMENTSINSPECTION.ELEMENTCODE = ELEMENTS.CODE
                                                                                        LEFT JOIN INSPECTIONEVENTTEMPLATE ON
                                                                                            INSPECTIONEVENTTEMPLATE.EVENTCODE = ELEMENTSINSPECTIONEVENT.CODEEVENTCODE
                                                                                        WHERE
                                                                                            ELEMENTSINSPECTION.ELEMENTCODE LIKE '%$el10%' 
                                                                                        AND
                                                                                            LEFT(ELEMENTSINSPECTIONEVENT.CODEEVENTCODE,1)='D'
                                                                                        AND ELEMENTSINSPECTION.LENGTHUOMCODE IS NULL
                                                                                        GROUP BY
                                                                                            ELEMENTSINSPECTION.QUALITYCODE";
                                                                    $db2exec2 = db2_exec($conn1, $query2_db2);
                                                                    $db2_data2 = db2_fetch_assoc($db2exec2); ?>

                                                                    <!-- End Query Db2 -->

                                                                    <tr>
                                                                        <td><?php echo $row_data_orgatex['batch_ref_no']; ?>
                                                                        </td>
                                                                        <td><?php echo $resep; ?></td>
                                                                        <td><?php echo $db2_data['DEMAND']; ?></td>
                                                                        <td><?php echo $db2_data['LONGDESCRIPTION'] ?></td>
                                                                        <td><?php echo $db2_data['NO_HANGER'] ?></td>
                                                                        <td><?php echo $db2_data['LXG'] ?></td>
                                                                        <td><?php echo $row_data_orgatex['batch_text_06'] ?>
                                                                        </td>
                                                                        <td><?php echo $row_data_orgatex['machine_no'] ?></td>
                                                                        <td><?php echo $data_mesin_baru['no_mesin_baru'] ?></td>
                                                                        <td><?php echo $data_dye['rol'] ?></td>
                                                                        <td align="right"><?php if (!empty($data_dye['bruto'])) {
                                                                            echo $data_dye['bruto'] . ' Kg';
                                                                        } else {
                                                                            echo '';
                                                                        } ?></td>
                                                                        <td align="center"><?php if (!empty($data_dye['kapasitas']) && !empty($data_dye['bruto'])) {
                                                                            $rumus_kapasitas = ROUND(($data_dye['bruto'] / $data_dye['kapasitas']), 2) * 100;
                                                                            echo $rumus_kapasitas . ' %';
                                                                        } else {
                                                                            echo '';
                                                                        } ?></td>
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
                                                                        <td><?php echo number_format($row_integ['LiquorRatio'], 2) ?>
                                                                        </td>
                                                                        <td align="left"><?php if (!empty($row_integ['LiquorRatio']) && !empty($data_dye['bruto'])) {
                                                                            $rumus_air = ROUND((number_format($row_integ['LiquorRatio'], 2) * $data_dye['bruto']));
                                                                            echo $rumus_air;
                                                                        } else {
                                                                            echo '';
                                                                        } ?></td>

                                                                        <td><?php echo $data_dye['rpm'] ?></td>
                                                                        <td><?php echo $data_dye['cycle_time'] ?></td>
                                                                        <td><?php echo $data_dye['nozzle'] ?></td>
                                                                        <td><?php echo $row_integ['Parameter8'] ?></td>
                                                                        <td><?php echo $row_integ['Parameter9'] ?></td>
                                                                        
                                                                        <td><?php echo $data_dye['plaiter'] ?></td>
                                                                        <td><?php if(!empty($db2_data2['DEFECT'])OR !empty($db2_data2['TOT_POINT'])){
                                                                            echo '(';
                                                                            echo $db2_data2['DEFECT'];
                                                                            echo ') ';
                                                                            echo $db2_data2['TOT_POINT'];
                                                                            }else{
                                                                                echo '-';
                                                                            } ?></td>
                                                                            

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
    </form>
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
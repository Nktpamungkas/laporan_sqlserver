<?php
ini_set("error_reporting", 1);
session_start();
require_once "koneksi.php";
include_once "utils/helper.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>PRD - Konsistensi Timbang Gerobak</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="#">
    <meta name="keywords"
        content="Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="#">
    <link rel="icon" href="files\assets\images\favicon.ico" type="image/x-icon">
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet"> -->
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
    <script>
        function cekStatus($before, $after, $keterangan) {
            if ($keterangan == "Before" && $before == "v" && $after == "x") {
                return "Valid";
            }
            elseif($keterangan == "Before - After" && $before == "v" && $after == "v") {
                return "Valid";
            }
            elseif($keterangan == "After" && $before == "x" && $after == "v") {
                return "Valid";
            } else {
                return "Invalid";
            }
        }
    </script>
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
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Filter Data</h5> <?php echo $_SERVER['REMOTE_ADDR']; ?>
                                    </div>
                                    <div class="card-block">
                                        <form action="" method="post">
                                            <div class="row">
                                                <div class="col-sm-12 col-xl-12 m-b-30">
                                                    <!-- <h4 class="sub-title">Creationdatetime Production Progress</h4> -->
                                                    <input type="date" class="form-control" name="date" value="<?php if (isset($_POST['submit'])) {
                                                                                                                    echo $_POST['date'];
                                                                                                                } ?>" required>
                                                </div>
                                                <div class="col-sm-12 col-xl-4 m-b-30">
                                                    <button type="submit" name="submit" class="btn btn-primary"> <i
                                                            class="icofont icofont-search-alt-1"></i> Cari data</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php if (isset($_POST['submit'])): ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-block">
                                                    <h4>FROM MECHINE LOGIC ART : TICKET</h4>
                                                    <div class="row">
                                                    <a class="btn btn-mat btn-success" target="_BLANK" href="export_excel.php?date=<?= $_POST['date'];?>">CETAK EXCEL</a>
                                                        <div class="table-responsive dt-responsive">
                                                            <table 
                                                                class="table table-striped table-bordered nowrap">
                                                                <thead>
                                                                    <tr align="center">
                                                                        <th>PRODUCTION ORDER</th>
                                                                        <th>PRODUCTION DEMAND</th>
                                                                        <th>NO PROJECT</th>
                                                                        <th>OPERATION CODE</th>
                                                                        <th>STEP NUMBER</th>
                                                                        <th>DEPT</th>
                                                                        <th>OPERATOR IN</th>
                                                                        <th>OPERATOR OUT</th>
                                                                        <th>QTY PRODUCTION ORDER</th>
                                                                        <th>BEFORE</th>
                                                                        <th>AFTER</th>
                                                                        <th>KETENTUAN</th>
                                                                        <th>STATUS</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    ini_set("error_reporting", 1);
                                                                    require_once "koneksi.php";
                                                                    $query = "SELECT 
                                                                                    p.PROGRESSNUMBER,
                                                                                    CAST(p.CREATIONDATETIME AS DATE) AS CREATIONDATETIME,
                                                                                    TRIM(p.PRODUCTIONORDERCODE) AS PRODUCTIONORDERCODE,
                                                                                    TRIM(p3.PRODUCTIONDEMANDCODE) AS PRODUCTIONDEMANDCODE,
                                                                                    TRIM(p5.ORIGDLVSALORDLINESALORDERCODE) AS ORIGDLVSALORDLINESALORDERCODE,
                                                                                    TRIM(p.OPERATIONCODE) AS OPERATIONCODE,
                                                                                    p3.STEPNUMBER,
                                                                                    o.OPERATIONGROUPCODE,
                                                                                    r.LONGDESCRIPTION,
                                                                                    CASE
                                                                                        WHEN p.PROGRESSTEMPLATECODE = 'S01' THEN r.LONGDESCRIPTION
                                                                                    END	AS OP_IN,
                                                                                    CASE
                                                                                        WHEN p.PROGRESSTEMPLATECODE = 'E01' THEN r.LONGDESCRIPTION
                                                                                    END	AS OP_OUT,
                                                                                    p4.TOTALPRIMARYQUANTITY
                                                                                FROM
                                                                                    PRODUCTIONPROGRESS p 
                                                                                LEFT JOIN PRODUCTIONPROGRESSSTEPUPDATED p2 ON p2.PROPROGRESSPROGRESSNUMBER = p.PROGRESSNUMBER
                                                                                LEFT JOIN PRODUCTIONDEMANDSTEP p3 ON p3.PRODUCTIONDEMANDCODE = p2.DEMANDSTEPPRODUCTIONDEMANDCODE AND p3.STEPNUMBER = p2.DEMANDSTEPSTEPNUMBER
                                                                                LEFT JOIN OPERATION o ON o.CODE = p.OPERATIONCODE 
                                                                                LEFT JOIN RESOURCES r ON r.CODE = p.OPERATORCODE
                                                                                LEFT JOIN PRODUCTIONORDER p4 ON p4.CODE = p.PRODUCTIONORDERCODE 
                                                                                LEFT JOIN PRODUCTIONDEMAND p5 ON p5.CODE = p3.PRODUCTIONDEMANDCODE 
                                                                                WHERE
                                                                                    CAST(p.CREATIONDATETIME AS DATE) = '$_POST[date]'
                                                                                    AND NOT p.OPERATIONCODE IN ('RTW1', 'AKJ1', 'AKJ2', 'AKW1', 'AMC', 'BAT1', 'BAT3', 'BBS1', 'BBS2', 'BBS3', 'BBS4', 'BBS5', 'BBS6', 'BBS7', 'BBS8', 'BBS9', 
                                                                                                            'BKR1', 'BLD1', 'BLD2', 'BLD3', 'BLD4', 'BLP1', 'BRD1', 'BS10', 'BS11', 'BS12', 'CCK1', 'CCK10', 'CCK2', 'CCK3', 'CCK4', 'CCK5', 
                                                                                                            'CCK6', 'CCK7', 'CCK8', 'CCK9', 'CNP1', 'DIP1', 'DRY1', 'DY1-001', 'DY1-002', 'DY1-003', 'DY1-004', 'DY1-005', 'DY1-006', 'GKF1', 
                                                                                                            'GKJ1', 'GKJ2', 'GKJ3', 'GKJ4', 'GKJ5', 'HOLD', 'INS1', 'INS4', 'INS5', 'INS6', 'INS7', 'INS9', 'KKT', 'KNT1', 'KNT2', 'LAB-T1', 
                                                                                                            'LAB-T2', 'LAB-T3', 'LAB2', 'MAT1', 'MAT1-TC1', 'MAT1-TC2', 'MAT1-TC3', 'MAT1-TC4', 'MAT1-TC5', 'MAT2', 'MAT2-R1', 'MAT2-R3', 
                                                                                                            'MAT2-R4', 'MAT2-R5', 'MAT2-R6', 'MAT2R2', 'MATD', 'MWS1', 'NCP1', 'NCP10', 'NCP11', 'NCP12', 'NCP13', 'NCP14', 'NCP15', 'NCP16', 
                                                                                                            'NCP17', 'NCP18', 'NCP19', 'NCP2', 'NCP20', 'NCP21', 'NCP22', 'NCP23', 'NCP3', 'NCP4', 'NCP5', 'NCP6', 'NCP7', 'NCP8', 'NCP9', 
                                                                                                            'OPW1', 'OPW2', 'OPW3', 'OPW4', 'PAK1', 'PBG', 'PBS', 'PER1', 'PPC1', 'PPC2', 'PPC3', 'PPC4', 'PPC5', 'PQC', 'PST1', 'PST2', 'PST3',
                                                                                                            'QCF1','QCF2', 'QCF3', 'QCF4', 'QCF5', 'QCF6', 'QCF7', 'QCF8', 'QCF9', 'RCP1', 'REW1', 'RTR1', 'SWN1', 'TBS1', 'TAS', 'TAS1', 'TAS2', 
                                                                                                            'TAS3', 'TAS4', 'TBS1', 'TEST1', 'TMF1', 'TPB', 'TRF1', 'TST', 'TTQ', 'TWR1', 'WAIT1', 'WAIT10', 'WAIT11', 'WAIT14', 'WAIT15', 'WAIT17', 
                                                                                                            'WAIT18', 'WAIT19', 'WAIT2', 'WAIT23', 'WAIT24', 'WAIT25', 'WAIT26', 'WAIT27', 'WAIT28', 'WAIT29', 'WAIT3', 'WAIT30', 'WAIT31', 'WAIT32', 
                                                                                                            'WAIT33', 'WAIT34', 'WAIT35', 'WAIT36', 'WAIT37', 'WAIT38', 'WAIT39', 'WAIT4', 'WAIT40', 'WAIT41', 'WAIT42', 'WAIT43', 'WAIT44', 'WAIT45', 
                                                                                                            'WAIT46', 'WAIT47', 'WAIT48', 'WAIT49', 'WAIT5', 'WAIT50', 'WAIT51', 'WAIT52', 'WAIT53', 'WAIT54', 'WAIT55', 'WAIT56', 'WAIT57', 'WAIT58', 
                                                                                                            'WAIT59', 'WAIT6', 'WAIT60', 'WAIT61', 'WAIT62', 'WAIT63', 'WAIT64', 'WAIT65', 'WAIT7', 'WAIT8', 'WAIT9', 'WN1-RC3', 'WN1-RC4', 'WN1-RC5', 
                                                                                                            'WN1-RP1', 'WN1-RP2', 'WN1-SC1', 'WN1-SC2', 'WN1-SP3', 'WN1-SP4', 'WSH1', 'WSH2', 'YDR1', 'YDR2')
                                                                                    AND NOT EXISTS (
                                                                                                SELECT
                                                                                                    q.QUALITYDOCPRODUCTIONORDERCODE,
                                                                                                    q2.OPERATIONCODE,
                                                                                                    q.VALUEQUANTITY
                                                                                                FROM
                                                                                                    QUALITYDOCLINE q 
                                                                                                LEFT JOIN QUALITYDOCUMENT q2 ON q2.HEADERNUMBERID = q.QUALITYDOCUMENTHEADERNUMBERID 
                                                                                                    AND q2.HEADERLINE = q.QUALITYDOCUMENTHEADERLINE 
                                                                                                    AND q2.PRODUCTIONORDERCODE = q.QUALITYDOCPRODUCTIONORDERCODE 
                                                                                                WHERE
                                                                                                    q.QUALITYDOCPRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                                                                                                    AND q.CHARACTERISTICCODE = 'GRB1'
                                                                                                    AND q.VALUEQUANTITY = '1'
                                                                                            )";
                                                                    $stmt = db2_exec($conn1, $query);
                                                                    $no = 1;
                                                                    while ($row_timbang = db2_fetch_assoc($stmt)) {
                                                                        $MainTimbangGerobak_before = "SELECT
                                                                                                        *,
                                                                                                        sum( jml_rol ) AS rol_tot,
                                                                                                        sum( berat ) AS berat_tot,
                                                                                                        sum( berat_kosong ) AS berat_kosong_tot,
                                                                                                        DATE_FORMAT( tgl_update, '%Y-%m-%d' ) AS tgl_timbang,
                                                                                                        group_concat( DISTINCT userid, ', ' ) AS user_gabung 
                                                                                                    FROM
                                                                                                        kain_proses 
                                                                                                    WHERE
                                                                                                        ket = 'before' AND prod_order = '$row_timbang[PRODUCTIONORDERCODE]' AND no_step = '$row_timbang[STEPNUMBER]'
                                                                                                    GROUP BY
                                                                                                        proses,
                                                                                                        ket,
                                                                                                        prod_order,
                                                                                                        no_demand,
                                                                                                        no_step 
                                                                                                    ORDER BY
                                                                                                        prod_order,
                                                                                                        no_step ASC";
                                                                        $execTimbangGerobak_before = mysqli_query($con_now_gerobak, $MainTimbangGerobak_before);
                                                                        $fetchTimbangGerobak_before = mysqli_fetch_assoc($execTimbangGerobak_before);

                                                                        $MainTimbangGerobak_after = "SELECT
                                                                                                        *,
                                                                                                        sum( jml_rol ) AS rol_tot,
                                                                                                        sum( berat ) AS berat_tot,
                                                                                                        sum( berat_kosong ) AS berat_kosong_tot,
                                                                                                        DATE_FORMAT( tgl_update, '%Y-%m-%d' ) AS tgl_timbang,
                                                                                                        group_concat( DISTINCT userid, ', ' ) AS user_gabung 
                                                                                                    FROM
                                                                                                        kain_proses 
                                                                                                    WHERE
                                                                                                        ket = 'after' AND prod_order = '$row_timbang[PRODUCTIONORDERCODE]' AND no_step = '$row_timbang[STEPNUMBER]'
                                                                                                    GROUP BY
                                                                                                        proses,
                                                                                                        ket,
                                                                                                        prod_order,
                                                                                                        no_demand,
                                                                                                        no_step 
                                                                                                    ORDER BY
                                                                                                        prod_order,
                                                                                                        no_step ASC";
                                                                        $execTimbangGerobak_after   = mysqli_query($con_now_gerobak, $MainTimbangGerobak_after);
                                                                        $fetchTimbangGerobak_after  = mysqli_fetch_assoc($execTimbangGerobak_after);

                                                                        $MainCekStatus  = "SELECT
                                                                                                    * 
                                                                                                FROM
                                                                                                    `tbl_masterstd` 
                                                                                                WHERE
                                                                                                    operation = '$row_timbang[OPERATIONCODE]'";
                                                                        $execCekStatus  = mysqli_query($con_now_gerobak, $MainCekStatus);
                                                                        $fetchCekStatus = mysqli_fetch_assoc($execCekStatus);
                                                                    ?>
                                                                        <tr>
                                                                            <!-- <td>'<?= $row_timbang['PRODUCTIONORDERCODE']; ?></td> -->
                                                                            <!-- <td>'<?= $row_timbang['PRODUCTIONDEMANDCODE']; ?></td> -->
                                                                            <td> <a target="_BLANK" href="http://online.indotaichen.com/laporan/ppc_filter_steps.php?demand=<?= $row_timbang['PRODUCTIONDEMANDCODE']; ?>&prod_order=<?= $row_timbang['PRODUCTIONORDERCODE']; ?>"><?= $row_timbang['PRODUCTIONORDERCODE']; ?></td>
                                                                            <td> <a target="_BLANK" href="http://online.indotaichen.com/laporan/ppc_filter_steps.php?demand=<?= $row_timbang['PRODUCTIONDEMANDCODE']; ?>&prod_order=<?= $row_timbang['PRODUCTIONORDERCODE']; ?>"><?= $row_timbang['PRODUCTIONDEMANDCODE']; ?></td>
                                                                            <td><?= $row_timbang['ORIGDLVSALORDLINESALORDERCODE']; ?></td>
                                                                            <td><?= $row_timbang['STEPNUMBER']; ?></td>
                                                                            <td><?= $row_timbang['OPERATIONCODE']; ?></td>
                                                                            <td><?= $row_timbang['OPERATIONGROUPCODE']; ?></td>
                                                                            <td><?= $row_timbang['OP_IN']; ?></td>
                                                                            <td><?= $row_timbang['OP_OUT']; ?></td>
                                                                            <td><?= $row_timbang['TOTALPRIMARYQUANTITY']; ?></td>
                                                                            <td align="center">
                                                                                <?php
                                                                                if ($fetchTimbangGerobak_before['berat']) {
                                                                                    $before = 'v';
                                                                                    echo $before;
                                                                                } else {
                                                                                    $before = 'x';
                                                                                    echo $before;
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td align="center">
                                                                                <?php
                                                                                if ($fetchTimbangGerobak_after['berat']) {
                                                                                    $after = 'v';
                                                                                    echo $after;
                                                                                } else {
                                                                                    $after = 'x';
                                                                                    echo $after;
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td><?= $fetchCekStatus['timbang_gerobak']; ?></td>
                                                                            <td>
                                                                                <?php
                                                                                $keterangan = $fetchCekStatus['timbang_gerobak'];

                                                                                if ($keterangan == "Before" && $before == "v" && $after == "x") {
                                                                                    $status = "Valid";
                                                                                } elseif ($keterangan == "Before - After" && $before == "v" && $after == "v") {
                                                                                    $status = "Valid";
                                                                                } elseif ($keterangan == "After" && $before == "x" && $after == "v") {
                                                                                    $status = "Valid";
                                                                                } else {
                                                                                    $status = "Invalid";
                                                                                }
                                                                                echo $status;
                                                                                ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
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

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');
    </script>
    <script>
        $('#excel-cams').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                customize: function (xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row c[r^="F"]', sheet).each(function () {
                        if ($('is t', this).text().replace(/[^\d]/g, '') * 1 >= 500000) {
                            $(this).attr('s', '20');
                        }
                    });
                }
            }]
        });

        $('#excel-LA').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                customize: function (xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row c[r^="F"]', sheet).each(function () {
                        if ($('is t', this).text().replace(/[^\d]/g, '') * 1 >= 500000) {
                            $(this).attr('s', '20');
                        }
                    });
                }
            }]
        });
    </script>
</body>

</html>
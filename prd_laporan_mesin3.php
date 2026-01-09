<?php
ini_set("error_reporting", 1);
session_start();
set_time_limit(0);
require_once "koneksi.php";
$kategori = $_POST['pilihan'];
$date1 = $_POST['date1'];
$date2 = $_POST['date2'];
?>
<!DOCTYPE html>
<html lang="en">
<!-- Macro Plan BRS -->

<head>
    <title>PRD - Laporan Macro</title>
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
                                        <h5>Filter Data</h5>
                                    </div>
                                    <div class="card-block">
                                        <form action="" method="post">
                                             <div class="row">
                                                 <div class="col-sm-12 col-md-6">
                                                     <h4 class="sub-title">Delivery Date</h4>
                                                     <div class="row">
                                                         <div class="col-sm-6">
                                                             <label for="date1">Tanggal Awal</label>
                                                             <input type='date' id="date1" name='date1' class="form-control" value="<?= $_POST['date1'] ?? '' ?>">
                                                         </div>
                                                         <div class="col-sm-6">
                                                             <label for="date2">Tanggal Akhir</label>
                                                             <input type='date' id="date2" name='date2' class="form-control" value="<?= $_POST['date2'] ?? '' ?>">
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div class="col-sm-12 col-md-6">
                                                     <h4 class="sub-title">Status Progress</h4><label>Status Progress Prod Demand</label><br>
                                                     <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                         <label class="btn btn-outline-primary btn-sm <?= ($kategori == "= '0'") ? 'active' : ''; ?>">
                                                             <input type="radio" name="pilihan" id="pilihan1" value="= '0'" required autocomplete="off" <?= ($kategori == "= '0'") ? 'checked' : ''; ?>> Only Open
                                                         </label>
                                                         <label class="btn btn-outline-primary btn-sm <?= ($kategori == "IN ('0','1','2')") ? 'active' : ''; ?>">
                                                             <input type="radio" name="pilihan" id="pilihan2" value="IN ('0','1','2')" required autocomplete="off" <?= ($kategori == "IN ('0','1','2')") ? 'checked' : ''; ?>> In Progress
                                                         </label>
                                                         <label class="btn btn-outline-primary btn-sm <?= ($kategori == "= '3'") ? 'active' : ''; ?>">
                                                             <input type="radio" name="pilihan" id="pilihan3" value="= '3'" required autocomplete="off" <?= ($kategori == "= '3'") ? 'checked' : ''; ?>> Closed
                                                         </label>
                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="row mt-3">
                                                 <div class="col-sm-12">
                                                     <button type="submit" name="submit" class="btn btn-primary btn-sm">
                                                         <i class="icofont icofont-search-alt-1"></i> Cari data
                                                     </button>
                                                 </div>
                                             </div>
                                        </form>
                                    </div>
                                </div>
                                <?php if (isset($_POST['submit'])): ?>
                                    <?php 
                                    // echo $kategori;
                                    $date_filter = "";
                                    if (!empty($_POST['date1']) && !empty($_POST['date2'])) {
                                        $date1 = $_POST['date1'];
                                        $date2 = $_POST['date2'];
                                        $date_filter = "DELIVERYDATE BETWEEN '$date1' AND '$date2'";
                                    } else if (!empty($_POST['date1']) && empty($_POST['date2'])) {
                                        $current_year = date('Y');
                                        $date_filter = "DELIVERYDATE BETWEEN '$date1' AND '$current_year-12-31'";
                                    } else if (empty($_POST['date1']) && !empty($_POST['date2'])) {
                                        $current_year = date('Y');
                                        $date_filter = "DELIVERYDATE BETWEEN '$current_year-01-01' AND '$date2'";
                                    } else {
                                        $current_year = date('Y');
                                        $date_filter = "DELIVERYDATE BETWEEN '$current_year-01-01' AND '$current_year-12-31'";
                                    }

                                    $query_macro = "SELECT
                                                        DISTINCT
                                                        Tanggal_Bon_Order,
                                                        CODE,
                                                        DEMAND,
                                                        DELIVERYDATE,
                                                        ACTUAL_DELIVERY,
                                                        GREIGE_AWAL,
                                                        GREIGE_AKHIR,
                                                        S1,
                                                        S2,
                                                        S3,
                                                        S4,
                                                        S5,
                                                        S6,
                                                        LANGGANAN,
                                                        WORKCENTERCODE,
                                                        CASE
                                                            WHEN S1 IN ('TC', 'CVC', 'TCX', 'CVCX')
                                                            AND OPERATIONCODE IN ('DYE2', 'DYE4') THEN 'DYE2'
                                                            ELSE OPERATIONCODE
                                                        END AS OPERATIONCODE,
                                                        qty
                                                    FROM
                                                        (
                                                        SELECT
                                                            DISTINCT 
                                                            s.CREATIONDATETIME,
                                                            DATE(s.CREATIONDATETIME) AS Tanggal_Bon_Order,
                                                            s.CODE,
                                                            p.CODE AS DEMAND,
                                                            s2.DELIVERYDATE,
                                                            trim(p.SUBCODE01)AS S1,
                                                            trim(p.SUBCODE02)AS S2,
                                                            trim(p.SUBCODE03)AS S3,
                                                            trim(p.SUBCODE04)AS S4,
                                                            trim(p.SUBCODE05)AS S5,
                                                            trim(p.SUBCODE06)AS S6,
                                                            ip.LANGGANAN || '(' || ip.BUYER || ')' AS LANGGANAN,
                                                            p2.WORKCENTERCODE,
                                                            CASE
                                                                WHEN p2.PRODRESERVATIONLINKGROUPCODE IS NULL
                                                                OR p2.PRODRESERVATIONLINKGROUPCODE = '' THEN p2.OPERATIONCODE
                                                                ELSE p2.PRODRESERVATIONLINKGROUPCODE
                                                            END AS OPERATIONCODE,
                                                            COALESCE(s2.CONFIRMEDDELIVERYDATE, s.CONFIRMEDDUEDATE) AS ACTUAL_DELIVERY,
                                                            CASE
                                                                WHEN p.ITEMTYPEAFICODE = 'KFF' THEN p.USERPRIMARYQUANTITY
                                                                WHEN p.ITEMTYPEAFICODE = 'FKF' THEN p.USERSECONDARYQUANTITY
                                                            END AS qty,
                                                            COALESCE(a2.VALUEDATE, a4.VALUEDATE) AS GREIGE_AWAL,
                                                            a.VALUEDATE AS GREIGE_AKHIR
                                                        FROM
                                                            SALESORDER s
                                                        LEFT JOIN PRODUCTIONDEMAND p ON	p.DLVSALORDERLINESALESORDERCODE = s.CODE
                                                        LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE
                                                        LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.CODE = p.ORIGDLVSALORDLINESALORDERCODE
                                                        LEFT JOIN ITXVIEWKGBRUTOBONORDER2_FKF f ON f.CODE = p.CODE
                                                        LEFT JOIN SALESORDERDELIVERY s2 ON s2.SALESORDERLINESALESORDERCODE = s.CODE	AND s2.SALESORDERLINEORDERLINE = p.DLVSALESORDERLINEORDERLINE
                                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.FIELDNAME = 'RMPGreigeReqDateTo'
                                                        LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'RMPReqDate'
                                                        LEFT JOIN ADSTORAGE a3 ON a3.UNIQUEID = p.ABSUNIQUEID AND a3.FIELDNAME = 'ProAllow'
                                                        LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = p.ABSUNIQUEID AND a4.FIELDNAME = 'ProAllowDate'
                                                        WHERE
                                                            (p2.WORKCENTERCODE = 'P3RS1'
                                                                OR p2.WORKCENTERCODE = 'P3SU1'
                                                                OR p2.WORKCENTERCODE = 'P3ST1'
                                                                OR p2.WORKCENTERCODE = 'P3CP1'
                                                                OR p2.WORKCENTERCODE = 'P3TD1'
                                                                OR p2.WORKCENTERCODE = 'P3SH1'
                                                                OR p2.WORKCENTERCODE = 'P3CO1'
                                                                OR p2.WORKCENTERCODE = 'P3AR1'
                                                                OR p2.WORKCENTERCODE = 'P3BC1'
                                                                OR p2.WORKCENTERCODE = 'P3DY1'
                                                                OR p2.WORKCENTERCODE = 'P3RX1'
                                                                OR p2.WORKCENTERCODE = 'P3CB1'
                                                                OR p2.WORKCENTERCODE = 'P3SM1'	
                                                                OR p2.WORKCENTERCODE = 'P3IN3')
                                                    --		AND s.CREATIONDATETIME BETWEEN '2025-01-01' AND '2025-02-21'
                                                    --		AND p.SUBCODE01 IN ('TC','CVC','TCX','CVCX')
                                                            AND NOT p.ORIGDLVSALORDLINESALORDERCODE IS NULL
                                                    --		AND p.CODE = '00331086'
                                                            AND p2.STEPTYPE = '0'
                                                    --		AND p2.PROGRESSSTATUS = '0' --Progress Status OPEN
                                                    --		AND p2.PROGRESSSTATUS = '3' --Progress Status Closed
                                                            -- AND p2.PROGRESSSTATUS IN ('0','1','2') --Progress Status OPEN
                                                            AND p2.PROGRESSSTATUS $kategori --Progress Status OPEN
                                                            --AND p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','DYE6')
                                                            --AND p.CODE ='00208479     '		
                                                    )
                                                        WHERE $date_filter";
                                                        $stmt = db2_exec($conn1, $query_macro);
                                                        // echo $query_macro;
                                    ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header table-card-header">
                                                    <h5>Macro Data</h5>
                                                </div>
                                                <div class="card-block">
                                                    <div class="dt-responsive table-responsive">
                                                        <table id="basic-btn" class="table compact table-bordered nowrap">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>TANGGAL BON ORDER</th>
                                                                    <th>CODE</th>
                                                                    <th>DEMAND</th>
                                                                    <th>DELIVERYDATE</th>
                                                                    <th>ACTUAL DELIVERY</th>
                                                                    <th>GREIGE AWAL</th>
                                                                    <th>GREIGE AKHIR</th>
                                                                    <th>S1</th>
                                                                    <th>S2</th>
                                                                    <th>S3</th>
                                                                    <th>S4</th>
                                                                    <th>S5</th>
                                                                    <th>S6</th>
                                                                    <th>LANGGANAN</th>
                                                                    <th>WORKCENTERCODE</th>
                                                                    <th>OPERATIONCODE</th>
                                                                    <th>QTY</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $no = 1;
                                                                    while($row = db2_fetch_assoc($stmt)):?>
                                                                    <tr>
                                                                        <td><?= $no++;?></td>
                                                                        <td><?= $row['TANGGAL_BON_ORDER'];?></td>
                                                                        <td><?= $row['CODE'];?></td>
                                                                        <td>`<a target="_BLANK" href="http://online.indotaichen.com/laporan/ppc_filter_steps.php?demand=<?= $row['DEMAND']?>"><?= $row['DEMAND']?></a></td>
                                                                        <td><?= $row['DELIVERYDATE']?></td>
                                                                        <td><?= $row['ACTUAL_DELIVERY']?></td>
                                                                        <td><?= $row['GREIGE_AWAL']?></td>
                                                                        <td><?= $row['GREIGE_AKHIR']?></td>
                                                                        <td><?= $row['S1']?></td>
                                                                        <td><?= $row['S2']?></td>
                                                                        <td><?= $row['S3']?></td>
                                                                        <td><?= $row['S4']?></td>
                                                                        <td><?= $row['S5']?></td>
                                                                        <td><?= $row['S6']?></td>
                                                                        <td><?= $row['LANGGANAN']?></td>
                                                                        <td><?= $row['WORKCENTERCODE']?></td>
                                                                        <td><?= $row['OPERATIONCODE']?></td>
                                                                        <td><?= $row['QTY']?></td>
                                                                    </tr>
                                                                <?php endwhile;?>
                                                            </tbody>
                                                        </table>
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

</body>

</html>
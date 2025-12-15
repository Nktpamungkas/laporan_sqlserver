<?php
    ini_set("error_reporting", 0);
    session_start();
    require_once "koneksi.php";
    include "utils/helper.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>PPC - Laporan Bagi Kain (PPC ke GKG)</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="#">
    <meta name="keywords" content="Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
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
    <link rel="stylesheet" type="text/css" href="files\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\pages\data-table\css\buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="files\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css">
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
                                        <h5>SERAH TERIMA KARTU KERJA KE GUDANG GREIGE</h5>
                                    </div>
                                    <div class="card-block">
                                        <form action="" method="post">
                                            <div class="row">
                                                <div class="col-sm-12 col-xl-12 m-b-30">
                                                    <h4 class="sub-title">Dari tanggal Out Cams:</h4>
                                                    <input type="date" name="tgl1" class="form-control" value="<?php if (isset($_POST['submit'])) { echo $_POST['tgl1']; } ?>" required>
                                                </div>
                                                <!-- <div class="col-sm-6 col-xl-6 m-b-30">
                                                    <h4 class="sub-title">Sampai tanggal Out Cams:</h4>
                                                    <input type="date" name="tgl2" class="form-control" value="<?php if (isset($_POST['submit'])) { echo $_POST['tgl2']; } ?>" required>
                                                </div> -->
                                                <div class="col-sm-12 col-xl-4 m-b-30">
                                                    <button type="submit" name="submit" class="btn btn-primary"><i class="icofont icofont-search-alt-1"></i> Cari data</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php if (isset($_POST['submit'])) : ?>
                                    <div class="card">
                                        <div class="card-block">
                                            <div class="dt-responsive table-responsive">
                                                <table id="download-excel" class="table table-striped table-bordered nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th width="100px" style="text-align: center;">TGL DEL</th>
                                                            <th width="100px" style="text-align: center;">FOLLOW UP</th>
                                                            <th width="100px" style="text-align: center;">NO</th>
                                                            <th width="100px" style="text-align: center;">BUYER</th>
                                                            <th width="100px" style="text-align: center;">ORDER</th>
                                                            <th width="100px" style="text-align: center;">NO. ITEM</th>
                                                            <th width="100px" style="text-align: center;">WARNA</th>
                                                            <th width="100px" style="text-align: center;">BRUTO</th>
                                                            <th width="100px" style="text-align: center;">NETTO</th>
                                                            <th width="100px" style="text-align: center;">LOT</th>
                                                            <th width="100px" style="text-align: center;">PRODUCTION ORDER</th>
                                                            <th width="100px" style="text-align: center;">DEMAND</th>
                                                            <th width="100px" style="text-align: center;">QTY PLAN BAGI</th>
                                                            <th width="100px" style="text-align: center;">QTY ACTUAL BAGI</th>
                                                            <!-- <th width="100px" style="text-align: center;">KETERANGAN</th> -->
                                                            <th width="100px" style="text-align: center;">STATUS TERAKHIR</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $sqlMain = "SELECT DISTINCT  
                                                                            TRIM(p.PRODUCTIONORDERCODE) AS PRODUCTIONORDERCODE,
	                                                                        TRIM(p2.DEMANDSTEPPRODUCTIONDEMANDCODE) AS PRODUCTIONDEMANDCODE
                                                                        FROM
                                                                            PRODUCTIONPROGRESS p
                                                                        LEFT JOIN PRODUCTIONPROGRESSSTEPUPDATED p2 ON p2.PROPROGRESSPROGRESSNUMBER = p.PROGRESSNUMBER 
                                                                        WHERE
                                                                            -- p.PROGRESSENDDATE BETWEEN '$_POST[tgl1]' AND '$_POST[tgl2]'
                                                                            p.PROGRESSENDDATE = '$_POST[tgl1]'
                                                                            AND p.OPERATIONCODE IN ('WAIT2','WAIT23','WAIT26','WAIT27','WAIT3','WAIT31')";
                                                            $resultMain = db2_exec($conn1, $sqlMain);
                                                            $no = 1;
                                                            $dataList=[];
                                                            while ($row = db2_fetch_assoc($resultMain)) {
                                                                $row['PRODUCTIONORDERCODE'] = trim($row['PRODUCTIONORDERCODE']);
                                                                $row['PRODUCTIONDEMANDCODE'] = trim($row['PRODUCTIONDEMANDCODE']);
                                                                $dataList[] = $row;
                                                            }

                                                            $countOrder = [];
                                                            foreach ($dataList as $d) {
                                                                $poc = $d['PRODUCTIONORDERCODE'];
                                                                if (!isset($countOrder[$poc])) {
                                                                    $countOrder[$poc] = 0;
                                                                }
                                                                $countOrder[$poc]++;
                                                            }

                                                            // print_r($countOrder);
                                                            foreach ($dataList as $dataMain) {
                                                                $sqlDetail = "SELECT
                                                                                    *
                                                                                FROM
                                                                                    ITXVIEW_POSISI_KARTU_KERJA ipkk
                                                                                WHERE
                                                                                    ipkk.PRODUCTIONORDERCODE = '$dataMain[PRODUCTIONORDERCODE]'
                                                                                    AND ipkk.PRODUCTIONDEMANDCODE = '$dataMain[PRODUCTIONDEMANDCODE]'
                                                                                    -- AND NOT EXISTS (
                                                                                    --     SELECT
                                                                                    --         1
                                                                                    --     FROM
                                                                                    --         ITXVIEW_POSISI_KARTU_KERJA ipkk2
                                                                                    --     WHERE
                                                                                    --         ipkk2.PRODUCTIONORDERCODE = '$dataMain[PRODUCTIONORDERCODE]'
                                                                                    --         AND ipkk2.PRODUCTIONDEMANDCODE = '$dataMain[PRODUCTIONDEMANDCODE]'
                                                                                    --         AND ipkk2.OPERATIONCODE = 'BAT1'
                                                                                    --         AND (ipkk2.STATUS_OPERATION = 'Closed' OR ipkk2.STATUS_OPERATION = 'Progress')
                                                                                    -- )
                                                                                    AND ipkk.DEPT = 'PPC'";
                                                                $resultDetail = db2_exec($conn1, $sqlDetail);
                                                                // Ambil data detail
                                                                if ($dataDetail = db2_fetch_assoc($resultDetail)) {
                                                                    $sqlMain_demand     = db2_exec($conn1, "SELECT * FROM PRODUCTIONDEMAND WHERE CODE = '$dataDetail[PRODUCTIONDEMANDCODE]'");
                                                                    $row_main_demand    = db2_fetch_assoc($sqlMain_demand);

                                                                    $sqlMain_salesorder = db2_exec($conn1, "SELECT * FROM SALESORDER WHERE CODE = '$row_main_demand[ORIGDLVSALORDLINESALORDERCODE]'");
                                                                    $row_main_salesorder = db2_fetch_assoc($sqlMain_salesorder);

                                                                    $sqlMain_actDevliery      = db2_exec($conn1, "SELECT
                                                                                                                    COALESCE(s2.CONFIRMEDDELIVERYDATE, s.CONFIRMEDDUEDATE) AS ACTUAL_DELIVERY
                                                                                                                FROM
                                                                                                                    SALESORDER s 
                                                                                                                LEFT JOIN SALESORDERDELIVERY s2 ON s2.SALESORDERLINESALESORDERCODE = s.CODE AND s2.SALORDLINESALORDERCOMPANYCODE = s.COMPANYCODE AND s2.SALORDLINESALORDERCOUNTERCODE = s.COUNTERCODE 
                                                                                                                WHERE
                                                                                                                    s2.SALESORDERLINESALESORDERCODE = '$row_main_demand[ORIGDLVSALORDLINESALORDERCODE]'
                                                                                                                    AND s2.SALESORDERLINEORDERLINE = '$row_main_demand[ORIGDLVSALORDERLINEORDERLINE]'");
                                                                    $dataActDevliery    = db2_fetch_assoc($sqlMain_actDevliery);

                                                                    $sqlMain_pelanggan = db2_exec($conn1, "SELECT * FROM ITXVIEW_PELANGGAN WHERE CODE = '$row_main_salesorder[CODE]' AND ORDPRNCUSTOMERSUPPLIERCODE = '$row_main_salesorder[ORDPRNCUSTOMERSUPPLIERCODE]'");
                                                                    $row_main_pelanggan = db2_fetch_assoc($sqlMain_pelanggan);

                                                                    $sqlMain_warna = "SELECT 
                                                                                        TRIM(WARNA) AS WARNA
                                                                                    FROM 
                                                                                        ITXVIEWCOLOR 
                                                                                    WHERE 
                                                                                        ITEMTYPECODE = '$row_main_demand[ITEMTYPEAFICODE]' 
                                                                                        AND SUBCODE01 = '$row_main_demand[SUBCODE01]' 
                                                                                        AND SUBCODE02 = '$row_main_demand[SUBCODE02]'
                                                                                        AND SUBCODE03 = '$row_main_demand[SUBCODE03]' 
                                                                                        AND SUBCODE04 = '$row_main_demand[SUBCODE04]'
                                                                                        AND SUBCODE05 = '$row_main_demand[SUBCODE05]' 
                                                                                        AND SUBCODE06 = '$row_main_demand[SUBCODE06]'
                                                                                        AND SUBCODE07 = '$row_main_demand[SUBCODE07]' 
                                                                                        AND SUBCODE08 = '$row_main_demand[SUBCODE08]'
                                                                                        AND SUBCODE09 = '$row_main_demand[SUBCODE09]' 
                                                                                        AND SUBCODE10 = '$row_main_demand[SUBCODE10]'";
                                                                    $resultMain_warna   = db2_exec($conn1, $sqlMain_warna);
                                                                    $data_warna         = db2_fetch_assoc($resultMain_warna);
                                                                    
                                                                    $sqlMain_bruto  = db2_exec($conn1, "SELECT
                                                                                                            p.ORIGDLVSALORDLINESALORDERCODE,
                                                                                                            p.ORIGDLVSALORDERLINEORDERLINE,
                                                                                                            SUM(p2.USERPRIMARYQUANTITY) AS BRUTO
                                                                                                        FROM
                                                                                                            SALESORDER s
                                                                                                        LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE 
                                                                                                        LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE = 'KFF'
                                                                                                        LEFT JOIN PRODUCTIONRESERVATION p2 ON p2.ORDERCODE = p.CODE AND p2.ITEMTYPEAFICODE = 'KGF'
                                                                                                        WHERE
                                                                                                            s.CODE = '$row_main_salesorder[CODE]'
                                                                                                            AND p.ORIGDLVSALORDERLINEORDERLINE = '$row_main_demand[ORIGDLVSALORDERLINEORDERLINE]'
                                                                                                        GROUP BY 
                                                                                                            p.ORIGDLVSALORDLINESALORDERCODE,
                                                                                                            p.ORIGDLVSALORDERLINEORDERLINE");
                                                                    $data_bruto     = db2_fetch_assoc($sqlMain_bruto);

                                                                    $sqlMain_netto = db2_exec($conn1, "SELECT
                                                                                                            in2.USERPRIMARYQUANTITY AS NETTO,
                                                                                                            in2.CODE,
                                                                                                            in2.SALESORDERLINESALESORDERCODE
                                                                                                        FROM
                                                                                                            ITXVIEW_NETTO in2
                                                                                                        WHERE 
                                                                                                            in2.CODE = '$dataDetail[PRODUCTIONDEMANDCODE]' 
                                                                                                            AND in2.SALESORDERLINESALESORDERCODE = '$row_main_salesorder[CODE]'");
                                                                    $data_netto     = db2_fetch_assoc($sqlMain_netto);

                                                                    $sqlMain_bruto_plan_bagikain  = db2_exec($conn1, "SELECT
                                                                                                            p.ORIGDLVSALORDLINESALORDERCODE,
                                                                                                            p.ORIGDLVSALORDERLINEORDERLINE,
                                                                                                            p.CODE,
                                                                                                            SUM(p2.USERPRIMARYQUANTITY) AS BRUTO
                                                                                                        FROM
                                                                                                            SALESORDER s
                                                                                                        LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE 
                                                                                                        LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE = 'KFF'
                                                                                                        LEFT JOIN PRODUCTIONRESERVATION p2 ON p2.ORDERCODE = p.CODE AND p2.ITEMTYPEAFICODE = 'KGF'
                                                                                                        WHERE
                                                                                                            s.CODE = '$row_main_salesorder[CODE]'
                                                                                                            AND p.CODE = '$dataDetail[PRODUCTIONDEMANDCODE]'
                                                                                                        GROUP BY 
                                                                                                            p.ORIGDLVSALORDLINESALORDERCODE,
                                                                                                            p.ORIGDLVSALORDERLINEORDERLINE,
                                                                                                            p.CODE");
                                                                    $data_bruto_plan_bagikain     = db2_fetch_assoc($sqlMain_bruto_plan_bagikain);
                                                                    
                                                                    $sqlMain_bruto_actual_bagikain  = db2_exec($conn1, "SELECT
                                                                                                            p.ORIGDLVSALORDLINESALORDERCODE,
                                                                                                            p.ORIGDLVSALORDERLINEORDERLINE,
                                                                                                            p.CODE,
                                                                                                            SUM(p2.USEDUSERPRIMARYQUANTITY) AS BRUTO
                                                                                                        FROM
                                                                                                            SALESORDER s
                                                                                                        LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE 
                                                                                                        LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE = 'KFF'
                                                                                                        LEFT JOIN PRODUCTIONRESERVATION p2 ON p2.ORDERCODE = p.CODE AND p2.ITEMTYPEAFICODE = 'KGF'
                                                                                                        WHERE
                                                                                                            s.CODE = '$row_main_salesorder[CODE]'
                                                                                                            AND p.CODE = '$dataDetail[PRODUCTIONDEMANDCODE]'
                                                                                                        GROUP BY 
                                                                                                            p.ORIGDLVSALORDLINESALORDERCODE,
                                                                                                            p.ORIGDLVSALORDERLINEORDERLINE,
                                                                                                            p.CODE");
                                                                    $data_bruto_actual_bagikain     = db2_fetch_assoc($sqlMain_bruto_actual_bagikain);

                                                                    $sqlMain_StatusTerakhir = db2_exec($conn1, "SELECT
                                                                                                                    OPERATIONCODE || ' - ' || LONGDESCRIPTION AS STATUSTERAKHIR
                                                                                                                FROM
                                                                                                                    ITXVIEW_POSISI_KARTU_KERJA ipkk
                                                                                                                WHERE
                                                                                                                    ipkk.PRODUCTIONORDERCODE = '$dataMain[PRODUCTIONORDERCODE]'
                                                                                                                    AND ipkk.PRODUCTIONDEMANDCODE = '$dataMain[PRODUCTIONDEMANDCODE]'
                                                                                                                    AND ipkk.STATUS_OPERATION <> 'Closed'
                                                                                                                ORDER BY
                                                                                                                    ipkk.STEPNUMBER ASC
                                                                                                                FETCH FIRST 1 ROW ONLY;");
                                                                    $dataMain_StatusTerakhir = db2_fetch_assoc($sqlMain_StatusTerakhir);
                                                                    $sqlBruto = db2_exec($conn1, "SELECT
                                                                                                        p.*,
                                                                                                        a.VALUEDECIMAL AS BRUTO_KK
                                                                                                    FROM
                                                                                                        PRODUCTIONDEMAND p
                                                                                                    LEFT JOIN ADSTORAGE a 
                                                                                                        ON a.UNIQUEID = p.ABSUNIQUEID
                                                                                                        AND a.FIELDNAME = 'OriginalBruto'
                                                                                                    WHERE
                                                                                                        p.CODE = '$dataMain[PRODUCTIONDEMANDCODE]'");
                                                                    $rowBruto = db2_fetch_assoc($sqlBruto);
                                                                    $StatusTerakhir = $dataMain_StatusTerakhir['STATUSTERAKHIR'] ?? '';
                                                                    $productionorder = trim($dataDetail['PRODUCTIONORDERCODE']);
                                                                    $color = (isset($countOrder[$productionorder]) && $countOrder[$productionorder] >= 2) ? "color:red;" : "";
                                                        ?>
                                                            <tr>
                                                                <td width="100px" style="text-align: center;"><?= $dataActDevliery['ACTUAL_DELIVERY'] ?></td> <!-- TGL DEL -->
                                                                <td width="100px" style="text-align: center;"></td> <!-- FOLLOW UP -->
                                                                <td width="100px" style="text-align: center;"><?= $no++; ?></td> <!-- NO -->
                                                                <td width="100px" style="text-align: center;"><?= $row_main_pelanggan['BUYER'] ?></td> <!-- BUYER -->
                                                                <td width="100px" style="text-align: center;"><?= $row_main_salesorder['CODE'] ?></td> <!-- ORDER -->
                                                                <td width="100px" style="text-align: center;"><?= TRIM($row_main_demand['SUBCODE02']).TRIM($row_main_demand['SUBCODE03']); ?></td> <!-- NO. ITEM -->
                                                                <td width="100px" style="text-align: center;"><?= $data_warna['WARNA']; ?></td> <!-- WARNA -->
                                                                <td width="100px" style="text-align: center;"><?= number_format($rowBruto['BRUTO_KK']?? 0, 2); ?></td> <!-- BRUTO -->
                                                                <!-- <td width="100px" style="text-align: center;"><?= number_format($data_bruto['BRUTO'], 2); ?></td> BRUTO -->
                                                                <td width="100px" style="text-align: center;"><?= number_format($data_netto['NETTO'], 2) ?></td> <!-- NETTO -->
                                                                <td width="100px" style="text-align: center;"><?= $row_main_demand['DESCRIPTION']; ?></td> <!-- LOT -->
                                                                <td width="100px" style="text-align:center; <?= $color ?>"><?= $productionorder ?></td>
                                                                <td width="100px" style="text-align: center;"><a target="_BLANK" href="http://online.indotaichen.com/laporan/ppc_filter_steps.php?demand=<?= $dataDetail['PRODUCTIONDEMANDCODE'] ?>&prod_order=<?= $dataDetail['PRODUCTIONORDERCODE'] ?>"><?= $dataDetail['PRODUCTIONDEMANDCODE'] ?></a></td> <!-- DEMAND -->
                                                                <td width="100px" style="text-align: center;"><?= number_format($rowBruto['BRUTO_KK']?? 0, 2); ?></td><!-- QTY PLAN BAGI -->
                                                                <!-- <td width="100px" style="text-align: center;"><?= number_format($data_bruto_plan_bagikain['BRUTO'], 2); ?></td> QTY PLAN BAGI -->
                                                                <td width="100px" style="text-align: center;"><?= number_format($data_bruto_actual_bagikain['BRUTO'], 2); ?></td> <!-- QTY ACTUAL BAGI -->
                                                                <!-- <td width="100px" style="text-align: center;"></td> KETERANGAN -->
                                                                <td width="100px" style="text-align: center;"><?= $StatusTerakhir ?></td> <!-- STATUS TERAKHIR -->
                                                            </tr>
                                                        <?php } } ?>
                                                    </tbody>
                                                </table>
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
</body>
<script src="files\assets\js\pcoded.min.js"></script>
<script type="text/javascript" src="files\assets\js\script.js"></script>
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
<script type="text/javascript" src="files\bower_components\i18next-browser-languagedetector\js\i18nextBrowserLanguageDetector.min.js"></script>
<script type="text/javascript" src="files\bower_components\jquery-i18next\js\jquery-i18next.min.js"></script>
<script src="files\assets\pages\data-table\extensions\buttons\js\extension-btns-custom.js"></script>
<script src="files\assets\js\menu\menu-hori-fixed.js"></script>
<script src="files\assets\js\jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript" src="files\assets\js\script.js"></script>
<script>
    $('#download-excel').DataTable({
    order: [
        [2, 'asc']
    ],
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'excelHtml5',
            text: 'Export Excel',
            className: 'btn btn-success',
            customize: function(xlsx) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                $('row c[r^="F"]', sheet).each(function() {
                    if ($('is t', this).text().replace(/[^\d]/g, '') * 1 >= 500000) {
                        $(this).attr('s', '20');
                    }
                });
            }
        },
        {
            text: 'Cetak Report',
            className: 'btn btn-primary',
            action: function() {
                window.open('ppc_bagi_kain_print.php?tgl=<?= $_POST['tgl1'] ?>', '_blank');
            }
        }
    ]
});

</script>
<?php require_once 'footer.php'; ?>
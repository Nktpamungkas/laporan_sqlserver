<?php
ini_set("error_reporting", 0);
session_start();
require_once "koneksi.php";
include "utils/helper.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>PPC - Status Greige</title>
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
    <style>
        .button-container {
            position: relative;
            display: inline-block;
        }

        .new-label {
            background-color: yellow; /* Warna latar belakang label */
            color: black; /* Warna teks label */
            padding: 5px 10px; /* Padding untuk label */
            border-radius: 5px; /* Sudut melengkung */
            position: absolute; /* Posisi absolut untuk label */
            top: -10px; /* Atur posisi vertikal */
            right: -10px; /* Atur posisi horizontal */
            font-weight: bold; /* Tebal */
            font-size: 12px; /* Ukuran font */
        }
    </style>
</head>
<?php require_once 'header.php'; ?>

<body>
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">
                        <div class="card">
                            <div class="card-block">
                                <form action="" method="post">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card mt-3">
                                                <div class="card-header">
                                                    <!-- <h5 class="mb-0">Hasil Pencarian</h5> -->
                                                </div>
                                                <div class="card-block">
                                                    <div class="table-responsive">
                                                        <table id="excel-status-greige" class="table table-striped table-bordered nowrap">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>SALES ORDER</th>
                                                                    <th>HANGER</th>
                                                                    <th>VARIAN</th>
                                                                    <th>WARNA</th>
                                                                    <!-- <th>PROJECT GREIGE</th> -->
                                                                    <th>GREIGE NEEDS</th>
                                                                    <th>GREIGE READY</th>
                                                                    <th>DELIVERY GREIGE (BON ORDER)</th>
                                                                    <th>DELIVERY KAIN JADI (ACTUAL)</th>
                                                                    <th>STATUS RAJUT</th>
                                                                    <th>STATUS PPC</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    $qDataMain  = "WITH RAJUT AS (
                                                                                    SELECT 
                                                                                        b.PROJECTCODE,
                                                                                        b.DECOSUBCODE01,
                                                                                        b.DECOSUBCODE02,
                                                                                        b.DECOSUBCODE03,
                                                                                        b.DECOSUBCODE04,
                                                                                        b.LOGICALWAREHOUSECODE,
                                                                                        SUM(b.BASEPRIMARYQUANTITYUNIT) AS QTY_RAJUT_READY
                                                                                    FROM(
                                                                                        SELECT DISTINCT 
                                                                                            p.ORIGDLVSALORDLINESALORDERCODE,
                                                                                            p.SUBCODE01,
                                                                                            p.SUBCODE02,
                                                                                            p.SUBCODE03,
                                                                                            p.SUBCODE04
                                                                                        FROM
                                                                                            PRODUCTIONDEMAND p 
                                                                                        WHERE
                                                                                            p.ITEMTYPEAFICODE = 'KGF'
                                                                                            ) PRODUCTIONDEMAND
                                                                                    LEFT JOIN BALANCE b ON b.DECOSUBCODE01 = PRODUCTIONDEMAND.SUBCODE01 
                                                                                                        AND b.DECOSUBCODE02 = PRODUCTIONDEMAND.SUBCODE02 
                                                                                                        AND b.DECOSUBCODE03 = PRODUCTIONDEMAND.SUBCODE03 
                                                                                                        AND b.DECOSUBCODE04 = PRODUCTIONDEMAND.SUBCODE04
                                                                                                        AND b.PROJECTCODE = PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE 
                                                                                    AND 
                                                                                        LOGICALWAREHOUSECODE = 'M021'
                                                                                    GROUP BY
                                                                                        b.PROJECTCODE,
                                                                                        b.DECOSUBCODE01,
                                                                                        b.DECOSUBCODE02,
                                                                                        b.DECOSUBCODE03,
                                                                                        b.DECOSUBCODE04,
                                                                                        b.LOGICALWAREHOUSECODE
                                                                                ),
                                                                                BOOKING_BLM_READY1 AS (
                                                                                    SELECT 
                                                                                        b.PROJECTCODE,
                                                                                        b.DECOSUBCODE01,
                                                                                        b.DECOSUBCODE02,
                                                                                        b.DECOSUBCODE03,
                                                                                        b.DECOSUBCODE04,
                                                                                        b.LOGICALWAREHOUSECODE,
                                                                                        SUM(b.BASEPRIMARYQUANTITYUNIT) AS QTY_RAJUT_READY
                                                                                    FROM(
                                                                                        SELECT DISTINCT 
                                                                                            p.ORIGDLVSALORDLINESALORDERCODE,
                                                                                            p.SUBCODE01,
                                                                                            p.SUBCODE02,
                                                                                            p.SUBCODE03,
                                                                                            p.SUBCODE04
                                                                                        FROM
                                                                                            PRODUCTIONDEMAND p 
                                                                                        WHERE
                                                                                            p.ITEMTYPEAFICODE = 'KGF'
                                                                                            ) PRODUCTIONDEMAND
                                                                                    LEFT JOIN BALANCE b ON b.DECOSUBCODE01 = PRODUCTIONDEMAND.SUBCODE01 
                                                                                                        AND b.DECOSUBCODE02 = PRODUCTIONDEMAND.SUBCODE02 
                                                                                                        AND b.DECOSUBCODE03 = PRODUCTIONDEMAND.SUBCODE03 
                                                                                                        AND b.DECOSUBCODE04 = PRODUCTIONDEMAND.SUBCODE04
                                                                                                        AND b.PROJECTCODE = PRODUCTIONDEMAND.ORIGDLVSALORDLINESALORDERCODE 
                                                                                    AND 
                                                                                        LOGICALWAREHOUSECODE = 'M021'
                                                                                    GROUP BY
                                                                                        b.PROJECTCODE,
                                                                                        b.DECOSUBCODE01,
                                                                                        b.DECOSUBCODE02,
                                                                                        b.DECOSUBCODE03,
                                                                                        b.DECOSUBCODE04,
                                                                                        b.LOGICALWAREHOUSECODE
                                                                                )
                                                                                SELECT 
                                                                                    p2.ORIGDLVSALORDLINESALORDERCODE AS NO_ORDER,
                                                                                    TRIM(p2.SUBCODE02) || '-' || TRIM(p2.SUBCODE03) AS HANGER,
                                                                                    i.WARNA,
                                                                                    s.CONFIRMEDDELIVERYDATE AS ACTUAL_DELIVERY,
                                                                                    p3.SUBCODE04 AS VARIAN,
                                                                                    r.PROJECTCODE AS PROJECTCODE_RAJUT,
                                                                                    r.QTY_RAJUT_READY,
                                                                                    r1.PROJECTCODE AS PROJECTCODE_BOOKING_BLMREADY1,
                                                                                    r1.QTY_RAJUT_READY AS QTY_BOOKING_BLMREADY1,
                                                                                    r2.PROJECTCODE AS PROJECTCODE_BOOKING_BLMREADY2,
                                                                                    r2.QTY_RAJUT_READY AS QTY_BOOKING_BLMREADY2,
                                                                                    r3.PROJECTCODE AS PROJECTCODE_BOOKING_BLMREADY3,
                                                                                    r3.QTY_RAJUT_READY AS QTY_BOOKING_BLMREADY3,
                                                                                    r4.PROJECTCODE AS PROJECTCODE_BOOKING_BLMREADY4,
                                                                                    r4.QTY_RAJUT_READY AS QTY_BOOKING_BLMREADY4,
                                                                                    r5.PROJECTCODE AS PROJECTCODE_BOOKING_BLMREADY5,
                                                                                    r5.QTY_RAJUT_READY AS QTY_BOOKING_BLMREADY5,
                                                                                    r6.PROJECTCODE AS PROJECTCODE_BOOKING_BLMREADY6,
                                                                                    r6.QTY_RAJUT_READY AS QTY_BOOKING_BLMREADY6,
                                                                                    ibn.ONLY_PROJECTCODE AS PROJECTCODE_READY1,
                                                                                    ibn.QTY_ALOKASI_BRUTO AS QTY_READY1
                                                                                FROM
                                                                                    PRODUCTIONDEMANDSTEP p 
                                                                                LEFT JOIN PRODUCTIONDEMAND p2 ON p2.CODE = p.PRODUCTIONDEMANDCODE 
                                                                                LEFT JOIN SALESORDER s2 ON s2.CODE = p2.ORIGDLVSALORDLINESALORDERCODE 
                                                                                LEFT JOIN PRODUCTIONRESERVATION p3 ON p3.ITEMTYPEAFICODE = 'KGF' AND p3.ORDERCODE = p2.CODE 
                                                                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p2.ABSUNIQUEID AND a.FIELDNAME = 'OriginalPDCode'
                                                                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p2.ABSUNIQUEID AND a2.FIELDNAME = 'ProAllow'
                                                                                LEFT JOIN ADSTORAGE a3 ON a3.UNIQUEID = p2.ABSUNIQUEID AND a3.FIELDNAME = 'ProAllow2'
                                                                                LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = p2.ABSUNIQUEID AND a4.FIELDNAME = 'ProAllow3'
                                                                                LEFT JOIN ADSTORAGE a5 ON a5.UNIQUEID = p2.ABSUNIQUEID AND a5.FIELDNAME = 'ProAllow4'
                                                                                LEFT JOIN ADSTORAGE a6 ON a6.UNIQUEID = p2.ABSUNIQUEID AND a6.FIELDNAME = 'ProAllow5'
                                                                                LEFT JOIN ADSTORAGE a7 ON a7.UNIQUEID = p2.ABSUNIQUEID AND a7.FIELDNAME = 'ProAllow7'
                                                                                LEFT JOIN ITXVIEWCOLOR i ON i.ITEMTYPECODE = p2.ITEMTYPEAFICODE 
                                                                                                        AND i.SUBCODE01 = p2.SUBCODE01 
                                                                                                        AND i.SUBCODE02 = p2.SUBCODE02 
                                                                                                        AND i.SUBCODE03 = p2.SUBCODE03 
                                                                                                        AND i.SUBCODE04 = p2.SUBCODE04 
                                                                                                        AND i.SUBCODE05 = p2.SUBCODE05 
                                                                                                        AND i.SUBCODE06 = p2.SUBCODE06 
                                                                                                        AND i.SUBCODE07 = p2.SUBCODE07 
                                                                                                        AND i.SUBCODE08 = p2.SUBCODE08 
                                                                                                        AND i.SUBCODE09 = p2.SUBCODE09 
                                                                                                        AND i.SUBCODE10 = p2.SUBCODE10
                                                                                LEFT JOIN SALESORDERDELIVERY s ON s.SALESORDERLINESALESORDERCODE = p2.ORIGDLVSALORDLINESALORDERCODE 
                                                                                                            AND s.SALESORDERLINEORDERLINE = p2.ORIGDLVSALORDERLINEORDERLINE
                                                                                LEFT JOIN RAJUT r ON r.PROJECTCODE = p2.ORIGDLVSALORDLINESALORDERCODE 
                                                                                                AND r.DECOSUBCODE01 = p3.SUBCODE01
                                                                                                AND r.DECOSUBCODE02 = p3.SUBCODE02
                                                                                                AND r.DECOSUBCODE03 = p3.SUBCODE03
                                                                                                AND r.DECOSUBCODE04 = p3.SUBCODE04
                                                                                LEFT JOIN BOOKING_BLM_READY1 r1 ON r1.PROJECTCODE = a2.VALUESTRING 
                                                                                                AND r1.DECOSUBCODE01 = p3.SUBCODE01
                                                                                                AND r1.DECOSUBCODE02 = p3.SUBCODE02
                                                                                                AND r1.DECOSUBCODE03 = p3.SUBCODE03
                                                                                                AND r1.DECOSUBCODE04 = p3.SUBCODE04
                                                                                LEFT JOIN BOOKING_BLM_READY1 r2 ON r2.PROJECTCODE = a3.VALUESTRING 
                                                                                                AND r2.DECOSUBCODE01 = p3.SUBCODE01
                                                                                                AND r2.DECOSUBCODE02 = p3.SUBCODE02
                                                                                                AND r2.DECOSUBCODE03 = p3.SUBCODE03
                                                                                                AND r2.DECOSUBCODE04 = p3.SUBCODE04
                                                                                LEFT JOIN BOOKING_BLM_READY1 r3 ON r3.PROJECTCODE = a4.VALUESTRING 
                                                                                                AND r3.DECOSUBCODE01 = p3.SUBCODE01
                                                                                                AND r3.DECOSUBCODE02 = p3.SUBCODE02
                                                                                                AND r3.DECOSUBCODE03 = p3.SUBCODE03
                                                                                                AND r3.DECOSUBCODE04 = p3.SUBCODE04
                                                                                LEFT JOIN BOOKING_BLM_READY1 r4 ON r4.PROJECTCODE = a5.VALUESTRING 
                                                                                                AND r4.DECOSUBCODE01 = p3.SUBCODE01
                                                                                                AND r4.DECOSUBCODE02 = p3.SUBCODE02
                                                                                                AND r4.DECOSUBCODE03 = p3.SUBCODE03
                                                                                                AND r4.DECOSUBCODE04 = p3.SUBCODE04
                                                                                LEFT JOIN BOOKING_BLM_READY1 r5 ON r5.PROJECTCODE = a6.VALUESTRING 
                                                                                                AND r5.DECOSUBCODE01 = p3.SUBCODE01
                                                                                                AND r5.DECOSUBCODE02 = p3.SUBCODE02
                                                                                                AND r5.DECOSUBCODE03 = p3.SUBCODE03
                                                                                                AND r5.DECOSUBCODE04 = p3.SUBCODE04
                                                                                LEFT JOIN BOOKING_BLM_READY1 r6 ON r6.PROJECTCODE = a7.VALUESTRING 
                                                                                                AND r6.DECOSUBCODE01 = p3.SUBCODE01
                                                                                                AND r6.DECOSUBCODE02 = p3.SUBCODE02
                                                                                                AND r6.DECOSUBCODE03 = p3.SUBCODE03
                                                                                                AND r6.DECOSUBCODE04 = p3.SUBCODE04
                                                                                LEFT JOIN ITXVIEW_BOOKING_NEW ibn ON ibn.SALESORDERCODE = p2.ORIGDLVSALORDLINESALORDERCODE AND ibn.ORDERLINE = p2.ORIGDLVSALORDERLINEORDERLINE 
                                                                                WHERE
                                                                                    p.OPERATIONCODE = 'BAT1'
                                                                                    AND p.PROGRESSSTATUS IN ('0', '1')
                                                                                    AND a.VALUESTRING IS NULL
                                                                                    AND CAST(p2.CREATIONDATETIME AS DATE) > '2024-01-01' 
                                                                                    AND p2.ITEMTYPEAFICODE = 'KFF'
                                                                                    AND p2.PROGRESSSTATUS = '2'
                                                                                    AND NOT p2.ORIGDLVSALORDLINESALORDERCODE IS NULL
                                                                                    AND p3.ITEMTYPEAFICODE = 'KGF'
                                                                                    AND s2.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                                                                GROUP BY
                                                                                    p2.ORIGDLVSALORDLINESALORDERCODE,
                                                                                    p2.SUBCODE02,
                                                                                    p2.SUBCODE03,
                                                                                    i.WARNA,
                                                                                    s.CONFIRMEDDELIVERYDATE,
                                                                                    p3.SUBCODE04,
                                                                                    r.PROJECTCODE,
                                                                                    r.QTY_RAJUT_READY,
                                                                                    r1.PROJECTCODE,
                                                                                    r1.QTY_RAJUT_READY,
                                                                                    r2.PROJECTCODE,
                                                                                    r2.QTY_RAJUT_READY,
                                                                                    r3.PROJECTCODE,
                                                                                    r3.QTY_RAJUT_READY,
                                                                                    r4.PROJECTCODE,
                                                                                    r4.QTY_RAJUT_READY,
                                                                                    r5.PROJECTCODE,
                                                                                    r5.QTY_RAJUT_READY,
                                                                                    r6.PROJECTCODE,
                                                                                    r6.QTY_RAJUT_READY,
                                                                                    ibn.ONLY_PROJECTCODE,
                                                                                    ibn.QTY_ALOKASI_BRUTO";
                                                                    $execMain   = db2_exec($conn1, $qDataMain);
                                                                ?>
                                                                <?php while ($rowMain   = db2_fetch_assoc($execMain))  : ?>
                                                                    <tr>
                                                                        <td><?= $rowMain['NO_ORDER'] ?></td>
                                                                        <td><?= $rowMain['HANGER'] ?></td>
                                                                        <td><?= $rowMain['VARIAN'] ?></td>
                                                                        <td><?= $rowMain['WARNA'] ?></td>
                                                                        <!-- <td><?= $rowMain['PROJECTCODE_RAJUT'] ?? $rowMain['PROJECTCODE_BOOKING_BLMREADY1'] ?? $rowMain['PROJECTCODE_BOOKING_BLMREADY3'] ?? $rowMain['PROJECTCODE_BOOKING_BLMREADY4'] ?? $rowMain['PROJECTCODE_BOOKING_BLMREADY5'] ?? $rowMain['PROJECTCODE_BOOKING_BLMREADY6'] ?></td> -->
                                                                        <?php
                                                                        $qtyTotal = 
                                                                            ($rowMain['QTY_BOOKING_BLMREADY1'] ?? 0) + 
                                                                            ($rowMain['QTY_BOOKING_BLMREADY2'] ?? 0) + 
                                                                            ($rowMain['QTY_BOOKING_BLMREADY3'] ?? 0) + 
                                                                            ($rowMain['QTY_BOOKING_BLMREADY4'] ?? 0) + 
                                                                            ($rowMain['QTY_BOOKING_BLMREADY5'] ?? 0) + 
                                                                            ($rowMain['QTY_BOOKING_BLMREADY6'] ?? 0);
                                                                        ?>

                                                                        <td><!-- <?= rtrim(rtrim($qtyTotal, '0'), '.') ?> --></td>
                                                                        <td><?= rtrim(rtrim($rowMain['QTY_RAJUT_READY'] ?? 0, '0'), '.') ?></td>
                                                                        <td><?= $rowMain[''] ?></td>
                                                                        <td><?= $rowMain['ACTUAL_DELIVERY'] ?></td>
                                                                        <td><?= $rowMain[''] ?></td>
                                                                        <td><?= $rowMain[''] ?></td>
                                                                    </tr>
                                                                <?php endwhile; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </form>
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
<?php require_once 'footer.php'; ?>
<script>
    $('#excel-status-greige').DataTable({
        dom: 'Bfrtip',
        text: '<i class="fa fa-file-excel-o"></i> Export Excel',
        className: 'btn btn-success',
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

    function getCurrentDateTimeForDB2() {
        const now = new Date();

        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }

    function updateDateTime() {
        const db2Time = getCurrentDateTimeForDB2();
        document.getElementById('datenow').value = db2Time;
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>
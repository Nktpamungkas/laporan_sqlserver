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
    <!-- Daterangepicker CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .button-container {
            position: relative;
            display: inline-block;
        }
        table.dataTable thead tr {
            height: auto !important;
        }
    </style>
    <style>
        .status-complete {
            color: green;
            font-weight: bold;
            animation: blink 1s step-start 0s infinite;
        }

        .status-progress {
            color: orange;
            font-weight: bold;
        }

        @keyframes blink {
            50% {
                opacity: 0;
            }
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

                                                    <?php
                                                        function formatQty($value) {
                                                            return ((float)$value != 0) ? number_format((float)$value, 2) : '';
                                                        }
                                                    ?>

                                                    <div class="table-responsive">
                                                        <table id="excel-status-greige" class="table table-striped table-bordered">
                                                            <thead class="thead-light">
                                                                <tr style="height: unset !important;">
                                                                    <th>SALES ORDER</th>
                                                                    <th>PRODUCTION DEMAND KFF</th>
                                                                    <th>HANGER</th>
                                                                    <th>VARIAN</th>
                                                                    <th>WARNA</th>
                                                                    <th>DELIVERY GREIGE<br>(BON ORDER)</th>
                                                                    <th>DELIVERY KAIN JADI<br>(ACTUAL)</th>
                                                                    <th>GREIGE NEEDS</th>
                                                                     <th>PROJECT CODE RAJUT</th>
                                                                     <th>QTY RAJUT READY</th>
                                                                     <th class="text-center">PROJECT CODE BOOKING<br>BLMREADY 1</th>
                                                                     <th class="text-center">QTY BOOKING<br>BLM READY 1</th>
                                                                     <th class="text-center">PROJECT CODE BOOKING<br>BLM READY 2</th>
                                                                     <th class="text-center">QTY BOOKING<br>BLM READY 2</th>
                                                                     <th class="text-center">PROJECT CODE BOOKING<br>BLM READY 3</th>
                                                                     <th class="text-center">QTY BOOKING<br>BLM READY 3</th>
                                                                     <th class="text-center">PROJECT CODE BOOKING<br>BLM READY 4</th>
                                                                     <th class="text-center">QTY BOOKING<br>BLM READY 4</th>
                                                                     <th class="text-center">PROJECT CODE BOOKING<br>BLM READY 5</th>
                                                                     <th class="text-center">QTY BOOKING<br>BLM READY 5</th>
                                                                     <th class="text-center">PROJECT CODE BOOKING<br>BLM READY 6</th>
                                                                     <th class="text-center">QTY BOOKING<br>BLM READY 6</th>
                                                                     <th class="text-center">PROJECT CODE BOOKING<br>BLM READY 7</th>
                                                                     <th class="text-center">QTY BOOKING<br>BLM READY 7</th>
                                                                    <th>STATUS</th>
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
                                                                                SELECT DISTINCT
                                                                                    LISTAGG( DISTINCT TRIM(p.PRODUCTIONDEMANDCODE),',') as DEMAND_KFF,
                                                                                    (p2.ORIGDLVSALORDERLINEORDERLINE) AS ORDERLINE,
                                                                                    p2.ORIGDLVSALORDLINESALORDERCODE AS NO_ORDER,
                                                                                    TRIM(p2.SUBCODE02) || '-' || TRIM(p2.SUBCODE03) AS HANGER,
                                                                                    i.WARNA,
                                                                                    p2.SUBCODE01,
                                                                                    p2.SUBCODE02,
                                                                                    p2.SUBCODE03,
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
                                                                                    r6.PROJECTCODE AS PROJECTCODE_BOOKING_BLMREADY7,
                                                                                    r6.QTY_RAJUT_READY AS QTY_BOOKING_BLMREADY7,
                                                                                    ibn.ONLY_PROJECTCODE AS PROJECTCODE_READY1,
                                                                                    a2.VALUESTRING AS ADDITIONALDATA1,
                                                                                    a3.VALUESTRING AS ADDITIONALDATA2,
                                                                                    a4.VALUESTRING AS ADDITIONALDATA3,
                                                                                    a5.VALUESTRING AS ADDITIONALDATA4,
                                                                                    a6.VALUESTRING AS ADDITIONALDATA5,
                                                                                    a7.VALUESTRING AS ADDITIONALDATA6
                                                                                FROM
                                                                                    PRODUCTIONDEMANDSTEP p 
                                                                                LEFT JOIN PRODUCTIONDEMAND p2 ON p2.CODE = p.PRODUCTIONDEMANDCODE 
                                                                                LEFT JOIN SALESORDER s2 ON s2.CODE = p2.ORIGDLVSALORDLINESALORDERCODE 
                                                                                LEFT JOIN SALESORDERLINE s3 ON s3.SALESORDERCODE = p2.ORIGDLVSALORDLINESALORDERCODE 
                                                                                                        AND s3.ORDERLINE = p2.ORIGDLVSALORDERLINEORDERLINE 
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
                                                                                    AND s3.LINESTATUS = '1'
                                                                                    -- AND p2.ORIGDLVSALORDLINESALORDERCODE = 'SME2500244'
                                                                                GROUP BY
                                                                                    p2.ORIGDLVSALORDERLINEORDERLINE,
                                                                                    p2.ORIGDLVSALORDLINESALORDERCODE,
                                                                                    p2.SUBCODE01,
                                                                                    p2.SUBCODE02,
                                                                                    p2.SUBCODE03,
                                                                                    i.WARNA,
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
                                                                                    ibn.QTY_ALOKASI_BRUTO,
                                                                                    a2.VALUESTRING,
                                                                                    a3.VALUESTRING,
                                                                                    a4.VALUESTRING,
                                                                                    a5.VALUESTRING,
                                                                                    a6.VALUESTRING,
                                                                                    a7.VALUESTRING";
                                                                    $execMain   = db2_exec($conn1, $qDataMain);
                                                                ?>
                                                                <?php while ($rowMain   = db2_fetch_assoc($execMain))  : ?>
                                                                    <?php
                                                                        // DELIVERY GREIGE (BON ORDER)
                                                                            $qDelGreige1 = "SELECT DISTINCT
                                                                                                SALESORDERCODE,
                                                                                                ORDERLINE,
                                                                                                DATE_AKTUAL,
                                                                                                DATE_AKTUAL2,
                                                                                                DATE_AKTUAL3,
                                                                                                DATE_AKTUAL4,
                                                                                                DATE_AKTUAL5,
                                                                                                DATE_AKTUAL6,
                                                                                                DATE_AKTUAL7,
                                                                                                DATE_AKTUAL_TO,
                                                                                                DATE_AKTUAL_TO2,
                                                                                                DATE_AKTUAL_TO3,
                                                                                                DATE_AKTUAL_TO4,
                                                                                                DATE_AKTUAL_TO5,
                                                                                                DATE_AKTUAL_TO6,
                                                                                                DATE_AKTUAL_TO7,
                                                                                                ADDITIONALDATA
                                                                                            FROM ITXVIEWBONORDER i
                                                                                            WHERE 
                                                                                                i.SALESORDERCODE = '$rowMain[NO_ORDER]'
                                                                                                AND i.ORDERLINE = '$rowMain[ORDERLINE]'";
                                                                            $execDelGreige1 = db2_exec($conn1, $qDelGreige1);
                                                                            $rowDelGreige1 = db2_fetch_assoc($execDelGreige1);
                                                                            
                                                                            $qDelGreige2 = "SELECT 
                                                                                                a7.VALUEDATE AS TGLRENCANA,
                                                                                                a8.VALUEDATE AS TGLPOGREIGE
                                                                                            FROM(
                                                                                                SELECT DISTINCT 
                                                                                                    p.ORIGDLVSALORDLINESALORDERCODE,
                                                                                                    p.SUBCODE01,
                                                                                                    p.SUBCODE02,
                                                                                                    p.SUBCODE03,
                                                                                                    p.SUBCODE04,
                                                                                                    p.ABSUNIQUEID
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
                                                                                            LEFT JOIN ADSTORAGE a7 ON a7.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND a7.FIELDNAME = 'RMPReqDate'
                                                                                            LEFT JOIN ADSTORAGE a8 ON a8.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND a8.FIELDNAME = 'RMPGreigeReqDateTo'
                                                                                            AND 
                                                                                                LOGICALWAREHOUSECODE = 'M021'
                                                                                            WHERE
                                                                                                b.PROJECTCODE = '$rowMain[PROJECTCODE_RAJUT]'
                                                                                                AND b.DECOSUBCODE01 = '$rowMain[SUBCODE01]'
                                                                                                AND b.DECOSUBCODE02 = '$rowMain[SUBCODE02]'
                                                                                                AND b.DECOSUBCODE03 = '$rowMain[SUBCODE03]'
                                                                                                AND b.DECOSUBCODE04 = '$rowMain[VARIAN]'
                                                                                                AND b.LOGICALWAREHOUSECODE = 'M021'
                                                                                            GROUP BY
                                                                                                a7.VALUEDATE,
                                                                                                a8.VALUEDATE ";
                                                                            $execDelGreige2 = db2_exec($conn1, $qDelGreige2);
                                                                            $rowDelGreige2 = db2_fetch_assoc($execDelGreige2);
                                                                            
                                                                            $qDelGreige3 = "SELECT 
                                                                                                a7.VALUEDATE AS TGLRENCANA,
                                                                                                a8.VALUEDATE AS TGLPOGREIGE
                                                                                            FROM(
                                                                                                SELECT DISTINCT 
                                                                                                    p.ORIGDLVSALORDLINESALORDERCODE,
                                                                                                    p.SUBCODE01,
                                                                                                    p.SUBCODE02,
                                                                                                    p.SUBCODE03,
                                                                                                    p.SUBCODE04,
                                                                                                    p.ABSUNIQUEID
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
                                                                                            LEFT JOIN ADSTORAGE a7 ON a7.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND a7.FIELDNAME = 'RMPReqDate'
                                                                                            LEFT JOIN ADSTORAGE a8 ON a8.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND a8.FIELDNAME = 'RMPGreigeReqDateTo'
                                                                                            AND 
                                                                                                LOGICALWAREHOUSECODE = 'M021'
                                                                                            WHERE
                                                                                                b.PROJECTCODE = '$rowMain[ADDITIONALDATA1]'
                                                                                                AND b.DECOSUBCODE01 = '$rowMain[SUBCODE01]'
                                                                                                AND b.DECOSUBCODE02 = '$rowMain[SUBCODE02]'
                                                                                                AND b.DECOSUBCODE03 = '$rowMain[SUBCODE03]'
                                                                                                AND b.DECOSUBCODE04 = '$rowMain[VARIAN]'
                                                                                                AND b.LOGICALWAREHOUSECODE = 'M021'
                                                                                            GROUP BY
                                                                                                a7.VALUEDATE,
                                                                                                a8.VALUEDATE ";
                                                                            $execDelGreige3 = db2_exec($conn1, $qDelGreige3);
                                                                            $rowDelGreige3 = db2_fetch_assoc($execDelGreige3);
                                                                            
                                                                            $qDelGreige32 = "SELECT 
                                                                                                a7.VALUEDATE AS TGLRENCANA,
                                                                                                a8.VALUEDATE AS TGLPOGREIGE
                                                                                            FROM(
                                                                                                SELECT DISTINCT 
                                                                                                    p.ORIGDLVSALORDLINESALORDERCODE,
                                                                                                    p.SUBCODE01,
                                                                                                    p.SUBCODE02,
                                                                                                    p.SUBCODE03,
                                                                                                    p.SUBCODE04,
                                                                                                    p.ABSUNIQUEID
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
                                                                                            LEFT JOIN ADSTORAGE a7 ON a7.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND a7.FIELDNAME = 'RMPReqDate'
                                                                                            LEFT JOIN ADSTORAGE a8 ON a8.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND a8.FIELDNAME = 'RMPGreigeReqDateTo'
                                                                                            AND 
                                                                                                LOGICALWAREHOUSECODE = 'M021'
                                                                                            WHERE
                                                                                                b.PROJECTCODE = '$rowMain[ADDITIONALDATA2]'
                                                                                                AND b.DECOSUBCODE01 = '$rowMain[SUBCODE01]'
                                                                                                AND b.DECOSUBCODE02 = '$rowMain[SUBCODE02]'
                                                                                                AND b.DECOSUBCODE03 = '$rowMain[SUBCODE03]'
                                                                                                AND b.DECOSUBCODE04 = '$rowMain[VARIAN]'
                                                                                                AND b.LOGICALWAREHOUSECODE = 'M021'
                                                                                            GROUP BY
                                                                                                a7.VALUEDATE,
                                                                                                a8.VALUEDATE ";
                                                                            $execDelGreige32 = db2_exec($conn1, $qDelGreige32);
                                                                            $rowDelGreige32 = db2_fetch_assoc($execDelGreige32);
                                                                            
                                                                            $qDelGreige33 = "SELECT 
                                                                                                a7.VALUEDATE AS TGLRENCANA,
                                                                                                a8.VALUEDATE AS TGLPOGREIGE
                                                                                            FROM(
                                                                                                SELECT DISTINCT 
                                                                                                    p.ORIGDLVSALORDLINESALORDERCODE,
                                                                                                    p.SUBCODE01,
                                                                                                    p.SUBCODE02,
                                                                                                    p.SUBCODE03,
                                                                                                    p.SUBCODE04,
                                                                                                    p.ABSUNIQUEID
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
                                                                                            LEFT JOIN ADSTORAGE a7 ON a7.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND a7.FIELDNAME = 'RMPReqDate'
                                                                                            LEFT JOIN ADSTORAGE a8 ON a8.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND a8.FIELDNAME = 'RMPGreigeReqDateTo'
                                                                                            AND 
                                                                                                LOGICALWAREHOUSECODE = 'M021'
                                                                                            WHERE
                                                                                                b.PROJECTCODE = '$rowMain[ADDITIONALDATA3]'
                                                                                                AND b.DECOSUBCODE01 = '$rowMain[SUBCODE01]'
                                                                                                AND b.DECOSUBCODE02 = '$rowMain[SUBCODE02]'
                                                                                                AND b.DECOSUBCODE03 = '$rowMain[SUBCODE03]'
                                                                                                AND b.DECOSUBCODE04 = '$rowMain[VARIAN]'
                                                                                                AND b.LOGICALWAREHOUSECODE = 'M021'
                                                                                            GROUP BY
                                                                                                a7.VALUEDATE,
                                                                                                a8.VALUEDATE ";
                                                                            $execDelGreige33 = db2_exec($conn1, $qDelGreige33);
                                                                            $rowDelGreige33 = db2_fetch_assoc($execDelGreige33);
                                                                            
                                                                            $qDelGreige34 = "SELECT 
                                                                                                a7.VALUEDATE AS TGLRENCANA,
                                                                                                a8.VALUEDATE AS TGLPOGREIGE
                                                                                            FROM(
                                                                                                SELECT DISTINCT 
                                                                                                    p.ORIGDLVSALORDLINESALORDERCODE,
                                                                                                    p.SUBCODE01,
                                                                                                    p.SUBCODE02,
                                                                                                    p.SUBCODE03,
                                                                                                    p.SUBCODE04,
                                                                                                    p.ABSUNIQUEID
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
                                                                                            LEFT JOIN ADSTORAGE a7 ON a7.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND a7.FIELDNAME = 'RMPReqDate'
                                                                                            LEFT JOIN ADSTORAGE a8 ON a8.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND a8.FIELDNAME = 'RMPGreigeReqDateTo'
                                                                                            AND 
                                                                                                LOGICALWAREHOUSECODE = 'M021'
                                                                                            WHERE
                                                                                                b.PROJECTCODE = '$rowMain[ADDITIONALDATA4]'
                                                                                                AND b.DECOSUBCODE01 = '$rowMain[SUBCODE01]'
                                                                                                AND b.DECOSUBCODE02 = '$rowMain[SUBCODE02]'
                                                                                                AND b.DECOSUBCODE03 = '$rowMain[SUBCODE03]'
                                                                                                AND b.DECOSUBCODE04 = '$rowMain[VARIAN]'
                                                                                                AND b.LOGICALWAREHOUSECODE = 'M021'
                                                                                            GROUP BY
                                                                                                a7.VALUEDATE,
                                                                                                a8.VALUEDATE ";
                                                                            $execDelGreige34 = db2_exec($conn1, $qDelGreige34);
                                                                            $rowDelGreige34 = db2_fetch_assoc($execDelGreige34);
                                                                            
                                                                            $qDelGreige35 = "SELECT 
                                                                                                a7.VALUEDATE AS TGLRENCANA,
                                                                                                a8.VALUEDATE AS TGLPOGREIGE
                                                                                            FROM(
                                                                                                SELECT DISTINCT 
                                                                                                    p.ORIGDLVSALORDLINESALORDERCODE,
                                                                                                    p.SUBCODE01,
                                                                                                    p.SUBCODE02,
                                                                                                    p.SUBCODE03,
                                                                                                    p.SUBCODE04,
                                                                                                    p.ABSUNIQUEID
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
                                                                                            LEFT JOIN ADSTORAGE a7 ON a7.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND a7.FIELDNAME = 'RMPReqDate'
                                                                                            LEFT JOIN ADSTORAGE a8 ON a8.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND a8.FIELDNAME = 'RMPGreigeReqDateTo'
                                                                                            AND 
                                                                                                LOGICALWAREHOUSECODE = 'M021'
                                                                                            WHERE
                                                                                                b.PROJECTCODE = '$rowMain[ADDITIONALDATA5]'
                                                                                                AND b.DECOSUBCODE01 = '$rowMain[SUBCODE01]'
                                                                                                AND b.DECOSUBCODE02 = '$rowMain[SUBCODE02]'
                                                                                                AND b.DECOSUBCODE03 = '$rowMain[SUBCODE03]'
                                                                                                AND b.DECOSUBCODE04 = '$rowMain[VARIAN]'
                                                                                                AND b.LOGICALWAREHOUSECODE = 'M021'
                                                                                            GROUP BY
                                                                                                a7.VALUEDATE,
                                                                                                a8.VALUEDATE ";
                                                                            $execDelGreige35 = db2_exec($conn1, $qDelGreige35);
                                                                            $rowDelGreige35 = db2_fetch_assoc($execDelGreige35);

                                                                            $qDelGreige36 = "SELECT 
                                                                                                a7.VALUEDATE AS TGLRENCANA,
                                                                                                a8.VALUEDATE AS TGLPOGREIGE
                                                                                            FROM(
                                                                                                SELECT DISTINCT 
                                                                                                    p.ORIGDLVSALORDLINESALORDERCODE,
                                                                                                    p.SUBCODE01,
                                                                                                    p.SUBCODE02,
                                                                                                    p.SUBCODE03,
                                                                                                    p.SUBCODE04,
                                                                                                    p.ABSUNIQUEID
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
                                                                                            LEFT JOIN ADSTORAGE a7 ON a7.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND a7.FIELDNAME = 'RMPReqDate'
                                                                                            LEFT JOIN ADSTORAGE a8 ON a8.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND a8.FIELDNAME = 'RMPGreigeReqDateTo'
                                                                                            AND 
                                                                                                LOGICALWAREHOUSECODE = 'M021'
                                                                                            WHERE
                                                                                                b.PROJECTCODE = '$rowMain[ADDITIONALDATA6]'
                                                                                                AND b.DECOSUBCODE01 = '$rowMain[SUBCODE01]'
                                                                                                AND b.DECOSUBCODE02 = '$rowMain[SUBCODE02]'
                                                                                                AND b.DECOSUBCODE03 = '$rowMain[SUBCODE03]'
                                                                                                AND b.DECOSUBCODE04 = '$rowMain[VARIAN]'
                                                                                                AND b.LOGICALWAREHOUSECODE = 'M021'
                                                                                            GROUP BY
                                                                                                a7.VALUEDATE,
                                                                                                a8.VALUEDATE ";
                                                                            $execDelGreige36 = db2_exec($conn1, $qDelGreige36);
                                                                            $rowDelGreige36 = db2_fetch_assoc($execDelGreige36);
                                                                        // DELIVERY GREIGE (BON ORDER)

                                                                        // DELIVERY KAIN JADI (ACTUAL)
                                                                            $qDelKainJadiActual = "SELECT DISTINCT 
                                                                                                        CASE 
                                                                                                            WHEN s2.CONFIRMEDDELIVERYDATE IS NULL THEN s.CONFIRMEDDUEDATE
                                                                                                            ELSE s2.CONFIRMEDDELIVERYDATE 
                                                                                                        END AS ACTUAL_DELIVERY
                                                                                                    FROM
                                                                                                        ITXVIEWBONORDER i
                                                                                                    LEFT JOIN SALESORDERDELIVERY s2 ON s2.SALESORDERLINESALESORDERCODE = i.SALESORDERCODE AND s2.SALESORDERLINEORDERLINE = i.ORDERLINE 
                                                                                                    LEFT JOIN SALESORDER s ON s.CODE = s2.SALESORDERLINESALESORDERCODE
                                                                                                    WHERE 
                                                                                                        i.SALESORDERCODE = 'DOM2500360'
                                                                                                        AND i.ORDERLINE = '40'
                                                                                                    GROUP BY
                                                                                                        i.SUBCODE02,
                                                                                                        i.SUBCODE03,
                                                                                                        s2.CONFIRMEDDELIVERYDATE,
                                                                                                        s.CONFIRMEDDUEDATE";
                                                                            $execDelKainJadiActual  = db2_exec($conn1, $qDelKainJadiActual);
                                                                            $rowDelKainJadiActual   = db2_fetch_assoc($execDelKainJadiActual);
                                                                            $dataDelKainJadiActual  = $rowDelKainJadiActual['ACTUAL_DELIVERY'] ?? '';
                                                                        // DELIVERY KAIN JADI (ACTUAL)
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= $rowMain['NO_ORDER'] ?></td>
                                                                        <td style="width: 220px; white-space: normal; overflow-wrap: break-word; word-break: break-word;">
                                                                            <?= htmlspecialchars($rowMain['DEMAND_KFF']) ?>
                                                                        </td>
                                                                        <td><?= $rowMain['HANGER'] ?></td>
                                                                        <td><?= $rowMain['VARIAN'] ?></td>
                                                                        <td><?= $rowMain['WARNA'] ?></td>
                                                                        <td>
                                                                            <!-- 1 -->
                                                                            <?= ($rowDelGreige1['DATE_AKTUAL'] ? $rowDelGreige1['DATE_AKTUAL'] . ' - ' : '') . $rowDelGreige1['DATE_AKTUAL_TO'] ?> &nbsp;
                                                                            <?= ($rowDelGreige1['DATE_AKTUAL2'] ? $rowDelGreige1['DATE_AKTUAL2'] . ' - ' : '') . $rowDelGreige1['DATE_AKTUAL_TO2'] ?> &nbsp;
                                                                            <?= ($rowDelGreige1['DATE_AKTUAL3'] ? $rowDelGreige1['DATE_AKTUAL3'] . ' - ' : '') . $rowDelGreige1['DATE_AKTUAL_TO3'] ?> &nbsp;
                                                                            <?= ($rowDelGreige1['DATE_AKTUAL4'] ? $rowDelGreige1['DATE_AKTUAL4'] . ' - ' : '') . $rowDelGreige1['DATE_AKTUAL_TO4'] ?> &nbsp;
                                                                            <?= ($rowDelGreige1['DATE_AKTUAL5'] ? $rowDelGreige1['DATE_AKTUAL5'] . ' - ' : '') . $rowDelGreige1['DATE_AKTUAL_TO5'] ?> &nbsp;
                                                                            <?= ($rowDelGreige1['DATE_AKTUAL6'] ? $rowDelGreige1['DATE_AKTUAL6'] . ' - ' : '') . $rowDelGreige1['DATE_AKTUAL_TO6'] ?> &nbsp;
                                                                            <?= ($rowDelGreige1['DATE_AKTUAL7'] ? $rowDelGreige1['DATE_AKTUAL7'] . ' - ' : '') . $rowDelGreige1['DATE_AKTUAL_TO7'] ?> &nbsp;

                                                                            <!-- 2 -->
                                                                            <?= $rowDelGreige2['TGLRENCANA'] . $rowDelGreige2['TGLPOGREIGE'] ?> &nbsp;

                                                                            <!-- 3 -->
                                                                            <?= $rowDelGreige3['TGLRENCANA'] . $rowDelGreige3['TGLPOGREIGE'] ?> &nbsp;
                                                                            <?= $rowDelGreige32['TGLRENCANA'] . $rowDelGreige32['TGLPOGREIGE'] ?> &nbsp;
                                                                            <?= $rowDelGreige33['TGLRENCANA'] . $rowDelGreige33['TGLPOGREIGE'] ?> &nbsp;
                                                                            <?= $rowDelGreige34['TGLRENCANA'] . $rowDelGreige34['TGLPOGREIGE'] ?> &nbsp;
                                                                            <?= $rowDelGreige35['TGLRENCANA'] . $rowDelGreige35['TGLPOGREIGE'] ?> &nbsp;
                                                                            <?= $rowDelGreige36['TGLRENCANA'] . $rowDelGreige36['TGLPOGREIGE'] ?> &nbsp;

                                                                        </td>
                                                                        <td><?= $dataDelKainJadiActual; ?></td>
                                                                        <td><?php $query_qty_br="SELECT 
                                                                                                    SUM(USERPRIMARYQUANTITY) AS QTY_BRUTO,
                                                                                                    MAX(USERPRIMARYUOMCODE) AS UOMCODE,
                                                                                                    MAX(i.ORIGDLVSALORDLINESALORDERCODE),
                                                                                                    MAX(i.ORIGDLVSALORDERLINEORDERLINE) 
                                                                                                FROM ITXVIEWKGBRUTOBONORDER2 i 
                                                                                                WHERE 
                                                                                                    i.ORIGDLVSALORDLINESALORDERCODE ='$rowMain[NO_ORDER]'
                                                                                                    AND i.ORIGDLVSALORDERLINEORDERLINE= '$rowMain[ORDERLINE]'
                                                                                                    GROUP BY
                                                                                                    i.ITEMTYPE_DEMAND,
                                                                                                    i.SUBCODE01,
                                                                                                    i.SUBCODE02,
                                                                                                    i.SUBCODE03,
                                                                                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                                                                                    i.ORIGDLVSALORDLINESALORDERCODE";
                                                                                        $stmt_kg = db2_exec($conn1, $query_qty_br);
                                                                                        $data_kg = db2_fetch_assoc($stmt_kg);
                                                                                    // echo $data_kg['QTY_BRUTO']. ' '. $data_kg['UOMCODE']; 
                                                                                    echo formatQty((float)($data_kg['QTY_BRUTO'] ?? 0), 2);
                                                                            ?>
                                                                        </td>
                                                                        <td><?= $rowMain['PROJECTCODE_RAJUT'] ?></td>
                                                                        <td><?= formatQty((float)($rowMain['QTY_RAJUT_READY'] ?? 0), 2) ?></td>
                                                                        <td><?= $rowMain['PROJECTCODE_BOOKING_BLMREADY1'] ?></td>
                                                                        <td><?= formatQty((float)($rowMain['QTY_BOOKING_BLMREADY1'] ?? 0), 2) ?></td>
                                                                        <td><?= $rowMain['PROJECTCODE_BOOKING_BLMREADY2'] ?></td>
                                                                        <td><?= formatQty((float)($rowMain['QTY_BOOKING_BLMREADY2'] ?? 0), 2) ?></td>
                                                                        <td><?= $rowMain['PROJECTCODE_BOOKING_BLMREADY3'] ?></td>
                                                                        <td><?= formatQty((float)($rowMain['QTY_BOOKING_BLMREADY3'] ?? 0), 2) ?></td>
                                                                        <td><?= $rowMain['PROJECTCODE_BOOKING_BLMREADY4'] ?></td>
                                                                        <td><?= formatQty((float)($rowMain['QTY_BOOKING_BLMREADY4'] ?? 0), 2) ?></td>
                                                                        <td><?= $rowMain['PROJECTCODE_BOOKING_BLMREADY5'] ?></td>
                                                                        <td><?= formatQty((float)($rowMain['QTY_BOOKING_BLMREADY5'] ?? 0), 2) ?></td>
                                                                        <td><?= $rowMain['PROJECTCODE_BOOKING_BLMREADY6'] ?></td>
                                                                        <td><?= formatQty((float)($rowMain['QTY_BOOKING_BLMREADY6'] ?? 0), 2) ?></td>
                                                                        <td><?= $rowMain['PROJECTCODE_BOOKING_BLMREADY7'] ?></td>
                                                                        <td><?= formatQty((float)($rowMain['QTY_BOOKING_BLMREADY7'] ?? 0), 2) ?></td>

                                                                        <?php
                                                                            // Hitung semua qty ready
                                                                            $qtyRajut = (float)($rowMain['QTY_RAJUT_READY'] ?? 0);
                                                                            $qty1 = (float)($rowMain['QTY_BOOKING_BLMREADY1'] ?? 0);
                                                                            $qty2 = (float)($rowMain['QTY_BOOKING_BLMREADY2'] ?? 0);
                                                                            $qty3 = (float)($rowMain['QTY_BOOKING_BLMREADY3'] ?? 0);
                                                                            $qty4 = (float)($rowMain['QTY_BOOKING_BLMREADY4'] ?? 0);
                                                                            $qty5 = (float)($rowMain['QTY_BOOKING_BLMREADY5'] ?? 0);
                                                                            $qty6 = (float)($rowMain['QTY_BOOKING_BLMREADY6'] ?? 0);
                                                                            $qty7 = (float)($rowMain['QTY_BOOKING_BLMREADY7'] ?? 0);

                                                                            $qtyReadyTotal = $qtyRajut + $qty1 + $qty2 + $qty3 + $qty4 + $qty5 + $qty6 + $qty7;
                                                                            $qtyBruto = (float)($data_kg['QTY_BRUTO'] ?? 0);

                                                                            $status = ($qtyReadyTotal >= $qtyBruto && $qtyBruto > 0) ? 'COMPLETE' : 'ON PROGRESS';
                                                                            $statusClass = 'status-blink ';
                                                                            $statusClass .= ($status === 'COMPLETE') ? 'status-complete' : 'status-progress';
                                                                        ?>
                                                                        <td class="<?= $statusClass ?>"><?= $status ?></td>
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

<!-- Moment.js dan Daterangepicker JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(function () {
        $('#daterange').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            startDate: moment().subtract(30, 'days'),
            endDate: moment()
        });
    });
</script>
<?php require_once 'footer.php'; ?>
<script>
    $('#excel-status-greige').DataTable({
        scrollX: true,
        autoWidth: false,
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
        }],
        columnDefs: [
            { width: '220px', targets: 1 },
        ]
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
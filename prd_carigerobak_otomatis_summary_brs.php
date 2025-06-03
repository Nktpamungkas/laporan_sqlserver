<?php
    ob_start();
?>

<?php
    ini_set("error_reporting", 0);
    session_start();
    require_once "koneksi.php";

    date_default_timezone_set('Asia/Jakarta');

    $bulan = [
        1 => 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI',
        'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOVEMBER', 'DESEMBER',
    ];

    $tgl = date('j');
    $bln = $bulan[(int) date('n')];
    $thn = date('Y');

    // Dapatkan jam sekarang
    $jam = (int) date('H');

    // Tentukan waktu
    if ($jam >= 5 && $jam < 12) {
        $waktu = 'PAGI';
    } else {
        $waktu = 'SORE';
    }

?>

<?php
    // Variabel Summary
    $WET_SUEDING_QTY     = 0;
    $WET_SUEDING_GEROBAK = 0;

    $AIRO_QTY     = 0;
    $AIRO_GEROBAK = 0;

    $ANTI_PILLING_QTY     = 0;
    $ANTI_PILLING_GEROBAK = 0;

    $SISIR_QTY     = 0;
    $SISIR_GEROBAK = 0;

    $GARUK_QTY     = 0;
    $GARUK_GEROBAK = 0;

    $POTONG_BULU_QTY     = 0;
    $POTONG_BULU_GEROBAK = 0;

    $PEACH_SKIN_QTY     = 0;
    $PEACH_SKIN_GEROBAK = 0;

    $POLISHING_QTY     = 0;
    $POLISHING_GEROBAK = 0;

    $ADM_BRS_QTY     = 0;
    $ADM_BRS_GEROBAK = 0;

    $INSPEK_BRS_QTY     = 0;
    $INSPEK_BRS_GEROBAK = 0;

    $NCP_QTY     = 0;
    $NCP_GEROBAK = 0;

    $PERSIAPAN_QTY     = 0;
    $PERSIAPAN_GEROBAK = 0;

    $TOTAL_QTY_SUMMARY     = 0;
    $TOTAL_GEROBAK_SUMMARY = 0;

    function brs_summary($operation, $qty, $jml)
    {
        global $WET_SUEDING_QTY, $WET_SUEDING_GEROBAK,
        $AIRO_QTY, $AIRO_GEROBAK,
        $ANTI_PILLING_QTY, $ANTI_PILLING_GEROBAK,
        $SISIR_QTY, $SISIR_GEROBAK,
        $GARUK_QTY, $GARUK_GEROBAK,
        $POTONG_BULU_QTY, $POTONG_BULU_GEROBAK,
        $PEACH_SKIN_QTY, $PEACH_SKIN_GEROBAK,
        $POLISHING_QTY, $POLISHING_GEROBAK,
        $ADM_BRS_QTY, $ADM_BRS_GEROBAK,
        $INSPEK_BRS_QTY, $INSPEK_BRS_GEROBAK,
        $NCP_QTY, $NCP_GEROBAK,
        $PERSIAPAN_QTY, $PERSIAPAN_GEROBAK;

        $WET_SUEDING  = ['WET1', 'WET2', 'WET3', 'WET4'];
        $AIRO         = ['AIR1'];
        $ANTI_PILLING = ['TDR1'];
        $SISIR        = ['COM1', 'COM2'];
        $GARUK        = ['RSE1', 'RSE2', 'RSE3', 'RSE4', 'RSE5'];
        $POTONG_BULU  = ['SHR1', 'SHR2', 'SHR3', 'SHR4', 'SHR5'];
        $PEACH_SKIN   = ['SUE1', 'SUE2', 'SUE3', 'SUE4'];
        $POLISHING    = ['POL1'];
        $ADM_BRS      = ['WAIT38'];
        $INSPEK_BRS   = ['INS9'];
        $NCP          = ['NCP8'];

        // WET SUEDING
        if (in_array($operation, $WET_SUEDING)) {
            $WET_SUEDING_QTY += $qty;
            $WET_SUEDING_GEROBAK += $jml;
        }

        // AIRO
        if (in_array($operation, $AIRO)) {
            $AIRO_QTY += $qty;
            $AIRO_GEROBAK += $jml;
        }

        // ANTI PILLING
        if (in_array($operation, $ANTI_PILLING)) {
            $ANTI_PILLING_QTY += $qty;
            $ANTI_PILLING_GEROBAK += $jml;
        }

        // SISIR
        if (in_array($operation, $SISIR)) {
            $SISIR_QTY += $qty;
            $SISIR_GEROBAK += $jml;

        }

        // GARUK
        if (in_array($operation, $GARUK)) {
            $GARUK_QTY += $qty;
            $GARUK_GEROBAK += $jml;

        }

        // POTONG BULU
        if (in_array($operation, $POTONG_BULU)) {
            $POTONG_BULU_QTY += $qty;
            $POTONG_BULU_GEROBAK += $jml;

        }

        // PEACH SKIN
        if (in_array($operation, $PEACH_SKIN)) {
            $PEACH_SKIN_QTY += $qty;
            $PEACH_SKIN_GEROBAK += $jml;

        }

        // POLISHING
        if (in_array($operation, $POLISHING)) {
            $POLISHING_QTY += $qty;
            $POLISHING_GEROBAK += $jml;

        }

        // ADM BRS
        if (in_array($operation, $ADM_BRS)) {
            $ADM_BRS_QTY += $qty;
            $ADM_BRS_GEROBAK += $jml;

        }

        // INSPEK BRS
        if (in_array($operation, $INSPEK_BRS)) {
            $INSPEK_BRS_QTY += $qty;
            $INSPEK_BRS_GEROBAK += $jml;

        }

        // NCP
        if (in_array($operation, $NCP)) {
            $NCP_QTY += $qty;
            $NCP_GEROBAK += $jml;

        }

    }
?>

<?php
    if ($_POST['demand']) {
        $where_demand = "AND p.PRODUCTIONDEMANDCODE = '$_POST[demand]'";
    } else {
        $where_demand = "";
    }

    if ($_POST['dept'] == 'DYE') {
        $where_entered_dye = "AND STATUS_OPERATION IN ('Entered', 'Progress')";
    } else {
        $where_entered_dye = "";
    }

    if ($_POST['dept'] == 'DYE') {
        $where_operation_dye = "WHERE NOT (OPERATIONCODE IN ('DYE1', 'DYE2', 'DYE4', 'SOA1', 'RDC1', 'LVL1', 'NEU1', 'CBL1', 'RLX1', 'FEW1', 'FIX1', 'HEW1', 'HOT1', 'SCO1', 'SCO2', 'SOF1', 'STR1') AND STATUS_OPERATION = 'Progress')";
    } else {
        $where_operation_dye = "WHERE NOT OPERATIONGROUPCODE = 'DYE'";
    }

    $q_iptip = db2_exec($conn1, "SELECT * FROM
                                    (SELECT DISTINCT
                                        PRODUCTIONORDERCODE,
                                        REPLACE(LISTAGG( '`'|| PRODUCTIONDEMANDCODE || '`', ', '), '`', '''')  AS PRODUCTIONDEMANDCODE2,
                                        LISTAGG(PRODUCTIONDEMANDCODE, ', ')  AS PRODUCTIONDEMANDCODE,
                                        STEPNUMBER,
                                        OPERATIONCODE,
                                        STATUS_OPERATION,
                                        HANGER,
                                        NO_WARNA,
                                        WARNA,
                                        SUBCODE06,
                                        OPERATIONGROUPCODE,
                                        ABSUNIQUEID_OPERATION,
                                        CREATIONDATETIME
                                    FROM
                                        (SELECT
                                            TRIM(p.PRODUCTIONORDERCODE) AS PRODUCTIONORDERCODE,
                                            TRIM(p.PRODUCTIONDEMANDCODE) AS PRODUCTIONDEMANDCODE,
                                            p.STEPNUMBER,
                                            TRIM(p.OPERATIONCODE) AS OPERATIONCODE,
                                            CASE
                                                WHEN p.PROGRESSSTATUS = 0 THEN 'Entered'
                                                WHEN p.PROGRESSSTATUS = 1 THEN 'Planned'
                                                WHEN p.PROGRESSSTATUS = 2 THEN 'Progress'
                                                WHEN p.PROGRESSSTATUS = 3 THEN 'Closed'
                                            END AS STATUS_OPERATION,
                                            TRIM(o.OPERATIONGROUPCODE) AS OPERATIONGROUPCODE,
                                            TRIM(p2.SUBCODE02) || '-' || TRIM(p2.SUBCODE03) AS HANGER,
                                            TRIM(p2.SUBCODE06) AS SUBCODE06,
                                            i.SUBCODE05 AS NO_WARNA,
                                            i.WARNA,
                                            ROW_NUMBER() OVER (PARTITION BY p.PRODUCTIONORDERCODE, p.PRODUCTIONDEMANDCODE ORDER BY p.STEPNUMBER) AS RN,
                                            o.ABSUNIQUEID AS ABSUNIQUEID_OPERATION,
                                            p2.CREATIONDATETIME
                                        FROM
                                            PRODUCTIONDEMANDSTEP p
                                        LEFT JOIN OPERATION o ON o.CODE = p.OPERATIONCODE
                                        LEFT JOIN PRODUCTIONDEMAND p2 ON p2.CODE = p.PRODUCTIONDEMANDCODE
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
                                        WHERE
                                            TRIM(p.PROGRESSSTATUS) IN ('0', '2')
                                            $where_demand
                                            AND p2.CREATIONDATETIME >= '2023-11-01'
                                            AND NOT p.PRODUCTIONORDERCODE IS NULL
                                            AND (TRIM(p2.DESTINATIONORDER) = '1' OR NOT p2.PROJECTCODE IS NULL)
                                            )
                                    LEFT JOIN ADSTORAGE a ON a.UNIQUEID = ABSUNIQUEID_OPERATION AND a.FIELDNAME = 'Gerobak'
                                    WHERE
                                        RN = 1
                                        AND NOT OPERATIONCODE = 'BAT1'
                                        AND NOT TRIM(OPERATIONGROUPCODE) IS NULL
                                        $where_entered_dye
                                        AND (a.VALUEBOOLEAN IS NULL OR a.VALUEBOOLEAN = 0)
                                    GROUP BY
                                        PRODUCTIONORDERCODE,
                                        STEPNUMBER,
                                        OPERATIONCODE,
                                        STATUS_OPERATION,
                                        HANGER,
                                        NO_WARNA,
                                        WARNA,
                                        SUBCODE06,
                                        OPERATIONGROUPCODE,
                                        ABSUNIQUEID_OPERATION,
                                        CREATIONDATETIME
                                    ORDER BY
                                        OPERATIONGROUPCODE ASC)
                                    $where_operation_dye");
    $totalGerobak_QC  = 0;
    $totalGerobak_BRS = 0;
    $totalGerobak_DYE = 0;
    $totalGerobak_FIN = 0;
    $totalGerobak_GKG = 0;
    $totalGerobak_KNT = 0;
    $totalGerobak_LAB = 0;
    $totalGerobak_PPC = 0;
    $totalGerobak_PRT = 0;
    $totalGerobak_RMP = 0;
    $totalGerobak_TAS = 0;
?>

<?php while ($row_iptip = db2_fetch_assoc($q_iptip)): ?>

<?php
    if ($dept == 'DYE') {
        $gerobak = "CASE
                                    WHEN TRIM(p.OPERATIONCODE) = 'DYE2' THEN 'Poly'
                                    WHEN TRIM(p.OPERATIONCODE) = 'DYE4' THEN 'Cotton'
                                    ELSE LISTAGG(DISTINCT FLOOR(idqd.VALUEQUANTITY), ', ')
                                END AS GEROBAK";
    } else {
        $gerobak = "LISTAGG(DISTINCT FLOOR(idqd.VALUEQUANTITY), ', ') AS GEROBAK";
    }

    $q_posisikk = db2_exec($conn1, "SELECT DISTINCT
                                                    p.STEPNUMBER AS STEPNUMBER,
                                                    CASE
                                                        WHEN TRIM(p.PRODRESERVATIONLINKGROUPCODE) IS NULL OR TRIM(p.PRODRESERVATIONLINKGROUPCODE) = '' THEN TRIM(p.OPERATIONCODE)
                                                        ELSE TRIM(p.PRODRESERVATIONLINKGROUPCODE)
                                                    END AS OPERATIONCODE,
                                                    TRIM(o.OPERATIONGROUPCODE) AS DEPT,
                                                    o.LONGDESCRIPTION,
                                                    CASE
                                                        WHEN p.PROGRESSSTATUS = 0 THEN 'Entered'
                                                        WHEN p.PROGRESSSTATUS = 1 THEN 'Planned'
                                                        WHEN p.PROGRESSSTATUS = 2 THEN 'Progress'
                                                        WHEN p.PROGRESSSTATUS = 3 THEN 'Closed'
                                                    END AS STATUS_OPERATION,
                                                    iptip.MULAI,
                                                    CASE
                                                        WHEN p.PROGRESSSTATUS = 3 THEN COALESCE(iptop.SELESAI, SUBSTRING(p.LASTUPDATEDATETIME, 1, 19) || '(Run Manual Closures)')
                                                        ELSE iptop.SELESAI
                                                    END AS SELESAI,
                                                    p.PRODUCTIONORDERCODE,
                                                    p.PRODUCTIONDEMANDCODE,
                                                    iptip.LONGDESCRIPTION AS OP1,
                                                    iptop.LONGDESCRIPTION AS OP2,
                                                    $gerobak
                                                FROM
                                                    PRODUCTIONDEMANDSTEP p
                                                LEFT JOIN OPERATION o ON o.CODE = p.OPERATIONCODE
                                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = o.ABSUNIQUEID AND a.FIELDNAME = 'Gerobak'
                                                LEFT JOIN ITXVIEW_POSISIKK_TGL_IN_PRODORDER iptip ON iptip.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE AND iptip.DEMANDSTEPSTEPNUMBER = p.STEPNUMBER
                                                LEFT JOIN ITXVIEW_POSISIKK_TGL_OUT_PRODORDER iptop ON iptop.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE AND iptop.DEMANDSTEPSTEPNUMBER = p.STEPNUMBER
                                                LEFT JOIN ITXVIEW_DETAIL_QA_DATA idqd ON idqd.PRODUCTIONDEMANDCODE = p.PRODUCTIONDEMANDCODE AND idqd.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                                                                                    AND idqd.OPERATIONCODE = CASE
                                                                                                                WHEN TRIM(p.PRODRESERVATIONLINKGROUPCODE) IS NULL OR TRIM(p.PRODRESERVATIONLINKGROUPCODE) = '' THEN TRIM(p.OPERATIONCODE)
                                                                                                                ELSE TRIM(p.PRODRESERVATIONLINKGROUPCODE)
                                                                                                            END
                                                                                    AND (idqd.VALUEINT = p.STEPNUMBER OR idqd.VALUEINT = p.GROUPSTEPNUMBER)
                                                                                    AND (idqd.CHARACTERISTICCODE = 'GRB1' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB2' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB3' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB4' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB5' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB6' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB7' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB8' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB9' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB10' OR
                                                                                        idqd.CHARACTERISTICCODE = 'AREA')
                                                                                    AND NOT (idqd.VALUEQUANTITY = 999 OR idqd.VALUEQUANTITY = 1 OR idqd.VALUEQUANTITY = 9999 OR idqd.VALUEQUANTITY = 99999 OR idqd.VALUEQUANTITY = 91)
                                                WHERE
                                                    p.PRODUCTIONORDERCODE  = '$row_iptip[PRODUCTIONORDERCODE]'
                                                    AND p.PRODUCTIONDEMANDCODE IN ($row_iptip[PRODUCTIONDEMANDCODE2])
                                                    AND p.STEPNUMBER < '$row_iptip[STEPNUMBER]'
                                                    AND NOT idqd.VALUEQUANTITY IS NULL
                                                    AND (a.VALUEBOOLEAN IS NULL OR a.VALUEBOOLEAN = 0)
                                                GROUP BY
                                                    p.PRODUCTIONORDERCODE,
                                                    p.STEPNUMBER,
                                                    p.OPERATIONCODE,
                                                    p.PRODRESERVATIONLINKGROUPCODE,
                                                    o.OPERATIONGROUPCODE,
                                                    o.LONGDESCRIPTION,
                                                    p.PROGRESSSTATUS,
                                                    iptip.MULAI,
                                                    iptop.SELESAI,
                                                    p.LASTUPDATEDATETIME,
                                                    p.PRODUCTIONORDERCODE,
                                                    p.PRODUCTIONDEMANDCODE,
                                                    iptip.LONGDESCRIPTION,
                                                    iptop.LONGDESCRIPTION
                                                ORDER BY
                                                    p.STEPNUMBER
                                                DESC
                                                LIMIT 1");

    $row_posisikk = db2_fetch_assoc($q_posisikk);

    $count_gerobak = db2_exec($conn1, "SELECT
                                                    TRIM(o.OPERATIONGROUPCODE) AS DEPT,
                                                    COUNT(DISTINCT idqd.VALUEQUANTITY) AS JML_GEROBAK
                                                FROM
                                                    PRODUCTIONDEMANDSTEP p
                                                LEFT JOIN OPERATION o ON o.CODE = p.OPERATIONCODE
                                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = o.ABSUNIQUEID AND a.FIELDNAME = 'Gerobak'
                                                LEFT JOIN ITXVIEW_POSISIKK_TGL_IN_PRODORDER iptip ON iptip.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE AND iptip.DEMANDSTEPSTEPNUMBER = p.STEPNUMBER
                                                LEFT JOIN ITXVIEW_POSISIKK_TGL_OUT_PRODORDER iptop ON iptop.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE AND iptop.DEMANDSTEPSTEPNUMBER = p.STEPNUMBER
                                                LEFT JOIN ITXVIEW_DETAIL_QA_DATA idqd ON idqd.PRODUCTIONDEMANDCODE = p.PRODUCTIONDEMANDCODE AND idqd.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                                                                                    AND idqd.OPERATIONCODE = CASE
                                                                                                                WHEN TRIM(p.PRODRESERVATIONLINKGROUPCODE) IS NULL OR TRIM(p.PRODRESERVATIONLINKGROUPCODE) = '' THEN TRIM(p.OPERATIONCODE)
                                                                                                                ELSE TRIM(p.PRODRESERVATIONLINKGROUPCODE)
                                                                                                            END
                                                                                    AND (idqd.VALUEINT = p.STEPNUMBER OR idqd.VALUEINT = p.GROUPSTEPNUMBER)
                                                                                    AND (idqd.CHARACTERISTICCODE = 'GRB1' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB2' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB3' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB4' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB5' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB6' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB7' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB8' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB9' OR
                                                                                        idqd.CHARACTERISTICCODE = 'GRB10' OR
                                                                                        idqd.CHARACTERISTICCODE = 'AREA')
                                                                                    AND NOT (idqd.VALUEQUANTITY = 999 OR idqd.VALUEQUANTITY = 1 OR idqd.VALUEQUANTITY = 9999 OR idqd.VALUEQUANTITY = 99999 OR idqd.VALUEQUANTITY = 91)
                                                WHERE
                                                    p.PRODUCTIONORDERCODE  = '$row_iptip[PRODUCTIONORDERCODE]'
                                                    AND p.PRODUCTIONDEMANDCODE IN ($row_iptip[PRODUCTIONDEMANDCODE2])
                                                    AND p.STEPNUMBER < '$row_iptip[STEPNUMBER]'
                                                    AND NOT idqd.VALUEQUANTITY IS NULL
                                                    AND (a.VALUEBOOLEAN IS NULL OR a.VALUEBOOLEAN = 0)
                                                GROUP BY
                                                    p.PRODUCTIONORDERCODE,
                                                    p.STEPNUMBER,
                                                    p.OPERATIONCODE,
                                                    p.PRODRESERVATIONLINKGROUPCODE,
                                                    o.OPERATIONGROUPCODE,
                                                    o.LONGDESCRIPTION,
                                                    p.PROGRESSSTATUS,
                                                    iptip.MULAI,
                                                    iptop.SELESAI,
                                                    p.LASTUPDATEDATETIME,
                                                    p.PRODUCTIONORDERCODE,
                                                    p.PRODUCTIONDEMANDCODE,
                                                    iptip.LONGDESCRIPTION,
                                                    iptop.LONGDESCRIPTION
                                                ORDER BY
                                                    p.STEPNUMBER
                                                DESC
                                                LIMIT 1");
    $row_count_gerobak = db2_fetch_assoc($count_gerobak);

    if ($row_iptip['OPERATIONGROUPCODE'] == 'BRS') {
        $totalGerobak_BRS += $row_count_gerobak['JML_GEROBAK'];
    }

    if ($row_iptip['OPERATIONGROUPCODE'] == 'DYE') {
        $totalGerobak_DYE += $row_count_gerobak['JML_GEROBAK'];
    }

    if ($row_iptip['OPERATIONGROUPCODE'] == 'FIN') {
        $totalGerobak_FIN += $row_count_gerobak['JML_GEROBAK'];
    }

    if ($row_iptip['OPERATIONGROUPCODE'] == 'GKG') {
        $totalGerobak_GKG += $row_count_gerobak['JML_GEROBAK'];
    }

    if ($row_iptip['OPERATIONGROUPCODE'] == 'KNT') {
        $totalGerobak_KNT += $row_count_gerobak['JML_GEROBAK'];
    }

    if ($row_iptip['OPERATIONGROUPCODE'] == 'LAB') {
        $totalGerobak_LAB += $row_count_gerobak['JML_GEROBAK'];
    }

    if ($row_iptip['OPERATIONGROUPCODE'] == 'PPC') {
        $totalGerobak_PPC += $row_count_gerobak['JML_GEROBAK'];
    }

    if ($row_iptip['OPERATIONGROUPCODE'] == 'PRT') {
        $totalGerobak_PRT += $row_count_gerobak['JML_GEROBAK'];
    }

    if ($row_iptip['OPERATIONGROUPCODE'] == 'RMP') {
        $totalGerobak_RMP += $row_count_gerobak['JML_GEROBAK'];
    }

    if ($row_iptip['OPERATIONGROUPCODE'] == 'TAS') {
        $totalGerobak_TAS += $row_count_gerobak['JML_GEROBAK'];
    }

    if ($row_iptip['OPERATIONGROUPCODE'] == 'QC') {
        $totalGerobak_QC += $row_count_gerobak['JML_GEROBAK'];
    }
?>

<?php if (! empty($row_posisikk['GEROBAK'])): ?>

<!-- ini table yang pertama disini -->

<?php
    $sql_qtyorder = db2_exec($conn1, "SELECT DISTINCT
                                                        GROUPSTEPNUMBER,
                                                        INITIALUSERPRIMARYQUANTITY AS QTY_ORDER,
                                                        INITIALUSERSECONDARYQUANTITY AS QTY_ORDER_YARD
                                                    FROM
                                                        VIEWPRODUCTIONDEMANDSTEP
                                                    WHERE
                                                        PRODUCTIONORDERCODE = '$row_iptip[PRODUCTIONORDERCODE]'
                                                        -- AND GROUPSTEPNUMBER = '$row_iptip[STEPNUMBER]'
                                                    ORDER BY
                                                        GROUPSTEPNUMBER ASC LIMIT 1");
    $dt_qtyorder = db2_fetch_assoc($sql_qtyorder);
?>

<?php
    $DEPT_CHOICE      = $row_posisikk['DEPT'];
    $OPERATION_CHOICE = $row_posisikk['OPERATIONCODE'];
    $QTY_CHOICE       = $dt_qtyorder['QTY_ORDER'];
    $GEROBAK_CHOICE   = $row_count_gerobak['JML_GEROBAK'];

    if ($DEPT_CHOICE == "BRS") {
        brs_summary($OPERATION_CHOICE, $QTY_CHOICE, $GEROBAK_CHOICE);
    }
?>

<?php else: ?>

<?php
    // FIELDNYA = tempat, kain_gerobak
    $q_ncp = mysqli_query($con_db_qc, "SELECT * FROM `tbl_ncp_qcf_now`
    WHERE nokk = '$row_iptip[PRODUCTIONORDERCODE]'
    AND nodemand = '$row_iptip[PRODUCTIONDEMANDCODE]'");

    $row_ncp = mysqli_fetch_assoc($q_ncp);
?>

<?php if ($row_ncp): ?>

<!-- ini table yang kedua disini -->

<?php

    $DEPT_CHOICE2      = $row_ncp['dept'];
    $OPERATION_CHOICE2 = $row_posisikk['OPERATIONCODE'];
    $QTY_CHOICE2       = $row_ncp['berat'];
    $GEROBAK_CHOICE2   = 0;

    if ($DEPT_CHOICE2 == "BRS") {
        brs_summary($OPERATION_CHOICE2, $QTY_CHOICE2, $GEROBAK_CHOICE2);
    }
?>

<?php endif; // if row ncp ?>

<?php endif; // if row posisi kk?>
<?php endwhile; ?>

<?php

    $TOTAL_QTY_SUMMARY = $WEB_SEUDING_QTY +
        $AIRO_QTY +
        $ANTI_PILLING_QTY +
        $SISIR_QTY +
        $GARUK_QTY +
        $POTONG_BULU_QTY +
        $PEACH_SKIN_QTY +
        $POLISHING_QTY +
        $ADM_BRS_QTY +
        $INSPEK_BRS_QTY +
        $NCP_QTY +
        $PERSIAPAN_QTY;

    $TOTAL_GEROBAK_SUMMARY = $WEB_SEUDING_GEROBAK +
        $AIRO_GEROBAK +
        $ANTI_PILLING_GEROBAK +
        $SISIR_GEROBAK +
        $GARUK_GEROBAK +
        $POTONG_BULU_GEROBAK +
        $PEACH_SKIN_GEROBAK +
        $POLISHING_QGEROBAK +
        $ADM_BRS_GEROBAK +
        $INSPEK_BRS_GEROBAK +
        $NCP_GEROBAK +
        $PERSIAPAN_GEROBAK;

?>

<html>
<body>
<table border="1" cellspacing="0" cellpadding="3">
    <tr>
        <td colspan="3" align="center" bgcolor="#FFFF00">
           <b> SISA&nbsp;<?php echo $tgl . ' ' . $bln . ' ' . $thn ?> (<?php echo $waktu ?>) </b>
        </td>
    </tr>

    <tr style="font-weight:bold; background:#f2f2f2;">
        <td align="center"><b>PROSES</b></td>
        <td align="center"><b>QTY</b></td>
        <td align="center"><b>GEROBAK</b></td>
    </tr>

    <tr>
        <td>WET SUEDING</td>
        <td align="center"><?php echo $WET_SUEDING_QTY ?: '-' ?></td>
        <td align="center"><?php echo $WET_SUEDING_GEROBAK ?: '-' ?></td>
    </tr>

    <tr>
        <td>AIRO</td>
        <td align="center"><?php echo $AIRO_QTY ?: '-' ?></td>
        <td align="center"><?php echo $AIRO_GEROBAK ?: '-' ?></td>
    </tr>

    <tr>
        <td>ANTI PILLING</td>
        <td align="center"><?php echo $ANTI_PILLING_QTY ?: '-' ?></td>
        <td align="center"><?php echo $ANTI_PILLING_GEROBAK ?: '-' ?></td>
    </tr>

    <tr>
        <td>SISIR</td>
        <td align="center"><?php echo $SISIR_QTY ?: '-' ?></td>
        <td align="center"><?php echo $SISIR_GEROBAK ?: '-' ?></td>
    </tr>

    <tr>
        <td>GARUK</td>
        <td align="center"><?php echo $GARUK_QTY ?: '-' ?></td>
        <td align="center"><?php echo $GARUK_GEROBAK ?: '-' ?></td>
    </tr>

    <tr>
        <td>POTONG BULU</td>
        <td align="center"><?php echo $POTONG_BULU_QTY ?: '-' ?></td>
        <td align="center"><?php echo $POTONG_BULU_GEROBAK ?: '-' ?></td>
    </tr>

    <tr>
        <td>PEACH SKIN</td>
        <td align="center"><?php echo $PEACH_SKIN_QTY ?: '-' ?></td>
        <td align="center"><?php echo $PEACH_SKIN_GEROBAK ?: '-' ?></td>
    </tr>

    <tr>
        <td>POLISHING</td>
        <td align="center"><?php echo $POLISHING_QTY ?: '-' ?></td>
        <td align="center"><?php echo $POLISHING_GEROBAK ?: '-' ?></td>
    </tr>

    <tr>
        <td>ADM BRS</td>
        <td align="center"><?php echo $ADM_BRS_QTY ?: '-' ?></td>
        <td align="center"><?php echo $ADM_BRS_GEROBAK ?: '-' ?></td>
    </tr>

    <tr>
        <td>INSPEK BRS</td>
        <td align="center"><?php echo $INSPEK_BRS_QTY ?: '-' ?></td>
        <td align="center"><?php echo $INSPEK_BRS_GEROBAK ?: '-' ?></td>
    </tr>

    <tr>
        <td>NCP</td>
        <td align="center"><?php echo $NCP_QTY ?: '-' ?></td>
        <td align="center"><?php echo $NCP_GEROBAK ?: '-' ?></td>
    </tr>

    <tr>
        <td>PERSIAPAN / KOSONG</td>
        <td align="center"><?php echo $PERSIAPAN_QTY ?: '-' ?></td>
        <td align="center"><?php echo $PERSIAPAN_GEROBAK ?: '-' ?></td>
    </tr>

    <tr style="font-weight:bold; background:#f9f9f9;">
        <td align="center" bgcolor="#FFFF00"><b>TOTAL</b></td>
        <td align="center" bgcolor="#FFFF00"><b><?php echo $TOTAL_QTY_SUMMARY ?></b></td>
        <td align="center" bgcolor="#FFFF00"><b><?php echo $TOTAL_GEROBAK_SUMMARY ?><b></td>
    </tr>
</table>
</body>
</html>


<?php
    $htmlContent = ob_get_clean();
    $filename    = "SummaryPencarianGerobak-BRS-" . date('Y-m-d_H-i-s') . ".xls";
    $filePath    = "Y:\\DATA PO\\Laporan pencarian gerobak\\summary\\BRS\\" . $filename;
    file_put_contents($filePath, $htmlContent);
?>


<?php
header("content-type:application/vnd-ms-excel");
header("content-disposition:attachment;filename=Summary Order PPC.xls");
header('Cache-Control: max-age=0');
?>
<style>
    .str {
        mso-number-format: \@;
    }
</style>
<table>
    <thead>
        <tr>
            <th>MKT</th>
            <th>NO MC</th>
            <th>AKJ/AKW</th>
            <th>LANGGANAN</th>
            <th>BUYER</th>
            <th>ITEM</th>
            <th>SALES ORDER</th>
            <th>JENIS KAIN</th>
            <th>WARNA</th>
            <th>NO WARNA</th>
            <th>LOT</th>
            <th>FIRST LOT</th>
            <th>PRODUCTION ORDER</th>
            <th>DEMAND</th>
            <th>DEL. INTERNAL</th>
            <th>DEL. ACTUAL</th>
            <th>LBR</th>
            <th>GRMS</th>
            <th>Bruto Per KK</th>
            <th>Bruto Sales Order Line</th>
            <th>NETTO</th>
            <th>PO GREIGE | GREIGE AWAL | GREIGE AKHIR</th>
            <th>VARIAN GREIGE</th>
            <th>ROLL</th>
            <th>QTY</th>
            <th>PROSES PRETREATMENT</th>
            <th>TGL BAGI KAN</th>
            <th>TGL PRESET</th>
            <th>CELUP GREIGE</th>
            <th>KETERANGAN</th>
            <th>LEADTIME ACTUAL</th>
            <th>TGL TERIMA ORDER</th>
            <th>TGL BUKA KK</th>
        </tr>
    </thead>
    <tbody>
        <?php
        session_start();
        require_once "koneksi.php";

        $start_date = $_GET['tgl1'] ?? '2025-01-01';
        $end_date   = $_GET['tgl2'] ?? date('Y-m-d');

        $whereDate = ($_GET['tgl1'] &&  $_GET['tgl2'])
            ? "CAST(s.REQUIREDDUEDATE  AS DATE) BETWEEN '$start_date' AND '$end_date'"
            : "CAST(s.CREATIONDATETIME AS DATE) BETWEEN '$start_date' AND '$end_date'";


        $where = "  AND p.PROGRESSSTATUS <> 6
                AND p.ITEMTYPEAFICODE = 'KFF'
                AND a1.VALUESTRING IS NULL
                AND NOT PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE IS NULL
                AND COALESCE(SUBSTR(s.CODE, 1, 3), '') IN (
                    'EXP', 'DOM', 'SAM', 'SME', 'REP',
                    'RPE', 'RFD', 'DMB', 'TBG', 'RBG',
                    'OPN', 'MNB'
                )";

        // search (opsional, OR across beberapa kolom)
        $params = [$startDate, $endDate];

        // Data query + paging
        $sqlData = "SELECT DISTINCT
                s.CREATIONUSER AS MKT,
                TRIM(p.SUBCODE01) || '-' ||
                TRIM(p.SUBCODE02) || '-' ||
                TRIM(p.SUBCODE03) || '-' ||
                TRIM(p.SUBCODE04) || '-' ||
                TRIM(p.SUBCODE05) || '-' ||
                TRIM(p.SUBCODE06) || '-' ||
                TRIM(p.SUBCODE07) || '-' ||
                TRIM(p.SUBCODE08) AS ITEM,
                s.CODE AS SALESORDER,
                CASE
                    WHEN p2.LONGDESCRIPTION IS NULL THEN s2.ITEMDESCRIPTION
                    ELSE p2.LONGDESCRIPTION
                END || ' ' ||
                COALESCE(s.INTERNALREFERENCE, COALESCE(s2.INTERNALREFERENCE, '')) AS ITEMDESCRIPTION,
                TRIM(p.SUBCODE05) AS NO_WARNA,
                p.DESCRIPTION AS LOT,
                PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE,
                p.CODE AS DEMAND,
                s.REQUIREDDUEDATE AS DEL_INTERNAL,
                s.CONFIRMEDDUEDATE AS DEL_ACTUAL,
                p.ABSUNIQUEID AS ABSUNIQUEID_DEMAND,
                s.ABSUNIQUEID AS ABSUNIQUEID_SALESORDER,
                s.ORDPRNCUSTOMERSUPPLIERCODE,
                s2.ORDERLINE,
                p.ITEMTYPEAFICODE,
                p.SUBCODE01,
                p.SUBCODE02,
                p.SUBCODE03,
                p.SUBCODE04,
                p.SUBCODE05,
                p.SUBCODE06,
                p.SUBCODE07,
                p.SUBCODE08,
                p.SUBCODE09,
                p.SUBCODE10,
                a2.VALUEBOOLEAN AS FIRSTLOT,
                a3.VALUESTRING AS AKJAKW,
                CAST(po.CREATIONDATETIME AS DATE) AS TGL_BUKA_KK
            FROM PRODUCTIONDEMAND p
            LEFT JOIN (
                SELECT DISTINCT PRODUCTIONDEMANDCODE, PRODUCTIONORDERCODE
                FROM PRODUCTIONDEMANDSTEP
            ) PRODUCTIONDEMANDSTEP ON PRODUCTIONDEMANDSTEP.PRODUCTIONDEMANDCODE = p.CODE
            LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = p.ORIGDLVSALORDLINESALORDERCODE AND s2.ORDERLINE = p.ORIGDLVSALORDERLINEORDERLINE 
            LEFT JOIN SALESORDER s ON s.CODE = p.ORIGDLVSALORDLINESALORDERCODE
            LEFT JOIN PRODUCT p2 ON p2.ITEMTYPECODE = p.ITEMTYPEAFICODE 
                                AND p2.SUBCODE01 = p.SUBCODE01 
                                AND p2.SUBCODE02 = p.SUBCODE02 
                                AND p2.SUBCODE03 = p.SUBCODE03 
                                AND p2.SUBCODE04 = p.SUBCODE04 
                                AND p2.SUBCODE05 = p.SUBCODE05 
                                AND p2.SUBCODE06 = p.SUBCODE06 
                                AND p2.SUBCODE07 = p.SUBCODE07 
                                AND p2.SUBCODE08 = p.SUBCODE08 
                                AND p2.SUBCODE09 = p.SUBCODE09 
                                AND p2.SUBCODE10 = p.SUBCODE10
            LEFT JOIN ADSTORAGE a1 ON a1.UNIQUEID = p.ABSUNIQUEID AND a1.FIELDNAME = 'OriginalPDCode'                    
            LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = s2.ABSUNIQUEID AND a2.FIELDNAME = 'FirstLot'
            LEFT JOIN ADSTORAGE a3 ON a3.UNIQUEID = s2.ABSUNIQUEID AND a3.FIELDNAME = 'KainAKJ'
            LEFT JOIN PRODUCTIONORDER po ON po.CODE = PRODUCTIONDEMANDSTEP.PRODUCTIONORDERCODE
            WHERE 
                $whereDate
                $where
            ORDER BY s.CREATIONUSER ASC";

        $stmt = db2_prepare($conn1, $sqlData);
        if (!$stmt) {
            echo "SQLSTATE: " . db2_stmt_error() . "\n";
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }

        // bind base filters
        $bind = 1;
        db2_bind_param($stmt, $bind++, "startDate", DB2_PARAM_IN);
        db2_bind_param($stmt, $bind++, "endDate", DB2_PARAM_IN);

        $ok = db2_execute($stmt);
        if (!$ok) {
            echo json_encode(['error' => db2_stmt_errormsg($stmt)]);
            exit;
        }

        // helper format tanggal (null-safe)
        function fmtDate($v)
        {
            if (!$v) return '';
            // DB2 bisa mengembalikan string timestamp, amankan di PHP:
            $ts = strtotime($v);
            return $ts ? date('Y-m-d', $ts) : $v;
        }

        function fmtNumber($val)
        {
            if ($val === null || $val === '') return '';
            $val = (float)$val;

            // cek apakah ada pecahan desimal
            if (fmod($val, 1) == 0.0) {
                // tanpa desimal → format ribuan saja
                return number_format($val, 0, '.', ',');
            } else {
                // ada desimal → biarin semua desimal tampil
                // (hapus trailing nol di belakang koma)
                $formatted = number_format($val, 5, '.', ','); // format dulu 5 digit
                return rtrim(rtrim($formatted, '0'), '.');     // bersihkan nol & titik kalau kosong
            }
        }

        // MC DYEING
        $qMCDyeing = "SELECT VALUESTRING
                                FROM ADSTORAGE
                                WHERE UNIQUEID = ? AND FIELDNAME = 'DYEMachineNoCode'
                                FETCH FIRST 1 ROW ONLY";
        $stmtMCD = db2_prepare($conn1, $qMCDyeing);
        if (!$stmtMCD) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        // variabel bind untuk UNIQUEID
        $uniqDemand = null;
        db2_bind_param($stmtMCD, 1, "uniqDemand", DB2_PARAM_IN);
        // MC DYEING

        // LANGGANAN
        $qLangganan = "SELECT 
                                    *
                                FROM 
                                    ITXVIEW_PELANGGAN ip
                                WHERE 
                                    ip.CODE = ? 
                                    AND ip.ORDPRNCUSTOMERSUPPLIERCODE = ?";
        $stmtLangganan = db2_prepare($conn1, $qLangganan);
        if (!$stmtLangganan) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        $salesOrderCode = null;
        $ordPrnCustomerSupplierCode = null;
        db2_bind_param($stmtLangganan, 1, "salesOrderCode", DB2_PARAM_IN);
        db2_bind_param($stmtLangganan, 2, "ordPrnCustomerSupplierCode", DB2_PARAM_IN);
        // LANGGANAN

        // WARNA
        $qWarna = "SELECT 
                                *
                            FROM 
                                ITXVIEWCOLOR
                            WHERE 
                                ITEMTYPECODE = ?
                                AND SUBCODE01 = ?
                                AND SUBCODE02 = ?
                                AND SUBCODE03 = ?
                                AND SUBCODE04 = ?
                                AND SUBCODE05 = ?
                                AND SUBCODE06 = ?
                                AND SUBCODE07 = ?
                                AND SUBCODE08 = ?
                                AND SUBCODE09 = ?
                                AND SUBCODE10 = ?";
        $stmtWarna = db2_prepare($conn1, $qWarna);
        if (!$stmtWarna) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        $itemtypecode = null;
        $subcode01 = null;
        $subcode02 = null;
        $subcode03 = null;
        $subcode04 = null;
        $subcode05 = null;
        $subcode06 = null;
        $subcode07 = null;
        $subcode08 = null;
        $subcode09 = null;
        $subcode10 = null;
        db2_bind_param($stmtWarna, 1, "itemtypecode", DB2_PARAM_IN);
        db2_bind_param($stmtWarna, 2, "subcode01", DB2_PARAM_IN);
        db2_bind_param($stmtWarna, 3, "subcode02", DB2_PARAM_IN);
        db2_bind_param($stmtWarna, 4, "subcode03", DB2_PARAM_IN);
        db2_bind_param($stmtWarna, 5, "subcode04", DB2_PARAM_IN);
        db2_bind_param($stmtWarna, 6, "subcode05", DB2_PARAM_IN);
        db2_bind_param($stmtWarna, 7, "subcode06", DB2_PARAM_IN);
        db2_bind_param($stmtWarna, 8, "subcode07", DB2_PARAM_IN);
        db2_bind_param($stmtWarna, 9, "subcode08", DB2_PARAM_IN);
        db2_bind_param($stmtWarna, 10, "subcode09", DB2_PARAM_IN);
        db2_bind_param($stmtWarna, 11, "subcode10", DB2_PARAM_IN);
        // WARNA

        // LEBAR
        $qLebar = "SELECT 
                                CAST(ROUND(LEBAR) AS INT) AS LEBAR
                            FROM 
                                ITXVIEWLEBAR il 
                            WHERE
                                il.SALESORDERCODE = ? 
                                AND il.ORDERLINE = ?";
        $stmtLebar = db2_prepare($conn1, $qLebar);
        if (!$stmtLebar) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        $salesOrderCode = null;
        $orderline      = null;
        db2_bind_param($stmtLebar, 1, "salesOrderCode", DB2_PARAM_IN);
        db2_bind_param($stmtLebar, 2, "orderline", DB2_PARAM_IN);
        // LEBAR

        // GRAMASI
        $qGramasi = "SELECT 
                                COALESCE(CAST(FLOOR(GRAMASI_KFF) AS VARCHAR), CAST(GRAMASI_FKF AS VARCHAR)) AS GRAMASI
                            FROM 
                                ITXVIEWGRAMASI 
                            WHERE
                                SALESORDERCODE = ?
                                AND ORDERLINE = ?";
        $stmtGramasi = db2_prepare($conn1, $qGramasi);
        if (!$stmtGramasi) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        $salesOrderCode = null;
        $orderline      = null;
        db2_bind_param($stmtGramasi, 1, "salesOrderCode", DB2_PARAM_IN);
        db2_bind_param($stmtGramasi, 2, "orderline", DB2_PARAM_IN);
        // GRAMASI

        // BRUTO PER KK
        $qBrutoPerKK = "SELECT VALUEDECIMAL
                                FROM ADSTORAGE
                                WHERE UNIQUEID = ? AND FIELDNAME = 'OriginalBruto'";
        $stmtBrutoPerKK = db2_prepare($conn1, $qBrutoPerKK);
        if (!$stmtBrutoPerKK) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        // variabel bind untuk UNIQUEID
        $uniqDemand = null;
        db2_bind_param($stmtBrutoPerKK, 1, "uniqDemand", DB2_PARAM_IN);
        // BRUTO PER KK

        // BRUTO SALESORDERLINE
        $qBrutoPerSalesOrderLine = "SELECT
                                                p.ORIGDLVSALORDLINESALORDERCODE,
                                                p.ORIGDLVSALORDERLINEORDERLINE,
                                                SUM(a.VALUEDECIMAL) AS BRUTO_SALESORDER_LINE
                                            FROM
                                                PRODUCTIONDEMAND p
                                            LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.FIELDNAME = 'OriginalBruto'
                                            WHERE 
                                                NOT a.VALUEDECIMAL IS NULL
                                                AND p.ORIGDLVSALORDLINESALORDERCODE = ?
                                                AND p.ORIGDLVSALORDERLINEORDERLINE = ?
                                            GROUP BY
                                                p.ORIGDLVSALORDLINESALORDERCODE,
                                                p.ORIGDLVSALORDERLINEORDERLINE";
        $stmtBrutoPerSalesOrderLine = db2_prepare($conn1, $qBrutoPerSalesOrderLine);
        if (!$stmtBrutoPerSalesOrderLine) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        $salesOrderCode = null;
        $orderline      = null;
        db2_bind_param($stmtBrutoPerSalesOrderLine, 1, "salesOrderCode", DB2_PARAM_IN);
        db2_bind_param($stmtBrutoPerSalesOrderLine, 2, "orderline", DB2_PARAM_IN);
        // BRUTO SALESORDERLINE

        // NETTO
        $qNettoPerSalesOrderLine = "SELECT
                                                *
                                            FROM
                                                ITXVIEW_NETTO
                                            WHERE
                                                CODE = ?
                                                AND SALESORDERLINESALESORDERCODE = ?";
        $stmtNettoPerSalesOrderLine = db2_prepare($conn1, $qNettoPerSalesOrderLine);
        if (!$stmtNettoPerSalesOrderLine) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        $prodDemand      = null;
        $salesOrderCode = null;
        db2_bind_param($stmtNettoPerSalesOrderLine, 1, "prodDemand", DB2_PARAM_IN);
        db2_bind_param($stmtNettoPerSalesOrderLine, 2, "salesOrderCode", DB2_PARAM_IN);
        // NETTO

        // ITXVIEWKK
        $qITXVIEWKK = "SELECT
                                    *
                                FROM
                                    ITXVIEWKK
                                WHERE
                                    PRODUCTIONORDERCODE = ?
                                    AND PRODUCTIONDEMANDCODE = ?";
        $stmtITXVIEWKK = db2_prepare($conn1, $qITXVIEWKK);
        if (!$stmtITXVIEWKK) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        $prodOrder  = null;
        $prodDemand = null;
        db2_bind_param($stmtITXVIEWKK, 1, "prodOrder", DB2_PARAM_IN);
        db2_bind_param($stmtITXVIEWKK, 2, "prodDemand", DB2_PARAM_IN);
        // ITXVIEWKK

        // ROLL
        $rRoll = "SELECT 
                            count(*) AS ROLL, 
                            SUM(USERPRIMARYQUANTITY) AS QTY,
                            s2.PRODUCTIONORDERCODE
                        FROM 
                            STOCKTRANSACTION s2 
                        WHERE 
                            s2.ITEMTYPECODE = 'KGF' 
                            AND s2.PRODUCTIONORDERCODE = ?
                        GROUP BY 
                            s2.PRODUCTIONORDERCODE";
        $stmtRoll = db2_prepare($conn1, $rRoll);
        if (!$stmtRoll) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        $prodOrder  = null;
        db2_bind_param($stmtRoll, 1, "prodOrder", DB2_PARAM_IN);
        // ROLL

        // PROSES PRETREATMENT
        $rPreTreatment = "SELECT 
                                    CASE 
                                        WHEN EXISTS (
                                            SELECT 1
                                            FROM ITXVIEW_POSISI_KARTU_KERJA
                                            WHERE PRODUCTIONORDERCODE = ?
                                            AND PRODUCTIONDEMANDCODE = ?
                                            AND OPERATIONCODE IN ('CBL1','PRE1','RLX1','BBL1','OVN1')
                                        ) 
                                        THEN 'YES' 
                                        ELSE 'NO' 
                                    END AS HAS_DATA
                                FROM SYSIBM.SYSDUMMY1";
        $stmtPreTreatment = db2_prepare($conn1, $rPreTreatment);
        if (!$stmtPreTreatment) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        $prodOrder  = null;
        $prodDemand = null;
        db2_bind_param($stmtPreTreatment, 1, "prodOrder", DB2_PARAM_IN);
        db2_bind_param($stmtPreTreatment, 2, "prodDemand", DB2_PARAM_IN);
        // PROSES PRETREATMENT

        // TGL BAGI KAIN
        $rTglBagiKain = "SELECT * FROM ITXVIEW_TGLBAGIKAIN WHERE PRODUCTIONORDERCODE = ?";
        $stmtTglBagiKain = db2_prepare($conn1, $rTglBagiKain);
        if (!$stmtTglBagiKain) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        $prodOrder  = null;
        db2_bind_param($stmtTglBagiKain, 1, "prodOrder", DB2_PARAM_IN);
        // TGL BAGI KAIN

        // TGL PRESET
        $rTglPreset = "SELECT
                                    MULAI
                                FROM
                                    ITXVIEW_POSISI_KARTU_KERJA
                                WHERE
                                    PRODUCTIONORDERCODE = ?
                                    AND PRODUCTIONDEMANDCODE = ?
                                    AND OPERATIONCODE IN ('PRE1')";
        $stmtTglPreset = db2_prepare($conn1, $rTglPreset);
        if (!$stmtTglPreset) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        $prodOrder  = null;
        $prodDemand = null;
        db2_bind_param($stmtTglPreset, 1, "prodOrder", DB2_PARAM_IN);
        db2_bind_param($stmtTglPreset, 2, "prodDemand", DB2_PARAM_IN);
        // TGL PRESET

        // TGL CELUP GREIGE
        $rTglCelupGreige = "SELECT
                                        MULAI
                                    FROM
                                        ITXVIEW_POSISI_KARTU_KERJA
                                    WHERE
                                        PRODUCTIONORDERCODE = ?
                                        AND PRODUCTIONDEMANDCODE = ?
                                        AND OPERATIONCODE IN ('DYE1', 'DYE2', 'DYE3', 'DYE4', 'DYE5', 'DYE6')";
        $stmtTglCelupGreige = db2_prepare($conn1, $rTglCelupGreige);
        if (!$stmtTglCelupGreige) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        $prodOrder  = null;
        $prodDemand = null;
        db2_bind_param($stmtTglCelupGreige, 1, "prodOrder", DB2_PARAM_IN);
        db2_bind_param($stmtTglCelupGreige, 2, "prodDemand", DB2_PARAM_IN);
        // TGL CELUP GREIGE

        // KETERANGAN / STATUS TERAKHIR
        $qStatusTerakhir = "SELECT
                                        COALESCE(DEPT, OPERATIONCODE) || ' - ' || LONGDESCRIPTION AS STATUS_TERAKHIR
                                    FROM
                                        ITXVIEW_POSISI_KARTU_KERJA
                                    WHERE
                                        PRODUCTIONORDERCODE = ?
                                        AND PRODUCTIONDEMANDCODE = ?
                                        AND NOT STATUS_OPERATION = 'Closed'
                                    ORDER BY
                                        STEPNUMBER DESC
                                    LIMIT 1";
        $stmtStatusTerakhir = db2_prepare($conn1, $qStatusTerakhir);
        if (!$stmtStatusTerakhir) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        $prodOrder  = null;
        $prodDemand = null;
        db2_bind_param($stmtStatusTerakhir, 1, "prodOrder", DB2_PARAM_IN);
        db2_bind_param($stmtStatusTerakhir, 2, "prodDemand", DB2_PARAM_IN);
        // KETERANGAN / STATUS TERAKHIR

        // LEADTIME ACTUAL
        $qTglPacking = "SELECT
                                    CAST(iptipc.MULAI AS DATE) AS MULAI
                                FROM
                                    ITXVIEW_POSISI_KARTU_KERJA ipkk
                                LEFT JOIN ITXVIEW_POSISIKK_TGL_IN_PRODORDER_CNP1 iptipc ON iptipc.PRODUCTIONORDERCODE = ipkk.PRODUCTIONORDERCODE 
                                WHERE
                                    ipkk.PRODUCTIONORDERCODE = ?
                                    AND ipkk.PRODUCTIONDEMANDCODE = ?
                                    AND ipkk.OPERATIONCODE IN ('CNP1')
                                LIMIT 1";
        $stmtTglPacking = db2_prepare($conn1, $qTglPacking);
        if (!$stmtTglPacking) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        $prodOrder  = null;
        $prodDemand = null;
        db2_bind_param($stmtTglPacking, 1, "prodOrder", DB2_PARAM_IN);
        db2_bind_param($stmtTglPacking, 2, "prodDemand", DB2_PARAM_IN);
        // LEADTIME ACTUAL

        // TGL TERIMA ORDER
        $qTglTerimaOrder = "SELECT
                                        VALUEDATE
                                    FROM
                                        ADSTORAGE
                                    WHERE
                                        FIELDNAME = 'ApprovalDate'
                                        AND UNIQUEID = ?";
        $stmtTglTerimaOrder = db2_prepare($conn1, $qTglTerimaOrder);
        if (!$stmtTglTerimaOrder) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        $uniqSalesorder = null;
        db2_bind_param($stmtTglTerimaOrder, 1, "uniqSalesorder", DB2_PARAM_IN);
        // TGL TERIMA ORDER

        // DELIVERY ACTUAL
        $qDelActual = "SELECT
                                    COALESCE(s2.CONFIRMEDDELIVERYDATE, s.CONFIRMEDDUEDATE) AS ACTUAL_DELIVERY
                                FROM
                                    SALESORDER s 
                                LEFT JOIN SALESORDERDELIVERY s2 ON s2.SALESORDERLINESALESORDERCODE = s.CODE AND s2.SALORDLINESALORDERCOMPANYCODE = s.COMPANYCODE AND s2.SALORDLINESALORDERCOUNTERCODE = s.COUNTERCODE 
                                WHERE
                                    s2.SALESORDERLINESALESORDERCODE = ?
                                    AND s2.SALESORDERLINEORDERLINE = ?";
        $stmtDelActual = db2_prepare($conn1, $qDelActual);
        if (!$stmtDelActual) {
            echo json_encode(['error' => db2_stmt_errormsg()]);
            exit;
        }
        $salesOrderCode  = null;
        $orderline = null;
        db2_bind_param($stmtDelActual, 1, "salesOrderCode", DB2_PARAM_IN);
        db2_bind_param($stmtDelActual, 2, "orderline", DB2_PARAM_IN);
        // DELIVERY ACTUAL

        $data = [];
        while ($row = db2_fetch_assoc($stmt)) {
            // MC DYEING
            $rowMCDyeing = null;
            $uniqDemand = $row['ABSUNIQUEID_DEMAND'] ?? null;

            if (db2_execute($stmtMCD)) {
                $rowMCDyeing = db2_fetch_assoc($stmtMCD);
            }
            // MC DYEING

            // LANGGANAN 
            $rowLangganan = null;
            $salesOrderCode = $row['SALESORDER'] ?? null;
            $ordPrnCustomerSupplierCode = $row['ORDPRNCUSTOMERSUPPLIERCODE'] ?? null;

            if (db2_execute($stmtLangganan)) {
                $rowLangganan = db2_fetch_assoc($stmtLangganan);
            }
            // LANGGANAN

            // WARNA 
            $rowWarna = null;
            $itemtypecode = $row['ITEMTYPEAFICODE'] ?? null;
            $subcode01 = $row['SUBCODE01'] ?? null;
            $subcode02 = $row['SUBCODE02'] ?? null;
            $subcode03 = $row['SUBCODE03'] ?? null;
            $subcode04 = $row['SUBCODE04'] ?? null;
            $subcode05 = $row['SUBCODE05'] ?? null;
            $subcode06 = $row['SUBCODE06'] ?? null;
            $subcode07 = $row['SUBCODE07'] ?? null;
            $subcode08 = $row['SUBCODE08'] ?? null;
            $subcode09 = $row['SUBCODE09'] ?? null;
            $subcode10 = $row['SUBCODE10'] ?? null;

            if (db2_execute($stmtWarna)) {
                $rowWarna = db2_fetch_assoc($stmtWarna);
            }
            // WARNA

            // LEBAR 
            $rowLebar = null;
            $salesOrderCode = $row['SALESORDER'] ?? null;
            $orderline = $row['ORDERLINE'] ?? null;

            if (db2_execute($stmtLebar)) {
                $rowLebar = db2_fetch_assoc($stmtLebar);
            }
            // LEBAR

            // GRAMASI
            $rowGramasi = null;
            $salesOrderCode = $row['SALESORDER'] ?? null;
            $orderline = $row['ORDERLINE'] ?? null;

            if (db2_execute($stmtGramasi)) {
                $rowGramasi = db2_fetch_assoc($stmtGramasi);
            }
            // GRAMASI

            // BRUTO PER KK
            $rowBrutoPerKK = null;
            $uniqDemand = $row['ABSUNIQUEID_DEMAND'] ?? null;

            if (db2_execute($stmtBrutoPerKK)) {
                $rowBrutoPerKK = db2_fetch_assoc($stmtBrutoPerKK);
            }
            // BRUTO PER KK

            // BRUTO SALESORDERLINE
            $rowBrutoPerSalesOrderLine = null;
            $salesOrderCode = $row['SALESORDER'] ?? null;
            $orderline = $row['ORDERLINE'] ?? null;

            if (db2_execute($stmtBrutoPerSalesOrderLine)) {
                $rowBrutoPerSalesOrderLine = db2_fetch_assoc($stmtBrutoPerSalesOrderLine);
            }
            // BRUTO SALESORDERLINE

            // NETTO
            $rowNettoPerSalesOrderLine = null;
            $prodDemand = $row['DEMAND'] ?? null;
            $salesOrderCode = $row['SALESORDER'] ?? null;

            if (db2_execute($stmtNettoPerSalesOrderLine)) {
                $rowNettoPerSalesOrderLine = db2_fetch_assoc($stmtNettoPerSalesOrderLine);
            }
            // NETTO

            // ITXVIEWKK
            $rowITXVIEWKK = null;
            $prodOrder  = $row['PRODUCTIONORDERCODE'] ?? null;
            $prodDemand = $row['DEMAND'] ?? null;

            if (db2_execute($stmtITXVIEWKK)) {
                $rowITXVIEWKK = db2_fetch_assoc($stmtITXVIEWKK);
            }
            // ITXVIEWKK

            // ROLL
            $rowRoll = null;
            $prodOrder  = $row['PRODUCTIONORDERCODE'] ?? null;

            if (db2_execute($stmtRoll)) {
                $rowRoll = db2_fetch_assoc($stmtRoll);
            }
            // ROLL

            // PROSES PRETREATMENT
            $rowPreTreatment = null;
            $prodOrder  = $row['PRODUCTIONORDERCODE'] ?? null;
            $prodDemand = $row['DEMAND'] ?? null;

            if (db2_execute($stmtPreTreatment)) {
                $rowPreTreatment = db2_fetch_assoc($stmtPreTreatment);
            }
            // PROSES PRETREATMENT

            // TGL BAGI KAIN
            $rowTglBagiKain = null;
            $prodOrder  = $row['PRODUCTIONORDERCODE'] ?? null;

            if ($prodOrder) {
                if (db2_execute($stmtTglBagiKain)) {
                    $rowTglBagiKain = db2_fetch_assoc($stmtTglBagiKain);
                }
            }
            // TGL BAGI KAIN

            // TGL PRESET
            $rowTglPreset = null;
            $prodOrder  = $row['PRODUCTIONORDERCODE'] ?? null;
            $prodDemand = $row['DEMAND'] ?? null;

            if ($prodOrder && $prodDemand) {
                if (db2_execute($stmtTglPreset)) {
                    $rowTglPreset = db2_fetch_assoc($stmtTglPreset);
                }
            }
            // TGL PRESET

            // TGL CELUP GREIGE
            $rowTglCelupGreige = null;
            $prodOrder  = $row['PRODUCTIONORDERCODE'] ?? null;
            $prodDemand = $row['DEMAND'] ?? null;

            if ($prodOrder && $prodDemand) {
                if (db2_execute($stmtTglCelupGreige)) {
                    $rowTglCelupGreige = db2_fetch_assoc($stmtTglCelupGreige);
                }
            }
            // TGL CELUP GREIGE

            // KETERANGAN / STATUS TERAKHIR
            $rowStatusTerakhir = null;
            $prodOrder  = $row['PRODUCTIONORDERCODE'] ?? null;
            $prodDemand = $row['DEMAND'] ?? null;

            if ($prodOrder && $prodDemand) {
                if (db2_execute($stmtStatusTerakhir)) {
                    $rowStatusTerakhir = db2_fetch_assoc($stmtStatusTerakhir);
                }
            }
            // KETERANGAN / STATUS TERAKHIR

            // LEADTIME ACTUAL
            $rowTglPacking = null;
            $prodOrder  = $row['PRODUCTIONORDERCODE'] ?? null;
            $prodDemand = $row['DEMAND'] ?? null;

            if ($prodOrder && $prodDemand) {
                if (db2_execute($stmtTglPacking)) {
                    $rowTglPacking = db2_fetch_assoc($stmtTglPacking);
                    // Hitung Lead Time Actual (selisih hari antara Tgl Bagi Kain dan Tgl Packing Aktual)
                    $leadTimeActual = '';
                    $tglBagiKain = $rowTglBagiKain['TRANSACTIONDATE'] ?? null;
                    $tglPacking = $rowTglPacking['MULAI'] ?? null;
                    if ($tglBagiKain && $tglPacking) {
                        $date1 = new DateTime($tglBagiKain);
                        $date2 = new DateTime($tglPacking);
                        $interval = $date1->diff($date2);
                        $leadTimeActual = $interval->days;
                        // Jika ingin tahu arah (positif/negatif), bisa pakai $interval->invert
                        if ($interval->invert) {
                            $leadTimeActual = -$leadTimeActual;
                        }
                    }
                }
            }
            // LEADTIME ACTUAL

            // TGL TERIMA ORDER
            $rowTglTerimaOrder = null;
            $uniqSalesorder  = $row['ABSUNIQUEID_SALESORDER'] ?? null;

            if ($uniqSalesorder) {
                if (db2_execute($stmtTglTerimaOrder)) {
                    $rowTglTerimaOrder = db2_fetch_assoc($stmtTglTerimaOrder);
                }
            }
            // TGL TERIMA ORDER

            // DELIVERY ACTUAL
            $rowDelActual = null;
            $salesOrderCode = $row['SALESORDER'] ?? null;
            $orderline = $row['ORDERLINE'] ?? null;

            if ($salesOrderCode && $orderline) {
                if (db2_execute($stmtDelActual)) {
                    $rowDelActual = db2_fetch_assoc($stmtDelActual);
                }
            }
            // DELIVERY ACTUAL

            // AKJ / AKW / Non-AKJ
            switch ($row['AKJAKW'] ?? '') {
                case 0:
                    $akj_akw = 'Non-AKJ';
                    break;
                case 1:
                    $akj_akw = 'AKJ';
                    break;
                case 2:
                    $akj_akw = 'AKW';
                    break;
                default:
                    $akj_akw = '';
            };
            // AKJ / AKW / Non-AKJ
        ?>
            <tr>
                <td><?= $row['MKT'] ?? '' ?></td>
                <td><?= $rowMCDyeing['VALUESTRING'] ?? '' ?></td>
                <td><?= $akj_akw ?></td>
                <td><?= $rowLangganan['LANGGANAN'] ?? '' ?></td>
                <td><?= $rowLangganan['BUYER'] ?? '' ?></td>
                <td><?= $row['ITEM'] ?? '' ?></td>
                <td><?= $row['SALESORDER'] ?? '' ?></td>
                <td><?= $row['ITEMDESCRIPTION'] ?? '' ?></td>
                <td><?= $rowWarna['WARNA'] ?? '' ?></td>
                <td><?= $row['NO_WARNA'] ?? '' ?></td>
                <td>`<?= $row['LOT'] ?? '' ?></td>
                <td><?= $row['FIRSTLOT'] ? 'First Lot' : '' ?></td>
                <td><?= $row['PRODUCTIONORDERCODE'] ?? '' ?></td>
                <td><?= $row['DEMAND'] ?? '' ?></td>
                <td><?= fmtDate($row['DEL_INTERNAL'] ?? null) ?></td>
                <td><?= fmtDate($rowDelActual['ACTUAL_DELIVERY'] ?? null) ?></td>
                <td><?= $rowLebar['LEBAR'] ?? '0' ?></td>
                <td><?= $rowGramasi['GRAMASI'] ?? '' ?></td>
                <td><?= fmtNumber($rowBrutoPerKK['VALUEDECIMAL'] ?? null) ?></td>
                <td><?= fmtNumber($rowBrutoPerSalesOrderLine['BRUTO_SALESORDER_LINE'] ?? null) ?></td>
                <td><?= fmtNumber(isset($rowNettoPerSalesOrderLine['USERPRIMARYQUANTITY']) ? round($rowNettoPerSalesOrderLine['USERPRIMARYQUANTITY'], 2) : null) ?></td>
                <td>Klik Disini</td>
                <td><?= $rowITXVIEWKK['RESERVATION_SUBCODE04'] ?? '' ?></td>
                <td><?= $rowRoll['ROLL'] ?? '0' ?></td>
                <td><?= fmtNumber($rowRoll['QTY'] ?? null) ?></td>
                <td><?= $rowPreTreatment['HAS_DATA'] ?? '' ?></td>
                <td><?= $rowTglBagiKain['TRANSACTIONDATE'] ?? '' ?></td>
                <td><?= fmtDate($rowTglPreset['MULAI'] ?? null) ?></td>
                <td><?= fmtDate($rowTglCelupGreige['MULAI'] ?? null) ?></td>
                <td><?= $rowStatusTerakhir['STATUS_TERAKHIR'] ?? '' ?></td>
                <td><?= $leadTimeActual ?? '' ?></td>
                <td><?= fmtDate($rowTglTerimaOrder['VALUEDATE'] ?? null) ?></td>
                <td><?= fmtDate($row['TGL_BUKA_KK'] ?? null) ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
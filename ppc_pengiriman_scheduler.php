<?php
    require_once 'koneksi.php';


    //ini akan dijalankan ketika sudah selesai insert data yang tidak dapat mengakses h-1 lagi
    // $tanggal_kemarin = date('Y-m-d');
    // $tanggal_kemarin = date('Y-m-d', strtotime('-1 day'));

    // if ($tanggal_kemarin) {
    //     $where_date = "i.GOODSISSUEDATE BETWEEN '$tanggal_kemarin' AND '$tanggal_kemarin'";
    // } else {
    //     $where_date = "";
    // }

    $start = new DateTime('2025-01-10');
    $end = new DateTime('2025-01-11');

    $dept = 'PPC';

    while ($start < $end) {
        $tanggal_kemarin = $start->format('Y-m-d');

        $where_date = "i.GOODSISSUEDATE BETWEEN '$tanggal_kemarin' AND '$tanggal_kemarin'";

        $codeExport = "TRIM(i.DEFINITIVECOUNTERCODE) = 'CESDEF' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'CESPROV' OR
                        TRIM(i.DEFINITIVECOUNTERCODE) = 'DREDEF' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'DREPROV' OR 
                        TRIM(i.DEFINITIVECOUNTERCODE) = 'DSEDEF' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'EXDDEF' OR
                        TRIM(i.DEFINITIVECOUNTERCODE) = 'EXDPROV' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'EXPDEF' OR
                        TRIM(i.DEFINITIVECOUNTERCODE) = 'EXPPROV' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'GSEDEF' OR 
        TRIM(i.DEFINITIVECOUNTERCODE) = 'GSEPROV' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'PSEPROV'";

        $sqlDB2 = "SELECT DISTINCT
                i.PROVISIONALCODE,
                TRIM(i.PRICEUNITOFMEASURECODE) AS PRICEUNITOFMEASURECODE,
                i.DEFINITIVECOUNTERCODE,
                i.DEFINITIVEDOCUMENTDATE,
                i.ORDERPARTNERBRANDCODE,
                CASE
                    WHEN $codeExport THEN '' ELSE i.PO_NUMBER
                END AS PO_NUMBER,
                i.PROJECTCODE,
                DAY(i.GOODSISSUEDATE) ||'-'|| MONTHNAME(i.GOODSISSUEDATE) ||'-'|| YEAR(i.GOODSISSUEDATE) AS GOODSISSUEDATE,
                i.ORDPRNCUSTOMERSUPPLIERCODE,
                i.PAYMENTMETHODCODE,   
                i.ITEMTYPEAFICODE,
                CASE
                    WHEN $codeExport THEN '' ELSE i.DLVSALORDERLINESALESORDERCODE
                END AS DLVSALORDERLINESALESORDERCODE,
                CASE
                    WHEN $codeExport THEN 0 ELSE i.DLVSALESORDERLINEORDERLINE
                END AS DLVSALESORDERLINEORDERLINE,
                CASE
                    WHEN $codeExport THEN '' ELSE 
                        TRIM(i.SUBCODE01) || '-' || TRIM(i.SUBCODE02) || '-' || TRIM(i.SUBCODE03) || '-' || TRIM(i.SUBCODE04) || '-' ||
                        TRIM(i.SUBCODE05) || '-' || TRIM(i.SUBCODE06) || '-' || TRIM(i.SUBCODE07) || '-' || TRIM(i.SUBCODE08)
                END AS ITEMDESCRIPTION,
                CASE
                    WHEN $codeExport THEN '' ELSE iasp.LOTCODE
                END AS LOTCODE,
                CASE
                    WHEN $codeExport THEN '' ELSE i2.WARNA
                END AS WARNA,
                i.LEGALNAME1,
                CASE
                    WHEN $codeExport THEN 'EXPORT' ELSE i.CODE
                END AS CODE
            FROM 
                ITXVIEW_SURATJALAN_PPC_FOR_POSELESAI i
            LEFT JOIN ITXVIEW_ALLOCATION_SURATJALAN_PPC iasp ON iasp.CODE = i.CODE
            LEFT JOIN ITXVIEWCOLOR i2 ON i2.ITEMTYPECODE =  i.ITEMTYPEAFICODE
                                    AND i2.SUBCODE01 = i.SUBCODE01 AND i2.SUBCODE02 = i.SUBCODE02
                                    AND i2.SUBCODE03 = i.SUBCODE03 AND i2.SUBCODE04 = i.SUBCODE04
                                    AND i2.SUBCODE05 = i.SUBCODE05 AND i2.SUBCODE06 = i.SUBCODE06
                                    AND i2.SUBCODE07 = i.SUBCODE07 AND i2.SUBCODE08 = i.SUBCODE08
                                    AND i2.SUBCODE09 = i.SUBCODE09 AND i2.SUBCODE10 = i.SUBCODE10
            WHERE 
            $where_date 
                AND NOT (SUBSTR(i.DLVSALORDERLINESALESORDERCODE, 1,3) = 'CAP' AND (i.ITEMTYPEAFICODE = 'KFF' OR i.ITEMTYPEAFICODE = 'KGF'))
                AND i.DOCUMENTTYPETYPE = 05 
                AND NOT i.CODE IS NULL 
                AND i.PROGRESSSTATUS_SALDOC = 2
                AND iasp.PROGRESSSTATUS = 2 -- STATUS ALLOCATIONNYA 'CLOSED' = SHIPPED
            GROUP BY
                i.PROVISIONALCODE,
                i.PRICEUNITOFMEASURECODE,
                i.DEFINITIVEDOCUMENTDATE,
                i.ORDERPARTNERBRANDCODE,
                i.PO_NUMBER,
                i.PROJECTCODE,
                i.GOODSISSUEDATE,
                i.ORDPRNCUSTOMERSUPPLIERCODE,
                i.PAYMENTMETHODCODE,
                i.PO_NUMBER,    
                i.ITEMTYPEAFICODE,
                i.DLVSALORDERLINESALESORDERCODE,
                i.DLVSALESORDERLINEORDERLINE,
                i.ITEMDESCRIPTION,
                iasp.LOTCODE,
                i.DEFINITIVECOUNTERCODE,
                i2.WARNA,
                i.LEGALNAME1,
                i.CODE,
                i.SUBCODE01,
                i.SUBCODE02,
                i.SUBCODE03,
                i.SUBCODE04,
                i.SUBCODE05,
                i.SUBCODE06,
                i.SUBCODE07,
                i.SUBCODE08,
                i.SUBCODE09,
                i.SUBCODE10
            ORDER BY 
        i.PROVISIONALCODE ASC";

        // echo $sqlDB2;
        $stmt = db2_exec($conn1, $sqlDB2);
        // $rowdb2 = db2_fetch_assoc($stmt);
        // echo $rowdb2;
        $total_qty_kg = 0;

        while ($rowdb2 = db2_fetch_assoc($stmt)) {
            $q_ket_foc = db2_exec($conn1, "SELECT 
                    COUNT(QUALITYREASONCODE) AS ROLL,
                    SUM(FOC_KG) AS KG,
                    SUM(FOC_YARDMETER) AS YARD_MTR,
                    KET_YARDMETER
                FROM
                    ITXVIEW_SURATJALAN_EXIM2A
                WHERE 
                    QUALITYREASONCODE = 'FOC'
                    AND PROVISIONALCODE = '$rowdb2[PROVISIONALCODE]'
                GROUP BY 
                    KET_YARDMETER");

            $d_ket_foc = db2_fetch_assoc($q_ket_foc);   

            if ($d_ket_foc['ROLL'] > 0 && $d_ket_foc['KG'] > 0 && $d_ket_foc['YARD_MTR'] > 0) {
                if ($rowdb2['CODE'] == 'EXPORT') {
                    $q_roll = db2_exec($conn1, "
                        SELECT
                            COUNT(ise.COUNTROLL) AS ROLL,
                            SUM(ise.QTY_KG) AS QTY_SJ_KG,
                            SUM(ise.QTY_YARDMETER) AS QTY_SJ_YARD
                        FROM ITXVIEW_SURATJALAN_EXIM2A ise
                        WHERE ise.PROVISIONALCODE = '{$rowdb2['PROVISIONALCODE']}'
                        GROUP BY ise.ADDRESSEE, ise.BRAND_NM
                    ");

                } else {
                    $q_roll = db2_exec($conn1, "
                        SELECT
                            COUNT(ITEMTYPECODE) AS ROLL,
                            SUM(USERPRIMARYQUANTITY) AS QTY_SJ_KG,
                            SUM(USERSECONDARYQUANTITY) AS QTY_SJ_YARD
                        FROM STOCKTRANSACTION
                        WHERE
                            TEMPLATECODE = 'S02'
                            AND LOGICALWAREHOUSECODE = 'M031'
                            AND ORDERCODE = '{$rowdb2['PROVISIONALCODE']}'
                            AND DERIVATIONCODE = '{$rowdb2['CODE']}'
                    ");
                }
            } else {
                if ($rowdb2['CODE'] == 'EXPORT') {
                    $q_roll = db2_exec($conn1, "
                        SELECT
                            ise.ITEMTYPEAFICODE,
                            COUNT(ise.COUNTROLL) AS ROLL,
                            SUM(ise.QTY_KG) AS QTY_SJ_KG,
                            SUM(ise.QTY_YARDMETER) AS QTY_SJ_YARD,
                            inpe.PROJECT,
                            ise.ADDRESSEE,
                            ise.BRAND_NM
                        FROM ITXVIEW_SURATJALAN_EXIM2A ise 
                        LEFT JOIN ITXVIEW_NO_PROJECTS_EXIM inpe 
                            ON inpe.PROVISIONALCODE = ise.PROVISIONALCODE 
                        WHERE 
                            ise.PROVISIONALCODE = '{$rowdb2['PROVISIONALCODE']}'
                            AND ise.ITEMTYPEAFICODE = '{$rowdb2['ITEMTYPEAFICODE']}'
                        GROUP BY 
                            ise.ITEMTYPEAFICODE,
                            inpe.PROJECT,
                            ise.ADDRESSEE,
                            ise.BRAND_NM
                    ");

                } else {
                    $q_roll = db2_exec($conn1, "
                        SELECT
                            COUNT(CODE) AS ROLL,
                            SUM(BASEPRIMARYQUANTITY) AS QTY_SJ_KG,
                            SUM(BASESECONDARYQUANTITY) AS QTY_SJ_YARD,
                            LOTCODE
                        FROM ITXVIEWALLOCATION0 
                        WHERE 
                            CODE = '{$rowdb2['CODE']}' 
                            AND LOTCODE = '{$rowdb2['LOTCODE']}'
                        GROUP BY LOTCODE
                    ");
                }
            }

            $d_roll = db2_fetch_assoc($q_roll);

            $qty_kg = number_format($d_roll['QTY_SJ_KG'] ?? 0, 2);

            // cek type
            $raw_qty_kg = $d_roll['QTY_SJ_KG'] ?? 0;
            if (strtoupper($rowdb2['ITEMTYPEAFICODE']) !== 'GYR') {
                $total_qty_kg += $raw_qty_kg;
            }
        }

        // Cek apakah data dengan tanggal tersebut sudah ada
        $checkQuery = "SELECT COUNT(*) AS jumlah FROM dbo.tbl_summary WHERE tanggal = ?";
        $params = [$tanggal_kemarin];
        $checkStmt = sqlsrv_query($con_db_ppc, $checkQuery, $params);

        // Pastikan query berhasil
        if ($checkStmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Ambil hasil jumlah data
        $checkResult = sqlsrv_fetch_array($checkStmt, SQLSRV_FETCH_ASSOC);
        $jumlah = $checkResult['jumlah'] ?? 0;
        // echo $jumlah;

        // Jika belum ada, lakukan insert
        if ($jumlah == 0) {
            $insertQuery = "INSERT INTO dbo.tbl_summary (tanggal, qty) VALUES (?, ?)";
            $insertParams = [$tanggal_kemarin, $total_qty_kg];
            $insertStmt = sqlsrv_query($con_db_ppc, $insertQuery, $insertParams);

            if ($insertStmt === false) {
                die(print_r(sqlsrv_errors(), true));
            } else {
                echo "Data berhasil disimpan. ";
            }
        } else {
            echo "Data untuk tanggal $tanggal_kemarin sudah ada. Tidak disimpan ulang. " ; 
        }

    echo "Proses tanggal: $tanggal_kemarin<br>";

        // Increment ke tanggal berikutnya
        $start->modify('+1 day');
    }

?>

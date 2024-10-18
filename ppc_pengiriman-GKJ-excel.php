<?php
header("content-type:application/vnd-ms-excel");
header("content-disposition:attachment;filename=Laporan Pengiriman GKJ.xls");
header('Cache-Control: max-age=0');
?>
<table border='1'>
    <thead>
        <?php
        $dateformat = date_create($_GET['tgl1']);
        ?>
        <tr align="center">
            <th rowspan="3"> <img src="img\logo.png" alt="logo" style="max-width: 2.5cm; height: auto;"></th>
            <th style='border-bottom: 0' colspan="11">LAPORAN HARIAN PENGIRIMAN KAIN JADI</th>
        </tr>
        <tr>
            <th style='border-top: 0 ;border-bottom: 0' colspan="11">
                <?php echo "FW-19-GKJ-03/05"; ?>
            </th>
        </tr>
        <tr>
            <th style='border-top:0' colspan="11">Tanggal: <?= date_format($dateformat, "d F Y"); ?></th>
        </tr>
        <tr>
            <th style="border:0;" align="left">Departemen:</th>
            <th style='border:0' colspan="2" align="left"><?php echo 'Gudang Kain Jadi'; ?></th>
        </tr>
        <tr>
            <th>Langganan</th>
            <th>No PO</th>
            <th>No Order</th>
            <th>Jenis Kain</th>
            <th>No Warna</th>
            <th>Warna</th>
            <th>No Lot</th>
            <th>Roll</th>
            <th>Berat</th>
            <th>Lokasi</th>
            <th>No SJ</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php
        ini_set("error_reporting", 1);
        session_start();
        require_once "koneksi.php";
        $tgl1 = $_GET['tgl1'];
        // $tgl1 = '2024-10-07';
        // $tgl1 = '2024-10-09';
        $no_order = $_GET['no_order'];
        // $no_order = 'EXP2400511';
        
        if ($tgl1) {
            $where_date = "i.GOODSISSUEDATE = '$tgl1'";
        } else {
            $where_date = "";
        }
        if ($no_order) {
            $where_no_order = "i.DLVSALORDERLINESALESORDERCODE = '$no_order'";
        } else {
            $where_no_order = "";
        }
        $sqlDB2 = "SELECT
                    DISTINCT 
                    LISTAGG(DISTINCT TRIM(i.SUBCODE05), ', ') AS NO_WARNA,
                    i.PROVISIONALCODE,
                    TRIM(i.PRICEUNITOFMEASURECODE) AS PRICEUNITOFMEASURECODE,
                    i.DEFINITIVECOUNTERCODE,
                    i.DEFINITIVEDOCUMENTDATE,
                    i.ORDERPARTNERBRANDCODE,
                    i.PO_NUMBER AS PO_NUMBER,
                    i.ITEMDESCRIPTION AS JENIS_KAIN,
                    LISTAGG(DISTINCT TRIM(iasp.WAREHOUSELOCATIONCODE), ', ') AS LOKASI,
                    DAY(i.GOODSISSUEDATE) || '-' || MONTHNAME(i.GOODSISSUEDATE) || '-' || YEAR(i.GOODSISSUEDATE) AS GOODSISSUEDATE,
                    i.ORDPRNCUSTOMERSUPPLIERCODE,
                    CASE 
                        WHEN i.PAYMENTMETHODCODE <> 'FOC' THEN ''
                        ELSE i.PAYMENTMETHODCODE
                    END AS PAYMENTMETHODCODE,
                    i.ITEMTYPEAFICODE,
                    i.DLVSALORDERLINESALESORDERCODE AS DLVSALORDERLINESALESORDERCODE,
                    i.DLVSALESORDERLINEORDERLINE AS DLVSALESORDERLINEORDERLINE,
                    LISTAGG(DISTINCT TRIM(iasp.LOTCODE), ', ' ) AS LOTCODE,
                    LISTAGG(DISTINCT ''''|| TRIM(iasp.LOTCODE) ||'''', ', ' ) AS LOTCODE2,
                    i2.WARNA AS WARNA,
                    i.LEGALNAME1,
                    i.CODE AS CODE
                FROM
                    ITXVIEW_SURATJALAN_PPC_FOR_POSELESAI i
                LEFT JOIN ITXVIEW_ALLOCATION_SURATJALAN_PPC iasp ON
                    iasp.CODE = i.CODE
                LEFT JOIN ITXVIEWCOLOR i2 ON
                    i2.ITEMTYPECODE = i.ITEMTYPEAFICODE
                    AND i2.SUBCODE01 = i.SUBCODE01
                    AND i2.SUBCODE02 = i.SUBCODE02
                    AND i2.SUBCODE03 = i.SUBCODE03
                    AND i2.SUBCODE04 = i.SUBCODE04
                    AND i2.SUBCODE05 = i.SUBCODE05
                    AND i2.SUBCODE06 = i.SUBCODE06
                    AND i2.SUBCODE07 = i.SUBCODE07
                    AND i2.SUBCODE08 = i.SUBCODE08
                    AND i2.SUBCODE09 = i.SUBCODE09
                    AND i2.SUBCODE10 = i.SUBCODE10
                WHERE
                    $where_no_order $where_date 
                    -- i.PROVISIONALCODE = 'POD2407534'
                    AND NOT (SUBSTR(i.DLVSALORDERLINESALESORDERCODE, 1,3) = 'CAP' AND (i.ITEMTYPEAFICODE = 'KFF' OR i.ITEMTYPEAFICODE = 'KGF'))
                    AND i.DOCUMENTTYPETYPE = 05 
                    AND NOT i.CODE IS NULL 
                    AND i.PROGRESSSTATUS_SALDOC = 2
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
                    i.DEFINITIVECOUNTERCODE,
                    i2.WARNA,
                    i.LEGALNAME1,
                    i.CODE
                ORDER BY
                    i.PROVISIONALCODE ASC";
        $stmt = db2_exec($conn1, $sqlDB2);
        $no = 1;
        while ($rowdb2 = db2_fetch_assoc($stmt)) {
            ?>
        <?php
            $q_ket_foc = db2_exec($conn1, "SELECT 
                                                COUNT(QUALITYREASONCODE) AS ROLL,
                                                SUM(FOC_KG) AS KG,
                                                SUM(FOC_YARDMETER) AS YARD_MTR,
                                                KET_YARDMETER,
                                                ALLOCATIONCODE
                                            FROM
                                                ITXVIEW_SURATJALAN_EXIM2A
                                            WHERE 
                                                QUALITYREASONCODE = 'FOC'
                                                AND PROVISIONALCODE = '$rowdb2[PROVISIONALCODE]'
                                                AND ALLOCATIONCODE = '$rowdb2[CODE]'
                                            GROUP BY 
                                                KET_YARDMETER,
                                                ALLOCATIONCODE");
            $d_ket_foc = db2_fetch_assoc($q_ket_foc);
            ?>
        <?php if ($d_ket_foc['ROLL'] > 0 and $d_ket_foc['KG'] > 0 and $d_ket_foc['YARD_MTR'] > 0): ?>
        <tr>
            <!-- Untuk Pelanggan -->
            <td>
                <?php
                        $q_pelanggan = db2_exec($conn1, "SELECT * FROM ITXVIEW_PELANGGAN WHERE ORDPRNCUSTOMERSUPPLIERCODE = '$rowdb2[ORDPRNCUSTOMERSUPPLIERCODE]' 
                                                                                                                                                AND CODE = '$rowdb2[DLVSALORDERLINESALESORDERCODE]'");
                        $r_pelanggan = db2_fetch_assoc($q_pelanggan);
                        if ($rowdb2['CODE'] == 'EXPORT') {
                            echo $d_roll['ADDRESSEE'] . ' - ' . $d_roll['BRAND_NM'];
                        } else {
                            echo $r_pelanggan['LANGGANAN'];

                        }
                        ?>
            </td>
            <!-- End Pelanggan -->

            <!-- Untuk PO Number -->
            <td>`
                <?php echo $rowdb2['PO_NUMBER']; ?>
            </td>
            <!-- End PO Number -->

            <!-- No Order -->
            <td>
                <?php
                        if ($rowdb2['CODE'] == 'EXPORT') {
                            echo $d_roll['PROJECT'];
                        } else {
                            echo $rowdb2['DLVSALORDERLINESALESORDERCODE'];
                        }
                        ?>
            </td>
            <!-- End No Order -->

            <td><?= $rowdb2['JENIS_KAIN'] ?></td>
            <td><?= $rowdb2['NO_WARNA'] ?></td>
            <td><?= $rowdb2['WARNA']; ?></td>
            <td>`<?= $rowdb2['LOTCODE']; ?></td>

            <!-- Untuk Roll -->
            <td>
                <?php echo $d_ket_foc['ROLL']; ?>
            </td>
            <!-- End Untuk Roll -->

            <td>
                <?= number_format($d_ket_foc['KG'], 2); ?>
            </td>
            <td><?= $rowdb2['LOKASI'] ?></td>
            <td>
                <?= $rowdb2['PROVISIONALCODE']; ?>
            </td>
            <td>FOC</td>
        </tr>
        <tr>
            <td><?php
                    $q_pelanggan = db2_exec($conn1, "SELECT * FROM ITXVIEW_PELANGGAN WHERE ORDPRNCUSTOMERSUPPLIERCODE = '$rowdb2[ORDPRNCUSTOMERSUPPLIERCODE]' 
                                                                                                                                                AND CODE = '$rowdb2[DLVSALORDERLINESALESORDERCODE]'");
                    $r_pelanggan = db2_fetch_assoc($q_pelanggan);
                    if ($rowdb2['CODE'] == 'EXPORT') {
                        echo $d_roll['ADDRESSEE'] . ' - ' . $d_roll['BRAND_NM'];
                    } else {
                        echo $r_pelanggan['LANGGANAN'];

                    }
                    ?>
            </td>
            <td>`<?= $rowdb2['PO_NUMBER']; ?></td>
            <td>
                <?php
                        if ($rowdb2['CODE'] == 'EXPORT') {
                            echo $d_roll['PROJECT'];
                        } else {
                            echo $rowdb2['DLVSALORDERLINESALESORDERCODE'];
                        }
                        ?>
            </td>
            <td><?= $rowdb2['JENIS_KAIN'] ?></td>
            <td>
                <?= $rowdb2['NO_WARNA'] ?>
            </td>
            <td><?= $rowdb2['WARNA']; ?></td>
            <td>
                `<?= $rowdb2['LOTCODE']; ?>
            </td>
            <td><?php
                    if (in_array($rowdb2['DEFINITIVECOUNTERCODE'], array('CESDEF', 'DREDEF', 'DSEDEF', 'EXDPROV', 'EXPPROV', 'GSEPROV', 'CESPROV', 'DREPROV', 'EXDDEF', 'EXPDEF', 'GSEDEF', 'PSEPROV'))) {
                        $q_roll = db2_exec($conn1, "SELECT
                                                                                                                    COUNT(ise.COUNTROLL) AS ROLL,
                                                                                                                    SUM(ise.QTY_KG) AS QTY_SJ_KG,
                                                                                                                    SUM(ise.QTY_YARDMETER) AS QTY_SJ_YARD,
                                                                                                                    inpe.PROJECT,
                                                                                                                    ise.ADDRESSEE,
                                                                                                                    ise.BRAND_NM,
                                                                                                                    ise.ALLOCATIONCODE
                                                                                                                FROM
                                                                                                                    ITXVIEW_SURATJALAN_EXIM2A ise 
                                                                                                                LEFT JOIN ITXVIEW_NO_PROJECTS_EXIM inpe ON inpe.PROVISIONALCODE = ise.PROVISIONALCODE 
                                                                                                                WHERE 
                                                                                                                    ise.PROVISIONALCODE = '$rowdb2[PROVISIONALCODE]'
                                                                                                                    AND ise.ALLOCATIONCODE = '$rowdb2[CODE]'
                                                                                                                    -- AND (ise.QUALITYREASONCODE <> 'FOC' OR ise.QUALITYREASONCODE IS NULL)
                                                                                                                GROUP BY 
                                                                                                                    inpe.PROJECT,ise.ADDRESSEE,ise.BRAND_NM,ise.ALLOCATIONCODE
                                                                                                                    ");
                        $d_roll = db2_fetch_assoc($q_roll);
                        $q_rollfoc = db2_exec($conn1, "SELECT
                                                                                                                    COUNT(ise.COUNTROLL) AS ROLL,
                                                                                                                    SUM(ise.QTY_KG) AS QTY_SJ_KG,
                                                                                                                    SUM(ise.QTY_YARDMETER) AS QTY_SJ_YARD,
                                                                                                                    inpe.PROJECT,
                                                                                                                    ise.ADDRESSEE,
                                                                                                                    ise.BRAND_NM,
                                                                                                                    ise.ALLOCATIONCODE
                                                                                                                FROM
                                                                                                                    ITXVIEW_SURATJALAN_EXIM2A ise 
                                                                                                                LEFT JOIN ITXVIEW_NO_PROJECTS_EXIM inpe ON inpe.PROVISIONALCODE = ise.PROVISIONALCODE 
                                                                                                                WHERE 
                                                                                                                    ise.PROVISIONALCODE = '$rowdb2[PROVISIONALCODE]'
                                                                                                                    AND ise.ALLOCATIONCODE = '$rowdb2[CODE]'
                                                                                                                    AND ise.QUALITYREASONCODE = 'FOC' 
                                                                                                                GROUP BY 
                                                                                                                    inpe.PROJECT,ise.ADDRESSEE,ise.BRAND_NM,ise.ALLOCATIONCODE
                                                                                                                    ");
                        $d_rollfoc = db2_fetch_assoc($q_rollfoc);
                        $roll1 = $d_roll['ROLL'] - $d_rollfoc['ROLL'];
                        echo $roll1; // MENGHITUNG JIKA FOC SEBAGIAN, MAKA ROLL UNTUK FOC TIDAK PERLU DIPISAH DARI KESELURUHAN
                    } else {
                        $q_roll = db2_exec($conn1, "SELECT COUNT(CODE) AS ROLL,
                                                                                                                        SUM(BASEPRIMARYQUANTITY) AS QTY_SJ_KG,
                                                                                                                        SUM(BASESECONDARYQUANTITY) AS QTY_SJ_YARD,
                                                                                                                        LISTAGG(TRIM(LOTCODE), ', ') AS LOTCODE
                                                                                                                FROM 
                                                                                                                    ITXVIEWALLOCATION0 
                                                                                                                WHERE 
                                                                                                                    CODE = '$rowdb2[CODE]' AND LOTCODE IN ($rowdb2[LOTCODE2])");
                        $d_roll = db2_fetch_assoc($q_roll);
                        $roll1 = $d_roll['ROLL'];
                        echo $roll1;
                    }
                    ;

                    ?></td>
            <td>
                <?php $qty1 = number_format($d_roll['QTY_SJ_KG'], 2);
                        echo $qty1; ?>
            </td>
            <td><?= $rowdb2['LOKASI'] ?></td>
            <td>
                <?= $rowdb2['PROVISIONALCODE']; ?>
            </td>
            <td><?php echo $rowdb2['PAYMENTMETHODCODE']; ?></td>
        </tr>
        <?php else: ?>
        <tr>
            <td><?php
                    $q_pelanggan = db2_exec($conn1, "SELECT * FROM ITXVIEW_PELANGGAN WHERE ORDPRNCUSTOMERSUPPLIERCODE = '$rowdb2[ORDPRNCUSTOMERSUPPLIERCODE]' 
                                                                                                                                                AND CODE = '$rowdb2[DLVSALORDERLINESALESORDERCODE]'");
                    $r_pelanggan = db2_fetch_assoc($q_pelanggan);
                    if ($rowdb2['CODE'] == 'EXPORT') {
                        echo $d_roll['ADDRESSEE'] . ' - ' . $d_roll['BRAND_NM'];
                    } else {
                        echo $r_pelanggan['LANGGANAN'];

                    }
                    ?></td>
            <td>`<?= $rowdb2['PO_NUMBER']; ?></td>
            <td>
                <?php
                        if ($rowdb2['CODE'] == 'EXPORT') {
                            echo $d_roll['PROJECT'];
                        } else {
                            echo $rowdb2['DLVSALORDERLINESALESORDERCODE'];
                        }
                        ?>
            </td>
            <td><?= $rowdb2['JENIS_KAIN'] ?></td>
            <td>
                <?= $rowdb2['NO_WARNA'] ?>
            </td>
            <td><?= $rowdb2['WARNA']; ?></td>
            <td>
                `<?= $rowdb2['LOTCODE']; ?>
            </td>
            <td>
                <?php
                        if (in_array($rowdb2['DEFINITIVECOUNTERCODE'], array('CESDEF', 'DREDEF', 'DSEDEF', 'EXDPROV', 'EXPPROV', 'GSEPROV', 'CESPROV', 'DREPROV', 'EXDDEF', 'EXPDEF', 'GSEDEF', 'PSEPROV'))) {
                            $q_roll = db2_exec($conn1, "SELECT
                                                                                                                    COUNT(ise.COUNTROLL) AS ROLL,
                                                                                                                    SUM(ise.QTY_KG) AS QTY_SJ_KG,
                                                                                                                    SUM(ise.QTY_YARDMETER) AS QTY_SJ_YARD,
                                                                                                                    inpe.PROJECT,
                                                                                                                    ise.ADDRESSEE,
                                                                                                                    ise.BRAND_NM,
                                                                                                                    ise.ALLOCATIONCODE
                                                                                                                FROM
                                                                                                                    ITXVIEW_SURATJALAN_EXIM2A ise 
                                                                                                                LEFT JOIN ITXVIEW_NO_PROJECTS_EXIM inpe ON inpe.PROVISIONALCODE = ise.PROVISIONALCODE 
                                                                                                                WHERE 
                                                                                                                    ise.PROVISIONALCODE = '$rowdb2[PROVISIONALCODE]'
                                                                                                                    AND ise.ALLOCATIONCODE = '$rowdb2[CODE]'
                                                                                                                GROUP BY 
                                                                                                                    inpe.PROJECT,ise.ADDRESSEE,ise.BRAND_NM,ise.ALLOCATIONCODE");
                            $d_roll = db2_fetch_assoc($q_roll);
                            $roll2 = $d_roll['ROLL'];
                            echo $roll2; // MENGHITUNG JIKA FOC SEBAGIAN, MAKA ROLL UNTUK FOC TIDAK PERLU DIPISAH DARI KESELURUHAN
                        } else {
                            $q_roll = db2_exec($conn1, "SELECT COUNT(CODE) AS ROLL,
                                                                                                                        SUM(BASEPRIMARYQUANTITY) AS QTY_SJ_KG,
                                                                                                                        SUM(BASESECONDARYQUANTITY) AS QTY_SJ_YARD,
                                                                                                                        LISTAGG(TRIM(LOTCODE), ', ') AS LOTCODE
                                                                                                                FROM 
                                                                                                                    ITXVIEWALLOCATION0 
                                                                                                                WHERE 
                                                                                                                    CODE = '$rowdb2[CODE]' AND LOTCODE IN ($rowdb2[LOTCODE2])");
                            $d_roll = db2_fetch_assoc($q_roll);
                            $roll2 = $d_roll['ROLL'];
                            echo $roll2;
                        }
                        ?>
            </td>
            <td>
                <?php $qty2 = number_format($d_roll['QTY_SJ_KG'], 2);
                        echo $qty2;
                        ?>
            </td>
            <td><?= $rowdb2['LOKASI'] ?></td>
            <td>
                <?= $rowdb2['PROVISIONALCODE']; ?>
            </td>
            <td>
                <?php if ($rowdb2['PAYMENTMETHODCODE'] == 'FOC') {
                            echo $rowdb2['PAYMENTMETHODCODE'];
                        } ?>
            </td>
        </tr>
        <?php endif; ?>
        <?php } ?>
        <!-- CAPITAL KFF & KGF -->
        <?php
        $stmt_cap_kff = db2_exec($conn1, "SELECT 
                                                                                                DISTINCT
                                                                                                i.GOODSISSUEDATE,
                                                                                                i.PROVISIONALCODE,
                                                                                                i2.WARNA,
                                                                                                COUNT(i2.SUBCODE05) AS ROLL,
                                                                                                SUM(iasp.BASEPRIMARYQUANTITY) AS QTY_KG,
                                                                                                SUM(iasp.BASESECONDARYQUANTITY) AS QTY_YD,
                                                                                                i.LONGDESCRIPTION AS BUYER,
                                                                                                i.LEGALNAME1 AS CUSTOMER,
                                                                                                i.PO_NUMBER,
                                                                                                i.DLVSALORDERLINESALESORDERCODE,
                                                                                                CASE
                                                                                                    WHEN LOCATE('//', LISTAGG(DISTINCT TRIM(p.LONGDESCRIPTION), '//')) = 0 THEN LISTAGG(DISTINCT TRIM(p.LONGDESCRIPTION), '//')
                                                                                                    ELSE
                                                                                                        SUBSTR(LISTAGG(DISTINCT TRIM(p.LONGDESCRIPTION), '//'), 1, LOCATE('//', LISTAGG(DISTINCT TRIM(p.LONGDESCRIPTION), '//'))-1)
                                                                                                END AS JENIS_KAIN   
                                                                                            FROM 
                                                                                                ITXVIEW_SURATJALAN_PPC i
                                                                                            LEFT JOIN ITXVIEW_ALLOCATION_SURATJALAN_PPC iasp ON iasp.CODE = i.CODE
                                                                                            LEFT JOIN ITXVIEWCOLOR i2 ON i2.ITEMTYPECODE =  i.ITEMTYPEAFICODE
                                                                                                                    AND i2.SUBCODE01 = i.SUBCODE01 AND i2.SUBCODE02 = i.SUBCODE02
                                                                                                                    AND i2.SUBCODE03 = i.SUBCODE03 AND i2.SUBCODE04 = i.SUBCODE04
                                                                                                                    AND i2.SUBCODE05 = i.SUBCODE05 AND i2.SUBCODE06 = i.SUBCODE06
                                                                                                                    AND i2.SUBCODE07 = i.SUBCODE07 AND i2.SUBCODE08 = i.SUBCODE08
                                                                                                                    AND i2.SUBCODE09 = i.SUBCODE09 AND i2.SUBCODE10 = i.SUBCODE10
                                                                                            LEFT JOIN PRODUCT p ON p.ITEMTYPECODE =  i.ITEMTYPEAFICODE
                                                                                                                    AND p.SUBCODE01 = i.SUBCODE01 AND p.SUBCODE02 = i.SUBCODE02
                                                                                                                    AND p.SUBCODE03 = i.SUBCODE03 AND p.SUBCODE04 = i.SUBCODE04
                                                                                                                    AND p.SUBCODE05 = i.SUBCODE05 AND p.SUBCODE06 = i.SUBCODE06
                                                                                                                    AND p.SUBCODE07 = i.SUBCODE07 AND p.SUBCODE08 = i.SUBCODE08
                                                                                                                    AND p.SUBCODE09 = i.SUBCODE09 AND p.SUBCODE10 = i.SUBCODE10
                                                                                            WHERE 
                                                                                                -- $where_no_order $where_date 
                                                                                                i.PROVISIONALCODE = 'POD2407534'
                                                                                                AND (SUBSTR(i.DLVSALORDERLINESALESORDERCODE, 1,3) = 'CAP' AND (i.ITEMTYPEAFICODE = 'KFF' OR i.ITEMTYPEAFICODE = 'KGF'))
                                                                                            GROUP BY 
                                                                                                i.GOODSISSUEDATE,
                                                                                                i.PROVISIONALCODE,
                                                                                                i2.WARNA,
                                                                                                i.LONGDESCRIPTION,
                                                                                                i.LEGALNAME1,
                                                                                                i.PO_NUMBER,
                                                                                                i.DLVSALORDERLINESALESORDERCODE");
        ?>
        <?php $nourut = 1;
        while ($row_stmt_cap_kff = db2_fetch_assoc($stmt_cap_kff)) { ?>
        <tr>
            <td><?= $row_stmt_cap_kff['CUSTOMER']; ?></td>
            <td>`<?= $row_stmt_cap_kff['PO_NUMBER']; ?></td>
            <td><?= $row_stmt_cap_kff['DLVSALORDERLINESALESORDERCODE']; ?></td>
            <td><?= $row_stmt_cap_kff['JENIS_KAIN']; ?></td>
            <td><?php //No warna ?></td>
            <td><?= $row_stmt_cap_kff['WARNA']; ?></td>
            <td><?php //Lot ?></td>
            <td><?= $row_stmt_cap_kff['ROLL']; ?></td>
            <td><?= $row_stmt_cap_kff['QTY_KG']; ?></td>
            <td><?php //Lokasi ?></td>
            <td><?= $row_stmt_cap_kff['PROVISIONALCODE']; ?></td>
            <td><?php //Keterangan ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td>KFF</td>
        </tr>
        <?php } ?>

        <!-- Query untuk total Roll -->
        <?php

        if ($tgl1) {
            $where_datefilter = "i.ISSUE_DATE = '$tgl1'";
        } else {
            $where_datefilter = '';
        }

        $query_total = "SELECT COUNT(i.COUNTROLL) AS ROLL,
	SUM(i.QTY_KG) + SUM(i.FOC_KG_GUDANG) AS QTY
   	FROM ITXVIEW_SURATJALAN_EXIM2A i
	WHERE
    $where_no_order $where_datefilter
    ";

        $exec = db2_exec($conn1, $query_total);
        $data_total = db2_fetch_assoc($exec);
        ?>
        <!-- End Total Roll -->

        <tr>
            <td colspan="7" align='center'> TOTAL </td>
            <td align='center'> <?php echo $data_total['ROLL']; ?> </td>
            <td align='center'> <?php echo number_format($data_total['QTY'], 2); ?> </td>
            <td align='center'> - </td>
            <td align='center'> - </td>
            <td align='center'> - </td>
        </tr>
        <tr>
            <td colspan="12" style="border:0"></td>
        </tr>
    </tbody>
    <tfoot>
        <?php
        // ROLL TANGGAL HARI 
        $q_roll_harian = "SELECT DISTINCT
                                    TRIM(i.PROVISIONALCODE) AS PROVISIONALCODE,
                                    CASE
                                        WHEN TRIM(i.DEFINITIVECOUNTERCODE) = 'CESDEF' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'CESPROV' OR
                                            TRIM(i.DEFINITIVECOUNTERCODE) = 'DREDEF' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'DREPROV' OR 
                                            TRIM(i.DEFINITIVECOUNTERCODE) = 'DSEDEF' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'EXDDEF' OR
                                            TRIM(i.DEFINITIVECOUNTERCODE) = 'EXDPROV' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'EXPDEF' OR
                                            TRIM(i.DEFINITIVECOUNTERCODE) = 'EXPPROV' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'GSEDEF' OR 
                                            TRIM(i.DEFINITIVECOUNTERCODE) = 'GSEPROV' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'PSEPROV' THEN 'EXPORT' 
                                        ELSE TRIM(i.CODE)
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
                                    i.GOODSISSUEDATE = '$_GET[tgl1]' AND i.DOCUMENTTYPETYPE = 05 AND NOT i.CODE IS NULL AND i.PROGRESSSTATUS_SALDOC = 2
                                GROUP BY
                                    i.PROVISIONALCODE,
                                    i.DEFINITIVECOUNTERCODE,
                                    i.CODE";
        $db2_roll_harian_local = db2_exec($conn1, $q_roll_harian);
        $db2_roll_harian_export = db2_exec($conn1, $q_roll_harian);

        // LOCAL
        while ($row_roll_harian_code_local = db2_fetch_assoc($db2_roll_harian_local)) {
            if ($row_roll_harian_code_local['CODE'] != 'EXPORT') {
                $r_roll_harian_code_local[] = "'" . $row_roll_harian_code_local['CODE'] . "'";
            }
        }
        if ($r_roll_harian_code_local) {
            $value_roll_harian_code_local = implode(',', $r_roll_harian_code_local);
            $data_roll_harian_code_local = db2_exec($conn1, "SELECT COUNT(CODE) AS ROLL,
                                                                                SUM(BASEPRIMARYQUANTITY) AS QTY_SJ_KG,
                                                                                SUM(BASESECONDARYQUANTITY) AS QTY_SJ_YARD
                                                                        FROM 
                                                                            ITXVIEWALLOCATION0 
                                                                        WHERE 
                                                                            CODE IN ($value_roll_harian_code_local)");
            $fetch_roll_harian_local = db2_fetch_assoc($data_roll_harian_code_local);
        }
        // LOCAL
        
        // EXPORT
        while ($row_roll_harian_code_export = db2_fetch_assoc($db2_roll_harian_export)) {
            if ($row_roll_harian_code_export['CODE'] == 'EXPORT') {
                $r_roll_harian_code_export[] = "'" . $row_roll_harian_code_export['PROVISIONALCODE'] . "'";
            }
        }
        if (!empty($r_roll_harian_code_export)) {
            $value_roll_harian_code_export = implode(',', $r_roll_harian_code_export);
            $data_roll_harian_code_export = db2_exec($conn1, "SELECT COUNT(ise.COUNTROLL) AS ROLL,
                                                                                SUM(ise.QTY_KG) AS QTY_SJ_KG,
                                                                                SUM(ise.QTY_YARDMETER) AS QTY_SJ_YARD
                                                                            FROM
                                                                                ITXVIEW_SURATJALAN_EXIM2A ise 
                                                                            LEFT JOIN ITXVIEW_NO_PROJECTS_EXIM inpe ON inpe.PROVISIONALCODE = ise.PROVISIONALCODE 
                                                                            WHERE 
                                                                                ise.PROVISIONALCODE IN ($value_roll_harian_code_export)");
            $fetch_roll_harian_export = db2_fetch_assoc($data_roll_harian_code_export);
        }
        // EXPORT
        // ROLL TANGGAL HARI
        
        // ROLL TANGGAL HARI -1
        $tgl1_kurang = $_GET['tgl1']; // pendefinisian tanggal awal
        if (substr($tgl1_kurang, 8, 2) != '01') {
            $tgl2_kurang = date('Y-m-d', strtotime('-1 days', strtotime($tgl1_kurang))); //operasi pengurangan tanggal sebanyak 1 hari
        } else {
            $tgl2_kurang = substr($_GET['tgl1'], 0, 8) . '01'; // operasi pengurang tidak dikurangi jika tanggal 01 disetiap bulan
        }
        $awal_bulan = substr($_GET['tgl1'], 0, 8) . '01';
        $q_roll_harian_1 = "SELECT DISTINCT
                                            TRIM(i.PROVISIONALCODE) AS PROVISIONALCODE,
                                            CASE
                                                WHEN TRIM(i.DEFINITIVECOUNTERCODE) = 'CESDEF' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'CESPROV' OR
                                                    TRIM(i.DEFINITIVECOUNTERCODE) = 'DREDEF' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'DREPROV' OR 
                                                    TRIM(i.DEFINITIVECOUNTERCODE) = 'DSEDEF' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'EXDDEF' OR
                                                    TRIM(i.DEFINITIVECOUNTERCODE) = 'EXDPROV' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'EXPDEF' OR
                                                    TRIM(i.DEFINITIVECOUNTERCODE) = 'EXPPROV' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'GSEDEF' OR 
                                                    TRIM(i.DEFINITIVECOUNTERCODE) = 'GSEPROV' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'PSEPROV' THEN 'EXPORT' 
                                                ELSE TRIM(i.CODE)
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
                                            i.GOODSISSUEDATE BETWEEN '$awal_bulan' AND '$tgl2_kurang' AND i.DOCUMENTTYPETYPE = 05 AND NOT i.CODE IS NULL AND i.PROGRESSSTATUS_SALDOC = 2
                                        GROUP BY
                                            i.PROVISIONALCODE,
                                            i.DEFINITIVECOUNTERCODE,
                                            i.CODE";
        $db2_roll_harian_local_1 = db2_exec($conn1, $q_roll_harian_1);
        $db2_roll_harian_export_1 = db2_exec($conn1, $q_roll_harian_1);

        // LOCAL
        while ($row_roll_harian_code_local_1 = db2_fetch_assoc($db2_roll_harian_local_1)) {
            if ($row_roll_harian_code_local_1['CODE'] != 'EXPORT') {
                $r_roll_harian_code_local_1[] = "'" . $row_roll_harian_code_local_1['CODE'] . "'";
            }
        }
        if ($r_roll_harian_code_local_1) {
            $value_roll_harian_code_local_1 = implode(',', $r_roll_harian_code_local_1);
            $data_roll_harian_code_local_1 = db2_exec($conn1, "SELECT COUNT(CODE) AS ROLL,
                                                                                SUM(BASEPRIMARYQUANTITY) AS QTY_SJ_KG,
                                                                                SUM(BASESECONDARYQUANTITY) AS QTY_SJ_YARD
                                                                        FROM 
                                                                            ITXVIEWALLOCATION0 
                                                                        WHERE 
                                                                            CODE IN ($value_roll_harian_code_local_1)");
            $fetch_roll_harian_local_1 = db2_fetch_assoc($data_roll_harian_code_local_1);
        }
        // LOCAL
        
        // EXPORT
        while ($row_roll_harian_code_export_1 = db2_fetch_assoc($db2_roll_harian_export_1)) {
            if ($row_roll_harian_code_export_1['CODE'] == 'EXPORT') {
                $r_roll_harian_code_export_1[] = "'" . $row_roll_harian_code_export_1['PROVISIONALCODE'] . "'";
            }
        }
        if ($r_roll_harian_code_export_1) {
            $value_roll_harian_code_export_1 = implode(',', $r_roll_harian_code_export_1);
            $data_roll_harian_code_export_1 = db2_exec($conn1, "SELECT COUNT(ise.COUNTROLL) AS ROLL,
                                                                                SUM(ise.QTY_KG) AS QTY_SJ_KG,
                                                                                SUM(ise.QTY_YARDMETER) AS QTY_SJ_YARD
                                                                            FROM
                                                                                ITXVIEW_SURATJALAN_EXIM2A ise 
                                                                            LEFT JOIN ITXVIEW_NO_PROJECTS_EXIM inpe ON inpe.PROVISIONALCODE = ise.PROVISIONALCODE 
                                                                            WHERE 
                                                                                ise.PROVISIONALCODE IN ($value_roll_harian_code_export_1)");
            $fetch_roll_harian_export_1 = db2_fetch_assoc($data_roll_harian_code_export_1);
        }
        // EXPORT
        // ROLL TANGGAL HARI -1
        
        // ROLL TANGGAL HARI sd hari H
        $tgl1_hariH = $_GET['tgl1']; // pendefinisian tanggal awal
        
        if (substr($tgl1_kurang, 8, 2) != '01') {
            $tgl2_hariH = date('Y-m-d', strtotime('+1 days', strtotime($tgl1_hariH))); //operasi pengurangan tanggal sebanyak 1 hari
        } else {
            $tgl2_hariH = substr($_GET['tgl1'], 0, 8) . '01'; // operasi pengurang tidak dikurangi jika tanggal 01 disetiap bulan
        }
        $awal_bulan_hariH = substr($_GET['tgl1'], 0, 8) . '01';

        $q_roll_harian_hariH = "SELECT DISTINCT
                                    TRIM(i.PROVISIONALCODE) AS PROVISIONALCODE,
                                    CASE
                                        WHEN TRIM(i.DEFINITIVECOUNTERCODE) = 'CESDEF' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'CESPROV' OR
                                            TRIM(i.DEFINITIVECOUNTERCODE) = 'DREDEF' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'DREPROV' OR 
                                            TRIM(i.DEFINITIVECOUNTERCODE) = 'DSEDEF' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'EXDDEF' OR
                                            TRIM(i.DEFINITIVECOUNTERCODE) = 'EXDPROV' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'EXPDEF' OR
                                            TRIM(i.DEFINITIVECOUNTERCODE) = 'EXPPROV' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'GSEDEF' OR 
                                            TRIM(i.DEFINITIVECOUNTERCODE) = 'GSEPROV' OR TRIM(i.DEFINITIVECOUNTERCODE) = 'PSEPROV' THEN 'EXPORT' 
                                        ELSE TRIM(i.CODE)
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
                                    i.GOODSISSUEDATE BETWEEN '$awal_bulan_hariH' AND '$tgl2_hariH' AND i.DOCUMENTTYPETYPE = 05 AND NOT i.CODE IS NULL AND i.PROGRESSSTATUS_SALDOC = 2
                                GROUP BY
                                    i.PROVISIONALCODE,
                                    i.DEFINITIVECOUNTERCODE,
                                    i.CODE";
        $db2_roll_harian_local_hariH = db2_exec($conn1, $q_roll_harian_hariH);
        $db2_roll_harian_export_hariH = db2_exec($conn1, $q_roll_harian_hariH);


        ?>

        <tr>
            <th colspan="4" align="left"> </th>
            <th colspan="2" align="center">Dibuat Oleh</th>
            <th colspan="3" align="center">Diperiksa Oleh</th>
            <th colspan="3" align="center">Diketahui Oleh</th>
        </tr>
        <tr>
            <th colspan="4" align="left">Nama</th>
            <th colspan="2" align="center">Ridwan</th>
            <th colspan="3" align="center"></th>
            <th colspan="3" align="center">Tardo</th>
        </tr>
        <tr>
            <th colspan="4" align="left">Jabatan</th>
            <th colspan="2" align="center">Clerk</th>
            <th colspan="3" align="center"></th>
            <th colspan="3" align="center">Asst. Supervisor</th>
        </tr>
        <tr>
            <th colspan="4" align="left">Tanggal</th>
            <th colspan="2" align="center"><?php $date = date_create($_GET['tgl1']);
            echo date_format($date, "d-M-Y"); ?></th>
            <th colspan="3" align="center"></th>
            <th colspan="3" align="center"><?php $date = date_create($_GET['tgl1']);
            echo date_format($date, "d-M-Y"); ?></th>
        </tr>
        <tr>
            <th colspan="4"><br></th>
            <th colspan="2"><br></th>
            <th colspan="3"><br><br><br></th>
            <th colspan="3"><br><br><br></th>
        </tr>
    </tfoot>
</table>
<!-- <table border="0">
    <thead>
        <tr>
            <th>Total SJ</th>
            <th><?php //echo $row_countSJ['TOTAL_SJ']; 
            ?></th>
        </tr>
    </thead>
</table> -->
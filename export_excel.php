<?php
header("content-type:application/vnd-ms-excel");
header("content-disposition:attachment;filename=PRD - Konsistensi Timbang Gerobak.xls");
header('Cache-Control: max-age=0');
?>
<?php
ini_set("error_reporting", 1);
require_once "koneksi.php";
?>
<table
    class="table table-striped table-bordered nowrap">
    <thead>
        <tr align="center">
            <th>TANGGAL</th>
            <th>PRODUCTION ORDER</th>
            <th>PRODUCTION DEMAND</th>
            <th>NO PROJECT</th>
            <th>STEP NUMBER</th>
            <th>OPERATION CODE</th>
            <th>DEPT</th>
            <th>OPERATOR IN</th>
            <th>OPERATOR OUT</th>
            <th>QTY PRODUCTION ORDER</th>
            <th>BEFORE</th>
            <th>AFTER</th>
            <th>KETENTUAN</th>
            <th>STATUS</th>
            <th>NO MESIN</th>
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
                    p4.TOTALPRIMARYQUANTITY,
                    p.MACHINECODE
                FROM
                    PRODUCTIONPROGRESS p 
                LEFT JOIN PRODUCTIONPROGRESSSTEPUPDATED p2 ON p2.PROPROGRESSPROGRESSNUMBER = p.PROGRESSNUMBER
                LEFT JOIN PRODUCTIONDEMANDSTEP p3 ON p3.PRODUCTIONDEMANDCODE = p2.DEMANDSTEPPRODUCTIONDEMANDCODE AND p3.STEPNUMBER = p2.DEMANDSTEPSTEPNUMBER
                LEFT JOIN OPERATION o ON o.CODE = p.OPERATIONCODE 
                LEFT JOIN RESOURCES r ON r.CODE = p.OPERATORCODE
                LEFT JOIN PRODUCTIONORDER p4 ON p4.CODE = p.PRODUCTIONORDERCODE 
                LEFT JOIN PRODUCTIONDEMAND p5 ON p5.CODE = p3.PRODUCTIONDEMANDCODE 
                WHERE
                    CAST(p.CREATIONDATETIME AS DATE) BETWEEN '$_GET[date]' AND  '$_GET[date2]'
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
                                            'WN1-RP1', 'WN1-RP2', 'WN1-SC1', 'WN1-SC2', 'WN1-SP3', 'WN1-SP4', 'WSH1', 'WSH2', 'YDR1', 'YDR2', 'RLX1')
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
                <td>'<?= $row_timbang['CREATIONDATETIME']; ?></td>
                <td> <a target="_BLANK" href="https://online.indotaichen.com/nowgerobak/HasilTimbang-<?= $row_timbang['PRODUCTIONORDERCODE']; ?>">`<?= $row_timbang['PRODUCTIONORDERCODE']; ?></td>
                <td> <a target="_BLANK" href="https://online.indotaichen.com/laporan/ppc_filter_steps.php?demand=<?= $row_timbang['PRODUCTIONDEMANDCODE']; ?>&prod_order=<?= $row_timbang['PRODUCTIONORDERCODE']; ?>">`<?= $row_timbang['PRODUCTIONDEMANDCODE']; ?></td>
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
                <td><?= $row_timbang['MACHINECODE']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php
header("content-type:application/vnd-ms-excel");
header("content-disposition:attachment;filename=TerimaOrder.xls");
header('Cache-Control: max-age=0');
require_once 'koneksi.php';
$tglInput = $_GET['tgl'];
?>
LAPORAN DELIVERY ORDER PERMINGGU TAHUN 2025
<table border="1" width="100%" style="border-collapse:collapse; border:1px solid #000; font-size:12px; text-align:center;">
    <thead>
      <tr>
        <th colspan="12" style="text-align: right;">Update, <?= $tglInput; ?></th>
      </tr>
      <tr>
        <th>BULAN</th>
        <th>LOKAL</th>
        <th>%LOKAL</th>
        <th>EXPORT</th>
        <th>%EXPORT</th>
        <th>F/K</th>
        <th>TOTAL</th>
        <th style="background-color: yellow;">BOOKING</th>
        <th>JASA</th>
        <th>PRINTING</th>
        <th>KIRIM</th>
        <th>ON TIME</th>
      </tr>
    </thead>
    <tbody>
      <!-- DESEMBER 'TAHUN SEBELUMNYA -->
        <?php
          // Ambil tahun sebelumnya
          $tahunSebelumnya = date('Y', strtotime($tglInput)) - 1;

          // Bangun awal dan akhir bulan Desember tahun sebelumnya
          $tglAwalDes     = $tahunSebelumnya . '-12-01';
          $tglAkhirDes    = $tahunSebelumnya . '-12-31';

          // LOKAL
            $qDesThnSebelumnyaLokal = "WITH QTY_BRUTO AS (
                                          SELECT
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE,
                                            SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                            SUM(i.USERSECONDARYQUANTITY) AS FKF
                                          FROM
                                            ITXVIEWKGBRUTOBONORDER2 i
                                          GROUP BY 
                                          i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE
                                        )
                                        SELECT 
                                          SUM(QTY) AS QTY
                                        FROM 
                                        (SELECT
                                          s.CODE,
                                          s3.DELIVERYDATE,
                                          ROUND(SUM(qb.KFF)) AS QTY
                                        FROM
                                          SALESORDER s
                                        LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                        LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                        LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                        LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                        LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                        WHERE
                                          CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                          AND s.TEMPLATECODE IN ('DOM', 'SAM')
                                          AND NOT s3.DELIVERYDATE IS NULL
                                          AND NOT a.VALUESTRING IS NULL
                                          AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                        GROUP BY
                                          s.CODE,
                                          s3.DELIVERYDATE)
                                        WHERE
                                          DELIVERYDATE BETWEEN '$tglAwalDes' AND '$tglAkhirDes' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultDesThnSebelumnyaLokal = db2_exec($conn1, $qDesThnSebelumnyaLokal);
            $rowDesThnSebelumnyaLokal    = db2_fetch_assoc($resultDesThnSebelumnyaLokal);
            $dataDesThnSebelumnyaLokal   = $rowDesThnSebelumnyaLokal['QTY'];
          // LOKAL
          
          // EXPORT
            $qDesThnSebelumnyaExport = "WITH QTY_BRUTO AS (
                                          SELECT
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE,
                                            SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                            SUM(i.USERSECONDARYQUANTITY) AS FKF
                                          FROM
                                            ITXVIEWKGBRUTOBONORDER2 i
                                          GROUP BY 
                                          i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE
                                        )
                                        SELECT 
                                          SUM(QTY) AS QTY
                                        FROM 
                                        (SELECT
                                          s.CODE,
                                          s3.DELIVERYDATE,
                                          ROUND(SUM(qb.KFF)) AS QTY
                                        FROM
                                          SALESORDER s
                                        LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                        LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                        LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                        LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                        LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                        WHERE
                                          CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                          AND s.TEMPLATECODE IN ('EXP', 'SME')
                                          AND NOT s3.DELIVERYDATE IS NULL
                                          AND NOT a.VALUESTRING IS NULL
                                          AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                        GROUP BY
                                          s.CODE,
                                          s3.DELIVERYDATE)
                                        WHERE
                                          DELIVERYDATE BETWEEN '$tglAwalDes' AND '$tglAkhirDes' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultDesThnSebelumnyaExport = db2_exec($conn1, $qDesThnSebelumnyaExport);
            $rowDesThnSebelumnyaExport    = db2_fetch_assoc($resultDesThnSebelumnyaExport);
            $dataDesThnSebelumnyaExport   = $rowDesThnSebelumnyaExport['QTY'];
          // EXPORT
          
          // F/K
            $qDesThnSebelumnyaLokalExport_fkf = "WITH QTY_BRUTO AS (
                                                    SELECT
                                                      i.CODE,
                                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                                    FROM
                                                      ITXVIEWKGBRUTOBONORDER2 i
                                                    GROUP BY 
                                                    i.CODE,
                                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                                      i.ORIGDLVSALORDERLINEORDERLINE
                                                  )
                                                  SELECT 
                                                    SUM(QTY) AS QTY
                                                  FROM 
                                                  (SELECT
                                                    s.CODE,
                                                    s3.DELIVERYDATE,
                                                    ROUND(SUM(qb.KFF)) AS QTY
                                                  FROM
                                                    SALESORDER s
                                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'FKF'
                                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                                  WHERE
                                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                                    AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                                    AND NOT s3.DELIVERYDATE IS NULL
                                                    AND NOT a.VALUESTRING IS NULL
                                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                                  GROUP BY
                                                    s.CODE,
                                                    s3.DELIVERYDATE)
                                                  WHERE
                                                    DELIVERYDATE BETWEEN '$tglAwalDes' AND '$tglAkhirDes' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultDesThnSebelumnyaLokalExport_fkf = db2_exec($conn1, $qDesThnSebelumnyaLokalExport_fkf);
            $rowDesThnSebelumnyaLokalExport_fkf    = db2_fetch_assoc($resultDesThnSebelumnyaLokalExport_fkf);
            $dataDesThnSebelumnyaLokalExport_fkf   = $rowDesThnSebelumnyaLokalExport_fkf['QTY'];
          // F/K
          
          // BOOKING
            $qDesThnSebelumnyaBooking = "WITH QTY_BRUTO AS (
                                          SELECT
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE,
                                            SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                            SUM(i.USERSECONDARYQUANTITY) AS FKF
                                          FROM
                                            ITXVIEWKGBRUTOBONORDER2 i
                                          GROUP BY 
                                          i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE
                                        )
                                        SELECT 
                                          SUM(QTY) AS QTY
                                        FROM 
                                        (SELECT
                                          s.CODE,
                                          s3.DELIVERYDATE,
                                          ROUND(SUM(qb.KFF)) AS QTY
                                        FROM
                                          SALESORDER s
                                        LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                        LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                        LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                        LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                        LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                        WHERE
                                          CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                          AND s.TEMPLATECODE IN ('OPN', 'TBG', 'MNB', 'DMB', 'RBG')
                                          AND NOT s3.DELIVERYDATE IS NULL
                                          AND NOT a.VALUESTRING IS NULL
                                          AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                        GROUP BY
                                          s.CODE,
                                          s3.DELIVERYDATE)
                                        WHERE
                                          DELIVERYDATE BETWEEN '$tglAwalDes' AND '$tglAkhirDes' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultDesThnSebelumnyaBooking = db2_exec($conn1, $qDesThnSebelumnyaBooking);
            $rowDesThnSebelumnyaBooking    = db2_fetch_assoc($resultDesThnSebelumnyaBooking);
            $dataDesThnSebelumnyaBooking   = $rowDesThnSebelumnyaBooking['QTY'];
          // BOOKING
          
          // JASA
            $qDesThnSebelumnyaJasa = "WITH QTY_BRUTO AS (
                                        SELECT
                                          i.CODE,
                                          i.ORIGDLVSALORDLINESALORDERCODE,
                                          i.ORIGDLVSALORDERLINEORDERLINE,
                                          SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                          SUM(i.USERSECONDARYQUANTITY) AS FKF
                                        FROM
                                          ITXVIEWKGBRUTOBONORDER2 i
                                        GROUP BY 
                                        i.CODE,
                                          i.ORIGDLVSALORDLINESALORDERCODE,
                                          i.ORIGDLVSALORDERLINEORDERLINE
                                      )
                                      SELECT 
                                          SUM(QTY) AS QTY
                                        FROM 
                                        (SELECT
                                          s.CODE,
                                          s3.DELIVERYDATE,
                                          ROUND(SUM(qb.KFF)) AS QTY
                                        FROM
                                          SALESORDER s
                                        LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                        LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                        LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                        LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                        LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                        WHERE
                                          CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                          AND s.TEMPLATECODE IN ('CWD')
                                          AND NOT s3.DELIVERYDATE IS NULL
                                          AND NOT a.VALUESTRING IS NULL
                                          AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                        GROUP BY
                                          s.CODE,
                                          s3.DELIVERYDATE)
                                        WHERE
                                          DELIVERYDATE BETWEEN '$tglAwalDes' AND '$tglAkhirDes' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultDesThnSebelumnyaJasa = db2_exec($conn1, $qDesThnSebelumnyaJasa);
            $rowDesThnSebelumnyaJasa    = db2_fetch_assoc($resultDesThnSebelumnyaJasa);
            $dataDesThnSebelumnyaJasa   = $rowDesThnSebelumnyaJasa['QTY'];
          // JASA
          
          // PRINTING
            $qDesThnSebelumnyaPrt = "WITH QTY_BRUTO AS (
                                      SELECT
                                        i.CODE,
                                        i.ORIGDLVSALORDLINESALORDERCODE,
                                        i.ORIGDLVSALORDERLINEORDERLINE,
                                        SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                        SUM(i.USERSECONDARYQUANTITY) AS FKF
                                      FROM
                                        ITXVIEWKGBRUTOBONORDER2 i
                                      GROUP BY 
                                      i.CODE,
                                        i.ORIGDLVSALORDLINESALORDERCODE,
                                        i.ORIGDLVSALORDERLINEORDERLINE
                                    )
                                    SELECT 
                                      SUM(QTY) AS QTY
                                    FROM 
                                    (SELECT
                                      s.CODE,
                                      s3.DELIVERYDATE,
                                      ROUND(SUM(qb.KFF)) AS QTY
                                    FROM
                                      SALESORDER s
                                    LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF') AND NOT TRIM(SUBCODE07) = '-'
                                    LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                    LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                    LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                    LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                    LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                    WHERE
                                      CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                      AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')  
                                      AND NOT s3.DELIVERYDATE IS NULL
                                      AND NOT a.VALUESTRING IS NULL
                                      AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                    GROUP BY
                                      s.CODE,
                                      s3.DELIVERYDATE)
                                    WHERE
                                      DELIVERYDATE BETWEEN '$tglAwalDes' AND '$tglAkhirDes' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultDesThnSebelumnyaPrt = db2_exec($conn1, $qDesThnSebelumnyaPrt);
            $rowDesThnSebelumnyaPrt    = db2_fetch_assoc($resultDesThnSebelumnyaPrt);
            $dataDesThnSebelumnyaPrt   = $rowDesThnSebelumnyaPrt['QTY'];
          // PRINTING

          // TOTAL
            $total_DesThnSebelumnya = $dataDesThnSebelumnyaLokal + $dataDesThnSebelumnyaExport + $dataDesThnSebelumnyaLokalExport_fkf;
            $data_total_DesThnSebelumnya = $total_DesThnSebelumnya;
          // TOTAL

          // %LOKAL
            $a_DesThnSebelumnya = $dataDesThnSebelumnyaLokal + $dataDesThnSebelumnyaLokalExport_fkf + $dataDesThnSebelumnyaPrt;
            $b_DesThnSebelumnya = $data_total_DesThnSebelumnya;
            $data_PersentageLokal_DesThnSebelumnya = ($b_DesThnSebelumnya != 0) ? ROUND($a_DesThnSebelumnya / $b_DesThnSebelumnya * 100) : 0;
          // %LOKAL

          // %EXPORT
            $data_PersentageExport_DesThnSebelumnya = ($data_total_DesThnSebelumnya != 0) ? ROUND($dataDesThnSebelumnyaExport / $data_total_DesThnSebelumnya * 100) : 0;
          // %EXPORT

          // KIRIM
            $qQtyPengirimanThnSebelumnya = "SELECT 
                                              SUM(qty) AS qty
                                            FROM
                                              [nowprd].[tbl_summary] 
                                            WHERE
                                              tanggal BETWEEN '$tglAwalDes' AND '$tglAkhirDes'";
            $resultQtyPengirimanThnSebelumnya = sqlsrv_query($con_nowprd, $qQtyPengirimanThnSebelumnya);
            $rowQtyPengirimanThnSebelumnya    = sqlsrv_fetch_array($resultQtyPengirimanThnSebelumnya);
            $dataDesThnSebelumnyaKirim        = $rowQtyPengirimanThnSebelumnya['qty'];
          // KIRIM
        ?>
        <tr>
          <td>DESEMBER '<?= substr($tahunSebelumnya, 2); ?></td>
          <td><?= number_format($dataDesThnSebelumnyaLokal); ?></td>
          <td><?= number_format($data_PersentageLokal_DesThnSebelumnya); ?> %</td>
          <td><?= number_format($dataDesThnSebelumnyaExport); ?></td>
          <td><?= number_format($data_PersentageExport_DesThnSebelumnya); ?> %</td>
          <td><?= number_format($dataDesThnSebelumnyaLokalExport_fkf); ?></td>
          <td><?= number_format($data_total_DesThnSebelumnya); ?></td>
          <td style="background-color: yellow;"><?= number_format($dataDesThnSebelumnyaBooking); ?></td>
          <td><?= number_format($dataDesThnSebelumnyaJasa); ?></td>
          <td><?= number_format($dataDesThnSebelumnyaPrt); ?></td>
          <td><?= number_format($dataDesThnSebelumnyaKirim); ?></td>
          <td>...</td>
        </tr>
      <!-- DESEMBER 'TAHUN SEBELUMNYA -->

      <!-- JANUARI -->
        <?php
          $tahunInput = date('Y', strtotime($tglInput));

          // Bangun awal dan akhir bulan Desember tahun sebelumnya
          $tglAwalJan     = $tahunInput . '-01-01';
          $tglAkhirJan    = $tahunInput . '-01-31';

          // LOKAL
            $qJanThnIniLokal = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('DOM', 'SAM')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalJan' AND '$tglAkhirJan' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJanThnIniLokal = db2_exec($conn1, $qJanThnIniLokal);
            $rowJanThnIniLokal    = db2_fetch_assoc($resultJanThnIniLokal);
            $dataJanThnIniLokal   = $rowJanThnIniLokal['QTY'];
          // LOKAL
          
          // EXPORT
            $qJanThnIniExport = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('EXP', 'SME')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalJan' AND '$tglAkhirJan' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJanThnIniExport = db2_exec($conn1, $qJanThnIniExport);
            $rowJanThnIniExport    = db2_fetch_assoc($resultJanThnIniExport);
            $dataJanThnIniExport   = $rowJanThnIniExport['QTY'];
          // EXPORT
          
          // F/K
            $qJanThnIniLokalExport_fkf = "WITH QTY_BRUTO AS (
                                          SELECT
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE,
                                            SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                            SUM(i.USERSECONDARYQUANTITY) AS FKF
                                          FROM
                                            ITXVIEWKGBRUTOBONORDER2 i
                                          GROUP BY 
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE
                                        )
                                        SELECT 
                                          SUM(QTY) AS QTY
                                        FROM 
                                        (SELECT
                                          s.CODE,
                                          s3.DELIVERYDATE,
                                          ROUND(SUM(qb.KFF)) AS QTY
                                        FROM
                                          SALESORDER s
                                        LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'FKF'
                                        LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                        LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                        LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                        LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                        WHERE
                                          CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                          AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                          AND NOT s3.DELIVERYDATE IS NULL
                                          AND NOT a.VALUESTRING IS NULL
                                          AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                        GROUP BY
                                          s.CODE,
                                          s3.DELIVERYDATE)
                                        WHERE
                                          DELIVERYDATE BETWEEN '$tglAwalJan' AND '$tglAkhirJan' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJanThnIniLokalExport_fkf = db2_exec($conn1, $qJanThnIniLokalExport_fkf);
            $rowJanThnIniLokalExport_fkf    = db2_fetch_assoc($resultJanThnIniLokalExport_fkf);
            $dataJanThnIniLokalExport_fkf   = $rowJanThnIniLokalExport_fkf['QTY'];
          // F/K
          
          // BOOKING
            $qJanThnIniBooking = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(qb.KFF)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND s.TEMPLATECODE IN ('OPN', 'TBG', 'MNB', 'DMB', 'RBG')
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalJan' AND '$tglAkhirJan' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJanThnIniBooking = db2_exec($conn1, $qJanThnIniBooking);
            $rowJanThnIniBooking    = db2_fetch_assoc($resultJanThnIniBooking);
            $dataJanThnIniBooking   = $rowJanThnIniBooking['QTY'];
          // BOOKING
          
          // JASA
            $qJanThnIniJasa = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD')
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalJan' AND '$tglAkhirJan' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJanThnIniJasa = db2_exec($conn1, $qJanThnIniJasa);
            $rowJanThnIniJasa    = db2_fetch_assoc($resultJanThnIniJasa);
            $dataJanThnIniJasa   = $rowJanThnIniJasa['QTY'];
          // JASA
          
          // PRINTING
            $qJanThnIniPrt = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF') AND NOT TRIM(SUBCODE07) = '-'
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')  
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalJan' AND '$tglAkhirJan' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJanThnIniPrt = db2_exec($conn1, $qJanThnIniPrt);
            $rowJanThnIniPrt    = db2_fetch_assoc($resultJanThnIniPrt);
            $dataJanThnIniPrt   = $rowJanThnIniPrt['QTY'];
          // PRINTING

          // TOTAL
            $total_JanThnIni = $dataJanThnIniLokal + $dataJanThnIniExport + $dataJanThnIniLokalExport_fkf;
            $data_total_JanThnIni = $total_JanThnIni;
          // TOTAL

          // %LOKAL
            $a_JanThnIni = $dataJanThnIniLokal + $dataJanThnIniLokalExport_fkf + $dataJanThnIniPrt;
            $b_JanThnIni = $data_total_JanThnIni;
            $data_PersentageLokal_JanThnIni = $b_JanThnIni == 0 ? 0 : ($a_JanThnIni / $b_JanThnIni * 100);
          // %LOKAL

          // %EXPORT
            $data_PersentageExport_JanThnIni = $data_total_JanThnIni == 0 ? 0 : ($dataJanThnIniExport / $data_total_JanThnIni * 100);
          // %EXPORT
          
          // KIRIM
            $qQtyJanPengirimanThnIni = "SELECT 
                                              SUM(qty) AS qty
                                            FROM
                                              [nowprd].[tbl_summary] 
                                            WHERE
                                              tanggal BETWEEN '$tglAwalJan' AND '$tglAkhirJan'";
            $resultQtyJanPengirimanThnIni      = sqlsrv_query($con_nowprd, $qQtyJanPengirimanThnIni);
            $rowQtyJanPengirimanThnIni         = sqlsrv_fetch_array($resultQtyJanPengirimanThnIni);
            $dataQtyJanPengirimanThnIniKirim   = $rowQtyJanPengirimanThnIni['qty'];
          // KIRIM
        ?>
        <tr>
          <td>JANUARI</td>
          <td><?= number_format($dataJanThnIniLokal); ?></td>
          <td><?= number_format($data_PersentageLokal_JanThnIni); ?> %</td>
          <td><?= number_format($dataJanThnIniExport); ?></td>
          <td><?= number_format($data_PersentageExport_JanThnIni); ?> %</td>
          <td><?= number_format($dataJanThnIniLokalExport_fkf); ?></td>
          <td><?= number_format($data_total_JanThnIni); ?></td>
          <td style="background-color: yellow;"><?= number_format($dataJanThnIniJasa); ?></td>
          <td><?= number_format($dataJanThnIniBooking); ?></td>
          <td><?= number_format($dataJanThnIniPrt); ?></td>
          <td><?= number_format($dataQtyJanPengirimanThnIniKirim); ?></td>
          <td>...</td>
        </tr>
      <!-- JANUARI -->

      <!-- FEBRUARI -->
        <?php
          $tahunInput = date('Y', strtotime($tglInput));

          // Bangun awal dan akhir bulan Desember tahun sebelumnya
          $tglAwalFeb     = $tahunInput . '-02-01';
          $tglAkhirFeb    = $tahunInput . '-02-28';

          // LOKAL
            $qFebThnIniLokal = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('DOM', 'SAM')
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalFeb' AND '$tglAkhirFeb' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultFebThnIniLokal = db2_exec($conn1, $qFebThnIniLokal);
            $rowFebThnIniLokal    = db2_fetch_assoc($resultFebThnIniLokal);
            $dataFebThnIniLokal   = $rowFebThnIniLokal['QTY'];
          // LOKAL
          
          // EXPORT
            $qFebThnIniExport = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('EXP', 'SME')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalFeb' AND '$tglAkhirFeb' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultFebThnIniExport = db2_exec($conn1, $qFebThnIniExport);
            $rowFebThnIniExport    = db2_fetch_assoc($resultFebThnIniExport);
            $dataFebThnIniExport   = $rowFebThnIniExport['QTY'];
          // EXPORT
          
          // F/K
            $qFebThnIniLokalExport_fkf = "WITH QTY_BRUTO AS (
                                          SELECT
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE,
                                            SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                            SUM(i.USERSECONDARYQUANTITY) AS FKF
                                          FROM
                                            ITXVIEWKGBRUTOBONORDER2 i
                                          GROUP BY 
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE
                                        )
                                        SELECT 
                                          SUM(QTY) AS QTY
                                        FROM 
                                        (SELECT
                                          s.CODE,
                                          s3.DELIVERYDATE,
                                          ROUND(SUM(qb.KFF)) AS QTY
                                        FROM
                                          SALESORDER s
                                        LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'FKF'
                                        LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                        LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                        LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                        LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                        WHERE
                                          CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                          AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                          AND NOT s3.DELIVERYDATE IS NULL
                                          AND NOT a.VALUESTRING IS NULL
                                          AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                        GROUP BY
                                          s.CODE,
                                          s3.DELIVERYDATE)
                                        WHERE
                                          DELIVERYDATE BETWEEN '$tglAwalFeb' AND '$tglAkhirFeb' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultFebThnIniLokalExport_fkf = db2_exec($conn1, $qFebThnIniLokalExport_fkf);
            $rowFebThnIniLokalExport_fkf    = db2_fetch_assoc($resultFebThnIniLokalExport_fkf);
            $dataFebThnIniLokalExport_fkf   = $rowFebThnIniLokalExport_fkf['QTY'];
          // F/K
          
          // BOOKING
            $qFebThnIniBooking = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(qb.KFF)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND s.TEMPLATECODE IN ('OPN', 'TBG', 'MNB', 'DMB', 'RBG')
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalFeb' AND '$tglAkhirFeb' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultFebThnIniBooking = db2_exec($conn1, $qFebThnIniBooking);
            $rowFebThnIniBooking    = db2_fetch_assoc($resultFebThnIniBooking);
            $dataFebThnIniBooking   = $rowFebThnIniBooking['QTY'];
          // BOOKING
          
          // JASA
            $qFebThnIniJasa = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD')
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalFeb' AND '$tglAkhirFeb' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultFebThnIniJasa = db2_exec($conn1, $qFebThnIniJasa);
            $rowFebThnIniJasa    = db2_fetch_assoc($resultFebThnIniJasa);
            $dataFebThnIniJasa   = $rowFebThnIniJasa['QTY'];
          // JASA
          
          // PRINTING
            $qFebThnIniPrt = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF') AND NOT TRIM(SUBCODE07) = '-'
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')  
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalFeb' AND '$tglAkhirFeb' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultFebThnIniPrt = db2_exec($conn1, $qFebThnIniPrt);
            $rowFebThnIniPrt    = db2_fetch_assoc($resultFebThnIniPrt);
            $dataFebThnIniPrt   = $rowFebThnIniPrt['QTY'];
          // PRINTING

          // TOTAL
            $total_FebThnIni = $dataFebThnIniLokal + $dataFebThnIniExport + $dataFebThnIniLokalExport_fkf;
            $data_total_FebThnIni = $total_FebThnIni;
          // TOTAL

          // %LOKAL
            $a_FebThnIni = $dataFebThnIniLokal + $dataFebThnIniLokalExport_fkf + $dataFebThnIniPrt;
            $b_FebThnIni = $data_total_FebThnIni;
            $data_PersentageLokal_FebThnIni = $b_FebThnIni == 0 ? 0 : ($a_FebThnIni / $b_FebThnIni * 100);
          // %LOKAL

          // %EXPORT
            $data_PersentageExport_FebThnIni = $data_total_FebThnIni == 0 ? 0 : ($dataFebThnIniExport / $data_total_FebThnIni * 100);
          // %EXPORT

          // KIRIM
            $qQtyFebPengirimanThnIni = "SELECT 
                                              SUM(qty) AS qty
                                            FROM
                                              [nowprd].[tbl_summary] 
                                            WHERE
                                              tanggal BETWEEN '$tglAwalFeb' AND '$tglAkhirFeb'";
            $resultQtyFebPengirimanThnIni      = sqlsrv_query($con_nowprd, $qQtyFebPengirimanThnIni);
            $rowQtyFebPengirimanThnIni         = sqlsrv_fetch_array($resultQtyFebPengirimanThnIni);
            $dataQtyFebPengirimanThnIniKirim   = $rowQtyFebPengirimanThnIni['qty'];
          // KIRIM
        ?>
        <tr>
          <td>FEBRUARI</td>
          <td><?= number_format($dataFebThnIniLokal); ?></td>
          <td><?= number_format($data_PersentageLokal_FebThnIni); ?> %</td>
          <td><?= number_format($dataFebThnIniExport); ?></td>
          <td><?= number_format($data_PersentageExport_FebThnIni); ?> %</td>
          <td><?= number_format($dataFebThnIniLokalExport_fkf); ?></td>
          <td><?= number_format($data_total_FebThnIni); ?></td>
          <td style="background-color: yellow;"><?= number_format($dataFebThnIniJasa); ?></td>
          <td><?= number_format($dataFebThnIniBooking); ?></td>
          <td><?= number_format($dataFebThnIniPrt); ?></td>
          <td><?= number_format($dataQtyFebPengirimanThnIniKirim); ?></td>
          <td>...</td>
        </tr>
      <!-- FEBRUARI -->
      
      <!-- MARET -->
        <?php
          $tahunInput = date('Y', strtotime($tglInput));

          // Bangun awal dan akhir bulan Desember tahun sebelumnya
          $tglAwalMar     = $tahunInput . '-03-01';
          $tglAkhirMar    = $tahunInput . '-03-31';

          // LOKAL
            $qMarThnIniLokal = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('DOM', 'SAM')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalMar' AND '$tglAkhirMar' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultMarThnIniLokal = db2_exec($conn1, $qMarThnIniLokal);
            $rowMarThnIniLokal    = db2_fetch_assoc($resultMarThnIniLokal);
            $dataMarThnIniLokal   = $rowMarThnIniLokal['QTY'];
          // LOKAL
          
          // EXPORT
            $qMarThnIniExport = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(qb.KFF)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND s.TEMPLATECODE IN ('EXP', 'SME')
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalMar' AND '$tglAkhirMar' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultMarThnIniExport = db2_exec($conn1, $qMarThnIniExport);
            $rowMarThnIniExport    = db2_fetch_assoc($resultMarThnIniExport);
            $dataMarThnIniExport   = $rowMarThnIniExport['QTY'];
          // EXPORT
          
          // F/K
            $qMarThnIniLokalExport_fkf = "WITH QTY_BRUTO AS (
                                          SELECT
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE,
                                            SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                            SUM(i.USERSECONDARYQUANTITY) AS FKF
                                          FROM
                                            ITXVIEWKGBRUTOBONORDER2 i
                                          GROUP BY 
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE
                                        )
                                        SELECT 
                                            SUM(QTY) AS QTY
                                          FROM 
                                          (SELECT
                                            s.CODE,
                                            s3.DELIVERYDATE,
                                            ROUND(SUM(qb.KFF)) AS QTY
                                          FROM
                                            SALESORDER s
                                          LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'FKF'
                                          LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                          LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                          LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                          LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                          LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                          WHERE
                                            CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                            AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                            AND NOT s3.DELIVERYDATE IS NULL
                                            AND NOT a.VALUESTRING IS NULL
                                            AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                          GROUP BY
                                            s.CODE,
                                            s3.DELIVERYDATE)
                                          WHERE
                                            DELIVERYDATE BETWEEN '$tglAwalMar' AND '$tglAkhirMar' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultMarThnIniLokalExport_fkf = db2_exec($conn1, $qMarThnIniLokalExport_fkf);
            $rowMarThnIniLokalExport_fkf    = db2_fetch_assoc($resultMarThnIniLokalExport_fkf);
            $dataMarThnIniLokalExport_fkf   = $rowMarThnIniLokalExport_fkf['QTY'];
          // F/K
          
          // BOOKING
            $qMarThnIniBooking = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(qb.KFF)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND s.TEMPLATECODE IN ('OPN', 'TBG', 'MNB', 'DMB', 'RBG')
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalMar' AND '$tglAkhirMar' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultMarThnIniBooking = db2_exec($conn1, $qMarThnIniBooking);
            $rowMarThnIniBooking    = db2_fetch_assoc($resultMarThnIniBooking);
            $dataMarThnIniBooking   = $rowMarThnIniBooking['QTY'];
          // BOOKING
          
          // JASA
            $qMarThnIniJasa = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                  SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('CWD')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalMar' AND '$tglAkhirMar' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultMarThnIniJasa = db2_exec($conn1, $qMarThnIniJasa);
            $rowMarThnIniJasa    = db2_fetch_assoc($resultMarThnIniJasa);
            $dataMarThnIniJasa   = $rowMarThnIniJasa['QTY'];
          // JASA
          
          // PRINTING
            $qMarThnIniPrt = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF') AND NOT TRIM(SUBCODE07) = '-'
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')  
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalMar' AND '$tglAkhirMar' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultMarThnIniPrt = db2_exec($conn1, $qMarThnIniPrt);
            $rowMarThnIniPrt    = db2_fetch_assoc($resultMarThnIniPrt);
            $dataMarThnIniPrt   = $rowMarThnIniPrt['QTY'];
          // PRINTING

          // TOTAL
            $total_MarThnIni = $dataMarThnIniLokal + $dataMarThnIniExport + $dataMarThnIniLokalExport_fkf;
            $data_total_MarThnIni = $total_MarThnIni;
          // TOTAL

          // %LOKAL
            $a_MarThnIni = $dataMarThnIniLokal + $dataMarThnIniLokalExport_fkf + $dataMarThnIniPrt;
            $b_MarThnIni = $data_total_MarThnIni;
            $data_PersentageLokal_MarThnIni = $b_MarThnIni == 0 ? 0 : ($a_MarThnIni / $b_MarThnIni * 100);
          // %LOKAL

          // %EXPORT
            $data_PersentageExport_MarThnIni = $data_total_MarThnIni == 0 ? 0 : ($dataMarThnIniExport / $data_total_MarThnIni * 100);
          // %EXPORT

          // KIRIM
            $qQtyMarPengirimanThnIni = "SELECT 
                                              SUM(qty) AS qty
                                            FROM
                                              [nowprd].[tbl_summary] 
                                            WHERE
                                              tanggal BETWEEN '$tglAwalMar' AND '$tglAkhirMar'";
            $resultQtyMarPengirimanThnIni      = sqlsrv_query($con_nowprd, $qQtyMarPengirimanThnIni);
            $rowQtyMarPengirimanThnIni         = sqlsrv_fetch_array($resultQtyMarPengirimanThnIni);
            $dataQtyMarPengirimanThnIniKirim   = $rowQtyMarPengirimanThnIni['qty'];
          // KIRIM
        ?>
        <tr>
          <td>MARET</td>
          <td><?= number_format($dataMarThnIniLokal); ?></td>
          <td><?= number_format($data_PersentageLokal_MarThnIni); ?> %</td>
          <td><?= number_format($dataMarThnIniExport); ?></td>
          <td><?= number_format($data_PersentageExport_MarThnIni); ?> %</td>
          <td><?= number_format($dataMarThnIniLokalExport_fkf); ?></td>
          <td><?= number_format($data_total_MarThnIni); ?></td>
          <td style="background-color: yellow;"><?= number_format($dataMarThnIniJasa); ?></td>
          <td><?= number_format($dataMarThnIniBooking); ?></td>
          <td><?= number_format($dataMarThnIniPrt); ?></td>
          <td><?= number_format($dataQtyMarPengirimanThnIniKirim); ?></td>
          <td>...</td>
        </tr>
      <!-- MARET -->
      
      <!-- APRIL -->
        <?php
          $tahunInput = date('Y', strtotime($tglInput));

          // Bangun awal dan akhir bulan Desember tahun sebelumnya
          $tglAwalApr     = $tahunInput . '-04-01';
          $tglAkhirApr    = $tahunInput . '-04-30';

          // LOKAL
            $qAprThnIniLokal = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('DOM', 'SAM')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalApr' AND '$tglAkhirApr' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultAprThnIniLokal = db2_exec($conn1, $qAprThnIniLokal);
            $rowAprThnIniLokal    = db2_fetch_assoc($resultAprThnIniLokal);
            $dataAprThnIniLokal   = $rowAprThnIniLokal['QTY'];
          // LOKAL
          
          // EXPORT
            $qAprThnIniExport = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('EXP', 'SME')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalApr' AND '$tglAkhirApr' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultAprThnIniExport = db2_exec($conn1, $qAprThnIniExport);
            $rowAprThnIniExport    = db2_fetch_assoc($resultAprThnIniExport);
            $dataAprThnIniExport   = $rowAprThnIniExport['QTY'];
          // EXPORT
          
          // F/K
            $qAprThnIniLokalExport_fkf = "WITH QTY_BRUTO AS (
                                            SELECT
                                              i.CODE,
                                              i.ORIGDLVSALORDLINESALORDERCODE,
                                              i.ORIGDLVSALORDERLINEORDERLINE,
                                              SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                              SUM(i.USERSECONDARYQUANTITY) AS FKF
                                            FROM
                                              ITXVIEWKGBRUTOBONORDER2 i
                                            GROUP BY 
                                              i.CODE,
                                              i.ORIGDLVSALORDLINESALORDERCODE,
                                              i.ORIGDLVSALORDERLINEORDERLINE
                                          )
                                          SELECT 
                                            SUM(QTY) AS QTY
                                          FROM 
                                          (SELECT
                                            s.CODE,
                                            s3.DELIVERYDATE,
                                            ROUND(SUM(qb.KFF)) AS QTY
                                          FROM
                                            SALESORDER s
                                          LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'FKF'
                                          LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                          LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                          LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                          LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                          LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                          WHERE
                                            CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                            AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                            AND NOT s3.DELIVERYDATE IS NULL
                                            AND NOT a.VALUESTRING IS NULL
                                            AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                          GROUP BY
                                            s.CODE,
                                            s3.DELIVERYDATE)
                                          WHERE
                                            DELIVERYDATE BETWEEN '$tglAwalApr' AND '$tglAkhirApr' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultAprThnIniLokalExport_fkf = db2_exec($conn1, $qAprThnIniLokalExport_fkf);
            $rowAprThnIniLokalExport_fkf    = db2_fetch_assoc($resultAprThnIniLokalExport_fkf);
            $dataAprThnIniLokalExport_fkf   = $rowAprThnIniLokalExport_fkf['QTY'];
          // F/K
          
          // BOOKING
            $qAprThnIniBooking = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(qb.KFF)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND s.TEMPLATECODE IN ('OPN', 'TBG', 'MNB', 'DMB', 'RBG')
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalApr' AND '$tglAkhirApr' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultAprThnIniBooking = db2_exec($conn1, $qAprThnIniBooking);
            $rowAprThnIniBooking    = db2_fetch_assoc($resultAprThnIniBooking);
            $dataAprThnIniBooking   = $rowAprThnIniBooking['QTY'];
          // BOOKING
          
          // JASA
            $qAprThnIniJasa = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD')
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalApr' AND '$tglAkhirApr' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultAprThnIniJasa = db2_exec($conn1, $qAprThnIniJasa);
            $rowAprThnIniJasa    = db2_fetch_assoc($resultAprThnIniJasa);
            $dataAprThnIniJasa   = $rowAprThnIniJasa['QTY'];
          // JASA
          
          // PRINTING
            $qAprThnIniPrt = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF') AND NOT TRIM(SUBCODE07) = '-'
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')  
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalApr' AND '$tglAkhirApr' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultAprThnIniPrt = db2_exec($conn1, $qAprThnIniPrt);
            $rowAprThnIniPrt    = db2_fetch_assoc($resultAprThnIniPrt);
            $dataAprThnIniPrt   = $rowAprThnIniPrt['QTY'];
          // PRINTING

          // TOTAL
            $total_AprThnIni = $dataAprThnIniLokal + $dataAprThnIniExport + $dataAprThnIniLokalExport_fkf;
            $data_total_AprThnIni = $total_AprThnIni;
          // TOTAL

          // %LOKAL
            $a_AprThnIni = $dataAprThnIniLokal + $dataAprThnIniLokalExport_fkf + $dataAprThnIniPrt;
            $b_AprThnIni = $data_total_AprThnIni;
            $data_PersentageLokal_AprThnIni = $b_AprThnIni == 0 ? 0 : ($a_AprThnIni / $b_AprThnIni * 100);
          // %LOKAL

          // %EXPORT
            $data_PersentageExport_AprThnIni = $data_total_AprThnIni == 0 ? 0 : ($dataAprThnIniExport / $data_total_AprThnIni * 100);
          // %EXPORT

          // KIRIM
            $qQtyAprPengirimanThnIni = "SELECT 
                                              SUM(qty) AS qty
                                            FROM
                                              [nowprd].[tbl_summary] 
                                            WHERE
                                              tanggal BETWEEN '$tglAwalApr' AND '$tglAkhirApr'";
            $resultQtyAprPengirimanThnIni      = sqlsrv_query($con_nowprd, $qQtyAprPengirimanThnIni);
            $rowQtyAprPengirimanThnIni         = sqlsrv_fetch_array($resultQtyAprPengirimanThnIni);
            $dataQtyAprPengirimanThnIniKirim   = $rowQtyAprPengirimanThnIni['qty'];
          // KIRIM
        ?>
        <tr>
          <td>APRIL</td>
          <td><?= number_format($dataAprThnIniLokal); ?></td>
          <td><?= number_format($data_PersentageLokal_AprThnIni); ?> %</td>
          <td><?= number_format($dataAprThnIniExport); ?></td>
          <td><?= number_format($data_PersentageExport_AprThnIni); ?> %</td>
          <td><?= number_format($dataAprThnIniLokalExport_fkf); ?></td>
          <td><?= number_format($data_total_AprThnIni); ?></td>
          <td style="background-color: yellow;"><?= number_format($dataAprThnIniJasa); ?></td>
          <td><?= number_format($dataAprThnIniBooking); ?></td>
          <td><?= number_format($dataAprThnIniPrt); ?></td>
          <td><?= number_format($dataQtyAprPengirimanThnIniKirim); ?></td>
          <td>...</td>
        </tr>
      <!-- APRIL -->
      
      <!-- MEI -->
        <?php
          $tahunInput = date('Y', strtotime($tglInput));

          // Bangun awal dan akhir bulan Desember tahun sebelumnya
          $tglAwalMei     = $tahunInput . '-05-01';
          $tglAkhirMei    = $tahunInput . '-05-31';

          // LOKAL
            $qMeiThnIniLokal = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('DOM', 'SAM')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalMei' AND '$tglAkhirMei' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultMeiThnIniLokal = db2_exec($conn1, $qMeiThnIniLokal);
            $rowMeiThnIniLokal    = db2_fetch_assoc($resultMeiThnIniLokal);
            $dataMeiThnIniLokal   = $rowMeiThnIniLokal['QTY'];
          // LOKAL
          
          // EXPORT
            $qMeiThnIniExport = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('EXP', 'SME')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalMei' AND '$tglAkhirMei' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultMeiThnIniExport = db2_exec($conn1, $qMeiThnIniExport);
            $rowMeiThnIniExport    = db2_fetch_assoc($resultMeiThnIniExport);
            $dataMeiThnIniExport   = $rowMeiThnIniExport['QTY'];
          // EXPORT
          
          // F/K
            $qMeiThnIniLokalExport_fkf = "WITH QTY_BRUTO AS (
                                            SELECT
                                              i.CODE,
                                              i.ORIGDLVSALORDLINESALORDERCODE,
                                              i.ORIGDLVSALORDERLINEORDERLINE,
                                              SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                              SUM(i.USERSECONDARYQUANTITY) AS FKF
                                            FROM
                                              ITXVIEWKGBRUTOBONORDER2 i
                                            GROUP BY 
                                              i.CODE,
                                              i.ORIGDLVSALORDLINESALORDERCODE,
                                              i.ORIGDLVSALORDERLINEORDERLINE
                                          )
                                          SELECT 
                                            SUM(QTY) AS QTY
                                          FROM 
                                          (SELECT
                                            s.CODE,
                                            s3.DELIVERYDATE,
                                            ROUND(SUM(qb.KFF)) AS QTY
                                          FROM
                                            SALESORDER s
                                          LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'FKF'
                                          LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                          LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                          LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                          LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                          LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                          WHERE
                                            CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                            AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                            AND NOT s3.DELIVERYDATE IS NULL
                                            AND NOT a.VALUESTRING IS NULL
                                            AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                          GROUP BY
                                            s.CODE,
                                            s3.DELIVERYDATE)
                                          WHERE
                                            DELIVERYDATE BETWEEN '$tglAwalMei' AND '$tglAkhirMei' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultMeiThnIniLokalExport_fkf = db2_exec($conn1, $qMeiThnIniLokalExport_fkf);
            $rowMeiThnIniLokalExport_fkf    = db2_fetch_assoc($resultMeiThnIniLokalExport_fkf);
            $dataMeiThnIniLokalExport_fkf   = $rowMeiThnIniLokalExport_fkf['QTY'];
          // F/K
          
          // BOOKING
            $qMeiThnIniBooking = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(qb.KFF)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND s.TEMPLATECODE IN ('OPN', 'TBG', 'MNB', 'DMB', 'RBG')
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalMei' AND '$tglAkhirMei' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultMeiThnIniBooking = db2_exec($conn1, $qMeiThnIniBooking);
            $rowMeiThnIniBooking    = db2_fetch_assoc($resultMeiThnIniBooking);
            $dataMeiThnIniBooking   = $rowMeiThnIniBooking['QTY'];
          // BOOKING
          
          // JASA
            $qMeiThnIniJasa = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('CWD')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalMei' AND '$tglAkhirMei' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultMeiThnIniJasa = db2_exec($conn1, $qMeiThnIniJasa);
            $rowMeiThnIniJasa    = db2_fetch_assoc($resultMeiThnIniJasa);
            $dataMeiThnIniJasa   = $rowMeiThnIniJasa['QTY'];
          // JASA
          
          // PRINTING
            $qMeiThnIniPrt = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF') AND NOT TRIM(SUBCODE07) = '-'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')  
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalMei' AND '$tglAkhirMei' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultMeiThnIniPrt = db2_exec($conn1, $qMeiThnIniPrt);
            $rowMeiThnIniPrt    = db2_fetch_assoc($resultMeiThnIniPrt);
            $dataMeiThnIniPrt   = $rowMeiThnIniPrt['QTY'];
          // PRINTING

          // TOTAL
            $total_MeiThnIni = $dataMeiThnIniLokal + $dataMeiThnIniExport + $dataMeiThnIniLokalExport_fkf;
            $data_total_MeiThnIni = $total_MeiThnIni;
          // TOTAL

          // %LOKAL
            $a_MeiThnIni = $dataMeiThnIniLokal + $dataMeiThnIniLokalExport_fkf + $dataMeiThnIniPrt;
            $b_MeiThnIni = $data_total_MeiThnIni;
            $data_PersentageLokal_MeiThnIni = $b_MeiThnIni == 0 ? 0 : ($a_MeiThnIni / $b_MeiThnIni * 100);
          // %LOKAL

          // %EXPORT
            $data_PersentageExport_MeiThnIni = $data_total_MeiThnIni == 0 ? 0 : ($dataMeiThnIniExport / $data_total_MeiThnIni * 100);
          // %EXPORT

          // KIRIM
            $qQtyMeiPengirimanThnIni = "SELECT 
                                              SUM(qty) AS qty
                                            FROM
                                              [nowprd].[tbl_summary] 
                                            WHERE
                                              tanggal BETWEEN '$tglAwalMei' AND '$tglAkhirMei'";
            $resultQtyMeiPengirimanThnIni      = sqlsrv_query($con_nowprd, $qQtyMeiPengirimanThnIni);
            $rowQtyMeiPengirimanThnIni         = sqlsrv_fetch_array($resultQtyMeiPengirimanThnIni);
            $dataQtyMeiPengirimanThnIniKirim   = $rowQtyMeiPengirimanThnIni['qty'];
          // KIRIM
        ?>
        <tr>
          <td>MEI</td>
          <td><?= number_format($dataMeiThnIniLokal); ?></td>
          <td><?= number_format($data_PersentageLokal_MeiThnIni); ?> %</td>
          <td><?= number_format($dataMeiThnIniExport); ?></td>
          <td><?= number_format($data_PersentageExport_MeiThnIni); ?> %</td>
          <td><?= number_format($dataMeiThnIniLokalExport_fkf); ?></td>
          <td><?= number_format($data_total_MeiThnIni); ?></td>
          <td style="background-color: yellow;"><?= number_format($dataMeiThnIniJasa); ?></td>
          <td><?= number_format($dataMeiThnIniBooking); ?></td>
          <td><?= number_format($dataMeiThnIniPrt); ?></td>
          <td><?= number_format($dataQtyMeiPengirimanThnIniKirim); ?></td>
          <td>...</td>
        </tr>
      <!-- MEI -->
      
      <!-- JUNI -->
        <?php
          $tahunInput = date('Y', strtotime($tglInput));

          // Bangun awal dan akhir bulan Desember tahun sebelumnya
          $tglAwalJun     = $tahunInput . '-06-01';
          $tglAkhirJun    = $tahunInput . '-06-30';

          // LOKAL
            $qJunThnIniLokal = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('DOM', 'SAM')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalJun' AND '$tglAkhirJun' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJunThnIniLokal = db2_exec($conn1, $qJunThnIniLokal);
            $rowJunThnIniLokal    = db2_fetch_assoc($resultJunThnIniLokal);
            $dataJunThnIniLokal   = $rowJunThnIniLokal['QTY'];
          // LOKAL
          
          // EXPORT
            $qJunThnIniExport = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('EXP', 'SME')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalJun' AND '$tglAkhirJun' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJunThnIniExport = db2_exec($conn1, $qJunThnIniExport);
            $rowJunThnIniExport    = db2_fetch_assoc($resultJunThnIniExport);
            $dataJunThnIniExport   = $rowJunThnIniExport['QTY'];
          // EXPORT
          
          // F/K
            $qJunThnIniLokalExport_fkf = "WITH QTY_BRUTO AS (
                                          SELECT
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE,
                                            SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                            SUM(i.USERSECONDARYQUANTITY) AS FKF
                                          FROM
                                            ITXVIEWKGBRUTOBONORDER2 i
                                          GROUP BY 
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE
                                        )
                                        SELECT 
                                          SUM(QTY) AS QTY
                                        FROM 
                                        (SELECT
                                          s.CODE,
                                          s3.DELIVERYDATE,
                                          ROUND(SUM(qb.KFF)) AS QTY
                                        FROM
                                          SALESORDER s
                                        LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'FKF'
                                        LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                        LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                        LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                        LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                        WHERE
                                          CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                          AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                          AND NOT s3.DELIVERYDATE IS NULL
                                          AND NOT a.VALUESTRING IS NULL
                                          AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                        GROUP BY
                                          s.CODE,
                                          s3.DELIVERYDATE)
                                        WHERE
                                          DELIVERYDATE BETWEEN '$tglAwalJun' AND '$tglAkhirJun' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJunThnIniLokalExport_fkf = db2_exec($conn1, $qJunThnIniLokalExport_fkf);
            $rowJunThnIniLokalExport_fkf    = db2_fetch_assoc($resultJunThnIniLokalExport_fkf);
            $dataJunThnIniLokalExport_fkf   = $rowJunThnIniLokalExport_fkf['QTY'];
          // F/K
          
          // BOOKING
            $qJunThnIniBooking = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(qb.KFF)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND s.TEMPLATECODE IN ('OPN', 'TBG', 'MNB', 'DMB', 'RBG')
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalJun' AND '$tglAkhirJun' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJunThnIniBooking = db2_exec($conn1, $qJunThnIniBooking);
            $rowJunThnIniBooking    = db2_fetch_assoc($resultJunThnIniBooking);
            $dataJunThnIniBooking   = $rowJunThnIniBooking['QTY'];
          // BOOKING
          
          // JASA
            $qJunThnIniJasa = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('CWD')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalJun' AND '$tglAkhirJun' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJunThnIniJasa = db2_exec($conn1, $qJunThnIniJasa);
            $rowJunThnIniJasa    = db2_fetch_assoc($resultJunThnIniJasa);
            $dataJunThnIniJasa   = $rowJunThnIniJasa['QTY'];
          // JASA
          
          // PRINTING
            $qJunThnIniPrt = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF') AND NOT TRIM(SUBCODE07) = '-'
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')  
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalJun' AND '$tglAkhirJun' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJunThnIniPrt = db2_exec($conn1, $qJunThnIniPrt);
            $rowJunThnIniPrt    = db2_fetch_assoc($resultJunThnIniPrt);
            $dataJunThnIniPrt   = $rowJunThnIniPrt['QTY'];
          // PRINTING

          // TOTAL
            $total_JunThnIni = $dataJunThnIniLokal + $dataJunThnIniExport + $dataJunThnIniLokalExport_fkf;
            $data_total_JunThnIni = $total_JunThnIni;
          // TOTAL

          // %LOKAL
            $a_JunThnIni = $dataJunThnIniLokal + $dataJunThnIniLokalExport_fkf + $dataJunThnIniPrt;
            $b_JunThnIni = $data_total_JunThnIni;
            $data_PersentageLokal_JunThnIni = $data_total_JulThb_JunThnIninIni == 0 ? 0 : ($a_JunThnIni / $b_JunThnIni * 100);
          // %LOKAL

          // %EXPORT
            $data_PersentageExport_JunThnIni = $data_total_JunThnIni == 0 ? 0 : ($dataJunThnIniExport / $data_total_JunThnIni * 100);
          // %EXPORT

          // KIRIM
            $qQtyJunPengirimanThnIni = "SELECT 
                                              SUM(qty) AS qty
                                            FROM
                                              [nowprd].[tbl_summary] 
                                            WHERE
                                              tanggal BETWEEN '$tglAwalJun' AND '$tglAkhirJun'";
            $resultQtyJunPengirimanThnIni      = sqlsrv_query($con_nowprd, $qQtyJunPengirimanThnIni);
            $rowQtyJunPengirimanThnIni         = sqlsrv_fetch_array($resultQtyJunPengirimanThnIni);
            $dataQtyJunPengirimanThnIniKirim   = $rowQtyJunPengirimanThnIni['qty'];
          // KIRIM
        ?>
        <tr>
          <td>JUNI</td>
          <td><?= number_format($dataJunThnIniLokal); ?></td>
          <td><?= number_format($data_PersentageLokal_JunThnIni); ?> %</td>
          <td><?= number_format($dataJunThnIniExport); ?></td>
          <td><?= number_format($data_PersentageExport_JunThnIni); ?> %</td>
          <td><?= number_format($dataJunThnIniLokalExport_fkf); ?></td>
          <td><?= number_format($data_total_JunThnIni); ?></td>
          <td style="background-color: yellow;"><?= number_format($dataJunThnIniJasa); ?></td>
          <td><?= number_format($dataJunThnIniBooking); ?></td>
          <td><?= number_format($dataJunThnIniPrt); ?></td>
          <td><?= number_format($dataQtyJunPengirimanThnIniKirim); ?></td>
          <td>...</td>
        </tr>
      <!-- JUNI -->
      
      <!-- JULI -->
        <?php
          $tahunInput = date('Y', strtotime($tglInput));

          // Bangun awal dan akhir bulan Desember tahun sebelumnya
          $tglAwalJul     = $tahunInput . '-07-01';
          $tglAkhirJul    = $tahunInput . '-07-31';

          // LOKAL
            $qJulThnIniLokal = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('DOM', 'SAM')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalJul' AND '$tglAkhirJul' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJulThnIniLokal = db2_exec($conn1, $qJulThnIniLokal);
            $rowJulThnIniLokal    = db2_fetch_assoc($resultJulThnIniLokal);
            $dataJulThnIniLokal   = $rowJulThnIniLokal['QTY'];
          // LOKAL
          
          // EXPORT
            $qJulThnIniExport = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('EXP', 'SME')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalJul' AND '$tglAkhirJul' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJulThnIniExport = db2_exec($conn1, $qJulThnIniExport);
            $rowJulThnIniExport    = db2_fetch_assoc($resultJulThnIniExport);
            $dataJulThnIniExport   = $rowJulThnIniExport['QTY'];
          // EXPORT
          
          // F/K
            $qJulThnIniLokalExport_fkf = "WITH QTY_BRUTO AS (
                                          SELECT
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE,
                                            SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                            SUM(i.USERSECONDARYQUANTITY) AS FKF
                                          FROM
                                            ITXVIEWKGBRUTOBONORDER2 i
                                          GROUP BY 
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE
                                        )
                                        SELECT 
                                          SUM(QTY) AS QTY
                                        FROM 
                                        (SELECT
                                          s.CODE,
                                          s3.DELIVERYDATE,
                                          ROUND(SUM(qb.KFF)) AS QTY
                                        FROM
                                          SALESORDER s
                                        LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'FKF'
                                        LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                        LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                        LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                        LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                        WHERE
                                          CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                          AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                          AND NOT s3.DELIVERYDATE IS NULL
                                          AND NOT a.VALUESTRING IS NULL
                                          AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                        GROUP BY
                                          s.CODE,
                                          s3.DELIVERYDATE)
                                        WHERE
                                          DELIVERYDATE BETWEEN '$tglAwalJul' AND '$tglAkhirJul' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJulThnIniLokalExport_fkf = db2_exec($conn1, $qJulThnIniLokalExport_fkf);
            $rowJulThnIniLokalExport_fkf    = db2_fetch_assoc($resultJulThnIniLokalExport_fkf);
            $dataJulThnIniLokalExport_fkf   = $rowJulThnIniLokalExport_fkf['QTY'];
          // F/K
          
          // BOOKING
            $qJulThnIniBooking = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(qb.KFF)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND s.TEMPLATECODE IN ('OPN' , 'TBG', 'MNB', 'DMB', 'RBG')
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalJul' AND '$tglAkhirJul' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJulThnIniBooking = db2_exec($conn1, $qJulThnIniBooking);
            $rowJulThnIniBooking    = db2_fetch_assoc($resultJulThnIniBooking);
            $dataJulThnIniBooking   = $rowJulThnIniBooking['QTY'];
          // BOOKING
          
          // JASA
            $qJulThnIniJasa = "WITH QTY_BRUTO AS (
                                  SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD')
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalJul' AND '$tglAkhirJul' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJulThnIniJasa = db2_exec($conn1, $qJulThnIniJasa);
            $rowJulThnIniJasa    = db2_fetch_assoc($resultJulThnIniJasa);
            $dataJulThnIniJasa   = $rowJulThnIniJasa['QTY'];
          // JASA
          
          // PRINTING
            $qJulThnIniPrt = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF') AND NOT TRIM(SUBCODE07) = '-'
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')  
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalJul' AND '$tglAkhirJul' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultJulThnIniPrt = db2_exec($conn1, $qJulThnIniPrt);
            $rowJulThnIniPrt    = db2_fetch_assoc($resultJulThnIniPrt);
            $dataJulThnIniPrt   = $rowJulThnIniPrt['QTY'];
          // PRINTING

          // TOTAL
            $total_JulThnIni = $dataJulThnIniLokal + $dataJulThnIniExport + $dataJulThnIniLokalExport_fkf;
            $data_total_JulThnIni = $total_JulThnIni;
          // TOTAL

          // %LOKAL
            $a_JulThnIni = $dataJulThnIniLokal + $dataJulThnIniLokalExport_fkf + $dataJulThnIniPrt;
            $b_JulThnIni = $data_total_JulThnIni;
            $data_PersentageLokal_JulThnIni = $b_JulThnIni == 0 ? 0 : ($a_JulThnIni / $b_JulThnIni * 100);
          // %LOKAL

          // %EXPORT
            $data_PersentageExport_JulThnIni = $data_total_JulThnIni == 0 ? 0 : ($dataJulThnIniExport / $data_total_JulThnIni * 100);
          // %EXPORT

          // KIRIM
            $qQtyJulPengirimanThnIni = "SELECT 
                                              SUM(qty) AS qty
                                            FROM
                                              [nowprd].[tbl_summary] 
                                            WHERE
                                              tanggal BETWEEN '$tglAwalJul' AND '$tglAkhirJul'";
            $resultQtyJulPengirimanThnIni      = sqlsrv_query($con_nowprd, $qQtyJulPengirimanThnIni);
            $rowQtyJulPengirimanThnIni         = sqlsrv_fetch_array($resultQtyJulPengirimanThnIni);
            $dataQtyJulPengirimanThnIniKirim   = $rowQtyJulPengirimanThnIni['qty'];
          // KIRIM
        ?>
        <tr>
          <td>JULI</td>
          <td><?= number_format($dataJulThnIniLokal); ?></td>
          <td><?= number_format($data_PersentageLokal_JulThnIni); ?> %</td>
          <td><?= number_format($dataJulThnIniExport); ?></td>
          <td><?= number_format($data_PersentageExport_JulThnIni); ?> %</td>
          <td><?= number_format($dataJulThnIniLokalExport_fkf); ?></td>
          <td><?= number_format($data_total_JulThnIni); ?></td>
          <td style="background-color: yellow;"><?= number_format($dataJulThnIniJasa); ?></td>
          <td><?= number_format($dataJulThnIniBooking); ?></td>
          <td><?= number_format($dataJulThnIniPrt); ?></td>
          <td><?= number_format($dataQtyJulPengirimanThnIniKirim); ?></td>
          <td>...</td>
        </tr>
      <!-- JULI -->
      
      <!-- AGUSTUS -->
        <?php
          $tahunInput = date('Y', strtotime($tglInput));

          // Bangun awal dan akhir bulan Desember tahun sebelumnya
          $tglAwalAgs     = $tahunInput . '-08-01';
          $tglAkhirAgs    = $tahunInput . '-08-31';

          // LOKAL
            $qAgsThnIniLokal = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'   
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('DOM', 'SAM')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalAgs' AND '$tglAkhirAgs' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultAgsThnIniLokal = db2_exec($conn1, $qAgsThnIniLokal);
            $rowAgsThnIniLokal    = db2_fetch_assoc($resultAgsThnIniLokal);
            $dataAgsThnIniLokal   = $rowAgsThnIniLokal['QTY'];
          // LOKAL
          
          // EXPORT
            $qAgsThnIniExport = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('EXP', 'SME')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalAgs' AND '$tglAkhirAgs' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultAgsThnIniExport = db2_exec($conn1, $qAgsThnIniExport);
            $rowAgsThnIniExport    = db2_fetch_assoc($resultAgsThnIniExport);
            $dataAgsThnIniExport   = $rowAgsThnIniExport['QTY'];
          // EXPORT
          
          // F/K
            $qAgsThnIniLokalExport_fkf = "WITH QTY_BRUTO AS (
                                          SELECT
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE,
                                            SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                            SUM(i.USERSECONDARYQUANTITY) AS FKF
                                          FROM
                                            ITXVIEWKGBRUTOBONORDER2 i
                                          GROUP BY 
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE
                                        )
                                        SELECT 
                                          SUM(QTY) AS QTY
                                        FROM 
                                        (SELECT
                                          s.CODE,
                                          s3.DELIVERYDATE,
                                          ROUND(SUM(qb.KFF)) AS QTY
                                        FROM
                                          SALESORDER s
                                        LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'FKF'
                                        LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                        LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                        LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                        LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                        WHERE
                                          CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                          AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                          AND NOT s3.DELIVERYDATE IS NULL
                                          AND NOT a.VALUESTRING IS NULL
                                          AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                        GROUP BY
                                          s.CODE,
                                          s3.DELIVERYDATE)
                                        WHERE
                                          DELIVERYDATE BETWEEN '$tglAwalAgs' AND '$tglAkhirAgs' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultAgsThnIniLokalExport_fkf = db2_exec($conn1, $qAgsThnIniLokalExport_fkf);
            $rowAgsThnIniLokalExport_fkf    = db2_fetch_assoc($resultAgsThnIniLokalExport_fkf);
            $dataAgsThnIniLokalExport_fkf   = $rowAgsThnIniLokalExport_fkf['QTY'];
          // F/K
          
          // BOOKING
            $qAgsThnIniBooking = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(qb.KFF)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND s.TEMPLATECODE IN ('OPN' , 'TBG', 'MNB', 'DMB', 'RBG')
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalAgs' AND '$tglAkhirAgs' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultAgsThnIniBooking = db2_exec($conn1, $qAgsThnIniBooking);
            $rowAgsThnIniBooking    = db2_fetch_assoc($resultAgsThnIniBooking);
            $dataAgsThnIniBooking   = $rowAgsThnIniBooking['QTY'];
          // BOOKING
          
          // JASA
            $qAgsThnIniJasa = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD')
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalAgs' AND '$tglAkhirAgs' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultAgsThnIniJasa = db2_exec($conn1, $qAgsThnIniJasa);
            $rowAgsThnIniJasa    = db2_fetch_assoc($resultAgsThnIniJasa);
            $dataAgsThnIniJasa   = $rowAgsThnIniJasa['QTY'];
          // JASA
          
          // PRINTING
            $qAgsThnIniPrt = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF') AND NOT TRIM(SUBCODE07) = '-'
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')  
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalAgs' AND '$tglAkhirAgs' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultAgsThnIniPrt = db2_exec($conn1, $qAgsThnIniPrt);
            $rowAgsThnIniPrt    = db2_fetch_assoc($resultAgsThnIniPrt);
            $dataAgsThnIniPrt   = $rowAgsThnIniPrt['QTY'];
          // PRINTING

          // TOTAL
            $total_AgsThnIni = $dataAgsThnIniLokal + $dataAgsThnIniExport + $dataAgsThnIniLokalExport_fkf;
            $data_total_AgsThnIni = $total_AgsThnIni;
          // TOTAL

          // %LOKAL
            $a_AgsThnIni = $dataAgsThnIniLokal + $dataAgsThnIniLokalExport_fkf + $dataAgsThnIniPrt;
            $b_AgsThnIni = $data_total_AgsThnIni;
            $data_PersentageLokal_AgsThnIni = $b_AgsThnIni == 0 ? 0 : ($a_AgsThnIni / $b_AgsThnIni * 100);

          // %LOKAL

          // %EXPORT
            $data_PersentageExport_AgsThnIni = $data_total_AgsThnIni == 0 ? 0 : ($dataAgsThnIniExport / $data_total_AgsThnIni * 100);
          // %EXPORT

          // KIRIM
            $qQtyAgsPengirimanThnIni = "SELECT 
                                              SUM(qty) AS qty
                                            FROM
                                              [nowprd].[tbl_summary] 
                                            WHERE
                                              tanggal BETWEEN '$tglAwalAgs' AND '$tglAkhirAgs'";
            $resultQtyAgsPengirimanThnIni      = sqlsrv_query($con_nowprd, $qQtyAgsPengirimanThnIni);
            $rowQtyAgsPengirimanThnIni         = sqlsrv_fetch_array($resultQtyAgsPengirimanThnIni);
            $dataQtyAgsPengirimanThnIniKirim   = $rowQtyAgsPengirimanThnIni['qty'];
          // KIRIM
        ?>
        <tr>
          <td>AGUSTUS</td>
          <td><?= number_format($dataAgsThnIniLokal); ?></td>
          <td><?= number_format($data_PersentageLokal_AgsThnIni); ?> %</td>
          <td><?= number_format($dataAgsThnIniExport); ?></td>
          <td><?= number_format($data_PersentageExport_AgsThnIni); ?> %</td>
          <td><?= number_format($dataAgsThnIniLokalExport_fkf); ?></td>
          <td><?= number_format($data_total_AgsThnIni); ?></td>
          <td style="background-color: yellow;"><?= number_format($dataAgsThnIniJasa); ?></td>
          <td><?= number_format($dataAgsThnIniBooking); ?></td>
          <td><?= number_format($dataAgsThnIniPrt); ?></td>
          <td><?= number_format($dataQtyAgsPengirimanThnIniKirim); ?></td>
          <td>...</td>
        </tr>
      <!-- AGUSTUS -->
      
      <!-- SEPTEMBER -->
        <?php
          $tahunInput = date('Y', strtotime($tglInput));

          // Bangun awal dan akhir bulan Desember tahun sebelumnya
          $tglAwalSept     = $tahunInput . '-09-01';
          $tglAkhirSept    = $tahunInput . '-09-30';

          // LOKAL
            $qSeptThnIniLokal = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('DOM', 'SAM')
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalSept' AND '$tglAkhirSept' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultSeptThnIniLokal = db2_exec($conn1, $qSeptThnIniLokal);
            $rowSeptThnIniLokal    = db2_fetch_assoc($resultSeptThnIniLokal);
            $dataSeptThnIniLokal   = $rowSeptThnIniLokal['QTY'];
          // LOKAL
          
          // EXPORT
            $qSeptThnIniExport = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(qb.KFF)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND s.TEMPLATECODE IN ('EXP', 'SME')
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalSept' AND '$tglAkhirSept' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultSeptThnIniExport = db2_exec($conn1, $qSeptThnIniExport);
            $rowSeptThnIniExport    = db2_fetch_assoc($resultSeptThnIniExport);
            $dataSeptThnIniExport   = $rowSeptThnIniExport['QTY'];
          // EXPORT
          
          // F/K
            $qSeptThnIniLokalExport_fkf = "WITH QTY_BRUTO AS (
                                          SELECT
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE,
                                            SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                            SUM(i.USERSECONDARYQUANTITY) AS FKF
                                          FROM
                                            ITXVIEWKGBRUTOBONORDER2 i
                                          GROUP BY 
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE
                                        )
                                        SELECT 
                                          SUM(QTY) AS QTY
                                        FROM 
                                        (SELECT
                                          s.CODE,
                                          s3.DELIVERYDATE,
                                          ROUND(SUM(qb.KFF)) AS QTY
                                        FROM
                                          SALESORDER s
                                        LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'FKF'
                                        LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                        LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                        LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                        LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                        WHERE
                                          CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                          AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                          AND NOT s3.DELIVERYDATE IS NULL
                                          AND NOT a.VALUESTRING IS NULL
                                          AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                        GROUP BY
                                          s.CODE,
                                          s3.DELIVERYDATE)
                                        WHERE
                                          DELIVERYDATE BETWEEN '$tglAwalSept' AND '$tglAkhirSept' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultSeptThnIniLokalExport_fkf = db2_exec($conn1, $qSeptThnIniLokalExport_fkf);
            $rowSeptThnIniLokalExport_fkf    = db2_fetch_assoc($resultSeptThnIniLokalExport_fkf);
            $dataSeptThnIniLokalExport_fkf   = $rowSeptThnIniLokalExport_fkf['QTY'];
          // F/K
          
          // BOOKING
            $qSeptThnIniBooking = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(qb.KFF)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND s.TEMPLATECODE IN ('OPN', 'TBG', 'MNB', 'DMB', 'RBG')
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalSept' AND '$tglAkhirSept' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultSeptThnIniBooking = db2_exec($conn1, $qSeptThnIniBooking);
            $rowSeptThnIniBooking    = db2_fetch_assoc($resultSeptThnIniBooking);
            $dataSeptThnIniBooking   = $rowSeptThnIniBooking['QTY'];
          // BOOKING
          
          // JASA
            $qSeptThnIniJasa = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('CWD')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalSept' AND '$tglAkhirSept' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultSeptThnIniJasa = db2_exec($conn1, $qSeptThnIniJasa);
            $rowSeptThnIniJasa    = db2_fetch_assoc($resultSeptThnIniJasa);
            $dataSeptThnIniJasa   = $rowSeptThnIniJasa['QTY'];
          // JASA
          
          // PRINTING
            $qSeptThnIniPrt = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF') AND NOT TRIM(SUBCODE07) = '-'
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')  
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalSept' AND '$tglAkhirSept' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultSeptThnIniPrt = db2_exec($conn1, $qSeptThnIniPrt);
            $rowSeptThnIniPrt    = db2_fetch_assoc($resultSeptThnIniPrt);
            $dataSeptThnIniPrt   = $rowSeptThnIniPrt['QTY'];
          // PRINTING

          // TOTAL
            $total_SeptThnIni = $dataSeptThnIniLokal + $dataSeptThnIniExport + $dataSeptThnIniLokalExport_fkf;
            $data_total_SeptThnIni = $total_SeptThnIni;
          // TOTAL

          // %LOKAL
            $a_SeptThnIni = $dataSeptThnIniLokal + $dataSeptThnIniLokalExport_fkf + $dataSeptThnIniPrt;
            $b_SeptThnIni = $data_total_SeptThnIni;
            $data_PersentageLokal_SeptThnIni = $b_SeptThnIni == 0 ? 0 : ($a_SeptThnIni / $b_SeptThnIni) * 100;
          // %LOKAL

          // %EXPORT
            $data_PersentageExport_SeptThnIni = $data_total_SeptThnIni == 0 ? 0 : ($dataSeptThnIniExport / $data_total_SeptThnIni) * 100;
          // %EXPORT

          // KIRIM
            $qQtySeptPengirimanThnIni = "SELECT 
                                              SUM(qty) AS qty
                                            FROM
                                              [nowprd].[tbl_summary] 
                                            WHERE
                                              tanggal BETWEEN '$tglAwalSept' AND '$tglAkhirSept'";
            $resultQtySeptPengirimanThnIni      = sqlsrv_query($con_nowprd, $qQtySeptPengirimanThnIni);
            $rowQtySeptPengirimanThnIni         = sqlsrv_fetch_array($resultQtySeptPengirimanThnIni);
            $dataQtySeptPengirimanThnIniKirim   = $rowQtySeptPengirimanThnIni['qty'];
          // KIRIM
        ?>
        <tr>
          <td>SEPTEMBER</td>
          <td><?= number_format($dataSeptThnIniLokal); ?></td>
          <td><?= number_format($data_PersentageLokal_SeptThnIni); ?> %</td>
          <td><?= number_format($dataSeptThnIniExport); ?></td>
          <td><?= number_format($data_PersentageExport_SeptThnIni); ?> %</td>
          <td><?= number_format($dataSeptThnIniLokalExport_fkf); ?></td>
          <td><?= number_format($data_total_SeptThnIni); ?></td>
          <td style="background-color: yellow;"><?= number_format($dataSeptThnIniJasa); ?></td>
          <td><?= number_format($dataSeptThnIniBooking); ?></td>
          <td><?= number_format($dataSeptThnIniPrt); ?></td>
          <td><?= number_format($dataQtySeptPengirimanThnIniKirim); ?></td>
          <td>...</td>
        </tr>
      <!-- SEPTEMBER -->
      
      <!-- OKTOBER -->
        <?php
          $tahunInput = date('Y', strtotime($tglInput));

          // Bangun awal dan akhir bulan Desember tahun sebelumnya
          $tglAwalOkt     = $tahunInput . '-10-01';
          $tglAkhirOkt    = $tahunInput . '-10-31';

          // LOKAL
            $qOktThnIniLokal = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('DOM', 'SAM')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalOkt' AND '$tglAkhirOkt' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultOktThnIniLokal = db2_exec($conn1, $qOktThnIniLokal);
            $rowOktThnIniLokal    = db2_fetch_assoc($resultOktThnIniLokal);
            $dataOktThnIniLokal   = $rowOktThnIniLokal['QTY'];
          // LOKAL
          
          // EXPORT
            $qOktThnIniExport = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(qb.KFF)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND s.TEMPLATECODE IN ('EXP', 'SME')
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalOkt' AND '$tglAkhirOkt' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultOktThnIniExport = db2_exec($conn1, $qOktThnIniExport);
            $rowOktThnIniExport    = db2_fetch_assoc($resultOktThnIniExport);
            $dataOktThnIniExport   = $rowOktThnIniExport['QTY'];
          // EXPORT
          
          // F/K
            $qOktThnIniLokalExport_fkf = "WITH QTY_BRUTO AS (
                                          SELECT
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE,
                                            SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                            SUM(i.USERSECONDARYQUANTITY) AS FKF
                                          FROM
                                            ITXVIEWKGBRUTOBONORDER2 i
                                          GROUP BY 
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE
                                        )
                                        SELECT 
                                          SUM(QTY) AS QTY
                                        FROM 
                                        (SELECT
                                          s.CODE,
                                          s3.DELIVERYDATE,
                                          ROUND(SUM(qb.KFF)) AS QTY
                                        FROM
                                          SALESORDER s
                                        LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'FKF'
                                        LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                        LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                        LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                        LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                        WHERE
                                          CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                          AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                          AND NOT s3.DELIVERYDATE IS NULL
                                          AND NOT a.VALUESTRING IS NULL
                                          AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                        GROUP BY
                                          s.CODE,
                                          s3.DELIVERYDATE)
                                        WHERE
                                          DELIVERYDATE BETWEEN '$tglAwalOkt' AND '$tglAkhirOkt' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultOktThnIniLokalExport_fkf = db2_exec($conn1, $qOktThnIniLokalExport_fkf);
            $rowOktThnIniLokalExport_fkf    = db2_fetch_assoc($resultOktThnIniLokalExport_fkf);
            $dataOktThnIniLokalExport_fkf   = $rowOktThnIniLokalExport_fkf['QTY'];
          // F/K
          
          // BOOKING
            $qOktThnIniBooking = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(qb.KFF)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND s.TEMPLATECODE IN ('OPN', 'TBG', 'MNB', 'DMB', 'RBG')
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalOkt' AND '$tglAkhirOkt' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultOktThnIniBooking = db2_exec($conn1, $qOktThnIniBooking);
            $rowOktThnIniBooking    = db2_fetch_assoc($resultOktThnIniBooking);
            $dataOktThnIniBooking   = $rowOktThnIniBooking['QTY'];
          // BOOKING
          
          // JASA
            $qOktThnIniJasa = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD')
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalOkt' AND '$tglAkhirOkt' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultOktThnIniJasa = db2_exec($conn1, $qOktThnIniJasa);
            $rowOktThnIniJasa    = db2_fetch_assoc($resultOktThnIniJasa);
            $dataOktThnIniJasa   = $rowOktThnIniJasa['QTY'];
          // JASA
          
          // PRINTING
            $qOktThnIniPrt = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT
                                SUM(QTY) AS QTY
                              FROM
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF') AND NOT TRIM(SUBCODE07) = '-'
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')  
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalOkt' AND '$tglAkhirOkt' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultOktThnIniPrt = db2_exec($conn1, $qOktThnIniPrt);
            $rowOktThnIniPrt    = db2_fetch_assoc($resultOktThnIniPrt);
            $dataOktThnIniPrt   = $rowOktThnIniPrt['QTY'];
          // PRINTING

          // TOTAL
            $total_OktThnIni = $dataOktThnIniLokal + $dataOktThnIniExport + $dataOktThnIniLokalExport_fkf;
            $data_total_OktThnIni = $total_OktThnIni;
          // TOTAL

          // %LOKAL
            $a_OktThnIni = $dataOktThnIniLokal + $dataOktThnIniLokalExport_fkf + $dataOktThnIniPrt;
            $b_OktThnIni = $data_total_OktThnIni;
            $data_PersentageLokal_OktThnIni = $b_OktThnIni == 0 ? 0 : ($a_OktThnIni / $b_OktThnIni) * 100;
          // %LOKAL

          // %EXPORT
            $data_PersentageExport_OktThnIni = $data_total_OktThnIni == 0 ? 0 : ($dataOktThnIniExport / $data_total_OktThnIni) * 100;
          // %EXPORT

          // KIRIM
            $qQtyOktPengirimanThnIni = "SELECT 
                                              SUM(qty) AS qty
                                            FROM
                                              [nowprd].[tbl_summary] 
                                            WHERE
                                              tanggal BETWEEN '$tglAwalOkt' AND '$tglAkhirOkt'";
            $resultQtyOktPengirimanThnIni      = sqlsrv_query($con_nowprd, $qQtyOktPengirimanThnIni);
            $rowQtyOktPengirimanThnIni         = sqlsrv_fetch_array($resultQtyOktPengirimanThnIni);
            $dataQtyOktPengirimanThnIniKirim   = $rowQtyOktPengirimanThnIni['qty'];
          // KIRIM
        ?>
        <tr>
          <td>OKTOBER</td>
          <td><?= number_format($dataOktThnIniLokal); ?></td>
          <td><?= number_format($data_PersentageLokal_OktThnIni); ?> %</td>
          <td><?= number_format($dataOktThnIniExport); ?></td>
          <td><?= number_format($data_PersentageExport_OktThnIni); ?> %</td>
          <td><?= number_format($dataOktThnIniLokalExport_fkf); ?></td>
          <td><?= number_format($data_total_OktThnIni); ?></td>
          <td style="background-color: yellow;"><?= number_format($dataOktThnIniJasa); ?></td>
          <td><?= number_format($dataOktThnIniBooking); ?></td>
          <td><?= number_format($dataOktThnIniPrt); ?></td>
          <td><?= number_format($dataQtyOktPengirimanThnIniKirim); ?></td>
          <td>...</td>
        </tr>
      <!-- OKTOBER -->
      
      <!-- NOVEMBER -->
        <?php
          $tahunInput = date('Y', strtotime($tglInput));

          // Bangun awal dan akhir bulan Desember tahun sebelumnya
          $tglAwalNov     = $tahunInput . '-11-01';
          $tglAkhirNov    = $tahunInput . '-11-30';

          // LOKAL
            $qNovThnIniLokal = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('DOM', 'SAM')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalNov' AND '$tglAkhirNov' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultNovThnIniLokal = db2_exec($conn1, $qNovThnIniLokal);
            $rowNovThnIniLokal    = db2_fetch_assoc($resultNovThnIniLokal);
            $dataNovThnIniLokal   = $rowNovThnIniLokal['QTY'];
          // LOKAL
          
          // EXPORT
            $qNovThnIniExport = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('EXP', 'SME')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalNov' AND '$tglAkhirNov' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultNovThnIniExport = db2_exec($conn1, $qNovThnIniExport);
            $rowNovThnIniExport    = db2_fetch_assoc($resultNovThnIniExport);
            $dataNovThnIniExport   = $rowNovThnIniExport['QTY'];
          // EXPORT
          
          // F/K
            $qNovThnIniLokalExport_fkf = "WITH QTY_BRUTO AS (
                                          SELECT
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE,
                                            SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                            SUM(i.USERSECONDARYQUANTITY) AS FKF
                                          FROM
                                            ITXVIEWKGBRUTOBONORDER2 i
                                          GROUP BY 
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE
                                        )
                                        SELECT 
                                          SUM(QTY) AS QTY
                                        FROM 
                                        (SELECT
                                          s.CODE,
                                          s3.DELIVERYDATE,
                                          ROUND(SUM(qb.KFF)) AS QTY
                                        FROM
                                          SALESORDER s
                                        LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'FKF'
                                        LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                        LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                        LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                        LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                        WHERE
                                          CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                          AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                          AND NOT s3.DELIVERYDATE IS NULL
                                          AND NOT a.VALUESTRING IS NULL
                                          AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                        GROUP BY
                                          s.CODE,
                                          s3.DELIVERYDATE)
                                        WHERE
                                          DELIVERYDATE BETWEEN '$tglAwalNov' AND '$tglAkhirNov' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultNovThnIniLokalExport_fkf = db2_exec($conn1, $qNovThnIniLokalExport_fkf);
            $rowNovThnIniLokalExport_fkf    = db2_fetch_assoc($resultNovThnIniLokalExport_fkf);
            $dataNovThnIniLokalExport_fkf   = $rowNovThnIniLokalExport_fkf['QTY'];
          // F/K
          
          // BOOKING
            $qNovThnIniBooking = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(qb.KFF)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND s.TEMPLATECODE IN ('OPN', 'TBG', 'MNB', 'DMB', 'RBG')
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalNov' AND '$tglAkhirNov' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultNovThnIniBooking = db2_exec($conn1, $qNovThnIniBooking);
            $rowNovThnIniBooking    = db2_fetch_assoc($resultNovThnIniBooking);
            $dataNovThnIniBooking   = $rowNovThnIniBooking['QTY'];
          // BOOKING
          
          // JASA
            $qNovThnIniJasa = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD')
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalNov' AND '$tglAkhirNov' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultNovThnIniJasa = db2_exec($conn1, $qNovThnIniJasa);
            $rowNovThnIniJasa    = db2_fetch_assoc($resultNovThnIniJasa);
            $dataNovThnIniJasa   = $rowNovThnIniJasa['QTY'];
          // JASA
          
          // PRINTING
            $qNovThnIniPrt = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF') AND NOT TRIM(SUBCODE07) = '-'
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')  
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalNov' AND '$tglAkhirNov' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultNovThnIniPrt = db2_exec($conn1, $qNovThnIniPrt);
            $rowNovThnIniPrt    = db2_fetch_assoc($resultNovThnIniPrt);
            $dataNovThnIniPrt   = $rowNovThnIniPrt['QTY'];
          // PRINTING

          // TOTAL
            $total_NovThnIni = $dataNovThnIniLokal + $dataNovThnIniExport + $dataNovThnIniLokalExport_fkf;
            $data_total_NovThnIni = $total_NovThnIni;
          // TOTAL

          // %LOKAL
            $a_NovThnIni = $dataNovThnIniLokal + $dataNovThnIniLokalExport_fkf + $dataNovThnIniPrt;
            $b_NovThnIni = $data_total_NovThnIni;
            $data_PersentageLokal_NovThnIni = $b_NovThnIni == 0 ? 0 : ($a_NovThnIni / $b_NovThnIni) * 100;
          // %LOKAL

          // %EXPORT
            $data_PersentageExport_NovThnIni = $data_total_NovThnIni == 0 ? 0 : ($dataNovThnIniExport / $data_total_NovThnIni) * 100;
          // %EXPORT

          // KIRIM
            $qQtyNovPengirimanThnIni = "SELECT 
                                              SUM(qty) AS qty
                                            FROM
                                              [nowprd].[tbl_summary] 
                                            WHERE
                                              tanggal BETWEEN '$tglAwalNov' AND '$tglAkhirNov'";
            $resultQtyNovPengirimanThnIni      = sqlsrv_query($con_nowprd, $qQtyNovPengirimanThnIni);
            $rowQtyNovPengirimanThnIni         = sqlsrv_fetch_array($resultQtyNovPengirimanThnIni);
            $dataQtyNovPengirimanThnIniKirim   = $rowQtyNovPengirimanThnIni['qty'];
          // KIRIM
        ?>
        <tr>
          <td>NOVEMBER</td>
          <td><?= number_format($dataNovThnIniLokal); ?></td>
          <td><?= number_format($data_PersentageLokal_NovThnIni); ?> %</td>
          <td><?= number_format($dataNovThnIniExport); ?></td>
          <td><?= number_format($data_PersentageExport_NovThnIni); ?> %</td>
          <td><?= number_format($dataNovThnIniLokalExport_fkf); ?></td>
          <td><?= number_format($data_total_NovThnIni); ?></td>
          <td style="background-color: yellow;"><?= number_format($dataNovThnIniJasa); ?></td>
          <td><?= number_format($dataNovThnIniBooking); ?></td>
          <td><?= number_format($dataNovThnIniPrt); ?></td>
          <td><?= number_format($dataQtyNovPengirimanThnIniKirim); ?></td>
          <td>...</td>
        </tr>
      <!-- NOVEMBER -->
      
      <!-- DESEMBER -->
        <?php
          $tahunInput = date('Y', strtotime($tglInput));

          // Bangun awal dan akhir bulan Desember tahun sebelumnya
          $tglAwalDes     = $tahunInput . '-12-01';
          $tglAkhirDes    = $tahunInput . '-12-31';

          // LOKAL
            $qDesThnIniLokal = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM 
                                (SELECT
                                  s.CODE,
                                  s3.DELIVERYDATE,
                                  ROUND(SUM(qb.KFF)) AS QTY
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                  AND s.TEMPLATECODE IN ('DOM', 'SAM')
                                  AND NOT s3.DELIVERYDATE IS NULL
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                GROUP BY
                                  s.CODE,
                                  s3.DELIVERYDATE)
                                WHERE
                                  DELIVERYDATE BETWEEN '$tglAwalDes' AND '$tglAkhirDes' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultDesThnIniLokal = db2_exec($conn1, $qDesThnIniLokal);
            $rowDesThnIniLokal    = db2_fetch_assoc($resultDesThnIniLokal);
            $dataDesThnIniLokal   = $rowDesThnIniLokal['QTY'];
          // LOKAL
          
          // EXPORT
            $qDesThnIniExport = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(qb.KFF)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND s.TEMPLATECODE IN ('EXP', 'SME')
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalDes' AND '$tglAkhirDes' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultDesThnIniExport = db2_exec($conn1, $qDesThnIniExport);
            $rowDesThnIniExport    = db2_fetch_assoc($resultDesThnIniExport);
            $dataDesThnIniExport   = $rowDesThnIniExport['QTY'];
          // EXPORT
          
          // F/K
            $qDesThnIniLokalExport_fkf = "WITH QTY_BRUTO AS (
                                          SELECT
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE,
                                            SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                            SUM(i.USERSECONDARYQUANTITY) AS FKF
                                          FROM
                                            ITXVIEWKGBRUTOBONORDER2 i
                                          GROUP BY 
                                            i.CODE,
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE
                                        )SELECT 
                                          SUM(QTY) AS QTY
                                        FROM 
                                        (SELECT
                                          s.CODE,
                                          s3.DELIVERYDATE,
                                          ROUND(SUM(qb.KFF)) AS QTY
                                        FROM
                                          SALESORDER s
                                        LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'FKF'
                                        LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                        LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                        LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                        LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                        WHERE
                                          CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                          AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                          AND NOT s3.DELIVERYDATE IS NULL
                                          AND NOT a.VALUESTRING IS NULL
                                          AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                        GROUP BY
                                          s.CODE,
                                          s3.DELIVERYDATE)
                                        WHERE
                                          DELIVERYDATE BETWEEN '$tglAwalDes' AND '$tglAkhirDes' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultDesThnIniLokalExport_fkf = db2_exec($conn1, $qDesThnIniLokalExport_fkf);
            $rowDesThnIniLokalExport_fkf    = db2_fetch_assoc($resultDesThnIniLokalExport_fkf);
            $dataDesThnIniLokalExport_fkf   = $rowDesThnIniLokalExport_fkf['QTY'];
          // F/K
          
          // BOOKING
            $qDesThnIniBooking = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(qb.KFF)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND s.TEMPLATECODE IN ('OPN', 'TBG', 'MNB', 'DMB', 'RBG')
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalDes' AND '$tglAkhirDes' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultDesThnIniBooking = db2_exec($conn1, $qDesThnIniBooking);
            $rowDesThnIniBooking    = db2_fetch_assoc($resultDesThnIniBooking);
            $dataDesThnIniBooking   = $rowDesThnIniBooking['QTY'];
          // BOOKING
          
          // JASA
            $qDesThnIniJasa = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF','FKF')
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD')
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalDes' AND '$tglAkhirDes' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultDesThnIniJasa = db2_exec($conn1, $qDesThnIniJasa);
            $rowDesThnIniJasa    = db2_fetch_assoc($resultDesThnIniJasa);
            $dataDesThnIniJasa   = $rowDesThnIniJasa['QTY'];
          // JASA
          
          // PRINTING
            $qDesThnIniPrt = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM 
                              (SELECT
                                s.CODE,
                                s3.DELIVERYDATE,
                                ROUND(SUM(qb.KFF)) AS QTY
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF') AND NOT TRIM(SUBCODE07) = '-'
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput' -- FILTER pertama untuk mencari salesorder yg dibuat
                                AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')  
                                AND NOT s3.DELIVERYDATE IS NULL
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              GROUP BY
                                s.CODE,
                                s3.DELIVERYDATE)
                              WHERE
                                DELIVERYDATE BETWEEN '$tglAwalDes' AND '$tglAkhirDes' -- FILTER kedua untuk mencari tgldelivery dari salesorder perbulan";
            $resultDesThnIniPrt = db2_exec($conn1, $qDesThnIniPrt);
            $rowDesThnIniPrt    = db2_fetch_assoc($resultDesThnIniPrt);
            $dataDesThnIniPrt   = $rowDesThnIniPrt['QTY'];
          // PRINTING

          // TOTAL
            $total_DesThnIni = $dataDesThnIniLokal + $dataDesThnIniExport + $dataDesThnIniLokalExport_fkf;
            $data_total_DesThnIni = $total_DesThnIni;
          // TOTAL

          // %LOKAL
            $a_DesThnIni = $dataDesThnIniLokal + $dataDesThnIniLokalExport_fkf + $dataDesThnIniPrt;
            $b_DesThnIni = $data_total_DesThnIni;
            $data_PersentageLokal_DesThnIni = $b_DesThnIni == 0 ? 0 : ($a_DesThnIni / $b_DesThnIni) * 100;
          // %LOKAL

          // %EXPORT
            $data_PersentageExport_DesThnIni = $data_total_DesThnIni == 0 ? 0 : ($dataDesThnIniExport / $data_total_DesThnIni) * 100;
          // %EXPORT

          // KIRIM
            $qQtyDesPengirimanThnIni = "SELECT 
                                              SUM(qty) AS qty
                                            FROM
                                              [nowprd].[tbl_summary] 
                                            WHERE
                                              tanggal BETWEEN '$tglAwalDes' AND '$tglAkhirDes'";
            $resultQtyDesPengirimanThnIni      = sqlsrv_query($con_nowprd, $qQtyDesPengirimanThnIni);
            $rowQtyDesPengirimanThnIni         = sqlsrv_fetch_array($resultQtyDesPengirimanThnIni);
            $dataQtyDesPengirimanThnIniKirim   = $rowQtyDesPengirimanThnIni['qty'];
          // KIRIM
        ?>
        <tr>
          <td>DESEMBER</td>
          <td><?= number_format($dataDesThnIniLokal); ?></td>
          <td><?= number_format($data_PersentageLokal_DesThnIni); ?> %</td>
          <td><?= number_format($dataDesThnIniExport); ?></td>
          <td><?= number_format($data_PersentageExport_DesThnIni); ?> %</td>
          <td><?= number_format($dataDesThnIniLokalExport_fkf); ?></td>
          <td><?= number_format($data_total_DesThnIni); ?></td>
          <td style="background-color: yellow;"><?= number_format($dataDesThnIniJasa); ?></td>
          <td><?= number_format($dataDesThnIniBooking); ?></td>
          <td><?= number_format($dataDesThnIniPrt); ?></td>
          <td><?= number_format($dataQtyDesPengirimanThnIniKirim); ?></td>
          <td>...</td>
        </tr>
      <!-- DESEMBER -->
    </tbody>
</table>

<table border="1" width="100%" style="border-collapse:collapse; border:1px solid #000; font-size:12px; text-align:center;">
    <thead>
      <tr>
        <th colspan="3">DELIVERY</th>
        <th>(BRUTO)</th>
        <th>AKJ</th>
        <th>SDH CELUP</th>
        <th>BLM CELUP</th>
        <th>P' BLM BLP</th>
        <th>C' BLM CLP</th>

        <th colspan="3">DELIVERY</th>
        <th>TK</th>
        <th>GREIGE READY</th>
        <th>SDH PRESET BLM CELUP</th>
        <th>BELUM PRESET BLM CELUP</th>
      </tr>
    </thead>
    <tbody>
      <?php
        // Ambil tahun dan bulan dari tanggal input
        $tahunInput       = date('Y', strtotime($tglInput));
        $bulanInput       = date('m', strtotime($tglInput));
        $bulanSekarang    = date('m', strtotime($tglInput));

        // Buat objek DateTime
        $tanggalObj = new DateTime($tglInput);
        $tanggalObj->modify('+1 month');

        $namaBulanIndo = [
            '01' => 'JAN',
            '02' => 'FEB',
            '03' => 'MAR',
            '04' => 'APR',
            '05' => 'MEI',
            '06' => 'JUN',
            '07' => 'JUL',
            '08' => 'AGS',
            '09' => 'SEPT',
            '10' => 'OKT',
            '11' => 'NOV',
            '12' => 'DES'
        ];
      
        // Nama bulan sekarang dan bulan depan
        $bulan      = $namaBulanIndo[$bulanSekarang];

        // BRUTO
          function ambilQtyBrutoPeriode($conn, $tglInput, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
            // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
            $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
            $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
            $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
        
            $queryBruto = "WITH QTY_BRUTO AS (
                            SELECT
                              i.CODE,
                              i.ORIGDLVSALORDLINESALORDERCODE,
                              i.ORIGDLVSALORDERLINEORDERLINE,
                              SUM(i.USERPRIMARYQUANTITY) AS KFF,
                              SUM(i.USERSECONDARYQUANTITY) AS FKF
                            FROM
                              ITXVIEWKGBRUTOBONORDER2 i
                            GROUP BY 
                            i.CODE,
                              i.ORIGDLVSALORDLINESALORDERCODE,
                              i.ORIGDLVSALORDERLINEORDERLINE
                          ),
                          CELUP_DYEING AS(
                            SELECT DISTINCT 
                              p.ORIGDLVSALORDLINESALORDERCODE,
                              p.CODE,
                              p2.PROGRESSSTATUS 
                            FROM
                              PRODUCTIONDEMAND p
                            LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                            WHERE
                              p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                          )
                          SELECT 
                            SUM(QTY) AS QTY
                          FROM (
                            SELECT
                              COALESCE(qb.KFF, 0) AS QTY,
                              s.CODE,
                              p.CODE,
                              s2.ORDERLINE           
                            FROM
                              SALESORDER s
                            LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                            LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                            LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                            LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                            LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                            LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                            LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                            LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                            WHERE
                              CAST(s.CREATIONDATETIME AS DATE) < '$tglInput'
                              AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME')
                        --      AND s.TEMPLATECODE IN ('CWD')
                              AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                              AND NOT a.VALUESTRING IS NULL
                              AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                        --      AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                        --      AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                        --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                        --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                            GROUP BY
                              qb.KFF,
                              s.CODE,
                              p.CODE,
                              s2.ORDERLINE)";
        
            $resultBruto = db2_exec($conn, $queryBruto);
            $rowBruto = db2_fetch_assoc($resultBruto);
            $qtyBruto = $rowBruto['QTY'];

            return [
                'qty' => $qtyBruto,
                'tgl_awal' => (int)$tanggalAwal,
                'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
            ];
          }
          $databrutoI = ambilQtyBrutoPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 1, 7);
          $databrutoII = ambilQtyBrutoPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 8, 14);
          $databrutoIII = ambilQtyBrutoPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 15, 21);
          $databrutoIV = ambilQtyBrutoPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 22, 31);
        // BRUTO
        
        // AKJ
          function ambilQtyAKJPeriode($conn, $tglInput, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
            // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
            $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
            $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
            $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
        
            $queryAKJ = "WITH QTY_BRUTO AS (
                          SELECT
                            i.CODE,
                            i.ORIGDLVSALORDLINESALORDERCODE,
                            i.ORIGDLVSALORDERLINEORDERLINE,
                            SUM(i.USERPRIMARYQUANTITY) AS KFF,
                            SUM(i.USERSECONDARYQUANTITY) AS FKF
                          FROM
                            ITXVIEWKGBRUTOBONORDER2 i
                          GROUP BY 
                          i.CODE,
                            i.ORIGDLVSALORDLINESALORDERCODE,
                            i.ORIGDLVSALORDERLINEORDERLINE
                        ),
                        CELUP_DYEING AS(
                          SELECT DISTINCT 
                            p.ORIGDLVSALORDLINESALORDERCODE,
                            p.CODE,
                            p2.PROGRESSSTATUS 
                          FROM
                            PRODUCTIONDEMAND p
                          LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                          WHERE
                            p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                        )
                        SELECT 
                          SUM(QTY) AS QTY
                        FROM (
                          SELECT
                            COALESCE(qb.KFF, 0) AS QTY,
                            s.CODE,
                            p.CODE,
                            s2.ORDERLINE           
                          FROM
                            SALESORDER s
                          LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                          LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                          LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                          LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                          LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                          LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                          LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                          LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                          WHERE
                            CAST(s.CREATIONDATETIME AS DATE) < '$tglInput'
                            AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME')
                      --      AND s.TEMPLATECODE IN ('CWD')
                            AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                            AND NOT a.VALUESTRING IS NULL
                            AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                           AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                      --      AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                      --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                      --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                          GROUP BY
                            qb.KFF,
                            s.CODE,
                            p.CODE,
                            s2.ORDERLINE)";
        
            $resultAKJ = db2_exec($conn, $queryAKJ);
            $rowAKJ = db2_fetch_assoc($resultAKJ);
            $qtyAKJ = $rowAKJ['QTY'];

            return [
                'qty' => $qtyAKJ,
                'tgl_awal' => (int)$tanggalAwal,
                'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
            ];
          }
          $dataAKJI = ambilQtyAKJPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 1, 7);
          $dataAKJII = ambilQtyAKJPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 8, 14);
          $dataAKJIII = ambilQtyAKJPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 15, 21);
          $dataAKJIV = ambilQtyAKJPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 22, 31);
        // AKJ
        
        // SDH CELUP
          function ambilQtySdhCelupPeriode($conn, $tglInput, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
            // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
            $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
            $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
            $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
        
            $querySdhCelup = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              ),
                              CELUP_DYEING AS(
                                SELECT DISTINCT 
                                  p.ORIGDLVSALORDLINESALORDERCODE,
                                  p.CODE,
                                  p2.PROGRESSSTATUS 
                                FROM
                                  PRODUCTIONDEMAND p
                                LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                WHERE
                                  p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM (
                                SELECT
                                  COALESCE(qb.KFF, 0) AS QTY,
                                  s.CODE,
                                  p.CODE,
                                  s2.ORDERLINE           
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput'
                                  AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME')
                            --      AND s.TEMPLATECODE IN ('CWD')
                                  AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                            --      AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                                 AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                            --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                            --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                                GROUP BY
                                  qb.KFF,
                                  s.CODE,
                                  p.CODE,
                                  s2.ORDERLINE)";
        
            $resultSdhCelup = db2_exec($conn, $querySdhCelup);
            $rowSdhCelup = db2_fetch_assoc($resultSdhCelup);
            $qtySdhCelup = $rowSdhCelup['QTY'];

            return [
                'qty' => $qtySdhCelup,
                'tgl_awal' => (int)$tanggalAwal,
                'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
            ];
          }
          $dataSdhCelupI = ambilQtySdhCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 1, 7);
          $dataSdhCelupII = ambilQtySdhCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 8, 14);
          $dataSdhCelupIII = ambilQtySdhCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 15, 21);
          $dataSdhCelupIV = ambilQtySdhCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 22, 31);
        // SDH CELUP
        
        // BLM CELUP
          $dataBlmCelupI    = round($databrutoI['qty']) - round($dataAKJI['qty']) - round($dataSdhCelupI['qty']);
          $dataBlmCelupII   = round($databrutoII['qty']) - round($dataAKJII['qty']) - round($dataSdhCelupII['qty']);
          $dataBlmCelupIII  = round($databrutoIII['qty']) - round($dataAKJIII['qty']) - round($dataSdhCelupIII['qty']);
          $dataBlmCelupIV   = round($databrutoIV['qty']) - round($dataAKJIV['qty']) - round($dataSdhCelupIV['qty']);
        // BLM CELUP
        
        // P'BLM CELUP
          function ambilQtyPBlmCelupPeriode($conn, $tglInput, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
            // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
            $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
            $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
            $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
        
            $queryPBlmCelup = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              ),
                              CELUP_DYEING AS(
                                SELECT DISTINCT 
                                  p.ORIGDLVSALORDLINESALORDERCODE,
                                  p.CODE,
                                  p2.PROGRESSSTATUS 
                                FROM
                                  PRODUCTIONDEMAND p
                                LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                WHERE
                                  p2.OPERATIONCODE IN ('DYE2') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM (
                                SELECT
                                  COALESCE(qb.KFF, 0) AS QTY,
                                  s.CODE,
                                  p.CODE,
                                  s2.ORDERLINE           
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput'
                                  AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME')
                            --      AND s.TEMPLATECODE IN ('CWD')
                                  AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                            --      AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                                --  AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                                  AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                            --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                                GROUP BY
                                  qb.KFF,
                                  s.CODE,
                                  p.CODE,
                                  s2.ORDERLINE)";
        
            $resultPBlmCelup = db2_exec($conn, $queryPBlmCelup);
            $rowPBlmCelup = db2_fetch_assoc($resultPBlmCelup);
            $qtyPBlmCelup = $rowPBlmCelup['QTY'];

            return [
                'qty' => $qtyPBlmCelup,
                'tgl_awal' => (int)$tanggalAwal,
                'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
            ];
          }
          $dataPBlmCelupI = ambilQtyPBlmCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 1, 7);
          $dataPBlmCelupII = ambilQtyPBlmCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 8, 14);
          $dataPBlmCelupIII = ambilQtyPBlmCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 15, 21);
          $dataPBlmCelupIV = ambilQtyPBlmCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 22, 31);
        // P'BLM CELUP

        // C'BLM CELUP
          $dataCBlmCelupI   = round($dataBlmCelupI) - round($dataPBlmCelupI['qty']);
          $dataCBlmCelupII  = round($dataBlmCelupII) - round($dataPBlmCelupII['qty']);
          $dataCBlmCelupIII = round($dataBlmCelupIII) - round($dataPBlmCelupIII['qty']);
          $dataCBlmCelupIV  = round($dataBlmCelupIV) - round($dataPBlmCelupIV['qty']);
        // C'BLM CELUP

        // TK
          function ambilTKPeriode($conn, $tglInput, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
            // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
            $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
            $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
            $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
        
            $queryTK = "SELECT 
                          SUM(QTY) AS QTY 
                        FROM (
                          SELECT
                            (p2.USERPRIMARYQUANTITY) AS QTY,
                            s.CODE,
                            s2.ORDERLINE
                          FROM
                            SALESORDER s
                          LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                          LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                          LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                          LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE 
                          LEFT JOIN PRODUCTIONDEMAND p2 ON p2.ORIGDLVSALORDLINESALORDERCODE = p.ORIGDLVSALORDLINESALORDERCODE
                                        AND p2.ITEMTYPEAFICODE = 'KGF'
                                        AND p2.SUBCODE01 = p.SUBCODE01
                                        AND p2.SUBCODE02 = p.SUBCODE02
                                        AND p2.SUBCODE03 = p.SUBCODE03
                                        AND p2.SUBCODE04 = p.SUBCODE04
                          LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p2.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                          WHERE
                            CAST(s.CREATIONDATETIME AS DATE) < '$tglInput'
                            AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME')  
                            AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                            AND NOT TRIM(p2.PROGRESSSTATUS) = '6'
                            AND NOT a.VALUESTRING IS NULL
                            AND a2.VALUESTRING IS NULL
                          GROUP BY 
                            p2.USERPRIMARYQUANTITY,
                            s.CODE,
                            s2.ORDERLINE)";
        
            $resultTK = db2_exec($conn, $queryTK);
            $rowTK = db2_fetch_assoc($resultTK);
            $qtyTK = $rowTK['QTY'];

            return [
                'qty' => $qtyTK,
                'tgl_awal' => (int)$tanggalAwal,
                'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
            ];
          }
          $dataTKI   = ambilTKPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 1, 7);
          $dataTKII  = ambilTKPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 8, 14);
          $dataTKIII = ambilTKPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 15, 21);
          $dataTKIV  = ambilTKPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 22, 31);
        // TK

        // GREIGE READY 
          $dataGreigeReadyI   = round($dataBlmCelupI) - round($dataTKI['qty']);
          $dataGreigeReadyII  = round($dataBlmCelupII) - round($dataTKII['qty']);
          $dataGreigeReadyIII = round($dataBlmCelupIII) - round($dataTKIII['qty']);
          $dataGreigeReadyIV  = round($dataBlmCelupIV) - round($dataTKIV['qty']);
        // GREIGE READY 

        // SUDAH PRESET, BELUM CELUP
          function ambilQtySudahPresetBlmCelupPeriode($conn, $tglInput, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
            // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
            $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
            $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
            $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
        
            $querySudahPresetBlmCelup = "WITH QTY_BRUTO AS (
                                          SELECT
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE,
                                            SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                            SUM(i.USERSECONDARYQUANTITY) AS FKF
                                          FROM
                                            ITXVIEWKGBRUTOBONORDER2 i
                                          GROUP BY 
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE
                                        ),
                                        CELUP_DYEING AS (
                                          SELECT DISTINCT 
                                            p.ORIGDLVSALORDLINESALORDERCODE,
                                            p.CODE,
                                            p2.PROGRESSSTATUS 
                                          FROM
                                            PRODUCTIONDEMAND p
                                          LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                          WHERE
                                            p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') 
                                        ),
                                        SUDAH_PRESET AS (
                                          SELECT DISTINCT 
                                          p.ORIGDLVSALORDLINESALORDERCODE,
                                          p.CODE,
                                          p2.PROGRESSSTATUS 
                                        FROM
                                          PRODUCTIONDEMAND p
                                        LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                        WHERE
                                          p2.OPERATIONCODE IN ('PRE1')
                                        )
                                        SELECT 
                                          SUM(QTY) AS QTY
                                        FROM
                                          (SELECT	
                                            (COALESCE(qb.KFF, 0)) AS QTY,
                                            s.CODE,
                                            s2.ORDERLINE
                                          FROM
                                            SALESORDER s
                                          LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                          LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                          LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                          LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE
                                          LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                          LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                          LEFT JOIN CELUP_DYEING cd ON cd.ORIGDLVSALORDLINESALORDERCODE = p.ORIGDLVSALORDLINESALORDERCODE AND cd.CODE = p.CODE
                                          LEFT JOIN SUDAH_PRESET sp ON sp.ORIGDLVSALORDLINESALORDERCODE = p.ORIGDLVSALORDLINESALORDERCODE AND sp.CODE = p.CODE
                                          WHERE
                                            CAST(s.CREATIONDATETIME AS DATE) < '$tglInput'
                                            AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME')  
                                            AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                                            AND cd.PROGRESSSTATUS IN ('0','1','2') -- BELUM CELUP
                                            AND sp.PROGRESSSTATUS = '3' -- SUDAH PRESET
                                            AND NOT a.VALUESTRING IS NULL
                                            AND a2.VALUESTRING IS NULL
                                          GROUP BY
                                            qb.KFF,
                                            s.CODE,
                                            s2.ORDERLINE)";
        
            $resultSudahPresetBlmCelup = db2_exec($conn, $querySudahPresetBlmCelup);
            $rowSudahPresetBlmCelup = db2_fetch_assoc($resultSudahPresetBlmCelup);
            $qtySudahPresetBlmCelup = $rowSudahPresetBlmCelup['QTY'];

            return [
                'qty' => $qtySudahPresetBlmCelup,
                'tgl_awal' => (int)$tanggalAwal,
                'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
            ];
          }
          $dataSudahPresetBlmCelupI   = ambilQtySudahPresetBlmCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 1, 7);
          $dataSudahPresetBlmCelupII  = ambilQtySudahPresetBlmCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 8, 14);
          $dataSudahPresetBlmCelupIII = ambilQtySudahPresetBlmCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 15, 21);
          $dataSudahPresetBlmCelupIV  = ambilQtySudahPresetBlmCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 22, 31);
        // SUDAH PRESET, BELUM CELUP
        
        // BELUM PRESET, BELUM CELUP
          function ambilQtyBelumPresetBlmCelupPeriode($conn, $tglInput, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
            // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
            $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
            $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
            $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
        
            $queryBelumPresetBlmCelup = "WITH QTY_BRUTO AS (
                                            SELECT
                                              i.ORIGDLVSALORDLINESALORDERCODE,
                                              i.ORIGDLVSALORDERLINEORDERLINE,
                                              SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                              SUM(i.USERSECONDARYQUANTITY) AS FKF
                                            FROM
                                              ITXVIEWKGBRUTOBONORDER2 i
                                            GROUP BY 
                                              i.ORIGDLVSALORDLINESALORDERCODE,
                                              i.ORIGDLVSALORDERLINEORDERLINE
                                          ),
                                          CELUP_DYEING AS (
                                            SELECT DISTINCT 
                                              p.ORIGDLVSALORDLINESALORDERCODE,
                                              p.CODE,
                                              p2.PROGRESSSTATUS 
                                            FROM
                                              PRODUCTIONDEMAND p
                                            LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                            WHERE
                                              p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') 
                                          ),
                                          SUDAH_PRESET AS (
                                            SELECT DISTINCT 
                                            p.ORIGDLVSALORDLINESALORDERCODE,
                                            p.CODE,
                                            p2.PROGRESSSTATUS 
                                          FROM
                                            PRODUCTIONDEMAND p
                                          LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                          WHERE
                                            p2.OPERATIONCODE IN ('PRE1')
                                          )
                                          SELECT 
                                            SUM(QTY) AS QTY
                                          FROM
                                            (SELECT	
                                              (COALESCE(qb.KFF, 0)) AS QTY,
                                              s.CODE,
                                              s2.ORDERLINE
                                            FROM
                                              SALESORDER s
                                            LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                            LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                            LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                            LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE
                                            LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                            LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                            LEFT JOIN CELUP_DYEING cd ON cd.ORIGDLVSALORDLINESALORDERCODE = p.ORIGDLVSALORDLINESALORDERCODE AND cd.CODE = p.CODE
                                            LEFT JOIN SUDAH_PRESET sp ON sp.ORIGDLVSALORDLINESALORDERCODE = p.ORIGDLVSALORDLINESALORDERCODE AND sp.CODE = p.CODE
                                            WHERE
                                              CAST(s.CREATIONDATETIME AS DATE) < '$tglInput'
                                              AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME')  
                                              AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                                              AND cd.PROGRESSSTATUS IN ('0','1','2') -- BELUM CELUP
                                              AND sp.PROGRESSSTATUS IN ('0','1','2') -- BELUM PRESET
                                              AND NOT a.VALUESTRING IS NULL
                                              AND a2.VALUESTRING IS NULL
                                            GROUP BY
                                              qb.KFF,
                                              s.CODE,
                                              s2.ORDERLINE)";
        
            $resultBelumPresetBlmCelup = db2_exec($conn, $queryBelumPresetBlmCelup);
            $rowBelumPresetBlmCelup = db2_fetch_assoc($resultBelumPresetBlmCelup);
            $qtyBelumPresetBlmCelup = $rowBelumPresetBlmCelup['QTY'];

            return [
                'qty' => $qtyBelumPresetBlmCelup,
                'tgl_awal' => (int)$tanggalAwal,
                'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
            ];
          }
          $dataBelumPresetBlmCelupI   = ambilQtyBelumPresetBlmCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 1, 7);
          $dataBelumPresetBlmCelupII  = ambilQtyBelumPresetBlmCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 8, 14);
          $dataBelumPresetBlmCelupIII = ambilQtyBelumPresetBlmCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 15, 21);
          $dataBelumPresetBlmCelupIV  = ambilQtyBelumPresetBlmCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 22, 31);
        // BELUM PRESET, BELUM CELUP
      ?>
      <!-- MINGGU 1 -->
        <tr>
          <td><?= $bulan; ?></td>
          <td>I</td>
          <td>`<?= $databrutoI['tgl_awal']; ?>-<?= $databrutoI['tgl_akhir']; ?></td>

          <td><?= number_format($databrutoI['qty']); ?></td>
          <td><?= number_format($dataAKJI['qty']); ?></td>
          <td><?= number_format($dataSdhCelupI['qty']); ?></td>
          <td><?= number_format($dataBlmCelupI); ?></td>
          <td><?= number_format($dataPBlmCelupI['qty']); ?></td>
          <td><?= number_format($dataCBlmCelupI); ?></td>
          
          <!-- KOLOM DELIVERY -->
          <td><?= $bulan; ?></td>
          <td>I</td>
          <td>`<?= $databrutoI['tgl_awal']; ?>-<?= $databrutoI['tgl_akhir']; ?></td>

          <td><?= number_format($dataTKI['qty']); ?></td>
          <td><?= number_format($dataGreigeReadyI); ?></td>
          <td><?= number_format($dataSudahPresetBlmCelupI['qty']); ?></td>
          <td><?= number_format($dataBelumPresetBlmCelupI['qty']); ?></td>
        </tr>
      <!-- MINGGU 1 -->

      <!-- MINGGU 2 -->
        <tr>
          <!-- KOLOM DELIVERY -->
          <td><?= $bulan; ?></td>
          <td>II</td>
          <td>`<?= $databrutoII['tgl_awal']; ?>-<?= $databrutoII['tgl_akhir']; ?></td>

          <td><?= number_format($databrutoII['qty']); ?></td>
          <td><?= number_format($dataAKJII['qty']); ?></td>
          <td><?= number_format($dataSdhCelupII['qty']); ?></td>
          <td><?= number_format($dataBlmCelupII); ?></td>
          <td><?= number_format($dataPBlmCelupII['qty']); ?></td>
          <td><?= number_format($dataCBlmCelupII); ?></td>
          
          <!-- KOLOM DELIVERY -->
          <td><?= $bulan; ?></td>
          <td>II</td>
          <td>`<?= $databrutoII['tgl_awal']; ?>-<?= $databrutoII['tgl_akhir']; ?></td>

          <td><?= number_format($dataTKII['qty']); ?></td>
          <td><?= number_format($dataGreigeReadyII); ?></td>
          <td><?= number_format($dataSudahPresetBlmCelupII['qty']); ?></td>
          <td><?= number_format($dataBelumPresetBlmCelupII['qty']); ?></td>
        </tr>
      <!-- MINGGU 2 -->

      <!-- MINGGU 3 -->
        <tr>
          <!-- KOLOM DELIVERY -->
          <td><?= $bulan; ?></td>
          <td>III</td>
          <td><?= $databrutoIII['tgl_awal']; ?>-<?= $databrutoIII['tgl_akhir']; ?></td>

          <td><?= number_format($databrutoIII['qty']); ?></td>
          <td><?= number_format($dataAKJIII['qty']); ?></td>
          <td><?= number_format($dataSdhCelupIII['qty']); ?></td>
          <td><?= number_format($dataBlmCelupIII); ?></td>
          <td><?= number_format($dataPBlmCelupIII['qty']); ?></td>
          <td><?= number_format($dataCBlmCelupIII); ?></td>
          
          <!-- KOLOM DELIVERY -->
          <td><?= $bulan; ?></td>
          <td>III</td>
          <td>`<?= $databrutoIII['tgl_awal']; ?>-<?= $databrutoIII['tgl_akhir']; ?></td>

          <td><?= number_format($dataTKIII['qty']); ?></td>
          <td><?= number_format($dataGreigeReadyIII); ?></td>
          <td><?= number_format($dataSudahPresetBlmCelupIII['qty']); ?></td>
          <td><?= number_format($dataBelumPresetBlmCelupIII['qty']); ?></td>
        </tr>
      <!-- MINGGU 3 -->
       
      <!-- MINGGU 4 -->
        <tr>
          <!-- KOLOM DELIVERY -->
          <td><?= $bulan; ?></td>
          <td>IV</td>
          <td>`<?= $databrutoIV['tgl_awal']; ?>-<?= $databrutoIV['tgl_akhir']; ?></td>

          <td><?= number_format($databrutoIV['qty']); ?></td>
          <td><?= number_format($dataAKJIV['qty']); ?></td>
          <td><?= number_format($dataSdhCelupIV['qty']); ?></td>
          <td><?= number_format($dataBlmCelupIV); ?></td>
          <td><?= number_format($dataPBlmCelupIV['qty']); ?></td>
          <td><?= number_format($dataCBlmCelupIV); ?></td>
          
          <!-- KOLOM DELIVERY -->
          <td><?= $bulan; ?></td>
          <td>IV</td>
          <td>`<?= $databrutoIV['tgl_awal']; ?>-<?= $databrutoIV['tgl_akhir']; ?></td>

          <td><?= number_format($dataTKIV['qty']); ?></td>
          <td><?= number_format($dataGreigeReadyIV); ?></td>
          <td><?= number_format($dataSudahPresetBlmCelupIV['qty']); ?></td>
          <td><?= number_format($dataBelumPresetBlmCelupIV['qty']); ?></td>
        </tr>
      <!-- MINGGU 4 -->
</table>
<table border="1" width="100%" style="border-collapse:collapse; border:1px solid #000; font-size:12px; text-align:center;">
    <thead>
      <tr>
        <th colspan="3">DELIVERY</th>
        <th>(BRUTO)</th>
        <th>AKJ</th>
        <th>SDH CELUP</th>
        <th>BLM CELUP</th>
        <th>P' BLM BLP</th>
        <th>C' BLM CLP</th>

        <th colspan="3">DELIVERY</th>
        <th>TK</th>
        <th>GREIGE READY</th>
        <th>SDH PRESET BLM CELUP</th>
        <th>BELUM PRESET BLM CELUP</th>
      </tr>
    </thead>
    <tbody>
      <?php
        // Tambah 1 bulan
        $dt = new DateTime($tglInput);
        $dt->modify('+1 month');
        $tglInput_bulandepan = $dt->format('Y-m-d');

        // Ambil tahun dan bulan dari tanggal input
        $tahunInput       = date('Y', strtotime($tglInput_bulandepan));
        $bulanInput       = date('m', strtotime($tglInput_bulandepan));
        $bulanSekarang    = date('m', strtotime($tglInput_bulandepan));

        // Buat objek DateTime
        $bulanDepanAngka = $dt->format('m');

        $namaBulanIndo = [
            '01' => 'JAN',
            '02' => 'FEB',
            '03' => 'MAR',
            '04' => 'APR',
            '05' => 'MEI',
            '06' => 'JUN',
            '07' => 'JUL',
            '08' => 'AGS',
            '09' => 'SEPT',
            '10' => 'OKT',
            '11' => 'NOV',
            '12' => 'DES'
        ];
      
        // Nama bulan sekarang dan bulan depan
        $bulanDepan = $namaBulanIndo[$bulanDepanAngka];

        // BRUTO
          function ambilQtyBrutoPeriodeBulanDepan($conn, $tglInput_bulandepan, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
            // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
            $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
            $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
            $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
        
            $queryBruto = "WITH QTY_BRUTO AS (
                            SELECT
                              i.CODE,
                              i.ORIGDLVSALORDLINESALORDERCODE,
                              i.ORIGDLVSALORDERLINEORDERLINE,
                              SUM(i.USERPRIMARYQUANTITY) AS KFF,
                              SUM(i.USERSECONDARYQUANTITY) AS FKF
                            FROM
                              ITXVIEWKGBRUTOBONORDER2 i
                            GROUP BY 
                            i.CODE,
                              i.ORIGDLVSALORDLINESALORDERCODE,
                              i.ORIGDLVSALORDERLINEORDERLINE
                          ),
                          CELUP_DYEING AS(
                            SELECT DISTINCT 
                              p.ORIGDLVSALORDLINESALORDERCODE,
                              p.CODE,
                              p2.PROGRESSSTATUS 
                            FROM
                              PRODUCTIONDEMAND p
                            LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                            WHERE
                              p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                          )
                          SELECT 
                            SUM(QTY) AS QTY
                          FROM (
                            SELECT
                              COALESCE(qb.KFF, 0) AS QTY,
                              s.CODE,
                              p.CODE,
                              s2.ORDERLINE           
                            FROM
                              SALESORDER s
                            LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                            LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                            LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                            LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                            LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                            LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                            LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                            LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                            WHERE
                              CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulandepan'
                              AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME')
                        --      AND s.TEMPLATECODE IN ('CWD')
                              AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                              AND NOT a.VALUESTRING IS NULL
                              AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                        --      AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                        --      AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                        --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                        --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                            GROUP BY
                              qb.KFF,
                              s.CODE,
                              p.CODE,
                              s2.ORDERLINE)";
        
            $resultBruto = db2_exec($conn, $queryBruto);
            $rowBruto = db2_fetch_assoc($resultBruto);
            $qtyBruto = $rowBruto['QTY'];

            return [
                'qty' => $qtyBruto,
                'tgl_awal' => (int)$tanggalAwal,
                'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
            ];
          }
          $databrutoI = ambilQtyBrutoPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 1, 7);
          $databrutoII = ambilQtyBrutoPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 8, 14);
          $databrutoIII = ambilQtyBrutoPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 15, 21);
          $databrutoIV = ambilQtyBrutoPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 22, 31);
        // BRUTO
        
        // AKJ
          function ambilQtyAKJPeriodeBulanDepanBulanDepan($conn, $tglInput_bulandepan, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
            // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
            $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
            $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
            $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
        
            $queryAKJ = "WITH QTY_BRUTO AS (
                            SELECT
                              i.CODE,
                              i.ORIGDLVSALORDLINESALORDERCODE,
                              i.ORIGDLVSALORDERLINEORDERLINE,
                              SUM(i.USERPRIMARYQUANTITY) AS KFF,
                              SUM(i.USERSECONDARYQUANTITY) AS FKF
                            FROM
                              ITXVIEWKGBRUTOBONORDER2 i
                            GROUP BY 
                            i.CODE,
                              i.ORIGDLVSALORDLINESALORDERCODE,
                              i.ORIGDLVSALORDERLINEORDERLINE
                          ),
                          CELUP_DYEING AS(
                            SELECT DISTINCT 
                              p.ORIGDLVSALORDLINESALORDERCODE,
                              p.CODE,
                              p2.PROGRESSSTATUS 
                            FROM
                              PRODUCTIONDEMAND p
                            LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                            WHERE
                              p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                          )
                          SELECT 
                            SUM(QTY) AS QTY
                          FROM (
                            SELECT
                              COALESCE(qb.KFF, 0) AS QTY,
                              s.CODE,
                              p.CODE,
                              s2.ORDERLINE           
                            FROM
                              SALESORDER s
                            LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                            LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                            LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                            LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                            LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                            LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                            LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                            LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                            WHERE
                              CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulandepan'
                              AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME')
                        --      AND s.TEMPLATECODE IN ('CWD')
                              AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                              AND NOT a.VALUESTRING IS NULL
                              AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                             AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                        --      AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                        --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                        --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                            GROUP BY
                              qb.KFF,
                              s.CODE,
                              p.CODE,
                              s2.ORDERLINE)";
        
            $resultAKJ = db2_exec($conn, $queryAKJ);
            $rowAKJ = db2_fetch_assoc($resultAKJ);
            $qtyAKJ = $rowAKJ['QTY'];

            return [
                'qty' => $qtyAKJ,
                'tgl_awal' => (int)$tanggalAwal,
                'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
            ];
          }
          $dataAKJI = ambilQtyAKJPeriodeBulanDepanBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 1, 7);
          $dataAKJII = ambilQtyAKJPeriodeBulanDepanBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 8, 14);
          $dataAKJIII = ambilQtyAKJPeriodeBulanDepanBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 15, 21);
          $dataAKJIV = ambilQtyAKJPeriodeBulanDepanBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 22, 31);
        // AKJ
        
        // SDH CELUP
          function ambilQtySdhCelupPeriodeBulanDepanBulanDepan($conn, $tglInput_bulandepan, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
            // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
            $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
            $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
            $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
        
            $querySdhCelup = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                i.CODE,
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              ),
                              CELUP_DYEING AS(
                                SELECT DISTINCT 
                                  p.ORIGDLVSALORDLINESALORDERCODE,
                                  p.CODE,
                                  p2.PROGRESSSTATUS 
                                FROM
                                  PRODUCTIONDEMAND p
                                LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                WHERE
                                  p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                              )
                              SELECT 
                                SUM(QTY) AS QTY
                              FROM (
                                SELECT
                                  COALESCE(qb.KFF, 0) AS QTY,
                                  s.CODE,
                                  p.CODE,
                                  s2.ORDERLINE           
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulandepan'
                                  AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME')
                            --      AND s.TEMPLATECODE IN ('CWD')
                                  AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                            --      AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                                 AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                            --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                            --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                                GROUP BY
                                  qb.KFF,
                                  s.CODE,
                                  p.CODE,
                                  s2.ORDERLINE)";
        
            $resultSdhCelup = db2_exec($conn, $querySdhCelup);
            $rowSdhCelup = db2_fetch_assoc($resultSdhCelup);
            $qtySdhCelup = $rowSdhCelup['QTY'];

            return [
                'qty' => $qtySdhCelup,
                'tgl_awal' => (int)$tanggalAwal,
                'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
            ];
          }
          $dataSdhCelupI = ambilQtySdhCelupPeriodeBulanDepanBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 1, 7);
          $dataSdhCelupII = ambilQtySdhCelupPeriodeBulanDepanBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 8, 14);
          $dataSdhCelupIII = ambilQtySdhCelupPeriodeBulanDepanBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 15, 21);
          $dataSdhCelupIV = ambilQtySdhCelupPeriodeBulanDepanBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 22, 31);
        // SDH CELUP
        
        // BLM CELUP
          $dataBlmCelupI    = round($databrutoI['qty']) - round($dataAKJI['qty']) - round($dataSdhCelupI['qty']);
          $dataBlmCelupII   = round($databrutoII['qty']) - round($dataAKJII['qty']) - round($dataSdhCelupII['qty']);
          $dataBlmCelupIII  = round($databrutoIII['qty']) - round($dataAKJIII['qty']) - round($dataSdhCelupIII['qty']);
          $dataBlmCelupIV   = round($databrutoIV['qty']) - round($dataAKJIV['qty']) - round($dataSdhCelupIV['qty']);
        // BLM CELUP
        
        // P'BLM CELUP
          function ambilQtyPBlmCelupPeriodeBulanDepan($conn, $tglInput_bulandepan, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
            // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
            $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
            $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
            $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
        
            $queryPBlmCelup = "WITH QTY_BRUTO AS (
                                SELECT
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE,
                                  SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                  SUM(i.USERSECONDARYQUANTITY) AS FKF
                                FROM
                                  ITXVIEWKGBRUTOBONORDER2 i
                                GROUP BY 
                                  i.ORIGDLVSALORDLINESALORDERCODE,
                                  i.ORIGDLVSALORDERLINEORDERLINE
                              ),
                              CELUP_DYEING AS(
                                SELECT DISTINCT 
                                  p.ORIGDLVSALORDLINESALORDERCODE,
                                  p.CODE,
                                  p2.PROGRESSSTATUS 
                                FROM
                                  PRODUCTIONDEMAND p
                                LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                WHERE
                                  p2.OPERATIONCODE IN ('DYE2')
                              )
                              SELECT SUM(QTY) AS QTY FROM (
                                SELECT
                                  COALESCE(qb.KFF, 0) AS QTY,
                                  s.CODE,
                                  s2.ORDERLINE
                                FROM
                                  SALESORDER s
                                LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE
                                LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                LEFT JOIN CELUP_DYEING cd ON cd.ORIGDLVSALORDLINESALORDERCODE = p.ORIGDLVSALORDLINESALORDERCODE AND cd.CODE = p.CODE
                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                WHERE
                                  CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulandepan'
                                  AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')  
                                  AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                                  AND cd.PROGRESSSTATUS IN ('0')
                                  AND NOT a.VALUESTRING IS NULL
                                  AND a2.VALUESTRING IS NULL
                                GROUP BY
                                  qb.KFF,
                                  s.CODE,
                                  s2.ORDERLINE)";
        
            $resultPBlmCelup = db2_exec($conn, $queryPBlmCelup);
            $rowPBlmCelup = db2_fetch_assoc($resultPBlmCelup);
            $qtyPBlmCelup = $rowPBlmCelup['QTY'];

            return [
                'qty' => $qtyPBlmCelup,
                'tgl_awal' => (int)$tanggalAwal,
                'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
            ];
          }
          $dataPBlmCelupI = ambilQtyPBlmCelupPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 1, 7);
          $dataPBlmCelupII = ambilQtyPBlmCelupPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 8, 14);
          $dataPBlmCelupIII = ambilQtyPBlmCelupPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 15, 21);
          $dataPBlmCelupIV = ambilQtyPBlmCelupPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 22, 31);
        // P'BLM CELUP

        // C'BLM CELUP
          $dataCBlmCelupI   = round($dataBlmCelupI) - round($dataPBlmCelupI['qty']);
          $dataCBlmCelupII  = round($dataBlmCelupII) - round($dataPBlmCelupII['qty']);
          $dataCBlmCelupIII = round($dataBlmCelupIII) - round($dataPBlmCelupIII['qty']);
          $dataCBlmCelupIV  = round($dataBlmCelupIV) - round($dataPBlmCelupIV['qty']);
        // C'BLM CELUP

        // TK
          function ambilTKPeriodeBulanDepan($conn, $tglInput_bulandepan, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
            // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
            $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
            $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
            $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
        
            $queryTK = "SELECT 
                          SUM(QTY) AS QTY 
                        FROM (
                          SELECT
                            (p2.USERPRIMARYQUANTITY) AS QTY,
                            s.CODE,
                            s2.ORDERLINE
                          FROM
                            SALESORDER s
                          LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                          LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                          LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                          LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE 
                          LEFT JOIN PRODUCTIONDEMAND p2 ON p2.ORIGDLVSALORDLINESALORDERCODE = p.ORIGDLVSALORDLINESALORDERCODE
                                        AND p2.ITEMTYPEAFICODE = 'KGF'
                                        AND p2.SUBCODE01 = p.SUBCODE01
                                        AND p2.SUBCODE02 = p.SUBCODE02
                                        AND p2.SUBCODE03 = p.SUBCODE03
                                        AND p2.SUBCODE04 = p.SUBCODE04
                          LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p2.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                          WHERE
                            CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulandepan'
                            AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME')  
                            AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                            AND NOT TRIM(p2.PROGRESSSTATUS) = '6'
                            AND NOT a.VALUESTRING IS NULL
                            AND a2.VALUESTRING IS NULL
                          GROUP BY 
                            p2.USERPRIMARYQUANTITY,
                            s.CODE,
                            s2.ORDERLINE)";
        
            $resultTK = db2_exec($conn, $queryTK);
            $rowTK = db2_fetch_assoc($resultTK);
            $qtyTK = $rowTK['QTY'];

            return [
                'qty' => $qtyTK,
                'tgl_awal' => (int)$tanggalAwal,
                'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
            ];
          }
          $dataTKI   = ambilTKPeriodeBulanDepan($conn1, $tglInput, $tahunInput, $bulanInput, 1, 7);
          $dataTKII  = ambilTKPeriodeBulanDepan($conn1, $tglInput, $tahunInput, $bulanInput, 8, 14);
          $dataTKIII = ambilTKPeriodeBulanDepan($conn1, $tglInput, $tahunInput, $bulanInput, 15, 21);
          $dataTKIV  = ambilTKPeriodeBulanDepan($conn1, $tglInput, $tahunInput, $bulanInput, 22, 31);
        // TK

        // GREIGE READY 
          $dataGreigeReadyI   = round($dataBlmCelupI) - round($dataTKI['qty']);
          $dataGreigeReadyII  = round($dataBlmCelupII) - round($dataTKII['qty']);
          $dataGreigeReadyIII = round($dataBlmCelupIII) - round($dataTKIII['qty']);
          $dataGreigeReadyIV  = round($dataBlmCelupIV) - round($dataTKIV['qty']);
        // GREIGE READY

        // SUDAH PRESET, BELUM CELUP
          function ambilQtySudahPresetBlmCelupPeriodeBulanDepan($conn, $tglInput_bulandepan, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
            // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
            $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
            $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
            $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
        
            $querySudahPresetBlmCelup = "WITH QTY_BRUTO AS (
                                          SELECT
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE,
                                            SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                            SUM(i.USERSECONDARYQUANTITY) AS FKF
                                          FROM
                                            ITXVIEWKGBRUTOBONORDER2 i
                                          GROUP BY 
                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                            i.ORIGDLVSALORDERLINEORDERLINE
                                        ),
                                        CELUP_DYEING AS (
                                          SELECT DISTINCT 
                                            p.ORIGDLVSALORDLINESALORDERCODE,
                                            p.CODE,
                                            p2.PROGRESSSTATUS 
                                          FROM
                                            PRODUCTIONDEMAND p
                                          LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                          WHERE
                                            p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5') 
                                        ),
                                        SUDAH_PRESET AS (
                                          SELECT DISTINCT 
                                          p.ORIGDLVSALORDLINESALORDERCODE,
                                          p.CODE,
                                          p2.PROGRESSSTATUS 
                                        FROM
                                          PRODUCTIONDEMAND p
                                        LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                        WHERE
                                          p2.OPERATIONCODE IN ('PRE1')
                                        )
                                        SELECT 
                                          SUM(QTY) AS QTY
                                        FROM
                                          (SELECT	
                                            (COALESCE(qb.KFF, 0)) AS QTY,
                                            s.CODE,
                                            s2.ORDERLINE
                                          FROM
                                            SALESORDER s
                                          LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                          LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                          LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                          LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE
                                          LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                          LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                          LEFT JOIN CELUP_DYEING cd ON cd.ORIGDLVSALORDLINESALORDERCODE = p.ORIGDLVSALORDLINESALORDERCODE AND cd.CODE = p.CODE
                                          LEFT JOIN SUDAH_PRESET sp ON sp.ORIGDLVSALORDLINESALORDERCODE = p.ORIGDLVSALORDLINESALORDERCODE AND sp.CODE = p.CODE
                                          WHERE
                                            CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulandepan'
                                            AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME')  
                                            AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                                            AND cd.PROGRESSSTATUS IN ('0','1','2') -- BELUM CELUP
                                            AND sp.PROGRESSSTATUS = '3' -- SUDAH PRESET
                                            AND NOT a.VALUESTRING IS NULL
                                            AND a2.VALUESTRING IS NULL
                                          GROUP BY
                                            qb.KFF,
                                            s.CODE,
                                            s2.ORDERLINE)";
        
            $resultSudahPresetBlmCelup = db2_exec($conn, $querySudahPresetBlmCelup);
            $rowSudahPresetBlmCelup = db2_fetch_assoc($resultSudahPresetBlmCelup);
            $qtySudahPresetBlmCelup = $rowSudahPresetBlmCelup['QTY'];

            return [
                'qty' => $qtySudahPresetBlmCelup,
                'tgl_awal' => (int)$tanggalAwal,
                'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
            ];
          }
          $dataSudahPresetBlmCelupI = ambilQtySudahPresetBlmCelupPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 1, 7);
          $dataSudahPresetBlmCelupII = ambilQtySudahPresetBlmCelupPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 8, 14);
          $dataSudahPresetBlmCelupIII = ambilQtySudahPresetBlmCelupPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 15, 21);
          $dataSudahPresetBlmCelupIV = ambilQtySudahPresetBlmCelupPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 22, 31);
        // SUDAH PRESET, BELUM CELUP
        
        // BELUM PRESET, BELUM CELUP
          function ambilQtyBelumPresetBlmCelupPeriodeBulanDepan($conn, $tglInput_bulandepan, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
            // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
            $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
            $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
            $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
        
            $queryBelumPresetBlmCelup = "WITH QTY_BRUTO AS (
                                            SELECT
                                              i.ORIGDLVSALORDLINESALORDERCODE,
                                              i.ORIGDLVSALORDERLINEORDERLINE,
                                              SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                              SUM(i.USERSECONDARYQUANTITY) AS FKF
                                            FROM
                                              ITXVIEWKGBRUTOBONORDER2 i
                                            GROUP BY 
                                              i.ORIGDLVSALORDLINESALORDERCODE,
                                              i.ORIGDLVSALORDERLINEORDERLINE
                                          ),
                                          CELUP_DYEING AS (
                                            SELECT DISTINCT 
                                              p.ORIGDLVSALORDLINESALORDERCODE,
                                              p.CODE,
                                              p2.PROGRESSSTATUS 
                                            FROM
                                              PRODUCTIONDEMAND p
                                            LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                            WHERE
                                              p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5') 
                                          ),
                                          SUDAH_PRESET AS (
                                            SELECT DISTINCT 
                                            p.ORIGDLVSALORDLINESALORDERCODE,
                                            p.CODE,
                                            p2.PROGRESSSTATUS 
                                          FROM
                                            PRODUCTIONDEMAND p
                                          LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                          WHERE
                                            p2.OPERATIONCODE IN ('PRE1')
                                          )
                                          SELECT 
                                            SUM(QTY) AS QTY
                                          FROM
                                            (SELECT	
                                              (COALESCE(qb.KFF, 0)) AS QTY,
                                              s.CODE,
                                              s2.ORDERLINE
                                            FROM
                                              SALESORDER s
                                            LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                            LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                            LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                            LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE
                                            LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                            LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                            LEFT JOIN CELUP_DYEING cd ON cd.ORIGDLVSALORDLINESALORDERCODE = p.ORIGDLVSALORDLINESALORDERCODE AND cd.CODE = p.CODE
                                            LEFT JOIN SUDAH_PRESET sp ON sp.ORIGDLVSALORDLINESALORDERCODE = p.ORIGDLVSALORDLINESALORDERCODE AND sp.CODE = p.CODE
                                            WHERE
                                              CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulandepan'
                                              AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME')  
                                              AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                                              AND cd.PROGRESSSTATUS IN ('0','1','2') -- BELUM CELUP
                                              AND sp.PROGRESSSTATUS IN ('0','1','2') -- BELUM PRESET
                                              AND NOT a.VALUESTRING IS NULL
                                              AND a2.VALUESTRING IS NULL
                                            GROUP BY
                                              qb.KFF,
                                              s.CODE,
                                              s2.ORDERLINE)";
        
            $resultBelumPresetBlmCelup = db2_exec($conn, $queryBelumPresetBlmCelup);
            $rowBelumPresetBlmCelup = db2_fetch_assoc($resultBelumPresetBlmCelup);
            $qtyBelumPresetBlmCelup = $rowBelumPresetBlmCelup['QTY'];

            return [
                'qty' => $qtyBelumPresetBlmCelup,
                'tgl_awal' => (int)$tanggalAwal,
                'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
            ];
          }
          $dataBelumPresetBlmCelupI   = ambilQtyBelumPresetBlmCelupPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 1, 7);
          $dataBelumPresetBlmCelupII  = ambilQtyBelumPresetBlmCelupPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 8, 14);
          $dataBelumPresetBlmCelupIII = ambilQtyBelumPresetBlmCelupPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 15, 21);
          $dataBelumPresetBlmCelupIV  = ambilQtyBelumPresetBlmCelupPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 22, 31);
        // BELUM PRESET, BELUM CELUP
      ?>
      <!-- MINGGU 1 -->
        <tr>
          <td><?= $bulanDepan; ?></td>
          <td>I</td>
          <td>`<?= $databrutoI['tgl_awal']; ?>-<?= $databrutoI['tgl_akhir']; ?></td>

          <td><?= number_format($databrutoI['qty']); ?></td>
          <td><?= number_format($dataAKJI['qty']); ?></td>
          <td><?= number_format($dataSdhCelupI['qty']); ?></td>
          <td><?= number_format($dataBlmCelupI['qty']); ?></td>
          <td><?= number_format($dataPBlmCelupI['qty']); ?></td>
          <td><?= number_format($dataCBlmCelupI); ?></td>
          
          <!-- KOLOM DELIVERY -->
          <td><?= $bulanDepan; ?></td>
          <td>I</td>
          <td>`<?= $databrutoI['tgl_awal']; ?>-<?= $databrutoI['tgl_akhir']; ?></td>

          <td><?= number_format($dataTKI['qty']); ?></td>
          <td><?= number_format($dataGreigeReadyI); ?></td>
          <td><?= number_format($dataSudahPresetBlmCelupI['qty']); ?></td>
          <td><?= number_format($dataBelumPresetBlmCelupI['qty']); ?></td>
        </tr>
      <!-- MINGGU 1 -->

      <!-- MINGGU 2 -->
        <tr>
          <!-- KOLOM DELIVERY -->
          <td><?= $bulanDepan; ?></td>
          <td>II</td>
          <td>`<?= $databrutoII['tgl_awal']; ?>-<?= $databrutoII['tgl_akhir']; ?></td>

          <td><?= number_format($databrutoII['qty']); ?></td>
          <td><?= number_format($dataAKJII['qty']); ?></td>
          <td><?= number_format($dataSdhCelupII['qty']); ?></td>
          <td><?= number_format($dataBlmCelupII['qty']); ?></td>
          <td><?= number_format($dataPBlmCelupII['qty']); ?></td>
          <td><?= number_format($dataCBlmCelupII); ?></td>
          
          <!-- KOLOM DELIVERY -->
          <td><?= $bulanDepan; ?></td>
          <td>II</td>
          <td>`<?= $databrutoII['tgl_awal']; ?>-<?= $databrutoII['tgl_akhir']; ?></td>

          <td><?= number_format($dataTKII['qty']); ?></td>
          <td><?= number_format($dataGreigeReadyII); ?></td>
          <td><?= number_format($dataSudahPresetBlmCelupII['qty']); ?></td>
          <td><?= number_format($dataBelumPresetBlmCelupII['qty']); ?></td>
        </tr>
      <!-- MINGGU 2 -->

      <!-- MINGGU 3 -->
        <tr>
          <!-- KOLOM DELIVERY -->
          <td><?= $bulanDepan; ?></td>
          <td>III</td>
          <td><?= $databrutoIII['tgl_awal']; ?>-<?= $databrutoIII['tgl_akhir']; ?></td>

          <td><?= number_format($databrutoIII['qty']); ?></td>
          <td><?= number_format($dataAKJIII['qty']); ?></td>
          <td><?= number_format($dataSdhCelupIII['qty']); ?></td>
          <td><?= number_format($dataBlmCelupIII['qty']); ?></td>
          <td><?= number_format($dataPBlmCelupIII['qty']); ?></td>
          <td><?= number_format($dataCBlmCelupIII); ?></td>
          
          <!-- KOLOM DELIVERY -->
          <td><?= $bulanDepan; ?></td>
          <td>III</td>
          <td>`<?= $databrutoIII['tgl_awal']; ?>-<?= $databrutoIII['tgl_akhir']; ?></td>

          <td><?= number_format($dataTKIII['qty']); ?></td>
          <td><?= number_format($dataGreigeReadyIII); ?></td>
          <td><?= number_format($dataSudahPresetBlmCelupIII['qty']); ?></td>
          <td><?= number_format($dataBelumPresetBlmCelupIII['qty']); ?></td>
        </tr>
      <!-- MINGGU 3 -->

      <!-- MINGGU 4 -->
        <tr>
          <!-- KOLOM DELIVERY -->
          <td><?= $bulanDepan; ?></td>
          <td>IV</td>
          <td>`<?= $databrutoIV['tgl_awal']; ?>-<?= $databrutoIV['tgl_akhir']; ?></td>

          <td><?= number_format($databrutoIV['qty']); ?></td>
          <td><?= number_format($dataAKJIV['qty']); ?></td>
          <td><?= number_format($dataSdhCelupIV['qty']); ?></td>
          <td><?= number_format($dataBlmCelupIV['qty']); ?></td>
          <td><?= number_format($dataPBlmCelupIV['qty']); ?></td>
          <td><?= number_format($dataCBlmCelupIV); ?></td>
          
          <!-- KOLOM DELIVERY -->
          <td><?= $bulanDepan; ?></td>
          <td>IV</td>
          <td>`<?= $databrutoIV['tgl_awal']; ?>-<?= $databrutoIV['tgl_akhir']; ?></td>

          <td><?= number_format($dataTKIV['qty']); ?></td>
          <td><?= number_format($dataGreigeReadyIV); ?></td>
          <td><?= number_format($dataSudahPresetBlmCelupIV['qty']); ?></td>
          <td><?= number_format($dataBelumPresetBlmCelupIV['qty']); ?></td>
        </tr>
      <!-- MINGGU 4 -->
</table>

BOOKING
<table border="1" width="100%" style="border-collapse:collapse; border:1px solid #000; font-size:12px; text-align:center;">
  <thead>
    <tr>
      <th colspan="3">DELIVERY</th>
      <th>(BRUTO)</th>
      <th>SDH CELUP</th>
      <th>BLM CELUP</th>
    </tr>
  </thead>
  <tbody>
    <?php
      // Ambil tahun dan bulan dari tanggal input
      $tahunInput       = date('Y', strtotime($tglInput));
      $bulanInput       = date('m', strtotime($tglInput));
      $bulanSekarang    = date('m', strtotime($tglInput));

      // Buat objek DateTime
      $tanggalObj = new DateTime($tglInput);
      $tanggalObj->modify('+1 month');

      $namaBulanIndo = [
          '01' => 'JAN',
          '02' => 'FEB',
          '03' => 'MAR',
          '04' => 'APR',
          '05' => 'MEI',
          '06' => 'JUN',
          '07' => 'JUL',
          '08' => 'AGS',
          '09' => 'SEPT',
          '10' => 'OKT',
          '11' => 'NOV',
          '12' => 'DES'
      ];
    
      // Nama bulan sekarang dan bulan depan
      $bulan      = $namaBulanIndo[$bulanSekarang];

      // BRUTO
        function ambilQtyBookingBrutoPeriode($conn, $tglInput, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
          // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
          $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
          $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
          $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
      
          $queryBrutoBooking = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                  i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                ),
                                CELUP_DYEING AS(
                                  SELECT DISTINCT 
                                    p.ORIGDLVSALORDLINESALORDERCODE,
                                    p.CODE,
                                    p2.PROGRESSSTATUS 
                                  FROM
                                    PRODUCTIONDEMAND p
                                  LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                  WHERE
                                    p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM (
                                  SELECT
                                    COALESCE(qb.KFF, 0) AS QTY,
                                    s.CODE,
                                    p.CODE,
                                    s2.ORDERLINE           
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput'
                                    AND s.TEMPLATECODE IN ('OPN', 'TBG', 'MNB', 'DMB', 'RBG')
                              --      AND s.TEMPLATECODE IN ('CWD')
                                    AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              --      AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                              --      AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                              --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                              --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                                  GROUP BY
                                    qb.KFF,
                                    s.CODE,
                                    p.CODE,
                                    s2.ORDERLINE)";
      
          $resultBrutoBooking = db2_exec($conn, $queryBrutoBooking);
          $rowBrutoBooking = db2_fetch_assoc($resultBrutoBooking);
          $qtyBrutoBooking = $rowBrutoBooking['QTY'];

          return [
              'qty' => $qtyBrutoBooking,
              'tgl_awal' => (int)$tanggalAwal,
              'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
          ];
        }
        $databrutoBookingI   = ambilQtyBookingBrutoPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 1, 7);
        $databrutoBookingII  = ambilQtyBookingBrutoPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 8, 14);
        $databrutoBookingIII = ambilQtyBookingBrutoPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 15, 21);
        $databrutoBookingIV  = ambilQtyBookingBrutoPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 22, 31);
      // BRUTO

      // SDH CELUP
        function ambilQtyBookingSdhCelupPeriode($conn, $tglInput, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
          // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
          $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
          $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
          $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
      
          $queryBookingSdhCelup = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                    i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  ),
                                  CELUP_DYEING AS(
                                    SELECT DISTINCT 
                                      p.ORIGDLVSALORDLINESALORDERCODE,
                                      p.CODE,
                                      p2.PROGRESSSTATUS 
                                    FROM
                                      PRODUCTIONDEMAND p
                                    LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                    WHERE
                                      p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM (
                                    SELECT
                                      COALESCE(qb.KFF, 0) AS QTY,
                                      s.CODE,
                                      p.CODE,
                                      s2.ORDERLINE           
                                    FROM
                                      SALESORDER s
                                    LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                    LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                    LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                    LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                                    LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                    LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                    LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                    LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                                    WHERE
                                      CAST(s.CREATIONDATETIME AS DATE) < '$tglInput'
                                      AND s.TEMPLATECODE IN ('OPN', 'TBG', 'MNB', 'DMB', 'RBG')
                                --      AND s.TEMPLATECODE IN ('CWD')
                                      AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                                      AND NOT a.VALUESTRING IS NULL
                                      AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                --      AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                                      AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                                --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                                --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                                    GROUP BY
                                      qb.KFF,
                                      s.CODE,
                                      p.CODE,
                                      s2.ORDERLINE)";
      
          $resultBookingSdhCelup = db2_exec($conn, $queryBookingSdhCelup);
          $rowBookingSdhCelup = db2_fetch_assoc($resultBookingSdhCelup);
          $qtyBookingSdhCelup = $rowBookingSdhCelup['QTY'];

          return [
              'qty' => $qtyBookingSdhCelup,
              'tgl_awal' => (int)$tanggalAwal,
              'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
          ];
        }
        $dataBookingSdhCelupI    = ambilQtyBookingSdhCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 1, 7);
        $dataBookingSdhCelupII   = ambilQtyBookingSdhCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 8, 14);
        $dataBookingSdhCelupIII  = ambilQtyBookingSdhCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 15, 21);
        $dataBookingSdhCelupIV   = ambilQtyBookingSdhCelupPeriode($conn1, $tglInput, $tahunInput, $bulanInput, 22, 31);
      // SDH CELUP

      // BLM CELUP
        $dataBookingBlmCelupI   = round($databrutoBookingI['qty']) - round($dataBookingSdhCelupI['qty']);
        $dataBookingBlmCelupII  = round($databrutoBookingII['qty']) - round($dataBookingSdhCelupII['qty']);
        $dataBookingBlmCelupIII = round($databrutoBookingIII['qty']) - round($dataBookingSdhCelupIII['qty']);
        $dataBookingBlmCelupIV  = round($databrutoBookingIV['qty']) - round($dataBookingSdhCelupIV['qty']);
      // BLM CELUP
    ?>
    <!-- MINGGU 1 -->
      <tr>
        <!-- KOLOM DELIVERY -->
        <td><?= $bulan; ?></td>
        <td>I</td>
        <td>`<?= $databrutoBookingI['tgl_awal']; ?>-<?= $databrutoBookingI['tgl_akhir']; ?></td>

        <td><?= number_format($databrutoBookingI['qty']); ?></td>
        <td><?= number_format($dataBookingSdhCelupI['qty']); ?></td>
        <td><?= number_format($dataBookingBlmCelupI); ?></td>
      </tr>
    <!-- MINGGU 1 -->

    <!-- MINGGU 2 -->        
      <tr>
        <!-- KOLOM DELIVERY -->
        <td><?= $bulan; ?></td>
        <td>II</td>
        <td>`<?= $databrutoBookingII['tgl_awal']; ?>-<?= $databrutoBookingII['tgl_akhir']; ?></td>

        <td><?= number_format($databrutoBookingII['qty'])  ?></td>
        <td><?= number_format($dataBookingSdhCelupII['qty'])  ?></td>
        <td><?= number_format($dataBookingBlmCelupII)  ?></td>
      </tr>
    <!-- MINGGU 2 -->  

    <!-- MINGGU 3 -->
      <tr>
        <!-- KOLOM DELIVERY -->
        <td><?= $bulan; ?></td>
        <td>III</td>
        <td>`<?= $databrutoBookingIII['tgl_awal']; ?>-<?= $databrutoBookingIII['tgl_akhir']; ?></td>

        <td><?= number_format($databrutoBookingIII['qty'])  ?></td>
        <td><?= number_format($dataBookingSdhCelupIII['qty'])  ?></td>
        <td><?= number_format($dataBookingBlmCelupIII)  ?></td>
      </tr>
    <!-- MINGGU 3 -->

    <!-- MINGGU 4 -->        
      <tr>
        <!-- KOLOM DELIVERY -->
        <td><?= $bulan; ?></td>
        <td>IV</td>
        <td>`<?= $databrutoBookingIV['tgl_awal']; ?>-<?= $databrutoBookingIV['tgl_akhir']; ?></td>

        <td><?= number_format($databrutoBookingIV['qty'])  ?></td>
        <td><?= number_format($dataBookingSdhCelupIV['qty'])  ?></td>
        <td><?= number_format($dataBookingBlmCelupIV)  ?></td>
      </tr>
    <!-- MINGGU 4 -->        
  </tbody>
</table>
<table border="1" width="100%" style="border-collapse:collapse; border:1px solid #000; font-size:12px; text-align:center;">
  <thead>
    <tr>
      <th colspan="3">DELIVERY</th>
      <th>(BRUTO)</th>
      <th>SDH CELUP</th>
      <th>BLM CELUP</th>
    </tr>
  </thead>
  <tbody>
    <?php
      // Tambah 1 bulan
      $dt = new DateTime($tglInput);
      $dt->modify('+1 month');
      $tglInput_bulandepan = $dt->format('Y-m-d');

      // Ambil tahun dan bulan dari tanggal input
      $tahunInput       = date('Y', strtotime($tglInput_bulandepan));
      $bulanInput       = date('m', strtotime($tglInput_bulandepan));
      $bulanSekarang    = date('m', strtotime($tglInput_bulandepan));

      // Buat objek DateTime
      $bulanDepanAngka = $dt->format('m');

      $namaBulanIndo = [
          '01' => 'JAN',
          '02' => 'FEB',
          '03' => 'MAR',
          '04' => 'APR',
          '05' => 'MEI',
          '06' => 'JUN',
          '07' => 'JUL',
          '08' => 'AGS',
          '09' => 'SEPT',
          '10' => 'OKT',
          '11' => 'NOV',
          '12' => 'DES'
      ];
    
      // Nama bulan sekarang dan bulan depan
      $bulanDepan = $namaBulanIndo[$bulanDepanAngka];

      // BRUTO
        function ambilQtyBookingBrutoPeriodeBulanDepan($conn, $tglInput_bulandepan, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
          // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
          $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
          $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
          $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
      
          $queryBrutoBooking = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                  i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                ),
                                CELUP_DYEING AS(
                                  SELECT DISTINCT 
                                    p.ORIGDLVSALORDLINESALORDERCODE,
                                    p.CODE,
                                    p2.PROGRESSSTATUS 
                                  FROM
                                    PRODUCTIONDEMAND p
                                  LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                  WHERE
                                    p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM (
                                  SELECT
                                    COALESCE(qb.KFF, 0) AS QTY,
                                    s.CODE,
                                    p.CODE,
                                    s2.ORDERLINE           
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulandepan'
                                    AND s.TEMPLATECODE IN ('OPN', 'TBG', 'MNB', 'DMB', 'RBG')
                              --      AND s.TEMPLATECODE IN ('CWD')
                                    AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                              --      AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                              --      AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                              --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                              --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                                  GROUP BY
                                    qb.KFF,
                                    s.CODE,
                                    p.CODE,
                                    s2.ORDERLINE)";
      
          $resultBrutoBooking = db2_exec($conn, $queryBrutoBooking);
          $rowBrutoBooking = db2_fetch_assoc($resultBrutoBooking);
          $qtyBrutoBooking = $rowBrutoBooking['QTY'];

          return [
              'qty' => $qtyBrutoBooking,
              'tgl_awal' => (int)$tanggalAwal,
              'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
          ];
        }
        $databrutoBookingI   = ambilQtyBookingBrutoPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 1, 7);
        $databrutoBookingII  = ambilQtyBookingBrutoPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 8, 14);
        $databrutoBookingIII = ambilQtyBookingBrutoPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 15, 21);
        $databrutoBookingIV  = ambilQtyBookingBrutoPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 22, 31);
      // BRUTO

      // SDH CELUP
        function ambilQtyBookingSdhCelupPeriodeBulanDepan($conn, $tglInput_bulandepan, $tahunInput, $bulanInput, $tanggalAwal, $tanggalAkhir) {
          // Tanggal akhir disesuaikan dengan akhir bulan jika lebih besar
          $tglMaxBulan = date('Y-m-t', strtotime("$tahunInput-$bulanInput-01"));
          $tglAkhirFix = date('Y-m-d', min(strtotime("$tahunInput-$bulanInput-$tanggalAkhir"), strtotime($tglMaxBulan)));
          $tglAwalFix  = "$tahunInput-$bulanInput-" . str_pad($tanggalAwal, 2, '0', STR_PAD_LEFT);
      
          $queryBookingSdhCelup = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                    i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  ),
                                  CELUP_DYEING AS(
                                    SELECT DISTINCT 
                                      p.ORIGDLVSALORDLINESALORDERCODE,
                                      p.CODE,
                                      p2.PROGRESSSTATUS 
                                    FROM
                                      PRODUCTIONDEMAND p
                                    LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                    WHERE
                                      p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM (
                                    SELECT
                                      COALESCE(qb.KFF, 0) AS QTY,
                                      s.CODE,
                                      p.CODE,
                                      s2.ORDERLINE           
                                    FROM
                                      SALESORDER s
                                    LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                    LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                    LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                    LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                                    LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                    LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                    LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                    LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                                    WHERE
                                      CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulandepan'
                                      AND s.TEMPLATECODE IN ('OPN', 'TBG', 'MNB', 'DMB', 'RBG')
                                --      AND s.TEMPLATECODE IN ('CWD')
                                      AND s3.DELIVERYDATE BETWEEN '$tglAwalFix' AND '$tglAkhirFix'
                                      AND NOT a.VALUESTRING IS NULL
                                      AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                --      AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                                      AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                                --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                                --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                                    GROUP BY
                                      qb.KFF,
                                      s.CODE,
                                      p.CODE,
                                      s2.ORDERLINE)";
      
          $resultBookingSdhCelup = db2_exec($conn, $queryBookingSdhCelup);
          $rowBookingSdhCelup = db2_fetch_assoc($resultBookingSdhCelup);
          $qtyBookingSdhCelup = $rowBookingSdhCelup['QTY'];

          return [
              'qty' => $qtyBookingSdhCelup,
              'tgl_awal' => (int)$tanggalAwal,
              'tgl_akhir' => (int)date('d', strtotime($tglAkhirFix))
          ];
        }
        $dataBookingSdhCelupI    = ambilQtyBookingSdhCelupPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 1, 7);
        $dataBookingSdhCelupII   = ambilQtyBookingSdhCelupPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 8, 14);
        $dataBookingSdhCelupIII  = ambilQtyBookingSdhCelupPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 15, 21);
        $dataBookingSdhCelupIV   = ambilQtyBookingSdhCelupPeriodeBulanDepan($conn1, $tglInput_bulandepan, $tahunInput, $bulanInput, 22, 31);
      // SDH CELUP

      // BLM CELUP
        $dataBookingBlmCelupI   = round($databrutoBookingI['qty']) - round($dataBookingSdhCelupI['qty']);
        $dataBookingBlmCelupII  = round($databrutoBookingII['qty']) - round($dataBookingSdhCelupII['qty']);
        $dataBookingBlmCelupIII = round($databrutoBookingIII['qty']) - round($dataBookingSdhCelupIII['qty']);
        $dataBookingBlmCelupIV  = round($databrutoBookingIV['qty']) - round($dataBookingSdhCelupIV['qty']);
      // BLM CELUP
    ?>
    <!-- MINGGU 1 -->
      <tr>
        <!-- KOLOM DELIVERY -->
        <td><?= $bulanDepan; ?></td>
        <td>I</td>
        <td>`<?= $databrutoBookingI['tgl_awal']; ?>-<?= $databrutoBookingI['tgl_akhir']; ?></td>

        <td><?= number_format($databrutoBookingI['qty']); ?></td>
        <td><?= number_format($dataBookingSdhCelupI['qty']); ?></td>
        <td><?= number_format($dataBookingBlmCelupI); ?></td>
      </tr>
    <!-- MINGGU 1 -->

    <!-- MINGGU 2 -->        
      <tr>
        <!-- KOLOM DELIVERY -->
        <td><?= $bulanDepan; ?></td>
        <td>II</td>
        <td>`<?= $databrutoBookingII['tgl_awal']; ?>-<?= $databrutoBookingII['tgl_akhir']; ?></td>

        <td><?= number_format($databrutoBookingII['qty'])  ?></td>
        <td><?= number_format($dataBookingSdhCelupII['qty'])  ?></td>
        <td><?= number_format($dataBookingBlmCelupII)  ?></td>
      </tr>
    <!-- MINGGU 2 -->  

    <!-- MINGGU 3 -->
      <tr>
        <!-- KOLOM DELIVERY -->
        <td><?= $bulanDepan; ?></td>
        <td>III</td>
        <td>`<?= $databrutoBookingIII['tgl_awal']; ?>-<?= $databrutoBookingIII['tgl_akhir']; ?></td>

        <td><?= number_format($databrutoBookingIII['qty'])  ?></td>
        <td><?= number_format($dataBookingSdhCelupIII['qty'])  ?></td>
        <td><?= number_format($dataBookingBlmCelupIII)  ?></td>
      </tr>
    <!-- MINGGU 3 -->

    <!-- MINGGU 4 -->        
      <tr>
        <!-- KOLOM DELIVERY -->
        <td><?= $bulanDepan; ?></td>
        <td>IV</td>
        <td>`<?= $databrutoBookingIV['tgl_awal']; ?>-<?= $databrutoBookingIV['tgl_akhir']; ?></td>

        <td><?= number_format($databrutoBookingIV['qty'])  ?></td>
        <td><?= number_format($dataBookingSdhCelupIV['qty'])  ?></td>
        <td><?= number_format($dataBookingBlmCelupIV)  ?></td>
      </tr>
    <!-- MINGGU 4 -->        
  </tbody>
</table>

PRINTING
<table border="1" width="100%" style="border-collapse:collapse; border:1px solid #000; font-size:12px; text-align:center;">
  <thead>
    <tr>
      <th>DELIVERY</th>
      <th>SUBLIMATION</th>
      <th>REAKTIF</th>
      <th>PIGMENT</th>
      <th>SDH CELUP</th>
      <th>BLM CELUP</th>
    </tr>
  </thead>
  <tbody>
  <?php
    // PRINTING - 1 BULAN
      $dtBulanLalu = new DateTime($tglInput);
      $dtBulanLalu->modify('-1 month');
      $tglInput_bulanlalu = $dtBulanLalu->format('Y-m-d');

      // Buat objek DateTime
      $bulanLaluAngka = $dtBulanLalu->format('m');

      $namaBulanIndo = [
          '01' => 'JAN',
          '02' => 'FEB',
          '03' => 'MAR',
          '04' => 'APR',
          '05' => 'MEI',
          '06' => 'JUN',
          '07' => 'JUL',
          '08' => 'AGS',
          '09' => 'SEPT',
          '10' => 'OKT',
          '11' => 'NOV',
          '12' => 'DES'
      ];
    
      // Nama bulan sekarang dan bulan depan
      $bulanLalu = $namaBulanIndo[$bulanLaluAngka];

      $tglAwalBulanLalu  = $dtBulanLalu->format('Y-m-01');
      $tglAkhirBulanLalu = $dtBulanLalu->format('Y-m-t');

      $qPrintingBulanLalu = "WITH QTY_BRUTO AS (
                              SELECT
                                i.CODE,
                                i.ORIGDLVSALORDLINESALORDERCODE,
                                i.ORIGDLVSALORDERLINEORDERLINE,
                                SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                SUM(i.USERSECONDARYQUANTITY) AS FKF
                              FROM
                                ITXVIEWKGBRUTOBONORDER2 i
                              GROUP BY 
                              i.CODE,
                                i.ORIGDLVSALORDLINESALORDERCODE,
                                i.ORIGDLVSALORDERLINEORDERLINE
                            ),
                            CELUP_DYEING AS(
                              SELECT DISTINCT 
                                p.ORIGDLVSALORDLINESALORDERCODE,
                                p.CODE,
                                p2.PROGRESSSTATUS 
                              FROM
                                PRODUCTIONDEMAND p
                              LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                              WHERE
                                p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                            )
                            SELECT 
                              SUM(QTY) AS QTY
                            FROM (
                              SELECT
                                COALESCE(qb.KFF, 0) AS QTY,
                                s.CODE,
                                p.CODE,
                                s2.ORDERLINE           
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF') AND NOT TRIM(s2.SUBCODE07) = '-'
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF') AND NOT TRIM(p.SUBCODE07) = '-'
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE 
                              LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulanlalu'
                                AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                          --      AND s.TEMPLATECODE IN ('CWD')
                                AND s3.DELIVERYDATE BETWEEN '$tglAwalBulanLalu' AND '$tglAkhirBulanLalu'
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                          --      AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                                -- AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                          --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                          --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                              GROUP BY
                                qb.KFF,
                                s.CODE,
                                p.CODE,
                                s2.ORDERLINE)";
      $resultPrintingBulanLalu  = db2_exec($conn1, $qPrintingBulanLalu);
      $rowPrintingBulanLalu     = db2_fetch_assoc($resultPrintingBulanLalu);
      $qtyPrintingBulanLalu     = $rowPrintingBulanLalu['QTY'];
    // PRINTING - 1 BULAN

    // PRINTING - BULAN SAAT INI
      // Buat objek DateTime dari tanggal input
      $dtBulanIni = new DateTime($tglInput);
      $tglInput_bulanIni = $dtBulanIni->format('Y-m-d');

      // Ambil angka bulan dan konversi ke nama bulan Indonesia
      $bulanIniAngka = $dtBulanIni->format('m');

      $namaBulanIndo = [
          '01' => 'JAN',
          '02' => 'FEB',
          '03' => 'MAR',
          '04' => 'APR',
          '05' => 'MEI',
          '06' => 'JUN',
          '07' => 'JUL',
          '08' => 'AGS',
          '09' => 'SEPT',
          '10' => 'OKT',
          '11' => 'NOV',
          '12' => 'DES'
      ];

      $bulanIni = $namaBulanIndo[$bulanIniAngka];

      // Ambil awal dan akhir bulan dari tanggal input
      $tglAwalBulanIni  = $dtBulanIni->format('Y-m-01');
      $tglAkhirBulanIni = $dtBulanIni->format('Y-m-t');

      $qPrintingBulanIni = "WITH QTY_BRUTO AS (
                              SELECT
                                i.CODE,
                                i.ORIGDLVSALORDLINESALORDERCODE,
                                i.ORIGDLVSALORDERLINEORDERLINE,
                                SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                SUM(i.USERSECONDARYQUANTITY) AS FKF
                              FROM
                                ITXVIEWKGBRUTOBONORDER2 i
                              GROUP BY 
                              i.CODE,
                                i.ORIGDLVSALORDLINESALORDERCODE,
                                i.ORIGDLVSALORDERLINEORDERLINE
                            ),
                            CELUP_DYEING AS(
                              SELECT DISTINCT 
                                p.ORIGDLVSALORDLINESALORDERCODE,
                                p.CODE,
                                p2.PROGRESSSTATUS 
                              FROM
                                PRODUCTIONDEMAND p
                              LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                              WHERE
                                p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                            )
                            SELECT 
                              SUM(QTY) AS QTY
                            FROM (
                              SELECT
                                COALESCE(qb.KFF, 0) AS QTY,
                                s.CODE,
                                p.CODE,
                                s2.ORDERLINE           
                              FROM
                                SALESORDER s
                              LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF') AND NOT TRIM(s2.SUBCODE07) = '-'
                              LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                              LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                              LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                              LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF') AND NOT TRIM(p.SUBCODE07) = '-'
                              LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                              LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE 
                              LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                              WHERE
                                CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulanIni'
                                AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                          --      AND s.TEMPLATECODE IN ('CWD')
                                AND s3.DELIVERYDATE BETWEEN '$tglAwalBulanIni' AND '$tglAkhirBulanIni'
                                AND NOT a.VALUESTRING IS NULL
                                AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                          --      AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                                -- AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                          --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                          --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                              GROUP BY
                                qb.KFF,
                                s.CODE,
                                p.CODE,
                                s2.ORDERLINE)";
      $resultPrintingBulanIni  = db2_exec($conn1, $qPrintingBulanIni);
      $rowPrintingBulanIni     = db2_fetch_assoc($resultPrintingBulanIni);
      $qtyPrintingBulanIni     = $rowPrintingBulanIni['QTY'];

    // PRINTING - BULAN SAAT INI

    // TOTAL SUBLIMATION
      $totalSublimation = $qtyPrintingBulanLalu + $qtyPrintingBulanIni;
    // TOTAL SUBLIMATION

    // SDH CELUP PRINTING - 1 BULAN
      $qSdhCelupPrintingBlnLalu = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                    i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  ),
                                  CELUP_DYEING AS(
                                    SELECT DISTINCT 
                                      p.ORIGDLVSALORDLINESALORDERCODE,
                                      p.CODE,
                                      p2.PROGRESSSTATUS 
                                    FROM
                                      PRODUCTIONDEMAND p
                                    LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                    WHERE
                                      p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM (
                                    SELECT
                                      COALESCE(qb.KFF, 0) AS QTY,
                                      s.CODE,
                                      p.CODE,
                                      s2.ORDERLINE           
                                    FROM
                                      SALESORDER s
                                    LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF') AND NOT TRIM(s2.SUBCODE07) = '-'
                                    LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                    LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                    LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                                    LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF') AND NOT TRIM(p.SUBCODE07) = '-'
                                    LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                    LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE 
                                    LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                                    WHERE
                                      CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulanlalu'
                                      AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                --      AND s.TEMPLATECODE IN ('CWD')
                                      AND s3.DELIVERYDATE BETWEEN '$tglAwalBulanLalu' AND '$tglAkhirBulanLalu'
                                      AND NOT a.VALUESTRING IS NULL
                                      AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                --      AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                                      AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                                --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                                --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                                    GROUP BY
                                      qb.KFF,
                                      s.CODE,
                                      p.CODE,
                                      s2.ORDERLINE)";
      $resultSdhCelupPrintingBlnLalu  = db2_exec($conn1, $qSdhCelupPrintingBlnLalu);
      $rowSdhCelupPrintingBlnLalu     = db2_fetch_assoc($resultSdhCelupPrintingBlnLalu);
      $qtySdhCelupPrintingBlnLalu     = $rowSdhCelupPrintingBlnLalu['QTY'];
    // SDH CELUP PRINTING - 1 BULAN
    
    // SDH CELUP PRINTING - BULAN SAAT INI
      $qSdhCelupPrintingBlnIni = "WITH QTY_BRUTO AS (
                                    SELECT
                                      i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE,
                                      SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                      SUM(i.USERSECONDARYQUANTITY) AS FKF
                                    FROM
                                      ITXVIEWKGBRUTOBONORDER2 i
                                    GROUP BY 
                                    i.CODE,
                                      i.ORIGDLVSALORDLINESALORDERCODE,
                                      i.ORIGDLVSALORDERLINEORDERLINE
                                  ),
                                  CELUP_DYEING AS(
                                    SELECT DISTINCT 
                                      p.ORIGDLVSALORDLINESALORDERCODE,
                                      p.CODE,
                                      p2.PROGRESSSTATUS 
                                    FROM
                                      PRODUCTIONDEMAND p
                                    LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                    WHERE
                                      p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                                  )
                                  SELECT 
                                    SUM(QTY) AS QTY
                                  FROM (
                                    SELECT
                                      COALESCE(qb.KFF, 0) AS QTY,
                                      s.CODE,
                                      p.CODE,
                                      s2.ORDERLINE           
                                    FROM
                                      SALESORDER s
                                    LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF') AND NOT TRIM(s2.SUBCODE07) = '-'
                                    LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                    LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                    LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                                    LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF') AND NOT TRIM(p.SUBCODE07) = '-'
                                    LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                    LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE 
                                    LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                                    WHERE
                                      CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulanIni'
                                      AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                --      AND s.TEMPLATECODE IN ('CWD')
                                      AND s3.DELIVERYDATE BETWEEN '$tglAwalBulanIni' AND '$tglAkhirBulanIni'
                                      AND NOT a.VALUESTRING IS NULL
                                      AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                --      AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                                      AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                                --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                                --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                                    GROUP BY
                                      qb.KFF,
                                      s.CODE,
                                      p.CODE,
                                      s2.ORDERLINE)";
      $resultSdhCelupPrintingBlnIni  = db2_exec($conn1, $qSdhCelupPrintingBlnIni);
      $rowSdhCelupPrintingBlnIni     = db2_fetch_assoc($resultSdhCelupPrintingBlnIni);
      $qtySdhCelupPrintingBlnIni     = $rowSdhCelupPrintingBlnIni['QTY'];
    // SDH CELUP PRINTING - BULAN SAAT INI

    // BLM CELUP PRINTING - 1 BULAN
      $qtyBlmCelupPrintingBlnLalu     = round($qtyPrintingBulanLalu) - round($qtySdhCelupPrintingBlnLalu);
    // BLM CELUP PRINTING - 1 BULAN

    // BLM CELUP PRINTING - BULAN SAAT INI
      $qtyBlmCelupPrintingBlnIni     = round($qtyPrintingBulanIni) - round($qtySdhCelupPrintingBlnIni);
    // BLM CELUP PRINTING - BULAN SAAT INI

    // TOTAL SDH CELUP PRINTING
      $totalSdhCelupPrinting = $qtySdhCelupPrintingBlnLalu + $qtySdhCelupPrintingBlnIni;
    // TOTAL SDH CELUP PRINTING
    
    // TOTAL BLM CELUP PRINTING
      $totalBlmCelupPrinting = $qtyBlmCelupPrintingBlnLalu + $qtyBlmCelupPrintingBlnIni;
    // TOTAL BLM CELUP PRINTING
    ?>
    <tr>
      <td><?= $bulanLalu; ?></td>
      <td><?= number_format($qtyPrintingBulanLalu); ?></td>
      <td>0</td>
      <td>0</td>
      <td><?= number_format($qtySdhCelupPrintingBlnLalu); ?></td>
      <td><?= number_format($qtyBlmCelupPrintingBlnLalu); ?></td>
    </tr>
    
    <tr>
      <td><?= $bulanIni; ?></td>
      <td><?= number_format($qtyPrintingBulanIni); ?></td>
      <td>0</td>
      <td>0</td>
      <td><?= number_format($qtySdhCelupPrintingBlnIni); ?></td>
      <td><?= number_format($qtyBlmCelupPrintingBlnIni); ?></td>
    </tr>
    
    <tr>
      <td>TOTAL</td>
      <td><?= number_format($totalSublimation); ?></td>
      <td>0</td>
      <td>0</td>
      <td><?= number_format($totalSdhCelupPrinting); ?></td>
      <td><?= number_format($totalBlmCelupPrinting); ?></td>
    </tr>
  </tbody>
</table>

YARN DYE
<table border="1" width="100%" style="border-collapse:collapse; border:1px solid #000; font-size:12px; text-align:center;"> 
  <thead>
    <tr>
      <th>DELIVERY</th>
      <th>TOTAL ORDER</th>
      <th>SUDAH CELUP</th>
      <th>BELUM CELUP</th>
    </tr>
  </thead>
  <tbody>
    <?php
      // TOTAL ORDER - 1 BULAN LALU
        $dtBulanLalu = new DateTime($tglInput);
        $dtBulanLalu->modify('-1 month');
        $tglInput_bulanlalu = $dtBulanLalu->format('Y-m-d');

        // Buat objek DateTime
        $bulanLaluAngka = $dtBulanLalu->format('m');

        $namaBulanIndo = [
            '01' => 'JAN',
            '02' => 'FEB',
            '03' => 'MAR',
            '04' => 'APR',
            '05' => 'MEI',
            '06' => 'JUN',
            '07' => 'JUL',
            '08' => 'AGS',
            '09' => 'SEPT',
            '10' => 'OKT',
            '11' => 'NOV',
            '12' => 'DES'
        ];
      
        // Nama bulan sekarang dan bulan depan
        $bulanLalu = $namaBulanIndo[$bulanLaluAngka];

        $tglAwalBulanLalu  = $dtBulanLalu->format('Y-m-01');
        $tglAkhirBulanLalu = $dtBulanLalu->format('Y-m-t');

        $qTotalOrderYndBlnLalu = "SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(p.USERPRIMARYQUANTITY)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulanlalu' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND p.TEMPLATECODE = '100'
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalBulanLalu' AND '$tglAkhirBulanLalu'";
        $resultTotalOrderYndBlnLalu  = db2_exec($conn1, $qTotalOrderYndBlnLalu);
        $rowTotalOrderYndBlnLalu     = db2_fetch_assoc($resultTotalOrderYndBlnLalu);
        $qtyTotalOrderYndBlnLalu     = $rowTotalOrderYndBlnLalu['QTY'];
      // TOTAL ORDER - 1 BULAN LALU

      // SUDAH CELUP - 1 BULAN LALU
        $dtBulanLalu = new DateTime($tglInput);
        $dtBulanLalu->modify('-1 month');
        $tglInput_bulanlalu = $dtBulanLalu->format('Y-m-d');

        // Buat objek DateTime
        $bulanLaluAngka = $dtBulanLalu->format('m');

        $namaBulanIndo = [
            '01' => 'JAN',
            '02' => 'FEB',
            '03' => 'MAR',
            '04' => 'APR',
            '05' => 'MEI',
            '06' => 'JUN',
            '07' => 'JUL',
            '08' => 'AGS',
            '09' => 'SEPT',
            '10' => 'OKT',
            '11' => 'NOV',
            '12' => 'DES'
        ];
      
        // Nama bulan sekarang dan bulan depan
        $bulanLalu = $namaBulanIndo[$bulanLaluAngka];

        $tglAwalBulanLalu  = $dtBulanLalu->format('Y-m-01');
        $tglAkhirBulanLalu = $dtBulanLalu->format('Y-m-t');

        $qTotalOrderSdhCelupYndBlnLalu = "SELECT 
                                        SUM(QTY) AS QTY
                                      FROM 
                                      (SELECT
                                        s.CODE,
                                        s3.DELIVERYDATE,
                                        ROUND(SUM(p.USERPRIMARYQUANTITY)) AS QTY
                                      FROM
                                        SALESORDER s
                                      LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                      LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                      LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                      LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE
                                      LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                      LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE AND p2.OPERATIONCODE = 'YDY1'
                                      WHERE
                                        CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulanlalu' -- FILTER pertama untuk mencari salesorder yg dibuat
                                        AND p.TEMPLATECODE = '100'
                                        AND NOT s3.DELIVERYDATE IS NULL
                                        AND TRIM(p2.PROGRESSSTATUS) = '3'
                                        AND NOT a.VALUESTRING IS NULL
                                        AND a2.VALUESTRING IS NULL
                                      GROUP BY
                                        s.CODE,
                                        s3.DELIVERYDATE)
                                      WHERE
                                        DELIVERYDATE BETWEEN '$tglAwalBulanLalu' AND '$tglAkhirBulanLalu'";
        $resultTotalOrderSdhCelupYndBlnLalu  = db2_exec($conn1, $qTotalOrderSdhCelupYndBlnLalu);
        $rowTotalOrderSdhCelupYndBlnLalu     = db2_fetch_assoc($resultTotalOrderSdhCelupYndBlnLalu);
        $qtyTotalOrderSdhCelupYndBlnLalu     = $rowTotalOrderSdhCelupYndBlnLalu['QTY'];
      // SUDAH CELUP - 1 BULAN LALU

      // BELUM CELUP - 1 BULAN LALU
        $qtyTotalOrderBlmCelupYndBlnLalu = round($qtyTotalOrderYndBlnLalu) - round($qtyTotalOrderSdhCelupYndBlnLalu);
      // BELUM CELUP - 1 BULAN LALU

      // TOTAL ORDER - BULAN SAAT INI
        $dtBulanIni = new DateTime($tglInput);
        $tglInput_bulanIni = $dtBulanIni->format('Y-m-d');

        // Buat objek DateTime
        $bulanIniAngka = $dtBulanIni->format('m');

        $namaBulanIndo = [
            '01' => 'JAN',
            '02' => 'FEB',
            '03' => 'MAR',
            '04' => 'APR',
            '05' => 'MEI',
            '06' => 'JUN',
            '07' => 'JUL',
            '08' => 'AGS',
            '09' => 'SEPT',
            '10' => 'OKT',
            '11' => 'NOV',
            '12' => 'DES'
        ];
      
        // Nama bulan sekarang dan bulan depan
        $bulanIni = $namaBulanIndo[$bulanIniAngka];

        $tglAwalBulanIni  = $dtBulanIni->format('Y-m-01');
        $tglAkhirBulanIni = $dtBulanIni->format('Y-m-t');

        $qTotalOrderYndBlnIni = "SELECT 
                                    SUM(QTY) AS QTY
                                  FROM 
                                  (SELECT
                                    s.CODE,
                                    s3.DELIVERYDATE,
                                    ROUND(SUM(p.USERPRIMARYQUANTITY)) AS QTY
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE
                                  LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulanIni' -- FILTER pertama untuk mencari salesorder yg dibuat
                                    AND p.TEMPLATECODE = '100'
                                    AND NOT s3.DELIVERYDATE IS NULL
                                    AND NOT a.VALUESTRING IS NULL
                                    AND a2.VALUESTRING IS NULL
                                  GROUP BY
                                    s.CODE,
                                    s3.DELIVERYDATE)
                                  WHERE
                                    DELIVERYDATE BETWEEN '$tglAwalBulanIni' AND '$tglAkhirBulanIni'";
        $resultTotalOrderYndBlnIni  = db2_exec($conn1, $qTotalOrderYndBlnIni);
        $rowTotalOrderYndBlnIni     = db2_fetch_assoc($resultTotalOrderYndBlnIni);
        $qtyTotalOrderYndBlnIni     = $rowTotalOrderYndBlnIni['QTY'];
      // TOTAL ORDER - BULAN SAAT INI

      // SUDAH CELUP - BULAN SAAT INI
        $dtBulanIni = new DateTime($tglInput);
        $tglInput_bulanIni = $dtBulanIni->format('Y-m-d');

        // Buat objek DateTime
        $bulanIniAngka = $dtBulanIni->format('m');

        $namaBulanIndo = [
            '01' => 'JAN',
            '02' => 'FEB',
            '03' => 'MAR',
            '04' => 'APR',
            '05' => 'MEI',
            '06' => 'JUN',
            '07' => 'JUL',
            '08' => 'AGS',
            '09' => 'SEPT',
            '10' => 'OKT',
            '11' => 'NOV',
            '12' => 'DES'
        ];
      
        // Nama bulan sekarang dan bulan depan
        $bulanIni = $namaBulanIndo[$bulanIniAngka];

        $tglAwalBulanIni  = $dtBulanIni->format('Y-m-01');
        $tglAkhirBulanIni = $dtBulanIni->format('Y-m-t');

        $qTotalOrderSdhCelupYndBlnIni = "SELECT 
                                        SUM(QTY) AS QTY
                                      FROM 
                                      (SELECT
                                        s.CODE,
                                        s3.DELIVERYDATE,
                                        ROUND(SUM(p.USERPRIMARYQUANTITY)) AS QTY
                                      FROM
                                        SALESORDER s
                                      LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE = 'KFF'
                                      LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                      LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                      LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE
                                      LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                      LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE AND p2.OPERATIONCODE = 'YDY1'
                                      WHERE
                                        CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulanIni' -- FILTER pertama untuk mencari salesorder yg dibuat
                                        AND p.TEMPLATECODE = '100'
                                        AND NOT s3.DELIVERYDATE IS NULL
                                        AND TRIM(p2.PROGRESSSTATUS) = '3'
                                        AND NOT a.VALUESTRING IS NULL
                                        AND a2.VALUESTRING IS NULL
                                      GROUP BY
                                        s.CODE,
                                        s3.DELIVERYDATE)
                                      WHERE
                                        DELIVERYDATE BETWEEN '$tglAwalBulanIni' AND '$tglAkhirBulanIni'";
        $resultTotalOrderSdhCelupYndBlnIni  = db2_exec($conn1, $qTotalOrderSdhCelupYndBlnIni);
        $rowTotalOrderSdhCelupYndBlnIni     = db2_fetch_assoc($resultTotalOrderSdhCelupYndBlnIni);
        $qtyTotalOrderSdhCelupYndBlnIni     = $rowTotalOrderSdhCelupYndBlnIni['QTY'];
      // SUDAH CELUP - BULAN SAAT INI

      // BELUM CELUP - BULAN SAAT INI
        $qtyTotalOrderBlmCelupYndBlnIni = round($qtyTotalOrderYndBlnIni) - round($qtyTotalOrderSdhCelupYndBlnIni);
      // BELUM CELUP - BULAN SAAT INI
    ?>
    <tr>
      <td><?= $bulanLalu; ?></td>
      <td><?= number_format($qtyTotalOrderYndBlnLalu); ?></td>
      <td><?= number_format($qtyTotalOrderSdhCelupYndBlnLalu); ?></td>
      <td><?= number_format($qtyTotalOrderBlmCelupYndBlnLalu); ?></td>
    </tr>
    
    <tr>
      <td><?= $bulanIni; ?></td>
      <td><?= number_format($qtyTotalOrderYndBlnIni); ?></td>
      <td><?= number_format($qtyTotalOrderSdhCelupYndBlnIni); ?></td>
      <td><?= number_format($qtyTotalOrderBlmCelupYndBlnIni); ?></td>
    </tr>
  </tbody>
</table>

GANTI KAIN INTERNAL+EXTERNAL+RETUR
<table border="1" width="100%" style="border-collapse:collapse; border:1px solid #000; font-size:12px; text-align:center;">
  <thead>
    <tr>
      <th>BULAN</th>
      <th>INTERNAL</th>
      <th>EXTERNAL</th>
      <th>TOTAL INT+EXT</th>
      <th>OPER WARNA</th>
      <th>RETUR</th>
    </tr>
  </thead>
  <tbody>
    <?php
      // GI INTERNAL - 1 BULAN LALU
        $dtBulanLalu = new DateTime($tglInput);
        $dtBulanLalu->modify('-1 month');
        $tglInput_bulanlalu = $dtBulanLalu->format('Y-m-d');

        // Buat objek DateTime
        $bulanLaluAngka = $dtBulanLalu->format('m');

        $namaBulanIndo = [
            '01' => 'JAN',
            '02' => 'FEB',
            '03' => 'MAR',
            '04' => 'APR',
            '05' => 'MEI',
            '06' => 'JUN',
            '07' => 'JUL',
            '08' => 'AGS',
            '09' => 'SEPT',
            '10' => 'OKT',
            '11' => 'NOV',
            '12' => 'DES'
        ];
      
        // Nama bulan sekarang dan bulan depan
        $bulanLalu = $namaBulanIndo[$bulanLaluAngka];

        $tglAwalBulanLalu  = $dtBulanLalu->format('Y-m-01');
        $tglAkhirBulanLalu = $dtBulanLalu->format('Y-m-t');

        $qGIInternalBulanLalu = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                  i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                ),
                                CELUP_DYEING AS(
                                  SELECT DISTINCT 
                                    p.ORIGDLVSALORDLINESALORDERCODE,
                                    p.CODE,
                                    p2.PROGRESSSTATUS 
                                  FROM
                                    PRODUCTIONDEMAND p
                                  LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                  WHERE
                                    p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM (
                                  SELECT
                                    COALESCE(qb.KFF, 0) AS QTY,
                                    s.CODE,
                                    p.CODE,
                                    s2.ORDERLINE           
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  -- LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a5 ON a5.UNIQUEID = p.ABSUNIQUEID AND a5.FIELDNAME = 'DefectNote'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulanlalu'
                                    AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                    AND s3.DELIVERYDATE BETWEEN '$tglAwalBulanLalu' AND '$tglAkhirBulanLalu'
                                    AND NOT a.VALUESTRING IS NULL
                                    -- AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                    AND a5.VALUESTRING LIKE '%GI%'
                              --      AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                              --      AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                              --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                              --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                                  GROUP BY
                                    qb.KFF,
                                    s.CODE,
                                    p.CODE,
                                    s2.ORDERLINE)";
        $resultGIInternalBulanLalu  = db2_exec($conn1, $qGIInternalBulanLalu);
        $rowGIInternalBulanLalu     = db2_fetch_assoc($resultGIInternalBulanLalu);
        $qtyGIInternalBulanLalu     = $rowGIInternalBulanLalu['QTY'];
      // GI INTERNAL - 1 BULAN LALU

      // GI EXTERNAL - 1 BULAN LALU
        $dtBulanLalu = new DateTime($tglInput);
        $dtBulanLalu->modify('-1 month');
        $tglInput_bulanlalu = $dtBulanLalu->format('Y-m-d');

        // Buat objek DateTime
        $bulanLaluAngka = $dtBulanLalu->format('m');

        $namaBulanIndo = [
            '01' => 'JAN',
            '02' => 'FEB',
            '03' => 'MAR',
            '04' => 'APR',
            '05' => 'MEI',
            '06' => 'JUN',
            '07' => 'JUL',
            '08' => 'AGS',
            '09' => 'SEPT',
            '10' => 'OKT',
            '11' => 'NOV',
            '12' => 'DES'
        ];
      
        // Nama bulan sekarang dan bulan depan
        $bulanLalu = $namaBulanIndo[$bulanLaluAngka];

        $tglAwalBulanLalu  = $dtBulanLalu->format('Y-m-01');
        $tglAkhirBulanLalu = $dtBulanLalu->format('Y-m-t');

        $qGIExternalBulanLalu = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                  i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                ),
                                CELUP_DYEING AS(
                                  SELECT DISTINCT 
                                    p.ORIGDLVSALORDLINESALORDERCODE,
                                    p.CODE,
                                    p2.PROGRESSSTATUS 
                                  FROM
                                    PRODUCTIONDEMAND p
                                  LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                  WHERE
                                    p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM (
                                  SELECT
                                    COALESCE(qb.KFF, 0) AS QTY,
                                    s.CODE,
                                    p.CODE,
                                    s2.ORDERLINE           
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  -- LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a5 ON a5.UNIQUEID = p.ABSUNIQUEID AND a5.FIELDNAME = 'DefectNote'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulanlalu'
                                    AND s.TEMPLATECODE IN ('RFD', 'RFE')
                                    AND s3.DELIVERYDATE BETWEEN '$tglAwalBulanLalu' AND '$tglAkhirBulanLalu'
                                    AND NOT a.VALUESTRING IS NULL
                                    -- AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                    AND a5.VALUESTRING LIKE '%GI%'
                              --      AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                              --      AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                              --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                              --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                                  GROUP BY
                                    qb.KFF,
                                    s.CODE,
                                    p.CODE,
                                    s2.ORDERLINE)";
        $resultGIExternalBulanLalu  = db2_exec($conn1, $qGIExternalBulanLalu);
        $rowGIExternalBulanLalu     = db2_fetch_assoc($resultGIExternalBulanLalu);
        $qtyGIExternalBulanLalu     = $rowGIExternalBulanLalu['QTY'];
      // GI EXTERNAL - 1 BULAN LALU

      // TOTAL INT + EXT - 1 BULAN LALU
        $totalGIInternalExternalBulanLalu = $qtyGIInternalBulanLalu + $qtyGIExternalBulanLalu;
      // TOTAL INT + EXT - 1 BULAN LALU

      // GI INTERNAL - BULAN SAAT INI
        $dtBulanIni = new DateTime($tglInput);
        $tglInput_bulanIni = $dtBulanIni->format('Y-m-d');

        // Buat objek DateTime
        $bulanIniAngka = $dtBulanIni->format('m');

        $namaBulanIndo = [
            '01' => 'JAN',
            '02' => 'FEB',
            '03' => 'MAR',
            '04' => 'APR',
            '05' => 'MEI',
            '06' => 'JUN',
            '07' => 'JUL',
            '08' => 'AGS',
            '09' => 'SEPT',
            '10' => 'OKT',
            '11' => 'NOV',
            '12' => 'DES'
        ];
      
        // Nama bulan sekarang dan bulan depan
        $bulanIni = $namaBulanIndo[$bulanIniAngka];

        $tglAwalBulanIni  = $dtBulanIni->format('Y-m-01');
        $tglAkhirBulanIni = $dtBulanIni->format('Y-m-t');

        $qGIInternalBulanIni = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                  i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                ),
                                CELUP_DYEING AS(
                                  SELECT DISTINCT 
                                    p.ORIGDLVSALORDLINESALORDERCODE,
                                    p.CODE,
                                    p2.PROGRESSSTATUS 
                                  FROM
                                    PRODUCTIONDEMAND p
                                  LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                  WHERE
                                    p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM (
                                  SELECT
                                    COALESCE(qb.KFF, 0) AS QTY,
                                    s.CODE,
                                    p.CODE,
                                    s2.ORDERLINE           
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  -- LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a5 ON a5.UNIQUEID = p.ABSUNIQUEID AND a5.FIELDNAME = 'DefectNote'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulanIni'
                                    AND s.TEMPLATECODE IN ('CWD', 'CWE', 'DOM', 'EXP', 'REP', 'RFD', 'RFE', 'RPE', 'SAM', 'SME', 'OPN')
                                    AND s3.DELIVERYDATE BETWEEN '$tglAwalBulanIni' AND '$tglAkhirBulanIni'
                                    AND NOT a.VALUESTRING IS NULL
                                    -- AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                    AND a5.VALUESTRING LIKE '%GI%'
                              --      AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                              --      AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                              --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                              --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                                  GROUP BY
                                    qb.KFF,
                                    s.CODE,
                                    p.CODE,
                                    s2.ORDERLINE)";
        $resultGIInternalBulanIni  = db2_exec($conn1, $qGIInternalBulanIni);
        $rowGIInternalBulanIni     = db2_fetch_assoc($resultGIInternalBulanIni);
        $qtyGIInternalBulanIni     = $rowGIInternalBulanIni['QTY'];
      // GI INTERNAL - BULAN SAAT INI

      // GI EXTERNAL - BULAN SAAT INI
        $dtBulanIni = new DateTime($tglInput);
        $tglInput_bulanIni = $dtBulanIni->format('Y-m-d');

        // Buat objek DateTime
        $bulanIniAngka = $dtBulanIni->format('m');

        $namaBulanIndo = [
            '01' => 'JAN',
            '02' => 'FEB',
            '03' => 'MAR',
            '04' => 'APR',
            '05' => 'MEI',
            '06' => 'JUN',
            '07' => 'JUL',
            '08' => 'AGS',
            '09' => 'SEPT',
            '10' => 'OKT',
            '11' => 'NOV',
            '12' => 'DES'
        ];
      
        // Nama bulan sekarang dan bulan depan
        $bulanIni = $namaBulanIndo[$bulanIniAngka];

        $tglAwalBulanIni  = $dtBulanIni->format('Y-m-01');
        $tglAkhirBulanIni = $dtBulanIni->format('Y-m-t');

        $qGIExternalBulanIni = "WITH QTY_BRUTO AS (
                                  SELECT
                                    i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE,
                                    SUM(i.USERPRIMARYQUANTITY) AS KFF,
                                    SUM(i.USERSECONDARYQUANTITY) AS FKF
                                  FROM
                                    ITXVIEWKGBRUTOBONORDER2 i
                                  GROUP BY 
                                  i.CODE,
                                    i.ORIGDLVSALORDLINESALORDERCODE,
                                    i.ORIGDLVSALORDERLINEORDERLINE
                                ),
                                CELUP_DYEING AS(
                                  SELECT DISTINCT 
                                    p.ORIGDLVSALORDLINESALORDERCODE,
                                    p.CODE,
                                    p2.PROGRESSSTATUS 
                                  FROM
                                    PRODUCTIONDEMAND p
                                  LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
                                  WHERE
                                    p2.OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','MWS1') AND TRIM(p2.PROGRESSSTATUS) IN('0','1','2','3')
                                )
                                SELECT 
                                  SUM(QTY) AS QTY
                                FROM (
                                  SELECT
                                    COALESCE(qb.KFF, 0) AS QTY,
                                    s.CODE,
                                    p.CODE,
                                    s2.ORDERLINE           
                                  FROM
                                    SALESORDER s
                                  LEFT JOIN SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE AND s2.LINESTATUS = 1 AND s2.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  LEFT JOIN SALESORDERDELIVERY s3 ON s3.SALESORDERLINESALESORDERCODE = s2.SALESORDERCODE AND s3.SALESORDERLINEORDERLINE = s2.ORDERLINE AND s3.ITEMTYPEAFICODE = s2.ITEMTYPEAFICODE
                                  LEFT JOIN ADSTORAGE a ON a.UNIQUEID = s.ABSUNIQUEID AND a.FIELDNAME = 'ApprovalRMP'
                                  LEFT JOIN ADSTORAGE a4 ON a4.UNIQUEID = s2.ABSUNIQUEID AND a4.FIELDNAME = 'KainAKJ'
                                  LEFT JOIN PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND p.ITEMTYPEAFICODE IN ('KFF', 'FKF')
                                  -- LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
                                  LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE AND qb.CODE = p.CODE
                                  LEFT JOIN CELUP_DYEING cd ON cd.CODE = p.CODE
                                  LEFT JOIN ADSTORAGE a5 ON a5.UNIQUEID = p.ABSUNIQUEID AND a5.FIELDNAME = 'DefectNote'
                                  WHERE
                                    CAST(s.CREATIONDATETIME AS DATE) < '$tglInput_bulanIni'
                                    AND s.TEMPLATECODE IN ('RFD', 'RFE')
                                    AND s3.DELIVERYDATE BETWEEN '$tglAwalBulanIni' AND '$tglAkhirBulanIni'
                                    AND NOT a.VALUESTRING IS NULL
                                    -- AND a2.VALUESTRING IS NULL -- DEMAND ASLI
                                    AND a5.VALUESTRING LIKE '%GI%'
                              --      AND TRIM(a4.VALUESTRING) IN ('1','2') -- AKJ
                              --      AND TRIM(cd.PROGRESSSTATUS) = '3' -- SDH CELUP
                              --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2') -- BLM CELUP
                              --      AND TRIM(cd.PROGRESSSTATUS) IN ('0','1','2','3') -- SDH & BLM CELUP
                                  GROUP BY
                                    qb.KFF,
                                    s.CODE,
                                    p.CODE,
                                    s2.ORDERLINE)";
        $resultGIExternalBulanIni  = db2_exec($conn1, $qGIExternalBulanIni);
        $rowGIExternalBulanIni     = db2_fetch_assoc($resultGIExternalBulanIni);
        $qtyGIExternalBulanIni     = $rowGIExternalBulanIni['QTY'];
      // GI EXTERNAL - BULAN SAAT INI

      // TOTAL INT + EXT - BULAN SAAT INI
        $totalGIInternalExternalBulanIni = $qtyGIInternalBulanIni + $qtyGIExternalBulanIni;
      // TOTAL INT + EXT - BULAN SAAT INI
    ?>
    <tr>
      <td><?= $bulanLalu; ?></td>
      <td><?= number_format($qtyGIInternalBulanLalu); ?></td>
      <td><?= number_format($qtyGIExternalBulanLalu); ?></td>
      <td><?= number_format($totalGIInternalExternalBulanLalu); ?></td>
      <td>N/A</td>
      <td>N/A</td>
    </tr>
    
    <tr>
      <td><?= $bulanIni; ?></td>
      <td><?= number_format($qtyGIInternalBulanIni); ?></td>
      <td><?= number_format($qtyGIExternalBulanIni); ?></td>
      <td><?= number_format($totalGIInternalExternalBulanIni); ?></td>
      <td>N/A</td>
      <td>N/A</td>
    </tr>
  </tbody>
</table>
<?php
    date_default_timezone_set('Asia/Jakarta');
    ini_set("error_reporting", 0);
    session_start();
    require_once "koneksi.php";

    function tungguJikaStatus0($maxRetry = 5)
    {
        global $con_now_gerobak;
        $retry = 0;
        do {
            $sqlStatus = "SELECT status FROM tmp_status LIMIT 1";
            $result    = mysqli_query($con_now_gerobak, $sqlStatus);
            $status    = 0;

            if ($row = mysqli_fetch_assoc($result)) {
                $status = (int) $row['status'];
            }

            if ($status === 0) {
                if (++$retry > $maxRetry) {
                    die("Status masih 0 setelah menunggu " . ($maxRetry * 3) . " menit. Proses dihentikan.");
                }
                sleep(180); // tunggu 3 menit
            }
        } while ($status === 0);
    }

    tungguJikaStatus0();

    $filename = "SummaryPencarianGerobak-QCF-" . date('Y-m-d_H-i-s') . ".xls";

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Cache-Control: max-age=0");

    $bulan = [
        1 => 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI',
        'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOVEMBER', 'DESEMBER',
    ];

    $tgl   = date('j');
    $bln   = $bulan[(int) date('n')];
    $thn   = date('Y');
    $jam   = (int) date('H');
    $waktu = ($jam >= 5 && $jam < 12) ? 'PAGI' : 'SORE';

    // Inisialisasi variabel
    $INSPEK_QTY                 = $INSPEK_GEROBAK                   = $INSPEK_KELUAR                = 0;
    $PACKING_QTY                = $PACKING_GEROBAK                  = $PACKING_KELUAR               = 0;
    $PERBAIKAN_QTY              = $PERBAIKAN_GEROBAK                = $PERBAIKAN_KELUAR             = 0;
    $NCP_BELUM_BERGERAK_QTY     = $NCP_BELUM_BERGERAK_GEROBAK       = $NCP_BELUM_BERGERAK_KELUAR    = 0;
    $PERSIAPAN_QTY              = $PERSIAPAN_GEROBAK                = $PERSIAPAN_KELUAR             = 0;

   function qc_summary($operation, $qty, $jml)
    {
        global $INSPEK_QTY, $INSPEK_GEROBAK,
            $PACKING_QTY, $PACKING_GEROBAK,
            $PERBAIKAN_QTY, $PERBAIKAN_GEROBAK,
            $NCP_BELUM_BERGERAK_QTY, $NCP_BELUM_BERGERAK_GEROBAK,
            $PERSIAPAN_QTY, $PERSIAPAN_GEROBAK;

        $operationMap = [
            'INS3' => 'INSPEK',
            'CNP1' => 'PACKING',
            'PQC'  => 'PERBAIKAN'
        ];

        $key = strtoupper(trim($operation));

        if (isset($operationMap[$key])) {
            $category = $operationMap[$key];
            ${$category . '_QTY'}     += $qty;
            ${$category . '_GEROBAK'} += $jml;

            // echo "$key => $qty - $jml<br>";
        }
    }

    // Cari kolom QTY dan GEROBAK
    $sql = "SELECT
            OPERATION,
            DEPARTEMEN,
            GEROBAK,
            JML_GEROBAK,
            SUM(QTY) AS total_qty
        FROM (
            SELECT
                OPERATION,
                DEPARTEMEN,
                GEROBAK,
                QTY,
                JML_GEROBAK
            FROM tmp_cari_gerobak_otomatis
            GROUP BY
                OPERATION,
                DEPARTEMEN,
                GEROBAK,
                QTY,
                JML_GEROBAK
        ) AS t1
        GROUP BY
            OPERATION,
            DEPARTEMEN,
            GEROBAK,
            JML_GEROBAK";

    $result = mysqli_query($con_now_gerobak, $sql);
    

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['DEPARTEMEN'] == "QC") {
                qc_summary($row['OPERATION'], $row['total_qty'], $row['JML_GEROBAK']);
            }
        }
    }

    // Cari Kolom Keluar
    if ($waktu == "PAGI") {
        $start = date('Y-m-d 07:00:00', strtotime('-1 day'));
        $end = date('Y-m-d 07:00:00');

        $sql_keluar = " SELECT 
                COUNT(DISTINCT a.personil) as inspektor, 
                SUM( a.qty ) AS bruto, 
                SUM( a.yard ) AS panjang, b.g_shift, 
                SUM(IF(c.status_produk = '1' AND (b.proses='Inspect Finish' OR b.proses='Inspect Packing' OR b.proses='Inspect White' OR b.proses='Inspect Qty Kecil'),a.qty,0)) AS `sts_ok`, 
                SUM(IF(c.status_produk = '2' AND (b.proses='Inspect Finish' OR b.proses='Inspect Packing' OR b.proses='Inspect White' OR b.proses='Inspect Qty Kecil'),a.qty,0)) AS `sts_x`, 
                SUM(IF(c.status_produk = '3' AND (b.proses='Inspect Finish' OR b.proses='Inspect Packing' OR b.proses='Inspect White' OR b.proses='Inspect Qty Kecil'),a.qty,0)) AS `sts_pr`, 
                SUM(if(b.proses='Inspect Finish',a.qty,0)) as sts_fin, 
                SUM(if(b.proses='Inspect Oven',a.qty,0)) as sts_oven, 
                SUM(if(b.proses='Pisah',a.qty,0)) as sts_pisah, 
                SUM(if(b.proses='Perbaikan' OR b.proses='Perbaikan Grade' OR b.proses='Tandai Defect' OR b.proses='Inspect Ulang (Setelah Perbaikan)',a.qty,0)) as sts_perbaikan, 
                SUM(if(b.proses='Kragh',a.qty,0)) as sts_kragh, 
                SUM(a.qty) as sts_tot, 
                SUM(a.yard) as sts_yard
            FROM 
                tbl_inspection a 
            INNER JOIN 
                tbl_schedule b ON a.id_schedule = b.id
            INNER JOIN 
                tbl_gerobak c ON c.id_schedule = b.id 
            WHERE 
                a.tgl_buat >= '$start' AND a.tgl_buat < '$end'
            GROUP BY 
                b.g_shift"
        ;

        $result_keluar = mysqli_query($con_db_qc, $sql_keluar);
        if (!$result_keluar) {
            die("Query gagal: " . mysqli_error($con_db_qc));
        }

        $INSPEK_KELUAR = 0;
		$PERBAIKAN_KELUAR = 0;

        while ($row1 = mysqli_fetch_array($result_keluar)) {
            $INSPEK_KELUAR += $row1['sts_tot'];
            $PERBAIKAN_KELUAR += $row1['sts_perbaikan'];
        }
        
        $sql_keluar_packing = " SELECT 
            tgl_update, jam_update, jml_roll, bruto, netto
            FROM 
                tbl_lap_inspeksi 
            where 
                dept = 'PACKING' 
            AND 
                STR_TO_DATE(CONCAT(tgl_update, ' ', jam_update), '%Y-%m-%d %H:%i:%s') >= '$start'
            AND
                STR_TO_DATE(CONCAT(tgl_update, ' ', jam_update), '%Y-%m-%d %H:%i:%s') < '$end'
        ";

        $result_packing = mysqli_query($con_db_qc, $sql_keluar_packing);
        if (!$result_packing) {
            die("Query PACKING gagal: " . mysqli_error($con_db_qc));
        }

        $PACKING_KELUAR = 0;

        if (mysqli_num_rows($result_packing) > 0) {
            while ($row = mysqli_fetch_assoc($result_packing)) {
                $qty = $row['netto'];
                $PACKING_KELUAR += $qty;
            }
        }
    }

    $TOTAL_QTY_SUMMARY = $INSPEK_QTY + $PACKING_QTY + $PERBAIKAN_QTY + $NCP_BELUM_BERGERAK_QTY +
        $PERSIAPAN_QTY;

    $TOTAL_GEROBAK_SUMMARY = $INSPEK_GEROBAK + $PACKING_GEROBAK + $PERBAIKAN_GEROBAK + $NCP_BELUM_BERGERAK_GEROBAK +
        $PERSIAPAN_GEROBAK;

    $TOTAL_KELUAR_SUMMARY = $INSPEK_KELUAR + $PACKING_KELUAR + $PERBAIKAN_KELUAR + $NCP_BELUM_BERGERAK_KELUAR +
        $PERSIAPAN_KELUAR;

    echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
    echo '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>';
    echo '<body>';
?>

<table border="1" cellspacing="0" cellpadding="3">
    <tr>
        <td rowspan="8" align="center" bgcolor="#FFFF00">
            QCF
        </td>
        <td rowspan="2" align="center" style="font-weight:bold; background:#f2f2f2;"><b>DEPT. QCF / <?php echo $tgl . ' ' . $bln . ' ' . $thn . ' (' . strtoupper($waktu) . ')' ?></b></td>
        <td rowspan="2" align="center" style="font-weight:bold; background:#f2f2f2;">KELUAR</td>
        <td colspan="2" align="center" style="font-weight:bold; background:#f2f2f2;">SISA</td>
    </tr>
    <tr>
        <td align="center" style="font-weight:bold; background:#f2f2f2;">QTY</td>
        <td align="center" style="font-weight:bold; background:#f2f2f2;">GEROBAK</td>
    </tr>

    <?php
        $rows = [
            'INSPEK'             => [$INSPEK_QTY, $INSPEK_GEROBAK, $INSPEK_KELUAR],
            'PACKING'            => [$PACKING_QTY, $PACKING_GEROBAK, $PACKING_KELUAR],
            'PERBAIKAN'          => [$PERBAIKAN_QTY, $PERBAIKAN_GEROBAK, $PERBAIKAN_KELUAR],
            'NCP BELUM BERGERAK' => [$NCP_BELUM_BERGERAK_QTY, $NCP_BELUM_BERGERAK_GEROBAK, $NCP_BELUM_BERGERAK_KELUAR],
            'PERSIAPAN'          => [$PERSIAPAN_QTY, $PERSIAPAN_GEROBAK, $PERSIAPAN_KELUAR],
        ];

        foreach ($rows as $proses => [$qty, $gerobak, $keluar]) {
            $formattedKeluar  = (!empty($keluar)) ? number_format($keluar, 2) : '-';
            $formattedQty     = (!empty($qty)) ? number_format($qty, 2) : '-';
            $formattedGerobak = (!empty($gerobak)) ? $gerobak : '-';

            echo "<tr>
                <td>$proses</td>
                <td align='center'>$formattedKeluar</td>
                <td align='center'>$formattedQty</td>
                <td align='center'>$formattedGerobak</td>
            </tr>";
        }
    ?>

    <tr style="font-weight:bold; background:#f9f9f9;">
        <td align="center" bgcolor="#FFFF00"><b>TOTAL</b></td>
        <td align="center" bgcolor="#FFFF00"><b><?php echo number_format($TOTAL_KELUAR_SUMMARY, 2) ?></b></td>
        <td align="center" bgcolor="#FFFF00"><b><?php echo number_format($TOTAL_QTY_SUMMARY, 2) ?></b></td>
        <td align="center" bgcolor="#FFFF00"><b><?php echo $TOTAL_GEROBAK_SUMMARY ?></b></td>
    </tr>
</table>

<?php
    echo '</body></html>';
?>

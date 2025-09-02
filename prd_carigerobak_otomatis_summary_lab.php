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

    $filename = "SummaryPencarianGerobak-LAB-" . date('Y-m-d_H-i-s') . ".xls";

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
    $GREIGE_QTY             = $GREIGE_GEROBAK             = 0;
    $TUNGGU_LOT_QTY         = $TUNGGU_LOT_GEROBAK         = 0;
    $PERBAIKAN_QTY          = $PERBAIKAN_GEROBAK          = 0;
    $GREIGE_ENTERED_QTY     = $GREIGE_ENTERED_GEROBAK     = 0;

    function lab_summary($operation, $qty, $jml, $status)
    {
        global $GREIGE_QTY, $GREIGE_GEROBAK,
        $TUNGGU_LOT_QTY, $TUNGGU_LOT_GEROBAK,
        $PERBAIKAN_QTY, $PERBAIKAN_GEROBAK,
        $GREIGE_ENTERED_QTY, $GREIGE_ENTERED_GEROBAK;

        $ops = [
            'MAT1'   => 'GREIGE',
            'WAIT8'  => 'TUNGGU_LOT',
            'LAB-T1' => 'PERBAIKAN', 'LAB-T2' => 'PERBAIKAN', 'LAB-T3' => 'PERBAIKAN','MAT1-TC1' => 'PERBAIKAN','MAT1-TC2' => 'PERBAIKAN','MAT1-TC3' => 'PERBAIKAN','MAT1-TC4' => 'PERBAIKAN','MAT1-TC5' => 'PERBAIKAN','MAT2-R1' => 'PERBAIKAN','MAT2-R3' => 'PERBAIKAN','MAT2-R4' => 'PERBAIKAN','MAT2-R5' => 'PERBAIKAN','MAT2-R6' => 'PERBAIKAN',
            'MAT1'   => 'GREIGE_ENTERED',
        ];

        if ($operation === 'MAT1') {
            if (strtoupper($status) === 'PROGRESS') {
                $category = 'GREIGE';
            } elseif (strtoupper($status) === 'ENTERED') {
                $category = 'GREIGE_ENTERED';
            } else {
                return;
            }
        } elseif (isset($ops[$operation])) {
            $category = $ops[$operation];
        } else {
            return;
        }

        ${$category . '_QTY'} += $qty;
        ${$category . '_GEROBAK'} += $jml;
    }

    $sql = "SELECT
            OPERATION,
            DEPARTEMEN,
            GEROBAK,
            JML_GEROBAK,
            STATUS,
            SUM(QTY) AS total_qty
        FROM (
            SELECT
                OPERATION,
                DEPARTEMEN,
                GEROBAK,
                QTY,
                JML_GEROBAK,
                STATUS
            FROM tmp_cari_gerobak_otomatis
            GROUP BY
                OPERATION,
                DEPARTEMEN,
                GEROBAK,
                QTY,
                JML_GEROBAK,
                STATUS
        ) AS t1
        GROUP BY
            OPERATION,
            DEPARTEMEN,
            GEROBAK,
            JML_GEROBAK,
            STATUS";

    $result = mysqli_query($con_now_gerobak, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['DEPARTEMEN'] == "LAB") {
                lab_summary($row['OPERATION'], $row['total_qty'], $row['JML_GEROBAK'], $row['STATUS']);
            }
        }
    }

    $TOTAL_QTY_SUMMARY = $GREIGE_QTY + $TUNGGU_LOT_QTY + $PERBAIKAN_QTY + $GREIGE_ENTERED_QTY;
    $TOTAL_GEROBAK_SUMMARY = $GREIGE_GEROBAK + $TUNGGU_LOT_GEROBAK + $PERBAIKAN_GEROBAK + $GREIGE_ENTERED_GEROBAK;

    echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
    echo '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>';
    echo '<body>';
?>

<table border="1" cellspacing="0" cellpadding="3">
    <tr>
        <td colspan="3" align="center" bgcolor="#FFFF00"><b>SISA                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 <?php echo $tgl . ' ' . $bln . ' ' . $thn ?> (<?php echo $waktu ?>)</b></td>
    </tr>
    <tr style="font-weight:bold; background:#f2f2f2;">
        <td align="center">PROSES</td>
        <td align="center">QTY</td>
        <td align="center">GEROBAK</td>
    </tr>
    <?php
        $rows = [
            'GREIGE'                    => [$GREIGE_QTY, $GREIGE_GEROBAK],
            'TUNGGU LOT'                => [$TUNGGU_LOT_QTY, $TUNGGU_LOT_GEROBAK],
            'PERBAIKAN'                 => [$PERBAIKAN_QTY, $PERBAIKAN_GEROBAK],
            'GREIGE ENTERED'            => [$GREIGE_ENTERED_QTY, $GREIGE_ENTERED_GEROBAK]
        ];

        foreach ($rows as $proses => [$qty, $gerobak]) {
            $formattedQty = $qty !== null ? number_format($qty, 2) : '-';
            echo "<tr><td>$proses</td><td align='center'>$formattedQty</td><td align='center'>" . ($gerobak ?: '-') . "</td></tr>";
        }
    ?>
    <tr style="font-weight:bold; background:#f9f9f9;">
        <td align="center" bgcolor="#FFFF00"><b>TOTAL</b></td>
        <td align="center" bgcolor="#FFFF00"><b><?php echo number_format($TOTAL_QTY_SUMMARY, 2) ?></b></td>
        <td align="center" bgcolor="#FFFF00"><b><?php echo $TOTAL_GEROBAK_SUMMARY ?></b></td>
    </tr>
</table>

<?php
    echo '</body></html>';
?>

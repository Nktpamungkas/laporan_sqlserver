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

    $filename = "SummaryPencarianGerobak-CQA-" . date('Y-m-d_H-i-s') . ".xls";

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
    $COLOR_CHECK_QTY  = $COLOR_CHECK_GEROBAK  = 0;
    $ADM_CQA_QTY      = $ADM_CQA_GEROBAK      = 0;
    $TOLAK_BASAH_QTY   = $TOLAK_BASAH_GEROBAK   = 0;
    $NCP_CQA_QTY      = $NCP_CQA_GEROBAK      = 0;

    function brs_summary($operation, $qty, $jml)
    {
        global 
        $COLOR_CHECK_QTY, $COLOR_CHECK_GEROBAK,
        $ADM_CQA_QTY, $ADM_CQA_GEROBAK,
        $TOLAK_BASAH_QTY, $TOLAK_BASAH_GEROBAK,
        $NCP_CQA_QTY, $NCP_CQA_GEROBAK;

        $ops = [
            'CCK2'   => 'COLOR_CHECK', 
            'WAIT45' => 'ADM_CQA',
            'TBS1'   => 'TOLAK_BASAH',
            'NCP14'   => 'NCP_CQA',
        ];

        $category = $ops[$operation];
        ${$category . '_QTY'} += $qty;
        ${$category . '_GEROBAK'} += $jml;
    }

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
            if ($row['DEPARTEMEN'] == "CQA") {
                brs_summary($row['OPERATION'], $row['total_qty'], $row['JML_GEROBAK']);
            }
        }
    }

    $TOTAL_QTY_SUMMARY = $COLOR_CHECK_QTY + $ADM_CQA_QTY + $TOLAK_BASAH_QTY + $NCP_CQA_QTY;

    $TOTAL_GEROBAK_SUMMARY = $COLOR_CHECK_GEROBAK + $ADM_CQA_GEROBAK + $TOLAK_BASAH_GEROBAK + $NCP_CQA_GEROBAK;

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
            'COLOR CHECK AFTER FIN'         => [$COLOR_CHECK_QTY, $COLOR_CHECK_GEROBAK],
            'ADM CQA'                       => [$ADM_CQA_QTY, $ADM_CQA_GEROBAK],
            'NCP CQA'                       => [$NCP_CQA_QTY, $NCP_CQA_GEROBAK],
            'TOLAK BASAH CQA'               => [$TOLAK_BASAH_QTY, $TOLAK_BASAH_GEROBAK],
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

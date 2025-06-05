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

    $filename = "SummaryPencarianGerobak-BRS-" . date('Y-m-d_H-i-s') . ".xls";

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
    $WET_SUEDING_QTY  = $WET_SUEDING_GEROBAK  = 0;
    $AIRO_QTY         = $AIRO_GEROBAK         = 0;
    $ANTI_PILLING_QTY = $ANTI_PILLING_GEROBAK = 0;
    $SISIR_QTY        = $SISIR_GEROBAK        = 0;
    $GARUK_QTY        = $GARUK_GEROBAK        = 0;
    $POTONG_BULU_QTY  = $POTONG_BULU_GEROBAK  = 0;
    $PEACH_SKIN_QTY   = $PEACH_SKIN_GEROBAK   = 0;
    $POLISHING_QTY    = $POLISHING_GEROBAK    = 0;
    $ADM_BRS_QTY      = $ADM_BRS_GEROBAK      = 0;
    $INSPEK_BRS_QTY   = $INSPEK_BRS_GEROBAK   = 0;
    $NCP_QTY          = $NCP_GEROBAK          = 0;
    $PERSIAPAN_QTY    = $PERSIAPAN_GEROBAK    = 0;

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

        $ops = [
            'WET1'   => 'WET_SUEDING', 'WET2' => 'WET_SUEDING', 'WET3' => 'WET_SUEDING', 'WET4' => 'WET_SUEDING',
            'AIR1'   => 'AIRO',
            'TDR1'   => 'ANTI_PILLING',
            'COM1'   => 'SISIR', 'COM2'       => 'SISIR',
            'RSE1'   => 'GARUK', 'RSE2'       => 'GARUK', 'RSE3'       => 'GARUK', 'RSE4'       => 'GARUK', 'RSE5'       => 'GARUK',
            'SHR1'   => 'POTONG_BULU', 'SHR2' => 'POTONG_BULU', 'SHR3' => 'POTONG_BULU', 'SHR4' => 'POTONG_BULU', 'SHR5' => 'POTONG_BULU',
            'SUE1'   => 'PEACH_SKIN', 'SUE2'  => 'PEACH_SKIN', 'SUE3'  => 'PEACH_SKIN', 'SUE4'  => 'PEACH_SKIN',
            'POL1'   => 'POLISHING',
            'WAIT38' => 'ADM_BRS',
            'INS9'   => 'INSPEK_BRS',
            'NCP8'   => 'NCP',
        ];

        $category = $ops[$operation] ?? 'PERSIAPAN';
        ${$category . '_QTY'} += $qty;
        ${$category . '_GEROBAK'} += $jml;
    }

    $sql    = "SELECT * FROM tmp_cari_gerobak_otomatis";
    $result = mysqli_query($con_now_gerobak, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['DEPARTEMEN'] == "BRS") {
                brs_summary($row['OPERATION'], $row['QTY'], $row['JML_GEROBAK']);
            }
        }
    }

    $TOTAL_QTY_SUMMARY = $WET_SUEDING_QTY + $AIRO_QTY + $ANTI_PILLING_QTY + $SISIR_QTY + $GARUK_QTY +
        $POTONG_BULU_QTY + $PEACH_SKIN_QTY + $POLISHING_QTY + $ADM_BRS_QTY + $INSPEK_BRS_QTY + $NCP_QTY + $PERSIAPAN_QTY;

    $TOTAL_GEROBAK_SUMMARY = $WET_SUEDING_GEROBAK + $AIRO_GEROBAK + $ANTI_PILLING_GEROBAK + $SISIR_GEROBAK + $GARUK_GEROBAK +
        $POTONG_BULU_GEROBAK + $PEACH_SKIN_GEROBAK + $POLISHING_GEROBAK + $ADM_BRS_GEROBAK + $INSPEK_BRS_GEROBAK + $NCP_GEROBAK + $PERSIAPAN_GEROBAK;

    echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
    echo '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>';
    echo '<body>';
?>

<table border="1" cellspacing="0" cellpadding="3">
    <tr>
        <td colspan="3" align="center" bgcolor="#FFFF00"><b>SISA                                                                                                                                                                                                                                                                                                                                 <?php echo $tgl . ' ' . $bln . ' ' . $thn ?> (<?php echo $waktu ?>)</b></td>
    </tr>
    <tr style="font-weight:bold; background:#f2f2f2;">
        <td align="center">PROSES</td>
        <td align="center">QTY</td>
        <td align="center">GEROBAK</td>
    </tr>
    <?php
        $rows = [
            'WET SUEDING'        => [$WET_SUEDING_QTY, $WET_SUEDING_GEROBAK],
            'AIRO'               => [$AIRO_QTY, $AIRO_GEROBAK],
            'ANTI PILLING'       => [$ANTI_PILLING_QTY, $ANTI_PILLING_GEROBAK],
            'SISIR'              => [$SISIR_QTY, $SISIR_GEROBAK],
            'GARUK'              => [$GARUK_QTY, $GARUK_GEROBAK],
            'POTONG BULU'        => [$POTONG_BULU_QTY, $POTONG_BULU_GEROBAK],
            'PEACH SKIN'         => [$PEACH_SKIN_QTY, $PEACH_SKIN_GEROBAK],
            'POLISHING'          => [$POLISHING_QTY, $POLISHING_GEROBAK],
            'ADM BRS'            => [$ADM_BRS_QTY, $ADM_BRS_GEROBAK],
            'INSPEK BRS'         => [$INSPEK_BRS_QTY, $INSPEK_BRS_GEROBAK],
            'NCP'                => [$NCP_QTY, $NCP_GEROBAK],
            'PERSIAPAN / KOSONG' => [$PERSIAPAN_QTY, $PERSIAPAN_GEROBAK],
        ];
        foreach ($rows as $proses => [$qty, $gerobak]) {
            echo "<tr><td>$proses</td><td align='center'>" . ($qty ?: '-') . "</td><td align='center'>" . ($gerobak ?: '-') . "</td></tr>";
        }
    ?>
    <tr style="font-weight:bold; background:#f9f9f9;">
        <td align="center" bgcolor="#FFFF00"><b>TOTAL</b></td>
        <td align="center" bgcolor="#FFFF00"><b><?php echo $TOTAL_QTY_SUMMARY ?></b></td>
        <td align="center" bgcolor="#FFFF00"><b><?php echo $TOTAL_GEROBAK_SUMMARY ?></b></td>
    </tr>
</table>

<?php
    echo '</body></html>';
?>

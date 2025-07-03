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

    $filename = "SummaryPencarianGerobak-FIN-" . date('Y-m-d_H-i-s') . ".xls";

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
    $BELAH_QTY                = $BELAH_GEROBAK                = $BELAH_KELUAR                = 0;
    $STEAMER_QTY              = $STEAMER_GEROBAK              = $STEAMER_KELUAR              = 0;
    $OVEN_GREIGE_QTY          = $OVEN_GREIGE_GEROBAK          = $OVEN_GREIGE_KELUAR          = 0;
    $OVEN_DYE_QTY             = $OVEN_DYE_GEROBAK             = $OVEN_DYE_KELUAR             = 0;
    $OVEN_STENTER_QTY         = $OVEN_STENTER_GEROBAK         = $OVEN_STENTER_KELUAR         = 0;
    $OVEN_OBAT_QTY            = $OVEN_OBAT_GEROBAK            = $OVEN_OBAT_KELUAR            = 0;
    $FINISHING_1_QTY          = $FINISHING_1_GEROBAK          = $FINISHING_1_KELUAR          = 0;
    $PADDER_QTY               = $PADDER_GEROBAK               = $PADDER_KELUAR               = 0;
    $FINISHING_JADI_ULANG_QTY = $FINISHING_JADI_ULANG_GEROBAK = $FINISHING_JADI_ULANG_KELUAR = 0;
    $COMPACT_QTY              = $COMPACT_GEROBAK              = $COMPACT_KELUAR              = 0;
    $NCP_QTY                  = $NCP_GEROBAK                  = $NCP_KELUAR                  = 0;
    $LIPAT_INSPEK_FIN_QTY     = $LIPAT_INSPEK_FIN_GEROBAK     = $LIPAT_INSPEK_FIN_KELUAR     = 0;
    $PERSIAPAN_QTY            = $PERSIAPAN_GEROBAK            = $PERSIAPAN_KELUAR            = 0;

    function fin_summary($operation, $qty, $jml)
    {
        global $BELAH_QTY, $BELAH_GEROBAK,
        $STEAMER_QTY, $STEAMER_GEROBAK,
        $OVEN_GREIGE_QTY, $OVEN_GREIGE_GEROBAK,
        $OVEN_DYE_QTY, $OVEN_DYE_GEROBAK,
        $OVEN_STENTER_QTY, $OVEN_STENTER_GEROBAK,
        $OVEN_OBAT_QTY, $OVEN_OBAT_GEROBAK,
        $FINISHING_1_QTY, $FINISHING_1_GEROBAK,
        $PADDER_QTY, $PADDER_GEROBAK,
        $FINISHING_JADI_ULANG_QTY, $FINISHING_JADI_ULANG_GEROBAK,
        $COMPACT_QTY, $COMPACT_GEROBAK,
        $NCP_QTY, $NCP_GEROBAK,
        $LIPAT_INSPEK_FIN_QTY, $LIPAT_INSPEK_FIN_GEROBAK,
        $PERSIAPAN_QTY, $PERSIAPAN_GEROBAK;

        //Mapping prefix ke kategori
        $prefixMap = [
            'BLP' => 'BELAH', 'OPW'                => 'BELAH', 'BLD'                => 'BELAH',
            'STM' => 'STEAMER',
            'PRE' => 'OVEN_GREIGE', 'OVG'          => 'OVEN_GREIGE',
            'OVD' => 'OVEN_DYE',
            'OVN' => 'OVEN_STENTER',
            'OVB' => 'OVEN_OBAT',
            'FIN' => 'FINISHING_1',
            'PAD' => 'PADDER',
            'FNJ' => 'FINISHING_JADI_ULANG', 'FNV' => 'FINISHING_JADI_ULANG', 'FNU' => 'FINISHING_JADI_ULANG',
            'CPD' => 'COMPACT', 'CPT'              => 'COMPACT', 'CPF'          => 'COMPACT',
            'LIP' => 'LIPAT_INSPEK_FIN', 'INS'     => 'LIPAT_INSPEK_FIN', 'TMF' => 'LIPAT_INSPEK_FIN',
        ];

        $prefix = strtoupper(substr($operation, 0, 3));

        if (isset($prefixMap[$prefix])) {
            $category = $prefixMap[$prefix];
            ${$category . '_QTY'} += $qty;
            ${$category . '_GEROBAK'} += $jml;
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
            if ($row['DEPARTEMEN'] == "FIN") {
                fin_summary($row['OPERATION'], $row['total_qty'], $row['JML_GEROBAK']);
            }
        }
    }

    // Cari Kolom Keluar
    if ($waktu == "PAGI") {
        $start_datetime = date('Y-m-d H:i:s', strtotime('-2 days 23:00:01'));
        $end_datetime   = date('Y-m-d H:i:s', strtotime('-1 days 23:00:00'));

        $sql_keluar = "SELECT * FROM db_finishing.tbl_produksi a WHERE
            CONCAT(a.tgl_update,CONCAT(' ',a.jam_in)) BETWEEN ? AND ?";

        $params  = [$start_datetime, $end_datetime];
        $options = ["Scrollable" => SQLSRV_CURSOR_STATIC];

        $result_keluar = sqlsrv_query($con_finishing, $sql_keluar, $params, $options);

        // Mapping Category
        $keluar_map = [
            'Belah Cuci (Normal)'                          => 'BELAH_KELUAR',
            'Belah Cuci ulang (Normal)'                    => 'BELAH_KELUAR',
            'Belah Dyeing (Bantu)'                         => 'BELAH_KELUAR',
            'Belah Preset (Normal)'                        => 'BELAH_KELUAR',
            'Steamer (Normal)'                             => 'STEAMER_KELUAR',
            'Oven Greige (Normal)'                         => 'OVEN_GREIGE_KELUAR',
            'Preset (Normal)'                              => 'OVEN_GREIGE_KELUAR',
            'Oven Dyeing (Bantu)'                          => 'OVEN_DYE_KELUAR',
            'Oven Stenter (Normal)'                        => [
                'P3ST' => 'OVEN_STENTER_KELUAR',
            ],
            'Oven Stenter Dyeing (Bantu)'                  => [
                'P3ST' => 'OVEN_STENTER_KELUAR',
            ],
            'Oven Stenter Ulang (Normal)'                  => [
                'P3ST' => 'OVEN_STENTER_KELUAR',
            ],
            'Oven Kering (Normal)'                         => [
                'P3ST' => 'OVEN_STENTER_KELUAR',
            ],
            'Oven Fleece (Normal)'                         => [
                'P3ST' => 'OVEN_STENTER_KELUAR',
            ],
            'Oven Fleece Ulang (Normal)'                   => [
                'P3ST' => 'OVEN_STENTER_KELUAR',
            ],
            'CURING STENTER (Bantu)'                       => [
                'P3ST' => 'OVEN_STENTER_KELUAR',
            ],
            'Oven Kragh (Normal)'                          => [
                'P3ST' => 'OVEN_STENTER_KELUAR',
            ],
            'Oven Perbaikan Kragh (Normal)'                => [
                'P3ST' => 'OVEN_STENTER_KELUAR',
            ],
            'Oven Tambah Obat (Khusus)'                    => 'OVEN_OBAT_KELUAR',
            'FIN 1X (Normal)'                              => 'FINISHING_1_KELUAR',
            'Finishing 1X (Normal)'                        => 'FINISHING_1_KELUAR',
            'Finishing 1X (ov) (Normal)'                   => 'FINISHING_1_KELUAR',
            'Finishing 1X ulang (Normal)'                  => 'FINISHING_1_KELUAR',
            'Padder - Dyeing (Bantu)'                      => 'PADDER_KELUAR',
            'Padder 2x - Dyeing (Bantu)'                   => 'PADDER_KELUAR',
            'Padder 3x - Dyeing (Bantu)'                   => 'PADDER_KELUAR',
            'Padder 4x - Dyeing (Bantu)'                   => 'PADDER_KELUAR',
            'Padder 5x - Dyeing (Bantu)'                   => 'PADDER_KELUAR',
            'Finishing Jadi (Normal)'                      => 'FINISHING_JADI_ULANG_KELUAR',
            'Finishing Ulang (Normal)'                     => 'FINISHING_JADI_ULANG_KELUAR',
            'Finishing Ulang - Brushing (Bantu)'           => 'FINISHING_JADI_ULANG_KELUAR',
            'Finishing Ulang - Dyeing (Bantu)'             => 'FINISHING_JADI_ULANG_KELUAR',
            'Finishing Ulang - Dyeing 2 (Bantu)'           => 'FINISHING_JADI_ULANG_KELUAR',
            'Finishing Ulang - Dyeing 3 (Bantu)'           => 'FINISHING_JADI_ULANG_KELUAR',
            'Finishing Ulang - Naik Suhu Dyeing (Bantu)'   => 'FINISHING_JADI_ULANG_KELUAR',
            'Finishing Ulang - Naik Suhu Dyeing 2 (Bantu)' => 'FINISHING_JADI_ULANG_KELUAR',
            'Finishing Ulang - Naik Suhu Dyeing 3 (Bantu)' => 'FINISHING_JADI_ULANG_KELUAR',
            'Finishing Ulang 2 (Normal)'                   => 'FINISHING_JADI_ULANG_KELUAR',
            'Finishing Ulang 3 (Normal)'                   => 'FINISHING_JADI_ULANG_KELUAR',
            'Finishing Suhu Tinggi - Dyeing (Bantu)'       => 'FINISHING_JADI_ULANG_KELUAR',
            'Compact (Normal)'                             => 'COMPACT_KELUAR',
            'Compact - Dyeing (Bantu)'                     => 'COMPACT_KELUAR',
            'Compact - Dyeing 2 (Bantu)'                   => 'COMPACT_KELUAR',
            'Compact - Dyeing 3 (Bantu)'                   => 'COMPACT_KELUAR',
            'Compact Perbaikan (Normal)'                   => 'COMPACT_KELUAR',
            'Inspek Finishing (Normal)'                    => 'LIPAT_INSPEK_FIN_KELUAR',
            'Lipat (Normal)'                               => 'LIPAT_INSPEK_FIN_KELUAR',
            'Perbaikan (Bantu)'                            => 'LIPAT_INSPEK_FIN_KELUAR',
            'Potong Pinggir (Normal)'                      => 'LIPAT_INSPEK_FIN_KELUAR',
            'Tarik Lebar (Normal)'                         => 'LIPAT_INSPEK_FIN_KELUAR',
            'Tarik Lebar - Dyeing (Bantu)'                 => 'LIPAT_INSPEK_FIN_KELUAR',
            'Tarik Lebar - Dyeing (Bantu)'                 => 'LIPAT_INSPEK_FIN_KELUAR',

        ];

        if (sqlsrv_num_rows($result_keluar) > 0) {

            while ($row = sqlsrv_fetch_array($result_keluar, SQLSRV_FETCH_ASSOC)) {
                $proses   = $row['proses'];
                $no_mesin = $row['no_mesin'];
                $no_mesin = substr($no_mesin = $row['no_mesin'], 0, 4);
                $qty      = $row['qty'];

                if (isset($keluar_map[$proses])) {
                    if (is_array($keluar_map[$proses])) {
                        if (isset($keluar_map[$proses][$no_mesin])) {
                            $varName = $keluar_map[$proses][$no_mesin];
                            $$varName += $qty;
                        }
                    } else {
                        $varName = $keluar_map[$proses];
                        $$varName += $qty;
                    }
                }
            }
        }
    }

    $TOTAL_QTY_SUMMARY = $BELAH_QTY + $STEAMER_QTY + $OVEN_GREIGE_QTY + $OVEN_DYE_QTY +
        $OVEN_STENTER_QTY + $OVEN_OBAT_QTY + $FINISHING_1_QTY + $PADDER_QTY +
        $FINISHING_JADI_ULANG_QTY + $COMPACT_QTY + $NCP_QTY + $LIPAT_INSPEK_FIN_QTY + $PERSIAPAN_QTY;

    $TOTAL_GEROBAK_SUMMARY = $BELAH_GEROBAK + $STEAMER_GEROBAK + $OVEN_GREIGE_GEROBAK + $OVEN_DYE_GEROBAK +
        $OVEN_STENTER_GEROBAK + $OVEN_OBAT_GEROBAK + $FINISHING_1_GEROBAK + $PADDER_GEROBAK +
        $FINISHING_JADI_ULANG_GEROBAK + $COMPACT_GEROBAK + $NCP_GEROBAK + $LIPAT_INSPEK_FIN_GEROBAK + $PERSIAPAN_GEROBAK;

    $TOTAL_KELUAR_SUMMARY = $BELAH_KELUAR + $STEAMER_KELUAR + $OVEN_GREIGE_KELUAR + $OVEN_DYE_KELUAR +
        $OVEN_STENTER_KELUAR + $OVEN_OBAT_KELUAR + $FINISHING_1_KELUAR + $PADDER_KELUAR +
        $FINISHING_JADI_ULANG_KELUAR + $COMPACT_KELUAR + $NCP_KELUAR + $LIPAT_INSPEK_FIN_KELUAR + $PERSIAPAN_KELUAR;

    echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
    echo '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>';
    echo '<body>';
?>

<table border="1" cellspacing="0" cellpadding="3">
    <tr>
    <td colspan="4" align="center" bgcolor="#FFFF00">
  <b>DEPT. FINISHING /                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <?php echo $tgl . ' ' . $bln . ' ' . $thn . ' (' . strtoupper($waktu) . ')' ?></b>
</td>

    </tr>
    <tr style="font-weight:bold; background:#f2f2f2;">
        <td align="center">FINISHING</td>
        <td align="center">KELUAR</td>
        <td align="center">QTY SISA</td>
        <td align="center">GEROBAK</td>
    </tr>
    <?php
        $rows = [
            'BELAH'                  => [$BELAH_QTY, $BELAH_GEROBAK, $BELAH_KELUAR],
            'STEAMER'                => [$STEAMER_QTY, $STEAMER_GEROBAK, $STEAMER_KELUAR],
            'PRESET / OVEN GREIGE'   => [$OVEN_GREIGE_QTY, $OVEN_GREIGE_GEROBAK, $OVEN_GREIGE_KELUAR],
            'OVEN DYE'               => [$OVEN_DYE_QTY, $OVEN_DYE_GEROBAK, $OVEN_DYE_KELUAR],
            'OVEN STENTER'           => [$OVEN_STENTER_QTY, $OVEN_STENTER_GEROBAK, $OVEN_STENTER_KELUAR],
            'OVEN + OBAT'            => [$OVEN_OBAT_QTY, $OVEN_OBAT_GEROBAK, $OVEN_OBAT_KELUAR],
            'FINISHING 1x'           => [$FINISHING_1_QTY, $FINISHING_1_GEROBAK, $FINISHING_1_KELUAR],
            'PADDER'                 => [$PADDER_QTY, $PADDER_GEROBAK, $PADDER_KELUAR],
            'FINISHING JADI + ULANG' => [$FINISHING_JADI_ULANG_QTY, $FINISHING_JADI_ULANG_GEROBAK, $FINISHING_JADI_ULANG_KELUAR],
            'COMPACT'                => [$COMPACT_QTY, $COMPACT_GEROBAK, $COMPACT_KELUAR],
            'NCP PAKAI GEROBAK'      => [$NCP_QTY, $NCP_GEROBAK, $NCP_KELUAR],
            'LIPAT + INSPEK FIN'     => [$LIPAT_INSPEK_FIN_QTY, $LIPAT_INSPEK_FIN_GEROBAK, $LIPAT_INSPEK_FIN_KELUAR],
            'PERSIAPAN'              => [$PERSIAPAN_QTY, $PERSIAPAN_GEROBAK, $PERSIAPAN_KELUAR],
        ];

        foreach ($rows as $proses => [$qty, $gerobak, $keluar]) {
            $formattedKeluar  = (! empty($keluar)) ? number_format($keluar, 2) : '-';
            $formattedQty     = (! empty($qty)) ? number_format($qty, 2) : '-';
            $formattedGerobak = (! empty($gerobak)) ? $gerobak : '-';

            echo "<tr><td>$proses</td>
            <td align='center'>$formattedKeluar</td>
            <td align='center'>$formattedQty</td>
            <td align='center'>$formattedGerobak</td></tr>";
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

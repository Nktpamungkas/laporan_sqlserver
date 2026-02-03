<?php
    header("content-type:application/vnd-ms-excel");
    header("content-disposition:attachment;filename=Memo_Penting_Libre_" . date('Ymd_His') . ".xls");
    header('Cache-Control: max-age=0');

    include_once "utils/helper.php";

    if (count($_GET) < 2) {
        exit('Parameter filter harus di isi.');
    }

    session_start();
    require_once "koneksi.php";

    $prod_order     = $_GET['prod_order'] ?? '';
    $prod_demand    = $_GET['prod_demand'] ?? '';
    $no_order       = $_GET['no_order'] ?? '';
    $tgl1           = $_GET['tgl1'] ?? '';
    $tgl2           = $_GET['tgl2'] ?? '';
    $no_po          = $_GET['no_po'] ?? '';
    $article_group  = $_GET['article_group'] ?? '';
    $article_code   = $_GET['article_code'] ?? '';
    $nama_warna     = $_GET['nama_warna'] ?? '';
    $kkoke          = $_GET['kkoke'] ?? '';
    $orderline      = $_GET['orderline'] ?? '';

    // Prepare API request body
    $api_body = [
        'noOrder' => $no_order,
        'prodDemand' => $prod_demand,
        'prodOrder' => $prod_order,
        'tgl1' => $tgl1,
        'tgl2' => $tgl2,
        'noPo' => $no_po,
        'articleGroup' => $article_group,
        'articleCode' => $article_code,
        'namaWarna' => $nama_warna,
        'kkoke' => $kkoke,
        'orderline' => $orderline
    ];

    // Call API using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://10.0.1.154:8080/api/ppc/memo-penting");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($api_body));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $api_response = curl_exec($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($curl_error) {
        die("Error calling API: " . $curl_error);
    }

    $api_data = json_decode($api_response, true);
    
    if (!$api_data || !isset($api_data['success']) || !$api_data['success']) {
        die("Error: API tidak mengembalikan data yang valid");
    }

    $data_list = $api_data['data'] ?? [];
?>
<table border="1">
    <thead>
        <tr>
            <th>TGL BUKA KARTU</th>
            <th>PELANGGAN</th>
            <th>NO ORDER</th>
            <th>NO PO</th>
            <th>KETERANGAN PRODUCT</th>
            <th>LEBAR</th>
            <th>GRAMASI</th>
            <th>WARNA</th>
            <th>NO WARNA</th>
            <th>DELIVERY</th>
            <th>DELIVERY ACTUAL</th>
            <th>GREIGE AWAL</th>
            <th>GREIGE AKHIR</th>
            <th>BAGI KAIN TGL</th>
            <th>ROLL</th>
            <th>BRUTO BAGI KAIN</th>
            <th>QTY SALINAN</th>
            <th>QTY PACKING</th>
            <th>NETTO KG</th>
            <th>NETTO YD MTR</th>
            <th>QTY KURANG KG</th>
            <th>QTY KURANG YD MTR</th>
            <th>DELAY</th>
            <th>TARGET SELESAI</th>
            <th>KODE DEPT</th>
            <th>STATUS TERAKHIR</th>
            <th>NOMOR MESIN SCHEDULE</th>
            <th>NOMOR URUT SCHEDULE</th>
            <th>DELAY PROGRESS STATUS</th>
            <th>PROGRESS STATUS</th>
            <th>TOTAL HARI</th>
            <th>LOT</th>
            <th>NO DEMAND</th>
            <th>NO KARTU KERJA</th>
            <th>CATATAN PO GREIGE</th>
            <th>KETERANGAN</th>
            <th>ORIGINAL PD CODE</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($data_list as $rowdb2) {
            // Skip jika kkoke = 'tidak' dan status adalah KK Oke atau Entered
            if ($kkoke == 'tidak') {
                $progress_status = $rowdb2['PROGRESS_STATUS'] ?? '';
                if (stripos($progress_status, 'KK Oke') !== false || 
                    stripos($progress_status, 'Entered') !== false) {
                    continue;
                }
            }
        ?>
        <tr>
            <td><?= $rowdb2['TGL_BUKA_KARTU'] ?? ''; ?></td>
            <td><?= $rowdb2['PELANGGAN'] ?? ''; ?></td>
            <td><?= $rowdb2['NO_ORDER'] ?? ''; ?></td>
            <td><?= $rowdb2['NO_PO'] ?? ''; ?></td>
            <td><?= $rowdb2['KETERANGAN_PRODUCT'] ?? ''; ?></td>
            <td><?= $rowdb2['LEBAR'] ?? ''; ?></td>
            <td><?= $rowdb2['GRAMASI'] ?? ''; ?></td>
            <td><?= $rowdb2['WARNA'] ?? ''; ?></td>
            <td><?= $rowdb2['NO_WARNA'] ?? ''; ?></td>
            <td><?= $rowdb2['DELIVERY'] ?? ''; ?></td>
            <td><?= $rowdb2['DELIVERY_ACTUAL'] ?? ''; ?></td>
            <td><?= $rowdb2['GREIGE_AWAL'] ?? ''; ?></td>
            <td><?= $rowdb2['GREIGE_AKHIR'] ?? ''; ?></td>
            <td><?= $rowdb2['BAGI_KAIN_TGL'] ?? ''; ?></td>
            <td><?= $rowdb2['ROLL'] ?? ''; ?></td>
            <td><?= $rowdb2['BRUTO_BAGI_KAIN'] ?? ''; ?></td>
            <td><?= $rowdb2['QTY_SALINAN'] ?? ''; ?></td>
            <td><?= $rowdb2['QTY_PACKING'] ?? ''; ?></td>
            <td><?= $rowdb2['NETTO_KG'] ?? ''; ?></td>
            <td><?= $rowdb2['NETTO_YD_MTR'] ?? ''; ?></td>
            <td><?= $rowdb2['QTY_KURANG_KG'] ?? ''; ?></td>
            <td><?= $rowdb2['QTY_KURANG_YD_MTR'] ?? ''; ?></td>
            <td><?= $rowdb2['DELAY'] ?? ''; ?></td>
            <td><?= $rowdb2['TARGET_SELESAI'] ?? ''; ?></td>
            <td><?= $rowdb2['KODE_DEPT'] ?? ''; ?></td>
            <td><?= $rowdb2['STATUS_TERAKHIR'] ?? ''; ?></td>
            <td><?= $rowdb2['NOMOR_MESIN_SCHEDULE'] ?? ''; ?></td>
            <td><?= $rowdb2['NOMOR_URUT_SCHEDULE'] ?? ''; ?></td>
            <td><?= $rowdb2['DELAY_PROGRESS_STATUS'] ?? ''; ?></td>
            <td><?= $rowdb2['PROGRESS_STATUS'] ?? ''; ?></td>
            <td><?= $rowdb2['TOTAL_HARI'] ?? ''; ?></td>
            <td><?= $rowdb2['LOT'] ?? ''; ?></td>
            <td><?= $rowdb2['NO_DEMAND'] ?? ''; ?></td>
            <td><?= $rowdb2['NO_KARTU_KERJA'] ?? ''; ?></td>
            <td><?= $rowdb2['CATATAN_PO_GREIGE'] ?? ''; ?></td>
            <td><?= $rowdb2['KETERANGAN'] ?? ''; ?></td>
            <td><?= $rowdb2['ORIGINAL_PD_CODE'] ?? ''; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
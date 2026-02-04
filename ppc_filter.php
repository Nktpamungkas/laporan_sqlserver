<?php
// Enable output buffering untuk speed up rendering
ob_start();

$start_time = microtime(true);
// ini_set("error_reporting", 1);
// Pastikan session path bisa ditulis (hindari warning permission)
ini_set('session.save_path', __DIR__ . '/tmp');
if (!is_dir(__DIR__ . '/tmp')) {
    mkdir(__DIR__ . '/tmp', 0777, true);
}
session_start();
require_once "koneksi.php";
include_once "utils/helper.php";

$kkoke_1 = isset($_GET['kkoke']) ? $_GET['kkoke'] : (isset($_POST['kkoke']) ? $_POST['kkoke'] : 'tidak');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>PPC - Memo Penting</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="#">
    <meta name="keywords" content="Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="#">
    <link rel="icon" href="files\assets\images\favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="files\bower_components\bootstrap\css\bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\icon\themify-icons\themify-icons.css">
    <link rel="stylesheet" type="text/css" href="files\assets\icon\icofont\css\icofont.css">
    <link rel="stylesheet" type="text/css" href="files\assets\icon\feather\css\feather.css">
    <link rel="stylesheet" type="text/css" href="files\assets\pages\prism\prism.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\style.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\jquery.mCustomScrollbar.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\pcoded-horizontal.min.css">
    <link rel="stylesheet" type="text/css" href="files\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\pages\data-table\css\buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="files\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css">
    <script src="TabCounter.js"></script>
    
    <style>
        /* Loading Overlay Styles */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .loading-overlay.active {
            display: flex;
        }
        
        .loading-content {
            background: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
            min-width: 400px;
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #4099ff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .loading-text {
            font-size: 18px;
            color: #333;
            margin-bottom: 15px;
            font-weight: 500;
        }
        
        .loading-subtext {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }
        
        .progress-container {
            width: 100%;
            background-color: #f0f0f0;
            border-radius: 20px;
            overflow: hidden;
            height: 30px;
            margin-bottom: 10px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        
        .progress-bar-custom {
            height: 100%;
            background: linear-gradient(90deg, #4099ff 0%, #73b4ff 100%);
            width: 0%;
            transition: width 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        
        .loading-details {
            font-size: 12px;
            color: #888;
            margin-top: 10px;
        }
        
        .pulse {
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</head>

<?php require_once 'header.php'; ?>
<span class="count" hidden></span>
<script>
    tabCount.onTabChange(function(count) {
        document.querySelector(".count").innerText = count;
        document.querySelector("title").innerText = count + " Tabs opened Memo Penting.";
    }, true);
</script>

<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <div class="loading-text" id="loadingText">Memproses data...</div>
            <div class="loading-subtext pulse" id="loadingSubtext">Mohon tunggu sebentar</div>
            <div class="progress-container">
                <div class="progress-bar-custom" id="progressBar">0%</div>
            </div>
            <div class="loading-details" id="loadingDetails">Estimasi waktu: menghitung...</div>
        </div>
    </div>

    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header" style="background-color: #ffeb3b; padding: 12px; font-family: 'Courier New', monospace;">
                                        <h5>Reminder :</h5><br>
                                        - Jika data terasa mulai <b>lambat</b> cobalah untuk klik tombol <b><i class="icofont icofont-refresh"></i> Reset</b> untuk menghapus semua history pencarian<br>
                                        - Khusus Memo Penting <b>dilarang</b> membuka lebih dari 1 Tab atau 1 Browser<br>
                                        - Pencarian WARNA hanya bisa dikombinasikan.
                                    </div>
                                    <div class="card-header">
                                        <h5>Filter Data</h5>
                                    </div>
                                    <div class="card-block">
                                        <form action="" method="post" id="filterForm">
                                            <div class="row">
                                                <div class="col-sm-12 col-xl-2 m-b-30">
                                                    <h4 class="sub-title">Bon Order</h4>
                                                    <input type="text" name="no_order" class="form-control" onkeyup="this.value = this.value.toUpperCase()" value="<?php if (isset($_POST['submit'])) {
                                                                                                                                                                        echo $_POST['no_order'];
                                                                                                                                                                    } elseif ($_GET['no_order']) {
                                                                                                                                                                        echo $_GET['no_order'];
                                                                                                                                                                    } ?>">
                                                </div>
                                                <div class="col-sm-12 col-xl-2 m-b-30">
                                                    <h4 class="sub-title">Production Demand</h4>
                                                    <input type="text" name="prod_demand" class="form-control" value="<?php if (isset($_POST['submit'])) {
                                                                                                                            echo $_POST['prod_demand'];
                                                                                                                        } elseif ($_GET['demand']) {
                                                                                                                            echo $_GET['demand'];
                                                                                                                        } ?>">
                                                </div>
                                                <div class="col-sm-12 col-xl-2 m-b-30">
                                                    <h4 class="sub-title">Production Order</h4>
                                                    <input type="text" name="prod_order" class="form-control" value="<?php if (isset($_POST['submit'])) {
                                                                                                                            echo $_POST['prod_order'];
                                                                                                                        } elseif ($_GET['prod_order']) {
                                                                                                                            echo $_GET['prod_order'];
                                                                                                                        } ?>">
                                                </div>
                                                <div class="col-sm-12 col-xl-2 m-b-30">
                                                    <h4 class="sub-title">Dari Tanggal</h4>
                                                    <?php
                                                    $now = date('H:i');
                                                    $jam_selesai = '02:00';
                                                    $jam_mulai = '08:30';

                                                    if ($now >= $jam_mulai && $now <= $jam_selesai) {
                                                        $readonly = "readonly";
                                                    } else {
                                                        $readonly = "";
                                                    }
                                                    ?>
                                                    <input type="date" name="tgl1" class="form-control" id="tgl1" value="<?php if (isset($_POST['submit'])) {
                                                                                                                                echo $_POST['tgl1'];
                                                                                                                            } ?>" <?= $readonly; ?>>
                                                </div>
                                                <div class="col-sm-12 col-xl-2 m-b-30">
                                                    <h4 class="sub-title">Sampai Tanggal</h4>
                                                    <input type="date" name="tgl2" class="form-control" id="tgl2" value="<?php if (isset($_POST['submit'])) {
                                                                                                                                echo $_POST['tgl2'];
                                                                                                                            } ?>" <?= $readonly; ?>>
                                                </div>
                                                <div class="col-sm-12 col-xl-2 m-b-30">
                                                    <h4 class="sub-title">Nomor PO</h4>
                                                    <input type="text" name="no_po" class="form-control" value="<?php if (isset($_POST['submit'])) {
                                                                                                                    echo $_POST['no_po'];
                                                                                                                } ?>">
                                                </div>
                                                <div class="col-sm-12 col-xl-1 m-b-30">
                                                    <h4 style="font-size: 12px;" class="sub-title">Article Group</h4>
                                                    <input type="text" name="article_group" class="form-control" value="<?php if (isset($_POST['submit'])) {
                                                                                                                            echo $_POST['article_group'];
                                                                                                                        } ?>">
                                                </div>
                                                <div class="col-sm-12 col-xl-1 m-b-30">
                                                    <h4 style="font-size: 12px;" class="sub-title">Article Code</h4>
                                                    <input type="text" name="article_code" class="form-control" value="<?php if (isset($_POST['submit'])) {
                                                                                                                            echo $_POST['article_code'];
                                                                                                                        } ?>">
                                                </div>
                                                <div class="col-sm-12 col-xl-2 m-b-30">
                                                    <h4 class="sub-title">Nama Warna</h4>
                                                    <input type="text" name="nama_warna" class="form-control" value="<?php if (isset($_POST['submit'])) {
                                                                                                                            echo $_POST['nama_warna'];
                                                                                                                        } ?>">
                                                </div>
                                                <div class="col-sm-12 col-xl-2 m-b-30">
                                                    <h4 class="sub-title">KK OKE</h4>
                                                    <select name="kkoke" class="form-control">
                                                        <option value="tidak" <?php if ($kkoke_1 == 'tidak') {
                                                                                    echo "SELECTED";
                                                                                } else {
                                                                                    echo "";
                                                                                } ?>>Jangan sertakan KK OKE</option>
                                                        <option value="ya" <?php if ($kkoke_1 == 'ya') {
                                                                                echo "SELECTED";
                                                                            } else {
                                                                                echo "";
                                                                            } ?>>Sertakan KK OKE</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-12 col-xl-12 m-b-30">
                                                    <button type="submit" name="submit" class="btn btn-primary" id="btnCari">
                                                        <i class="icofont icofont-search-alt-1"></i> Cari data
                                                    </button>
                                                    <a class="btn btn-warning" href="ppc_filter.php"><i class="icofont icofont-refresh"></i> Reset</a>
                                                    <button type="submit" name="submit_excel" class="btn btn-danger" id="btnExcel">
                                                        <i class="icofont icofont-download"></i> Download data
                                                    </button>

                                                    <?php if (isset($_POST['submit'])) : ?>
                                                        <a class="btn btn-mat btn-success cetak-excel-btn" href="ppc_memopenting-excel.php?no_order=<?= $_POST['no_order']; ?>&tgl1=<?= $_POST['tgl1']; ?>&tgl2=<?= $_POST['tgl2']; ?>&operation=<?= $_POST['operation']; ?>">CETAK EXCEL</a>
                                                        <a class="btn btn-mat btn-warning cetak-excel-btn" href="ppc_memopenting-libre.php?no_order=<?= $_POST['no_order']; ?>&tgl1=<?= $_POST['tgl1']; ?>&tgl2=<?= $_POST['tgl2']; ?>&operation=<?= $_POST['operation']; ?>">CETAK EXCEL (LIBRE)</a>
                                                        <a class="btn btn-mat btn-danger cetak-excel-btn" href="ppc_memopenting-excel_qc.php?no_order=<?= $_POST['no_order']; ?>&tgl1=<?= $_POST['tgl1']; ?>&tgl2=<?= $_POST['tgl2']; ?>&operation=<?= $_POST['operation']; ?>">CETAK EXCEL (QC)</a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php if (isset($_POST['submit']) or ($_GET['demand'] and $_GET['prod_order']) or $_GET['no_order'] or $_GET['prod_order']) : ?>
                                    <div class="card">
                                        <div class="card-block">
                                            <div class="dt-responsive table-responsive">
                                                <table id="lang-dt" class="table table-striped table-bordered nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th>TGL BUKA KARTU</th>
                                                            <th>PELANGGAN</th>
                                                            <th>NO. ORDER</th>
                                                            <th>NO. PO</th>
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
                                                            <th>BRUTO/BAGI KAIN</th>
                                                            <th title="Sumber data: &#013; 1. Production Order &#013; 2. Reservation &#013; 3. KFF/KGF User Primary Quantity">QTY SALINAN</th>
                                                            <th title="Sumber data: &#013; 1. Production Demand &#013; 2. Bagian group Entered quantity &#013; 3. User Primary Quantity">QTY PACKING</th>
                                                            <th>NETTO(kg)</th>
                                                            <th>NETTO(yd/mtr)</th>
                                                            <th>QTY KURANG (KG)</th>
                                                            <th>QTY KURANG (YD/MTR)</th>
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
                                                            <th>ORIGINAL PD CODE</th>
                                                            <th>CATATAN PO GREIGE</th>
                                                            <th>KETERANGAN</th>
                                                            <th>RE PROSES ADDITIONAL</th>
                                                            <?php if ($_SERVER['REMOTE_ADDR'] == '10.0.5.132') : ?>
                                                                <th>Only Nilo</th>
                                                            <?php endif; ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        // Prepare filter parameters
                                                        $prod_order     = $_POST['prod_order'] ?? $_GET['prod_order'] ?? '';
                                                        $prod_demand    = $_POST['prod_demand'] ?? $_GET['prod_demand'] ?? '';
                                                        $no_order       = $_POST['no_order'] ?? $_GET['no_order'] ?? '';
                                                        $orderline      = $_GET['orderline'] ?? '';
                                                        $tgl1           = $_POST['tgl1'] ?? '';
                                                        $tgl2           = $_POST['tgl2'] ?? '';
                                                        $no_po          = $_POST['no_po'] ?? '';
                                                        $article_group  = $_POST['article_group'] ?? '';
                                                        $article_code   = $_POST['article_code'] ?? '';
                                                        $nama_warna     = $_POST['nama_warna'] ?? '';
                                                        $kkoke          = $_POST['kkoke'] ?? $_GET['kkoke'] ?? '';

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
                                                        
                                                        $api_start = microtime(true);
                                                        
                                                        // Call API using cURL with timeout
                                                        $ch = curl_init();
                                                        curl_setopt($ch, CURLOPT_URL, "http://localhost:8080/api/ppc/memo-penting");
                                                        curl_setopt($ch, CURLOPT_POST, true);
                                                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($api_body));
                                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                                                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
                                                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                                            'Content-Type: application/json'
                                                        ]);

                                                        $api_response = curl_exec($ch);
                                                        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                                        $curl_error = curl_error($ch);
                                                        curl_close($ch);
                                                        
                                                        $api_duration = round((microtime(true) - $api_start) * 1000, 2);
                                                        
                                                        echo "<!-- API Call Duration: {$api_duration}ms | HTTP Code: {$http_code} -->\n";

                                                        // Process API response
                                                        $api_data = json_decode($api_response, true);
                                                        
                                                        if ($curl_error) {
                                                            echo '<tr><td colspan="39">Error: ' . htmlspecialchars($curl_error) . '</td></tr>';
                                                        } elseif ($api_data && isset($api_data['success']) && $api_data['success']) {
                                                            $data_list = $api_data['data'] ?? [];
                                                            
                                                            foreach ($data_list as $rowdb2) {
                                                                $rowdb2 = (object) $rowdb2;
                                                        ?>
                                                            <?php
                                                            $status_operation = $rowdb2->PROGRESS_STATUS ?? 'Entered';
                                                            $kode_dept = $rowdb2->KODE_DEPT ?? '';
                                                            $status_terakhir = $rowdb2->STATUS_TERAKHIR ?? '';
                                                            $status = 'A';
                                                            ?>
                                                                <tr>
                                                                    <td><?= $rowdb2->TGL_BUKA_KARTU ?? ''; ?></td>
                                                                    <td><?= $rowdb2->PELANGGAN ?? ''; ?></td>
                                                                    <td><?= $rowdb2->NO_ORDER ?? ''; ?></td>
                                                                    <td><?= $rowdb2->NO_PO ?? ''; ?></td>
                                                                    <td><?= $rowdb2->KETERANGAN_PRODUCT ?? ''; ?></td>
                                                                    <td><?= $rowdb2->LEBAR ?? ''; ?></td>
                                                                    <td><?= $rowdb2->GRAMASI ?? ''; ?></td>
                                                                    <td><?= $rowdb2->WARNA ?? ''; ?></td>
                                                                    <td><?= $rowdb2->NO_WARNA ?? ''; ?></td>
                                                                    <td><?= $rowdb2->DELIVERY ?? ''; ?></td>
                                                                    <td><?= $rowdb2->DELIVERY_ACTUAL ?? ''; ?></td>
                                                                    <td><?= $rowdb2->GREIGE_AWAL ?? ''; ?></td>
                                                                    <td><?= $rowdb2->GREIGE_AKHIR ?? ''; ?></td>
                                                                    <td><?= $rowdb2->BAGI_KAIN_TGL ?? ''; ?></td>
                                                                    <td><?= $rowdb2->ROLL ?? ''; ?></td>
                                                                    <td><?= $rowdb2->BRUTO_BAGI_KAIN ?? ''; ?></td>
                                                                    <td><?= $rowdb2->QTY_SALINAN ?? ''; ?></td>
                                                                    <td><?= $rowdb2->QTY_PACKING ?? ''; ?></td>
                                                                    <td><?= $rowdb2->NETTO_KG ?? ''; ?></td>
                                                                    <td><?= $rowdb2->NETTO_YD_MTR ?? ''; ?></td>
                                                                    <td><?= $rowdb2->QTY_KURANG_KG ?? ''; ?></td>
                                                                    <td><?= $rowdb2->QTY_KURANG_YD_MTR ?? ''; ?></td>
                                                                    <td><?= $rowdb2->DELAY ?? ''; ?></td>
                                                                    <td><?= $rowdb2->TARGET_SELESAI ?? ''; ?></td>
                                                                    <td><?= $rowdb2->KODE_DEPT ?? ''; ?></td>
                                                                    <td><?= $rowdb2->STATUS_TERAKHIR ?? ''; ?></td>
                                                                    <td><?= $rowdb2->NOMOR_MESIN_SCHEDULE ?? ''; ?></td>
                                                                    <td><?= $rowdb2->NOMOR_URUT_SCHEDULE ?? ''; ?></td>
                                                                    <td><?= $rowdb2->DELAY_PROGRESS_STATUS ?? ''; ?></td>
                                                                    <td><?= $rowdb2->PROGRESS_STATUS ?? ''; ?></td>
                                                                    <td><?= $rowdb2->TOTAL_HARI ?? ''; ?></td>
                                                                    <td><?= $rowdb2->LOT ?? ''; ?></td>
                                                                    <td><a target="_BLANK" href="http://online.indotaichen.com/laporan/ppc_filter_steps.php?demand=<?= $rowdb2->NO_DEMAND ?? ''; ?>&prod_order=<?= $rowdb2->NO_KARTU_KERJA ?? ''; ?>"><?= $rowdb2->NO_DEMAND ?? ''; ?></a></td>
                                                                    <td><?= $rowdb2->NO_KARTU_KERJA ?? ''; ?></td>
                                                                    <td><?= $rowdb2->ORIGINAL_PD_CODE ?? ''; ?></td>
                                                                    <td><?= $rowdb2->CATATAN_PO_GREIGE ?? ''; ?></td>
                                                                    <td><?= $rowdb2->KETERANGAN ?? ''; ?></td>
                                                                    <td><?= $rowdb2->RE_PROSES_ADDITIONAL ?? '0'; ?></td>
                                                                    <?php if ($_SERVER['REMOTE_ADDR'] == '10.0.5.132') : ?>
                                                                        <td>
                                                                            <?= isset($groupstep_option) ? $groupstep_option : ''; ?>
                                                                            <?= $status ?? ''; ?>
                                                                        </td>
                                                                    <?php endif; ?>
                                                                </tr>
                                                            <?php 
                                                            }
                                                        } else {
                                                            echo '<tr><td colspan="39">Error fetching data from API or no data found</td></tr>';
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif (isset($_POST['submit_excel'])) : ?>
                                    <?php
                                    ini_set("error_reporting", 1);
                                    
                                    $prod_order     = $_POST['prod_order'] ?? $_GET['prod_order'] ?? '';
                                    $prod_demand    = $_POST['prod_demand'] ?? $_GET['prod_demand'] ?? '';
                                    $no_order       = $_POST['no_order'] ?? '';
                                    $tgl1           = $_POST['tgl1'] ?? '';
                                    $tgl2           = $_POST['tgl2'] ?? '';
                                    $no_po          = $_POST['no_po'] ?? '';
                                    $article_group  = $_POST['article_group'] ?? '';
                                    $article_code   = $_POST['article_code'] ?? '';
                                    $nama_warna     = $_POST['nama_warna'] ?? '';
                                    $kkoke          = $_POST['kkoke'] ?? $_GET['kkoke'] ?? '';
                                    $orderline      = $_GET['orderline'] ?? '';

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

                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, "http://localhost:8080/api/ppc/memo-penting");
                                    curl_setopt($ch, CURLOPT_POST, true);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($api_body));
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                                    
                                    $api_response = curl_exec($ch);
                                    curl_close($ch);
                                    
                                    $api_data = json_decode($api_response, true);
                                    
                                    if ($api_data && isset($api_data['success']) && $api_data['success']) {
                                        $_SESSION['export_data'] = $api_data['data'];
                                        $_SESSION['export_filters'] = $api_body;
                                    }

                                    echo '<script>window.location.href = "ppc_memopenting-excel.php?prod_order=' . $prod_order . '&prod_demand=' . $prod_demand . '&no_order=' . $no_order . '&tgl1=' . $tgl1 . '&tgl2=' . $tgl2 . '&no_po=' . $no_po . '&article_group=' . $article_group . '&article_code=' . $article_code . '&nama_warna=' . $nama_warna . '&kkoke=' . $kkoke . '&akses=catch";</script>';
                                    ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Progress Loading Script
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filterForm');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const progressBar = document.getElementById('progressBar');
            const loadingText = document.getElementById('loadingText');
            const loadingSubtext = document.getElementById('loadingSubtext');
            const loadingDetails = document.getElementById('loadingDetails');
            
            // Messages untuk berbagai tahap loading
            const loadingMessages = [
                { percent: 10, text: 'Menghubungkan ke server...', subtext: 'Memulai proses' },
                { percent: 25, text: 'Memproses filter data...', subtext: 'Menyiapkan parameter' },
                { percent: 40, text: 'Mengambil data dari database...', subtext: 'Ini mungkin memakan waktu' },
                { percent: 60, text: 'Memproses data...', subtext: 'Hampir selesai' },
                { percent: 80, text: 'Menyiapkan tampilan...', subtext: 'Tinggal sedikit lagi' },
                { percent: 95, text: 'Finalisasi...', subtext: 'Sebentar lagi selesai' }
            ];
            
            const excelLoadingMessages = [
                { percent: 15, text: 'Menyiapkan file Excel...', subtext: 'Memulai proses export' },
                { percent: 35, text: 'Mengambil data...', subtext: 'Mengumpulkan semua data' },
                { percent: 55, text: 'Membuat format Excel...', subtext: 'Menyusun kolom dan baris' },
                { percent: 75, text: 'Memproses styling...', subtext: 'Menambahkan format' },
                { percent: 90, text: 'Finalisasi file...', subtext: 'Hampir selesai' }
            ];
            
            let currentProgress = 0;
            let progressInterval;
            let startTime;
            
            function updateProgress(targetPercent, message, submessage) {
                const increment = (targetPercent - currentProgress) / 20;
                
                const interval = setInterval(() => {
                    currentProgress += increment;
                    
                    if (currentProgress >= targetPercent) {
                        currentProgress = targetPercent;
                        clearInterval(interval);
                    }
                    
                    progressBar.style.width = currentProgress + '%';
                    progressBar.textContent = Math.round(currentProgress) + '%';
                }, 50);
                
                loadingText.textContent = message;
                loadingSubtext.textContent = submessage;
                
                // Update estimasi waktu
                const elapsed = (Date.now() - startTime) / 1000;
                const estimated = Math.max(5, Math.round((elapsed / currentProgress) * (100 - currentProgress)));
                loadingDetails.textContent = `Waktu berlalu: ${Math.round(elapsed)}s | Estimasi sisa: ${estimated}s`;
            }
            
            function simulateProgress(messages) {
                let stage = 0;
                
                progressInterval = setInterval(() => {
                    if (stage < messages.length) {
                        const msg = messages[stage];
                        updateProgress(msg.percent, msg.text, msg.subtext);
                        stage++;
                    } else {
                        clearInterval(progressInterval);
                    }
                }, 2000); // Update setiap 2 detik
            }
            
            // Handle button clicks
            const btnCari = document.getElementById('btnCari');
            const btnExcel = document.getElementById('btnExcel');
            
            if (btnCari) {
                btnCari.addEventListener('click', function(e) {
                    // Validasi form sederhana
                    const formInputs = form.querySelectorAll('input[type="text"], input[type="date"], select');
                    let hasValue = false;
                    
                    formInputs.forEach(input => {
                        if (input.value.trim() !== '') {
                            hasValue = true;
                        }
                    });
                    
                    if (!hasValue) {
                        alert('Silakan isi minimal satu filter!');
                        e.preventDefault();
                        return false;
                    }
                    
                    // Tampilkan loading
                    startTime = Date.now();
                    currentProgress = 0;
                    loadingOverlay.classList.add('active');
                    progressBar.style.width = '0%';
                    progressBar.textContent = '0%';
                    simulateProgress(loadingMessages);
                });
            }
            
            if (btnExcel) {
                btnExcel.addEventListener('click', function(e) {
                    startTime = Date.now();
                    currentProgress = 0;
                    loadingOverlay.classList.add('active');
                    loadingText.textContent = 'Menyiapkan file Excel...';
                    loadingSubtext.textContent = 'Mohon tunggu sebentar';
                    progressBar.style.width = '0%';
                    progressBar.textContent = '0%';
                    simulateProgress(excelLoadingMessages);
                });
            }
            
            // Handle CETAK EXCEL buttons (yang muncul setelah data ditampilkan)
            const cetakExcelButtons = document.querySelectorAll('.cetak-excel-btn');
            cetakExcelButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    const buttonText = this.textContent.trim();
                    
                    startTime = Date.now();
                    currentProgress = 0;
                    loadingOverlay.classList.add('active');
                    loadingText.textContent = 'Membuat file ' + buttonText + '...';
                    loadingSubtext.textContent = 'Mohon tunggu, file sedang diproses';
                    progressBar.style.width = '0%';
                    progressBar.textContent = '0%';
                    
                    simulateProgress(excelLoadingMessages);
                    
                    // Redirect setelah loading dimulai
                    setTimeout(() => {
                        window.location.href = url;
                        
                        // Hide loading setelah beberapa detik (karena file akan di-download)
                        setTimeout(() => {
                            currentProgress = 100;
                            progressBar.style.width = '100%';
                            progressBar.textContent = '100%';
                            loadingText.textContent = 'File siap diunduh!';
                            loadingSubtext.textContent = 'Silakan cek folder download Anda';
                            
                            setTimeout(() => {
                                loadingOverlay.classList.remove('active');
                                clearInterval(progressInterval);
                            }, 2000);
                        }, 8000); // Hide after 8 seconds
                    }, 500);
                });
            });
            
            // Jika halaman sudah selesai load dan ada data, sembunyikan loading
            window.addEventListener('load', function() {
                setTimeout(() => {
                    if (loadingOverlay.classList.contains('active')) {
                        currentProgress = 100;
                        progressBar.style.width = '100%';
                        progressBar.textContent = '100%';
                        loadingText.textContent = 'Selesai!';
                        loadingSubtext.textContent = 'Data berhasil dimuat';
                        
                        setTimeout(() => {
                            loadingOverlay.classList.remove('active');
                            clearInterval(progressInterval);
                        }, 500);
                    }
                }, 500);
            });
        });
    </script>
    <script src="sse-progress.js"></script>
</body>
<?php require_once 'footer.php'; ?>

<?php
// Log performance di akhir
$end_time       = microtime(true);
$load_duration  = round(($end_time - $start_time), 3);
$ip_address     = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
$datetime       = date('Y-m-d H:i:s');
$url            = $_SERVER['REQUEST_URI'] ?? '';

if ($con_nowprd) {
    $sql = "INSERT INTO nowprd.log_loading_ppc (ip_address, url, load_duration, source, accessed_at)
            VALUES (?, ?, ?, 'server', ?)";
    $params = [$ip_address, $url, $load_duration, $datetime];
    $stmt = sqlsrv_query($con_nowprd, $sql, $params);
}

ob_end_flush();
?>
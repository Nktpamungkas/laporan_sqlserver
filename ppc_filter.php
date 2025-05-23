<?php
$start_time = microtime(true);
// ini_set("error_reporting", 1);
session_start();
require_once "koneksi.php";
include_once "utils/helper.php";
sqlsrv_query($con_nowprd, "DELETE FROM nowprd.itxview_memopentingppc WHERE CREATEDATETIME BETWEEN DATEADD(DAY, -3, GETDATE()) AND DATEADD(DAY, -1, GETDATE());");
sqlsrv_query($con_nowprd, "DELETE FROM nowprd.itxview_memopentingppc WHERE IPADDRESS = '$_SERVER[REMOTE_ADDR]'");

// echo($_GET['prod_order']);
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
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet"> -->
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
</head>
<?php require_once 'header.php'; ?>
<span class="count" hidden></span>
<script>
    tabCount.onTabChange(function(count) {
        document.querySelector(".count").innerText = count;
        document.querySelector("title").innerText = count + " Tabs opened Memo Penting.";
    }, true);
</script>
<?php
    $end_time       = microtime(true);
    $load_duration  = round(($end_time - $start_time), 3);
    $ip_address     = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $datetime       = date('Y-m-d H:i:s');
    $url            = $_SERVER['REQUEST_URI'] ?? '';

    require_once "koneksi.php";
    
    if ($con_nowprd) {
        $sql = "INSERT INTO nowprd.log_loading_ppc (ip_address, url, load_duration, source, accessed_at)
                VALUES (?, ?, ?, 'server', ?)";
        $params = [$ip_address, $url, $load_duration, $datetime];
        $stmt = sqlsrv_query($con_nowprd, $sql, $params);

        if (!$stmt) {
            die(print_r(sqlsrv_errors(), true));  // Menampilkan error jika query gagal
        }
    }
?>
<body>
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
                                        <form action="" method="post">
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
                                                    $now = date('H:i'); // Format waktu dalam format jam:menit (24 jam)
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
                                                <!-- <div class="col-sm-12 col-xl-2 m-b-30">
                                                    <h4 style="font-size: 12px;" class="sub-title">DEPARTEMENT</h4>
                                                    <?php
                                                    require_once "koneksi.php";
                                                    $q_operation    = db2_exec($conn1, "SELECT DISTINCT OPERATIONGROUPCODE FROM OPERATION WHERE NOT OPERATIONGROUPCODE IS NULL ORDER BY OPERATIONGROUPCODE ASC");
                                                    ?>
                                                    <select class="form-control input-xl" name="operation">
                                                        <option selected value="">-</option>
                                                        <?php while ($d_operation = db2_fetch_assoc($q_operation)) { ?>
                                                            <option value="<?= $d_operation['OPERATIONGROUPCODE']; ?>" 
                                                            <?php
                                                            if (isset($_POST['submit'])) {
                                                                if ($d_operation['OPERATIONGROUPCODE'] == $_POST['operation']) {
                                                                    echo "SELECTED";
                                                                }
                                                            }
                                                            ?>><?= $d_operation['OPERATIONGROUPCODE']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div> -->
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
                                                    <button type="submit" name="submit" class="btn btn-primary"><i class="icofont icofont-search-alt-1"></i> Cari data</button>
                                                    <a class="btn btn-warning" href="ppc_filter.php"><i class="icofont icofont-refresh"></i> Reset</a>
                                                    <button type="submit" name="submit_excel" class="btn btn-danger"><i class="icofont icofont-download"></i> Download data</button>
                                                    <!-- <a href="ppc_memopenting-excel.php?no_order=<?= $_POST['no_order']; ?>&tgl1=<?= $_POST['tgl1']; ?>&tgl2=<?= $_POST['tgl2']; ?>&operation=<?= $_POST['operation']; ?>&akses=catch" class="btn btn-danger"><i class="icofont icofont-download"></i> Download data (Excel)</a> -->

                                                    <?php if (isset($_POST['submit'])) : ?>
                                                        <a class="btn btn-mat btn-success" href="ppc_memopenting-excel.php?no_order=<?= $_POST['no_order']; ?>&tgl1=<?= $_POST['tgl1']; ?>&tgl2=<?= $_POST['tgl2']; ?>&operation=<?= $_POST['operation']; ?>">CETAK EXCEL</a>
                                                        <a class="btn btn-mat btn-warning" href="ppc_memopenting-libre.php?no_order=<?= $_POST['no_order']; ?>&tgl1=<?= $_POST['tgl1']; ?>&tgl2=<?= $_POST['tgl2']; ?>&operation=<?= $_POST['operation']; ?>">CETAK EXCEL (LIBRE)</a>
                                                        <a class="btn btn-mat btn-danger" href="ppc_memopenting-excel_qc.php?no_order=<?= $_POST['no_order']; ?>&tgl1=<?= $_POST['tgl1']; ?>&tgl2=<?= $_POST['tgl2']; ?>&operation=<?= $_POST['operation']; ?>">CETAK EXCEL (QC)</a>
                                                    <?php endif; ?>
                                                    <!-- <p>Warning : Jika melakukan<b><i class="icofont icofont-refresh"></i> Reset Data</b>, pastikan tidak ada yang menggunakan Memo Penting</p> -->
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

                                                        $conditions = [];
                                                        $conditions2 = [];

                                                        // Menambahkan kondisi berdasarkan filter yang diisi
                                                        if ($nama_warna) {
                                                            $conditions[] = "WARNA LIKE '%$nama_warna%'";
                                                        }
                                                        if ($prod_order) {
                                                            $conditions[] = "NO_KK = '$prod_order'";
                                                        }
                                                        if ($prod_demand) {
                                                            $conditions[] = "DEMAND = '$prod_demand'";
                                                        }
                                                        if ($no_order) {
                                                            $conditions[] = "NO_ORDER = '$no_order'";
                                                        }
                                                        if ($orderline) {
                                                            $conditions[] = "ORDERLINE = '$orderline'";
                                                        }
                                                        if ($tgl1 && $tgl2) {
                                                            $conditions[] = "DELIVERY BETWEEN '$tgl1' AND '$tgl2'";
                                                        }
                                                        if ($no_po) {
                                                            $conditions[] = "NO_PO = '$no_po'";
                                                        }
                                                        if ($article_group && $article_code) {
                                                            $conditions[] = "SUBCODE02 = '$article_group' AND SUBCODE03 = '$article_code'";
                                                        }
                                                        if ($kkoke === 'tidak') {
                                                            $conditions2[] = "NOT PROGRESSSTATUS = '6' AND NOT PROGRESSSTATUS_DEMAND = '6'";
                                                        }

                                                        // Menyusun string kondisi
                                                        $conditionsString = count($conditions) > 0 ? implode(" AND ", $conditions) : "1=1";

                                                        // Menyusun query
                                                        $query = "SELECT * FROM (SELECT * FROM ITXVIEW_MEMOPENTINGPPC WHERE $conditionsString)";

                                                        if (count($conditions2) > 0) {
                                                            $query .= " WHERE " . implode(" AND ", $conditions2);
                                                        }

                                                        // ITXVIEW_MEMOPENTINGPPC
                                                        $itxviewmemo              = db2_exec($conn1, $query, array('cursor' => DB2_SCROLLABLE));
                                                        $r_itxviewmemo = [];
                                                        while ($row_itxviewmemo   = db2_fetch_assoc($itxviewmemo)) {
                                                            $r_itxviewmemo[] = [
                                                                cek(addslashes($row_itxviewmemo['ORDERDATE'])),
                                                                cek($row_itxviewmemo['PELANGGAN']),
                                                                cek($row_itxviewmemo['NO_ORDER']),
                                                                cek($row_itxviewmemo['NO_PO']),
                                                                cek($row_itxviewmemo['SUBCODE02']),
                                                                cek($row_itxviewmemo['SUBCODE03']),
                                                                cek($row_itxviewmemo['KETERANGAN_PRODUCT']),
                                                                cek($row_itxviewmemo['WARNA']),
                                                                cek($row_itxviewmemo['NO_WARNA']),
                                                                cek(addslashes($row_itxviewmemo['DELIVERY'])),
                                                                cek($row_itxviewmemo['QTY_BAGIKAIN']),
                                                                cek($row_itxviewmemo['NETTO']),
                                                                cek($row_itxviewmemo['DELAY']),
                                                                cek($row_itxviewmemo['NO_KK']),
                                                                cek($row_itxviewmemo['DEMAND']),
                                                                substr(cek($row_itxviewmemo['LOT']) ?? '', 0, 7), // di database length nya 7
                                                                cek($row_itxviewmemo['ORDERLINE']),
                                                                cek($row_itxviewmemo['PROGRESSSTATUS']),
                                                                cek($row_itxviewmemo['PROGRESSSTATUS_DEMAND']),
                                                                cek($row_itxviewmemo['KETERANGAN']),
                                                                $_SERVER['REMOTE_ADDR'],
                                                                date('Y-m-d H:i:s'),
                                                                'MEMO'
                                                            ];
                                                        }

                                                        try {
                                                            // Define the query with placeholders
                                                            $query = "
                                                                    INSERT INTO nowprd.itxview_memopentingppc (
                                                                        ORDERDATE, PELANGGAN, NO_ORDER, NO_PO, ARTICLE_GROUP, ARTICLE_CODE,
                                                                        KETERANGAN_PRODUCT, WARNA, NO_WARNA, DELIVERY, QTY_BAGIKAIN, NETTO,
                                                                        DELAY, NO_KK, DEMAND, LOT, ORDERLINE, PROGRESSSTATUS, PROGRESSSTATUS_DEMAND,
                                                                        KETERANGAN, IPADDRESS, CREATEDATETIME, ACCESS_TO
                                                                    ) VALUES (
                                                                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                                                                    )
                                                                ";

                                                            // Prepare the statement
                                                            $stmt = $pdo->prepare($query);

                                                            // Define the data to be inserted
                                                            foreach ($r_itxviewmemo as $row) {
                                                                // Ensure data is properly encoded and does not contain problematic characters
                                                                $row = array_map(function ($value) {
                                                                    // Check if the value is not null before converting encoding
                                                                    return $value !== null ? mb_convert_encoding($value, 'UTF-8', 'auto') : $value;
                                                                }, $row);

                                                                // Execute the statement with the data
                                                                if (!$stmt->execute($row)) {
                                                                    // Handle error
                                                                    echo "Error: ";
                                                                    print_r($stmt->errorInfo());
                                                                    exit();
                                                                }
                                                            }
                                                            echo " ";
                                                        } catch (PDOException $e) {
                                                            echo "xError: " . $e->getMessage();
                                                        }


                                                        // --------------------------------------------------------------------------------------------------------------- //
                                                        $prod_order_2   = $_POST['prod_order'] ?? $_GET['prod_order'] ?? '';
                                                        $prod_demand_2  = $_POST['prod_demand'] ?? $_GET['prod_demand'] ?? '';
                                                        $no_order_2     = $_POST['no_order'] ?? $_GET['no_order'] ?? '';
                                                        $orderline_2    = $_GET['orderline'] ?? '';
                                                        $tgl1_2         = $_POST['tgl1'] ?? '';
                                                        $tgl2_2         = $_POST['tgl2'] ?? '';
                                                        $no_po2         = $_POST['no_po'] ?? '';
                                                        $article_group2 = $_POST['article_group'] ?? '';
                                                        $article_code2  = $_POST['article_code'] ?? '';

                                                        $conditions = [];

                                                        if ($prod_order_2) {
                                                            $conditions[] = "NO_KK  = '$prod_order'";
                                                        }
                                                        if ($prod_demand_2) {
                                                            $conditions[] = "DEMAND = '$prod_demand'";
                                                        }
                                                        if ($no_order_2) {
                                                            $conditions[] = "NO_ORDER = '$no_order_2'";
                                                        }
                                                        if ($orderline_2) {
                                                            $conditions[] = "ORDERLINE = '$orderline_2'";
                                                        }
                                                        if ($tgl1_2 & $tgl2_2) {
                                                            $conditions[] = "DELIVERY BETWEEN '$tgl1_2' AND '$tgl2_2'";
                                                        }
                                                        if ($no_po2) {
                                                            $conditions[] = "NO_PO = '$no_po'";
                                                        }
                                                        if ($article_group2 & $article_code2) {
                                                            $conditions[] = "ARTICLE_GROUP = '$article_group2' AND ARTICLE_CODE = '$article_code2'";
                                                        }

                                                        // Menyusun string kondisi
                                                        $conditionsString = count($conditions) > 0 ? implode(" AND ", $conditions) : "1=1";

                                                        // Menyusun query
                                                        $query = "SELECT DISTINCT * FROM nowprd.itxview_memopentingppc WHERE $conditionsString AND ACCESS_TO = 'MEMO' AND IPADDRESS = '$_SERVER[REMOTE_ADDR]' ORDER BY DELIVERY ASC";

                                                        $stmt   = sqlsrv_query($con_nowprd, $query);
                                                        while ($rowdb2 = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                        ?>
                                                            <?php
                                                            //Deteksi Production Demand Closed Atau Belum
                                                            if ($rowdb2['PROGRESSSTATUS_DEMAND'] == 6) {
                                                                $status = 'AAA';
                                                                $kode_dept          = '-';
                                                                $status_terakhir    = '-';
                                                                $status_operation   = 'KK Oke';
                                                            } else {
                                                                // 1. Deteksi Production Order Closed Atau Belum
                                                                if ($rowdb2['PROGRESSSTATUS'] == 6) {
                                                                    $status = 'AA';
                                                                    $kode_dept          = '-';
                                                                    $status_terakhir    = '-';
                                                                    $status_operation   = 'KK Oke';
                                                                } else {
                                                                    // mendeteksi statusnya close
                                                                    $q_deteksi_status_close = db2_exec($conn1, "SELECT 
                                                                                                                        p.PRODUCTIONORDERCODE AS PRODUCTIONORDERCODE, 
                                                                                                                        -- CASE
                                                                                                                        --     WHEN TRIM(p.STEPTYPE) = '0' THEN p.GROUPSTEPNUMBER
                                                                                                                        --     WHEN TRIM(p.STEPTYPE) = '3' THEN p2.STEPNUMBER 
                                                                                                                        --     ELSE p.GROUPSTEPNUMBER
                                                                                                                        -- END AS GROUPSTEPNUMBER,
                                                                                                                        TRIM(p.GROUPSTEPNUMBER) AS GROUPSTEPNUMBER,
                                                                                                                        TRIM(p.PROGRESSSTATUS) AS PROGRESSSTATUS
                                                                                                                    FROM 
                                                                                                                        VIEWPRODUCTIONDEMANDSTEP p
                                                                                                                    -- LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE AND p2.STEPTYPE = p.STEPTYPE AND p2.OPERATIONCODE = p.OPERATIONCODE 
                                                                                                                    WHERE
                                                                                                                        p.PRODUCTIONORDERCODE = '$rowdb2[NO_KK]' AND
                                                                                                                        (p.PROGRESSSTATUS = '3' OR p.PROGRESSSTATUS = '2') ORDER BY p.GROUPSTEPNUMBER DESC LIMIT 1");
                                                                    $row_status_close = db2_fetch_assoc($q_deteksi_status_close);

                                                                    // UNTUK DELAY PROGRESS STATUS PERMINTAAN MS. AMY
                                                                    if ($row_status_close['PROGRESSSTATUS'] == '2') { // KALAU PROGRESS STATUSNYA ENTERED
                                                                        $q_delay_progress_selesai   = db2_exec($conn1, "SELECT 
                                                                                                                                    p.PRODUCTIONORDERCODE AS PRODUCTIONORDERCODE, 
                                                                                                                                    CASE
                                                                                                                                        WHEN TRIM(p.STEPTYPE) = '0' THEN p.GROUPSTEPNUMBER
                                                                                                                                        WHEN TRIM(p.STEPTYPE) = '3' THEN p2.STEPNUMBER 
                                                                                                                                        WHEN TRIM(p.STEPTYPE) = '1' THEN p2.STEPNUMBER
                                                                                                                                        ELSE p.GROUPSTEPNUMBER
                                                                                                                                    END AS GROUPSTEPNUMBER,
                                                                                                                                    iptip.MULAI,
                                                                                                                                    DAYS(CURRENT DATE) - DAYS(iptip.MULAI) AS DELAY_PROGRESSSTATUS,
                                                                                                                                    p.PROGRESSSTATUS AS PROGRESSSTATUS
                                                                                                                                FROM 
                                                                                                                                    PRODUCTIONDEMANDSTEP p
                                                                                                                                LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE AND p2.STEPTYPE = p.STEPTYPE AND p2.OPERATIONCODE = p.OPERATIONCODE 
                                                                                                                                LEFT JOIN ITXVIEW_POSISIKK_TGL_IN_PRODORDER iptip ON iptip.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE AND iptip.DEMANDSTEPSTEPNUMBER = p.STEPNUMBER
                                                                                                                                WHERE
                                                                                                                                    p.PRODUCTIONORDERCODE = '$rowdb2[NO_KK]' AND p.PROGRESSSTATUS = '2' ORDER BY p.GROUPSTEPNUMBER DESC LIMIT 1");
                                                                        $d_delay_progress_selesai   = db2_fetch_assoc($q_delay_progress_selesai);
                                                                        $jam_status_terakhir        = $d_delay_progress_selesai['MULAI'];
                                                                        $delay_progress_status      = $d_delay_progress_selesai['DELAY_PROGRESSSTATUS'] . ' Hari';
                                                                    } elseif ($row_status_close['PROGRESSSTATUS'] == '3') { // KALAU PROGRESS STATUSNYA PROGRESS
                                                                        $q_delay_progress_mulai   = db2_exec($conn1, "SELECT 
                                                                                                                                    p.PRODUCTIONORDERCODE AS PRODUCTIONORDERCODE, 
                                                                                                                                    CASE
                                                                                                                                        WHEN TRIM(p.STEPTYPE) = '0' THEN p.GROUPSTEPNUMBER
                                                                                                                                        WHEN TRIM(p.STEPTYPE) = '3' THEN p2.STEPNUMBER 
                                                                                                                                        WHEN TRIM(p.STEPTYPE) = '1' THEN p2.STEPNUMBER
                                                                                                                                        ELSE p.GROUPSTEPNUMBER
                                                                                                                                    END AS GROUPSTEPNUMBER,
                                                                                                                                    COALESCE(iptop.SELESAI, SUBSTRING(p2.LASTUPDATEDATETIME, 1, 19)) AS SELESAI,
                                                                                                                                    DAYS(CURRENT DATE) - COALESCE(DAYS(iptop.SELESAI), DAYS(p2.LASTUPDATEDATETIME)) AS DELAY_PROGRESSSTATUS,
                                                                                                                                    p.PROGRESSSTATUS AS PROGRESSSTATUS
                                                                                                                                FROM 
                                                                                                                                    VIEWPRODUCTIONDEMANDSTEP p
                                                                                                                                LEFT JOIN PRODUCTIONDEMANDSTEP p2 ON p2.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE AND p2.STEPTYPE = p.STEPTYPE AND p2.OPERATIONCODE = p.OPERATIONCODE
                                                                                                                                LEFT JOIN ITXVIEW_POSISIKK_TGL_OUT_PRODORDER iptop ON iptop.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE 
                                                                                                                                                                                    AND iptop.DEMANDSTEPSTEPNUMBER = 
                                                                                                                                                                                        CASE
                                                                                                                                                                                            WHEN TRIM(p.STEPTYPE) = '0' THEN p.GROUPSTEPNUMBER
                                                                                                                                                                                            WHEN TRIM(p.STEPTYPE) = '3' THEN p2.STEPNUMBER 
                                                                                                                                                                                            WHEN TRIM(p.STEPTYPE) = '1' THEN p2.STEPNUMBER
                                                                                                                                                                                            ELSE p.GROUPSTEPNUMBER
                                                                                                                                                                                        END
                                                                                                                                WHERE
                                                                                                                                    p.PRODUCTIONORDERCODE = '$rowdb2[NO_KK]' AND p.PROGRESSSTATUS = '3' 
                                                                                                                                    AND (NOT iptop.SELESAI IS NULL OR NOT p2.LASTUPDATEDATETIME IS NULL)
                                                                                                                                ORDER BY 
                                                                                                                                    CASE
                                                                                                                                        WHEN TRIM(p.STEPTYPE) = '0' THEN p.GROUPSTEPNUMBER 
                                                                                                                                        WHEN TRIM(p.STEPTYPE) = '3' THEN p2.STEPNUMBER 
                                                                                                                                        WHEN TRIM(p.STEPTYPE) = '1' THEN p2.STEPNUMBER
                                                                                                                                        ELSE p.GROUPSTEPNUMBER
                                                                                                                                    END DESC 
                                                                                                                                LIMIT 1");
                                                                        $d_delay_progress_mulai   = db2_fetch_assoc($q_delay_progress_mulai);
                                                                        $jam_status_terakhir      = $d_delay_progress_mulai['SELESAI'];
                                                                        $delay_progress_status    = $d_delay_progress_mulai['DELAY_PROGRESSSTATUS'] . ' Hari';
                                                                    } else {
                                                                        $jam_status_terakhir      = '';
                                                                        $delay_progress_status    = '';
                                                                    }
                                                                    // UNTUK DELAY PROGRESS STATUS PERMINTAAN MS. AMY

                                                                    if (!empty($row_status_close['GROUPSTEPNUMBER'])) {
                                                                        $groupstepnumber    = $row_status_close['GROUPSTEPNUMBER'];
                                                                    } else {
                                                                        $groupstepnumber    = '0';
                                                                    }

                                                                    $q_cnp1             = db2_exec($conn1, "SELECT 
                                                                                                                    GROUPSTEPNUMBER,
                                                                                                                    TRIM(OPERATIONCODE) AS OPERATIONCODE,
                                                                                                                    o.LONGDESCRIPTION AS LONGDESCRIPTION,
                                                                                                                    PROGRESSSTATUS,
                                                                                                                    CASE
                                                                                                                        WHEN PROGRESSSTATUS = 0 THEN 'Entered'
                                                                                                                        WHEN PROGRESSSTATUS = 1 THEN 'Planned'
                                                                                                                        WHEN PROGRESSSTATUS = 2 THEN 'Progress'
                                                                                                                        WHEN PROGRESSSTATUS = 3 THEN 'Closed'
                                                                                                                    END AS STATUS_OPERATION
                                                                                                                FROM 
                                                                                                                    VIEWPRODUCTIONDEMANDSTEP v
                                                                                                                LEFT JOIN OPERATION o ON o.CODE = v.OPERATIONCODE
                                                                                                                WHERE 
                                                                                                                    PRODUCTIONORDERCODE = '$rowdb2[NO_KK]' AND PROGRESSSTATUS = 3 
                                                                                                                ORDER BY 
                                                                                                                    GROUPSTEPNUMBER DESC LIMIT 1");
                                                                    $d_cnp_close        = db2_fetch_assoc($q_cnp1);

                                                                    if ($d_cnp_close['PROGRESSSTATUS'] == 3) { // 3 is Closed From Demands Steps 
                                                                        $status = 'A';
                                                                        if ($d_cnp_close['OPERATIONCODE'] == 'PPC4') {
                                                                            if ($rowdb2['PROGRESSSTATUS'] == 6) {
                                                                                $status = 'B';
                                                                                $kode_dept          = '-';
                                                                                $status_terakhir    = '-';
                                                                                $status_operation   = 'KK Oke';
                                                                            } else {
                                                                                $status = 'C';
                                                                                $kode_dept          = '-';
                                                                                $status_terakhir    = '-';
                                                                                $status_operation   = 'KK Oke | Segera Closed Production Order!';
                                                                            }
                                                                        } else {
                                                                            $status = 'D';
                                                                            if ($row_status_close['PROGRESSSTATUS'] == 2) {
                                                                                $status = 'E';
                                                                                $groupstep_option       = "= '$groupstepnumber'";
                                                                            } else { //kalau status terakhirnya bukan PPC dan status terakhirnya CLOSED
                                                                                $status = 'F';
                                                                                $q_deteksi_total_step    = db2_exec($conn1, "SELECT COUNT(*) AS TOTALSTEP FROM VIEWPRODUCTIONDEMANDSTEP 
                                                                                                                                WHERE PRODUCTIONORDERCODE = '$rowdb2[NO_KK]'");
                                                                                $d_deteksi_total_step    = db2_fetch_assoc($q_deteksi_total_step);

                                                                                $q_deteksi_total_close  = db2_exec($conn1, "SELECT COUNT(*) AS TOTALCLOSE FROM VIEWPRODUCTIONDEMANDSTEP 
                                                                                                                                WHERE PRODUCTIONORDERCODE = '$rowdb2[NO_KK]'
                                                                                                                                AND PROGRESSSTATUS = 3");
                                                                                $d_deteksi_total_close  = db2_fetch_assoc($q_deteksi_total_close);

                                                                                if ($d_deteksi_total_step['TOTALSTEP'] ==  $d_deteksi_total_close['TOTALCLOSE']) {
                                                                                    $groupstep_option       = "= '$groupstepnumber'";
                                                                                } else {
                                                                                    $groupstep_option       = "> '$groupstepnumber'";
                                                                                }
                                                                            }
                                                                            // $status = 'G';
                                                                            $q_not_cnp1             = db2_exec($conn1, "SELECT 
                                                                                                                                GROUPSTEPNUMBER,
                                                                                                                                TRIM(OPERATIONCODE) AS OPERATIONCODE,
                                                                                                                                TRIM(o.OPERATIONGROUPCODE) AS OPERATIONGROUPCODE,
                                                                                                                                o.LONGDESCRIPTION AS LONGDESCRIPTION,
                                                                                                                                PROGRESSSTATUS,
                                                                                                                                CASE
                                                                                                                                    WHEN PROGRESSSTATUS = 0 THEN 'Entered'
                                                                                                                                    WHEN PROGRESSSTATUS = 1 THEN 'Planned'
                                                                                                                                    WHEN PROGRESSSTATUS = 2 THEN 'Progress'
                                                                                                                                    WHEN PROGRESSSTATUS = 3 THEN 'Closed'
                                                                                                                                END AS STATUS_OPERATION
                                                                                                                            FROM 
                                                                                                                                VIEWPRODUCTIONDEMANDSTEP v
                                                                                                                            LEFT JOIN OPERATION o ON o.CODE = v.OPERATIONCODE
                                                                                                                            WHERE 
                                                                                                                                PRODUCTIONORDERCODE = '$rowdb2[NO_KK]' AND 
                                                                                                                                GROUPSTEPNUMBER $groupstep_option
                                                                                                                            ORDER BY 
                                                                                                                                GROUPSTEPNUMBER ASC LIMIT 1");
                                                                            $d_not_cnp_close        = db2_fetch_assoc($q_not_cnp1);

                                                                            if ($d_not_cnp_close) {
                                                                                $kode_dept          = $d_not_cnp_close['OPERATIONGROUPCODE'];
                                                                                $status_terakhir    = $d_not_cnp_close['LONGDESCRIPTION'];
                                                                                $status_operation   = $d_not_cnp_close['STATUS_OPERATION'];
                                                                            } else {
                                                                                $status = 'H';
                                                                                $groupstep_option       = "= '$groupstepnumber'";
                                                                                $q_not_cnp1             = db2_exec($conn1, "SELECT 
                                                                                                                                GROUPSTEPNUMBER,
                                                                                                                                TRIM(OPERATIONCODE) AS OPERATIONCODE,
                                                                                                                                TRIM(o.OPERATIONGROUPCODE) AS OPERATIONGROUPCODE,
                                                                                                                                o.LONGDESCRIPTION AS LONGDESCRIPTION,
                                                                                                                                PROGRESSSTATUS,
                                                                                                                                CASE
                                                                                                                                    WHEN PROGRESSSTATUS = 0 THEN 'Entered'
                                                                                                                                    WHEN PROGRESSSTATUS = 1 THEN 'Planned'
                                                                                                                                    WHEN PROGRESSSTATUS = 2 THEN 'Progress'
                                                                                                                                    WHEN PROGRESSSTATUS = 3 THEN 'Closed'
                                                                                                                                END AS STATUS_OPERATION
                                                                                                                            FROM 
                                                                                                                                VIEWPRODUCTIONDEMANDSTEP v
                                                                                                                            LEFT JOIN OPERATION o ON o.CODE = v.OPERATIONCODE
                                                                                                                            WHERE 
                                                                                                                                PRODUCTIONORDERCODE = '$rowdb2[NO_KK]' AND 
                                                                                                                                GROUPSTEPNUMBER $groupstep_option
                                                                                                                            ORDER BY 
                                                                                                                                GROUPSTEPNUMBER ASC LIMIT 1");
                                                                                $d_not_cnp_close        = db2_fetch_assoc($q_not_cnp1);

                                                                                $kode_dept          = $d_not_cnp_close['OPERATIONGROUPCODE'];
                                                                                $status_terakhir    = $d_not_cnp_close['LONGDESCRIPTION'];
                                                                                $status_operation   = $d_not_cnp_close['STATUS_OPERATION'];
                                                                            }
                                                                        }
                                                                    } else {
                                                                        $status = 'H';
                                                                        if ($row_status_close['PROGRESSSTATUS'] == 2) {
                                                                            $status = 'I';
                                                                            $groupstep_option       = "= '$groupstepnumber'";
                                                                        } else {
                                                                            $status = 'J';
                                                                            $groupstep_option       = "> '$groupstepnumber'";
                                                                        }
                                                                        $status = 'K';
                                                                        $q_StatusTerakhir   = db2_exec($conn1, "SELECT 
                                                                                                                        p.PRODUCTIONORDERCODE, 
                                                                                                                        p.GROUPSTEPNUMBER, 
                                                                                                                        p.OPERATIONCODE, 
                                                                                                                        TRIM(o.OPERATIONGROUPCODE) AS OPERATIONGROUPCODE,
                                                                                                                        o.LONGDESCRIPTION AS LONGDESCRIPTION, 
                                                                                                                        CASE
                                                                                                                            WHEN p.PROGRESSSTATUS = 0 THEN 'Entered'
                                                                                                                            WHEN p.PROGRESSSTATUS = 1 THEN 'Planned'
                                                                                                                            WHEN p.PROGRESSSTATUS = 2 THEN 'Progress'
                                                                                                                            WHEN p.PROGRESSSTATUS = 3 THEN 'Closed'
                                                                                                                        END AS STATUS_OPERATION,
                                                                                                                        wc.LONGDESCRIPTION AS DEPT, 
                                                                                                                        p.WORKCENTERCODE
                                                                                                                    FROM 
                                                                                                                        VIEWPRODUCTIONDEMANDSTEP p
                                                                                                                    LEFT JOIN WORKCENTER wc ON wc.CODE = p.WORKCENTERCODE
                                                                                                                    LEFT JOIN OPERATION o ON o.CODE = p.OPERATIONCODE
                                                                                                                    WHERE 
                                                                                                                        p.PRODUCTIONORDERCODE = '$rowdb2[NO_KK]' AND
                                                                                                                        (p.PROGRESSSTATUS = '0' OR p.PROGRESSSTATUS = '1' OR p.PROGRESSSTATUS ='2') 
                                                                                                                        AND p.GROUPSTEPNUMBER $groupstep_option
                                                                                                                    ORDER BY p.GROUPSTEPNUMBER ASC LIMIT 1");
                                                                        $d_StatusTerakhir   = db2_fetch_assoc($q_StatusTerakhir);
                                                                        $kode_dept          = $d_StatusTerakhir['OPERATIONGROUPCODE'];
                                                                        $status_terakhir    = $d_StatusTerakhir['LONGDESCRIPTION'];
                                                                        $status_operation   = $d_StatusTerakhir['STATUS_OPERATION'];
                                                                    }
                                                                }
                                                            }

                                                            // mendeteksi jika status terakrhinya BKR1 MAT1 BKN1 closed, tapi step selanjutnya tidak muncul. Tiket no : BDIT250000492, BDIT250001161
                                                            $q_deteksi_status_terakhir_BKR1   = db2_exec($conn1, "SELECT
                                                                                                                            DISTINCT STEPNUMBER
                                                                                                                        FROM
                                                                                                                            ITXVIEW_POSISI_KARTU_KERJA
                                                                                                                        WHERE
                                                                                                                            PRODUCTIONORDERCODE = '$rowdb2[NO_KK]'
                                                                                                                            AND PRODUCTIONDEMANDCODE = '$rowdb2[DEMAND]'
                                                                                                                            AND NOT STATUS_OPERATION IN ('Entered', 'Progress')
                                                                                                                            AND (OPERATIONCODE = 'BKR1' OR OPERATIONCODE = 'MAT1' OR OPERATIONCODE = 'BKN1' OR OPERATIONCODE = 'BAT1' OR OPERATIONCODE = 'BAT2' OR
                                                                                                                                OPERATIONCODE = 'WAIT35' OR OPERATIONCODE = 'WAIT40' OR OPERATIONCODE = 'WAIT37' OR OPERATIONCODE = 'PRE1' OR OPERATIONCODE = 'SUE1' 
                                                                                                                                OR OPERATIONCODE = 'WAIT33' OR OPERATIONCODE = 'WAIT36')
                                                                                                                        ORDER BY
                                                                                                                            STEPNUMBER DESC 
                                                                                                                        FETCH FIRST 1 ROWS ONLY");
                                                            $row_status_terakhir_closed_BKR1 = db2_fetch_assoc($q_deteksi_status_terakhir_BKR1);

                                                            $q_deteksi_status_terakhir   = db2_exec($conn1, "SELECT
                                                                                                                        DISTINCT *
                                                                                                                    FROM
                                                                                                                        ITXVIEW_POSISI_KARTU_KERJA
                                                                                                                    WHERE
                                                                                                                        PRODUCTIONORDERCODE = '$rowdb2[NO_KK]'
                                                                                                                        AND PRODUCTIONDEMANDCODE = '$rowdb2[DEMAND]'
                                                                                                                        AND STEPNUMBER > '$row_status_terakhir_closed_BKR1[STEPNUMBER]'
                                                                                                                    ORDER BY
                                                                                                                        STEPNUMBER DESC 
                                                                                                                    FETCH FIRST 1 ROWS ONLY;");
                                                            $row_status_terakhir_closed = db2_fetch_assoc($q_deteksi_status_terakhir);
                                                            ?>
                                                            <?php if (empty($row_status_terakhir_closed_BKR1) or $row_status_terakhir_closed) : ?>
                                                                <tr>
                                                                    <td><?= $rowdb2['ORDERDATE']->format('Y-m-d H:i:s'); ?></td> <!-- TGL TERIMA ORDER -->
                                                                    <td><?= $rowdb2['PELANGGAN']; ?></td> <!-- PELANGGAN -->
                                                                    <td><?= $rowdb2['NO_ORDER']; ?></td> <!-- NO. ORDER -->
                                                                    <td><?= $rowdb2['NO_PO']; ?></td> <!-- NO. PO -->
                                                                    <td><?= $rowdb2['KETERANGAN_PRODUCT']; ?></td> <!-- KETERANGAN PRODUCT -->
                                                                    <td>
                                                                        <?php
                                                                        $q_lebar = db2_exec($conn1, "SELECT * FROM ITXVIEWLEBAR WHERE SALESORDERCODE = '$rowdb2[NO_ORDER]' AND ORDERLINE = '$rowdb2[ORDERLINE]'");
                                                                        $d_lebar = db2_fetch_assoc($q_lebar);
                                                                        ?>
                                                                        <?= number_format($d_lebar['LEBAR'] ?? 0, 0); ?>
                                                                    </td><!-- LEBAR -->
                                                                    <td>
                                                                        <?php
                                                                        $q_gramasi = db2_exec($conn1, "SELECT * FROM ITXVIEWGRAMASI WHERE SALESORDERCODE = '$rowdb2[NO_ORDER]' AND ORDERLINE = '$rowdb2[ORDERLINE]'");
                                                                        $d_gramasi = db2_fetch_assoc($q_gramasi);
                                                                        ?>
                                                                        <?php
                                                                        if ($d_gramasi['GRAMASI_KFF']) {
                                                                            echo number_format($d_gramasi['GRAMASI_KFF'], 0);
                                                                        } elseif ($d_gramasi['GRAMASI_FKF']) {
                                                                            echo number_format($d_gramasi['GRAMASI_FKF'], 0);
                                                                        } else {
                                                                            echo '-';
                                                                        }
                                                                        ?>
                                                                    </td> <!-- GRAMASI -->
                                                                    <td><?= $rowdb2['WARNA']; ?></td> <!-- WARNA -->
                                                                    <td><?= $rowdb2['NO_WARNA']; ?></td> <!-- NO WARNA -->
                                                                    <td><?php echo cek($rowdb2['DELIVERY']); ?></td> <!-- DELIVERY -->
                                                                    <td>
                                                                        <?php
                                                                        $q_actual_delivery      = db2_exec($conn1, "SELECT
                                                                                                                        COALESCE(s2.CONFIRMEDDELIVERYDATE, s.CONFIRMEDDUEDATE) AS ACTUAL_DELIVERY
                                                                                                                    FROM
                                                                                                                        SALESORDER s 
                                                                                                                    LEFT JOIN SALESORDERDELIVERY s2 ON s2.SALESORDERLINESALESORDERCODE = s.CODE AND s2.SALORDLINESALORDERCOMPANYCODE = s.COMPANYCODE AND s2.SALORDLINESALORDERCOUNTERCODE = s.COUNTERCODE 
                                                                                                                    WHERE
                                                                                                                        s2.SALESORDERLINESALESORDERCODE = '$rowdb2[NO_ORDER]'
                                                                                                                        AND s2.SALESORDERLINEORDERLINE = '$rowdb2[ORDERLINE]'");
                                                                        $row_actual_delivery    = db2_fetch_assoc($q_actual_delivery);
                                                                        echo $row_actual_delivery['ACTUAL_DELIVERY'];
                                                                        ?>
                                                                    </td> <!-- ACTUAL DELIVERY -->
                                                                    <?php
                                                                    $q_qtysalinan = db2_exec($conn1, "SELECT * FROM PRODUCTIONDEMAND WHERE CODE = '$rowdb2[DEMAND]'");
                                                                    $d_qtysalinan = db2_fetch_assoc($q_qtysalinan);
                                                                    ?>
                                                                    <?php
                                                                    $sql_benang_booking_new        = db2_exec($conn1, "SELECT * FROM ITXVIEW_BOOKING_NEW WHERE SALESORDERCODE = '$rowdb2[NO_ORDER]'
                                                                                                                                                    AND ORDERLINE = '$rowdb2[ORDERLINE]'");
                                                                    $r_benang_booking_new        = db2_fetch_assoc($sql_benang_booking_new);
                                                                    $d_benang_booking_new        = $r_benang_booking_new['SALESORDERCODE'];
                                                                    ?>
                                                                    <?php
                                                                    $sql_benang_rajut        = db2_exec($conn1, "SELECT
                                                                                                                            *
                                                                                                                        FROM
                                                                                                                            ITXVIEW_RAJUT
                                                                                                                        WHERE
                                                                                                                            (ITEMTYPEAFICODE ='KGF' OR ITEMTYPEAFICODE ='FKG')
                                                                                                                            AND TRIM(SUBCODE01) = '$d_qtysalinan[SUBCODE01]'
                                                                                                                            AND TRIM(SUBCODE02) = '$d_qtysalinan[SUBCODE02]'
                                                                                                                            AND TRIM(SUBCODE03) = '$d_qtysalinan[SUBCODE03]'
                                                                                                                            AND TRIM(SUBCODE04) = '$d_qtysalinan[SUBCODE04]'
                                                                                                                            AND TRIM(ORIGDLVSALORDLINESALORDERCODE) = '$rowdb2[NO_ORDER]'");
                                                                    $r_benang_rajut        = db2_fetch_assoc($sql_benang_rajut);
                                                                    $d_benang_rajut        = $r_benang_rajut['CODE'];
                                                                    ?>
                                                                    <?php
                                                                    $q_tgl_greige       = db2_exec($conn1, "SELECT
                                                                                                                        a2.VALUEDATE AS AWAL,
                                                                                                                        a.VALUEDATE AS AKHIR	
                                                                                                                    FROM
                                                                                                                        PRODUCTIONDEMAND p
                                                                                                                    LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.FIELDNAME = 'RMPGreigeReqDateTo'
                                                                                                                    LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'RMPReqDate'
                                                                                                                    WHERE 
                                                                                                                        CODE = '$d_benang_rajut'");
                                                                    $data_tgl_greige    = db2_fetch_assoc($q_tgl_greige);
                                                                    ?>
                                                                    <?php
                                                                    $tgl_awal_greige_rmp        = db2_exec($conn1, "SELECT
                                                                                                                            a.VALUESTRING,
                                                                                                                            a2.VALUEDATE AS AWAL
                                                                                                                        FROM
                                                                                                                            PRODUCTIONDEMAND p
                                                                                                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.FIELDNAME = 'ProAllow'
                                                                                                                        LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'ProAllowDate'
                                                                                                                        WHERE
                                                                                                                            CODE = '$rowdb2[DEMAND]'");
                                                                    $data_tgl_awal_greige_rmp   = db2_fetch_assoc($tgl_awal_greige_rmp);
                                                                    ?>
                                                                    <?php
                                                                    if ($d_benang_rajut) {
                                                                        if (!empty($data_tgl_greige['AWAL'])) {
                                                                            $awal   = $data_tgl_greige['AWAL'];
                                                                        } else {
                                                                            $awal   = $data_tgl_awal_greige_rmp['AWAL'];
                                                                        }

                                                                        $akhir  = $data_tgl_greige['AKHIR'];
                                                                    } else {
                                                                        $awal   = $data_tgl_awal_greige_rmp['AWAL'];
                                                                        $akhir  = $r_benang_rajut['TGLPOGREIGE'];
                                                                    }
                                                                    ?>
                                                                    <td><?= $awal; ?></td><!-- GREIGE AWAL -->
                                                                    <td><?= $akhir; ?></td><!-- GREIGE AKHIR -->
                                                                    <td>
                                                                        <?php
                                                                        $q_tglbagikain = db2_exec($conn1, "SELECT * FROM ITXVIEW_TGLBAGIKAIN WHERE PRODUCTIONORDERCODE = '$rowdb2[NO_KK]'");
                                                                        $d_tglbagikain = db2_fetch_assoc($q_tglbagikain);
                                                                        ?>
                                                                        <?= $d_tglbagikain['TRANSACTIONDATE']; ?>
                                                                    </td> <!-- BAGI KAIN TGL -->
                                                                    <td>
                                                                        <?php
                                                                        // KK GABUNG
                                                                        // $q_roll_gabung      = db2_exec($conn1, "SELECT 
                                                                        //                                     COUNT(*) AS ROLL
                                                                        //                                 FROM 
                                                                        //                                     PRODUCTIONDEMAND p 
                                                                        //                                 LEFT JOIN STOCKTRANSACTION s ON s.ORDERCODE = p.CODE
                                                                        //                                 WHERE 
                                                                        //                                     p.RESERVATIONORDERCODE = '$rowdb2[DEMAND]'");
                                                                        // $d_roll_gabung      = db2_fetch_assoc($q_roll_gabung);

                                                                        // KK TIDAK GABUNG
                                                                        $q_roll_tdk_gabung  = db2_exec($conn1, "SELECT count(*) AS ROLL, s2.PRODUCTIONORDERCODE
                                                                                                                    FROM STOCKTRANSACTION s2 
                                                                                                                    WHERE s2.ITEMTYPECODE ='KGF' AND s2.PRODUCTIONORDERCODE = '$rowdb2[NO_KK]'
                                                                                                                    GROUP BY s2.PRODUCTIONORDERCODE");
                                                                        $d_roll_tdk_gabung  = db2_fetch_assoc($q_roll_tdk_gabung);

                                                                        // if(!empty($d_roll_gabung['ROLL'])){
                                                                        // $roll   = $d_roll_gabung['ROLL'];
                                                                        // }else{
                                                                        $roll   = $d_roll_tdk_gabung['ROLL'];
                                                                        // }
                                                                        ?>
                                                                        <?= $roll; ?>
                                                                    </td> <!-- ROLL -->
                                                                    <td>
                                                                        <?php
                                                                        $q_orig_pd_code     = db2_exec($conn1, "SELECT 
                                                                                                                    *, a.VALUESTRING AS ORIGINALPDCODE
                                                                                                                FROM 
                                                                                                                    PRODUCTIONDEMAND p 
                                                                                                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.FIELDNAME = 'OriginalPDCode'
                                                                                                                WHERE p.CODE = '$rowdb2[DEMAND]'");
                                                                        $d_orig_pd_code     = db2_fetch_assoc($q_orig_pd_code);

                                                                        $q_cek_salinan     = db2_exec($conn1, "SELECT 
                                                                                                                    a2.VALUESTRING AS SALINAN_058
                                                                                                                FROM 
                                                                                                                    PRODUCTIONDEMAND p 
                                                                                                                LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'DefectTypeCode'
                                                                                                                LEFT JOIN USERGENERICGROUP u ON u.CODE = a2.VALUESTRING AND u.USERGENERICGROUPTYPECODE = '006'
                                                                                                                WHERE p.CODE = '$rowdb2[DEMAND]'");
                                                                        $d_cek_salinan     = db2_fetch_assoc($q_cek_salinan);
                                                                        ?>
                                                                        <?php if ($d_orig_pd_code['ORIGINALPDCODE']) : ?>
                                                                            <?php if ($d_cek_salinan['SALINAN_058'] == '058') : ?>
                                                                                <?= number_format($rowdb2['QTY_BAGIKAIN'], 2); ?>
                                                                            <?php else : ?>
                                                                                0
                                                                            <?php endif; ?>
                                                                        <?php else : ?>
                                                                            <?= number_format($rowdb2['QTY_BAGIKAIN'], 2); ?>
                                                                        <?php endif; ?>
                                                                    </td> <!-- BRUTO/BAGI KAIN -->
                                                                    <td>
                                                                        <?php if ($d_orig_pd_code['ORIGINALPDCODE']) : ?>
                                                                            <?php if ($d_cek_salinan['SALINAN_058'] == '058') : ?>
                                                                                0
                                                                            <?php else : ?>
                                                                                <?= number_format($d_qtysalinan['USERPRIMARYQUANTITY'], 3) ?>
                                                                            <?php endif; ?>
                                                                        <?php else : ?>
                                                                            0
                                                                        <?php endif; ?>
                                                                    </td> <!-- QTY SALINAN -->
                                                                    <td title="dari laporan packing qc-final-new">
                                                                        <?php
                                                                        // $q_qtypacking = db2_exec($conn1, "SELECT * FROM ITXVIEW_QTYPACKING WHERE DEMANDCODE = '$rowdb2[DEMAND]'");
                                                                        // $d_qtypacking = db2_fetch_assoc($q_qtypacking);
                                                                        // echo $d_qtypacking['QTY_PACKING'];

                                                                        $q_qtypacking = mysqli_query($con_db_qc, "SELECT
                                                                                                                nodemand,
                                                                                                                sum( jml_mutasi ) AS roll,
                                                                                                                sum( mutasi ) AS mutasi 
                                                                                                            FROM
                                                                                                                tbl_lap_inspeksi 
                                                                                                            WHERE
                                                                                                                trim( nodemand ) = '$rowdb2[DEMAND]' 
                                                                                                                AND dept = 'PACKING' 
                                                                                                            GROUP BY
                                                                                                                trim(nodemand)");
                                                                        $d_qtypacking = mysqli_fetch_assoc($q_qtypacking);
                                                                        echo $d_qtypacking['mutasi'];
                                                                        ?>
                                                                    </td> <!-- QTY PACKING -->
                                                                    <td><?= number_format($rowdb2['NETTO'] ?? 0, 0); ?></td> <!-- NETTO KG-->
                                                                    <td>
                                                                        <?php
                                                                        $sql_netto_yd = db2_exec($conn1, "SELECT * FROM ITXVIEW_NETTO WHERE CODE = '$rowdb2[DEMAND]'");
                                                                        $d_netto_yd = db2_fetch_assoc($sql_netto_yd);
                                                                        if (TRIM($d_netto_yd['PRICEUNITOFMEASURECODE']) == 'm') {
                                                                            echo number_format($d_netto_yd['USERSECONDARYQUANTITY'] ?? 0, 0);
                                                                        } else {
                                                                            echo number_format($d_netto_yd['BASESECONDARYQUANTITY'] ?? 0, 0);
                                                                        }
                                                                        ?>
                                                                    </td> <!-- NETTO YD-->
                                                                    <?php
                                                                    if ($rowdb2['NO_ORDER'] && $rowdb2['ORDERLINE']) {
                                                                        //ini_set("error_reporting", 0);
                                                                        $sqlQtyKurang   = db2_exec($conn1, "SELECT
                                                                                                        isqd.ORDERLINE,
                                                                                                        isqd.PELANGGAN,
                                                                                                        TRIM(isqd.NO_ORDER) AS NO_ORDER,
                                                                                                        isqd.NO_PO,
                                                                                                        isqd.KET_PRODUCT,
                                                                                                        isqd.STYLE,
                                                                                                        isqd.LEBAR,
                                                                                                        isqd.GRAMASI,
                                                                                                        isqd.WARNA,
                                                                                                        isqd.NO_WARNA,
                                                                                                        isqd.PRICEUNITOFMEASURECODE,
                                                                                                        isqd.NETTO,
                                                                                                        isqd.NETTO_2,
                                                                                                        isqd.NETTO_M,
                                                                                                        isqd.KONVERSI,
                                                                                                        isqd.ACTUAL_DELIVERY,
                                                                                                        SUM(isqd.QTY_SUDAH_KIRIM) AS QTY_SUDAH_KIRIM,
                                                                                                        SUM(isqd.QTY_SUDAH_KIRIM_2) AS QTY_SUDAH_KIRIM_2,
                                                                                                        CASE
                                                                                                            WHEN DAYS(now()) - DAYS(Timestamp_Format(isqd.ACTUAL_DELIVERY, 'YYYY-MM-DD')) < 0 THEN 0
                                                                                                            ELSE DAYS(now()) - DAYS(Timestamp_Format(isqd.ACTUAL_DELIVERY, 'YYYY-MM-DD'))
                                                                                                        END	AS DELAY,
                                                                                                        isqd.SUBCODE01,
                                                                                                        isqd.SUBCODE02,
                                                                                                        isqd.SUBCODE03,
                                                                                                        isqd.SUBCODE04,
                                                                                                        isqd.SUBCODE05,
                                                                                                        isqd.SUBCODE06,
                                                                                                        isqd.SUBCODE07,
                                                                                                        isqd.SUBCODE08,
                                                                                                        s.STATISTICALGROUPCODE,
                                                                                                        ip.BUYER 
                                                                                                    FROM
                                                                                                        ITXVIEW_SUMMARY_QTY_DELIVERY isqd
                                                                                                    LEFT JOIN SALESORDER s ON s.CODE = isqd.NO_ORDER 
                                                                                                    LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE AND ip.CODE = s.CODE 
                                                                                                    WHERE
                                                                                                        isqd.NO_ORDER = '$rowdb2[NO_ORDER]'
                                                                                                        AND isqd.ORDERLINE = '$rowdb2[ORDERLINE]'
                                                                                                    GROUP BY
                                                                                                        isqd.ORDERLINE,
                                                                                                        isqd.PELANGGAN,
                                                                                                        isqd.NO_ORDER,
                                                                                                        isqd.NO_PO,
                                                                                                        isqd.KET_PRODUCT,
                                                                                                        isqd.STYLE,
                                                                                                        isqd.LEBAR,
                                                                                                        isqd.GRAMASI,
                                                                                                        isqd.WARNA,
                                                                                                        isqd.NO_WARNA,
                                                                                                        isqd.PRICEUNITOFMEASURECODE,
                                                                                                        isqd.NETTO,
                                                                                                        isqd.NETTO_2,
                                                                                                        isqd.NETTO_M,
                                                                                                        isqd.KONVERSI,
                                                                                                        isqd.ACTUAL_DELIVERY,
                                                                                                        isqd.SUBCODE01,
                                                                                                        isqd.SUBCODE02,
                                                                                                        isqd.SUBCODE03,
                                                                                                        isqd.SUBCODE04,
                                                                                                        isqd.SUBCODE05,
                                                                                                        isqd.SUBCODE06,
                                                                                                        isqd.SUBCODE07,
                                                                                                        isqd.SUBCODE08,
                                                                                                        s.STATISTICALGROUPCODE,
                                                                                                        ip.BUYER  
                                                                                                    ORDER BY
                                                                                                        isqd.ORDERLINE ASC");
                                                                        $fetchDataQtyKurang     = db2_fetch_assoc($sqlQtyKurang);

                                                                        $ResultLotCode  = "SELECT 
                                                                                                    LISTAGG('''' || TRIM(PRODUCTIONORDERCODE) || '''', ', ' ) AS PRODUCTIONORDERCODE,
                                                                                                    LISTAGG('''' || TRIM(PRODUCTIONDEMANDCODE) || '''', ', ' ) AS PRODUCTIONDEMANDCODE
                                                                                                FROM 
                                                                                                    ITXVIEWKK
                                                                                                WHERE 
                                                                                                    PROJECTCODE = '$rowdb2[NO_ORDER]' 
                                                                                                AND ITEMTYPEAFICODE = 'KFF'
                                                                                                    AND ORIGDLVSALORDERLINEORDERLINE = '$rowdb2[ORDERLINE]'";
                                                                        $exec_lotcode   = db2_exec($conn1, $ResultLotCode);
                                                                        $fetch_lotcode  = db2_fetch_assoc($exec_lotcode);

                                                                        if ($fetch_lotcode['PRODUCTIONORDERCODE']) {
                                                                            $sqlQtyReady          = "SELECT
                                                                                                            SUM(BASEPRIMARYQUANTITYUNIT) AS QTY_READY,
                                                                                                            SUM(BASESECONDARYQUANTITYUNIT) AS QTY_READY_2
                                                                                                        FROM
                                                                                                            BALANCE b
                                                                                                        WHERE
                                                                                                            LOTCODE IN ($fetch_lotcode[PRODUCTIONORDERCODE])
                                                                                                            AND LEFT(ELEMENTSCODE, 8) IN ($fetch_lotcode[PRODUCTIONDEMANDCODE])
                                                                                                            AND LOGICALWAREHOUSECODE = 'M031'
                                                                                                            AND PROJECTCODE = '$rowdb2[NO_ORDER]'";

                                                                            $fetchQtyReady    = db2_exec($conn1, $sqlQtyReady);
                                                                            $dataQtyReady    = db2_fetch_assoc($fetchQtyReady);
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <td align="right">
                                                                    <?= 
                                                                        ($fetchDataQtyKurang['KONVERSI'] != 0) 
                                                                        ? number_format(
                                                                            (
                                                                                (trim($fetchDataQtyKurang['PRICEUNITOFMEASURECODE']) == 'm' 
                                                                                    ? $fetchDataQtyKurang['NETTO_M'] 
                                                                                    : $fetchDataQtyKurang['NETTO_2']
                                                                                ) 
                                                                                - $fetchDataQtyKurang['QTY_SUDAH_KIRIM_2'] 
                                                                                - $dataQtyReady['QTY_READY_2']
                                                                            ) / $fetchDataQtyKurang['KONVERSI'], 
                                                                            2
                                                                        ) 
                                                                        : '0.00';
                                                                    ?>


                                                                    </td><!-- QTY KURANG (KG) -->
                                                                    <td align="right">
                                                                        <?= number_format(
                                                                            (trim($fetchDataQtyKurang['PRICEUNITOFMEASURECODE']) == 'm' ? $fetchDataQtyKurang['NETTO_M'] : $fetchDataQtyKurang['NETTO_2']) - $fetchDataQtyKurang['QTY_SUDAH_KIRIM_2']-$dataQtyReady['QTY_READY_2'], 2); ?>

                                                                    </td> <!-- QTY KURANG (YD/MTR) -->

                                                                    <td><?= $rowdb2['DELAY']; ?></td> <!-- DELAY -->
                                                                    <td></td> <!-- TARGET SELESAI -->
                                                                    <td><?= $kode_dept; ?></td> <!-- KODE DEPT -->
                                                                    <td>
                                                                        <?= $status_terakhir; ?>
                                                                        <?php if ($status_operation != 'KK Oke') : ?>
                                                                            (<?= $jam_status_terakhir; ?>)
                                                                        <?php endif; ?>
                                                                    </td> <!-- STATUS TERAKHIR -->
                                                                    <td>
                                                                        <?php
                                                                        // if($status_operation == 'Progress'){ // KALAU PROGRESS STATUSNYA PROGRESS
                                                                        if ($kode_dept == 'DYE') {
                                                                            $q_schedule_dye     = mysqli_query($con_db_dyeing, "SELECT * FROM `tbl_schedule` WHERE nokk = '$rowdb2[NO_KK]' AND NOT `status` = 'selesai'");
                                                                            $data_schedule_dye  = mysqli_fetch_assoc($q_schedule_dye);
                                                                            $nomesin            = $data_schedule_dye['no_mesin'];
                                                                            $nourut             = $data_schedule_dye['no_urut'];
                                                                        } elseif ($kode_dept == 'FIN') {
                                                                            $schedule_fin       = mysqli_query($con_db_finishing, "SELECT * FROM `tbl_schedule_new` WHERE nokk = '$rowdb2[NO_KK]' AND nodemand = '$rowdb2[DEMAND]' ORDER BY id DESC LIMIT 1");
                                                                            $data_schedule_fin  = mysqli_fetch_assoc($schedule_fin);
                                                                            $nomesin            = $data_schedule_fin['no_mesin'] . '-' . substr(TRIM($data_schedule_fin['no_mesin'] ?? ''), -5, 2) . substr(TRIM($data_schedule_fin['no_mesin'] ?? ''), -2);
                                                                            $nourut             = $data_schedule_fin['nourut'];
                                                                        } else {
                                                                            $nomesin            = '';
                                                                            $nourut             = '';
                                                                        }
                                                                        // }
                                                                        ?>
                                                                        <?= $nomesin; ?>
                                                                    </td> <!-- NOMOR MESIN SCHEDULE -->
                                                                    <td><?= $nourut; ?></td> <!-- NOMOR URUT SCHEDULE -->
                                                                    <td>
                                                                        <?php if ($status_operation != 'KK Oke') : ?>
                                                                            <?= $delay_progress_status; ?>
                                                                        <?php endif; ?>
                                                                    </td> <!-- DELAY PROGRESS STATUS -->
                                                                    <td><?= $status_operation; ?></td> <!-- PROGRESS STATUS -->
                                                                    <td>
                                                                        <?php
                                                                        if (!empty($d_tglbagikain['TRANSACTIONDATE'])) {
                                                                            $tgl_bagikain   = date_create(substr($d_tglbagikain['TRANSACTIONDATE'], 0, 10));
                                                                            $tglsekarang    = date_create(date('Y-m-d H:i:s'));
                                                                            $diff_totalharibagikain = date_diff($tgl_bagikain, $tglsekarang);

                                                                            // echo $diff_totalharibagikain->m. ' Bulan, '.$diff_totalharibagikain->d. ' Hari';
                                                                            echo $diff_totalharibagikain->days . ' Hari';
                                                                        } else {
                                                                            $tgl_buka_kartu   = date_create(substr($rowdb2['ORDERDATE']->format('Y-m-d H:i:s'), 0, 10));
                                                                            $tglsekarang    = date_create(date('Y-m-d H:i:s'));
                                                                            $diff_totalharibagikain = date_diff($tgl_buka_kartu, $tglsekarang);

                                                                            // echo $diff_totalharibagikain->m. ' Bulan, '.$diff_totalharibagikain->d. ' Hari';
                                                                            echo $diff_totalharibagikain->days . ' Hari';
                                                                        }
                                                                        ?>
                                                                    </td> <!-- TOTAL HARI BAGI KAIN -->
                                                                    <td><?= $rowdb2['LOT']; ?></td> <!-- LOT -->
                                                                    <td><a target="_BLANK" href="http://online.indotaichen.com/laporan/ppc_filter_steps.php?demand=<?= $rowdb2['DEMAND']; ?>&prod_order=<?= $rowdb2['NO_KK']; ?>"><?= $rowdb2['DEMAND']; ?></a></td> <!-- DEMAND -->
                                                                    <td><?= $rowdb2['NO_KK']; ?></td> <!-- NO KARTU KERJA -->
                                                                    <td><?= $d_orig_pd_code['ORIGINALPDCODE']; ?></td> <!-- ORIGINAL PD CODE -->
                                                                    <td>
                                                                        <!-- <a href="http://online.indotaichen.com/laporan/ppc_catatan_po_greige.php?" target="_blank">Detail</a> -->
                                                                        <?php if ($d_benang_booking_new) {
                                                                            echo $d_benang_booking_new . '. Greige Ready';
                                                                        } ?>
                                                                        <?php if ($d_benang_rajut) {
                                                                            echo $d_benang_rajut . '. Rajut';
                                                                        } ?>
                                                                    </td> <!-- CATATAN PO GREIGE -->
                                                                    <td><?= $rowdb2['KETERANGAN']; ?></td> <!-- KETERANGAN -->
                                                                    <td>
                                                                        <?php
                                                                        $pr_order   = $rowdb2['NO_KK'];
                                                                        $dmand      = $rowdb2['DEMAND'];
                                                                        $additional = db2_exec($conn1, "SELECT COUNT(*) AS TOTAL_ADDITIONAL
                                                                                                                FROM PRODUCTIONDEMANDSTEP p 
                                                                                                                WHERE p.PRODUCTIONORDERCODE = $pr_order 
                                                                                                                    AND p.PRODUCTIONDEMANDCODE =  $dmand
                                                                                                                    AND (p.STEPTYPE = 1)");
                                                                        $additional_data = db2_fetch_assoc($additional);
                                                                        ?>
                                                                        <?= $additional_data['TOTAL_ADDITIONAL']; ?>
                                                                    </td>
                                                                    <?php if ($_SERVER['REMOTE_ADDR'] == '10.0.5.132') : ?>
                                                                        <td>
                                                                            <?= $groupstep_option; ?>
                                                                            <?= $status; ?>
                                                                        </td>
                                                                    <?php endif; ?>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif (isset($_POST['<i class="icofont icofont-refresh"></i> Reset'])) : ?>
                                    <?php
                                    ini_set("error_reporting", 1);
                                    session_start();
                                    require_once "koneksi.php";
                                    sqlsrv_query($con_nowprd, "DELETE FROM nowprd.itxview_memopentingppc");
                                    header("Location: ppc_filter.php");
                                    ?>
                                <?php elseif (isset($_POST['submit_excel'])) : ?>
                                    <?php
                                    ini_set("error_reporting", 1);
                                    session_start();
                                    require_once "koneksi.php";

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
</body>
<?php require_once 'footer.php'; ?>
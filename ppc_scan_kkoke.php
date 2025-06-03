<?php
ini_set("error_reporting", 0);
session_start();
require_once "koneksi.php";
include "utils/helper.php";

$tgl_hari_ini = date('Y-m-d');
$tgl1_kkoke = isset($_POST['tgl1_kkoke']) ? $_POST['tgl1_kkoke'] : $tgl_hari_ini;
$tgl2_kkoke = isset($_POST['tgl2_kkoke']) ? $_POST['tgl2_kkoke'] : $tgl_hari_ini;

if (isset($_POST['execute'])) {
    $productionorder    = $_POST['productionorder'];
    $datenow            = $_POST['datenow'];

    $queryStep = "UPDATE 
                    PRODUCTIONORDER 
                SET 
                    PROGRESSSTATUS = '6',
                    LASTUPDATEUSER = '$_SERVER[REMOTE_ADDR]'
                WHERE 
                    CODE = '$productionorder'";
    $resultStep = db2_exec($conn1, $queryStep);
    
    $queryHeader = "UPDATE 
                        PRODUCTIONDEMANDSTEP 
                    SET 
                        PROGRESSSTATUS = '3',
                        LASTUPDATEDATETIME = '$datenow',
                        LASTUPDATEUSER = '$_SERVER[REMOTE_ADDR]'
                    WHERE 
                        PRODUCTIONORDERCODE = '$productionorder'
                        AND OPERATIONCODE = 'PPC4'";
    $resultHeader = db2_exec($conn1, $queryHeader);

    if ($resultStep && $resultHeader) {
        echo "<script>
                alert('$productionorder BERHASIL di close');
                window.location.href = 'https://online.indotaichen.com/laporan/ppc_scan_kkoke.php';
            </script>";
    } else {
        echo "<script>
                alert('$productionorder GAGAL di close');
                window.location.href = 'https://online.indotaichen.com/laporan/ppc_scan_kkoke.php';
            </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>PPC - Scan KK OKE</title>
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
    <style>
        .button-container {
            position: relative;
            display: inline-block;
        }

        .new-label {
            background-color: yellow; /* Warna latar belakang label */
            color: black; /* Warna teks label */
            padding: 5px 10px; /* Padding untuk label */
            border-radius: 5px; /* Sudut melengkung */
            position: absolute; /* Posisi absolut untuk label */
            top: -10px; /* Atur posisi vertikal */
            right: -10px; /* Atur posisi horizontal */
            font-weight: bold; /* Tebal */
            font-size: 12px; /* Ukuran font */
        }
    </style>
</head>
<?php require_once 'header.php'; ?>

<body>
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">
                        <div class="card">
                            <div class="card-block">
                                <form action="" method="post">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card-block">
                                                <h4 class="sub-title">Close KK OKE</h4>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label"><b>Scan no kartu kerja</b></label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="productionorder" class="form-control form-control-sm" autofocus autocomplete="off">
                                                    </div>
                                                    <label class="col-sm-2 col-form-label">Waktu sekarang</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="datenow" id="datenow" class="form-control form-control-sm" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-2">
                                                        <button type="submit" name="execute" class="btn btn-danger btn-sm"><i class="icofont icofont-save"></i> SUBMIT FOR CLOSED KK</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5 class="mb-0">Data Scan KK OKE</h5>
                                                </div>
                                                <div class="card-block">
                                                    <form method="POST">
                                                        <div class="form-row align-items-end">
                                                            <div class="form-group col-md-3">
                                                                <label for="tgl1_kkoke"><strong>Tanggal Mulai Scan KK OKE</strong></label>
                                                                <input type="date" name="tgl1_kkoke" id="tgl1_kkoke" class="form-control form-control-sm" value="<?= $tgl1_kkoke; ?>">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="tgl2_kkoke"><strong>Tanggal Selesai Scan KK OKE</strong></label>
                                                                <input type="date" name="tgl2_kkoke" id="tgl2_kkoke" class="form-control form-control-sm" value="<?= $tgl2_kkoke; ?>">
                                                            </div>
                                                            <div class="form-group col-md-2">
                                                                <button type="submit" name="search" class="btn btn-primary btn-sm btn-block">
                                                                    <i class="icofont icofont-search"></i> Search
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <?php if (isset($_POST['search'])): ?>
                                                <div class="card mt-3">
                                                    <div class="card-header">
                                                        <h5 class="mb-0">Hasil Pencarian</h5>
                                                    </div>
                                                    <div class="card-block">
                                                        <div class="table-responsive">
                                                            <table id="excel-kkok" class="table table-striped table-bordered nowrap">
                                                                <table id="excelDownload" class="table table-striped table-bordered nowrap">
                                                                <thead class="thead-light">
                                                                    <tr>
                                                                        <th>Tgl Scan KK Ok</th>
                                                                        <th>Langganan</th>
                                                                        <th>Buyer</th>
                                                                        <th>Warna</th>
                                                                        <th>Item</th>
                                                                        <th>Prod. Demand</th>
                                                                        <th>Prod. Order</th>
                                                                        <th>No. Order</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                        $qDataMain  = "SELECT 
                                                                                        CAST(p.LASTUPDATEDATETIME AS DATE) AS TGL_SCAN_KKOKE,
                                                                                        ip.LANGGANAN,
                                                                                        ip.BUYER,
                                                                                        i.WARNA,
                                                                                        TRIM(p2.SUBCODE02) || '-' || TRIM(p2.SUBCODE03) AS ITEM,
                                                                                        p.PRODUCTIONDEMANDCODE,
                                                                                        p.PRODUCTIONORDERCODE,
                                                                                        p2.ORIGDLVSALORDLINESALORDERCODE AS NO_ORDER
                                                                                    FROM
                                                                                        PRODUCTIONDEMANDSTEP p
                                                                                    LEFT JOIN PRODUCTIONDEMAND p2 ON p2.CODE = p.PRODUCTIONDEMANDCODE
                                                                                    LEFT JOIN SALESORDER s ON s.CODE = p2.ORIGDLVSALORDLINESALORDERCODE
                                                                                    LEFT JOIN ITXVIEW_PELANGGAN ip ON ip.ORDPRNCUSTOMERSUPPLIERCODE = s.ORDPRNCUSTOMERSUPPLIERCODE AND ip.CODE = s.CODE
                                                                                    LEFT JOIN ITXVIEWCOLOR i ON i.ITEMTYPECODE = p2.ITEMTYPEAFICODE
                                                                                                            AND i.SUBCODE01 = p2.SUBCODE01
                                                                                                            AND i.SUBCODE02 = p2.SUBCODE02
                                                                                                            AND i.SUBCODE03 = p2.SUBCODE03
                                                                                                            AND i.SUBCODE04 = p2.SUBCODE04
                                                                                                            AND i.SUBCODE05 = p2.SUBCODE05
                                                                                                            AND i.SUBCODE06 = p2.SUBCODE06
                                                                                                            AND i.SUBCODE07 = p2.SUBCODE07
                                                                                                            AND i.SUBCODE08 = p2.SUBCODE08
                                                                                                            AND i.SUBCODE09 = p2.SUBCODE09
                                                                                                            AND i.SUBCODE10 = p2.SUBCODE10
                                                                                    WHERE
                                                                                        p.LASTUPDATEUSER LIKE '10.0%'
                                                                                        AND CAST(p.LASTUPDATEDATETIME AS DATE) BETWEEN '$tgl1_kkoke' AND '$tgl2_kkoke'";
                                                                        $execMain   = db2_exec($conn1, $qDataMain);
                                                                    ?>
                                                                    <?php while ($rowMain   = db2_fetch_assoc($execMain))  : ?>
                                                                        <tr>
                                                                            <td><?= $rowMain['TGL_SCAN_KKOKE'] ?></td>
                                                                            <td><?= $rowMain['LANGGANAN'] ?></td>
                                                                            <td><?= $rowMain['BUYER'] ?></td>
                                                                            <td><?= $rowMain['WARNA'] ?></td>
                                                                            <td><?= $rowMain['ITEM'] ?></td>
                                                                            <td>`<?= $rowMain['PRODUCTIONDEMANDCODE'] ?></td>
                                                                            <td>`<?= $rowMain['PRODUCTIONORDERCODE'] ?></td>
                                                                            <td><?= $rowMain['NO_ORDER'] ?></td>
                                                                        </tr>
                                                                    <?php endwhile; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="files\assets\js\pcoded.min.js"></script>
<script type="text/javascript" src="files\assets\js\script.js"></script>
<?php require_once 'footer.php'; ?>
<script>
    $('#excelDownload').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excelHtml5',
            customize: function (xlsx) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                $('row c[r^="F"]', sheet).each(function () {
                    if ($('is t', this).text().replace(/[^\d]/g, '') * 1 >= 500000) {
                        $(this).attr('s', '20');
                    }
                });
            }
        }]
    });

    function getCurrentDateTimeForDB2() {
        const now = new Date();

        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }

    function updateDateTime() {
        const db2Time = getCurrentDateTimeForDB2();
        document.getElementById('datenow').value = db2Time;
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>
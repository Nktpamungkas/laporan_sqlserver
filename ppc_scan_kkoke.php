<?php
ini_set("error_reporting", 0);
session_start();
require_once "koneksi.php";
include "utils/helper.php";

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
                                                        <input type="text" name="productionorder" class="form-control form-control-sm" autofocus>
                                                    </div>
                                                    <label class="col-sm-2 col-form-label">Status terakhir</label>
                                                    <div class="col-sm-10">
                                                        <label class="col-sm-2 col-form-label">.....</label>
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
<script>
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
<?php require_once 'footer.php'; ?>
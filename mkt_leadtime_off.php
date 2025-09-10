<?php
    session_start();
    require_once "koneksi.php"; // koneksi ke MySQL

    // --- Ambil IP user
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    // --- Cek IP di database
    $q_check = "SELECT 1 FROM ipwhitelist WHERE ip = '$ip' LIMIT 1";
    $result  = mysqli_query($con_invoice, $q_check);

    if (!$result || mysqli_num_rows($result) === 0) {
        // Jika tidak ada di whitelist
        die("Access Denied for IP: $ip");
    }
?>
<?php
    session_start();
    require_once "koneksi.php"; // koneksi ke MySQL

    // --- Ambil IP user
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    // --- Jam masuk (SQL Server akan auto pakai GETDATE(), tapi bisa kirim manual juga)
    $jamMasuk = date("Y-m-d H:i:s");

    // --- Nama halaman
    $page = basename(__FILE__);

    // --- Insert log
    $sql = "INSERT INTO nowprd.log_sales_report (ip_address, access_time, page_name) VALUES (?, ?, ?)";
    $params = [$ip, $jamMasuk, $page];
    $stmt = sqlsrv_query($con_nowprd, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
?>
<?php 
    ini_set("error_reporting", 1);
    session_start();
    require_once "koneksi.php";
    sqlsrv_query($con_nowprd, "DELETE FROM nowprd.itxview_leadtime WHERE CREATEDATETIME BETWEEN GETDATE() - 3 AND GETDATE() - 1");
    sqlsrv_query($con_nowprd, "DELETE FROM nowprd.itxview_leadtime WHERE IPADDRESS = '$_SERVER[REMOTE_ADDR]'"); 

    sqlsrv_query($con_nowprd, "DELETE FROM nowprd.posisikk_cache_leadtime WHERE CREATEDATETIME BETWEEN GETDATE() - 3 AND GETDATE() - 1");
    sqlsrv_query($con_nowprd, "DELETE FROM nowprd.posisikk_cache_leadtime WHERE IPADDRESS = '$_SERVER[REMOTE_ADDR]'"); 

    sqlsrv_query($con_nowprd, "DELETE FROM nowprd.itxview_posisikk_tgl_in_prodorder_ins3_leadtime WHERE CREATEDATETIME BETWEEN GETDATE()-3 AND GETDATE()-1");
    sqlsrv_query($con_nowprd, "DELETE FROM nowprd.itxview_posisikk_tgl_in_prodorder_ins3_leadtime WHERE IPADDRESS = '$_SERVER[REMOTE_ADDR]'"); 

    sqlsrv_query($con_nowprd, "DELETE FROM nowprd.itxview_posisikk_tgl_in_prodorder_cnp1_leadtime WHERE CREATEDATETIME BETWEEN GETDATE()-3 AND GETDATE()-1");
    sqlsrv_query($con_nowprd, "DELETE FROM nowprd.itxview_posisikk_tgl_in_prodorder_cnp1_leadtime WHERE IPADDRESS = '$_SERVER[REMOTE_ADDR]'"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>MKT - Internal Lead Time</title>
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
</head>
<?php require_once 'header.php'; ?>
<body>
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Filter Data</h5>
                                    </div>
                                    <div class="card-block">
                                        <form action="mkt_leadtime_excel.php" method="POST">
                                            <div class="row">
                                                <div class="col-sm-12 col-xl-2 m-b-30">
                                                    <h4 class="sub-title">Dari Tanggal</h4>
                                                    <input type="date" name="tgl1" class="form-control" id="tgl1" value="<?php if (isset($_POST['submit'])){ echo $_POST['tgl1']; } ?>">
                                                </div>
                                                <div class="col-sm-12 col-xl-2 m-b-30">
                                                    <h4 class="sub-title">Sampai Tanggal</h4>
                                                    <input type="date" name="tgl2" class="form-control" id="tgl2" value="<?php if (isset($_POST['submit'])){ echo $_POST['tgl2']; } ?>">
                                                </div>
                                                <div class="col-sm-12 col-xl-12 m-b-30">
                                                    <button type="submit" name="submit" class="btn btn-primary"><i class="icofont icofont-search-alt-1"></i> Cari data</button>
                                                    <a class="btn btn-warning" href="mkt_leadtime.php"><i class="icofont icofont-refresh"></i> Reset</a>
                                                </div>
                                            </div>
                                            <p>*Note : Jika data terasa mulai <b>lambat</b> cobalah untuk klik tombol <b><i class="icofont icofont-refresh"></i> Reset</b> untuk menghapus semua history pencarian.</p>
                                        </form>
                                    </div>
                                </div>
                                <?php if(isset($_POST['<i class="icofont icofont-refresh"></i> Reset'])) : ?>
                                    <?php
                                        ini_set("error_reporting", 1);
                                        session_start();
                                        require_once "koneksi.php";
                                        sqlsrv_query($con_nowprd, "DELETE FROM nowprd.itxview_leadtime");
                                        header("Location: mkt_leadtime.php");
                                       
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
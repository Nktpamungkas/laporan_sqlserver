<?php
session_start();

require_once "koneksi.php";
$date = date('Y-m-d H:i:s');
$menu = 'ppc_laporan_summary_order_ppc.php'; // Set the menu for this login
$ip_comp = $_SERVER['REMOTE_ADDR'];
$q_cek_login    = sqlsrv_query($con_nowprd, "SELECT COUNT(*) AS COUNT FROM nowprd.log_activity_users WHERE IPADDRESS = '$ip_comp' AND menu = '$menu'");
$data_login     = sqlsrv_fetch_array($q_cek_login);
if ($data_login['COUNT'] == '1') {
    $q_waktu_cek_login    = sqlsrv_query($con_nowprd, "SELECT
                                                            DATEDIFF( MINUTE, CREATEDATETIME, GETDATE( ) ) AS selisih_menit 
                                                        FROM
                                                            nowprd.log_activity_users 
                                                        WHERE
                                                            IPADDRESS = '$ip_comp' AND menu = '$menu'");
    $data_waktu_login     = sqlsrv_fetch_array($q_waktu_cek_login);

    if ($data_waktu_login['selisih_menit'] > 30) {
        sqlsrv_query($con_nowprd, "DELETE FROM nowprd.log_activity_users WHERE IPADDRESS = '$ip_comp' AND menu = '$menu'");
        header("Location: login_ppc_laporan_summary_order_ppc.php");
    } else {
        sqlsrv_query($con_nowprd, "UPDATE nowprd.log_activity_users
                                        SET CREATEDATETIME = '$date'
                                        WHERE IPADDRESS = '$ip_comp' AND menu = '$menu'");
        header("Location: ppc_laporan_summary_order_ppc.php");
        exit();
    }
}



if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $menu = 'ppc_laporan_summary_order_ppc.php'; // Set the menu for this login

    $query      = "SELECT * FROM nowprd.users WHERE username = ? AND password = ? AND menu = ?";
    $params     = array($username, $password, $menu);
    $stmt       = sqlsrv_query($con_nowprd, $query, $params);


    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    if (sqlsrv_has_rows($stmt)) {
        $userLogin = sqlsrv_fetch_array($stmt);
        $id_user = $userLogin['id'];
        
        $_SESSION['iduser'] = $id_user;
        $_SESSION['username'] = $username;
        $date = date('Y-m-d H:i:s');
        $logQuery = "INSERT INTO nowprd.log_activity_users ([user], IPADDRESS, CREATEDATETIME, menu) VALUES (?, ?, ?, ?)";
        $logParams = array($username, $_SERVER['REMOTE_ADDR'], $date, $menu);
        $query =  sqlsrv_query($con_nowprd, $logQuery, $logParams);
        header("Location: ppc_laporan_summary_order_ppc.php"); // Ganti dengan halaman setelah login
    } else {
        $error_message = "Username atau password salah. Silakan coba lagi.";
    }

    sqlsrv_close($con_nowprd);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>LOGIN-Buku Resep Digital</title>
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
    <link rel="stylesheet" href="files\bower_components\select2\css\select2.min.css">
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
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <section style="margin: 0 auto 0 auto; max-width: 50%">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <form class="md-float-material form-material" action="" method="post">
                        <div class="auth-box card">
                            <div class="card-block">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="text-center"><i class="feather icon-lock text-primary f-60 p-t-15 p-b-20 d-block"></i></h3>
                                    </div>
                                </div>
                                <div class="form-group form-primary">
                                    <input type="text" name="username" style="text-align: center;" class="form-control" required placeholder="Username">
                                    <span class="form-bar"></span>
                                </div>
                                <div class="form-group form-primary">
                                    <input type="password" name="password" style="text-align: center;" class="form-control" required placeholder="Password">
                                    <span class="form-bar"></span>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" name="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20"><i class="icofont icofont-lock"></i> Buka Halaman ini </button>
                                    </div>
                                </div>
                                <?php if (isset($error_message)) { ?>
                                    <center>
                                        <p class="error-message">
                                            <code>
                                                <b><?php echo $error_message; ?></b>
                                            </code>
                                        </p>
                                    </center>
                                <?php } ?>
                                <div class="row">
                                    <div class="col-md-10">
                                        <p class="text-inverse text-left m-b-0">Anda Belum login, silahkan login terlebih dahulu !</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
<?php require_once 'footer.php'; ?>
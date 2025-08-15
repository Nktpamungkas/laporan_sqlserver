<?php
session_start();
require_once "koneksi.php";
$date = date('Y-m-d H:i:s');
$menu = 'dye_create_topping.php';
$loggedInUser = $_SESSION['username'];

// Check existing login activity
$q_cek_login = sqlsrv_query($con_nowprd, "SELECT COUNT(*) AS COUNT FROM nowprd.log_activity_users WHERE [user] = ? AND menu = ?", [$loggedInUser, $menu]);
$data_login = sqlsrv_fetch_array($q_cek_login);

if ($data_login['COUNT'] == 1) {
    $q_waktu_cek_login = sqlsrv_query($con_nowprd, "SELECT DATEDIFF(MINUTE, CREATEDATETIME, GETDATE()) AS selisih_menit FROM nowprd.log_activity_users WHERE [user] = ?", [$loggedInUser]);
    $data_waktu_login = sqlsrv_fetch_array($q_waktu_cek_login);

    if ($data_waktu_login['selisih_menit'] > 30) {
        sqlsrv_query($con_nowprd, "DELETE FROM nowprd.log_activity_users WHERE [user] = ? AND menu = ?", [$loggedInUser, $menu]);
    } else {
        sqlsrv_query($con_nowprd, "UPDATE nowprd.log_activity_users SET CREATEDATETIME = ? WHERE [user] = ? AND menu = ?", [$date, $loggedInUser, $menu]);
        header("Location: dye_create_topping.php");
        exit();
    }
}else{
    sqlsrv_query($con_nowprd, "DELETE FROM nowprd.log_activity_users WHERE IPADDRESS = ? AND menu = ?", [$_SERVER['REMOTE_ADDR'], $menu]);
}

// Process login
if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $option = $_POST['option'];

    $query = "SELECT 1 AS COUNT, ID_ AS USERNAME FROM ACT_ID_USER WHERE ID_ = ?";
    $stmt = db2_prepare($conn1, $query);
    db2_execute($stmt, [$username]);

    $data_user = db2_fetch_assoc($stmt);

    if ($stmt === false) {
        die(print_r(db2_stmt_errormsg(), true));
    }

    if ($data_user && $data_user['COUNT'] == 1) {
        $_SESSION['username'] = $username;
        $_SESSION['option'] = $option;

        $logQuery = "INSERT INTO nowprd.log_activity_users ([user], IPADDRESS, CREATEDATETIME, menu) VALUES (?, ?, ?, ?)";
        $logParams = array($username, $_SERVER['REMOTE_ADDR'], $date, $menu);
        sqlsrv_query($con_nowprd, $logQuery, $logParams);

        header("Location: dye_create_topping.php?option=" . strtolower($option));
        exit();
    } else {
        $_SESSION['login_error'] = "Username tidak sesuai dengan user NOW. Silakan coba lagi.";
    }
}
?>
<?php require_once 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>LOGIN - Create Topping/Adjust</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="files/assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="files/bower_components/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="files/assets/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="files/assets/icon/icofont/css/icofont.css">
    <link rel="stylesheet" type="text/css" href="files/assets/icon/feather/css/feather.css">
    <link rel="stylesheet" href="files/bower_components/select2/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="files/assets/pages/prism/prism.css">
    <link rel="stylesheet" type="text/css" href="files/assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="files/assets/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" type="text/css" href="files/assets/css/pcoded-horizontal.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    <br><br><br><br><br><br>
    <section style="margin: 0 auto; max-width: 50%;">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-sm-6">
                    <form class="md-float-material form-material" action="" method="post">
                        <div class="auth-box card">
                            <div class="card-block">
                                <div class="text-center">
                                    <i class="feather icon-lock text-primary f-60 p-t-15 p-b-20 d-block"></i>
                                    <h4>CREATE TOPPING/ADJUST</h4>
                                </div>
                                <div class="form-group form-primary">
                                    <input type="text" name="username" class="form-control text-center" required placeholder="USERNAME NOW">
                                </div>
                                <div class="form-group form-primary">
                                    <select name="option" class="form-control text-center" required>
                                        <option value="">-- Pilih Opsi --</option>
                                        <option value="Toping">Toping</option>
                                        <option value="Adjust">Adjust</option>
                                    </select>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">
                                    <i class="icofont icofont-lock"></i> LOGIN
                                </button>
                                <div class="text-left mt-2">
                                    <p class="text-inverse">Anda belum login, silakan login terlebih dahulu!</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tampilkan alert jika login error -->
    <?php if (isset($_SESSION['login_error'])): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: <?= json_encode($_SESSION['login_error']) ?> // aman terhadap kutipan atau karakter spesial
            });
        </script>
        <?php unset($_SESSION['login_error']); ?>
    <?php endif; ?>

    <?php require_once 'footer.php'; ?>
</body>
</html>

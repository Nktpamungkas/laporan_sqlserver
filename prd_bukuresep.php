<?php
    session_start();
    require_once "koneksi.php";
    $menu = 'prd_bukuresep.php'; // Set the menu for this login
    $ip_comp = $_SERVER['REMOTE_ADDR'];

    // Cek apakah tombol logout ditekan
    if (isset($_POST['logout'])) {

        $qLogout    = "DELETE FROM nowprd.log_activity_users WHERE IPADDRESS = '$ip_comp' AND menu = '$menu'";
        sqlsrv_query($con_nowprd, $qLogout);

        // Redirect ke login
        header("Location: Login_prd_bukuresep.php");
        exit();
}
?>
<?php
    session_start();
    require_once "koneksi.php";
    include "utils/helper.php";
    $date = date('Y-m-d H:i:s');
    
    session_start();
    $q_cek_login    = sqlsrv_query($con_nowprd, "SELECT COUNT(*) AS COUNT FROM nowprd.log_activity_users WHERE IPADDRESS = '$ip_comp' AND menu = '$menu'");
    $data_login     = sqlsrv_fetch_array($q_cek_login);
    if ($data_login['COUNT'] == '1') {
        $q_waktu_cek_login    = sqlsrv_query($con_nowprd, "SELECT DATEDIFF(MINUTE, CREATEDATETIME, GETDATE()) AS selisih_menit FROM nowprd.log_activity_users WHERE IPADDRESS = '$ip_comp' AND menu = '$menu'");
        $data_waktu_login     = sqlsrv_fetch_array($q_waktu_cek_login);
        if ($data_waktu_login['selisih_menit'] > 30) {
            sqlsrv_query($con_nowprd, "DELETE FROM nowprd.log_activity_users WHERE IPADDRESS = '$ip_comp' AND menu = '$menu'");
            header("Location: Login_prd_bukuresep.php");
            exit();
        } else {
            sqlsrv_query($con_nowprd, "UPDATE nowprd.log_activity_users
                                            SET CREATEDATETIME = '$date'
                                            WHERE IPADDRESS = '$ip_comp' AND menu = '$menu'");
        }
    } else {
        header("Location: Login_prd_bukuresep.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>PRD - Buku Resep</title>
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
    <link href="alert/sweetalert2.min.css" rel="stylesheet" />
    <script src="alert/sweetalert2.all.min.js"></script>

    <!-- Online Card -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>
<style>
    .btn-cetak-minimal {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        color: #0d6efd;
        text-decoration: none;
        border: 1px solid #0d6efd;
        border-radius: 4px;
        padding: 2px 8px;
        background: transparent;
        transition: all 0.2s ease-in-out;
    }

    .btn-cetak-minimal i {
        font-size: 14px;
    }

    .btn-cetak-minimal:hover {
        background: #0d6efd;
        color: #fff;
        text-decoration: none;
    }
</style>
<style>
    /* Tab styling */
    .tabs {
        border-bottom: 1px solid #ddd;
        display: flex;
        gap: 1px;
        margin-bottom: 20px;
    }

    .tab-btn {
        padding: 8px 16px;
        border: 1px solid #ccc;
        border-bottom: none;
        background-color: #f8f9fa;
        cursor: pointer;
        font-weight: 500;
        font-size: 14px;
        border-top-left-radius: 6px;
        border-top-right-radius: 6px;
    }

    .tab-btn.active {
        background-color: #fff;
        border-color: #ccc #ccc #fff;
    }

    .tab-content {
        border: 1px solid #ccc;
        border-radius: 0 0 6px 6px;
        padding: 20px;
        background-color: #fff;
        display: none;
    }

    .tab-content.active {
        display: block;
    }
</style>
<style>
    .tabs {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .tab-buttons {
        display: flex;
        gap: 5px;
    }
    .logout-btn {
        background-color: red;
        color: white;
        border: none;
        padding: 8px 12px;
        cursor: pointer;
        border-radius: 4px;
    }
    .logout-btn:hover {
        background-color: darkred;
    }
</style>
<?php require_once 'header.php'; ?>

<!-- ONLINE CARD -->
    <style>
        #user-online-box {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 250px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.2);
            z-index: 9999;
            font-size: 14px;
            overflow: hidden;
        }

        #user-online-header {
            padding: 10px;
            background: #0d6efd;
            color: white;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }

        #user-online-body {
            padding: 10px;
            max-height: 250px;
            overflow-y: auto;
            background: #ffffff;
            display: block;
        }

        #user-online-box ul {
            padding-left: 0;
            margin: 0;
            list-style: none;
        }

        #user-online-box li {
            background: #f1f1f1;
            padding: 8px 10px;
            margin-bottom: 6px;
            border-radius: 8px;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
        }

        #user-online-box li i {
            margin-right: 8px;
            color: #0d6efd;
        }
    </style>

    <div id="user-online-box">
        <div id="user-online-header">
            <span><i class="bi bi-people-fill"></i> Sedang Online (<span id="online-count"><?= $jumlahOnline ?></span>)</span>
            <span id="toggle-user-online" style="cursor: pointer;">
                <i class="bi bi-plus-circle"></i>
            </span>
        </div>

        <div id="user-online-body" style="display: none;">
            <ul id="user-online-list">
                <?= $htmlList ?>
            </ul>
        </div>
    </div>

    <script>
        const toggleButton = document.getElementById("toggle-user-online");
        const bodyBox = document.getElementById("user-online-body");
        const onlineCount = document.getElementById("online-count");
        const onlineList = document.getElementById("user-online-list");

        // Toggle minimize
        toggleButton.addEventListener("click", function () {
            const isHidden = bodyBox.style.display === "none";
            bodyBox.style.display = isHidden ? "block" : "none";
            toggleButton.innerHTML = isHidden 
                ? '<i class="bi bi-dash-circle"></i>'
                : '<i class="bi bi-plus-circle"></i>';
        });

        // Fungsi ambil data online
        function loadUserOnline() {
            fetch("get_user_online.php")
                .then(res => res.json())
                .then(data => {
                    onlineCount.textContent = data.jumlah;
                    onlineList.innerHTML = data.html;
                })
                .catch(err => console.error("Gagal ambil data online:", err));
        }

        // Pertama kali jalan
        loadUserOnline();

        // Auto-refresh tiap 10 detik
        setInterval(loadUserOnline, 10000);
    </script>


<!-- ONLINE CARD -->

<body>
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">
                        <div class="row">
                            <div class="col-12">
                                <!-- Card Filter -->
                                <div class="card">
                                    <div class="card-header">
                                        <div class="tabs">
                                            <div class="tab-buttons">
                                                <button class="tab-btn active" onclick="openTab('tab-filter')">Filter Data</button>
                                                <button class="tab-btn" onclick="openTab('tab-password')">Change Password</button>
                                            </div>
                                            <form method="POST" style="margin:0;">
                                                <button type="submit" name="logout" class="logout-btn"
                                                        onclick="return confirm('Yakin ingin logout? Data aktivitas akan dihapus.')">
                                                    LOGOUT
                                                </button>
                                            </form>
                                        </div>
                                        <!-- Filter Data Tab -->
                                        <div id="tab-filter" class="tab-content active">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Filter Data</h5>
                                                </div>
                                                <div class="card-block">
                                                    <form action="" method="post" class="p-4 border rounded shadow-sm bg-white">
                                                        <div class="row g-3 align-items-end">
                                                            <div class="col-md-4">
                                                                <label for="color_code" class="form-label fw-semibold">Color Code:</label>
                                                                <input type="text" class="form-control" id="color_code" name="color_code"
                                                                    value="<?php if (isset($_POST['submit'])) { echo $_POST['color_code']; } elseif (isset($_GET['color_code'])) { echo $_GET['color_code']; } ?>"
                                                                    required>
                                                            </div>
                                                            <div class="col-md-4 d-grid">
                                                                <button type="submit" name="submit" class="btn btn-primary">
                                                                    <i class="bi bi-search"></i> Cari Data
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Change Password Tab -->
                                        <div id="tab-password" class="tab-content">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Change Password</h5>
                                                </div>
                                                <div class="card-block">
                                                    <form method="post" action="" class="p-4 border rounded shadow-sm bg-white">
                                                        <div class="mb-3">
                                                            <label for="old_pass" class="form-label fw-semibold">Old Password:</label>
                                                            <input type="password" class="form-control" id="old_pass" name="old_pass" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="new_pass" class="form-label fw-semibold">New Password:</label>
                                                            <input type="password" class="form-control" id="new_pass" name="new_pass" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="confirm_pass" class="form-label fw-semibold">Confirm New Password:</label>
                                                            <input type="password" class="form-control" id="confirm_pass" name="confirm_pass" required>
                                                        </div>
                                                        <button type="submit" name="change_password" class="btn btn-success">
                                                            <i class="bi bi-shield-lock"></i> Ubah Password
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Hasil -->
                                <?php if (isset($_POST['submit'])) : ?>
                                    <div class="row">
                                        <!-- Recipe Labdip -->
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>RECIPE LABDIP</h5>
                                                </div>
                                                <div class="card-block">
                                                    <div class="table-responsive dt-responsive">
                                                        <table id="table_labdip" class="table table-striped table-bordered nowrap compact">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Buyer</th>
                                                                    <th>Warna</th>
                                                                    <th>Recipe</th>
                                                                    <th>Opsi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    $color_code = $_POST['color_code'];
                                                                    $color_code_potong = substr($_POST['color_code'], 0, -1);
                                                                    $query_labdip = "SELECT DISTINCT
                                                                        tbl_matching_detail.id_status,
                                                                        tbl_matching.color_code,
                                                                        CONCAT(tbl_matching.recipe_code, '-', tbl_matching.no_resep) AS recipe,
                                                                        tbl_matching.no_resep,
                                                                        tbl_matching.jenis_matching,
                                                                        tbl_matching.buyer,
                                                                        tbl_matching.warna
                                                                        FROM [db_laborat].tbl_matching
                                                                        LEFT JOIN [db_laborat].tbl_matching_detail ON tbl_matching_detail.id_matching = tbl_matching.id
                                                                        LEFT JOIN [db_laborat].tbl_status_matching ON tbl_status_matching.idm = tbl_matching.no_resep
                                                                        WHERE (
                                                                            tbl_matching.color_code LIKE '%$color_code%' OR
                                                                            tbl_matching.color_code LIKE '%$color_code_potong%'
                                                                        )
                                                                        AND NOT tbl_matching.jenis_matching IN ('Perbaikan NOW', 'Perbaikan')
                                                                        AND tbl_matching.jenis_matching IN ('LD NOW', 'L/D')
                                                                        AND NOT tbl_matching.recipe_code IN ('', '-')
                                                                        AND NOT tbl_status_matching.[status] = 'arsip'";

                                                                    $result_labdip = sqlsrv_query($con_db_lab, $query_labdip);
                                                                    $no_labdip = 1;
                                                                ?>
                                                                <?php if ($result_labdip === false) : ?>
                                                                    <tr>
                                                                        <td colspan="5">Gagal mengambil data Labdip.</td>
                                                                    </tr>
                                                                    <!-- <?php echo htmlspecialchars(print_r(sqlsrv_errors(), true)); ?> -->
                                                                <?php else : ?>
                                                                    <?php while ($row_labdip = sqlsrv_fetch_array($result_labdip, SQLSRV_FETCH_ASSOC)) : ?>
                                                                    <tr>
                                                                        <td><?= $no_labdip++; ?></td>
                                                                        <td><?= $row_labdip['buyer']; ?></td>
                                                                        <td><?= $row_labdip['warna']; ?></td>
                                                                        <td><?= $row_labdip['recipe']; ?></td>
                                                                        <td>
                                                                            <a href="https://online.indotaichen.com/laborat/pages/cetak/cetak_resep.php?ids=<?= $row_labdip['id_status'] ?>&idm=<?= $row_labdip['no_resep'] ?>&frm=bresep&created_by=<?= $_SESSION['iduser']; ?>" target="_blank" class="btn-cetak-minimal">
                                                                                <i class="icofont icofont-print"></i> Cetak
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php endwhile; ?>
                                                                <?php endif; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Recipe Matching Ulang -->
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>RECIPE MATCHING ULANG & DEVELOPMENT</h5>
                                                </div>
                                                <div class="card-block">
                                                    <div class="table-responsive dt-responsive">
                                                        <table id="table_matching" class="table table-striped table-bordered nowrap compact">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Buyer</th>
                                                                    <th>Warna</th>
                                                                    <th>Recipe</th>
                                                                    <th>Opsi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    $color_code = $_POST['color_code'];
                                                                    $query_mu = "SELECT DISTINCT
                                                                        -- tbl_matching_detail.id_status,
                                                                        tbl_status_matching.id AS id_status,
                                                                        tbl_matching.color_code,
                                                                        CONCAT(tbl_matching.recipe_code, '-', tbl_matching.no_resep) AS recipe,
                                                                        tbl_matching.no_resep,
                                                                        tbl_matching.jenis_matching,
                                                                        tbl_matching.buyer,
                                                                        tbl_matching.warna
                                                                        FROM [db_laborat].tbl_matching
                                                                        LEFT JOIN [db_laborat].tbl_matching_detail ON tbl_matching_detail.id_matching = tbl_matching.id
                                                                        LEFT JOIN [db_laborat].tbl_status_matching ON tbl_status_matching.idm = tbl_matching.no_resep
                                                                        WHERE tbl_matching.color_code = '$color_code'
                                                                        AND NOT tbl_matching.jenis_matching IN ('Perbaikan NOW', 'Perbaikan')
                                                                        AND tbl_matching.jenis_matching IN ('Matching Ulang NOW', 'Matching Ulang', 'Matching Development')
                                                                        AND NOT tbl_matching.recipe_code IN ('', '-')
                                                                        AND NOT tbl_status_matching.[status] = 'arsip'";

                                                                    $result_mu = sqlsrv_query($con_db_lab, $query_mu);
                                                                    $no_mu = 1;
                                                                ?>
                                                                <?php if ($result_mu === false) : ?>
                                                                    <tr>
                                                                        <td colspan="5">Gagal mengambil data Matching Ulang.</td>
                                                                    </tr>
                                                                    <!-- <?php echo htmlspecialchars(print_r(sqlsrv_errors(), true)); ?> -->
                                                                <?php else : ?>
                                                                    <?php while ($row_mu = sqlsrv_fetch_array($result_mu, SQLSRV_FETCH_ASSOC)) : ?>
                                                                    <tr>
                                                                        <td><?= $no_mu++; ?></td>
                                                                        <td><?= $row_mu['buyer']; ?></td>
                                                                        <td><?= $row_mu['warna']; ?></td>
                                                                        <td><?= $row_mu['recipe']; ?></td>
                                                                        <td>
                                                                            <a href="https://online.indotaichen.com/laborat/pages/cetak/cetak_resep.php?ids=<?= $row_mu['id_status'] ?>&idm=<?= $row_mu['no_resep'] ?>&frm=bresep&created_by=<?= $_SESSION['iduser']; ?>" target="_blank" class="btn-cetak-minimal">
                                                                                <i class="icofont icofont-print"></i> Cetak
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php endwhile; ?>
                                                                <?php endif; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <!-- End Card Hasil -->

                                <?php 
                                    if (isset($_POST['change_password'])) {
                                        $user_id = $_SESSION['iduser'];
                                        $old_pass = $_POST['old_pass'];
                                        $new_pass = $_POST['new_pass'];
                                        $confirm_pass = $_POST['confirm_pass'];
                                        $msg = '';

                                        // Fetch current password from SQL Server database (plain text)
                                        $sql = "SELECT password FROM nowprd.users WHERE id = ?";
                                        $params = array($user_id);
                                        $stmt = sqlsrv_query($con_nowprd, $sql, $params);
                                        if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                            $db_pass = $row['password'];
                                            // Check old password
                                            if ($old_pass !== $db_pass) {
                                                echo "<script>
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Error',
                                                        text: 'Old password is incorrect.',
                                                        allowOutsideClick: false,
                                                        allowEscapeKey: false,
                                                        showConfirmButton: false,
                                                        timer: 2000
                                                    });
                                                </script>";
                                            } elseif ($new_pass !== $confirm_pass) {
                                                echo "<script>
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Error',
                                                        text: 'New passwords do not match.',
                                                        allowOutsideClick: false,
                                                        allowEscapeKey: false,
                                                        showConfirmButton: false,
                                                        timer: 2000
                                                    });
                                                </script>";
                                            } else {
                                                // Update password (plain text)
                                                $sql_update = "UPDATE nowprd.users SET password = ? WHERE id = ?";
                                                $params_update = array($new_pass, $user_id);
                                                $stmt_update = sqlsrv_query($con_nowprd, $sql_update, $params_update);
                                                if ($stmt_update) {
                                                    echo "<script>
                                                        Swal.fire({
                                                            icon: 'success',
                                                            title: 'Success',
                                                            text: 'Password changed successfully.',
                                                            allowOutsideClick: false,
                                                            allowEscapeKey: false,
                                                            showConfirmButton: false,
                                                            timer: 2000
                                                        });
                                                    </script>";
                                                } else {
                                                    echo "<script>
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Error',
                                                            text: 'Failed to update password.',
                                                            allowOutsideClick: false,
                                                            allowEscapeKey: false,
                                                            showConfirmButton: false,
                                                            timer: 2000
                                                        });
                                                    </script>";
                                                }
                                            }
                                        } else {
                                            echo "<script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Error',
                                                    text: 'User not found.',
                                                    allowOutsideClick: false,
                                                    allowEscapeKey: false,
                                                    showConfirmButton: false,
                                                    timer: 2000
                                                });
                                            </script>";
                                        }
                                    } 
                                ?>
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
    $(document).ready(function() {
        $('#table_labdip').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Cari di Labdip..."
            }
        });

        $('#table_matching').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Cari di Matching Ulang..."
            }
        });
    });
</script>
<script>
    function openTab(tabId) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));

        // Activate selected tab
        document.getElementById(tabId).classList.add('active');
        event.target.classList.add('active');
    }
</script>



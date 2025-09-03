<?php
    session_start();
    require_once "koneksi.php";
    $menu = 'ppc_laporan_summary_order_ppc.php'; // Set the menu for this login
    $ip_comp = $_SERVER['REMOTE_ADDR'];

    // Cek apakah tombol logout ditekan
    if (isset($_POST['logout'])) {

        $qLogout    = "DELETE FROM nowprd.log_activity_users WHERE IPADDRESS = '$ip_comp' AND menu = '$menu'";
        sqlsrv_query($con_nowprd, $qLogout);

        // Redirect ke login
        header("Location: login_ppc_laporan_summary_order_ppc.php");
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
        if ($data_waktu_login['selisih_menit'] > 5) {
            sqlsrv_query($con_nowprd, "DELETE FROM nowprd.log_activity_users WHERE IPADDRESS = '$ip_comp' AND menu = '$menu'");
            header("Location: login_ppc_laporan_summary_order_ppc.php");
            exit();
        } else {
            sqlsrv_query($con_nowprd, "UPDATE nowprd.log_activity_users
                                                SET CREATEDATETIME = '$date'
                                                WHERE IPADDRESS = '$ip_comp' AND menu = '$menu'");
        }
    } else {
        header("Location: login_ppc_laporan_summary_order_ppc.php");
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
                                            <b>Fitur tidak tersedia.</b>
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
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-block">
                                                <div class="dt-responsive table-responsive">
                                                    <table id="demandTable" class="table table-striped table-bordered nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th>MKT</th>
                                                                <th>NO MC</th>
                                                                <th>LANGGANAN</th>
                                                                <th>BUYER</th>
                                                                <th>ITEM</th>
                                                                <th>SALES ORDER</th>
                                                                <th>JENIS KAIN</th>
                                                                <th>WARNA</th>
                                                                <th>NO WARNA</th>
                                                                <th>LOT</th>
                                                                <th>PRODUCTION ORDER</th>
                                                                <th>DEMAND</th>
                                                                <th>DEL. INTERNAL</th>
                                                                <th>DEL. ACTUAL</th>
                                                                <th>LBR</th>
                                                                <th>GRMS</th>
                                                                <th>Bruto Per KK</th>
                                                                <th>Bruto Sales Order Line</th>
                                                                <th>NETTO</th>
                                                                <th>PO GREIGE | GREIGE AWAL | GREIGE AKHIR</th>
                                                                <th>VARIAN GREIGE</th>
                                                                <th>ROLL</th>
                                                                <th>QTY</th>
                                                                <th>PROSES PRETREATMENT</th>
                                                                <th>TGL BAGI KAN</th>
                                                                <th>TGL PRESET</th>
                                                                <th>CELUP GREIGE</th>
                                                                <th>KETERANGAN</th>
                                                                <th>LEADTIME ACTUAL</th>
                                                                <th>TGL TERIMA ORDER</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                            $menu = 'ppc_laporan_summary_order_ppc.php'; // Set the menu for this login
                                            $sql_update = "UPDATE nowprd.users SET password = ? WHERE id = ? AND menu = ?";
                                            $params_update = array($new_pass, $user_id, $menu);
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
        $('#table1').DataTable({
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
<script>
    $(function () {
        // optional: input filter tanggal kalau mau
        const defaultStart = '2025-01-01';
        const defaultEnd   = '2025-09-01';

        $('#demandTable').DataTable({
            processing: true,
            serverSide: true,   // paging/sort/search di server
            searching: true,   // aktifkan pencarian
            ordering: true,       // aktifkan sorting
            ajax: {
            url: 'ajax/lang_demand.php',
            type: 'POST',
            data: function (d) {
                    // kirim filter tanggal (bisa diganti dari datepicker)
                    d.start_date = defaultStart;
                    d.end_date   = defaultEnd;
                }
            },
            columns: [
                { data: 'MKT' },
                { data: 'NO_MC' },
                { data: 'LANGGANAN' },
                { data: 'BUYER' },
                { data: 'ITEM' },
                { data: 'SALESORDER' },
                { data: 'JENIS_KAIN' },
                { data: 'WARNA' },
                { data: 'NO_WARNA' },
                { data: 'LOT' },
                { data: 'PRODUCTIONORDERCODE' },
                { 
                    data: 'DEMAND',
                    render: function (data, type, row) {
                        if (type === 'display' && data) {
                            return '<a target="_blank" href="http://online.indotaichen.com/laporan/ppc_filter_steps.php?demand=' 
                                + encodeURIComponent(data) 
                                + '&prod_order=' 
                                + encodeURIComponent(row.PRODUCTIONORDERCODE) 
                                + '">' + data + '</a>';
                        }
                        return data;
                    }
                },
                { data: 'DEL_INTERNAL' },
                { data: 'DEL_ACTUAL' },
                { data: 'LBR' },
                { data: 'GRMS' },
                { data: 'BRUTO_PER_KK' },
                { data: 'BRUTO_SOL' },
                { data: 'NETTO' },
                { 
                    data: 'PO_GREIGE_GREIGE_AWAL_GREIGE_AKHIR',
                    render: function (data, type, row) {
                        if (type === 'display' && data) {
                            // Kirim 3 parameter via GET
                            var url = 'po_greige_detail.php?prod_order=' 
                                + encodeURIComponent(row.PRODUCTIONORDERCODE)
                                + '&demand=' + encodeURIComponent(row.DEMAND)
                                + '&del_actual=' + encodeURIComponent(row.DEL_ACTUAL);
                            return '<a href="' + url + '" target="_blank">' + data + '</a>';
                        }
                        return data;
                    }
                },
                { data: 'VARIAN_GREIGE' },
                { data: 'ROLL' },
                { data: 'QTY' },
                { data: 'PROSES_PRETREATMENT' },
                { data: 'TGL_BAGI_KAIN' },
                { data: 'TGL_PRESET' },
                { data: 'CELUP_GREIGE' },
                { data: 'KETERANGAN' },
                { data: 'LEADTIME_ACTUAL' },
                { data: 'TGL_TERIMA_ORDER' },
            ]
        });
    });
</script>
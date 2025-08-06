<?php
    session_start();
    require_once "koneksi.php";
    include "utils/helper.php";
    $date = date('Y-m-d H:i:s');
    $menu = 'prd_bukuresep.php'; // Set the menu for this login
    $ip_comp = $_SERVER['REMOTE_ADDR'];
    session_start();
    $q_cek_login    = sqlsrv_query($con_nowprd, "SELECT COUNT(*) AS COUNT FROM nowprd.log_activity_users WHERE IPADDRESS = '$ip_comp' AND menu = '$menu'");
    $data_login     = sqlsrv_fetch_array($q_cek_login);
    if ($data_login['COUNT'] == '1') {
        $q_waktu_cek_login    = sqlsrv_query($con_nowprd, "SELECT DATEDIFF(MINUTE, CREATEDATETIME, GETDATE()) AS selisih_menit FROM nowprd.log_activity_users WHERE IPADDRESS = '$ip_comp' AND menu = '$menu'");
        $data_waktu_login     = sqlsrv_fetch_array($q_waktu_cek_login);
        if ($data_waktu_login['selisih_menit'] > 5) {
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
    <style>
        
    </style>
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
                                        <h5>Filter Data</h5>
                                    </div>
                                    <div class="card-block">
                                        <form action="" method="post" class="p-4 border rounded shadow-sm bg-white">
                                            <div class="row g-3 align-items-end">
                                                <div class="col-md-4">
                                                    <label for="date" class="form-label fw-semibold">Color Code:</label>
                                                    <input type="text" class="form-control" id="color_code" name="color_code" value="<?php if (isset($_POST['submit'])) { echo $_POST['color_code'];} elseif (isset($_GET['color_code'])) { echo $_GET['color_code']; } ?>" required>
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
                                <!-- End Card Filter -->

                                <!-- Card Hasil -->
                                <?php if (isset($_POST['submit'])) : ?>
                                    <div class="row">
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

                                                                    $query_labdip = "SELECT
                                                                                        DISTINCT
                                                                                        tbl_matching_detail.id_status,
                                                                                        tbl_matching.color_code,
                                                                                        CONCAT(tbl_matching.recipe_code, '-', tbl_matching.no_resep) AS recipe,
                                                                                        tbl_matching.no_resep,
                                                                                        tbl_matching.jenis_matching,
                                                                                        tbl_matching.buyer,
                                                                                        tbl_matching.warna,
                                                                                        SUBSTRING_INDEX(SUBSTRING_INDEX(tbl_matching.recipe_code, ' ', 1), ' ', -1) as recipe_code_1, 
	                                                                                    SUBSTRING_INDEX(SUBSTRING_INDEX(tbl_matching.recipe_code, ' ', 2), ' ', -1) as recipe_code_2
                                                                                    FROM
                                                                                        tbl_matching 
                                                                                    LEFT JOIN tbl_matching_detail ON tbl_matching_detail.id_matching = tbl_matching.id
                                                                                    LEFT JOIN tbl_status_matching ON tbl_status_matching.idm = tbl_matching.no_resep
                                                                                    WHERE
                                                                                        (tbl_matching.color_code LIKE '%$color_code%' OR tbl_matching.color_code LIKE '%$color_code_potong%')
                                                                                        AND NOT tbl_matching.jenis_matching IN ('Perbaikan NOW', 'Perbaikan')
                                                                                        AND tbl_matching.jenis_matching IN ('LD NOW', 'L/D')
                                                                                        AND NOT tbl_matching.recipe_code IN ('', '-')
                                                                                        AND NOT tbl_status_matching.status = 'arsip'
                                                                                    ORDER BY
                                                                                        tbl_matching.id DESC";
                                                                    $result_labdip = mysqli_query($con_db_lab, $query_labdip);
                                                                    $no_labdip = 1;
                                                                ?>
                                                                <?php while ($row_labdip = mysqli_fetch_array($result_labdip)) : ?>
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
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                                                                    $query_mu = "SELECT
                                                                                        DISTINCT
                                                                                        tbl_matching_detail.id_status,
                                                                                        tbl_matching.color_code,
                                                                                        CONCAT(tbl_matching.recipe_code, '-', tbl_matching.no_resep) AS recipe,
                                                                                        tbl_matching.no_resep,
                                                                                        tbl_matching.jenis_matching,
                                                                                        tbl_matching.buyer,
                                                                                        tbl_matching.warna,
                                                                                        SUBSTRING_INDEX(SUBSTRING_INDEX(tbl_matching.recipe_code, ' ', 1), ' ', -1) as recipe_code_1, 
	                                                                                    SUBSTRING_INDEX(SUBSTRING_INDEX(tbl_matching.recipe_code, ' ', 2), ' ', -1) as recipe_code_2
                                                                                    FROM
                                                                                        tbl_matching 
                                                                                    LEFT JOIN tbl_matching_detail ON tbl_matching_detail.id_matching = tbl_matching.id
                                                                                    LEFT JOIN tbl_status_matching ON tbl_status_matching.idm = tbl_matching.no_resep
                                                                                    WHERE
                                                                                        tbl_matching.color_code = '$color_code'
                                                                                        AND NOT tbl_matching.jenis_matching IN ('Perbaikan NOW', 'Perbaikan')
                                                                                        AND tbl_matching.jenis_matching IN ('Matching Ulang NOW', 'Matching Ulang', 'Matching Development')
                                                                                        AND NOT tbl_matching.recipe_code IN ('', '-')
                                                                                        AND NOT tbl_status_matching.status = 'arsip'
                                                                                    ORDER BY
                                                                                        tbl_matching.id DESC";
                                                                    $result_mu = mysqli_query($con_db_lab, $query_mu);
                                                                    $no_mu = 1;
                                                                ?>
                                                                <?php while ($row_mu = mysqli_fetch_array($result_mu)) : ?>
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
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <!-- End Card Hasil -->

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

<?php
ini_set("error_reporting", 1);
session_start();
require_once "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Orgatex Dyelot</title>
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
    <link href="alert/toastr.css" rel="stylesheet" />
    <link href="alert/sweetalert2.min.css" rel="stylesheet" />
    <style>
        a.three:link {color:#ff0000;}
        a.three:visited {color:#0000ff;}
        a.three:hover {background:#66ff66;}

        .text-wrap {
            white-space: normal;
        }

        .width-desc {
            width: 40rem;
        }

        .width-desc {
            width: 30rem;
        }

        #loadingOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            /* White background with some transparency */
            z-index: 9999;
            /* Ensure it's on top of everything */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .spinner {
            border: 8px solid rgba(0, 0, 0, 0.1);
            border-left-color: #3498db;
            /* Customize the spinner color */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<?php require_once 'header.php'; ?>

<body>
    <div id="loadingOverlay" style="display:none;">
        <div class="spinner"></div>
    </div>
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
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-sm-12 col-xl-2 m-b-0">
                                                    <h4 class="sub-title">Tanggal Awal</h4>
                                                    <div class="input-group input-group-sm">
                                                        <input type="date" class="form-control" placeholder="input-group-sm" name="tgl" value="<?php if (isset($_POST['cari'])) {
                                                                                                                                                    echo $_POST['tgl'];
                                                                                                                                                } ?>">
                                                    </div>
                                                    <button type="submit" name="cari" class="btn btn-primary btn-sm"><i class="icofont icofont-search-alt-1"></i> Search</button>
                                                    <input type="button" name="reset" value="Reset" onclick="window.location.href='orgatex_dyelot_view.php'" class="btn btn-warning btn-sm">
                                                </div>
                                                <div class="col-sm-12 col-xl-2 m-b-0">
                                                    <h4 class="sub-title">Tanggal Akhir</h4>
                                                    <div class="input-group input-group-sm">
                                                        <input type="date" class="form-control" placeholder="input-group-sm" name="tgl2" value="<?php if (isset($_POST['cari'])) {
                                                                                                                                                    echo $_POST['tgl2'];
                                                                                                                                                } ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" style="background-color: #FFF7AE; padding: 5px; font-family: 'Courier New', monospace; font-size: 15px;">
                                        <center>Data yang tampil adalah data yang tercatat hari ini.</center>
                                        <center>Silahkan gunakan fitur pencarian untuk menemukan lebih banyak data pada tanggal yang Anda inginkan.</center>
                                        <!-- <center><b>UNDER MAINTENANCE !!</b><br>PROGRAM TETAP BISA DIGUNAKAN.</center> -->
                                    </div>
                                    <div class="card-block">
                                        <div class="dt-responsive table-responsive">
                                            <table id="dyelot-table" class="table compact table-striped table-bordered nowrap">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">AutoKey</th>
                                                        <th class="text-center">Dye Ref No</th>
                                                        <th class="text-center">Dyelot</th>
                                                        <th class="text-center">Redye</th>
                                                        <th class="text-center">Ip Address</th>
                                                        <th class="text-center">Machine New</th>
                                                        <th class="text-center">Color</th>
                                                        <th class="text-center">Date</th>
                                                        <th class="text-center">Import State</th>
                                                        <th class="text-center">Desc If Error Import</th>
                                                        <th class="text-center">State</th>
                                                        <th class="text-center">Desc State</th>
                                                        <th class="text-center">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

                                                    if (isset($_POST['cari'])) {
                                                        $where_tgl  = "CAST(a.QueueTime AS DATE) BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]'";
                                                    } else {
                                                        $where_tgl  = "CAST(a.QueueTime AS DATE) = CAST(GETDATE() AS DATE)";
                                                    }

                                                    // Prepare the SQL query
                                                    $dyelots = $pdo_orgatex->query("SELECT
                                                                                        a.AutoKey,
                                                                                        a.DyelotRefNo,
                                                                                        a.Dyelot,
                                                                                        a.ReDye,
                                                                                        a.Machine,
                                                                                        a.Color,
                                                                                        LEFT(CONVERT(VARCHAR, a.QueueTime, 120), 16) AS QueueTime,
                                                                                        a.ImportState,
                                                                                        a.[State],
                                                                                        b.[code] AS ErrorImportCode,
                                                                                        b.[Desc] AS ImportDesc,
                                                                                        c.[Desc] AS StateDesc,
                                                                                        d.batch_ref_no
                                                                                    FROM
                                                                                        [ORGATEX-INTEG].[dbo].[Dyelots] a
                                                                                    LEFT JOIN [ORGATEX-INTEG].[dbo].[Error_codes_ImportError] b ON b.code = a.ImportError
                                                                                    LEFT JOIN [ORGATEX-INTEG].[dbo].[Status_State] c ON c.code = a.State 
                                                                                    LEFT JOIN [ORGATEX].[dbo].[BatchDetail] d ON d.batch_ref_no = a.DyelotRefNo COLLATE SQL_Latin1_General_CP1_CI_AS
                                                                                    WHERE
	                                                                                    $where_tgl
                                                                                    ORDER BY
                                                                                        a.AutoKey DESC")->fetchAll(PDO::FETCH_ASSOC);
                                                    ?>
                                                    <?php foreach ($dyelots as $dyelot): ?>
                                                        <?php
                                                        if ($dyelot['ImportState'] == 1) {
                                                            $badge = '<span class="badge badge-pill badge-primary p-2">Waiting Checking</span>';
                                                        } else if ($dyelot['ImportState'] == 10) {
                                                            $badge = '<span class="badge badge-pill badge-success p-2">Success Import</span>';
                                                        } else if ($dyelot['ImportState'] == 30) {
                                                            $badge = '<span class="badge badge-pill badge-warning p-2">Waiting Delete</span>';
                                                        } else if ($dyelot['ImportState'] == 40) {
                                                            $badge = '<span class="badge badge-pill badge-danger p-2">Success Delete</span>';
                                                        } else if ($dyelot['ImportState'] == 50) {
                                                            $badge = '<span class="badge badge-pill badge-info p-2">Error Delete</span>';
                                                        } else if ($dyelot['ImportState'] == 20) {
                                                            $badge = '<span class="badge badge-pill badge-dark p-2">Error Import</span>';
                                                        } else {
                                                            $badge = '';
                                                        }


                                                        $currentIP = $_SERVER['REMOTE_ADDR'];
                                                        $allowedIPs = ['10.0.5.132', '10.0.6.247', '10.0.5.36', '10.0.7.75'];

                                                        if (!function_exists('checkIP')) {
                                                            function checkIP($curIP, $allowIP)
                                                            {
                                                                return in_array($curIP, $allowIP);
                                                            }
                                                        }

                                                        $buttons = '';

                                                        if (checkIP($currentIP, $allowedIPs)) {
                                                            $buttons .= '<div class="d-flex text-center">
                                                                            <div class="col">
                                                                                <button class="btn btn-danger" id="update-btn" data-dyelot="' . $dyelot['Dyelot'] . '" data-redye="' . $dyelot['ReDye'] . '" data-importstate="30">Delete Batch</button>
                                                                                <button class="btn btn-warning" id="update-btn" data-dyelot="' . $dyelot['Dyelot'] . '" data-redye="' . $dyelot['ReDye'] . '" data-importstate="40">Hard Delete</button>
                                                                            </div>
                                                                        </div>';
                                                        } else if ($dyelot['ImportState'] == 10 && $dyelot['State'] == 10) {
                                                            $buttons .= '<button class="btn btn-danger" id="update-btn" data-dyelot="' . $dyelot['Dyelot'] . '" data-redye="' . $dyelot['ReDye'] . '" data-importstate="30">Delete Batch</button>';
                                                        }
                                                        ?>

                                                        <?php
                                                            $sqlScheduleDye  = "SELECT * FROM tbl_mesin WHERE no_mesin_lama = '$dyelot[Machine]'";
                                                            $resultScheduleDye = mysqli_query($con_db_dyeing, $sqlScheduleDye);
                                                            $dataSchedule = mysqli_fetch_assoc($resultScheduleDye);
                                                        ?>
                                                        <tr>
                                                            <td class="text-center"><?= $dyelot['AutoKey']; ?></td>
                                                            <td class="text-center"><?= $dyelot['DyelotRefNo']; ?></td>
                                                            <td class="text-center"><?= $dyelot['Dyelot']; ?></td>
                                                            <td class="text-center"><?= $dyelot['ReDye']; ?></td>
                                                            <td class="text-center"><?= $dyelot['Machine']; ?></td>
                                                            <td class="text-center"><?= $dataSchedule['no_mesin']; ?></td>
                                                            <td class="text-center"><?= $dyelot['Color']; ?></td>
                                                            <td class="text-center"><?= $dyelot['QueueTime']; ?></td>
                                                            <td class="text-center"><?= $dyelot['ImportState'] . ' ' . $badge ?></td>
                                                            <td>
                                                                <?php
                                                                    if ($dyelot['ErrorImportCode'] != 0) {
                                                                        if ($dyelot['ErrorImportCode'] == 9709) {
                                                                            if(!empty($dyelot['batch_ref_no'])){
                                                                                echo "<div class='text-wrap width-desc'>
                                                                                    <a href='#' class='three' data-toggle='modal'data-target='#modalDetail'>
                                                                                        " . $dyelot['ErrorImportCode'] . "
                                                                                    </a> - " . $dyelot['ImportDesc'] . " <button class='btn btn-sm btn-outline-danger' data-toggle='modal' data-target='#modalDelete" . $dyelot['DyelotRefNo'] . "'>Delete</button>
                                                                                </div>";

                                                                                echo "
                                                                                    <div class='modal fade' role='dialog' id='modalDelete" . $dyelot['DyelotRefNo'] . "' >
                                                                                        <div class='modal-dialog modal-lg'>
                                                                                            <div class='modal-content'>
                                                                                                <form id='deleteBatchForm' method='POST'>
                                                                                                    <div class='modal-header'>
                                                                                                        <h5 class='modal-title'>Delete Batch Assistant/" . $dyelot['DyelotRefNo'] . "</h5>
                                                                                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                                            <span aria-hidden='true'>&times;</span>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                    <div class='modal-body text-center'>
                                                                                                        <svg aria-hidden='true' height='24' viewBox='0 0 24 24' version='1.1' width='24' class='octicon octicon-repo-locked color-fg-muted mt-2'>
                                                                                                            <path d='M2 2.75A2.75 2.75 0 0 1 4.75 0h14.5a.75.75 0 0 1 .75.75v8a.75.75 0 0 1-1.5 0V1.5H4.75c-.69 0-1.25.56-1.25 1.25v12.651A2.987 2.987 0 0 1 5 15h6.25a.75.75 0 0 1 0 1.5H5A1.5 1.5 0 0 0 3.5 18v1.25c0 .69.56 1.25 1.25 1.25h6a.75.75 0 0 1 0 1.5h-6A2.75 2.75 0 0 1 2 19.25V2.75Z'></path>
                                                                                                            <path d='M15 14.5a3.5 3.5 0 1 1 7 0V16h.25c.966 0 1.75.784 1.75 1.75v4.5A1.75 1.75 0 0 1 22.25 24h-7.5A1.75 1.75 0 0 1 13 22.25v-4.5c0-.966.784-1.75 1.75-1.75H15Zm3.5-2a2 2 0 0 0-2 2V16h4v-1.5a2 2 0 0 0-2-2Z'></path>
                                                                                                        </svg>
                                                                                                        <p class='font-weight-bold mt-2'>DeleteBatchAssistant/" . $dyelot['DyelotRefNo'] . "</p>
                                                                                                    <div class='row'>
                                                                                                            <div class='col-10 mx-auto'>
                                                                                                                <input class='form-control' type='text' name='validasi' required>
                                                                                                            </div>
                                                                                                            <div class='col-10 mx-auto'>
                                                                                                                <input class='form-control' type='hidden' value='" . $dyelot['DyelotRefNo'] . "' name='dyelotRefNo'>
                                                                                                            </div>
                                                                                                            <div class='col-10 mx-auto'>
                                                                                                                <input class='form-control' type='hidden' value='" . $dyelot['Dyelot'] . "' name='dyelot'>
                                                                                                            </div>
                                                                                                            <div class='col-10 mx-auto'>
                                                                                                                <input class='form-control' type='hidden' value='" . $dyelot['ReDye'] . "' name='reDye'>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                    </div>
                                                                                                    <div class='modal-footer'>
                                                                                                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                                                                                        <button class='btn btn-danger' type='submit' name='delete_data'>Delete this data</button>
                                                                                                    </div>
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    ";
                                                                            }else{
                                                                                echo '<span style="background-color:yellow">Data pada Batch List telah <b>berhasil dihapus</b>. Silakan lakukan <b>impor ulang</b>.</span>';
                                                                            }

                                                                        }else{
                                                                            echo "<div class='text-wrap width-desc'>" . $dyelot['ErrorImportCode'] . ' - ' . $dyelot['ImportDesc'] . "</div>";
                                                                        }
                                                                    } else {
                                                                        echo "<div class='text-wrap width-desc'>" . $dyelot['ImportDesc'] . "</div>";
                                                                    }
                                                                ?>
                                                            </td>                                                            
                                                            <td class="text-center"><?= $dyelot['State']; ?></td>
                                                            <td><?= "<div class='text-wrap width-desc2'>" . $dyelot['StateDesc'] . "</div>" ?></td>
                                                            <td class="text-center"><?= $buttons ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal to show machine details -->
    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetail" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="detailsModalLabel">Error Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <p class="font-weight-bold">Langkah Penyelesaian:</p>
                  <p class="font-weight-normal">1. Buka <strong>Batch Assistant</strong> di program Orgatex.</p>
                  <p class="font-weight-normal">2. Cari data berdasarkan <strong>Batch Ref No.</strong></p>
                  <p class="font-weight-normal">3. Pilih data yang sesuai, lalu klik <strong>Delete.</strong></p>
                  <p class="font-weight-normal">4. Lakukan proses <strong>ekspor ulang.</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="files\bower_components\jquery\js\jquery.min.js"></script>
    <script type="text/javascript" src="files\bower_components\jquery-ui\js\jquery-ui.min.js"></script>
    <script type="text/javascript" src="files\bower_components\popper.js\js\popper.min.js"></script>
    <script type="text/javascript" src="files\bower_components\bootstrap\js\bootstrap.min.js"></script>
    <script type="text/javascript" src="files\bower_components\jquery-slimscroll\js\jquery.slimscroll.js"></script>
    <script type="text/javascript" src="files\bower_components\modernizr\js\modernizr.js"></script>
    <script type="text/javascript" src="files\bower_components\modernizr\js\css-scrollbars.js"></script>
    <script src="files\bower_components\datatables.net\js\jquery.dataTables.min.js"></script>
    <script src="files\bower_components\datatables.net-buttons\js\dataTables.buttons.min.js"></script>
    <script src="files\assets\pages\data-table\js\jszip.min.js"></script>
    <script src="files\assets\pages\data-table\js\pdfmake.min.js"></script>
    <script src="files\assets\pages\data-table\js\vfs_fonts.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\dataTables.buttons.min.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\buttons.flash.min.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\jszip.min.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\vfs_fonts.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\buttons.colVis.min.js"></script>
    <script src="files\bower_components\datatables.net-buttons\js\buttons.print.min.js"></script>
    <script src="files\bower_components\datatables.net-buttons\js\buttons.html5.min.js"></script>
    <script src="files\bower_components\datatables.net-bs4\js\dataTables.bootstrap4.min.js"></script>
    <script src="files\bower_components\datatables.net-responsive\js\dataTables.responsive.min.js"></script>
    <script src="files\bower_components\datatables.net-responsive-bs4\js\responsive.bootstrap4.min.js"></script>
    <script type="text/javascript" src="files\bower_components\i18next\js\i18next.min.js"></script>
    <script type="text/javascript" src="files\bower_components\i18next-xhr-backend\js\i18nextXHRBackend.min.js">
    </script>
    <script type="text/javascript"
        src="files\bower_components\i18next-browser-languagedetector\js\i18nextBrowserLanguageDetector.min.js"></script>
    <script type="text/javascript" src="files\bower_components\jquery-i18next\js\jquery-i18next.min.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\extension-btns-custom.js"></script>
    <script src="files\assets\js\pcoded.min.js"></script>
    <script src="files\assets\js\menu\menu-hori-fixed.js"></script>
    <script src="files\assets\js\jquery.mCustomScrollbar.concat.min.js"></script>
    <script type="text/javascript" src="files\assets\js\script.js"></script>
    <script src="alert/toastr.js"></script>
    <script src="alert/sweetalert2.all.min.js"></script>
    <script>
        // Show loading overlay
        function showLoading() {
            $('#loadingOverlay').show();
        }

        // Hide loading overlay
        function hideLoading() {
            $('#loadingOverlay').hide();
        }

        $('#dyelot-table').DataTable({
            lengthMenu: [15, 30, 50, 75, 100],
            pageLength: 15,
            order: [[0, 'desc']]
        });

        // Event delegation for dynamically created buttons
        $('#dyelot-table').on('click', '#update-btn', function() {
            const dyelot = $(this).data('dyelot');
            const redye = $(this).data('redye');
            const importState = $(this).data('importstate');

            // SweetAlert confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete batch with number ${dyelot}  ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    $.ajax({
                        url: 'update_dyelot.php', // Adjust this to your PHP script path
                        type: 'POST',
                        data: {
                            DyelotToDelete: dyelot,
                            RedyeToDelete: redye,
                            ImportState: importState
                        },
                        success: function(response) {
                            hideLoading();
                            if (data.success) {
                                Swal.fire({
                                    title: 'Deleted',
                                    text: 'The batch has been deleted successfully.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Failed',
                                    text: 'The batch failed to delete.',
                                    icon: 'Error',
                                    confirmButtonText: 'OK'
                                })
                            }
                        },
                        error: function() {
                            hideLoading();
                            Swal.fire({
                                title: 'Failed',
                                text: 'The batch failed to delete.',
                                icon: 'Error',
                                confirmButtonText: 'OK'
                            })
                        }
                    });
                }
            });

        });

         // Show loading overlay
        function showLoading() {
        $('#loadingOverlay').show();
        }

        // Hide loading overlay
        function hideLoading() {
        $('#loadingOverlay').hide();
        }

        // Show toast error
        function showToastError(message) {
        toastr.error(message, 'Error', {
            closeButton: true,
            progressBar: true,
        });
        }

        // Show toast success
        function showToastSuccess(message) {
        toastr.success(message, 'Success', {
            closeButton: true,
            progressBar: true,
        });
        }

       $(document).ready(function () {
            $('#deleteBatchForm').on('submit', function (e) {
                e.preventDefault(); // Mencegah form submit secara default
                showLoading(); // Menampilkan loading indicator

                $.ajax({
                    url: 'delete_batch_detail.php', // Target file PHP
                    type: 'POST',
                    data: $(this).serialize(), // Mengirim data dari form
                    dataType: 'json',
                    success: function (response) {
                        hideLoading(); // Sembunyikan loading indicator
                        if (response.success) {
                            showToastSuccess(response.message); // Tampilkan toast sukses
                        } else {
                            showToastError(response.message); // Tampilkan toast error
                        }
                    },
                    error: function () {
                        hideLoading(); // Sembunyikan loading indicator
                        showToastError('Terjadi kesalahan, silakan coba lagi nanti.');
                    }
                });
            });
        });

    </script>
</body>

</html>
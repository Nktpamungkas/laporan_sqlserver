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
                                        <h5>List Dyelot</h5>
                                    </div>
                                    <div class="card-block">
                                        <div class="dt-responsive table-responsive">
                                            <table id="dyelot-table" class="table compact table-striped table-bordered nowrap">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">AutoKey</th>
                                                        <th class="text-center">Dyelot</th>
                                                        <th class="text-center">Redye</th>
                                                        <th class="text-center">Machine</th>
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
                                                    // Prepare the SQL query
                                                    $dyelots = $pdo_orgatex->query("SELECT
                                                    a.AutoKey, 
                                                    a.Dyelot,
                                                    a.ReDye,
                                                    a.Machine,
                                                    a.Color,
                                                    a.QueueTime,
                                                    a.ImportState,
                                                    a.[State],
                                                    b.[Desc] AS ImportDesc,
                                                    c.[Desc] AS StateDesc
                                                    FROM dbo.Dyelots AS a
                                                    LEFT JOIN dbo.Error_codes_ImportError AS b ON b.code = a.ImportError
                                                    LEFT JOIN dbo.Status_State AS c ON c.code = a.State
                                                    ORDER BY a.AutoKey DESC")->fetchAll(PDO::FETCH_ASSOC);
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
                                                        $allowedIPs = ['10.0.5.132', '10.0.6.247', '10.0.5.91'];

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
                                                        <tr>
                                                            <td class="text-center"><?= $dyelot['AutoKey']; ?></td>
                                                            <td class="text-center"><?= $dyelot['Dyelot']; ?></td>
                                                            <td class="text-center"><?= $dyelot['ReDye']; ?></td>
                                                            <td class="text-center"><?= $dyelot['Machine']; ?></td>
                                                            <td class="text-center"><?= $dyelot['Color']; ?></td>
                                                            <td class="text-center"><?= $dyelot['QueueTime']; ?></td>
                                                            <td class="text-center"><?= $dyelot['ImportState'] . ' ' . $badge ?></td>
                                                            <td><?= "<div class='text-wrap width-desc'>" . $dyelot['ImportDesc'] . "</div>" ?></td>
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
    </script>
</body>

</html>
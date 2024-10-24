<?php
ini_set("error_reporting", 1);
session_start();
require_once "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Export to Orgatex</title>
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

    <link rel="stylesheet" type="text/css" href="files\assets\icon\font-awesome\css\font-awesome.min.css">

    <style>
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
                                        <h5>Export to Orgatex</h5>
                                    </div>
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Bon Resep</h6>
                                                <input type="text" id="production_number" value="<?= $_GET['bonresep']; ?>" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Dyelot</h6>
                                                <input type="text" id="dyelot" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Group Line</h6>
                                                <input type="text" id="group_line" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Redye</h6>
                                                <input type="text" id="redye" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Machine Number</h6>
                                                <input type="text" id="machine_number" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Type Of Procedure</h6>
                                                <input type="text" id="procedure_type" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Procedure Number</h6>
                                                <input type="text" id="procedure_number" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Color</h6>
                                                <input type="text" id="color" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Color Desc</h6>
                                                <input type="text" id="warna" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Recipe Number</h6>
                                                <input type="text" id="recipe_number" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Order Number</h6>
                                                <input type="text" id="order_number" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Customer Name</h6>
                                                <input type="text" id="customer_name" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Article</h6>
                                                <input type="text" id="article" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Color Number</h6>
                                                <input type="text" id="color_number" class="form-control" readonly>
                                            </div>

                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Length</h6>
                                                <input type="text" id="length" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">LiquorRatio</h6>
                                                <input type="text" id="liquorRatio" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">LiquorQuantity</h6>
                                                <input type="number" id="liquorQuantity" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">PumpSpeed</h6>
                                                <input type="number" id="pumpSpeed" class="form-control">
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">ReelSpeed</h6>
                                                <input type="number" id="reelSpeed" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Absorption</h6>
                                                <input type="number" id="absorption" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Weight</h6>
                                                <input type="text" id="weight" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-10">
                                                <h6 style="font-weight: bold;">Blower Speed</h6>
                                                <input type="number" id="blower_speed" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card-header">
                                                    <h5>Recipe Preview</h5>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table compact table-striped table-bordered w-100" id="recipe_table">
                                                        <thead>
                                                            <tr>
                                                                <th>Code</th>
                                                                <th>Subcode</th>
                                                                <th>Commentline</th>
                                                                <th>Description</th>
                                                                <th>Consumption</th>
                                                                <th>UoM</th>
                                                                <th>Qty</th>
                                                                <th>UoM</th>
                                                                <th>Group Number</th>
                                                                <th>Callof</th>
                                                                <th>Counter</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Rows will be added here dynamically -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card-header">
                                                    <h5>Treatment Preview</h5>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table compact table-striped table-bordered w-100" id="treatment_table">
                                                        <thead>
                                                            <tr>
                                                                <th>Item</th>
                                                                <th>Treatment Code</th>
                                                                <th>Description</th>
                                                                <th>Check Available Treatment</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Rows will be added here dynamically -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <?php if (empty($_GET['bonresep'])) : ?>
                                        <div class="row">
                                            <div class="col-12 text-right ">
                                                <button type="button" id="submit_button" class="btn btn-primary mx-4">Export</button>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<?php require_once 'footer.php'; ?>
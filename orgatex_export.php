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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />
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
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Bon Resep</h5>
                                                <input type="text" id="production_number" value="<?= $_GET['bonresep']; ?>" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Dyelot</h5>
                                                <input type="text" id="dyelot" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Redye</h5>
                                                <input type="text" id="redye" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Machine Number</h5>
                                                <input type="text" id="machine_number" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Type Of Procedure</h5>
                                                <input type="text" id="procedure_type" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Procedure Number</h5>
                                                <input type="text" id="procedure_number" class="form-control" readonly>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Color</h5>
                                                <input type="text" id="color" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Recipe Number</h5>
                                                <input type="text" id="recipe_number" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Order Number</h5>
                                                <input type="text" id="order_number" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Customer Name</h5>
                                                <input type="text" id="customer_name" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Article</h5>
                                                <input type="text" id="article" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Color Number</h5>
                                                <input type="text" id="color_number" class="form-control" readonly>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Length</h5>
                                                <input type="text" id="length" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">LiquorRatio</h5>
                                                <input type="text" id="liquorRatio" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">LiquorQuantity</h5>
                                                <input type="text" id="liquorQuantity" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">PumpSpeed</h5>
                                                <input type="text" id="pumpSpeed" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">ReelSpeed</h5>
                                                <input type="text" id="reelSpeed" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Absorption</h5>
                                                <input type="text" id="absorption" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Weight</h5>
                                                <input type="text" id="weight" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card-header">
                                                    <h5>Recipe Preview</h5>
                                                </div>
                                                <table class="table table-sm table-bordered" id="recipe_table">
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
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card-header">
                                                    <h5>Treatment Preview</h5>
                                                </div>
                                                <table class="table table-sm table-bordered" id="treatment_table">
                                                    <thead>
                                                        <tr>
                                                            <th>Item</th>
                                                            <th>Treatment Code</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Rows will be added here dynamically -->
                                                    </tbody>
                                                </table>
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
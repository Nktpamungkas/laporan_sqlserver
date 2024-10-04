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
                                        <h5>Export to Orgatex</h5>
                                    </div>
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Production Number / Dyelot</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Redye</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Machine Number</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Type Of Procedure</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Procedure Number</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Color</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Recipe Number</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Order Number</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Customer Name</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Article</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Color Number</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Weight</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Length</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">LiquorRatio</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">LiquorQuantity</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">PumpSpeed</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">ReelSpeed</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Absorption</h5>
                                                <input type="text" name="prod_demand" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-header">
                                        <h5>Preview Recipe</h5>
                                    </div>

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
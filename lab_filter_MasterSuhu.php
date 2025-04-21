<?php
    // ini_set("error_reporting", 1);
    session_start();
    require_once "koneksi.php";
    include_once "utils/helper.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>LAB - Master Suhu</title>
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
    <script src="TabCounter.js"></script>
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
                                        <h5>Filter Data</h5>
                                    </div>
                                    <div class="card-block">
                                        <form action="" method="post">
                                            <div class="row">

                                                <div class="col-sm-12 col-xl-2 m-b-30">
                                                    <h4 class="sub-title">Article Group</h4>
                                                    <input type="text" name="article_group" class="form-control" value="<?php if (isset($_POST['submit'])) {
                                                                                                                            echo $_POST['article_group'];
                                                                                                                        } ?>">
                                                </div>
                                                <div class="col-sm-12 col-xl-2 m-b-30">
                                                    <h4 class="sub-title">Article Code</h4>
                                                    <input type="text" name="article_code" class="form-control" value="<?php if (isset($_POST['submit'])) {
                                                                                                                            echo $_POST['article_code'];
                                                                                                                        } ?>">
                                                </div>
                                                <div class="col-sm-12 col-xl-2 m-b-30">
                                                    <h4 class="sub-title">No Warna</h4>
                                                    <input type="text" name="no_warna" class="form-control" value="<?php if (isset($_POST['submit'])) {
                                                                                                                            echo $_POST['no_warna'];
                                                                                                                        } ?>">
                                                </div>
                                                <div class="col-sm-12 col-xl-12 m-b-30">
                                                    <button type="submit" name="submit" class="btn btn-primary"><i class="icofont icofont-search-alt-1"></i> Cari data</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php if (isset($_POST['submit'])) : ?>
                                    <div class="card">
                                        <div class="card-block">
                                            <div class="table-responsive dt-responsive">
                                                <table id="masterSuhu" class="table table-striped table-bordered nowrap">
                                                    <thead>
                                                        <tr align="center">
                                                            <th>Product Master</th>
                                                            <th>No Warna</th>
                                                            <th>Warna</th>
                                                            <th>Recipe Code</th>
                                                            <th>Temperatur</th>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $article_group  = $_POST['article_group'];
                                                            $article_code   = $_POST['article_code'];
                                                            $no_warna       = $_POST['no_warna'];

                                                            if ($article_group) {
                                                                $conditions[] = "SUBCODE02 = '$article_group'";
                                                            }
                                                            if ($article_code) {
                                                                $conditions[] = "SUBCODE03 = '$article_code'";
                                                            }
                                                            if ($no_warna) {
                                                                $conditions[] = "SUBCODE05 = '$no_warna'";
                                                            }

                                                            $conditionsString = count($conditions) > 0 ? implode(" AND ", $conditions) : "1=1";

                                                            $sqlDB2 = "SELECT * FROM ITXVIEWMASTERSUHU WHERE $conditionsString";
                                                            $stmt   = db2_exec($conn1, $sqlDB2);
                                                            while ($rowdb2 = db2_fetch_assoc($stmt)) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $rowdb2['PRODUCT_MASTER']; ?></td>
                                                            <td><?= $rowdb2['NO_WARNA']; ?></td>
                                                            <td><?= $rowdb2['NAMA_WARNA']; ?></td>
                                                            <td><?= nl2br($rowdb2['RECIPE_CODE']); ?></td>
                                                            <td><?= nl2br($rowdb2['TEMPERATUR']); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php require_once 'footer.php'; ?>
<script>
    $(document).ready(function() {
        $('#masterSuhu').DataTable({
            "language": {
                "decimal": ",",
                "thousands": "."
            },
            dom: 'Bfrtip', // Tambahkan ini untuk menampilkan tombol
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Download Excel',
                    title: 'Data Suhu',
                    exportOptions: {
                        columns: ':visible'
                    },
                    customizeData: function(data) {
                        for (let i = 0; i < data.body.length; i++) {
                            for (let j = 0; j < data.body[i].length; j++) {
                                if (typeof data.body[i][j] === 'string') {
                                    data.body[i][j] = data.body[i][j].replace(/<br\s*\/?>/gi, '\r\n');
                                }
                            }
                        }
                    },
                    customize: function(xlsx) {
                        let sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row c[r]', sheet).attr('s', '55'); // style wrap text
                    }
                }

            ]
        });
    });
</script>
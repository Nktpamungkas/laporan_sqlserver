<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="#">
    <meta name="keywords" content="Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="#">
    <link rel="icon" href="files\assets\images\favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="files\bower_components\bootstrap\css\bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\icon\themify-icons\themify-icons.css">
    <link rel="stylesheet" type="text/css" href="files\assets\icon\icofont\css\icofont.css">
    <link rel="stylesheet" type="text/css" href="files\assets\icon\feather\css\feather.css">
    <link rel="stylesheet" href="files\bower_components\select2\css\select2.min.css">
    <link rel="stylesheet" type="text/css" href="files\bower_components\bootstrap-multiselect\css\bootstrap-multiselect.css">
    <link rel="stylesheet" type="text/css" href="files\bower_components\multiselect\css\multi-select.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\style.css">
    <link rel="stylesheet" type="text/css" href="files\assets\pages\prism\prism.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\style.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\jquery.mCustomScrollbar.css">
    <link rel="stylesheet" type="text/css" href="files\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\pages\data-table\css\buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="files\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\pcoded-horizontal.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\component.css">
    <?php if ($pinjambuku = 'pinjam_buku') : ?>
        <script src="xeditable/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <?php endif; ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
                                        <div id="curve_chart" style="width: 900px; height: 500px"></div>
                                        <?php
                                            require_once "koneksi.php";
                                            $sql = "SELECT TOP 5 IPADDRESS, COUNT(*) AS jumlah_data
                                                    FROM [nowprd].[itxview_memopentingppc]
                                                    WHERE CAST(CREATEDATETIME AS DATE) = CAST(GETDATE() AS DATE)
                                                    GROUP BY IPADDRESS
                                                    HAVING COUNT(*) > 10
                                                    ORDER BY MAX(CREATEDATETIME) DESC;";
                                            
                                            $result = sqlsrv_query($con_nowprd, $sql);
                                            
                                            // Create arrays to hold the data
                                            $ipAddresses = [];
                                            $jumlahData = [];
                                            
                                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                                $ipAddresses[] = $row['IPADDRESS'];
                                                $jumlahData[] = $row['jumlah_data'];
                                            }
                                        ?>
                                        <script type="text/javascript">
                                            var ipAddresses = <?php echo json_encode($ipAddresses); ?>;
                                            var jumlahData = <?php echo json_encode($jumlahData); ?>;

                                            google.charts.load('current', {'packages':['corechart']});
                                            google.charts.setOnLoadCallback(drawChart);

                                            function drawChart() {
                                                // Prepare the data for Google Charts
                                                var data = new google.visualization.DataTable();
                                                data.addColumn('string', 'IP Address');
                                                data.addColumn('number', 'Jumlah Data');

                                                // Populate the DataTable with the PHP arrays
                                                for (var i = 0; i < ipAddresses.length; i++) {
                                                    data.addRow([ipAddresses[i], jumlahData[i]]);
                                                }

                                                var options = {
                                                    title: 'Jumlah Data per IP Address',
                                                    curveType: 'function',
                                                    legend: { position: 'bottom' }
                                                };

                                                var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                                                chart.draw(data, options);
                                            }

                                        </script>
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
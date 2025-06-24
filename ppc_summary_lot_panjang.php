<!DOCTYPE html>
<html lang="en">

<head>
    <title>PPC - Laporan Summary Lot Panjang </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="#">
    <meta name="keywords"
        content="Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
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
    <link rel="stylesheet" type="text/css"
        href="files\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\pages\data-table\css\buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="files\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css">
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
                                        <h5>Filter by</h5>
                                    </div>
                                    <div class="card-block">
                                        <form action="" method="post">
                                            <div class="row">
                                                <div class="col-sm-12 col-xl-2 m-b-30">
                                                    <h4 class="sub-title">Bon Order</h4>
                                                    <input type="text" name="no_order" class="form-control" 
                                                        onkeyup="this.value = this.value.toUpperCase()" value="<?php if (isset($_POST['submit'])) {
                                                            echo $_POST['no_order'];
                                                        } ?>">
                                                </div>
                                                <div class="col-sm-12 col-xl-12 m-b-30">
                                                    <button type="submit" name="submit" class="btn btn-primary"><i class="icofont icofont-search-alt-1"></i> Cari data</button>
                                                    
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php if (isset($_POST['submit'])): ?>
                                    <div class="card">
                                        <div class="card-block">
                                            <div class="dt-responsive table-responsive">
                                                <table id="excel-LA" class="table table-striped table-bordered nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th>NO</th>
                                                            <th>SALES ORDER - LINE</th>
                                                            <th>ITEM</th>
                                                            <th>WARNA</th>
                                                            <th>NETTO</th>
                                                            <th>BRUTO</th>
                                                            <th>SUDAH BAGI KAIN</th>
                                                            <th>BALANCE BELUM BAGI KAIN</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            require_once 'koneksi.php';
                                                            $no_order = isset($_POST['no_order']) ? trim($_POST['no_order']) : '';
                                                            $qMain = "WITH QTY_BRUTO AS (
                                                                        SELECT
                                                                            i.CODE,
                                                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                                                            i.ORIGDLVSALORDERLINEORDERLINE,
                                                                            SUM(i.USERPRIMARYQUANTITY) AS KFF
                                                                        FROM
                                                                            ITXVIEWKGBRUTOBONORDER2 i
                                                                        GROUP BY 
                                                                            i.CODE,
                                                                            i.ORIGDLVSALORDLINESALORDERCODE,
                                                                            i.ORIGDLVSALORDERLINEORDERLINE
                                                                        )
                                                                        SELECT DISTINCT 
                                                                            i.SALESORDERCODE,
                                                                            i.ORDERLINE,
                                                                            TRIM(i.SUBCODE02) || '-' || TRIM(i.SUBCODE03) AS ITEM,
                                                                            i.WARNA,
                                                                            ROUND(i.BASEPRIMARYQUANTITY) AS NETTO,
                                                                            SUM(qb.KFF) AS BRUTO,
                                                                            SUM(p2.USEDUSERPRIMARYQUANTITY) AS SUDAH_BAGI_KAIN,
                                                                            SUM(qb.KFF) - SUM(p2.USEDUSERPRIMARYQUANTITY) AS BALANCE_BELUM_BAGI_KAIN
                                                                        FROM
                                                                            ITXVIEWBONORDER i 
                                                                        LEFT JOIN PRODUCTIONDEMAND p ON p.CODE = i.DEMAND 
                                                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.FIELDNAME = 'OriginalPDCode'
                                                                        LEFT JOIN ITXVIEWCOLOR i2 ON i2.ITEMTYPECODE = i.ITEMTYPEAFICODE 
                                                                                                AND i2.SUBCODE01 = i.SUBCODE01
                                                                                                AND i2.SUBCODE02 = i.SUBCODE02 
                                                                                                AND i2.SUBCODE03 = i.SUBCODE03 
                                                                                                AND i2.SUBCODE04 = i.SUBCODE04 
                                                                                                AND i2.SUBCODE05 = i.SUBCODE05 
                                                                                                AND i2.SUBCODE06 = i.SUBCODE06 
                                                                                                AND i2.SUBCODE07 = i.SUBCODE07 
                                                                                                AND i2.SUBCODE08 = i.SUBCODE08 
                                                                                                AND i2.SUBCODE09 = i.SUBCODE09 
                                                                                                AND i2.SUBCODE10 = i.SUBCODE10
                                                                        LEFT JOIN QTY_BRUTO qb ON qb.ORIGDLVSALORDLINESALORDERCODE = i.SALESORDERCODE AND qb.ORIGDLVSALORDERLINEORDERLINE = i.ORDERLINE AND qb.CODE = p.CODE
                                                                        LEFT JOIN PRODUCTIONRESERVATION p2 ON p2.ORDERCODE = i.DEMAND AND p2.ITEMTYPEAFICODE = 'KGF'
                                                                        WHERE 
                                                                            i.SALESORDERCODE = '$no_order'
                                                                            AND a.VALUESTRING IS NULL
                                                                        GROUP BY
                                                                            i.SALESORDERCODE,
                                                                            i.ORDERLINE,
                                                                            i.SUBCODE02,
                                                                            i.SUBCODE03,
                                                                            i.WARNA,
                                                                            i.BASEPRIMARYQUANTITY
                                                                        ORDER BY 
                                                                            i.ORDERLINE
                                                                        ASC";
                                                            $fetchMain = db2_exec($conn1, $qMain);
                                                            $no = 1;
                                                        ?>
                                                        <?php while ($row = db2_fetch_assoc($fetchMain)) : ?>
                                                            <tr>
                                                                <td><?php echo $no++; ?></td>
                                                                <td><?php echo htmlspecialchars($row['SALESORDERCODE'] . ' - ' . $row['ORDERLINE']); ?></td>
                                                                <td><?php echo htmlspecialchars($row['ITEM']); ?></td>
                                                                <td><?php echo htmlspecialchars($row['WARNA']); ?></td>
                                                                <td><?php echo number_format($row['NETTO'], 2); ?></td>
                                                                <td><?php echo number_format($row['BRUTO'], 2); ?></td>
                                                                <td><?php echo number_format($row['SUDAH_BAGI_KAIN'], 2); ?></td>
                                                                <td><?php echo number_format($row['BALANCE_BELUM_BAGI_KAIN'], 2); ?></td>
                                                            </tr>
                                                        <?php endwhile; ?>
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
<script type="text/javascript" src="files\bower_components\i18next-xhr-backend\js\i18nextXHRBackend.min.js"></script>
<script type="text/javascript"
    src="files\bower_components\i18next-browser-languagedetector\js\i18nextBrowserLanguageDetector.min.js"></script>
<script type="text/javascript" src="files\bower_components\jquery-i18next\js\jquery-i18next.min.js"></script>
<script src="files\assets\pages\data-table\extensions\buttons\js\extension-btns-custom.js"></script>
<script src="files\assets\js\pcoded.min.js"></script>
<script src="files\assets\js\menu\menu-hori-fixed.js"></script>
<script src="files\assets\js\jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript" src="files\assets\js\script.js"></script>

<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-23581568-13');
</script>
<script>
    $('#excel-LA').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excelHtml5',
            customize: function (xlsx) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                $('row c[r^="F"]', sheet).each(function () {
                    if ($('is t', this).text().replace(/[^\d]/g, '') * 1 >= 500000) {
                        $(this).attr('s', '20');
                    }
                });
            }
        }]
    });
</script>
<?php require_once 'footer.php'; ?>
<?php 
    ini_set("error_reporting", 1);
    session_start();
    require_once "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>LAB - laporan Pemakaian Obat Gd. Kimia</title>
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
    <link rel="stylesheet" type="text/css" href="files\assets\pages\data-table\extensions\buttons\css\buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\jquery.mCustomScrollbar.css">    
    <!-- <script type="text/javascript" src="files\bower_components\jquery\js\jquery-3.6.0.min.js"></script> 
    <script type="text/javascript" src="files\bower_components\jquery\js\jquery-3.6.0.js"></script>  -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
                                                <div class="col-sm-12 col-xl-2 m-b-0">
                                                    <h4 class="sub-title">Tanggal Awal</h4>
                                                    <div class="input-group input-group-sm">
                                                        <input type="date" class="form-control" required placeholder="input-group-sm" name="tgl" value="<?php if (isset($_POST['submit'])){ echo $_POST['tgl']; } ?>" required >
                                                        <input name="time" type="text" class="form-control" id="time" placeholder="00:00" pattern="[0-9]{2}:[0-9]{2}$" title=" e.g 14:25" onkeyup="
																				var time = this.value;
																				if (time.match(/^\d{2}$/) !== null) {
																					this.value = time + ':';
																				} else if (time.match(/^\d{2}\:\d{2}$/) !== null) {
																					this.value = time + '';
																				}" value="<?php if (isset($_POST['submit'])){ echo $_POST['time']; } ?>" size="5" maxlength="5" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-xl-2 m-b-0">
                                                    <h4 class="sub-title">Tanggal Akhir</h4>
                                                    <div class="input-group input-group-sm">
                                                        <input type="date" class="form-control" required placeholder="input-group-sm" name="tgl2" value="<?php if (isset($_POST['submit'])){ echo $_POST['tgl2']; } ?>" required >
                                                        <input name="time2" type="text" class="form-control" id="time2" placeholder="00:00" pattern="[0-9]{2}:[0-9]{2}$" title=" e.g 14:25" onkeyup="
																				var time = this.value;
																				if (time.match(/^\d{2}$/) !== null) {
																					this.value = time + ':';
																				} else if (time.match(/^\d{2}\:\d{2}$/) !== null) {
																					this.value = time + '';
																				}" value="<?php if (isset($_POST['submit'])){ echo $_POST['time2']; } ?>" size="5" maxlength="5" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-xl-2 m-b-0">
                                                    <h4 class="sub-title">LOGICAL WAREHOUSE</h4>
                                                    <div class="input-group input-group-sm">
                                                        <select name="warehouse" class="form-control" style="width: 100%;" required>
                                                            <option value="M510 dan M101">M510 & M101</option>
                                                            <?php 
                                                                $sqlDB  =   "SELECT  
                                                                                TRIM(CODE) AS CODE,
                                                                                LONGDESCRIPTION 
                                                                            FROM
                                                                                LOGICALWAREHOUSE
                                                                            WHERE 
                                                                                CODE NOT IN ('M510', 'M101')
                                                                            ORDER BY 
                                                                                CODE ASC";
                                                                $stmt   =   db2_exec($conn1, $sqlDB);
                                                                while ($rowdb = db2_fetch_assoc($stmt)) {
                                                            ?>
                                                            <option value="<?= $rowdb['CODE']; ?>" <?php if($rowdb['CODE'] == $_POST['warehouse']){ echo "SELECTED"; } ?>>
                                                                <?= $rowdb['CODE']; ?> <?= $rowdb['LONGDESCRIPTION']; ?>
                                                            </option>
                                                            <?php } ?> 
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-xl-2">
                                                <h4 class="sub-title">&nbsp;</h4>
                                                    <button type="submit" name="submit" class="btn btn-primary btn-sm"><i class="icofont icofont-search-alt-1"></i> Cari data</button>
                                                        <?php if (isset($_POST['submit'])) { ?>
                                                            <!-- <a href="print_laporan pemakaian_obat2.php" class="btn btn-info btn-sm"><i class="icofont icofont-print"></i>Download Test</a> -->
                                                            <!-- <button id = "downloadBtn" class="btn btn-success">Export Table Data To Excel File</button> -->
                                                        <?php } ?>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php if (isset($_POST['submit'])) : ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header table-card-header">
                                                    <h5>LAPORAN BULANAN PEMAKAIAN OBAT GUDANG KIMIA</h5>
                                                </div>
                                                <div class="card-block">
                                                    <div class="dt-responsive table-responsive">
                                                        <table id="summary-table" name = "summary-table" class="table compact table-striped table-bordered nowrap">
                                                            <thead>                                                           
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>                                                    
                                                    </div>
                                                </div>
                                                <div class="card-block" hidden>
                                                    <div class="dt-responsive table-responsive">
                                                        <table id="basic-btn" class="table compact table-striped table-bordered nowrap" >
                                                            <thead>
                                                                <tr>
                                                                    <!-- <th>No</th> -->
                                                                    <th>No. Group Line</th>
                                                                    <th>Tanggal & Jam</th>
                                                                    <th>Kode Obat</th>
                                                                    <th>QTY TARGET</th>
                                                                    <th>QTY Actual</th>
                                                                    <th>SATUAN</th>
                                                                    <th>KETERANGAN</th>
                                                                    <th>NAMA OBAT</th>
                                                                    <th>QTY AWAL</th>
                                                                    <th>QTY MASUK</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    if($_POST['time'] && $_POST['time2']){
                                                                        $where_time     = "AND s.TRANSACTIONTIME BETWEEN '$_POST[time]' AND '$_POST[time2]'";
                                                                    }else{
                                                                        $where_time     = "";
                                                                    }
                                                                    if ($_POST['warehouse'] == 'M510 dan M101') {
                                                                        $where_warehouse = "AND s.LOGICALWAREHOUSECODE IN ('M510', 'M101')";
                                                                        $where_warehouse2 = "AND LOGICALWAREHOUSECODE IN ('M510', 'M101')";
                                                                    } else {
                                                                        $where_warehouse = "AND s.LOGICALWAREHOUSECODE = '$_POST[warehouse]'";
                                                                        $where_warehouse2 = "AND LOGICALWAREHOUSECODE = '$_POST[warehouse]'";
                                                                    }                                                                 
                                                                    $db_stocktransaction   = db2_exec($conn1, "SELECT 
                                                                                                                * 
                                                                                                            FROM 
                                                                                                            (SELECT
                                                                                                                s.TRANSACTIONDATE || ' ' || s.TRANSACTIONTIME AS TGL,
                                                                                                                TIMESTAMP(s.TRANSACTIONDATE, s.TRANSACTIONTIME) AS TGL_WAKTU,
                                                                                                                CASE
                                                                                                                    WHEN s.PRODUCTIONORDERCODE IS NULL THEN COALESCE(s.ORDERCODE, s.LOTCODE)
                                                                                                                    WHEN s.PRODUCTIONORDERCODE IS NULL AND s.LOGICALWAREHOUSECODE = 'M101' THEN COALESCE(s.LOTCODE,s.ORDERCODE)
                                                                                                                    ELSE s.PRODUCTIONORDERCODE
                                                                                                                END AS PRODUCTIONORDERCODE,
                                                                                                                s.ORDERLINE,
                                                                                                                s.DECOSUBCODE01,
                                                                                                                s.DECOSUBCODE02,
                                                                                                                s.DECOSUBCODE03,
                                                                                                                CASE
                                                                                                                    WHEN s.TEMPLATECODE = '120' THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)
                                                                                                                    WHEN s.TEMPLATECODE = '303' THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)
                                                                                                                    WHEN s.TEMPLATECODE = '304' THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)
                                                                                                                    WHEN s.TEMPLATECODE = '203' THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)
                                                                                                                    WHEN s.TEMPLATECODE = '201' THEN TRIM(s.DECOSUBCODE01) || '-' || TRIM(s.DECOSUBCODE02) || '-' || TRIM(s.DECOSUBCODE03)
                                                                                                                    ELSE s.TEMPLATECODE
                                                                                                                END AS KODE_OBAT,
                                                                                                                s.USERPRIMARYQUANTITY AS AKTUAL_QTY,
                                                                                                                s.USERPRIMARYUOMCODE AS SATUAN,
                                                                                                                p.LONGDESCRIPTION,
                                                                                                                s.TEMPLATECODE,
                                                                                                                CASE
                                                                                                                    WHEN s.TEMPLATECODE = '303' THEN l2.LONGDESCRIPTION
                                                                                                                    WHEN s.TEMPLATECODE = '203' THEN l.LONGDESCRIPTION
                                                                                                                    WHEN s.TEMPLATECODE = '201' THEN l.LONGDESCRIPTION
                                                                                                                    ELSE NULL
                                                                                                                END AS KETERANGAN,
                                                                                                                s3.QTY_MASUK,
                                                                                                                s3.USERPRIMARYUOMCODE AS SATUAN_QTY_MASUK
                                                                                                            FROM
                                                                                                                STOCKTRANSACTION s
                                                                                                            LEFT JOIN PRODUCT p ON p.ITEMTYPECODE = s.ITEMTYPECODE
                                                                                                                AND p.SUBCODE01 = s.DECOSUBCODE01
                                                                                                                AND p.SUBCODE02 = s.DECOSUBCODE02
                                                                                                                AND p.SUBCODE03 = s.DECOSUBCODE03
                                                                                                            LEFT JOIN INTERNALDOCUMENT i ON i.PROVISIONALCODE = s.ORDERCODE
                                                                                                            LEFT JOIN ORDERPARTNER o ON o.CUSTOMERSUPPLIERCODE = i.ORDPRNCUSTOMERSUPPLIERCODE
                                                                                                            LEFT JOIN LOGICALWAREHOUSE l ON l.CODE = o.CUSTOMERSUPPLIERCODE
                                                                                                            LEFT JOIN STOCKTRANSACTION s2 ON s2.TRANSACTIONNUMBER = s.TRANSACTIONNUMBER AND s2.DETAILTYPE = 2
                                                                                                            LEFT JOIN ( SELECT 
                                                                                                            ITEMTYPECODE,
                                                                                                            DETAILTYPE,
                                                                                                            DECOSUBCODE01 ,
                                                                                                            DECOSUBCODE02 ,
                                                                                                            DECOSUBCODE03 ,
                                                                                                            IFNULL(SUM(USERPRIMARYQUANTITY), 0) AS QTY_MASUK,
                                                                                                            USERPRIMARYUOMCODE
                                                                                                            from
                                                                                                            (SELECT 
                                                                                                            s.ITEMTYPECODE,
                                                                                                            s.DETAILTYPE,
                                                                                                            s.DECOSUBCODE01 ,
                                                                                                            s.DECOSUBCODE02 ,
                                                                                                            s.DECOSUBCODE03 ,
                                                                                                            CASE 
                                                                                                                WHEN USERPRIMARYUOMCODE = 't' THEN  SUM(s.USERPRIMARYQUANTITY)*1000
                                                                                                                ELSE   SUM(s.USERPRIMARYQUANTITY)
                                                                                                            END AS  USERPRIMARYQUANTITY,
                                                                                                            CASE 
                                                                                                                WHEN USERPRIMARYUOMCODE = 't' THEN 'kg'
                                                                                                                ELSE USERPRIMARYUOMCODE
                                                                                                            END AS USERPRIMARYUOMCODE    
                                                                                                            FROM 
                                                                                                            STOCKTRANSACTION s 
                                                                                                            WHERE
                                                                                                            s.ITEMTYPECODE ='DYC'   
                                                                                                            $where_warehouse 
                                                                                                            AND s. TEMPLATECODE in('QCT','OPN')
                                                                                                            AND s.TRANSACTIONDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]'
                                                                                                            GROUP BY 
                                                                                                            s.ITEMTYPECODE,
                                                                                                            s.DECOSUBCODE01,
                                                                                                            s.DECOSUBCODE02,
                                                                                                            s.DECOSUBCODE03,
                                                                                                            s.USERPRIMARYUOMCODE,
                                                                                                            s.DETAILTYPE)
                                                                                                            GROUP BY ITEMTYPECODE,
                                                                                                            DETAILTYPE,
                                                                                                            DECOSUBCODE01 ,
                                                                                                            DECOSUBCODE02 ,
                                                                                                            DECOSUBCODE03,
                                                                                                            USERPRIMARYUOMCODE) s3 ON s3.ITEMTYPECODE = s.ITEMTYPECODE
                                                                                                            AND s3.DECOSUBCODE01 = s.DECOSUBCODE01
                                                                                                            AND s3.DECOSUBCODE02 = s.DECOSUBCODE02
                                                                                                            AND s3.DECOSUBCODE03 = s.DECOSUBCODE03
                                                                                                            LEFT JOIN LOGICALWAREHOUSE l2 ON l2.CODE = s2.LOGICALWAREHOUSECODE
                                                                                                        WHERE  
                                                                                                            s.ITEMTYPECODE = 'DYC'
                                                                                                            AND s.TRANSACTIONDATE BETWEEN '$_POST[tgl]' AND '$_POST[tgl2]'
                                                                                                            AND NOT s.TEMPLATECODE = '313'
                                                                                                            AND (s.DETAILTYPE = 1 OR s.DETAILTYPE = 0)
                                                                                                            $where_warehouse
                                                                                                        ORDER BY
                                                                                                            s.PRODUCTIONORDERCODE ASC)
                                                                                                        WHERE
                                                                                                            TGL_WAKTU BETWEEN '$_POST[tgl] $_POST[time]:00' AND '$_POST[tgl2] $_POST[time2]:00'");
                                                                    $no = 1;
                                                                    while ($row_stocktransaction = db2_fetch_assoc($db_stocktransaction)) {
                                                                        $db_reservation     = db2_exec($conn1, "SELECT 
                                                                                                                    TRIM(p.PRODUCTIONORDERCODE) || '-' || TRIM(p.GROUPSTEPNUMBER) AS NO_RESEP,
                                                                                                                    p.GROUPSTEPNUMBER,
                                                                                                                    SUM(p.USERPRIMARYQUANTITY) AS USERPRIMARYQUANTITY,
                                                                                                                    CASE
                                                                                                                        WHEN p2.CODE LIKE '%T1%' OR p2.CODE LIKE '%T2%' OR p2.CODE LIKE '%T3%' OR p2.CODE LIKE '%T4%' OR p2.CODE LIKE '%T5%' OR p2.CODE LIKE '%T6%' OR p2.CODE LIKE '%T7%' THEN 'Tambah Obat'
                                                                                                                        WHEN p2.CODE LIKE '%R1%' OR p2.CODE LIKE '%R2%' OR p2.CODE LIKE '%R3%' OR p2.CODE LIKE '%R4%' OR p2.CODE LIKE '%R5%' OR p2.CODE LIKE '%R6%' OR p2.CODE LIKE '%R7%' THEN 'Perbaikan'
                                                                                                                        -- ELSE 'Normal'
                                                                                                                        -- ELSE p.PRODRESERVATIONLINKGROUPCODE
                                                                                                                        ELSE 
                                                                                                                            CASE
                                                                                                                	        	WHEN p.PRODRESERVATIONLINKGROUPCODE IS NULL THEN COALESCE(p3.OPERATIONCODE, p.PRODRESERVATIONLINKGROUPCODE)
                                                                                                                	        	ELSE p.PRODRESERVATIONLINKGROUPCODE
                                                                                                                	        END
                                                                                                                    END AS KETERANGAN
                                                                                                                FROM
                                                                                                                    PRODUCTIONRESERVATION p
                                                                                                                LEFT JOIN PRODRESERVATIONLINKGROUP p2 ON p2.CODE = p.PRODRESERVATIONLINKGROUPCODE 
                                                                                                                LEFT JOIN PRODUCTIONDEMANDSTEP p3 ON p3.STEPNUMBER = p.GROUPSTEPNUMBER AND p3.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                                                                                                                WHERE 
                                                                                                                    p.PRODUCTIONORDERCODE = '$row_stocktransaction[PRODUCTIONORDERCODE]' 
                                                                                                                    AND GROUPLINE = '$row_stocktransaction[ORDERLINE]'
                                                                                                                    -- AND p.SUBCODE01 = '$row_stocktransaction[DECOSUBCODE01]' 
                                                                                                                    -- AND p.SUBCODE02 = '$row_stocktransaction[DECOSUBCODE02]' 
                                                                                                                    -- AND p.SUBCODE03 = '$row_stocktransaction[DECOSUBCODE03]'
                                                                                                                GROUP BY
                                                                                                                    p.PRODUCTIONORDERCODE,
                                                                                                                    p.GROUPSTEPNUMBER,
                                                                                                                    p2.CODE,
                                                                                                                    p3.OPERATIONCODE,
                                                                                                                    p.PRODRESERVATIONLINKGROUPCODE");
                                                                        $row_reservation    = db2_fetch_assoc($db_reservation);
                                                                        
                                                                        $db_balance     = db2_exec($conn1, "SELECT 
                                                                                                                ITEMTYPECODE,
                                                                                                                DECOSUBCODE01 ,
                                                                                                                DECOSUBCODE02 ,
                                                                                                                DECOSUBCODE03 ,
                                                                                                                SUM(BASEPRIMARYQUANTITYUNIT) AS QTY_AWAL
                                                                                                                FROM BALANCE b  
                                                                                                                WHERE
                                                                                                                ITEMTYPECODE = 'DYC'
                                                                                                                AND DECOSUBCODE01 = '$row_stocktransaction[DECOSUBCODE01]'
                                                                                                                AND DECOSUBCODE02 = '$row_stocktransaction[DECOSUBCODE02]' 
                                                                                                                AND DECOSUBCODE03 = '$row_stocktransaction[DECOSUBCODE03]'
                                                                                                                $where_warehouse2
                                                                                                                GROUP BY 
                                                                                                                ITEMTYPECODE,
                                                                                                                DECOSUBCODE01,
                                                                                                                DECOSUBCODE02,
                                                                                                                DECOSUBCODE03 ");
                                                                        $row_balance    = db2_fetch_assoc($db_balance);
                                                                    ?>
                                                                <tr>
                                                                    <!-- <td><?= $no++; ?></td> -->
                                                                    <td><?php if($row_reservation['NO_RESEP']){ echo $row_reservation['NO_RESEP']; } else { echo $row_stocktransaction['PRODUCTIONORDERCODE']; } ?></td>
                                                                    <td><?= $row_stocktransaction['TGL']; ?></td>
                                                                    <td><?= $row_stocktransaction['KODE_OBAT']; ?></td>
                                                                    <td><?= number_format($row_reservation['USERPRIMARYQUANTITY'] ?? 0, 2); ?></td>
                                                                    <td>
                                                                        <?php if(substr(number_format($row_stocktransaction['AKTUAL_QTY'], 2), -3) == '.00') : ?>
                                                                            <?= number_format($row_stocktransaction['AKTUAL_QTY'], 0); ?>
                                                                        <?php else : ?>
                                                                            <?= number_format($row_stocktransaction['AKTUAL_QTY'], 2); ?>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td><?= $row_stocktransaction['SATUAN']; ?></td>
                                                                    <td>
                                                                        <?php if($row_stocktransaction['TEMPLATECODE'] == '303' OR $row_stocktransaction['TEMPLATECODE'] == '203' OR $row_stocktransaction['TEMPLATECODE'] == '201') : ?>
                                                                            <?= $row_stocktransaction['KETERANGAN']; ?>
                                                                        <?php else : ?>
                                                                            <?= $row_reservation['KETERANGAN']; ?>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td><?= $row_stocktransaction['LONGDESCRIPTION']; ?></td>
                                                                    <td><?= $row_balance['QTY_AWAL'] ?></td>
                                                                    <td><?= $row_stocktransaction['QTY_MASUK'] ?></td>
                                                                </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
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
    <script>
        $(document).ready(function() {
            var summaryData = {};

            // Loop melalui setiap baris dalam tabel
            $('#basic-btn tbody tr').each(function() {
                // Ambil data dari kolom yang diperlukan
                var kodeObat = $(this).find('td:nth-child(3)').text().trim();
                var qtyAktualStr = $(this).find('td:nth-child(5)').text().trim().replace(',', '');
                var qtyAktual = parseFloat(qtyAktualStr);
                var keterangan = $(this).find('td:nth-child(7)').text().trim();
                var namaObat = $(this).find('td:nth-child(8)').text().trim();
                var satuan = $(this).find('td:nth-child(6)').text().trim();
                var destinationWarehouseCode = $(this).find('td:nth-child(9)').text().trim();
                var SafetyStokStr = $(this).find('td:nth-child(10)').text().trim();
                var BukaPostr = $(this).find('td:nth-child(11)').text().trim().replace(',', '');
                var BukaPo = parseFloat(BukaPostr);
                var StockAwalstr = $(this).find('td:nth-child(9)').text().trim().replace(',', '');
                var TStockAwal = parseFloat(StockAwalstr);
                var StocMasukstr = $(this).find('td:nth-child(10)').text().trim().replace(',', '');
                var TStockMasuk = parseFloat(StocMasukstr) ?? 0;
                if (satuan.toLowerCase() === 'kg') {
                    qtyAktual *= 1000;
                }
                 if (satuan.toLowerCase() === 'kg') {
                    TStockMasuk *= 1000;
                }
                  if (satuan.toLowerCase() === 'kg') {
                    TStockAwal *= 1000;
                }
                var StockAwal = TStockAwal;
                StockAwal = parseFloat(StockAwal.toFixed(2));
                var StockMasuk = TStockMasuk;
                var StockMasuk = parseFloat((TStockMasuk || 0).toFixed(2));
                qtyAktual = parseFloat(qtyAktual.toFixed(2));
                var SafetyStok = parseFloat(SafetyStokStr);
                SafetyStok = parseFloat(SafetyStok.toFixed(2));

                if (!summaryData[kodeObat]) {
                    summaryData[kodeObat] = {
                        'Kode Obat': kodeObat,
                        'Nama Obat': namaObat,
                        'Stok Aman': 0,
                        'Buka PO': 0,
                        'Stock Awal': StockAwal,
                        'Masuk': StockMasuk,
                        'Normal': 0,
                        'Tambah Obat': 0,
                        'Perbaikan': 0,
                        'finishing': 0,
                        'printing': 0,
                        'dyeing': 0,
                        'Total Pemakaian': 0,
                        'Sisa Stok': 0,
                        'Selisih': 0,
                        'Sisa PO': 0,
                        'Stok Catatan GK': '-',
                        'Status': '-'
                    };
                }

                if (keterangan.includes('Tambah Obat')) {
                    summaryData[kodeObat]['Tambah Obat'] += qtyAktual;
                    summaryData[kodeObat]['Tambah Obat'] = parseFloat(summaryData[kodeObat]['Tambah Obat'].toFixed(2));
                } else if (keterangan.includes('Perbaikan')) {
                    summaryData[kodeObat]['Perbaikan'] += qtyAktual;
                    summaryData[kodeObat]['Perbaikan'] = parseFloat(summaryData[kodeObat]['Perbaikan'].toFixed(2));
                } else if (keterangan.includes('finishing')) {
                    summaryData[kodeObat]['finishing'] += qtyAktual;
                    summaryData[kodeObat]['finishing'] = parseFloat(summaryData[kodeObat]['finishing'].toFixed(2));
                } else if (keterangan.includes('printing') && destinationWarehouseCode === 'M516') {
                    summaryData[kodeObat]['printing'] += qtyAktual;
                    summaryData[kodeObat]['printing'] = parseFloat(summaryData[kodeObat]['printing'].toFixed(2));
                } else if (keterangan.includes('dyeing') && destinationWarehouseCode === 'P101') {
                    summaryData[kodeObat]['dyeing'] += qtyAktual;
                    summaryData[kodeObat]['dyeing'] = parseFloat(summaryData[kodeObat]['dyeing'].toFixed(2));
                } else {
                    summaryData[kodeObat]['Normal'] += qtyAktual;
                    summaryData[kodeObat]['Normal'] = parseFloat(summaryData[kodeObat]['Normal'].toFixed(2));
                }
            });

            for (var key in summaryData) {
                var totalPemakaian = summaryData[key]['Tambah Obat'] + summaryData[key]['Perbaikan'] + summaryData[key]['Normal'] + summaryData[key]['finishing'] + summaryData[key]['printing'] + summaryData[key]['dyeing'];
                totalPemakaian = parseFloat(totalPemakaian.toFixed(2));

                summaryData[key]['Total Pemakaian'] = totalPemakaian;
                 var sisa_stock = (summaryData[key]['Stock Awal'] + summaryData[key]['Masuk'])-(summaryData[key]['Tambah Obat'] + summaryData[key]['Perbaikan'] + summaryData[key]['Normal'] + summaryData[key]['finishing'] + summaryData[key]['printing'] + summaryData[key]['dyeing']);
            sisa_stock = parseFloat(sisa_stock.toFixed(2));

            summaryData[key]['Sisa Stok'] = sisa_stock;
            }

            var summaryArray = Object.values(summaryData);

            var tableHTML = '<table id="summary-table" class="table compact table-striped table-bordered nowrap"><thead><tr><th rowspan="2" style="text-align: center;">KODE OBAT ERP NOW</th><th rowspan="2" style="text-align: center;">NAMA DAN JENIS BAHAN KIMIA/DYESTUFF</th><th rowspan="2" style="text-align: center;">STOCK AWAL  (Gr)</th><th rowspan="2" style="text-align: center;">MASUK  (Gr)</th><th colspan="6" style="text-align: center;">PEMAKAIAN</th><th rowspan="2" style="text-align: center;">TOTAL PEMAKAIAN (Gr)</th><th rowspan="2" style="text-align: center;">SISA STOK (Gr)</th><th rowspan="2" style="text-align: center;">STOK AMAN</th><th rowspan="2" style="text-align: center;">STATUS</th><th rowspan="2" style="text-align: center;">BUKA PO</th><th rowspan="2" style="text-align: center;">SISA PO</th><th rowspan="2" style="text-align: center;">STOK CATATAN GK</th><th rowspan="2" style="text-align: center;">SELISIH</th></tr><tr><th style="text-align: center;">Normal</th><th style="text-align: center;">Tambah Obat</th><th style="text-align: center;">Perbaikan (Gr)</th><th style="text-align: center;">Finishing</th><th style="text-align: center;">Printing</th><th style="text-align: center;">Yarn Dye</th></tr></thead><tbody>';

            summaryArray.forEach(function(entry) {
                tableHTML += '<tr><td>' + entry['Kode Obat'] + '</td><td>' + entry['Nama Obat'] + '</td><td>' + entry['Stock Awal'] + '</td><td>' + entry['Masuk'] + '</td><td>' + entry['Normal'] + '</td><td>' + entry['Tambah Obat'] + '</td><td>' + entry['Perbaikan'] + '</td><td>' + entry['finishing'] + '</td><td>' + entry['printing'] + '</td><td>' + entry['dyeing'] + '</td><td>' + entry['Total Pemakaian'] + '</td><td>' + entry['Sisa Stok'] + '</td><td>' + entry['Stok Aman'] + '</td><td>' + entry['Status'] + '</td><td>' + entry['Buka PO'] + '</td><td>' + entry['Sisa PO'] + '</td><td>' + entry['Stok Catatan GK'] + '</td><td>' + entry['Selisih'] + '</td></tr>';
            });

            tableHTML += '</tbody></table>';

            $('#summary-table').html(tableHTML);

            // Inisialisasi DataTable setelah tabel baru dihasilkan
            $('#summary-table').DataTable();

            var downloadButton = $('<button class="btn btn-primary btn-sm"><i class="icofont-download">Download Summary Data</i></button>');
            downloadButton.on('click', function() {
                var csvContent = "data:text/csv;charset=utf-8,";
                csvContent += "KODE OBAT ERP NOW,NAMA DAN JENIS BAHAN KIMIA/DYESTUFF,Stock Awal (Gr),Masuk (Gr),Normal,Tambah Obat,Perbaikan (Gr),Finishing,Printing,Yarn Dye,Total Pemakaian (Gr),Sisa Stok (Gr),STOK AMAN,STATUS,BUKA PO,SISA PO,STOK CATATAN GK,SELISIH\n";

                summaryArray.forEach(function(entry) {
                    csvContent += entry['Kode Obat'] + "," + entry['Nama Obat'] + "," + entry['Stock Awal'] + "," + entry['Masuk'] + "," + entry['Normal'] + "," + entry['Tambah Obat'] + "," + entry['Perbaikan'] + "," + entry['finishing'] + ","+ entry['printing'] + ","+ entry['dyeing'] + ","+ entry['Total Pemakaian'] + "," + entry['Sisa Stok'] +"," + entry['Stok Aman'] +"," + entry['Status'] + "," + entry['Buka PO'] +"\n";
                });

                var encodedUri = encodeURI(csvContent);
                var link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", "DYE-Laporan Pemakaian Obat.csv");
                document.body.appendChild(link);
                link.click();
            });

            $('#summary-table').before(downloadButton);
        });
    </script>
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
    <script type="text/javascript" src="files\bower_components\i18next-browser-languagedetector\js\i18nextBrowserLanguageDetector.min.js"></script>
    <script type="text/javascript" src="files\bower_components\jquery-i18next\js\jquery-i18next.min.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\extension-btns-custom.js"></script>
    <script src="files\assets\js\pcoded.min.js"></script>
    <script src="files\assets\js\menu\menu-hori-fixed.js"></script>
    <script src="files\assets\js\jquery.mCustomScrollbar.concat.min.js"></script>
    <script type="text/javascript" src="files\assets\js\script.js"></script>
    <!-- <script>
$(document).ready(function() {
    // Inisialisasi DataTables
    $('#tbl_sum').DataTable();
});
</script> -->
<script>
let table = new DataTable('#tbl_sum') ;
</script>
</body>
</html>
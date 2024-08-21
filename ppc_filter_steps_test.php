<?php 
    ini_set("error_reporting", 0);
    session_start();
    require_once "koneksi_test.php";
    mysqli_query($con_nowprd, "DELETE FROM itxview_posisikk_tgl_in_prodorder_ins3 WHERE CREATEDATETIME BETWEEN NOW() - INTERVAL 3 DAY AND NOW() - INTERVAL 1 DAY");
    mysqli_query($con_nowprd, "DELETE FROM itxview_posisikk_tgl_in_prodorder_ins3 WHERE IPADDRESS = '$_SERVER[REMOTE_ADDR]'"); 
    mysqli_query($con_nowprd, "DELETE FROM itxview_posisikk_tgl_in_prodorder_cnp1 WHERE CREATEDATETIME BETWEEN NOW() - INTERVAL 3 DAY AND NOW() - INTERVAL 1 DAY");
    mysqli_query($con_nowprd, "DELETE FROM itxview_posisikk_tgl_in_prodorder_cnp1 WHERE IPADDRESS = '$_SERVER[REMOTE_ADDR]'"); 
    if($_GET['demand']){
        $demand     = $_GET['demand'];
    }else{
        $demand     = $_POST['demand'];
    }

    $q_ITXVIEWKK    = db2_exec($conn1, "SELECT * FROM ITXVIEWKK WHERE PRODUCTIONDEMANDCODE = '$demand'");
    $d_ITXVIEWKK    = db2_fetch_assoc($q_ITXVIEWKK);
    
    if($_GET['prod_order']){
        $prod_order     = $_GET['prod_order'];
    }elseif($_POST['prod_order']) {
        $prod_order     = $_POST['prod_order'];
    }else{
        $prod_order     = $d_ITXVIEWKK['PRODUCTIONORDERCODE'];
    }

    if(isset($_POST['simpanin_catch'])){
        $productionorder    = $_POST['productionorder'];
        $productiondemand   = $_POST['productiondemand'];
        $stepnumber         = $_POST['stepnumber'];
        $tanggal_proses_in  = $_POST['tanggal_proses_in'];
        $operation          = $_POST['operation'];
        $longdescription    = $_POST['longdescription'];
        $status             = $_POST['status'];
        $ipaddress          = $_POST['ipaddress'];
        $createdatetime     = $_POST['createdatetime'];

        $simpan_cache_in    = mysqli_query($con_nowprd, "INSERT INTO posisikk_cache_in(productionorder,
                                                            productiondemand,
                                                            stepnumber,
                                                            tanggal_in,
                                                            operation,
                                                            longdescription,
                                                            `status`,
                                                            ipaddress,
                                                            createdatetime)
                                        VALUES('$productionorder',
                                                '$productiondemand',
                                                '$stepnumber',
                                                '$tanggal_proses_in',
                                                '$operation',
                                                '$longdescription',
                                                '$status',
                                                '$ipaddress',
                                                '$createdatetime')");
        if($simpan_cache_in){
            header('Location: https://online.indotaichen.com/laporan/ppc_filter_steps.php?demand='.TRIM($productiondemand).'&prod_order='.TRIM($productionorder).'');
            exit;
        }else{
            echo("Error description: " . mysqli_error($simpan_cache_in));
        }
    }elseif (isset($_POST['simpanout_catch'])) {
        $productionorder    = $_POST['productionorder'];
        $productiondemand   = $_POST['productiondemand'];
        $stepnumber         = $_POST['stepnumber'];
        $tanggal_proses_out = $_POST['tanggal_proses_out'];
        $operation          = $_POST['operation'];
        $longdescription    = $_POST['longdescription'];
        $status             = $_POST['status'];
        $ipaddress          = $_POST['ipaddress'];
        $createdatetime     = $_POST['createdatetime'];

        $simpan_cache_out   = mysqli_query($con_nowprd, "INSERT INTO posisikk_cache_out(productionorder,
                                                            productiondemand,
                                                            stepnumber,
                                                            tanggal_out,
                                                            operation,
                                                            longdescription,
                                                            `status`,
                                                            ipaddress,
                                                            createdatetime)
                                        VALUES('$productionorder',
                                                '$productiondemand',
                                                '$stepnumber',
                                                '$tanggal_proses_out',
                                                '$operation',
                                                '$longdescription',
                                                '$status',
                                                '$ipaddress',
                                                '$createdatetime')");
        if($simpan_cache_out){
            header('Location: https://online.indotaichen.com/laporan/ppc_filter_steps.php?demand='.TRIM($productiondemand).'&prod_order='.TRIM($productionorder).'');
            exit;
        }else{
            echo("Error description: " . mysqli_error($simpan_cache_out));
        }
    }elseif (isset($_POST['simpan_keterangan'])) {
        $productionorder    = $_POST['productionorder'];
        $productiondemand   = $_POST['productiondemand'];
        $keterangan         = $_POST['keterangan'];
        $ipaddress          = $_POST['ipaddress'];
        $createdatetime     = $_POST['createdatetime'];

        $simpan_keterangan  = mysqli_query($con_nowprd, "INSERT INTO posisikk_keterangan(productionorder,
                                                                                productiondemand,
                                                                                keterangan,
                                                                                ipaddress,
                                                                                createdatetime)
                                                            VALUES('$productionorder',
                                                                    '$productiondemand',
                                                                    '$keterangan',
                                                                    '$ipaddress',
                                                                    '$createdatetime')");
        if($simpan_keterangan){
            header('Location: https://online.indotaichen.com/laporan/ppc_filter_steps.php?demand='.TRIM($productiondemand).'&prod_order='.TRIM($productionorder).'');
            exit;
        }else{
            echo("Error description: " . mysqli_error($simpan_keterangan));
        }
    }elseif ($_GET['simpan_note'] == 'simpan_note'){
        $productionorder    = $_GET['PRODUCTIONORDERCODE'];
        $productiondemand   = $_GET['PRODUCTIONDEMANDCODE'];
        $stepnumber         = $_GET['STEPNUMBER'];
        $operationcode      = $_GET['OPERATIONCODE'];
        $keterangan         = str_replace ("'","\'", $_GET['KETERANGAN']);
        $ipaddress          = $_GET['IPADDRESS'];
        $createdatetime     = $_GET['CREATEDATETIME'];

        $simpan_keterangan  = mysqli_query($con_nowprd, "INSERT INTO keterangan_leader(PRODUCTIONORDERCODE,
                                                                                        PRODUCTIONDEMANDCODE,
                                                                                        STEPNUMBER,
                                                                                        OPERATIONCODE,
                                                                                        KETERANGAN,
                                                                                        IPADDRESS,
                                                                                        CREATEDATETIME)
                                                            VALUES('$productionorder',
                                                                    '$productiondemand',
                                                                    '$stepnumber',
                                                                    '$operationcode',
                                                                    '$keterangan',
                                                                    '$ipaddress',
                                                                    '$createdatetime')");
        if($simpan_keterangan){
            header("Location: https://online.indotaichen.com/laporan/ppc_filter_steps.php?demand=$productiondemand&prod_order=$productionorder");
            exit;
        }else{
            echo("Error description: ".$mysqli -> error);
            echo "INSERT INTO keterangan_leader(PRODUCTIONORDERCODE,
                                                            PRODUCTIONDEMANDCODE,
                                                            STEPNUMBER,
                                                            OPERATIONCODE,
                                                            KETERANGAN,
                                                            IPADDRESS,
                                                            CREATEDATETIME)
                                                VALUES('$productionorder',
                                                '$productiondemand',
                                                '$stepnumber',
                                                '$operationcode',
                                                '$keterangan',
                                                '$ipaddress',
                                                '$createdatetime')";
            exit();
        }
    }elseif ($_GET['edit_note'] == 'edit_note'){
        $productionorder    = $_GET['PRODUCTIONORDERCODE'];
        $productiondemand   = $_GET['PRODUCTIONDEMANDCODE'];
        $stepnumber         = $_GET['STEPNUMBER'];
        $keterangan         = str_replace ("'","\'", $_GET['KETERANGAN']);
        $ipaddress          = $_GET['IPADDRESS'];
        $createdatetime     = $_GET['CREATEDATETIME'];

        $ubah_keterangan  = mysqli_query($con_nowprd, "UPDATE keterangan_leader SET KETERANGAN = '$keterangan'
                                                            WHERE PRODUCTIONORDERCODE = '$productionorder'
                                                            AND PRODUCTIONDEMANDCODE = '$productiondemand'
                                                            AND STEPNUMBER = '$stepnumber'");
        
        if($ubah_keterangan){
            header("Location: https://online.indotaichen.com/laporan/ppc_filter_steps.php?demand=$productiondemand&prod_order=$productionorder");
            exit;
        }else{
            echo("Error description: ".$mysqli -> error);
            echo "UPDATE keterangan_leader SET KETERANGAN = '$keterangan'
                                        WHERE PRODUCTIONORDERCODE = '$productionorder'
                                        AND PRODUCTIONDEMANDCODE = '$productiondemand'
                                        AND STEPNUMBER = '$stepnumber'";
            exit();
        }
    }

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PPC - Posisi KK</title>
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
                                        <h5>Filter Pencarian Steps / Posisi KK</h5>
                                    </div>
                                    <div class="card-block">
                                        <form action="" method="post">
                                            <div class="row">
                                                <div class="col-sm-6 col-xl-6 m-b-30">
                                                    <h4 class="sub-title">Production Order:</h4>
                                                    <input type="text" name="prod_order" class="form-control" value="<?php if(isset($_POST['submit'])){ echo $_POST['prod_order']; }elseif(isset($_GET['prod_order'])){ echo $_GET['prod_order']; } ?>">
                                                </div>
                                                <div class="col-sm-6 col-xl-6 m-b-30">
                                                    <h4 class="sub-title">Production Demand:</h4>
                                                    <input type="text" name="demand" class="form-control" placeholder="Wajib di isi" required value="<?php if(isset($_POST['submit'])){ echo $_POST['demand']; }elseif(isset($_GET['demand'])){ echo $_GET['demand']; } ?>">
                                                </div>
                                                <div class="col-sm-12 col-xl-4 m-b-30">
                                                    <button type="submit" name="submit" class="btn btn-primary"><i class="icofont icofont-search-alt-1"></i> Cari data</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div> 
                                </div>
                                <?php if (isset($_POST['submit']) OR isset($_GET['demand']) OR isset($_GET['prod_order'])) : ?>
                                    <div class="card">
                                        <div class="card-header">
                                            <?php
                                                ini_set("error_reporting", 0);
                                                session_start();
                                                require_once "koneksi_test.php";

                                                if($_GET['demand']){
                                                    $demand     = $_GET['demand'];
                                                }else{
                                                    $demand     = $_POST['demand'];
                                                }
                                                
                                                $q_demand   = db2_exec($conn1, "SELECT * FROM PRODUCTIONDEMAND WHERE CODE = '$demand'");
                                                $d_demand   = db2_fetch_assoc($q_demand);

                                                $sql_warna		= db2_exec($conn1, "SELECT DISTINCT TRIM(WARNA) AS WARNA FROM ITXVIEWCOLOR 
                                                                                        WHERE ITEMTYPECODE = '$d_demand[ITEMTYPEAFICODE]' 
                                                                                        AND SUBCODE01 = '$d_demand[SUBCODE01]' 
                                                                                        AND SUBCODE02 = '$d_demand[SUBCODE02]'
                                                                                        AND SUBCODE03 = '$d_demand[SUBCODE03]' 
                                                                                        AND SUBCODE04 = '$d_demand[SUBCODE04]'
                                                                                        AND SUBCODE05 = '$d_demand[SUBCODE05]' 
                                                                                        AND SUBCODE06 = '$d_demand[SUBCODE06]'
                                                                                        AND SUBCODE07 = '$d_demand[SUBCODE07]' 
                                                                                        AND SUBCODE08 = '$d_demand[SUBCODE08]'
                                                                                        AND SUBCODE09 = '$d_demand[SUBCODE09]' 
                                                                                        AND SUBCODE10 = '$d_demand[SUBCODE10]'");
                                                $dt_warna		= db2_fetch_assoc($sql_warna);
                                            ?>
                                            <table border="0" style='font-family:"Microsoft Sans Serif"'>
                                                <tr>
                                                    <td>Kode Product/Kode Warna </td>
                                                    <td>&nbsp;&nbsp;&nbsp; : &nbsp;</td>
                                                    <td><?= TRIM($d_demand['SUBCODE02']).TRIM($d_demand['SUBCODE03']).'-'.TRIM($d_demand['SUBCODE05']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Warna</td>
                                                    <td>&nbsp;&nbsp;&nbsp; : &nbsp;</td>
                                                    <td><?= $dt_warna['WARNA']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>No Order</td>
                                                    <td>&nbsp;&nbsp;&nbsp; : &nbsp;</td>
                                                    <td><?= $d_demand['ORIGDLVSALORDLINESALORDERCODE']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Original PD Code</td>
                                                    <td>&nbsp;&nbsp;&nbsp; : &nbsp;</td>
                                                    <td><a target="_BLANK" href="http://online.indotaichen.com/laporan/ppc_filter_steps.php?demand=<?= substr($d_ITXVIEWKK['ORIGINALPDCODE'], 4); ?>"><b><?= substr($d_ITXVIEWKK['ORIGINALPDCODE'], 4); ?></b></a></td>
                                                </tr>
                                                <tr>
                                                    <td>LOT</td>
                                                    <td>&nbsp;&nbsp;&nbsp; : &nbsp;</td>
                                                    <td><?= $d_demand['DESCRIPTION']; ?></td>
                                                </tr>
                                                <?php 
                                                    $sql_lebargramasi	= db2_exec($conn1, "SELECT i.LEBAR,
                                                                                            CASE
                                                                                                WHEN i2.GRAMASI_KFF IS NULL THEN i2.GRAMASI_FKF
                                                                                                ELSE i2.GRAMASI_KFF
                                                                                            END AS GRAMASI 
                                                                                            FROM 
                                                                                                ITXVIEWLEBAR i 
                                                                                            LEFT JOIN ITXVIEWGRAMASI i2 ON i2.SALESORDERCODE = '$d_ITXVIEWKK[PROJECTCODE]' 
                                                                                            AND i2.ORDERLINE = '$d_ITXVIEWKK[ORIGDLVSALORDERLINEORDERLINE]'
                                                                                            WHERE 
                                                                                            i.SALESORDERCODE = '$d_ITXVIEWKK[PROJECTCODE]' AND i.ORDERLINE = '$d_ITXVIEWKK[ORIGDLVSALORDERLINEORDERLINE]'");
                                                    $dt_lg				= db2_fetch_assoc($sql_lebargramasi);
                                                ?>
                                                <tr>
                                                    <td>Lebar x Gramasi</td>
                                                    <td>&nbsp;&nbsp;&nbsp; : &nbsp;</td>
                                                    <td><?= floor($dt_lg['LEBAR']); ?> x <?= floor($dt_lg['GRAMASI']); ?></td>
                                                </tr>
                                                <tr rowspan='5'>
                                                    <td>KETERANGAN</td>
                                                    <td>&nbsp;&nbsp;&nbsp; : &nbsp;</td>
                                                    <td> 
                                                        <?php
                                                            $cek_keterangan     = mysqli_query($con_nowprd, "SELECT * FROM posisikk_keterangan 
                                                                                                                WHERE 
                                                                                                                    productionorder = '$d_ITXVIEWKK[PRODUCTIONORDERCODE]' 
                                                                                                                    AND productiondemand = '$d_ITXVIEWKK[PRODUCTIONDEMANDCODE]'");
                                                            $data_keterangan    = mysqli_fetch_assoc($cek_keterangan);
                                                        ?>
                                                        <?php if($data_keterangan['keterangan']) :  ?>
                                                            <span style="background-color: #A5CEA8; color: black;"><?= $data_keterangan['keterangan']; ?></span>
                                                        <?php else : ?>
                                                            <form action="" method="POST">
                                                                <input type="hidden" name="productionorder" value="<?= $d_ITXVIEWKK['PRODUCTIONORDERCODE']; ?>">
                                                                <input type="hidden" name="productiondemand" value="<?= $d_ITXVIEWKK['PRODUCTIONDEMANDCODE']; ?>">
                                                                <input type="hidden" name="ipaddress" value="<?= $_SERVER['REMOTE_ADDR'] ?>">
                                                                <input type="hidden" name="createdatetime" value="<?= date('Y-m-d H:i:s'); ?>">
                                                                <input type="text" name="keterangan" class="form-control input-sm" value="">
                                                                <button class="btn btn-primary btn-mini" name="simpan_keterangan">Save</button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="card-block">
                                            <div class="table-responsive dt-responsive">
                                                <table border="1" style='font-family:"Microsoft Sans Serif"' width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="100px" style="text-align: center;">STEP <br>NUMBER</th>
                                                            <th width="100px" style="text-align: center;">ACTIONS</th>
                                                            <th width="300px" style="text-align: center;">TANGGAL <br>IN</th>
                                                            <th width="300px" style="text-align: center;">TANGGAL <br>OUT</th>
                                                            <th width="100px" style="text-align: center;">OPERATION</th>
                                                            <th width="100px" style="text-align: center;">DEPT</th>
                                                            <th width="500px" style="text-align: center;">LONGDESCRIPTION</th>
                                                            <th width="100px" style="text-align: center;">STATUS</th>
                                                            <th width="100px" style="text-align: center;">PROD. <br>ORDER</th>
                                                            <th width="100px" style="text-align: center;">PROD. <br>DEMAND</th>
                                                            <th width="100px" style="text-align: center;">OPERATOR <br>IN</th>
                                                            <th width="100px" style="text-align: center;">OPERATOR <br>OUT</th>
                                                            <th width="100px" style="text-align: center;">NO GEROBAK</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody> 
                                                        <?php 
                                                            ini_set("error_reporting", 1);
                                                            session_start();
                                                            require_once "koneksi_test.php";

                                                            // itxview_posisikk_tgl_in_prodorder_ins3
                                                            $posisikk_ins3 = db2_exec($conn1, "SELECT * FROM ITXVIEW_POSISIKK_TGL_IN_PRODORDER_INS3 WHERE PRODUCTIONORDERCODE = '$prod_order'");
                                                            while ($row_posisikk_ins3   = db2_fetch_assoc($posisikk_ins3)) {
                                                                $r_posisikk_ins3[]      = "('".TRIM(addslashes($row_posisikk_ins3['PRODUCTIONORDERCODE']))."',"
                                                                                        ."'".TRIM(addslashes($row_posisikk_ins3['OPERATIONCODE']))."',"
                                                                                        ."'".TRIM(addslashes($row_posisikk_ins3['PROPROGRESSPROGRESSNUMBER']))."',"
                                                                                        ."'".TRIM(addslashes($row_posisikk_ins3['DEMANDSTEPSTEPNUMBER']))."',"
                                                                                        ."'".TRIM(addslashes($row_posisikk_ins3['PROGRESSTEMPLATECODE']))."',"
                                                                                        ."'".TRIM(addslashes($row_posisikk_ins3['MULAI']))."',"
                                                                                        ."'".TRIM(addslashes($row_posisikk_ins3['OP']))."',"
                                                                                        ."'".$_SERVER['REMOTE_ADDR']."',"
                                                                                        ."'".date('Y-m-d H:i:s')."')";
                                                            }
                                                            if($r_posisikk_ins3){
                                                                $value_posisikk_ins3        = implode(',', $r_posisikk_ins3);
                                                                $insert_posisikk_ins3       = mysqli_query($con_nowprd, "INSERT INTO itxview_posisikk_tgl_in_prodorder_ins3(PRODUCTIONORDERCODE,OPERATIONCODE,PROPROGRESSPROGRESSNUMBER,DEMANDSTEPSTEPNUMBER,PROGRESSTEMPLATECODE,MULAI,OP,IPADDRESS,CREATEDATETIME) VALUES $value_posisikk_ins3");
                                                            }
                                                            
                                                            // itxview_posisikk_tgl_in_prodorder_cnp1
                                                            $posisikk_cnp1 = db2_exec($conn1, "SELECT * FROM ITXVIEW_POSISIKK_TGL_IN_PRODORDER_CNP1 WHERE PRODUCTIONORDERCODE = '$prod_order'");
                                                            while ($row_posisikk_cnp1   = db2_fetch_assoc($posisikk_cnp1)) {
                                                                $r_posisikk_cnp1[]      = "('".TRIM(addslashes($row_posisikk_cnp1['PRODUCTIONORDERCODE']))."',"
                                                                                        ."'".TRIM(addslashes($row_posisikk_cnp1['OPERATIONCODE']))."',"
                                                                                        ."'".TRIM(addslashes($row_posisikk_cnp1['PROPROGRESSPROGRESSNUMBER']))."',"
                                                                                        ."'".TRIM(addslashes($row_posisikk_cnp1['DEMANDSTEPSTEPNUMBER']))."',"
                                                                                        ."'".TRIM(addslashes($row_posisikk_cnp1['PROGRESSTEMPLATECODE']))."',"
                                                                                        ."'".TRIM(addslashes($row_posisikk_cnp1['MULAI']))."',"
                                                                                        ."'".TRIM(addslashes($row_posisikk_cnp1['OP']))."',"
                                                                                        ."'".$_SERVER['REMOTE_ADDR']."',"
                                                                                        ."'".date('Y-m-d H:i:s')."')";
                                                            }
                                                            if($r_posisikk_cnp1){
                                                                $value_posisikk_cnp1        = implode(',', $r_posisikk_cnp1);
                                                                $insert_posisikk_cnp1       = mysqli_query($con_nowprd, "INSERT INTO itxview_posisikk_tgl_in_prodorder_cnp1(PRODUCTIONORDERCODE,OPERATIONCODE,PROPROGRESSPROGRESSNUMBER,DEMANDSTEPSTEPNUMBER,PROGRESSTEMPLATECODE,MULAI,OP,IPADDRESS,CREATEDATETIME) VALUES $value_posisikk_cnp1");
                                                            }
                                                            
                                                            $sqlDB2 = "SELECT
                                                                            p.PRODUCTIONORDERCODE,
                                                                            p.STEPNUMBER AS STEPNUMBER,
                                                                            CASE
                                                                                WHEN TRIM(p.PRODRESERVATIONLINKGROUPCODE) IS NULL OR TRIM(p.PRODRESERVATIONLINKGROUPCODE) = '' THEN TRIM(p.OPERATIONCODE)
                                                                                ELSE TRIM(p.PRODRESERVATIONLINKGROUPCODE)
                                                                            END AS OPERATIONCODE,
                                                                            TRIM(o.OPERATIONGROUPCODE) AS DEPT,
                                                                            o.LONGDESCRIPTION,
                                                                            CASE
                                                                                WHEN p.PROGRESSSTATUS = 0 THEN 'Entered'
                                                                                WHEN p.PROGRESSSTATUS = 1 THEN 'Planned'
                                                                                WHEN p.PROGRESSSTATUS = 2 THEN 'Progress'
                                                                                WHEN p.PROGRESSSTATUS = 3 THEN 'Closed'
                                                                            END AS STATUS_OPERATION,
                                                                            iptip.MULAI,
                                                                            CASE
                                                                                WHEN p.PROGRESSSTATUS = 3 THEN COALESCE(iptop.SELESAI, SUBSTRING(p.LASTUPDATEDATETIME, 1, 19) || '(Run Manual Closures)')
                                                                                ELSE iptop.SELESAI
                                                                            END AS SELESAI,
                                                                            p.PRODUCTIONORDERCODE,
                                                                            p.PRODUCTIONDEMANDCODE,
                                                                            iptip.LONGDESCRIPTION AS OP1,
                                                                            iptop.LONGDESCRIPTION AS OP2,
                                                                            CASE
                                                                                WHEN a.VALUEBOOLEAN = 1 THEN 'Tidak Perlu Gerobak'
                                                                                ELSE LISTAGG(DISTINCT FLOOR(idqd.VALUEQUANTITY), ', ')
                                                                            END AS GEROBAK 
                                                                        FROM 
                                                                            PRODUCTIONDEMANDSTEP p 
                                                                        LEFT JOIN OPERATION o ON o.CODE = p.OPERATIONCODE 
                                                                        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = o.ABSUNIQUEID AND a.FIELDNAME = 'Gerobak'
                                                                        LEFT JOIN ITXVIEW_POSISIKK_TGL_IN_PRODORDER iptip ON iptip.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE AND iptip.DEMANDSTEPSTEPNUMBER = p.STEPNUMBER
                                                                        LEFT JOIN ITXVIEW_POSISIKK_TGL_OUT_PRODORDER iptop ON iptop.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE AND iptop.DEMANDSTEPSTEPNUMBER = p.STEPNUMBER
                                                                        LEFT JOIN ITXVIEW_DETAIL_QA_DATA idqd ON idqd.PRODUCTIONDEMANDCODE = p.PRODUCTIONDEMANDCODE AND idqd.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE
                                                                                                            -- AND idqd.OPERATIONCODE = COALESCE(p.PRODRESERVATIONLINKGROUPCODE, p.OPERATIONCODE)
                                                                                                            AND idqd.OPERATIONCODE = CASE
                                                                                                                                        WHEN TRIM(p.PRODRESERVATIONLINKGROUPCODE) IS NULL OR TRIM(p.PRODRESERVATIONLINKGROUPCODE) = '' THEN TRIM(p.OPERATIONCODE)
                                                                                                                                        ELSE TRIM(p.PRODRESERVATIONLINKGROUPCODE)
                                                                                                                                    END
                                                                                                            AND (idqd.VALUEINT = p.STEPNUMBER OR idqd.VALUEINT = p.GROUPSTEPNUMBER) 
                                                                                                            AND (idqd.CHARACTERISTICCODE = 'GRB1' OR
                                                                                                                idqd.CHARACTERISTICCODE = 'GRB2' OR
                                                                                                                idqd.CHARACTERISTICCODE = 'GRB3' OR
                                                                                                                idqd.CHARACTERISTICCODE = 'GRB4' OR
                                                                                                                idqd.CHARACTERISTICCODE = 'GRB5' OR
                                                                                                                idqd.CHARACTERISTICCODE = 'GRB6' OR
                                                                                                                idqd.CHARACTERISTICCODE = 'GRB7' OR
                                                                                                                idqd.CHARACTERISTICCODE = 'GRB8' OR
                                                                                                                idqd.CHARACTERISTICCODE = 'GRB9' OR
                                                                                                                idqd.CHARACTERISTICCODE = 'GRB10' OR
                                                                                                                idqd.CHARACTERISTICCODE = 'AREA')
                                                                                                            AND NOT (idqd.VALUEQUANTITY = 9 OR idqd.VALUEQUANTITY = 999 OR idqd.VALUEQUANTITY = 1 OR idqd.VALUEQUANTITY = 9999 OR idqd.VALUEQUANTITY = 99999 OR idqd.VALUEQUANTITY = 99 OR idqd.VALUEQUANTITY = 91)
                                                                        WHERE
                                                                            p.PRODUCTIONORDERCODE  = '$prod_order' AND p.PRODUCTIONDEMANDCODE = '$demand'  
                                                                        GROUP BY
                                                                            p.PRODUCTIONORDERCODE,
                                                                            p.STEPNUMBER,
                                                                            p.OPERATIONCODE,
                                                                            p.PRODRESERVATIONLINKGROUPCODE,
                                                                            o.OPERATIONGROUPCODE,
                                                                            o.LONGDESCRIPTION,
                                                                            p.PROGRESSSTATUS,
                                                                            iptip.MULAI,
                                                                            iptop.SELESAI,
                                                                            p.LASTUPDATEDATETIME,
                                                                            p.PRODUCTIONORDERCODE,
                                                                            p.PRODUCTIONDEMANDCODE,
                                                                            iptip.LONGDESCRIPTION,
                                                                            iptop.LONGDESCRIPTION,
                                                                            a.VALUEBOOLEAN
                                                                        ORDER BY p.STEPNUMBER ASC";
                                                            $stmt = db2_exec($conn1, $sqlDB2);
                                                            while ($rowdb2 = db2_fetch_assoc($stmt)) {
                                                        ?>
                                                            <tr>
                                                                <td align="center"><?= $rowdb2['STEPNUMBER']; ?></td>
                                                                <td align="center">
                                                                    <?php
                                                                        $q_ket_leader   = mysqli_query($con_nowprd, "SELECT * FROM keterangan_leader 
                                                                                                                            WHERE PRODUCTIONORDERCODE = '$rowdb2[PRODUCTIONORDERCODE]' 
                                                                                                                                AND PRODUCTIONDEMANDCODE = '$rowdb2[PRODUCTIONDEMANDCODE]' 
                                                                                                                                AND STEPNUMBER = '$rowdb2[STEPNUMBER]'");
                                                                        $d_ket_leader   = mysqli_fetch_assoc($q_ket_leader);
                                                                    ?>
                                                                    <?php if($d_ket_leader['KETERANGAN']) : ?>
                                                                        <abbr title="<?= $d_ket_leader['KETERANGAN']; ?>" data-toggle="modal" data-target="#view-note<?= $rowdb2['STEPNUMBER']; ?>">View Note</abbr>
                                                                    <?php else : ?>
                                                                        <button type="button" style="color: #4778FF;" data-toggle="modal" data-target="#confirm-note<?= $rowdb2['STEPNUMBER']; ?>">
                                                                            <i class="icofont icofont-speech-comments"></i>Notes
                                                                        </button>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td align="center">
                                                                    <?php if($rowdb2['OPERATIONCODE'] == 'INS3') : ?>
                                                                        <?php
                                                                            $q_mulai_ins3   = mysqli_query($con_nowprd, "SELECT
                                                                                                                                * 
                                                                                                                            FROM
                                                                                                                                `itxview_posisikk_tgl_in_prodorder_ins3` 
                                                                                                                            WHERE
                                                                                                                                productionordercode = '$prod_order'
                                                                                                                                AND IPADDRESS = '$_SERVER[REMOTE_ADDR]'
                                                                                                                            ORDER BY
                                                                                                                                MULAI ASC LIMIT 1");
                                                                            $d_mulai_ins3   = mysqli_fetch_assoc($q_mulai_ins3);
                                                                            echo $d_mulai_ins3['MULAI'];
                                                                        ?>
                                                                    <?php elseif($rowdb2['OPERATIONCODE'] == 'CNP1') : ?>
                                                                        <?php
                                                                            $q_mulai_cnp1   = mysqli_query($con_nowprd, "SELECT
                                                                                                                                * 
                                                                                                                            FROM
                                                                                                                                `itxview_posisikk_tgl_in_prodorder_cnp1` 
                                                                                                                            WHERE
                                                                                                                                productionordercode = '$prod_order'
                                                                                                                                AND IPADDRESS = '$_SERVER[REMOTE_ADDR]'
                                                                                                                            ORDER BY
                                                                                                                                MULAI ASC LIMIT 1");
                                                                            $d_mulai_cnp1   = mysqli_fetch_assoc($q_mulai_cnp1);
                                                                            echo $d_mulai_cnp1['MULAI'];
                                                                        ?>
                                                                    <?php else : ?>
                                                                        <?php if($rowdb2['MULAI']) : ?>
                                                                            <?= $rowdb2['MULAI']; ?>
                                                                        <?php else : ?>
                                                                            <?php 
                                                                                $cek_cache  = mysqli_query($con_nowprd, "SELECT * FROM posisikk_cache_in 
                                                                                                                                WHERE productionorder= '$rowdb2[PRODUCTIONORDERCODE]' 
                                                                                                                                AND productiondemand = '$rowdb2[PRODUCTIONDEMANDCODE]' 
                                                                                                                                AND stepnumber = '$rowdb2[STEPNUMBER]'");
                                                                                $d_cache    = mysqli_fetch_assoc($cek_cache);
                                                                                $cache_MULAI    = $d_cache['tanggal_in'];
                                                                            ?>
                                                                            <?php if($cache_MULAI) : ?>
                                                                                <span style="background-color: #A5CEA8;"><?= $cache_MULAI; ?></span>
                                                                            <?php else : ?>
                                                                                <?php if($rowdb2['STATUS_OPERATION'] != 'Closed') : ?>
                                                                                    <form action="" method="POST">
                                                                                        <input type="hidden" name="productionorder" value="<?= $rowdb2['PRODUCTIONORDERCODE']; ?>">
                                                                                        <input type="hidden" name="productiondemand" value="<?= $rowdb2['PRODUCTIONDEMANDCODE']; ?>">
                                                                                        <input type="hidden" name="stepnumber" value="<?= $rowdb2['STEPNUMBER']; ?>">
                                                                                        <input type="hidden" name="tanggal_out" value="<?= $rowdb2['SELESAI']; ?>">
                                                                                        <input type="hidden" name="operation" value="<?= $rowdb2['OPERATIONCODE']; ?>">
                                                                                        <input type="hidden" name="longdescription" value="<?= $rowdb2['LONGDESCRIPTION']; ?>">
                                                                                        <input type="hidden" name="status" value="<?= $rowdb2['STATUS_OPERATION']; ?>">
                                                                                        <input type="hidden" name="ipaddress" value="<?= $_SERVER['REMOTE_ADDR'] ?>">
                                                                                        <input type="hidden" name="createdatetime" value="<?= date('Y-m-d H:i:s'); ?>">
                                                                                        <input type="datetime-local" name="tanggal_proses_in" required>
                                                                                        <button class="btn btn-primary btn-mini" name="simpanin_catch">Save</button>
                                                                                    </form>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td align="center">
                                                                    <?php if($rowdb2['OPERATIONCODE'] == 'INS3') : ?>
                                                                        <?php
                                                                            // periksa jika hanya 1 data, maka 1 data tersebut untuk jam mulai saja
                                                                            $q_cek_ins3     = mysqli_query($con_nowprd, "SELECT
                                                                                                                                count(*) AS jml
                                                                                                                            FROM
                                                                                                                                `itxview_posisikk_tgl_in_prodorder_ins3` 
                                                                                                                            WHERE
                                                                                                                                productionordercode = '$prod_order' 
                                                                                                                                AND IPADDRESS = '$_SERVER[REMOTE_ADDR]'");
                                                                            $row_cek_ins3   = mysqli_fetch_assoc($q_cek_ins3);
                                                                            if($row_cek_ins3['jml'] == '1'){
                                                                                echo '';
                                                                            }else{
                                                                                $q_mulai_ins3   = mysqli_query($con_nowprd, "SELECT
                                                                                                                                    * 
                                                                                                                                FROM
                                                                                                                                    `itxview_posisikk_tgl_in_prodorder_ins3` 
                                                                                                                                WHERE
                                                                                                                                    productionordercode = '$prod_order' 
                                                                                                                                    AND IPADDRESS = '$_SERVER[REMOTE_ADDR]'
                                                                                                                                ORDER BY
                                                                                                                                    MULAI DESC LIMIT 1");
                                                                                $d_selesai_ins3   = mysqli_fetch_assoc($q_mulai_ins3);
                                                                                echo $d_selesai_ins3['MULAI'];
                                                                            }
                                                                        ?>
                                                                    <?php elseif($rowdb2['OPERATIONCODE'] == 'CNP1') : ?>
                                                                        <?php
                                                                            // periksa jika hanya 1 data, maka 1 data tersebut untuk jam mulai saja
                                                                            $q_cek_cnp1     = mysqli_query($con_nowprd, "SELECT
                                                                                                                                count(*) AS jml
                                                                                                                            FROM
                                                                                                                                `itxview_posisikk_tgl_in_prodorder_cnp1` 
                                                                                                                            WHERE
                                                                                                                                productionordercode = '$prod_order' 
                                                                                                                                AND IPADDRESS = '$_SERVER[REMOTE_ADDR]'");
                                                                            $row_cek_cnp1   = mysqli_fetch_assoc($q_cek_cnp1);
                                                                            if($row_cek_cnp1['jml'] == '1'){
                                                                                echo '';
                                                                            }else{
                                                                                $q_mulai_cnp1   = mysqli_query($con_nowprd, "SELECT
                                                                                                                                    * 
                                                                                                                                FROM
                                                                                                                                    `itxview_posisikk_tgl_in_prodorder_cnp1` 
                                                                                                                                WHERE
                                                                                                                                    productionordercode = '$prod_order'
                                                                                                                                    AND IPADDRESS = '$_SERVER[REMOTE_ADDR]'
                                                                                                                                ORDER BY
                                                                                                                                    MULAI DESC LIMIT 1");
                                                                                $d_selesai_cnp1   = mysqli_fetch_assoc($q_mulai_cnp1);
                                                                                echo $d_selesai_cnp1['MULAI'];
                                                                            }
                                                                        ?>
                                                                    <?php else : ?>
                                                                        <?php if($rowdb2['SELESAI']) : ?>
                                                                            <?= $rowdb2['SELESAI']; ?>
                                                                        <?php else : ?>
                                                                            <?php 
                                                                                $cek_cache  = mysqli_query($con_nowprd, "SELECT * FROM posisikk_cache_out 
                                                                                                                                WHERE productionorder= '$rowdb2[PRODUCTIONORDERCODE]' 
                                                                                                                                AND productiondemand = '$rowdb2[PRODUCTIONDEMANDCODE]' 
                                                                                                                                AND stepnumber = '$rowdb2[STEPNUMBER]'");
                                                                                $d_cache    = mysqli_fetch_assoc($cek_cache);
                                                                                $cache_SELESAI    = $d_cache['tanggal_out'];
                                                                            ?>
                                                                            <?php if($cache_SELESAI) : ?>
                                                                                <span style="background-color: #A5CEA8;"><?= $cache_SELESAI; ?></span>
                                                                            <?php else : ?>
                                                                                <?php if($rowdb2['STATUS_OPERATION'] != 'Closed') : ?>
                                                                                    <form action="" method="POST">
                                                                                        <input type="hidden" name="productionorder" value="<?= $rowdb2['PRODUCTIONORDERCODE']; ?>">
                                                                                        <input type="hidden" name="productiondemand" value="<?= $rowdb2['PRODUCTIONDEMANDCODE']; ?>">
                                                                                        <input type="hidden" name="stepnumber" value="<?= $rowdb2['STEPNUMBER']; ?>">
                                                                                        <input type="hidden" name="tanggal_out" value="<?= $rowdb2['SELESAI']; ?>">
                                                                                        <input type="hidden" name="operation" value="<?= $rowdb2['OPERATIONCODE']; ?>">
                                                                                        <input type="hidden" name="longdescription" value="<?= $rowdb2['LONGDESCRIPTION']; ?>">
                                                                                        <input type="hidden" name="status" value="<?= $rowdb2['STATUS_OPERATION']; ?>">
                                                                                        <input type="hidden" name="ipaddress" value="<?= $_SERVER['REMOTE_ADDR'] ?>">
                                                                                        <input type="hidden" name="createdatetime" value="<?= date('Y-m-d H:i:s'); ?>">
                                                                                        <input type="datetime-local" name="tanggal_proses_out" required>
                                                                                        <button class="btn btn-primary btn-mini" name="simpanout_catch">Save</button>
                                                                                    </form>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td align="center"><?= $rowdb2['OPERATIONCODE']; ?></td>
                                                                <td align="center"><?= $rowdb2['DEPT']; ?></td>
                                                                <td><?= $rowdb2['LONGDESCRIPTION']; ?></td>
                                                                <td 
                                                                    <?php 
                                                                        if($rowdb2['STATUS_OPERATION'] == 'Closed'){ 
                                                                            echo 'style="background-color:#DC526E; color:#F7F7F7;"'; 
                                                                            
                                                                        }elseif($rowdb2['STATUS_OPERATION'] == 'Progress'){ 
                                                                            echo 'style="background-color:#41CC11;"'; 
                                                                        }else{ 
                                                                            echo 'style="background-color:#CECECE;"'; 
                                                                        } 
                                                                    ?>>
                                                                    <center><?= $rowdb2['STATUS_OPERATION']; ?></center>
                                                                </td>
                                                                <td><?= $rowdb2['PRODUCTIONORDERCODE']; ?></td>
                                                                <td><?= $rowdb2['PRODUCTIONDEMANDCODE']; ?></td>
                                                                <td>
                                                                    <?php if($rowdb2['OPERATIONCODE'] == 'INS3') : ?>
                                                                        <?= $d_mulai_ins3['OP']; ?>
                                                                    <?php elseif($rowdb2['OPERATIONCODE'] == 'CNP1') : ?>
                                                                        <?= $d_mulai_cnp1['OP']; ?>
                                                                    <?php else : ?>
                                                                        <?= $rowdb2['OP1']; ?>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <?php if($rowdb2['OPERATIONCODE'] == 'INS3') : ?>
                                                                        <?= $d_selesai_ins3['OP']; ?>
                                                                    <?php elseif($rowdb2['OPERATIONCODE'] == 'CNP1') : ?>
                                                                        <?= $d_selesai_cnp1['OP']; ?>
                                                                    <?php else : ?>
                                                                        <?= $rowdb2['OP2']; ?>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td align="center">
                                                                    <?php
                                                                        if($rowdb2['GEROBAK'] == 'Tidak Perlu Gerobak'){
                                                                            echo "<span style='background-color:#CECECE;'>$rowdb2[GEROBAK]</span>";
                                                                        }else{
                                                                            echo $rowdb2['GEROBAK'];
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <div id="confirm-note<?= $rowdb2['STEPNUMBER']; ?>" class="modal fade" role="dialog">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="login-card card-block login-card-modal">
                                                                        <form class="md-float-material">
                                                                            <div class="text-center">
                                                                                <img src="img\logo.png" alt="Indotaichen" width="50" height="50">
                                                                            </div>
                                                                            <div class="card m-t-15">
                                                                                <div class="auth-box card-block">
                                                                                <div class="row m-b-20">
                                                                                    <div class="col-md-12 confirm">
                                                                                        <h3 class="text-center txt-primary"><i class="icofont icofont-check-circled text-primary"></i> Notes</h3>
                                                                                    </div>
                                                                                </div>
                                                                                <p class="text-inverse text-left m-t-15 f-16"><b>Dear Leader, <br> Silahkan masukan keterangan dibawah ini.</b></p>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-addon"><i class="icofont icofont-user-alt-7"></i></span>
                                                                                    <input type="hidden" name="PRODUCTIONORDERCODE" value="<?= TRIM($rowdb2['PRODUCTIONORDERCODE']) ?>">
                                                                                    <input type="hidden" name="PRODUCTIONDEMANDCODE" value="<?= TRIM($rowdb2['PRODUCTIONDEMANDCODE']) ?>">
                                                                                    <input type="hidden" name="STEPNUMBER" value="<?= $rowdb2['STEPNUMBER'] ?>">
                                                                                    <input type="hidden" name="OPERATIONCODE" value="<?= $rowdb2['OPERATIONCODE'] ?>">
                                                                                    <input type="hidden" name="IPADDRESS" value="<?= $_SERVER['REMOTE_ADDR'] ?>">
                                                                                    <input type="hidden" name="CREATEDATETIME" value="<?= date('Y-m-d H:i:s'); ?>">
                                                                                    <textarea placeholder="your notes..." name="KETERANGAN" 
                                                                                        style="width: 100%; 
                                                                                                height: 150px; 
                                                                                                padding: 12px 20px; 
                                                                                                box-sizing: border-box;
                                                                                                border: 2px solid #ccc; 
                                                                                                border-radius: 4px; 
                                                                                                background-color: #f8f8f8; 
                                                                                                font-size: 16px; 
                                                                                                resize: none;"></textarea>
                                                                                </div>
                                                                                <div class="row m-t-15">
                                                                                    <div class="col-md-12">
                                                                                        <button name="simpan_note" value="simpan_note" class="btn btn-primary btn-md btn-block waves-effect text-center">Confirm</button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <p class="text-inverse text-left m-b-0 m-t-10">Anda akan menambahkan notes untuk step <?= $rowdb2['OPERATIONCODE']; ?>.</p>
                                                                                        <p class="text-inverse text-left"><b>Leader <?= $rowdb2['OPERATIONCODE']; ?></b></p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="view-note<?= $rowdb2['STEPNUMBER']; ?>" class="modal fade" role="dialog">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="login-card card-block login-card-modal">
                                                                        <form class="md-float-material">
                                                                            <div class="text-center">
                                                                                <img src="img\logo.png" alt="Indotaichen" width="50" height="50">
                                                                            </div>
                                                                            <div class="card m-t-15">
                                                                                <div class="auth-box card-block">
                                                                                <div class="row m-b-20">
                                                                                    <div class="col-md-12 confirm">
                                                                                        <h3 class="text-center txt-primary"><i class="icofont icofont-check-circled text-primary"></i> Notes</h3>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-addon"><i class="icofont icofont-user-alt-7"></i></span>
                                                                                    <input type="hidden" name="PRODUCTIONORDERCODE" value="<?= TRIM($rowdb2['PRODUCTIONORDERCODE']) ?>">
                                                                                    <input type="hidden" name="PRODUCTIONDEMANDCODE" value="<?= TRIM($rowdb2['PRODUCTIONDEMANDCODE']) ?>">
                                                                                    <input type="hidden" name="STEPNUMBER" value="<?= $rowdb2['STEPNUMBER'] ?>">
                                                                                    <input type="hidden" name="OPERATIONCODE" value="<?= $rowdb2['OPERATIONCODE'] ?>">
                                                                                    <input type="hidden" name="IPADDRESS" value="<?= $_SERVER['REMOTE_ADDR'] ?>">
                                                                                    <input type="hidden" name="CREATEDATETIME" value="<?= date('Y-m-d H:i:s'); ?>">
                                                                                    <textarea placeholder="your notes..." name="KETERANGAN" 
                                                                                        style="width: 100%; 
                                                                                                height: 150px; 
                                                                                                padding: 12px 20px; 
                                                                                                box-sizing: border-box;
                                                                                                border: 2px solid #ccc; 
                                                                                                border-radius: 4px; 
                                                                                                background-color: #f8f8f8; 
                                                                                                font-size: 16px; 
                                                                                                resize: none;"><?= $d_ket_leader['KETERANGAN']; ?></textarea>
                                                                                </div>
                                                                                <div class="row m-t-15">
                                                                                    <div class="col-md-12">
                                                                                        <button name="edit_note" value="edit_note" class="btn btn-primary btn-md btn-block waves-effect text-center">save changes</button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <p class="text-inverse text-left m-b-0 m-t-10">Pastikan data yang anda masukan benar.</p>
                                                                                        <p class="text-inverse text-left"><b>Leader <?= $rowdb2['OPERATIONCODE']; ?></b></p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
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
<script src="files\assets\js\pcoded.min.js"></script>
<script type="text/javascript" src="files\assets\js\script.js"></script>
<?php require_once 'footer.php'; ?>
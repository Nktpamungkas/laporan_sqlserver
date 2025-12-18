<?php
include "koneksi.php";
include "phpqrcode/qrlib.php";
// require "vendor/barcode/vendor/autoload.php";

// use Picqer\Barcode\BarcodeGeneratorPNG;

$tgl = $_GET['tgl'];
$tgl_indo = date("d F Y", strtotime($tgl));
function safe_filename($text) {
    return preg_replace('/[^A-Za-z0-9_\-]/', '_', $text);
}
$tanggal_print = date("Y-m-d H:i:s");
$approve1 = "Yogi Rahmansyah - $tgl_indo -".uniqid();
$approve2 = "Bayu Nugraha - $tgl_indo -".uniqid();
$tempdir1 = "dist/barcode_ttd_bagi_kain/";
if (!file_exists($tempdir1)) 
    mkdir($tempdir1);
$nama_file1 = safe_filename($approve1).".png";
$nama_file2 = safe_filename($approve2).".png";
QRcode::png($approve1, $tempdir1.$nama_file1, QR_ECLEVEL_H, 1, 1);
QRcode::png($approve2, $tempdir1.$nama_file2, QR_ECLEVEL_H, 1, 1);
// $generator = new BarcodeGeneratorPNG();
// $barcode = base64_encode(
//     $generator->getBarcode($tgl, $generator::TYPE_CODE_128)
// );

// Query Utama
$sqlMain = "SELECT DISTINCT  
                TRIM(p.PRODUCTIONORDERCODE) AS PRODUCTIONORDERCODE,
                TRIM(p2.DEMANDSTEPPRODUCTIONDEMANDCODE) AS PRODUCTIONDEMANDCODE
            FROM 
                PRODUCTIONPROGRESS p
            LEFT JOIN 
                PRODUCTIONPROGRESSSTEPUPDATED p2 
                ON p2.PROPROGRESSPROGRESSNUMBER = p.PROGRESSNUMBER 
            WHERE 
                p.PROGRESSENDDATE = '$tgl'
                AND p.OPERATIONCODE IN ('WAIT2','WAIT23','WAIT26','WAIT27','WAIT3','WAIT31');";
$resultMain = db2_exec($conn1, $sqlMain);
$dataList = [];
$countOrder = [];
$grand_total_plan = 0;
$grand_total_actual = 0;

// Data Utama
while ($row = db2_fetch_assoc($resultMain)) {
    $dataList[] = array_map('trim', $row);
}

// Untuk ngakalin view warna merah
foreach ($dataList as $d) {
    $poc = $d['PRODUCTIONORDERCODE'];
    if (!isset($countOrder[$poc])) 
        {
            $countOrder[$poc] = 0;
        }
    $countOrder[$poc]++;
}

// Untuk variable total
foreach ($dataList as $d) {
    $detail = getDetailData($conn1, $d['PRODUCTIONORDERCODE'], $d['PRODUCTIONDEMANDCODE']);
    if (!$detail) continue;

    $grand_total_plan += $detail['qty_plan'];
    $grand_total_actual += $detail['qty_actual'];
}

$totalRows = count($dataList);
$rowsPerPage = 30;
$totalPages = ceil($totalRows / $rowsPerPage);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PRINT PPC BAGI KAIN</title>

<style>
    /* Font dasar */
    body {
        font-family: Arial, sans-serif;
        font-size: 11px;
    }

    /* Table utama */
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    thead { display: table-header-group; }
    tfoot { display: table-footer-group; }

    th, td {
        border: 1px solid black;
        padding: 2px 3px;
        text-align: center;
        font-size: 11px;
        line-height: 12px;
    }

    /* Header Judul */
    .header-title {
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 2px;
    }

    .sub-title {
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 8px;
    }

    /* Header bagian tanggal */
    .header-date {
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    /* Page break ketika pindah halaman baru */
    .page-break {
        page-break-after: always;
    }

    /* CSS Footnote */
    .footer {
        width: 100%;
    }

    .footer td {
        border: 1px solid black;
        font-size: 11px;
        padding: 4px;
        text-align: center;
    }

    /* Area yang tidak di-print */
    @media print {
        .no-print { display: none !important; }

        @page {
            size: A4 landscape;
            margin: 10mm 5mm; /* Margin atas-bawah 10mm, kanan-kiri 5mm */
        }

        body {
            margin: 0;
            -webkit-print-color-adjust: exact; /* Menjaga warna/background tetap tercetak */
        }

        table {
            width: 100%;
            table-layout: auto; /* Membiarkan browser menghitung lebar berdasarkan konten */
            border-collapse: collapse;
            font-size: 8pt; /* Mengecilkan font sedikit agar kolom warna punya ruang */
        }

        /* Targetkan kolom Warna (asumsi kolom ke-5) */
        table th:nth-child(5), 
        table td:nth-child(5) {
            width: 25%; /* Berikan porsi lebih besar (misal 25% dari lebar kertas) */
            word-wrap: break-word; /* Memaksa bungkus teks jika terlalu panjang */
            white-space: normal; /* Pastikan teks bisa turun ke bawah (wrap) */
            text-align: left;
            padding: 2px 4px;
        }

        /* Kolom angka lainnya bisa dibuat pas-pasan (fit) */
        table th:not(:nth-child(5)), 
        table td:not(:nth-child(5)) {
            white-space: nowrap; /* Jangan biarkan kolom angka/ID terpotong barisnya */
        }

        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }

        tr {
            page-break-inside: avoid;
            /* Hapus height: 10px; karena jika warna panjang, baris harus bisa membesar */
        }
    }
</style>

</head>
<body>

<!-- <div class="no-print">
    <button onclick="window.print()">🖨 PRINT</button>
</div> -->

<?php
$page = 1;
$index = 0;

// Buat get detail 
function getDetailData($conn1, $productionOrder, $productionDemand)
{
    $sqlDetail = " SELECT 
                        * 
                    FROM 
                        ITXVIEW_POSISI_KARTU_KERJA 
                    WHERE 
                        PRODUCTIONORDERCODE = '$productionOrder'
                        AND PRODUCTIONDEMANDCODE = '$productionDemand'
                        AND DEPT = 'PPC'
    ";
    $resultDetail = db2_exec($conn1, $sqlDetail);
    $dataDetail = db2_fetch_assoc($resultDetail);

    if (!$dataDetail) return null;

    $sqlDemand = db2_exec($conn1, "SELECT 
                                        * 
                                    FROM 
                                        PRODUCTIONDEMAND 
                                    WHERE 
                                        CODE = '{$dataDetail['PRODUCTIONDEMANDCODE']}'
    ");
    $rowDemand = db2_fetch_assoc($sqlDemand);

    $sqlSO = db2_exec($conn1, "SELECT 
                                    * 
                                FROM 
                                    SALESORDER 
                                WHERE 
                                    CODE = '{$rowDemand['ORIGDLVSALORDLINESALORDERCODE']}'
    ");
    $rowSO = db2_fetch_assoc($sqlSO);

    $sqlDelivery = db2_exec($conn1, "SELECT 
                                        COALESCE(s2.CONFIRMEDDELIVERYDATE, s.CONFIRMEDDUEDATE) AS ACTUAL_DELIVERY
                                    FROM 
                                        SALESORDER s
                                    LEFT JOIN 
                                        SALESORDERDELIVERY s2  ON s2.SALESORDERLINESALESORDERCODE = s.CODE
                                    WHERE 
                                        s2.SALESORDERLINESALESORDERCODE = '{$rowDemand['ORIGDLVSALORDLINESALORDERCODE']}'
                                        AND s2.SALESORDERLINEORDERLINE = '{$rowDemand['ORIGDLVSALORDERLINEORDERLINE']}'
    ");
    $rowDelivery = db2_fetch_assoc($sqlDelivery);

    $sqlCustomer = db2_exec($conn1, "SELECT 
                                        * 
                                    FROM 
                                        ITXVIEW_PELANGGAN 
                                    WHERE 
                                        CODE = '{$rowSO['CODE']}' 
                                        AND ORDPRNCUSTOMERSUPPLIERCODE = '{$rowSO['ORDPRNCUSTOMERSUPPLIERCODE']}'
    ");
    $rowCustomer = db2_fetch_assoc($sqlCustomer);

    $sqlWarna = "SELECT 
                    TRIM(WARNA) AS WARNA 
                FROM 
                    ITXVIEWCOLOR
                WHERE 
                    ITEMTYPECODE = '{$rowDemand['ITEMTYPEAFICODE']}'
                    AND SUBCODE01 = '{$rowDemand['SUBCODE01']}'
                    AND SUBCODE02 = '{$rowDemand['SUBCODE02']}'
                    AND SUBCODE03 = '{$rowDemand['SUBCODE03']}'
                    AND SUBCODE04 = '{$rowDemand['SUBCODE04']}'
                    AND SUBCODE05 = '{$rowDemand['SUBCODE05']}'
                    AND SUBCODE06 = '{$rowDemand['SUBCODE06']}'
                    AND SUBCODE07 = '{$rowDemand['SUBCODE07']}'
                    AND SUBCODE08 = '{$rowDemand['SUBCODE08']}'
                    AND SUBCODE09 = '{$rowDemand['SUBCODE09']}'
                    AND SUBCODE10 = '{$rowDemand['SUBCODE10']}'
    ";
    $warnaRes = db2_exec($conn1, $sqlWarna);
    $rowWarna = db2_fetch_assoc($warnaRes);

    $sqlBruto2 = db2_exec($conn1, "SELECT
                                        p.*,
                                        a.VALUEDECIMAL AS BRUTO_KK
                                    FROM
                                        PRODUCTIONDEMAND p
                                    LEFT JOIN ADSTORAGE a 
                                        ON a.UNIQUEID = p.ABSUNIQUEID
                                        AND a.FIELDNAME = 'OriginalBruto'
                                    WHERE
                                        p.CODE = '$productionDemand'
    ");
    $rowBruto2 = db2_fetch_assoc($sqlBruto2);
    $sqlBruto = db2_exec($conn1, "SELECT
                                        p.CODE,
                                        CASE 
                                            WHEN a.VALUESTRING IS NOT NULL THEN a2.VALUEDECIMAL
                                            ELSE b.QTY_BRUTO
                                        END AS BRUTO_KK
                                    FROM
                                        PRODUCTIONDEMAND p
                                    LEFT JOIN ADSTORAGE a2 ON
                                        a2.UNIQUEID = p.ABSUNIQUEID
                                        AND a2.FIELDNAME = 'OriginalBruto'
                                    LEFT JOIN ADSTORAGE a ON
                                        p.ABSUNIQUEID = a.UNIQUEID
                                        AND a.FIELDNAME = 'OriginalPDCode'
                                    LEFT JOIN (
                                        SELECT
                                            SUM(a2.VALUEDECIMAL) AS QTY_BRUTO,
                                            ORIGDLVSALORDLINESALORDERCODE,
                                            ORIGDLVSALORDERLINEORDERLINE
                                        FROM
                                            PRODUCTIONDEMAND p
                                        LEFT JOIN ADSTORAGE a ON
                                            p.ABSUNIQUEID = a.UNIQUEID
                                            AND a.FIELDNAME = 'OriginalPDCode'
                                        LEFT JOIN ADSTORAGE a2 ON
                                            p.ABSUNIQUEID = a2.UNIQUEID
                                            AND a2.FIELDNAME = 'OriginalBruto'
                                        WHERE
                                            --	p.CODE = '00391976'
                                            --	AND 
                                            a.VALUESTRING IS NULL
                                            AND p.ITEMTYPEAFICODE = 'KFF'
                                            --	AND ORIGDLVSALORDLINESALORDERCODE ='OPN2500443'
                                            --	AND ORIGDLVSALORDERLINEORDERLINE ='10'
                                        GROUP BY
                                            ORIGDLVSALORDLINESALORDERCODE,
                                            ORIGDLVSALORDERLINEORDERLINE) b ON
                                        b.ORIGDLVSALORDLINESALORDERCODE = p.ORIGDLVSALORDLINESALORDERCODE
                                        AND b.ORIGDLVSALORDERLINEORDERLINE = p.ORIGDLVSALORDERLINEORDERLINE
                                    WHERE
                                        p.CODE = '$productionDemand'");
    $rowBruto = db2_fetch_assoc($sqlBruto);
    // $sqlBruto = db2_exec($conn1, "SELECT 
    //                                 SUM(p2.USERPRIMARYQUANTITY) AS BRUTO
    //                             FROM 
    //                                 SALESORDER s
    //                             LEFT JOIN 
    //                                 SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE
    //                             LEFT JOIN 
    //                                 PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE 
    //                                 AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE 
    //                             LEFT JOIN 
    //                                 PRODUCTIONRESERVATION p2 ON p2.ORDERCODE = p.CODE
    //                             WHERE 
    //                                 s.CODE = '{$rowSO['CODE']}'
    //                                 AND p.ORIGDLVSALORDERLINEORDERLINE = '{$rowDemand['ORIGDLVSALORDERLINEORDERLINE']}'
    // ");
    // $rowBruto = db2_fetch_assoc($sqlBruto);

    $sqlNetto = db2_exec($conn1, " SELECT 
                                        USERPRIMARYQUANTITY AS NETTO
                                    FROM 
                                        ITXVIEW_NETTO
                                    WHERE 
                                        CODE = '{$dataDetail['PRODUCTIONDEMANDCODE']}'
                                        AND SALESORDERLINESALESORDERCODE = '{$rowSO['CODE']}'
    ");
    $rowNetto = db2_fetch_assoc($sqlNetto);

    $sqlStatus = db2_exec($conn1, " SELECT 
                                        OPERATIONCODE || ' - ' || LONGDESCRIPTION AS STATUSTERAKHIR
                                    FROM 
                                        ITXVIEW_POSISI_KARTU_KERJA
                                    WHERE 
                                        PRODUCTIONORDERCODE = '$productionOrder'
                                        AND PRODUCTIONDEMANDCODE = '$productionDemand'
                                        AND STATUS_OPERATION <> 'Closed'
                                    ORDER BY 
                                        STEPNUMBER ASC
    ");
    $rowStatus = db2_fetch_assoc($sqlStatus);

    $sqlMain_bruto_plan_bagikain= db2_exec($conn1, "SELECT
                                                        p.ORIGDLVSALORDLINESALORDERCODE,
                                                        p.ORIGDLVSALORDERLINEORDERLINE,
                                                        p.CODE,
                                                        SUM(p2.USERPRIMARYQUANTITY) AS BRUTO
                                                    FROM
                                                        SALESORDER s
                                                    LEFT JOIN 
                                                        SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE 
                                                    LEFT JOIN 
                                                        PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE 
                                                        AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE 
                                                        AND p.ITEMTYPEAFICODE = 'KFF'
                                                    LEFT JOIN 
                                                        PRODUCTIONRESERVATION p2 ON p2.ORDERCODE = p.CODE 
                                                        AND p2.ITEMTYPEAFICODE = 'KGF'
                                                    WHERE
                                                        s.CODE = '{$rowSO['CODE']}'
                                                        AND p.CODE = '$productionDemand'
                                                    GROUP BY 
                                                        p.ORIGDLVSALORDLINESALORDERCODE,
                                                        p.ORIGDLVSALORDERLINEORDERLINE,
                                                        p.CODE");
    $data_bruto_plan_bagikain   = db2_fetch_assoc($sqlMain_bruto_plan_bagikain);

    $sqlMain_bruto_actual_bagikain  = db2_exec($conn1, "SELECT
                                                            p.ORIGDLVSALORDLINESALORDERCODE,
                                                            p.ORIGDLVSALORDERLINEORDERLINE,
                                                            p.CODE,
                                                            SUM(p2.USEDUSERPRIMARYQUANTITY) AS BRUTO
                                                        FROM
                                                            SALESORDER s
                                                        LEFT JOIN 
                                                            SALESORDERLINE s2 ON s2.SALESORDERCODE = s.CODE 
                                                        LEFT JOIN 
                                                            PRODUCTIONDEMAND p ON p.ORIGDLVSALORDLINESALORDERCODE = s2.SALESORDERCODE 
                                                            AND p.ORIGDLVSALORDERLINEORDERLINE = s2.ORDERLINE 
                                                            AND p.ITEMTYPEAFICODE = 'KFF'
                                                        LEFT JOIN 
                                                            PRODUCTIONRESERVATION p2 ON p2.ORDERCODE = p.CODE 
                                                            AND p2.ITEMTYPEAFICODE = 'KGF'
                                                        WHERE
                                                            s.CODE = '{$rowSO['CODE']}'
                                                            AND p.CODE = '$productionDemand'
                                                        GROUP BY 
                                                            p.ORIGDLVSALORDLINESALORDERCODE,
                                                            p.ORIGDLVSALORDERLINEORDERLINE,
                                                            p.CODE");
    $data_bruto_actual_bagikain     = db2_fetch_assoc($sqlMain_bruto_actual_bagikain);
    return [
        'delivery'      => $rowDelivery['ACTUAL_DELIVERY'] ?? '',
        'buyer'         => $rowCustomer['BUYER'] ?? '',
        'order'         => $rowSO['CODE'] ?? '',
        'item'          => trim($rowDemand['SUBCODE02'] . $rowDemand['SUBCODE03']),
        'warna'         => $rowWarna['WARNA'] ?? '',
        'bruto'         => $rowBruto['BRUTO_KK'] ?? 0,
        'qty_plan'        => $rowBruto2['BRUTO_KK'] ?? 0,
        'netto'         => $rowNetto['NETTO'] ?? 0,
        'lot'           => $rowDemand['DESCRIPTION'] ?? '',
        'status'        => $rowStatus['STATUSTERAKHIR'] ?? '',
        'bruto2'      => $rowBruto['BRUTO_KK'] ?? 0,
        'qty_actual'    => $data_bruto_actual_bagikain['BRUTO'] ?? '',
    ];
}

while ($page <= $totalPages) :
?>

    <div class="header-title">SERAH TERIMA KARTU KERJA KE GUDANG GREIGE</div>
    <div class="sub-title">FW-14-PPC-08/05</div>
    <div class="sub-title">BULAN <?= strtoupper(date("F Y", strtotime($tgl))) ?></div>

    <b>TANGGAL : <span><?= $tgl_indo ?></span></b>

    <br>
    <br>
<!-- Table Utama -->
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>BUYER</th>
                <th>ORDER</th>
                <th>NO. ITEM</th>
                <th style="text-align:center">WARNA</th>
                <th>BRUTO</th>
                <th>NETTO</th>
                <th>LOT</th>
                <th>PROD.<br>ORDER</th>
                <th>DEMAND</th>
                <th>QTY PLAN BAGI</th>
                <th>QTY ACTUAL BAGI</th>
            </tr>
        </thead>
            <?php
                $total_plan = 0;
                $total_actual = 0;
                for ($i = 0; $i < $rowsPerPage && $index < $totalRows; $i++) {

                    $d = $dataList[$index];
                    $detail = getDetailData($conn1, $d['PRODUCTIONORDERCODE'], $d['PRODUCTIONDEMANDCODE']);
                    if (!$detail) 
                        { 
                            $index++; continue; 
                        }
                        
                    $buyer = $detail['buyer'];
                    $order = $detail['order'];
                    $noitem = $detail['item'];
                    $warna = $detail['warna'];
                    $bruto = number_format($detail['bruto'],2);
                    $netto = number_format($detail['netto'],2);
                    $lot = $detail['lot'];
                    $prodorder = $d['PRODUCTIONORDERCODE'];
                    $demand = $d['PRODUCTIONDEMANDCODE'];
                    $qtyPlan = number_format($detail['qty_plan'],2);
                    $qtyActual = number_format($detail['qty_actual'],2);
                    $color = (isset($countOrder[$prodorder]) && $countOrder[$prodorder] > 1)  ? "color:red;"  : "";
                    $total_plan += $qtyPlan;
                    $total_actual += $qtyActual;
            ?>
        <tbody>
            <tr>
                <td><?= $index+1?></td>
                <td><?= $buyer; ?></td>
                <td><?= $order; ?></td>
                <td><?= $noitem; ?></td>
                <td><?= $warna; ?></td>
                <td><?= $bruto; ?></td>
                <td><?= $netto; ?></td>
                <td><?= $lot; ?></td>
                <td style="<?= $color ?>"><?= $prodorder; ?></td>
                <td><?= $demand; ?></td>
                <td><?php echo $qtyPlan;?></td>
                <td><?php echo $qtyActual; ?></td>
            </tr>
            <?php  
                $index++;
                }?>
        </tbody>

<?php if ($page == $totalPages) : ?>
<!-- Kolom Total -->
        <tr>
            <td colspan="10" style="text-align:center;font-weight:bold;">TOTAL</td>
            <td><?= number_format($grand_total_plan,2); ?></td>
            <td><?= number_format($grand_total_actual,2); ?></td>
        </tr>
<?php endif; ?>
    </table>
<?php if ($page == $totalPages) : ?>
<!-- Tanda Tangan -->
    <table class="footer">
        <tr>
            <td>&nbsp;</td>
            <td><b>Dibuat Oleh:</b></td>
            <td><b>Diketahui Oleh:</b></td>
        </tr>
        <tr>
            <td style="text-align: left;"><strong>Nama</strong></td>
            <td><strong>Yogi Rahmansyah</strong></td>
            <td><strong>Bayu Nugraha</strong></td>
        </tr>
        <tr>
            <td style="text-align: left;"><strong>Jabatan</strong></td>
            <td><strong>Staff</strong></td>
            <td><strong>Supervisor</strong></td>
        </tr>
        <tr>
            <td style="text-align: left;"><strong>Tanggal</strong></td>
            <td><strong><?= $tgl_indo ?></strong></td>
            <td><strong><?= $tgl_indo ?></strong></td>
        </tr>
        <tr>
            <td style="height:10px;text-align:left;"><strong>Tanda Tangan</strong></td>
            <td style="height:10px;text-align:center;">
                <img src="<?= $tempdir1 . $nama_file1 ?>">
            </td>
            <td style="height:10px;text-align:center;">
                <img src="<?= $tempdir1 . $nama_file2 ?>">
            </td>
        </tr>
    </table>
<?php endif; ?>

<!-- Ngakalin biar bisa ganti halaman terus -->
<?php if ($page < $totalPages) : ?>
    <div class="page-break"></div>
<?php endif; ?>

<?php 
    $page++;
endwhile;
?>

</body>
</html>

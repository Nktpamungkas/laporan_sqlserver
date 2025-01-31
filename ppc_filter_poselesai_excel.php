<?php
session_start();
require_once "koneksi.php";
require_once "utils/helper.php";

// header("Content-Type: application/vnd.ms-excel");
// header("Content-Disposition: attachment; filename=ppc_filter_poselesai.xls");

$no_order = $_GET['no_order'] ?? '';
$tgl1 = $_GET['tgl1'] ?? '';
$tgl2 = $_GET['tgl2'] ?? '';
$tgl1_kirim = $_GET['tgl1_kirim'] ?? '';
$tgl2_kirim = $_GET['tgl2_kirim'] ?? '';
$rec = $_GET['rec'] ?? '';
$progress_status = $_GET['status_order'] == '1' ? "AND PROGRESSSTATUS = '2'" : "";

$where_order = !empty($no_order) ? "NO_ORDER = '$no_order'" : "";
$where_date = (!empty($tgl1) && !empty($tgl2)) ? "DELIVERY BETWEEN '$tgl1' AND '$tgl2'" : "";
$where_date_order = (!empty($no_order) && !empty($tgl1) && !empty($tgl2)) ? "NO_ORDER = '$no_order' AND DELIVERY BETWEEN '$tgl1' AND '$tgl2'" : "";
$where_rec = $rec ? "AND im.JENIS_KAIN LIKE '%RECYCLED%'" : "";

if (!empty($tgl1_kirim) && !empty($tgl2_kirim)) {
      $sql = "SELECT DISTINCT
                im.ORDERDATE,
                im.PELANGGAN,
                im.NO_ORDER,
                im.NO_PO,
                im.KETERANGAN_PRODUCT,
                im.JENIS_KAIN,
                im.WARNA,
                im.NO_WARNA,
                im.DELIVERY,
                im.QTY_BAGIKAIN,
                im.QTY_BAGIKAIN_YD_MTR,
                im.NETTO,
                im.DELAY,
                im.NO_KK,
                im.DEMAND,
                im.ORDERLINE,
                im.PROGRESSSTATUS,
                im.PROGRESSSTATUS_DEMAND,
                im.KETERANGAN,
                isp.PROVISIONALCODE AS SURATJALAN,
                isp.GOODSISSUEDATE AS TGL_KIRIM
            FROM ITXVIEW_SURATJALAN_PPC_FOR_POSELESAI isp
            LEFT JOIN ITXVIEW_ALLOCATION_SURATJALAN_PPC iasp ON isp.CODE = iasp.CODE 
            LEFT JOIN ITXVIEW_MEMOPENTINGPPC im ON im.NO_KK = iasp.LOTCODE 
            WHERE isp.GOODSISSUEDATE BETWEEN '$tgl1_kirim' AND '$tgl2_kirim' $where_rec";
} else {
    $sql = "SELECT * FROM ITXVIEW_MEMOPENTINGPPC as im WHERE $where_order $where_date $where_date_order $where_rec";
}

$stmt = db2_exec($conn1, $sql);

echo "<table border='1'>";
echo "<tr>
        <th>No</th>
        <th>TGL KIRIM</th>
        <th>NO SURAT JALAN</th>
        <th>WARNA</th>
        <th>NO WARNA</th>
        <th>ROLL KIRIM</th>
        <th>QTY KIRIM (KG)</th>
        <th>PELANGGAN</th>
        <th>NO. ORDER</th>
        <th>NO. PO</th>
        <th>JENIS KAIN</th>
        <th>BRUTO/BAGI KAIN</th>
        <th>QTY PACKING</th>
        <th>NO DEMAND</th>
        <th>NO KARTU KERJA</th>
        <th>KETERANGAN</th>
      </tr>";

if ($stmt) {
    $no = 1;
    while ($row = db2_fetch_assoc($stmt)) {
        // Fetch roll data
        $q_roll_tdk_gabung = db2_exec($conn1, "SELECT count(*) AS ROLL, s2.PRODUCTIONORDERCODE
                                                FROM STOCKTRANSACTION s2 
                                                WHERE s2.ITEMTYPECODE ='KGF' AND s2.PRODUCTIONORDERCODE = '{$row['NO_KK']}'
                                                GROUP BY s2.PRODUCTIONORDERCODE");
        $d_roll_tdk_gabung = db2_fetch_assoc($q_roll_tdk_gabung);
        $roll = $d_roll_tdk_gabung['ROLL'];

        // Fetch QTY_KIRIM data
        $q_suratjalan = db2_exec($conn1, "SELECT 
            PRODUCTIONORDERCODE,
            PRODUCTIONDEMANDCODE,
            SURAT_JALAN,
            TGL_KIRIM,
            SUM(QTY_KIRIM_KG_DETAIL) AS QTY_KIRIM_KG,
            SUM(QTY_KIRIM_YARD_MTR_DETAIL) AS QTY_KIRIM_YARD_MTR,
            COUNT(LOTCODE) AS ROLL,
            FOC,
            PROGRESSSTATUS
        FROM (
            SELECT DISTINCT 
                p.PRODUCTIONORDERCODE,
                p.PRODUCTIONDEMANDCODE,
                isp.CODE,
                isp.PROVISIONALCODE AS SURAT_JALAN,
                isp.GOODSISSUEDATE AS TGL_KIRIM,
                iasp2.LINENUMBER,
                iasp2.BASEPRIMARYQUANTITY AS QTY_KIRIM_KG_DETAIL,
                iasp.LOTCODE,
                CASE
                    WHEN isp.PAYMENTMETHODCODE = 'FOC' THEN isp.PAYMENTMETHODCODE
                    ELSE ''
                END AS FOC,
                CASE
                    WHEN isp.PRICEUNITOFMEASURECODE = 'yd' THEN (iasp2.BASESECONDARYQUANTITY) 
                    WHEN isp.PRICEUNITOFMEASURECODE = 'kg' THEN (iasp2.BASESECONDARYQUANTITY)
                    WHEN isp.PRICEUNITOFMEASURECODE = 'm' THEN (round(iasp2.BASESECONDARYQUANTITY * 0.9144, 2))
                END AS QTY_KIRIM_YARD_MTR_DETAIL,
                isp.PROGRESSSTATUS
            FROM 
                PRODUCTIONDEMANDSTEP p
            LEFT JOIN PRODUCTIONDEMAND p2 ON p2.CODE = p.PRODUCTIONDEMANDCODE
            LEFT JOIN ITXVIEW_ALLOCATION_SURATJALAN_PPC iasp ON iasp.LOTCODE = p.PRODUCTIONORDERCODE 
            RIGHT JOIN ITXVIEW_SURATJALAN_PPC_FOR_POSELESAI isp ON isp.CODE = iasp.CODE 
                AND isp.DLVSALORDERLINESALESORDERCODE = p2.ORIGDLVSALORDLINESALORDERCODE  
                AND isp.DLVSALESORDERLINEORDERLINE = p2.ORIGDLVSALORDERLINEORDERLINE
            LEFT JOIN ITXVIEW_ALLOCATION_SURATJALAN_PPC iasp2 ON iasp2.CODE = isp.CODE
        )
        WHERE PRODUCTIONDEMANDCODE = '{$row['DEMAND']}' $progress_status
        GROUP BY 
            PRODUCTIONORDERCODE,
            PRODUCTIONDEMANDCODE,
            SURAT_JALAN,
            TGL_KIRIM,
            FOC,
            PROGRESSSTATUS");

        $d_suratjalan = db2_fetch_assoc($q_suratjalan);

               // Fetch BRUTO/BAGI KAIN data
        $q_orig_pd_code = db2_exec($conn1, "SELECT 
                                                *, a.VALUESTRING AS ORIGINALPDCODE
                                            FROM 
                                                PRODUCTIONDEMAND p 
                                            LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.FIELDNAME = 'OriginalPDCode'
                                            WHERE p.CODE = '{$row['DEMAND']}'");
        $d_orig_pd_code = db2_fetch_assoc($q_orig_pd_code);
        
        $q_cek_salinan = db2_exec($conn1, "SELECT 
                                               a2.VALUESTRING AS SALINAN_058
                                           FROM 
                                               PRODUCTIONDEMAND p 
                                           LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'DefectTypeCode'
                                           LEFT JOIN USERGENERICGROUP u ON u.CODE = a2.VALUESTRING AND u.USERGENERICGROUPTYPECODE = '006'
                                           WHERE p.CODE = '{$row['DEMAND']}'");
        $d_cek_salinan = db2_fetch_assoc($q_cek_salinan);
        
        // Fetch QTY_PACKING data
        $q_qtypacking = db2_exec($conn1, "SELECT * FROM ITXVIEW_QTYPACKING WHERE DEMANDCODE = '{$row['DEMAND']}'");
        $d_qtypacking = db2_fetch_assoc($q_qtypacking);
        
        echo "<tr>
                <td>{$no}</td>
                <td>{$row['TGL_KIRIM']}</td>
                <td>{$row['SURATJALAN']}</td>
                <td>{$row['WARNA']}</td>
                <td>{$row['NO_WARNA']}</td>
                <td>{$roll}</td>
                <td>{$d_suratjalan['QTY_KIRIM_KG']}</td>
                <td>{$row['PELANGGAN']}</td>
                <td>{$row['NO_ORDER']}</td>
                <td>{$row['NO_PO']}</td>
                <td>{$row['JENIS_KAIN']}</td>
                <td>";
        if ($d_orig_pd_code['ORIGINALPDCODE']) {
            if ($d_cek_salinan['SALINAN_058'] == '058') {
                echo number_format($row['QTY_BAGIKAIN'], 2);
            } else {
                echo "0";
            }
        } else {
            echo number_format($row['QTY_BAGIKAIN'], 2);
        }
        echo "</td>
                <td>";
        if (!empty(cek($d_qtypacking['QTY_PACKING']))) {
            echo number_format($d_qtypacking['QTY_PACKING'], 2);
        } else {
            echo "0";
        }
        echo "</td>
                <td>{$row['DEMAND']}</td>
                <td>{$row['NO_KK']}</td>
                <td>{$row['KETERANGAN']}</td>
              </tr>";
        $no++;
        }
        }
        echo "</table>";
        exit();
?>
<?php
require_once "koneksi.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Ambil filter
$subcode01 = $_GET['subcode01'] ?? '';
$suffix    = $_GET['suffix'] ?? '';

if ($subcode01 && $suffix) {
    $WHERE = "WHERE p.SUFFIXCODE LIKE '%$suffix%' AND p.SUBCODE01 LIKE '%$subcode01%'";
} elseif (empty($subcode01) && $suffix) {
    $WHERE = "WHERE p.SUFFIXCODE LIKE '%$suffix%'";
} elseif ($subcode01 && empty($suffix)) {
    $WHERE = "WHERE p.SUBCODE01 LIKE '%$subcode01%'";
} else {
    $WHERE = '';
}

$sql = "SELECT 
            p.CREATIONDATETIME,
            i.DEAMAND,
            p.PRODUCTIONORDERCODE,
            i2.USEDUSERPRIMARYQUANTITY AS QTY_BAGIKAIN,
            p.SUBCODE01, 
            p.SUFFIXCODE,
            i.LOT
        FROM 
            PRODUCTIONORDERRESERVATION p
        LEFT JOIN ITXVIEWKK i ON i.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE 
        LEFT JOIN PRODUCTIONRESERVATION i2 ON i2.ORDERCODE = i.DEAMAND AND i2.ITEMTYPEAFICODE = 'KGF'
        $WHERE
        ORDER BY p.PRODUCTIONORDERCODE DESC";

$stmt = db2_exec($conn1, $sql);

// Buat spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Kartu Kerja By Suffix');

// Header kolom
$headers = [
    'CREATION DATE TIME',
    'PRODUCTION DEMAND',
    'PRODUCTION ORDER',
    'POSISI TERAKHIR',
    'QTY BAGIKAIN',
    'RCODE',
    'SUFFIX CODE',
    'LOT',
    'NO MESIN',
    'NO URUT'
];
$sheet->fromArray($headers, null, 'A1');

// Isi data
$row = 2;
while ($r = db2_fetch_assoc($stmt)) {
    set_time_limit(300);

    // ambil status terakhir
    $q_StatusTerakhir = db2_exec($conn1, "
        SELECT p.LONGDESCRIPTION
        FROM PRODUCTIONDEMANDSTEP p
        LEFT JOIN WORKCENTER wc ON wc.CODE = p.WORKCENTERCODE
        WHERE p.PRODUCTIONORDERCODE = '{$r['PRODUCTIONORDERCODE']}'
          AND p.PRODUCTIONDEMANDCODE = '{$r['DEAMAND']}'
          AND p.PROGRESSSTATUS IN ('0','1','2')
        ORDER BY p.GROUPSTEPNUMBER ASC
        FETCH FIRST 1 ROWS ONLY
    ");
    $d_StatusTerakhir = db2_fetch_assoc($q_StatusTerakhir);

    // ambil status terakhir DYE
    $q_StatusTerakhirDye = db2_exec($conn1, "
        SELECT GROUPSTEPNUMBER
        FROM PRODUCTIONDEMANDSTEP
        WHERE TRIM(OPERATIONCODE) IN ('DYE1','DYE2','DYE2-T1','DYE3','DYE4','DYE5','DYE6','DYE7')
          AND PRODUCTIONORDERCODE = '{$r['PRODUCTIONORDERCODE']}'
          AND PRODUCTIONDEMANDCODE = '{$r['DEAMAND']}'
        ORDER BY GROUPSTEPNUMBER DESC
        FETCH FIRST 1 ROWS ONLY
    ");
    $d_StatusTerakhirDye = db2_fetch_assoc($q_StatusTerakhirDye);

    // ambil groupStepNumber
    $q_groupStepNumber = db2_exec($conn1, "
        SELECT p.PRODUCTIONORDERCODE
        FROM PRODUCTIONDEMANDSTEP p
        WHERE p.PRODUCTIONORDERCODE = '{$r['PRODUCTIONORDERCODE']}'
          AND p.PRODUCTIONDEMANDCODE = '{$r['DEAMAND']}'
          AND p.PROGRESSSTATUS IN ('0','1','2')
          AND p.GROUPSTEPNUMBER <= '{$d_StatusTerakhirDye['GROUPSTEPNUMBER']}'
        ORDER BY p.GROUPSTEPNUMBER DESC
        FETCH FIRST 1 ROWS ONLY
    ");
    $d_groupStepNumber = db2_fetch_assoc($q_groupStepNumber);

    // skip kalau tidak ada hasil
    if (!$d_groupStepNumber) continue;

    $q_schedule_dye     = mysqli_query($con_db_dyeing, "SELECT * FROM `tbl_schedule` WHERE nokk = '$r[PRODUCTIONORDERCODE]' AND NOT `status` = 'selesai'");
    $data_schedule_dye  = mysqli_fetch_assoc($q_schedule_dye);
    $nomesin            = $data_schedule_dye['no_mesin'];
    $nourut             = $data_schedule_dye['no_urut'];

    // Tambahkan data ke sheet
    $sheet->fromArray([
        $r['CREATIONDATETIME'],
        $r['DEAMAND'],
        $r['PRODUCTIONORDERCODE'],
        $d_StatusTerakhir['LONGDESCRIPTION'] ?? '-',
        $r['QTY_BAGIKAIN'],
        $r['SUBCODE01'],
        $r['SUFFIXCODE'],
        $r['LOT'],
        $nomesin,
        $nourut
    ], null, "A{$row}");

    $row++;
}

// Styling header
$sheet->getStyle('A1:H1')->getFont()->setBold(true);
$sheet->getStyle('A1:H1')->getAlignment()->setHorizontal('center');
foreach (range('A', 'H') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Format general di semua cell
$sheet->getStyle('A:H')->getNumberFormat()
    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL);

// Output file
$filename = "DYE - Kartu Kerja By Suffix " . date('Ymd_His') . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>

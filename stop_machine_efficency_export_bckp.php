<?php

require 'vendor/autoload.php';
require_once "koneksi.php";
require_once "utils/helper.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet       = $spreadsheet->getActiveSheet();

$data = [];

$sqlMachine  = "SELECT * FROM Machines";
$stmtMachine = $pdo_orgatex->prepare($sqlMachine);
$stmtMachine->execute();
$machines = $stmtMachine->fetchAll(PDO::FETCH_ASSOC);

$now = new DateTime();

$startDate = $now;
$endDate   = $now;

if (isset($_POST['tgl']) && isset($_POST['time']) && isset($_POST['tgl2']) && isset($_POST['time2'])) {
    if ($_POST['tgl'] && $_POST['time']) {
        $startDate = $_POST['tgl'] . ' ' . $_POST['time'];
    }

    if ($_POST['tgl2'] && $_POST['time2']) {
        $endDate = $_POST['tgl2'] . ' ' . $_POST['time2'];
    }
}

if (isset($_POST['time_range'])) {
    $time_range = $_POST['time_range'];

    if ($time_range != 'custom') {
        switch ($time_range) {
            case '24_hours':
                $start_date = clone $now;
                $start_date->modify('-24 hours');
                $end_date  = $now;
                $startDate = $start_date->format('Y-m-d H:i:s');
                $endDate   = $end_date->format('Y-m-d H:i:s');
                break;

            case 'week':
                $start_date = clone $now;
                $start_date->modify('-1 week');
                $end_date  = $now;
                $startDate = $start_date->format('Y-m-d H:i:s');
                $endDate   = $end_date->format('Y-m-d H:i:s');
                break;

            case 'month':
                $start_date = clone $now;
                $start_date->modify('-1 month');
                $end_date  = $now;
                $startDate = $start_date->format('Y-m-d H:i:s');
                $endDate   = $end_date->format('Y-m-d H:i:s');
                break;

            default:
                echo "Please select a valid option.";
        }
    }
}

$dataHeader = [];

foreach ($machines as $machine) {
    $machineID = $machine['MachineNo'];

    $sqlLogs = "SELECT LogTimeStamp as logTimeStamp,
                    MachineProtocol.AlarmNo as value,
                    AlarmList.AlarmText as reason
                    FROM MachineProtocol
                    LEFT JOIN AlarmList ON AlarmList.AlarmNo = MachineProtocol.AlarmNo
                    WHERE MachineProtocol.Machine = :machineID AND
                    MachineProtocol.LogTimeStamp BETWEEN :startDate AND :endDate
                    ORDER BY MachineProtocol.LogTimeStamp";

    $stmtLogs = $pdo_orgatex->prepare($sqlLogs);
    $stmtLogs->bindParam(':machineID', $machineID);
    $stmtLogs->bindParam(':startDate', $startDate);
    $stmtLogs->bindParam(':endDate', $endDate);
    $stmtLogs->execute();

    $rows = $stmtLogs->fetchAll(PDO::FETCH_ASSOC);

    $totalSeconds = 0;
    foreach ($rows as $key => $row) {
        if ($row['value'] > 500 && isset($rows[$key + 1]) && $rows[$key + 1]['value'] == 0) {
            $date1    = new DateTime($row['logTimeStamp']);
            $date2    = new DateTime($rows[$key + 1]['logTimeStamp']);
            $interval = $date1->diff($date2);

            $seconds = ($interval->days * 24 * 60 * 60) +
            ($interval->h * 60 * 60) +
            ($interval->i * 60) +
            $interval->s;

            $totalSeconds += $seconds;
        }
    }

    $hours     = floor($totalSeconds / 3600);
    $minutes   = floor(($totalSeconds % 3600) / 60);
    $seconds   = $totalSeconds % 60;
    $totalStop = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

    $startDateTime    = new DateTime($startDate);
    $endDateTime      = new DateTime($endDate);
    $timeSpanInterval = $startDateTime->diff($endDateTime);

    $totalSecondsCalculate = ($timeSpanInterval->days * 24 * 60 * 60) +
    ($timeSpanInterval->h * 60 * 60) +
    ($timeSpanInterval->i * 60) +
    $timeSpanInterval->s;

    if ($totalSeconds > 0 && $totalSecondsCalculate > 0) {
        $totalPercentage = (($totalSecondsCalculate - $totalSeconds) / $totalSecondsCalculate) * 100;
        $statusMachine   = true;
    } else {
        $totalPercentage = 0;
        $statusMachine   = false;
    }

    $sqlScheduleDye    = "SELECT * FROM tbl_mesin WHERE no_mesin_lama = '$machineID'";
    $resultScheduleDye = mysqli_query($con_db_dyeing, $sqlScheduleDye);

    if (mysqli_num_rows($resultScheduleDye) > 0) {
        $dataSchedule = mysqli_fetch_assoc($resultScheduleDye);
        $machineIDNew = $dataSchedule['no_mesin_baru'];

        $resultDataMachine = mysqli_query($con_db_dyeing, "SELECT
                                    kapasitas as machine_capacity,
                                    ket as machine_description
                                FROM
                                    tbl_mesin
                                WHERE
                                    no_mesin = '$machineIDNew'");

        if (mysqli_num_rows($resultDataMachine) > 0) {
            $row                 = mysqli_fetch_assoc($resultDataMachine);
            $machine_capacity    = $row["machine_capacity"];
            $machine_description = $row["machine_description"];
        } else {
            $machine_capacity    = null;
            $machine_description = null;
        }
    } else {
        $machineIDNew = null;
    }

    $dataHeader[] = [
        "machine_number"           => $machineID,
        "machine_number_new"       => $machineIDNew,
        "machine_capacity"         => $machine_capacity,
        "machine_description"      => $machine_description,
        "total_stop"               => $totalStop,
        "total_percentage_running" => round($totalPercentage, 2),
        "status_machine"           => $statusMachine,
    ];

    $dataDetail = [];

    // $machineID = $_POST['machine_id'];

    $sqlLogs = "SELECT Dyelots.Dyelot AS dyelot, LogTimeStamp as logTimeStamp,
    MachineProtocol.AlarmNo as value,
    AlarmList.AlarmText as reason
    FROM MachineProtocol
    LEFT JOIN AlarmList ON AlarmList.AlarmNo = MachineProtocol.AlarmNo
    LEFT JOIN Dyelots ON Dyelots.DyelotRefNo = MachineProtocol.DyelotRefNo
    WHERE MachineProtocol.Machine = :machineID AND
    MachineProtocol.LogTimeStamp BETWEEN :startDate AND :endDate
    ORDER BY MachineProtocol.LogTimeStamp";

    $stmtLogs = $pdo_orgatex->prepare($sqlLogs);
    $stmtLogs->bindParam(':machineID', $machineID);
    $stmtLogs->bindParam(':startDate', $startDate);
    $stmtLogs->bindParam(':endDate', $endDate);
    $stmtLogs->execute();

    $rows = $stmtLogs->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $key => $row) {
        if ($row['value'] > 500 && isset($rows[$key + 1]) && $rows[$key + 1]['value'] == 0) {
            $date1  = new DateTime($row['logTimeStamp']);
            $date2  = new DateTime($rows[$key + 1]['logTimeStamp']);
            $dyelot = $row['dyelot'];

            $log_timestamp_start = $date1->format('Y-m-d H:i:s');
            $log_timestamp_stop  = $date2->format('Y-m-d H:i:s');

            $interval = $date1->diff($date2);

            $seconds = ($interval->days * 24 * 60 * 60) +
            ($interval->h * 60 * 60) +
            ($interval->i * 60) +
            $interval->s;

            $totalSeconds = $seconds;
            $totalStop    = '00:00:00';

            if (! empty($totalSeconds)) {
                $reason        = $row['reason'];
                $log_timestamp = $row['logTimeStamp'];

                $hours     = floor($totalSeconds / 3600);
                $minutes   = floor(($totalSeconds % 3600) / 60);
                $seconds   = $totalSeconds % 60;
                $totalStop = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

                $dataDetail[] = [
                    "dyelot"              => $dyelot,
                    "machine_number"      => $machineID,
                    "log_timestamp"       => $log_timestamp,
                    "log_timestamp_start" => $log_timestamp_start,
                    "log_timestamp_stop"  => $log_timestamp_stop,
                    "reason"              => $reason,
                    "total_stop"          => $totalStop,
                ];
            }
        }
    }

    $data[] = [
        'header' => $dataHeader,
        'detail' => $dataDetail,
    ];
}

// Data Header dan Detail (Sample)
// $data = [
//     [
//         'header' => [
//             'Ip Address'                    => '2638',
//             'Machine Number New'            => '2631',
//             'Machine Capacity'              => '10',
//             'Machine Description'           => 'TW SK 10 kg',
//             'Total Stop'                    => '00:00:04',
//             'Presentase Efficiency Machine' => '100.00%',
//         ],
//         'detail' => [
//             [
//                 'Dyelot'     => '187622',
//                 'Ip Address' => '2638',
//                 'Start Stop' => '2025-01-16 17:35:47',
//                 'End Stop'   => '2025-01-16 17:35:48',
//                 'Total Stop' => '00:00:01',
//                 'Reason'     => 'STOP by operator',
//             ],
//             [
//                 'Dyelot'     => '187622',
//                 'Ip Address' => '2638',
//                 'Start Stop' => '2025-01-17 07:04:55',
//                 'End Stop'   => '2025-01-17 07:04:55',
//                 'Total Stop' => '00:00:01',
//                 'Reason'     => 'STOP by operator',
//             ],
//         ],
//     ],
//     [
//         'header' => [
//             'Ip Address'                    => '2638',
//             'Machine Number New'            => '2631',
//             'Machine Capacity'              => '10',
//             'Machine Description'           => 'TW SK 10 kg',
//             'Total Stop'                    => '00:00:04',
//             'Presentase Efficiency Machine' => '100.00%',
//         ],
//         'detail' => [
//             [
//                 'Dyelot'     => '187622',
//                 'Ip Address' => '2638',
//                 'Start Stop' => '2025-01-16 17:35:47',
//                 'End Stop'   => '2025-01-16 17:35:48',
//                 'Total Stop' => '00:00:01',
//                 'Reason'     => 'STOP by operator',
//             ],
//             [
//                 'Dyelot'     => '187622',
//                 'Ip Address' => '2638',
//                 'Start Stop' => '2025-01-17 07:04:55',
//                 'End Stop'   => '2025-01-17 07:04:55',
//                 'Total Stop' => '00:00:01',
//                 'Reason'     => 'STOP by operator',
//             ],
//         ],
//     ],
//     [
//         'header' => [
//             'Ip Address'                    => '2638',
//             'Machine Number New'            => '2631',
//             'Machine Capacity'              => '10',
//             'Machine Description'           => 'TW SK 10 kg',
//             'Total Stop'                    => '00:00:04',
//             'Presentase Efficiency Machine' => '100.00%',
//         ],
//         'detail' => [
//             [
//                 'Dyelot'     => '187622',
//                 'Ip Address' => '2638',
//                 'Start Stop' => '2025-01-16 17:35:47',
//                 'End Stop'   => '2025-01-16 17:35:48',
//                 'Total Stop' => '00:00:01',
//                 'Reason'     => 'STOP by operator',
//             ],
//             [
//                 'Dyelot'     => '187622',
//                 'Ip Address' => '2638',
//                 'Start Stop' => '2025-01-17 07:04:55',
//                 'End Stop'   => '2025-01-17 07:04:55',
//                 'Total Stop' => '00:00:01',
//                 'Reason'     => 'STOP by operator',
//             ],
//         ],
//     ],
// ];

// Style untuk Header Utama
$headerMainStyle = [
    'fill'    => [
        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FF00B050'], // Hijau untuk "Header"
    ],
    'font'    => [
        'bold' => true,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

// Style untuk Detail Header
$detailHeaderStyle = [
    'fill'    => [
        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FF00B050'], // Hijau untuk "Header"
    ],
    'font'    => [
        'bold' => true,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

// Style untuk Detail Header
$detailTitleStyle = [
    'fill'    => [
        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFFFFF00'], // Kuning untuk "Detail"
    ],
    'font'    => [
        'bold' => true,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

// Style untuk Semua Border
$detailStyle = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

// Style Bold
$titleHeaderStyle = [
    'font'    => [
        'bold' => true,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

// Style untuk Alignment Center
$centerAlignStyle = [
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
];

// Style untuk Alignment Left
$leftAlignStyle = [
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    ],
];

$row = 1;

foreach ($data as $block) {
    // Header
    $sheet->setCellValue("A{$row}", 'Header');
    $sheet->mergeCells("A{$row}:F{$row}");
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($headerMainStyle);
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($centerAlignStyle);
    $row++;

    // Title Header
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($detailStyle);

    // Value Header
    foreach ($block['header'] as $key => $value) {
        $sheet->setCellValue("A{$row}", $key);
        $sheet->setCellValue("B{$row}", $value);
        $sheet->mergeCells("B{$row}:F{$row}");
        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($detailStyle);
        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($leftAlignStyle);
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);

        $row++;
    }

    // Detail
    $sheet->setCellValue("A{$row}", 'Detail');
    $sheet->mergeCells("A{$row}:F{$row}");
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($detailHeaderStyle);
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($centerAlignStyle);
    $row++;

    // Title Detail
    $sheet->setCellValue("A{$row}", 'Dyelot');
    $sheet->setCellValue("B{$row}", 'Ip Address');
    $sheet->setCellValue("C{$row}", 'Start Stop');
    $sheet->setCellValue("D{$row}", 'End Stop');
    $sheet->setCellValue("E{$row}", 'Total Stop');
    $sheet->setCellValue("F{$row}", 'Reason');
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($detailTitleStyle);
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($centerAlignStyle);
    $row++;

    // Value Detail
    foreach ($block['detail'] as $detail) {
        $sheet->setCellValue("A{$row}", $detail['Dyelot']);
        $sheet->setCellValue("B{$row}", $detail['Ip Address']);
        $sheet->setCellValue("C{$row}", $detail['Start Stop']);
        $sheet->setCellValue("D{$row}", $detail['End Stop']);
        $sheet->setCellValue("E{$row}", $detail['Total Stop']);
        $sheet->setCellValue("F{$row}", $detail['Reason']);
        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($detailStyle);
        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($centerAlignStyle);
        $row++;
    }

    $row++; // Baris kosong setelah setiap blok
}

// Atur lebar kolom otomatis
foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Simpan file
$writer   = new Xlsx($spreadsheet);
$filename = 'stop_machine_efficency.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"{$filename}\"");
header('Cache-Control: max-age=0');
$writer->save('php://output');

<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet       = $spreadsheet->getActiveSheet();

// Data Header dan Detail (Contoh)
$data = [
    [
        'header' => [
            'Ip Address'                    => '2638',
            'Machine Number New'            => '2631',
            'Machine Capacity'              => '10',
            'Machine Description'           => 'TW SK 10 kg',
            'Total Stop'                    => '00:00:04',
            'Presentase Efficiency Machine' => '100.00%',
        ],
        'detail' => [
            [
                'Dyelot'     => '187622',
                'Ip Address' => '2638',
                'Start Stop' => '2025-01-16 17:35:47',
                'End Stop'   => '2025-01-16 17:35:48',
                'Total Stop' => '00:00:01',
                'Reason'     => 'STOP by operator',
            ],
            [
                'Dyelot'     => '187622',
                'Ip Address' => '2638',
                'Start Stop' => '2025-01-17 07:04:55',
                'End Stop'   => '2025-01-17 07:04:55',
                'Total Stop' => '00:00:01',
                'Reason'     => 'STOP by operator',
            ],
        ],
    ],
    [
        'header' => [
            'Ip Address'                    => '2638',
            'Machine Number New'            => '2631',
            'Machine Capacity'              => '10',
            'Machine Description'           => 'TW SK 10 kg',
            'Total Stop'                    => '00:00:04',
            'Presentase Efficiency Machine' => '100.00%',
        ],
        'detail' => [
            [
                'Dyelot'     => '187622',
                'Ip Address' => '2638',
                'Start Stop' => '2025-01-16 17:35:47',
                'End Stop'   => '2025-01-16 17:35:48',
                'Total Stop' => '00:00:01',
                'Reason'     => 'STOP by operator',
            ],
            [
                'Dyelot'     => '187622',
                'Ip Address' => '2638',
                'Start Stop' => '2025-01-17 07:04:55',
                'End Stop'   => '2025-01-17 07:04:55',
                'Total Stop' => '00:00:01',
                'Reason'     => 'STOP by operator',
            ],
        ],
    ],
    [
        'header' => [
            'Ip Address'                    => '2638',
            'Machine Number New'            => '2631',
            'Machine Capacity'              => '10',
            'Machine Description'           => 'TW SK 10 kg',
            'Total Stop'                    => '00:00:04',
            'Presentase Efficiency Machine' => '100.00%',
        ],
        'detail' => [
            [
                'Dyelot'     => '187622',
                'Ip Address' => '2638',
                'Start Stop' => '2025-01-16 17:35:47',
                'End Stop'   => '2025-01-16 17:35:48',
                'Total Stop' => '00:00:01',
                'Reason'     => 'STOP by operator',
            ],
            [
                'Dyelot'     => '187622',
                'Ip Address' => '2638',
                'Start Stop' => '2025-01-17 07:04:55',
                'End Stop'   => '2025-01-17 07:04:55',
                'Total Stop' => '00:00:01',
                'Reason'     => 'STOP by operator',
            ],
        ],
    ],
];

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

// Awal baris
$row = 1;

foreach ($data as $block) {
    // Header
    $sheet->setCellValue("A{$row}", 'Header');
    $sheet->mergeCells("A{$row}:F{$row}");
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($headerMainStyle);
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($centerAlignStyle);
    $row++;

    foreach ($block['header'] as $key => $value) {
        $sheet->setCellValue("A{$row}", $key);
        $sheet->setCellValue("B{$row}", $value);
        $sheet->mergeCells("B{$row}:F{$row}");
        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($detailStyle);
        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($leftAlignStyle);
        $row++;
    }

    // Detail Header
    $sheet->setCellValue("A{$row}", 'Detail');
    $sheet->mergeCells("A{$row}:F{$row}");
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($detailHeaderStyle);
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($centerAlignStyle);
    $row++;

    // Kolom untuk Detail
    $sheet->setCellValue("A{$row}", 'Dyelot');
    $sheet->setCellValue("B{$row}", 'Ip Address');
    $sheet->setCellValue("C{$row}", 'Start Stop');
    $sheet->setCellValue("D{$row}", 'End Stop');
    $sheet->setCellValue("E{$row}", 'Total Stop');
    $sheet->setCellValue("F{$row}", 'Reason');
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($detailTitleStyle);
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray($centerAlignStyle);
    $row++;

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

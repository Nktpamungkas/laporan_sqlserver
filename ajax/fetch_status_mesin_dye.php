<?php
require_once "../koneksi.php";
header('Content-Type: application/json');

$query = "WITH KAINAKJ AS (
          SELECT 
            s2.SALESORDERCODE,
            s2.ORDERLINE,
            a3.VALUESTRING
          FROM 
            SALESORDERLINE s2
          LEFT JOIN ADSTORAGE a3 ON a3.UNIQUEID = s2.ABSUNIQUEID AND a3.FIELDNAME = 'KainAKJ'
          WHERE 
            a3.VALUESTRING IN ('0', '2')
        )
        SELECT DISTINCT 
          TRIM(p2.PRODUCTIONORDERCODE) AS PRODUCTIONORDERCODE,
        --	TRIM(p.CODE) AS PRODUCTIONDEMAND,
          a.VALUESTRING AS NOMOR_MESIN,
          p.PROGRESSSTATUS,
          a2.VALUESTRING AS ORIGINALPDCODE,
          p.CREATIONUSER,
          s.TEMPLATECODE,
          s.CODE,
          k_akj.VALUESTRING AS KAINAKJ,
          p3.CREATIONDATETIME
        FROM 
          PRODUCTIONDEMAND p 
        LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.FIELDNAME = 'DYEMachineNoCode'
        LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
        LEFT JOIN SALESORDER s ON s.CODE = p.ORIGDLVSALORDLINESALORDERCODE 
        LEFT JOIN KAINAKJ k_akj ON k_akj.SALESORDERCODE = s.CODE AND k_akj.ORDERLINE = p.ORIGDLVSALORDERLINEORDERLINE
        LEFT JOIN (
                SELECT
                    DISTINCT PRODUCTIONDEMANDCODE,
                    PRODUCTIONORDERCODE
                FROM
                    PRODUCTIONDEMANDSTEP p2 
            ) p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
        LEFT JOIN PRODUCTIONORDER p3 ON p3.CODE = p2.PRODUCTIONORDERCODE
        WHERE 	
          a.VALUESTRING IS NOT NULL
          AND NOT p.PROGRESSSTATUS = '6'
          AND (TRIM(p.CREATIONUSER) = 'yogi.rahmansyah' OR a2.VALUESTRING IS NULL)
          AND s.TEMPLATECODE IN ('DOM', 'EXP', 'SME', 'SAM', 'REP', 'RPE', 'OPN', 'DMB', 'MNB', 'TBG', 'RBG')
          AND p2.PRODUCTIONORDERCODE IS NOT NULL
          AND NOT p3.PROGRESSSTATUS = '6'
        ORDER BY 
          p3.CREATIONDATETIME ASC";
$result = db2_exec($conn1, $query);

if (!$result) {
  echo json_encode(["error" => db2_stmt_errormsg()]);
  exit;
}

$data_status = [
  'SUDAH BAGI KAIN' => [],
  'BELUM BAGI KAIN' => []
];

while ($row = db2_fetch_assoc($result)) {
  $productionOrder = trim($row['PRODUCTIONORDERCODE']);
  $machine = trim($row['NOMOR_MESIN']);

  if ($productionOrder === '' || $machine === '') continue;

  $subQuery = " SELECT DISTINCT 
                  ipkk.PRODUCTIONORDERCODE,
                  CASE
                      -- BELUM BAGI KAIN
                      WHEN 
                          MAX(CASE WHEN OPERATIONCODE = 'BAT1' AND STATUS_OPERATION = 'Entered' THEN 1 ELSE 0 END) = 1
                          AND 
                          SUM(CASE WHEN OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','DYE6') 
                                  AND STATUS_OPERATION <> 'Entered' THEN 1 ELSE 0 END) = 0
                      THEN 'BELUM BAGI KAIN'
                      -- SUDAH BAGI KAIN
                      WHEN 
                          MAX(CASE WHEN OPERATIONCODE = 'BAT1' AND STATUS_OPERATION IN ('Progress','Closed') THEN 1 ELSE 0 END) = 1
                          AND 
                          SUM(CASE WHEN OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','DYE6') 
                                  AND STATUS_OPERATION <> 'Entered' THEN 1 ELSE 0 END) = 0
                      THEN 'SUDAH BAGI KAIN'
                      -- SELESAI (semuanya closed)
                      WHEN 
                          MAX(CASE WHEN OPERATIONCODE = 'BAT1' AND STATUS_OPERATION = 'Closed' THEN 1 ELSE 0 END) = 1
                          AND 
                          SUM(CASE WHEN OPERATIONCODE IN ('DYE1','DYE2','DYE3','DYE4','DYE5','DYE6') 
                                  AND STATUS_OPERATION <> 'Closed' THEN 1 ELSE 0 END) = 0
                      THEN 'SELESAI'
                      -- Kondisi lain dianggap tidak normal
                      ELSE 'TIDAK NORMAL'
                  END AS STATUS_BAGI_KAIN
              FROM 
                  ITXVIEW_POSISI_KARTU_KERJA ipkk
              WHERE 
                  ipkk.PRODUCTIONORDERCODE = '$productionOrder'
                  AND ipkk.OPERATIONCODE IN ('BAT1','DYE1','DYE2','DYE3','DYE4','DYE5','DYE6')
              GROUP BY 
                  ipkk.PRODUCTIONORDERCODE,
                  ipkk.PRODUCTIONDEMANDCODE";

  $subResult = db2_exec($conn1, $subQuery);
  if (!$subResult) continue;

    // Ambil hasil status
  $status = null;

  while ($subRow = db2_fetch_assoc($subResult)) {
    $status = trim($subRow['STATUS_BAGI_KAIN']);
  }

  // Hanya ambil 2 status yang relevan
  if ($status === 'SUDAH BAGI KAIN' || $status === 'BELUM BAGI KAIN') {
    if (!isset($data_status[$status][$machine])) {
      $data_status[$status][$machine] = 0;
    }
    $data_status[$status][$machine]++;
  }
}

$output = [
  'dataSudahBagiKain' => [],
  'dataBelumBagiKain' => []
];

foreach ($data_status['SUDAH BAGI KAIN'] as $machine => $count) {
  $output['dataSudahBagiKain'][] = ['machine' => $machine, 'count' => $count];
}

foreach ($data_status['BELUM BAGI KAIN'] as $machine => $count) {
  $output['dataBelumBagiKain'][] = ['machine' => $machine, 'count' => $count];
}

echo json_encode($output, JSON_PRETTY_PRINT);
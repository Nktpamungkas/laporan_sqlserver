<?php
require_once "../koneksi.php";
header('Content-Type: application/json');

$queryResource = "SELECT 
                    r.BATHVOLUME, 
                    r.CODE, 
                    r.SHORTDESCRIPTION 
                  FROM RESOURCES r 
                  WHERE CODE LIKE 'P3%' AND TYPE = 2
                  ORDER BY r.BATHVOLUME DESC";
$resultResource = db2_exec($conn1, $queryResource);
$machineCapacities = [];
while ($row = db2_fetch_assoc($resultResource)) {
    // Hilangkan koma dari angka (contoh: 3,200 -> 3200)
    $volume = str_replace(',', '', $row['BATHVOLUME']);
    $machineCapacities[trim($row['CODE'])] = (int)$volume;
}

if (!$resultResource) {
  echo json_encode(["error" => db2_stmt_errormsg()]);
  exit;
}

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
          ),
          BRUTO_PER_CODE AS (
              SELECT 
                  pd.CODE,
                  SUM(a3.VALUEDECIMAL) AS BRUTO
              FROM PRODUCTIONDEMAND pd
              LEFT JOIN ADSTORAGE a3 
                  ON a3.UNIQUEID = pd.ABSUNIQUEID
                AND a3.FIELDNAME = 'OriginalBruto'
              GROUP BY pd.CODE
          )
          SELECT DISTINCT 
            TRIM(p2.PRODUCTIONORDERCODE) AS PRODUCTIONORDERCODE,
            LISTAGG(DISTINCT TRIM(p.CODE), ', ') AS PRODUCTIONDEMAND,
            TRIM(p.SUBCODE02) || '' || TRIM(p.SUBCODE03) AS ITEM,
            itxcolor.WARNA AS WARNA,
            a.VALUESTRING AS NOMOR_MESIN,
            p.PROGRESSSTATUS,
            a2.VALUESTRING AS ORIGINALPDCODE,
            s.TEMPLATECODE,
            LISTAGG(DISTINCT TRIM(s.CODE), ', ') AS CODE,
            k_akj.VALUESTRING AS KAINAKJ,
            SUM(DISTINCT bp.BRUTO) AS BRUTO,
            LISTAGG(DISTINCT TRIM(sd.DELIVERYDATE), ', ') AS DEL_INTERNAL,
            LISTAGG(DISTINCT TRIM(p.DESCRIPTION), ', ') AS LOT
          FROM 
            PRODUCTIONDEMAND p 
          LEFT JOIN ADSTORAGE a ON a.UNIQUEID = p.ABSUNIQUEID AND a.FIELDNAME = 'DYEMachineNoCode'
          LEFT JOIN ADSTORAGE a2 ON a2.UNIQUEID = p.ABSUNIQUEID AND a2.FIELDNAME = 'OriginalPDCode'
          LEFT JOIN BRUTO_PER_CODE bp ON bp.CODE = p.CODE
          LEFT JOIN SALESORDER s ON s.CODE = p.ORIGDLVSALORDLINESALORDERCODE
          LEFT JOIN ITXVIEWCOLOR AS itxcolor ON 
            p.SUBCODE01 = itxcolor.SUBCODE01 
            AND p.SUBCODE02 = itxcolor.SUBCODE02 
            AND p.SUBCODE03 = itxcolor.SUBCODE03
            AND p.SUBCODE04 = itxcolor.SUBCODE04
            AND p.SUBCODE05 = itxcolor.SUBCODE05 
          LEFT JOIN KAINAKJ k_akj ON k_akj.SALESORDERCODE = s.CODE AND k_akj.ORDERLINE = p.ORIGDLVSALORDERLINEORDERLINE
          LEFT JOIN (
                  SELECT
                      DISTINCT PRODUCTIONDEMANDCODE,
                      PRODUCTIONORDERCODE
                  FROM
                      PRODUCTIONDEMANDSTEP p2 
              ) p2 ON p2.PRODUCTIONDEMANDCODE = p.CODE 
          LEFT JOIN PRODUCTIONORDER p3 ON p3.CODE = p2.PRODUCTIONORDERCODE
          LEFT JOIN SALESORDERDELIVERY sd ON sd.SALESORDERLINESALESORDERCODE = p.ORIGDLVSALORDLINESALORDERCODE
            AND sd.SALESORDERLINEORDERLINE = p.DLVSALESORDERLINEORDERLINE
          WHERE 	
            a.VALUESTRING IS NOT NULL
            AND NOT p.PROGRESSSTATUS = '6'
            AND (TRIM(p.CREATIONUSER) = 'yogi.rahmansyah' OR a2.VALUESTRING IS NULL)
            AND s.TEMPLATECODE IN ('DOM', 'EXP', 'SME', 'SAM', 'REP', 'RPE', 'OPN', 'DMB', 'MNB', 'TBG', 'RBG')
            AND p2.PRODUCTIONORDERCODE IS NOT NULL
            AND NOT p3.PROGRESSSTATUS = '6'
          GROUP BY 
            p2.PRODUCTIONORDERCODE,
            TRIM(p.SUBCODE02) || '' || TRIM(p.SUBCODE03),
            itxcolor.WARNA,
            a.VALUESTRING,
            p.PROGRESSSTATUS,
            a2.VALUESTRING,
            s.TEMPLATECODE,
            k_akj.VALUESTRING";
$result = db2_exec($conn1, $query);

if (!$result) {
  echo json_encode(["error" => db2_stmt_errormsg()]);
  exit;
}

$data_status = [
  'SUDAH BAGI KAIN' => [],
  'BELUM BAGI KAIN' => []
];

$seenOrders = []; // 🧠 untuk melacak production order yang sudah diproses

while ($row = db2_fetch_assoc($result)) {
  $productionOrder = trim($row['PRODUCTIONORDERCODE']);
  $machine = trim($row['NOMOR_MESIN']);

  // jika sudah pernah diproses, skip
  if (isset($seenOrders[$productionOrder])) {
    continue;
  }

  // tandai sebagai sudah diproses
  $seenOrders[$productionOrder] = true;

  if ($productionOrder === '' || $machine === '') continue;

  // Check Status SUDAH / BELUM BAGI KAIN
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

  $subStmt = db2_prepare($conn1, $subQuery);
  if (!$subStmt) {
      continue;
  }
  $subResult = db2_execute($subStmt, [$productionOrder]);
  if (!$subResult) {
      continue;
  }

  // Ambil hasil status
  $status = null;

  while ($subRow = db2_fetch_assoc($subStmt)) {
    $status = trim($subRow['STATUS_BAGI_KAIN']);
  }

  // Hanya ambil 2 status yang relevan
  if ($status === 'SUDAH BAGI KAIN' || $status === 'BELUM BAGI KAIN') {
    $posisiTerakhirQuery = "SELECT
            COALESCE(DEPT, OPERATIONCODE) || ' - ' || LONGDESCRIPTION AS STATUS_TERAKHIR
        FROM
            ITXVIEW_POSISI_KARTU_KERJA
        WHERE
            PRODUCTIONORDERCODE = ?
            AND STATUS_OPERATION <> 'Closed'
        ORDER BY
            STEPNUMBER ASC
        FETCH FIRST 1 ROW ONLY
    ";

    $posisiTerakhirStmt = db2_prepare($conn1, $posisiTerakhirQuery);

    if (!$posisiTerakhirStmt) {
      // kalau prepare gagal, aman diabaikan saja supaya script tetap jalan
      $posisiTerakhirValue = null;
    } else {
      $exec = db2_execute($posisiTerakhirStmt, [$productionOrder]);

      if ($exec) {
        $rowPosisi = db2_fetch_assoc($posisiTerakhirStmt);

        if ($rowPosisi) {
          $posisiTerakhirValue = trim($rowPosisi['STATUS_TERAKHIR']);
        } else {
          $posisiTerakhirValue = null;
        }
      } else {
        $posisiTerakhirValue = null;
      }
    }

    if (!isset($data_status[$status][$machine])) {
      $data_status[$status][$machine] = [
        'count' => 0,
        'items' => [] // simpan detail
      ];
    }
    $data_status[$status][$machine]['count']++;
    $brutoRaw = isset($row['BRUTO']) ? trim($row['BRUTO']) : '';
    if ($brutoRaw === '') {
      $formattedBruto = '';
    } else {
      // normalisasi input: tangani kemungkinan pemisah ribuan/decimal dengan koma
      $s = $brutoRaw;
      if (strpos($s, ',') !== false && strpos($s, '.') !== false) {
        // asumsikan koma pemisah ribuan, hapus koma
        $s = str_replace(',', '', $s);
      } elseif (strpos($s, ',') !== false && strpos($s, '.') === false) {
        // asumsikan koma sebagai pemisah desimal
        $s = str_replace(',', '.', $s);
      }
      // tentukan apakah ada bagian desimal non-zero
      if (strpos($s, '.') !== false) {
        list($intPart, $fracPart) = explode('.', $s, 2);
        $fracPart = rtrim($fracPart, '0');
        if ($fracPart === '') {
      // tidak ada desimal berarti tampilkan sebagai integer dengan pemisah ribuan
      $formattedBruto = number_format((int)$intPart, 0, '.', ',');
        } else {
      // ada desimal, tampilkan sesuai jumlah digit desimal yang signifikan
      $decimals = strlen($fracPart);
      $formattedBruto = number_format((float)$s, $decimals, '.', ',');
        }
      } else {
        // murni integer
        $formattedBruto = number_format((int)$s, 0, '.', ',');
      }
    }
    $data_status[$status][$machine]['items'][] = [
      'production_order' => $productionOrder,
      'production_demand' => trim($row['PRODUCTIONDEMAND']),
      'code' => trim($row['CODE']),
      'item' => trim($row['ITEM']),
      'warna' => $row['WARNA'],
      'del_internal' => trim($row['DEL_INTERNAL']),
      'lot' => trim($row['LOT']),
      // format qty bruto: show decimals only if non-zero, always use thousands separator
      'qty_bruto' => $formattedBruto,
      'status_terakhir' => $posisiTerakhirValue,
    ];
  }
}

$output = [
  'dataSudahBagiKain' => [],
  'dataBelumBagiKain' => [],
  'machineCapacities' => $machineCapacities,
];

foreach ($data_status['SUDAH BAGI KAIN'] as $machine => $data) {
  $output['dataSudahBagiKain'][] = ['machine' => $machine, 'count' => $data['count'], 'items' => $data['items']];
}

foreach ($data_status['BELUM BAGI KAIN'] as $machine => $data) {
  $output['dataBelumBagiKain'][] = ['machine' => $machine, 'count' => $data['count'], 'items' => $data['items']];
}

echo json_encode($output, JSON_PRETTY_PRINT);
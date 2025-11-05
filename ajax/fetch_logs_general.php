<?php
require_once "../koneksi.php";

$start = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;
$search = $_POST['search']['value'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';

$where = "WHERE 1=1";
$params = [];

if ($search) {
    $where .= " AND (entity LIKE ? OR entity_id LIKE ? OR action LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($start_date && $end_date) {
    $where .= " AND created_at BETWEEN ? AND ?";
    $params[] = $start_date . " 00:00:00";
    $params[] = $end_date . " 23:59:59";
}

$totalQuery = sqlsrv_query($con_nowprd, "SELECT COUNT(*) AS total FROM nowprd.log_general $where", $params);
$totalRow = sqlsrv_fetch_array($totalQuery, SQLSRV_FETCH_ASSOC);
$total = $totalRow['total'];

$query = "
    SELECT TOP $length * FROM (
        SELECT ROW_NUMBER() OVER (ORDER BY created_at DESC) AS rn, *
        FROM nowprd.log_general
        $where
    ) AS sub
    WHERE rn > $start
";

$result = sqlsrv_query($con_nowprd, $query, $params);

$data = [];
$no = $start + 1;
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $data[] = [
        'no' => $no++,
        'entity' => $row['entity'],
        'entity_id' => $row['entity_id'],
        'action' => $row['action'],
        'data' => $row['data'],
        'created_at' => $row['created_at']->format('Y-m-d H:i:s')
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $total,
    "recordsFiltered" => $total,
    "data" => $data
]);

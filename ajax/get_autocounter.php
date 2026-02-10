<?php
require_once "../koneksi.php";

header('Content-Type: application/json');
$id = 2;

$res = sqlsrv_query($con_db_lab, "SELECT nomor_urut FROM [db_laborat].importautocounter WHERE id = ?", [$id]);
if ($res === false) {
    echo json_encode(['success' => false, 'message' => 'Query gagal']);
    exit;
}

$row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC);
if (!$row) {
    echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
    exit;
}

echo json_encode(['success' => true, 'nomor_urut' => intval($row['nomor_urut'])]);

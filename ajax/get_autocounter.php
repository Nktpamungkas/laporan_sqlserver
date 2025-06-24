<?php
require_once "../koneksi.php"; // koneksi MySQL Anda

header('Content-Type: application/json');
$id = 2;

$res = mysqli_query($con_db_lab, "SELECT nomor_urut FROM importautocounter WHERE id = '2'");
if (!$res || mysqli_num_rows($res) === 0) {
    echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
    exit;
}

$row = mysqli_fetch_assoc($res);
echo json_encode(['success' => true, 'nomor_urut' => intval($row['nomor_urut'])]);

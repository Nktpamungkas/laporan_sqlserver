<?php
require_once "../koneksi.php";

$data = json_decode(file_get_contents("php://input"), true);
$new = intval($data['nomor_urut'] ?? -1);

header('Content-Type: application/json');

if ($new < 0) {
    echo json_encode(['success' => false, 'message' => 'Parameter tidak valid']);
    exit;
}

$res = mysqli_query($con_db_lab, "UPDATE importautocounter SET nomor_urut = $new WHERE id = '2'");
if ($res) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal update']);
}

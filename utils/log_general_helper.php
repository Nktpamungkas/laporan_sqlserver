<?php
ini_set("error_reporting", 0);
session_start();
require_once "koneksi.php";

function insertLog($con, $entity, $entity_id, $action, $oldData, $newData) {
    // Encode data lama & baru jadi JSON
    $data = json_encode([
        'old' => $oldData,
        'new' => $newData
    ], JSON_UNESCAPED_UNICODE);

    // Tanggal saat ini
    $created_at = date('Y-m-d H:i:s');

    // Jalankan query insert
    $insert = sqlsrv_query($con, "
        INSERT INTO nowprd.log_general (entity, entity_id, action, data, created_at)
        VALUES (?, ?, ?, ?, ?)
    ", [$entity, $entity_id, $action, $data, $created_at]);

    // Jika gagal, tampilkan error
    if (!$insert) {
        $errors = sqlsrv_errors(SQLSRV_ERR_ERRORS);
        echo "<pre>";
        print_r($errors);
        echo "</pre>";
        return false;
    }

    return true;
}
?>

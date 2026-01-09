<?php
require_once 'koneksi.php';
require_once 'monitor_koneksi_lib.php';

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate');

$statuses = getConnectionStatuses();

echo json_encode([
    'generated_at' => date('H:i:s'),
    'statuses'     => $statuses,
]);

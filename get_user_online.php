<?php
require_once "koneksi.php";

$sql = "SELECT * 
        FROM nowprd.log_activity_users 
        WHERE CREATEDATETIME >= DATEADD(MINUTE, -5, GETDATE())
        AND menu = 'prd_bukuresep.php'";

$result = sqlsrv_query($con_nowprd, $sql);

$listHTML = "";
$jumlahOnline = 0;

while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $jumlahOnline++;
    $datetime = $row['CREATEDATETIME']->format('H:i:s');
    $listHTML .= "<li><i class='bi bi-person-circle'></i> {$row['user']} <small>({$datetime})</small></li>";
}

echo json_encode([
    'jumlah' => $jumlahOnline,
    'html' => $listHTML
]);

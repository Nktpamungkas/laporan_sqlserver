<?php
    include 'koneksi.php';

    $nowarna = $_GET['no_warna'] ?? '';
    $sql = "SELECT DISTINCT no_warna, warna, langganan FROM [db_laborat].tbl_matching WHERE recipe_code = ?";

    $query = sqlsrv_query($con_db_lab, $sql, [$nowarna]);
    $dt = $query ? sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC) : null;

    $json = array(
        'no_warna'  => $dt['no_warna'] ?? null,
        'warna'     => $dt['warna'] ?? null,
        'langganan' => $dt['langganan'] ?? null
    );

    header('Content-Type: application/json');
    echo json_encode($json);

<?php
include 'koneksi.php';

$nowarna    = $_GET['no_warna'];
// Syntax MySql untuk melihat semua record yang
$sql = "SELECT DISTINCT no_warna, warna, langganan FROM db_laborat.tbl_matching WHERE recipe_code = '$nowarna'";

//Execetute Query diatas
$query = sqlsrv_query($con_db_lab, $sql);
$dt    = sqlsrv_fetch_array($query);

//Menampung data yang dihasilkan
$json = array(
    'no_warna'      => $dt['no_warna'],
    'warna'         => $dt['warna'],
    'langganan'     => $dt['langganan']
);

//Merubah data kedalam bentuk JSON
header('Content-Type: application/json');
echo json_encode($json);

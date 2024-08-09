<?php
include 'koneksi.php'; // Pastikan koneksi ke SQL Server sudah dibuat

// Mengambil no_absen dari parameter GET dan menghapus karakter nol di depan
$no_absen = isset($_GET['no_absen']) ? ltrim($_GET['no_absen'], '0') : '';

// Memastikan no_absen tidak kosong sebelum melakukan query
if (!empty($no_absen)) {
    // Membuat query untuk memilih data
    $sql = "SELECT * FROM hrd.hrd.tbl_makar WHERE no_scan = ?";

    // Menggunakan prepared statement
    $params = array($no_absen); // Parameter untuk query
    $stmt = sqlsrv_query($con_hrd, $sql, $params);

    // Memeriksa apakah query berhasil
    if ($stmt === false) {
        // Jika terjadi kesalahan saat menjalankan query
        $json = array(
            'error' => 'Kesalahan dalam menjalankan query: ' . print_r(sqlsrv_errors(), true)
        );
    } else {
        // Mengambil data
        if ($dt = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            // Menampung data yang dihasilkan
            $json = array(
                'nama' => $dt['nama']
            );
        } else {
            // Jika tidak ada data ditemukan
            $json = array(
                'error' => 'Data tidak ditemukan.'
            );
        }

        // Menutup statement
        sqlsrv_free_stmt($stmt);
    }
} else {
    // Jika no_absen tidak valid
    $json = array(
        'error' => 'no_absen tidak valid.'
    );
}

// Mengembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($json);
?>
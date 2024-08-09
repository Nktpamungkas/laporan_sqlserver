<?php
include 'koneksi.php'; // Pastikan koneksi ke SQL Server sudah dibuat

// Mengambil ID dari parameter GET dan mengonversinya menjadi integer
$id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Menggunakan intval untuk menghindari SQL injection

// Pastikan ID tidak kosong atau nol
if ($id > 0) {
    // Membuat query untuk memilih data
    $sql = "SELECT * FROM nowprd.buku_pinjam WHERE id = ?";

    // Menggunakan prepared statement
    $params = array($id); // Parameter untuk query
    $stmt = sqlsrv_query($con_nowprd, $sql, $params);

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
                'no_warna' => $dt['no_warna']
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
    // Jika ID tidak valid
    $json = array(
        'error' => 'ID tidak valid.'
    );
}

// Mengembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($json);
?>
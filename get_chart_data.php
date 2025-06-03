<?php
    require_once "koneksi.php";

    // Ambil parameter URL yang dipilih
    $url = $_GET['url'] ?? '';
    $date = $_GET['date'] ?? date('Y-m-d');

    // Grafik: Durasi loading per 30 menit
    $sql = "SELECT 
                CONVERT(VARCHAR(5), DATEADD(MINUTE, (DATEDIFF(MINUTE, '00:00', accessed_at) / 30) * 30, '00:00'), 114) AS time_interval,
                AVG(load_duration) AS avg_duration
            FROM nowprd.log_loading_ppc
            WHERE url LIKE ? AND CAST(accessed_at AS DATE) = ?
            GROUP BY 
                CONVERT(VARCHAR(5), DATEADD(MINUTE, (DATEDIFF(MINUTE, '00:00', accessed_at) / 30) * 30, '00:00'), 114)
            ORDER BY time_interval";

    $stmt = sqlsrv_query($con_nowprd, $sql, array("%$url%", $date));
    $labels = [];
    $values = [];
    
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $labels[] = $row['time_interval'];  // Mengambil waktu interval 30 menit
        $values[] = round($row['avg_duration'], 3); // Rata-rata durasi loading per interval 30 menit
    }

    // Menutup koneksi
    sqlsrv_close($con_nowprd);

    // Kirim data ke frontend dalam format JSON
    echo json_encode([
        'labels' => $labels,
        'values' => $values
    ]);
?>

<?php
    require_once "koneksi.php";

    // Grafik 1: Rata-rata loading per URL
    $url_data = [];
    $sql1 = "SELECT url, AVG(load_duration) AS avg_duration 
            FROM nowprd.log_loading_ppc 
            GROUP BY url ORDER BY avg_duration DESC";
    $stmt1 = sqlsrv_query($con_nowprd, $sql1);
    while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
        $url_data[] = $row;
    }

    // Grafik 2: Total akses per IP
    $ip_data = [];
    $sql2 = "SELECT ip_address, COUNT(*) AS total_akses 
            FROM nowprd.log_loading_ppc 
            GROUP BY ip_address ORDER BY total_akses DESC";
    $stmt2 = sqlsrv_query($con_nowprd, $sql2);
    while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
        $ip_data[] = $row;
    }

    // Grafik 3: Akses per jam
    $jam_data = [];
    $sql3 = "SELECT FORMAT(accessed_at, 'HH') AS jam, COUNT(*) AS total 
            FROM nowprd.log_loading_ppc 
            GROUP BY FORMAT(accessed_at, 'HH') ORDER BY jam";
    $stmt3 = sqlsrv_query($con_nowprd, $sql3);
    while ($row = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
        $jam_data[] = $row;
    }

    $urls = [];
    $sql = "SELECT DISTINCT url FROM nowprd.log_loading_ppc ORDER BY url";
    $stmt = sqlsrv_query($con_nowprd, $sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $urls[] = ''.$row['url'];
    }

    sqlsrv_close($con_nowprd);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Log Loading</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
</head>
<body class="bg-gray-100 text-gray-800 p-2">
    <div class="flex max-w-12xl h-screen">
        <!-- Sidebar Filter -->
        <div class="w-1/4 bg-white p-2 rounded shadow mr-4 h-fit">
            <h2 class="text-xl font-semibold mb-4">🔍 Filter</h2>
            <label class="block mb-1 font-medium">Pilih URL</label>
            <select id="urlSelect" class="w-full p-2 border rounded">
                <option value="">-- Semua URL --</option>
                <?php foreach ($urls as $url): ?>
                    <option value="<?= htmlspecialchars($url) ?>">https://online.indotaichen.com<?= $url ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Filter Tanggal -->
            <label class="block mt-4 mb-1 font-medium">Pilih Tanggal</label>
            <input type="date" id="dateSelect" class="w-full p-2 border rounded" value="<?= date('Y-m-d') ?>">
        </div>

        <!-- Chart Area -->
        <div class="flex-1 bg-white p-20 rounded shadow">
            <h2 class="text-xl font-semibold mb-0">📊 Grafik Durasi Loading (per 30 menit)</h2>
            <canvas id="chartUrl" class="w-full h-50"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('chartUrl').getContext('2d');
        let chart;

        // Inisialisasi Choices untuk dropdown URL
        const urlSelect = document.getElementById('urlSelect');
        const choices = new Choices(urlSelect, {
            searchEnabled: true, // Enable search
            itemSelectText: '',  // Hide default text
            noResultsText: 'Tidak ada hasil', // Custom message when no results found
        });

        // Mendapatkan filter tanggal
        const dateSelect = document.getElementById('dateSelect');

        // Fungsi untuk memperbarui grafik berdasarkan URL dan tanggal yang dipilih
        function updateChart(url = '', date = '') {
            let query = 'get_chart_data.php?url=' + encodeURIComponent(url);
            if (date) {
                query += '&date=' + encodeURIComponent(date); // Kirim tanggal sebagai parameter
            }
            
            fetch(query)
                .then(res => res.json())
                .then(data => {
                    if (chart) chart.destroy();
                    chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Durasi (detik)',
                                data: data.values,
                                backgroundColor: 'rgba(54, 162, 235, 0.7)'
                            }]
                        },
                        options: { responsive: true, maintainAspectRatio: false }
                    });
                });
        }

        // Event listener untuk perubahan pilihan URL
        urlSelect.addEventListener('change', function () {
            const url = this.value;
            const date = dateSelect.value; // Ambil tanggal
            updateChart(url, date); // Update chart dengan URL dan tanggal
        });

        // Event listener untuk perubahan tanggal
        dateSelect.addEventListener('change', function () {
            const url = urlSelect.value; // Ambil URL
            const date = this.value; // Ambil tanggal
            updateChart(url, date); // Update chart dengan URL dan tanggal
        });

        // Inisialisasi chart dengan data default
        updateChart();
    </script>

</body>
</html>

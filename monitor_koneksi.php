<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitoring Koneksi Database</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="#">
    <meta name="keywords" content="Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="#">
    <link rel="icon" href="files\assets\images\favicon.ico" type="image/x-icon">
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet"> -->
    <link rel="stylesheet" type="text/css" href="files\bower_components\bootstrap\css\bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\icon\themify-icons\themify-icons.css">
    <link rel="stylesheet" type="text/css" href="files\assets\icon\icofont\css\icofont.css">
    <link rel="stylesheet" type="text/css" href="files\assets\icon\feather\css\feather.css">
    <link rel="stylesheet" type="text/css" href="files\assets\pages\prism\prism.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\style.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\jquery.mCustomScrollbar.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\pcoded-horizontal.min.css">
    <link rel="stylesheet" type="text/css" href="files\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\pages\data-table\css\buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="files\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css">
    <style>
        .status-card { border: 1px solid #e5e7eb; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
        .status-badge { font-size: 12px; padding: 6px 10px; }
        .small-text { color: #6b7280; font-size: 13px; }
    </style>
</head>
<?php require_once 'header.php'; ?>
<body>
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-10">
                                    <div class="card status-card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-0">Monitoring Koneksi</h5>
                                                <div class="small-text">Pantau status server, hidup/mati, dan kesehatan koneksi. Data otomatis refresh.</div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <div id="last-updated" class="small-text mr-3">Memuat...</div>
                                                <a href="#" id="btn-refresh" class="btn btn-sm btn-outline-primary">
                                                    <i class="feather icon-rotate-ccw"></i> Refresh
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div>
                                                <table border="1" width="100%" >
                                                    <thead>
                                                        <tr>
                                                            <th>Nama Koneksi</th>
                                                            <th>Server / DB</th>
                                                            <th>Tipe</th>
                                                            <th>Status</th>
                                                            <th>Durasi (ms)</th>
                                                            <th>Catatan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="status-body">
                                                        <tr>
                                                            <td colspan="6">Memuat data...</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 small-text">
                                        Status "UP" berarti connect & tes query sukses. "ISSUE" berarti connect berhasil tapi tes sederhana gagal (lihat catatan). "DOWN" berarti connect gagal.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            const bodyEl = document.getElementById('status-body');
                            const lastUpdatedEl = document.getElementById('last-updated');
                            const refreshBtn = document.getElementById('btn-refresh');

                            function renderRows(rows) {
                                if (!Array.isArray(rows) || !rows.length) {
                                    bodyEl.innerHTML = '<tr><td colspan="6">Tidak ada data.</td></tr>';
                                    return;
                                }
                                bodyEl.innerHTML = rows.map(row => `
                                    <tr>
                                        <td>${row.label}</td>
                                        <td>${row.target}</td>
                                        <td>${row.type}</td>
                                        <td><span class="badge ${row.class} status-badge">${row.status}</span></td>
                                        <td>${row.ms !== null && row.ms !== undefined ? row.ms : ''}</td>
                                        <td>${row.detail || ''}</td>
                                    </tr>
                                `).join('');
                            }

                            function setLoading() {
                                bodyEl.innerHTML = '<tr><td colspan="6">Memuat data...</td></tr>';
                            }

                            async function fetchStatus() {
                                setLoading();
                                try {
                                    const res = await fetch('monitor_koneksi_data.php', { cache: 'no-store' });
                                    const data = await res.json();
                                    renderRows(data.statuses || []);
                                    lastUpdatedEl.textContent = 'Update: ' + (data.generated_at || new Date().toLocaleTimeString());
                                } catch (e) {
                                    bodyEl.innerHTML = '<tr><td colspan="6">Gagal memuat data: ' + e + '</td></tr>';
                                    lastUpdatedEl.textContent = 'Gagal update';
                                }
                            }

                            refreshBtn.addEventListener('click', function (e) {
                                e.preventDefault();
                                fetchStatus();
                            });

                            fetchStatus();
                            setInterval(fetchStatus, 15000); // auto refresh tiap 15 detik
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php require_once 'footer.php'; ?>

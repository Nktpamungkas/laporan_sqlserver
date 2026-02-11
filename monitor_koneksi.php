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
        #status-body.loading { opacity: 0.6; transition: opacity 0.2s ease-in-out; }
        #status-body.fade-in { animation: fadeIn 0.2s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0.6; } to { opacity: 1; } }
        #btn-refresh.disabled { pointer-events: none; opacity: 0.6; }
        #recent-changes { max-height: 140px; overflow-y: auto; }
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
                                                <div class="small-text mt-1" id="summary-text">Memuat ringkasan...</div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <div id="last-updated" class="small-text mr-3">Memuat...</div>
                                                <a href="#" id="btn-refresh" class="btn btn-sm btn-outline-primary">
                                                    <i class="feather icon-rotate-ccw"></i> Refresh
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex flex-wrap align-items-center mb-3 gap-2">
                                                <input id="filter-input" class="form-control form-control-sm" style="max-width: 220px;" placeholder="Cari koneksi...">
                                                <div class="form-check form-check-inline mb-0">
                                                    <input class="form-check-input" type="checkbox" id="filter-problem">
                                                    <label class="form-check-label small-text" for="filter-problem">Hanya ISSUE/DOWN</label>
                                                </div>
                                                <div class="small-text ml-auto" id="refresh-info">Auto refresh: 5s</div>
                                            </div>
                                            <div>
                                                <table border="1" width="100%" >
                                                    <thead>
                                                        <tr>
                                                            <th>Nama Koneksi</th>
                                                            <th>Server / DB</th>
                                                            <th>Tipe</th>
                                                            <th>Status</th>
                                                            <th>Durasi (ms)</th>
                                                            <th>Terakhir Ubah</th>
                                                            <th>Catatan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="status-body">
                                                        <tr>
                                                            <td colspan="7">Memuat data...</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-3">
                                                <div class="small-text mb-1">Perubahan terbaru</div>
                                                <ul id="recent-changes" class="small-text mb-0"></ul>
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
                            const summaryEl = document.getElementById('summary-text');
                            const filterInput = document.getElementById('filter-input');
                            const filterProblem = document.getElementById('filter-problem');
                            const refreshInfoEl = document.getElementById('refresh-info');
                            const recentChangesEl = document.getElementById('recent-changes');
                            let isFetching = false;
                            let lastHash = '';
                            const lastStatusMap = new Map();
                            const lastChangeMap = new Map();
                            const recentChanges = [];
                            let lastRows = [];
                            let refreshIntervalMs = 5000;
                            let refreshTimer = null;

                            function normalizeText(text) {
                                return (text || '').toString().toLowerCase();
                            }

                            function applyFilters(rows) {
                                const needle = normalizeText(filterInput.value);
                                const onlyProblem = filterProblem.checked;
                                return rows.filter(row => {
                                    const hay = normalizeText(row.label) + ' ' + normalizeText(row.target) + ' ' + normalizeText(row.type);
                                    if (needle && !hay.includes(needle)) return false;
                                    if (onlyProblem && row.status === 'UP') return false;
                                    return true;
                                });
                            }

                            function renderRows(rows) {
                                if (!Array.isArray(rows) || !rows.length) {
                                    bodyEl.innerHTML = '<tr><td colspan="7">Tidak ada data.</td></tr>';
                                    return;
                                }
                                const filtered = applyFilters(rows);
                                if (!filtered.length) {
                                    bodyEl.innerHTML = '<tr><td colspan="7">Tidak ada data.</td></tr>';
                                    return;
                                }
                                bodyEl.innerHTML = filtered.map(row => `
                                    <tr>
                                        <td>${row.label}</td>
                                        <td>${row.target}</td>
                                        <td>${row.type}</td>
                                        <td><span class="badge ${row.class} status-badge">${row.status}</span></td>
                                        <td>${row.ms !== null && row.ms !== undefined ? row.ms : ''}</td>
                                        <td>${lastChangeMap.get(row.label) || '-'}</td>
                                        <td>${row.detail || ''}</td>
                                    </tr>
                                `).join('');

                                const tableRows = bodyEl.querySelectorAll('tr');
                                filtered.forEach((row, idx) => {
                                    const prev = lastStatusMap.get(row.label);
                                    if (prev && prev !== row.status) {
                                        tableRows[idx].style.backgroundColor = '#fff6d6';
                                        setTimeout(() => { tableRows[idx].style.backgroundColor = ''; }, 3000);
                                        const stamp = new Date().toLocaleTimeString();
                                        lastChangeMap.set(row.label, stamp);
                                        recentChanges.unshift(`${stamp} - ${row.label}: ${prev} → ${row.status}`);
                                        if (recentChanges.length > 10) recentChanges.pop();
                                    }
                                    lastStatusMap.set(row.label, row.status);
                                });
                                recentChangesEl.innerHTML = recentChanges.length
                                    ? recentChanges.map(item => `<li>${item}</li>`).join('')
                                    : '<li>Tidak ada perubahan</li>';
                            }

                            function setLoading(isLoading) {
                                if (isLoading) {
                                    bodyEl.classList.add('loading');
                                    refreshBtn.classList.add('disabled');
                                    refreshBtn.setAttribute('aria-disabled', 'true');
                                    lastUpdatedEl.textContent = 'Memuat...';
                                } else {
                                    bodyEl.classList.remove('loading');
                                    refreshBtn.classList.remove('disabled');
                                    refreshBtn.removeAttribute('aria-disabled');
                                }
                            }

                            async function fetchStatus() {
                                if (isFetching) return;
                                isFetching = true;
                                setLoading(true);
                                try {
                                    const res = await fetch('monitor_koneksi_data.php', { cache: 'no-store' });
                                    const data = await res.json();
                                    lastRows = data.statuses || [];
                                    const payload = JSON.stringify(data.statuses || []);
                                    if (payload !== lastHash) {
                                        renderRows(lastRows);
                                        bodyEl.classList.remove('fade-in');
                                        void bodyEl.offsetWidth;
                                        bodyEl.classList.add('fade-in');
                                        lastHash = payload;
                                    }
                                    if (Array.isArray(lastRows)) {
                                        const total = lastRows.length;
                                        const up = lastRows.filter(r => r.status === 'UP').length;
                                        const issue = lastRows.filter(r => r.status === 'ISSUE').length;
                                        const down = lastRows.filter(r => r.status === 'DOWN').length;
                                        summaryEl.textContent = `Total: ${total} | UP: ${up} | ISSUE: ${issue} | DOWN: ${down}`;
                                        const desiredInterval = (issue + down) > 0 ? 1000 : 5000;
                                        if (desiredInterval !== refreshIntervalMs) {
                                            refreshIntervalMs = desiredInterval;
                                            refreshInfoEl.textContent = `Auto refresh: ${refreshIntervalMs / 1000}s`;
                                            restartAutoRefresh();
                                        }
                                    } else {
                                        summaryEl.textContent = 'Ringkasan tidak tersedia';
                                    }
                                    lastUpdatedEl.textContent = 'Update: ' + (data.generated_at || new Date().toLocaleTimeString());
                                } catch (e) {
                                    lastUpdatedEl.textContent = 'Gagal update';
                                    summaryEl.textContent = 'Ringkasan tidak tersedia';
                                    if (!lastHash) {
                                        bodyEl.innerHTML = '<tr><td colspan="7">Gagal memuat data: ' + e + '</td></tr>';
                                    }
                                } finally {
                                    setLoading(false);
                                    isFetching = false;
                                }
                            }

                            function restartAutoRefresh() {
                                if (refreshTimer) {
                                    clearInterval(refreshTimer);
                                    refreshTimer = null;
                                }
                                refreshTimer = setInterval(fetchStatus, refreshIntervalMs);
                            }

                            refreshBtn.addEventListener('click', function (e) {
                                e.preventDefault();
                                fetchStatus();
                            });

                            filterInput.addEventListener('input', function () {
                                renderRows(lastRows);
                            });

                            filterProblem.addEventListener('change', function () {
                                renderRows(lastRows);
                            });

                            fetchStatus();
                            restartAutoRefresh();

                            document.addEventListener('visibilitychange', function () {
                                if (document.hidden) {
                                    if (refreshTimer) {
                                        clearInterval(refreshTimer);
                                        refreshTimer = null;
                                    }
                                    refreshInfoEl.textContent = 'Auto refresh: paused';
                                } else {
                                    refreshInfoEl.textContent = `Auto refresh: ${refreshIntervalMs / 1000}s`;
                                    restartAutoRefresh();
                                    fetchStatus();
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php require_once 'footer.php'; ?>

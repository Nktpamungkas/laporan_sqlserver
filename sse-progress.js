(function() {
    'use strict';
    
    // Configuration
    const API_BASE_URL = 'http://10.0.1.154:8080';
    const SSE_ENDPOINT = '/api/ppc/memo-penting-stream';
    
    // DOM Elements
    const loadingOverlay = document.getElementById('loadingOverlay');
    const loadingText = document.getElementById('loadingText');
    const loadingSubtext = document.getElementById('loadingSubtext');
    const progressBar = document.getElementById('progressBar');
    const loadingDetails = document.getElementById('loadingDetails');
    
    let eventSource = null;
    let startTime = null;
    let dataTableInstance = null;
    let currentFilterParams = {};
    let progressInterval = null;
    let downloadTimeout = null;
    let downloadPollInterval = null;
    let downloadPollTimeout = null;

    function buildDownloadToken() {
        return String(Date.now()) + '-' + Math.floor(Math.random() * 1000000);
    }

    function getCookieValue(name) {
        const match = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/[$()*+.?[\\\]^{|}]/g, '\\$&') + '=([^;]*)'));
        return match ? decodeURIComponent(match[1]) : null;
    }

    function clearCookie(name) {
        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
    }

    function waitForDownloadCookie(token, onDone) {
        const cookieName = 'downloadToken_' + token;

        if (downloadPollInterval) clearInterval(downloadPollInterval);
        if (downloadPollTimeout) clearTimeout(downloadPollTimeout);

        downloadPollInterval = setInterval(function() {
            const val = getCookieValue(cookieName);
            if (val) {
                clearInterval(downloadPollInterval);
                downloadPollInterval = null;
                clearTimeout(downloadPollTimeout);
                downloadPollTimeout = null;
                clearCookie(cookieName);
                onDone && onDone();
            }
        }, 300);

        downloadPollTimeout = setTimeout(function() {
            if (downloadPollInterval) {
                clearInterval(downloadPollInterval);
                downloadPollInterval = null;
            }
            onDone && onDone();
        }, 300000);
    }

    function downloadViaIframe(href, token, onDone) {
        let frame = document.getElementById('download-frame');
        if (!frame) {
            frame = document.createElement('iframe');
            frame.id = 'download-frame';
            frame.style.display = 'none';
            document.body.appendChild(frame);
        }

        if (downloadTimeout) {
            clearTimeout(downloadTimeout);
            downloadTimeout = null;
        }

        waitForDownloadCookie(token, onDone);
        frame.src = href;
    }
    
    function showLoading(initialMessage) {
        startTime = Date.now();
        loadingOverlay.classList.add('active');
        progressBar.style.width = '0%';
        progressBar.textContent = '0%';
        progressBar.style.background = 'linear-gradient(90deg, #4099ff 0%, #73b4ff 100%)';
        loadingText.textContent = initialMessage || 'Memproses data...';
        loadingSubtext.textContent = 'Mohon tunggu sebentar';
        loadingSubtext.classList.add('pulse');
        loadingDetails.textContent = 'Menghubungkan ke server...';
    }
    
    function hideLoading(delay) {
        if (progressInterval) {
            clearInterval(progressInterval);
            progressInterval = null;
        }
        setTimeout(function() {
            loadingOverlay.classList.remove('active');
            loadingSubtext.classList.remove('pulse');
        }, delay || 500);
    }
    
    function simulateProgress(messages) {
        let stage = 0;
        let currentProgress = 0;
        
        if (progressInterval) clearInterval(progressInterval);
        
        progressInterval = setInterval(function() {
            if (stage < messages.length) {
                const msg = messages[stage];
                if (currentProgress < msg.percent) {
                    currentProgress += 2;
                    if (currentProgress > msg.percent) currentProgress = msg.percent;
                    progressBar.style.width = currentProgress + '%';
                    progressBar.textContent = currentProgress + '%';
                }
                if (currentProgress >= msg.percent) {
                    loadingText.textContent = msg.text;
                    loadingSubtext.textContent = msg.subtext;
                    stage++;
                }
                const elapsed = ((Date.now() - startTime) / 1000).toFixed(1);
                loadingDetails.textContent = 'Waktu: ' + elapsed + 's';
            }
        }, 100);
    }
    
    function updateProgressFromSSE(data) {
        const percent = data.percent || 0;
        progressBar.style.width = percent + '%';
        progressBar.textContent = percent + '%';
        if (data.message) loadingText.textContent = data.message;
        if (data.detail) loadingSubtext.textContent = data.detail;
        
        const elapsed = data.elapsedMs ? (data.elapsedMs / 1000).toFixed(1) : ((Date.now() - startTime) / 1000).toFixed(1);
        if (data.stage === 'process' && data.total > 0) {
            loadingDetails.textContent = 'Proses: ' + data.current + '/' + data.total + ' | Waktu: ' + elapsed + 's';
        } else {
            loadingDetails.textContent = 'Waktu: ' + elapsed + 's';
        }
    }
    
    function buildQueryString(form) {
        const formData = new FormData(form);
        const params = new URLSearchParams();
        const fieldMap = {
            'no_order': 'noOrder',
            'prod_demand': 'prodDemand',
            'prod_order': 'prodOrder',
            'tgl1': 'tgl1',
            'tgl2': 'tgl2',
            'no_po': 'noPo',
            'article_group': 'articleGroup',
            'article_code': 'articleCode',
            'nama_warna': 'namaWarna',
            'kkoke': 'kkoke',
            'orderline': 'orderline'
        };
        
        currentFilterParams = {};
        for (let [key, value] of formData.entries()) {
            if (fieldMap[key]) {
                params.append(fieldMap[key], value ? value.trim() : '');
                currentFilterParams[key] = value ? value.trim() : '';
            }
        }
        return params.toString();
    }
    
    function getField(row) {
        return function() {
            for (let i = 0; i < arguments.length; i++) {
                if (row.hasOwnProperty(arguments[i])) {
                    const val = row[arguments[i]];
                    if (val !== undefined && val !== null) return String(val);
                }
            }
            return '';
        };
    }
    
    function createExcelButtons() {
        let btnContainer = document.getElementById('sse-excel-buttons');
        
        if (!btnContainer) {
            const formButtonArea = document.querySelector('#filterForm .col-sm-12.col-xl-12.m-b-30');
            if (formButtonArea) {
                btnContainer = document.createElement('span');
                btnContainer.id = 'sse-excel-buttons';
                btnContainer.style.marginLeft = '10px';
                formButtonArea.appendChild(btnContainer);
            }
        }
        if (!btnContainer) return;
        
        // Build PHP query string (nama field PHP, bukan API)
        const params = new URLSearchParams();
        params.append('no_order', currentFilterParams['no_order'] || '');
        params.append('tgl1', currentFilterParams['tgl1'] || '');
        params.append('tgl2', currentFilterParams['tgl2'] || '');
        params.append('prod_demand', currentFilterParams['prod_demand'] || '');
        params.append('prod_order', currentFilterParams['prod_order'] || '');
        params.append('article_group', currentFilterParams['article_group'] || '');
        params.append('article_code', currentFilterParams['article_code'] || '');
        params.append('nama_warna', currentFilterParams['nama_warna'] || '');
        params.append('no_po', currentFilterParams['no_po'] || '');
        params.append('kkoke', currentFilterParams['kkoke'] || 'tidak');
        const qs = params.toString();
        
        btnContainer.innerHTML = 
            '<a class="btn btn-mat btn-success cetak-excel-btn-js" href="ppc_memopenting-excel.php?' + qs + '">CETAK EXCEL</a> ' +
            '<a class="btn btn-mat btn-warning cetak-excel-btn-js" href="ppc_memopenting-libre.php?' + qs + '">CETAK EXCEL (LIBRE)</a> ' +
            '<a class="btn btn-mat btn-danger cetak-excel-btn-js" href="ppc_memopenting-excel_qc.php?' + qs + '">CETAK EXCEL (QC)</a>';
        
        // LANGSUNG REDIRECT - tidak pakai fetch!
        btnContainer.querySelectorAll('.cetak-excel-btn-js').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var href = this.getAttribute('href');
                var text = this.textContent.trim();
                var token = buildDownloadToken();
                var sep = href.indexOf('?') === -1 ? '?' : '&';
                href = href + sep + 'downloadToken=' + encodeURIComponent(token);
                
                showLoading('Menyiapkan ' + text + '...');
                simulateProgress([
                    { percent: 30, text: 'Mengambil data...', subtext: 'Memproses' },
                    { percent: 60, text: 'Membuat Excel...', subtext: 'Menyusun data' },
                    { percent: 90, text: 'Finalisasi...', subtext: 'Hampir selesai' }
                ]);
                
                setTimeout(function() {
                    downloadViaIframe(href, token, function() {
                        hideLoading(0);
                    });
                }, 800);
            });
        });
    }
    
    function hideExcelButtons() {
        var c = document.getElementById('sse-excel-buttons');
        if (c) c.innerHTML = '';
    }
    
    function getOrCreateTableContainer() {
        var container = document.getElementById('sse-data-container');
        if (!container) {
            var formCard = document.querySelector('#filterForm').closest('.card');
            container = document.createElement('div');
            container.id = 'sse-data-container';
            container.className = 'card';
            container.style.display = 'none';
            container.innerHTML = '<div class="card-header"><h5>Data Memo Penting</h5><span class="float-right" id="sse-data-info"></span></div>' +
                '<div class="card-block"><div class="dt-responsive table-responsive" style="overflow-x:auto;width:100%">' +
                '<table id="sse-data-table" class="table table-striped table-bordered nowrap" style="width:100%;min-width:3000px">' +
                '<thead><tr>' +
                '<th>TGL BUKA KARTU</th><th>PELANGGAN</th><th>NO. ORDER</th><th>NO. PO</th><th>KETERANGAN PRODUCT</th>' +
                '<th>LEBAR</th><th>GRAMASI</th><th>WARNA</th><th>NO WARNA</th><th>DELIVERY</th><th>DELIVERY ACTUAL</th>' +
                '<th>GREIGE AWAL</th><th>GREIGE AKHIR</th><th>BAGI KAIN TGL</th><th>ROLL</th><th>BRUTO/BAGI KAIN</th>' +
                '<th>QTY SALINAN</th><th>QTY PACKING</th><th>NETTO(kg)</th><th>NETTO(yd/mtr)</th><th>QTY KURANG (KG)</th>' +
                '<th>QTY KURANG (YD/MTR)</th><th>DELAY</th><th>TARGET SELESAI</th><th>KODE DEPT</th><th>STATUS TERAKHIR</th>' +
                '<th>NOMOR MESIN SCHEDULE</th><th>NOMOR URUT SCHEDULE</th><th>DELAY PROGRESS STATUS</th><th>PROGRESS STATUS</th>' +
                '<th>TOTAL HARI</th><th>LOT</th><th>NO DEMAND</th><th>NO KARTU KERJA</th><th>ORIGINAL PD CODE</th>' +
                '<th>CATATAN PO GREIGE</th><th>KETERANGAN</th><th>RE PROSES ADDITIONAL</th>' +
                '</tr></thead><tbody id="sse-data-body"></tbody></table></div></div>';
            formCard.parentNode.insertBefore(container, formCard.nextSibling);
        }
        return container;
    }
    
    function renderDataTable(data, elapsedMs) {
        var container = getOrCreateTableContainer();
        var tbody = document.getElementById('sse-data-body');
        var infoSpan = document.getElementById('sse-data-info');
        
        tbody.innerHTML = '';
        if (dataTableInstance) { dataTableInstance.destroy(); dataTableInstance = null; }
        
        if (!data || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="38" class="text-center">Tidak ada data</td></tr>';
            container.style.display = 'block';
            infoSpan.textContent = 'Total: 0 data';
            hideExcelButtons();
            return;
        }
        
        var html = '';
        for (var i = 0; i < data.length; i++) {
            var row = data[i];
            var g = getField(row);
            html += '<tr>' +
                '<td>' + g('TGL_BUKA_KARTU') + '</td>' +
                '<td>' + g('PELANGGAN') + '</td>' +
                '<td>' + g('NO_ORDER') + '</td>' +
                '<td>' + g('NO_PO') + '</td>' +
                '<td>' + g('KETERANGAN_PRODUCT') + '</td>' +
                '<td>' + g('LEBAR') + '</td>' +
                '<td>' + g('GRAMASI') + '</td>' +
                '<td>' + g('WARNA') + '</td>' +
                '<td>' + g('NO_WARNA') + '</td>' +
                '<td>' + g('DELIVERY') + '</td>' +
                '<td>' + g('DELIVERY_ACTUAL') + '</td>' +
                '<td>' + g('GREIGE_AWAL') + '</td>' +
                '<td>' + g('GREIGE_AKHIR') + '</td>' +
                '<td>' + g('BAGI_KAIN_TGL') + '</td>' +
                '<td>' + g('ROLL') + '</td>' +
                '<td>' + g('BRUTO_BAGI_KAIN') + '</td>' +
                '<td>' + g('QTY_SALINAN') + '</td>' +
                '<td>' + g('QTY_PACKING') + '</td>' +
                '<td>' + g('NETTO_KG') + '</td>' +
                '<td>' + g('NETTO_YD_MTR') + '</td>' +
                '<td>' + g('QTY_KURANG_KG') + '</td>' +
                '<td>' + g('QTY_KURANG_YD_MTR') + '</td>' +
                '<td>' + g('DELAY') + '</td>' +
                '<td>' + g('TARGET_SELESAI') + '</td>' +
                '<td>' + g('KODE_DEPT') + '</td>' +
                '<td>' + g('STATUS_TERAKHIR') + '</td>' +
                '<td>' + g('NOMOR_MESIN_SCHEDULE') + '</td>' +
                '<td>' + g('NOMOR_URUT_SCHEDULE') + '</td>' +
                '<td>' + g('DELAY_PROGRESS_STATUS') + '</td>' +
                '<td>' + g('PROGRESS_STATUS') + '</td>' +
                '<td>' + g('TOTAL_HARI') + '</td>' +
                '<td>' + g('LOT') + '</td>' +
                '<td><a href="http://online.indotaichen.com/laporan/ppc_filter_steps.php?demand=' + encodeURIComponent(g('NO_DEMAND')) + '&prod_order=' + encodeURIComponent(g('NO_KARTU_KERJA')) + '" target="_BLANK">' + g('NO_DEMAND') + '</a></td>' +
                '<td>' + g('NO_KARTU_KERJA') + '</td>' +
                '<td>' + g('ORIGINAL_PD_CODE') + '</td>' +
                '<td>' + g('CATATAN_PO_GREIGE') + '</td>' +
                '<td>' + g('KETERANGAN') + '</td>' +
                '<td>' + g('RE_PROSES_ADDITIONAL') + '</td>' +
                '</tr>';
        }
        
        tbody.innerHTML = html;
        container.style.display = 'block';
        infoSpan.innerHTML = '<span class="badge badge-success">Total: ' + data.length + ' data</span> <span class="badge badge-info">Waktu: ' + (elapsedMs/1000).toFixed(2) + 's</span>';
        
        createExcelButtons();
        
        if (typeof $ !== 'undefined' && $.fn.DataTable) {
            dataTableInstance = $('#sse-data-table').DataTable({
                responsive: false,
                scrollX: true,
                pageLength: 25,
                order: [[0, 'desc']],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    paginate: { next: "›", previous: "‹" }
                }
            });
        }
        container.scrollIntoView({ behavior: 'smooth' });
    }
    
    function fetchDataWithSSE(queryString, onComplete, onError) {
        var url = API_BASE_URL + SSE_ENDPOINT + '?' + queryString;
        console.log('SSE:', url);
        
        if (eventSource) eventSource.close();
        eventSource = new EventSource(url);
        
        eventSource.addEventListener('progress', function(e) {
            try {
                var data = JSON.parse(e.data);
                updateProgressFromSSE(data);
                
                if (data.stage === 'complete') {
                    eventSource.close();
                    if (data.data && Array.isArray(data.data)) {
                        onComplete(data.data, data.elapsedMs || 0);
                    } else {
                        onError('Data tidak valid');
                    }
                } else if (data.stage === 'error') {
                    eventSource.close();
                    onError(data.detail || 'Error');
                }
            } catch (err) {
                eventSource.close();
                onError('Parse error');
            }
        });
        
        eventSource.onerror = function() {
            eventSource.close();
            onError('Koneksi terputus');
        };
    }
    
    function handleCariData(form) {
        var formData = new FormData(form);
        var hasValue = false;
        for (var pair of formData.entries()) {
            if (pair[0] !== 'kkoke' && pair[0] !== 'submit' && pair[1] && pair[1].trim()) {
                hasValue = true;
                break;
            }
        }
        if (!hasValue) {
            alert('Silakan isi minimal satu filter!');
            return;
        }
        
        hideExcelButtons();
        showLoading('Mencari data...');
        
        fetchDataWithSSE(buildQueryString(form),
            function(data, elapsed) {
                progressBar.style.width = '100%';
                progressBar.textContent = '100%';
                loadingText.textContent = 'Selesai!';
                loadingSubtext.textContent = data.length + ' data ditemukan';
                setTimeout(function() {
                    renderDataTable(data, elapsed);
                    hideLoading(500);
                }, 300);
            },
            function(err) {
                progressBar.style.background = 'linear-gradient(90deg, #dc3545 0%, #ff6b6b 100%)';
                loadingText.textContent = 'Error';
                loadingSubtext.textContent = err;
                setTimeout(function() { hideLoading(0); }, 3000);
            }
        );
    }
    
    function handleDownloadData(form) {
        var formData = new FormData(form);
        var hasValue = false;
        for (var pair of formData.entries()) {
            if (pair[0] !== 'kkoke' && pair[0] !== 'submit' && pair[1] && pair[1].trim()) {
                hasValue = true;
                break;
            }
        }
        if (!hasValue) {
            alert('Silakan isi minimal satu filter!');
            return;
        }
        
        // Build query string untuk PHP Excel
        var params = new URLSearchParams();
        params.append('no_order', formData.get('no_order') || '');
        params.append('tgl1', formData.get('tgl1') || '');
        params.append('tgl2', formData.get('tgl2') || '');
        params.append('prod_demand', formData.get('prod_demand') || '');
        params.append('prod_order', formData.get('prod_order') || '');
        params.append('article_group', formData.get('article_group') || '');
        params.append('article_code', formData.get('article_code') || '');
        params.append('nama_warna', formData.get('nama_warna') || '');
        params.append('no_po', formData.get('no_po') || '');
        params.append('kkoke', formData.get('kkoke') || 'tidak');
        
        var token = buildDownloadToken();
        params.append('downloadToken', token);
        var href = 'ppc_memopenting-excel.php?' + params.toString();
        
        showLoading('Menyiapkan Download Data...');
        simulateProgress([
            { percent: 30, text: 'Mengambil data...', subtext: 'Memproses filter' },
            { percent: 60, text: 'Membuat Excel...', subtext: 'Menyusun data' },
            { percent: 90, text: 'Finalisasi...', subtext: 'Hampir selesai' }
        ]);
        
        setTimeout(function() {
            downloadViaIframe(href, token, function() {
                hideLoading(0);
            });
        }, 800);
    }
    
    function init() {
        var form = document.getElementById('filterForm');
        var btnCari = document.getElementById('btnCari');
        var btnExcel = document.getElementById('btnExcel');
        
        if (btnCari && form) {
            var newBtn = btnCari.cloneNode(true);
            btnCari.parentNode.replaceChild(newBtn, btnCari);
            newBtn.addEventListener('click', function(e) {
                e.preventDefault();
                handleCariData(form);
            });
        }
        
        if (btnExcel && form) {
            var newBtn2 = btnExcel.cloneNode(true);
            btnExcel.parentNode.replaceChild(newBtn2, btnExcel);
            newBtn2.addEventListener('click', function(e) {
                e.preventDefault();
                handleDownloadData(form);
            });
        }
        
        // Handle existing PHP Excel buttons
        document.querySelectorAll('.cetak-excel-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var href = this.getAttribute('href');
                var text = this.textContent.trim();
                var token = buildDownloadToken();
                var sep = href.indexOf('?') === -1 ? '?' : '&';
                href = href + sep + 'downloadToken=' + encodeURIComponent(token);
                
                showLoading('Menyiapkan ' + text + '...');
                simulateProgress([
                    { percent: 50, text: 'Memproses...', subtext: 'Mohon tunggu' },
                    { percent: 90, text: 'Hampir selesai...', subtext: 'Sebentar lagi' }
                ]);
                setTimeout(function() {
                    downloadViaIframe(href, token, function() {
                        hideLoading(0);
                    });
                }, 500);
            });
        });
        
        console.log('SSE Handler ready');
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    window.addEventListener('beforeunload', function() {
        if (eventSource) eventSource.close();
        if (progressInterval) clearInterval(progressInterval);
    });
})();

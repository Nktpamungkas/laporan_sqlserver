(function() {
    'use strict';
    
    // Configuration 
    const API_BASE_URL = 'http://localhost:8080';
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
    
    /**
     * Show loading overlay
     */
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
    
    /**
     * Hide loading overlay
     */
    function hideLoading(delay) {
        setTimeout(function() {
            loadingOverlay.classList.remove('active');
            loadingSubtext.classList.remove('pulse');
        }, delay || 500);
    }
    
    /**
     * Update progress bar from SSE data
     */
    function updateProgressFromSSE(data) {
        const percent = data.percent || 0;
        
        progressBar.style.width = percent + '%';
        progressBar.textContent = percent + '%';
        
        if (data.message) {
            loadingText.textContent = data.message;
        }
        if (data.detail) {
            loadingSubtext.textContent = data.detail;
        }
        
        // Update elapsed time
        const elapsed = data.elapsedMs ? (data.elapsedMs / 1000).toFixed(1) : ((Date.now() - startTime) / 1000).toFixed(1);
        
        if (data.stage === 'process' && data.total > 0) {
            loadingDetails.textContent = `Proses: ${data.current}/${data.total} | Waktu: ${elapsed}s`;
        } else {
            loadingDetails.textContent = `Waktu: ${elapsed}s`;
        }
    }
    
    /**
     * Build query string from form data
     */
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
        
        for (let [key, value] of formData.entries()) {
            if (value && value.trim() && fieldMap[key]) {
                params.append(fieldMap[key], value.trim());
            }
        }
        
        return params.toString();
    }
    
    /**
     * Helper function to get field value - check multiple possible field names
     * Returns proper value with fallback to empty string or 0 for missing fields
     */
    function getField(row, ...fieldNames) {
        for (let name of fieldNames) {
            if (row.hasOwnProperty(name)) {
                const val = row[name];
                // Return actual value even if 0, null, or empty string from API
                if (val !== undefined && val !== null) {
                    return val === '' ? '' : val;
                }
            }
        }
        return '';
    }
    
    /**
     * Get or create data table container
     */
    function getOrCreateTableContainer() {
        let container = document.getElementById('sse-data-container');
        
        if (!container) {
            // Cari posisi setelah form card
            const formCard = document.querySelector('#filterForm').closest('.card');
            
            // Buat container baru
            container = document.createElement('div');
            container.id = 'sse-data-container';
            container.className = 'card';
            container.style.display = 'none';
            container.innerHTML = `
                <div class="card-header">
                    <h5>Data Memo Penting</h5>
                    <span class="float-right" id="sse-data-info"></span>
                </div>
                <div class="card-block">
                    <div class="dt-responsive table-responsive" style="overflow-x: auto; width: 100%;">
                        <table id="sse-data-table" class="table table-striped table-bordered nowrap" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>TGL BUKA KARTU</th>
                                    <th>PELANGGAN</th>
                                    <th>NO. ORDER</th>
                                    <th>NO. PO</th>
                                    <th>KETERANGAN PRODUCT</th>
                                    <th>LEBAR</th>
                                    <th>GRAMASI</th>
                                    <th>WARNA</th>
                                    <th>NO WARNA</th>
                                    <th>DELIVERY</th>
                                    <th>DELIVERY ACTUAL</th>
                                    <th>GREIGE AWAL</th>
                                    <th>GREIGE AKHIR</th>
                                    <th>BAGI KAIN TGL</th>
                                    <th>ROLL</th>
                                    <th>BRUTO/BAGI KAIN</th>
                                    <th>QTY SALINAN</th>
                                    <th>QTY PACKING</th>
                                    <th>NETTO(kg)</th>
                                    <th>NETTO(yd/mtr)</th>
                                    <th>QTY KURANG (KG)</th>
                                    <th>QTY KURANG (YD/MTR)</th>
                                    <th>DELAY</th>
                                    <th>TARGET SELESAI</th>
                                    <th>KODE DEPT</th>
                                    <th>STATUS TERAKHIR</th>
                                    <th>NOMOR MESIN SCHEDULE</th>
                                    <th>NOMOR URUT SCHEDULE</th>
                                    <th>DELAY PROGRESS STATUS</th>
                                    <th>PROGRESS STATUS</th>
                                    <th>TOTAL HARI</th>
                                    <th>LOT</th>
                                    <th>NO DEMAND</th>
                                    <th>NO KARTU KERJA</th>
                                    <th>ORIGINAL PD CODE</th>
                                    <th>CATATAN PO GREIGE</th>
                                    <th>KETERANGAN</th>
                                    <th>RE PROSES ADDITIONAL</th>
                                </tr>
                            </thead>
                            <tbody id="sse-data-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
            
            // Insert after form card
            formCard.parentNode.insertBefore(container, formCard.nextSibling);
        }
        
        return container;
    }
    
    /**
     * Render data to table
     * Mencoba beberapa kemungkinan nama field (UPPERCASE, camelCase, dll)
     */
    function renderDataTable(data, elapsedMs) {
        const container = getOrCreateTableContainer();
        const tbody = document.getElementById('sse-data-body');
        const infoSpan = document.getElementById('sse-data-info');
        
        // Clear existing data
        tbody.innerHTML = '';
        
        // Destroy existing DataTable if any
        if (dataTableInstance) {
            dataTableInstance.destroy();
            dataTableInstance = null;
        }
        
        if (!data || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="38" class="text-center">Tidak ada data yang ditemukan</td></tr>';
            container.style.display = 'block';
            infoSpan.textContent = 'Total: 0 data';
            return;
        }
        
        // Debug: Log first row to see actual field names
        console.log('First row data:', data[0]);
        console.log('Field names:', Object.keys(data[0]));
        console.log('Total columns should be: 38');
        
        // Test getField pada beberapa kolom yang mungkin hilang
        if (data[0]) {
            console.log('QTY_SALINAN:', getField(data[0], 'QTY_SALINAN', 'qtySalinan'));
            console.log('QTY_PACKING:', getField(data[0], 'QTY_PACKING', 'qtyPacking'));
            console.log('NETTO_KG:', getField(data[0], 'NETTO_KG', 'nettoKg'));
            console.log('RE_PROSES_ADDITIONAL:', getField(data[0], 'RE_PROSES_ADDITIONAL', 'reProsesAdditional'));
        }
        
        // Build table rows - cek multiple kemungkinan nama field
        let html = '';
        data.forEach(function(row) {
            html += '<tr>';
            // TGL BUKA KARTU
            html += '<td>' + getField(row, 'TGL_BUKA_KARTU', 'tglBukaKartu', 'ORDERDATE', 'orderDate') + '</td>';
            // PELANGGAN
            html += '<td>' + getField(row, 'PELANGGAN', 'pelanggan') + '</td>';
            // NO ORDER
            html += '<td>' + getField(row, 'NO_ORDER', 'noOrder', 'SALESORDERCODE') + '</td>';
            // NO PO
            html += '<td>' + getField(row, 'NO_PO', 'noPo') + '</td>';
            // KETERANGAN PRODUCT
            html += '<td>' + getField(row, 'KETERANGAN_PRODUCT', 'keteranganProduct') + '</td>';
            // LEBAR
            html += '<td>' + getField(row, 'LEBAR', 'lebar') + '</td>';
            // GRAMASI
            html += '<td>' + getField(row, 'GRAMASI', 'gramasi') + '</td>';
            // WARNA
            html += '<td>' + getField(row, 'WARNA', 'warna') + '</td>';
            // NO WARNA
            html += '<td>' + getField(row, 'NO_WARNA', 'noWarna') + '</td>';
            // DELIVERY
            html += '<td>' + getField(row, 'DELIVERY', 'delivery') + '</td>';
            // DELIVERY ACTUAL
            html += '<td>' + getField(row, 'DELIVERY_ACTUAL', 'deliveryActual') + '</td>';
            // GREIGE AWAL
            html += '<td>' + getField(row, 'GREIGE_AWAL', 'greigeAwal') + '</td>';
            // GREIGE AKHIR
            html += '<td>' + getField(row, 'GREIGE_AKHIR', 'greigeAkhir') + '</td>';
            // BAGI KAIN TGL
            html += '<td>' + getField(row, 'BAGI_KAIN_TGL', 'bagiKainTgl') + '</td>';
            // ROLL
            html += '<td>' + getField(row, 'ROLL', 'roll') + '</td>';
            // BRUTO BAGI KAIN
            html += '<td>' + getField(row, 'BRUTO_BAGI_KAIN', 'brutoBagiKain') + '</td>';
            // QTY SALINAN
            html += '<td>' + getField(row, 'QTY_SALINAN', 'qtySalinan') + '</td>';
            // QTY PACKING
            html += '<td>' + getField(row, 'QTY_PACKING', 'qtyPacking') + '</td>';
            // NETTO KG
            html += '<td>' + getField(row, 'NETTO_KG', 'nettoKg') + '</td>';
            // NETTO YD MTR
            html += '<td>' + getField(row, 'NETTO_YD_MTR', 'nettoYdMtr') + '</td>';
            // QTY KURANG KG
            html += '<td>' + getField(row, 'QTY_KURANG_KG', 'qtyKurangKg') + '</td>';
            // QTY KURANG YD MTR
            html += '<td>' + getField(row, 'QTY_KURANG_YD_MTR', 'qtyKurangYdMtr') + '</td>';
            // DELAY
            html += '<td>' + getField(row, 'DELAY', 'delay') + '</td>';
            // TARGET SELESAI
            html += '<td>' + getField(row, 'TARGET_SELESAI', 'targetSelesai') + '</td>';
            // KODE DEPT
            html += '<td>' + getField(row, 'KODE_DEPT', 'kodeDept') + '</td>';
            // STATUS TERAKHIR
            html += '<td>' + getField(row, 'STATUS_TERAKHIR', 'statusTerakhir') + '</td>';
            // NOMOR MESIN SCHEDULE
            html += '<td>' + getField(row, 'NOMOR_MESIN_SCHEDULE', 'nomorMesinSchedule') + '</td>';
            // NOMOR URUT SCHEDULE
            html += '<td>' + getField(row, 'NOMOR_URUT_SCHEDULE', 'nomorUrutSchedule') + '</td>';
            // DELAY PROGRESS STATUS
            html += '<td>' + getField(row, 'DELAY_PROGRESS_STATUS', 'delayProgressStatus') + '</td>';
            // PROGRESS STATUS
            html += '<td>' + getField(row, 'PROGRESS_STATUS', 'progressStatus') + '</td>';
            // TOTAL HARI
            html += '<td>' + getField(row, 'TOTAL_HARI', 'totalHari') + '</td>';
            // LOT
            html += '<td>' + getField(row, 'LOT', 'lot') + '</td>';
            // NO DEMAND
            html += '<td>' + getField(row, 'NO_DEMAND', 'noDemand') + '</td>';
            // NO KARTU KERJA
            html += '<td>' + getField(row, 'NO_KARTU_KERJA', 'noKartuKerja') + '</td>';
            // ORIGINAL PD CODE
            html += '<td>' + getField(row, 'ORIGINAL_PD_CODE', 'originalPdCode') + '</td>';
            // CATATAN PO GREIGE
            html += '<td>' + getField(row, 'CATATAN_PO_GREIGE', 'catatanPoGreige') + '</td>';
            // KETERANGAN
            html += '<td>' + getField(row, 'KETERANGAN', 'keterangan') + '</td>';
            // RE PROSES ADDITIONAL
            html += '<td>' + getField(row, 'RE_PROSES_ADDITIONAL', 'reProsesAdditional') + '</td>';
            html += '</tr>';
        });
        
        tbody.innerHTML = html;
        
        // Show container
        container.style.display = 'block';
        
        // Update info
        const seconds = (elapsedMs / 1000).toFixed(2);
        infoSpan.innerHTML = '<span class="badge badge-success">Total: ' + data.length + ' data</span> <span class="badge badge-info">Waktu: ' + seconds + 's</span>';
        
        // Initialize DataTable
        if (typeof $ !== 'undefined' && $.fn.DataTable) {
            dataTableInstance = $('#sse-data-table').DataTable({
                responsive: false,  // Disable responsive agar semua kolom muncul
                scrollX: true,      // Enable horizontal scroll
                scrollCollapse: true,
                autoWidth: false,   // Prevent auto column width calculation
                pageLength: 25,
                order: [[0, 'desc']],
                columnDefs: [
                    { width: "150px", targets: "_all" }  // Set minimal width untuk semua kolom
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(filter dari _MAX_ total data)",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "›",
                        previous: "‹"
                    }
                }
            });
        }
        
        // Scroll to table
        container.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    /**
     * Connect to SSE and fetch data with progress
     */
    function fetchDataWithSSE(queryString, onComplete, onError) {
        const url = API_BASE_URL + SSE_ENDPOINT + '?' + queryString;
        console.log('SSE Connecting:', url);
        
        // Close existing connection
        if (eventSource) {
            eventSource.close();
        }
        
        eventSource = new EventSource(url);
        
        eventSource.addEventListener('progress', function(e) {
            try {
                const data = JSON.parse(e.data);
                console.log('SSE Progress:', data.stage, data.percent + '%');
                
                updateProgressFromSSE(data);
                
                if (data.stage === 'complete') {
                    eventSource.close();
                    if (onComplete) onComplete(data.data, data.elapsedMs);
                } else if (data.stage === 'error') {
                    eventSource.close();
                    if (onError) onError(data.detail || 'Terjadi kesalahan');
                }
            } catch (err) {
                console.error('SSE Parse Error:', err);
            }
        });
        
        eventSource.onerror = function(e) {
            console.error('SSE Connection Error:', e);
            eventSource.close();
            if (onError) onError('Koneksi ke server terputus. Silakan coba lagi.');
        };
    }
    
    /**
     * Build query string from URL (for Excel links)
     */
    function buildQueryStringFromURL(href) {
        const urlParams = new URLSearchParams(href.split('?')[1] || '');
        const params = new URLSearchParams();
        
        if (urlParams.get('no_order')) params.append('noOrder', urlParams.get('no_order'));
        if (urlParams.get('tgl1')) params.append('tgl1', urlParams.get('tgl1'));
        if (urlParams.get('tgl2')) params.append('tgl2', urlParams.get('tgl2'));
        if (urlParams.get('prod_order')) params.append('prodOrder', urlParams.get('prod_order'));
        if (urlParams.get('prod_demand')) params.append('prodDemand', urlParams.get('prod_demand'));
        params.append('kkoke', urlParams.get('kkoke') || 'tidak');
        
        return params.toString();
    }
    
    /**
     * Handle Cari Data button click
     */
    function handleCariData(form) {
        // Validate form
        const formData = new FormData(form);
        let hasValue = false;
        
        for (let [key, value] of formData.entries()) {
            if (key !== 'kkoke' && key !== 'submit' && key !== 'submit_excel' && value && value.trim()) {
                hasValue = true;
                break;
            }
        }
        
        if (!hasValue) {
            alert('Silakan isi minimal satu filter!');
            return;
        }
        
        // Show loading
        showLoading('Mencari data...');
        
        // Build query and fetch
        const queryString = buildQueryString(form);
        
        fetchDataWithSSE(queryString,
            // On Complete
            function(data, elapsedMs) {
                progressBar.style.width = '100%';
                progressBar.textContent = '100%';
                loadingText.textContent = 'Selesai!';
                loadingSubtext.textContent = 'Menampilkan ' + (data ? data.length : 0) + ' data';
                loadingSubtext.classList.remove('pulse');
                
                // Render table
                setTimeout(function() {
                    renderDataTable(data, elapsedMs);
                    hideLoading(500);
                }, 300);
            },
            // On Error
            function(errorMsg) {
                progressBar.style.background = 'linear-gradient(90deg, #dc3545 0%, #ff6b6b 100%)';
                loadingText.textContent = 'Terjadi Kesalahan';
                loadingSubtext.textContent = errorMsg;
                loadingSubtext.classList.remove('pulse');
                
                setTimeout(function() {
                    hideLoading(0);
                }, 3000);
            }
        );
    }
    
    /**
     * Handle Excel download with SSE progress
     */
    function handleExcelDownload(href, buttonText) {
        showLoading('Menyiapkan ' + buttonText + '...');
        
        const queryString = buildQueryStringFromURL(href);
        
        fetchDataWithSSE(queryString,
            // On Complete - proceed to download
            function(data, elapsedMs) {
                progressBar.style.width = '100%';
                progressBar.textContent = '100%';
                loadingText.textContent = 'File siap!';
                loadingSubtext.textContent = 'Mengunduh...';
                loadingSubtext.classList.remove('pulse');
                
                // Navigate to download
                setTimeout(function() {
                    window.location.href = href;
                    setTimeout(function() {
                        hideLoading(0);
                    }, 2000);
                }, 500);
            },
            // On Error - still try download
            function(errorMsg) {
                console.warn('SSE Error, trying download anyway:', errorMsg);
                window.location.href = href;
                setTimeout(function() {
                    hideLoading(0);
                }, 3000);
            }
        );
    }
    
    /**
     * Initialize
     */
    function init() {
        const form = document.getElementById('filterForm');
        const btnCari = document.getElementById('btnCari');
        const btnExcel = document.getElementById('btnExcel');
        
        // Handle Cari Data - INTERCEPT, no form submit
        if (btnCari && form) {
            btnCari.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                handleCariData(form);
            });
        }
        
        // Handle Download Data - INTERCEPT
        if (btnExcel && form) {
            btnExcel.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Validate
                const formData = new FormData(form);
                let hasValue = false;
                for (let [key, value] of formData.entries()) {
                    if (key !== 'kkoke' && key !== 'submit' && key !== 'submit_excel' && value && value.trim()) {
                        hasValue = true;
                        break;
                    }
                }
                
                if (!hasValue) {
                    alert('Silakan isi minimal satu filter!');
                    return;
                }
                
                // For Download Data, we still need to submit form to PHP Excel generator
                // But show SSE progress first
                showLoading('Menyiapkan file Excel...');
                
                const queryString = buildQueryString(form);
                fetchDataWithSSE(queryString,
                    function(data, elapsedMs) {
                        progressBar.style.width = '100%';
                        progressBar.textContent = '100%';
                        loadingText.textContent = 'Data siap!';
                        loadingSubtext.textContent = 'Membuat file Excel...';
                        
                        // Now submit form for Excel
                        setTimeout(function() {
                            form.submit();
                        }, 500);
                    },
                    function(errorMsg) {
                        // Still submit on error
                        form.submit();
                    }
                );
            });
        }
        
        // Handle CETAK EXCEL buttons
        document.querySelectorAll('.cetak-excel-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                handleExcelDownload(this.getAttribute('href'), this.textContent.trim());
            });
        });
        
        console.log('SSE Progress Handler (Full JS Rendering) initialized');
    }
    
    // Initialize when DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Cleanup
    window.addEventListener('beforeunload', function() {
        if (eventSource) {
            eventSource.close();
        }
    });
    
})();
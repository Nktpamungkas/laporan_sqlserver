<?php
// ====== SETUP & QUERY ======
$prodOrder  = $_GET['prod_order'] ?? '';
$prodDemand = $_GET['demand'] ?? '';
$delActual  = $_GET['del_actual'] ?? ''; // format YYYY-MM-DD disarankan

require_once 'koneksi.php';

$qPOGreige = "SELECT DISTINCT 
                i.ITEMTYPEAFICODE,
                i.SUBCODE01, i.SUBCODE02, i.SUBCODE03, i.SUBCODE04,
                i.RESERVATION_SUBCODE04,
                i.PROJECTCODE, i.ORDERLINE,
                CASE WHEN i.AKJ = 'NON AKJ' THEN i.DATE_AKTUAL     ELSE NULL END AS DATE_AKTUAL,
                CASE WHEN i.AKJ = 'NON AKJ' THEN i.DATE_AKTUAL_TO  ELSE NULL END AS DATE_AKTUAL_TO,
                CASE WHEN i.AKJ = 'NON AKJ' THEN i.DATE_AKTUAL2    ELSE NULL END AS DATE_AKTUAL2,
                CASE WHEN i.AKJ = 'NON AKJ' THEN i.DATE_AKTUAL_TO2 ELSE NULL END AS DATE_AKTUAL_TO2,
                CASE WHEN i.AKJ = 'NON AKJ' THEN i.DATE_AKTUAL3    ELSE NULL END AS DATE_AKTUAL3,
                CASE WHEN i.AKJ = 'NON AKJ' THEN i.DATE_AKTUAL_TO3 ELSE NULL END AS DATE_AKTUAL_TO3,
                CASE WHEN i.AKJ = 'NON AKJ' THEN i.DATE_AKTUAL4    ELSE NULL END AS DATE_AKTUAL4,
                CASE WHEN i.AKJ = 'NON AKJ' THEN i.DATE_AKTUAL_TO4 ELSE NULL END AS DATE_AKTUAL_TO4,
                CASE WHEN i.AKJ = 'NON AKJ' THEN i.DATE_AKTUAL5    ELSE NULL END AS DATE_AKTUAL5,
                CASE WHEN i.AKJ = 'NON AKJ' THEN i.DATE_AKTUAL_TO5 ELSE NULL END AS DATE_AKTUAL_TO5,
                CASE WHEN i.AKJ = 'NON AKJ' THEN i.DATE_AKTUAL6    ELSE NULL END AS DATE_AKTUAL6,
                CASE WHEN i.AKJ = 'NON AKJ' THEN i.DATE_AKTUAL_TO6 ELSE NULL END AS DATE_AKTUAL_TO6,
                /* Jika tdk ada kolom 7, biarkan NULL */
                /* CASE WHEN i.AKJ = 'NON AKJ' THEN i.DATE_AKTUAL7    ELSE NULL END AS DATE_AKTUAL7, */
                /* CASE WHEN i.AKJ = 'NON AKJ' THEN i.DATE_AKTUAL_TO7 ELSE NULL END AS DATE_AKTUAL_TO7, */

                CASE WHEN i.AKJ = 'AKJ' THEN i.PROD_ORDER_AKJ  ELSE NULL END AS PROD_ORDER_AKJ,
                CASE WHEN i.AKJ = 'AKJ' THEN i.PROD_ORDER_AKJ2 ELSE NULL END AS PROD_ORDER_AKJ2,
                CASE WHEN i.AKJ = 'AKJ' THEN i.PROD_ORDER_AKJ3 ELSE NULL END AS PROD_ORDER_AKJ3,
                CASE WHEN i.AKJ = 'AKJ' THEN i.PROD_ORDER_AKJ4 ELSE NULL END AS PROD_ORDER_AKJ4,
                CASE WHEN i.AKJ = 'AKJ' THEN i.PROD_ORDER_AKJ5 ELSE NULL END AS PROD_ORDER_AKJ5,
                CASE WHEN i.AKJ = 'AKJ' THEN i.SALESORDER_AKJ  ELSE NULL END AS SALESORDER_AKJ,
                CASE WHEN i.AKJ = 'AKJ' THEN i.SALESORDER_AKJ2 ELSE NULL END AS SALESORDER_AKJ2,
                CASE WHEN i.AKJ = 'AKJ' THEN i.SALESORDER_AKJ3 ELSE NULL END AS SALESORDER_AKJ3,
                CASE WHEN i.AKJ = 'AKJ' THEN i.SALESORDER_AKJ4 ELSE NULL END AS SALESORDER_AKJ4,
                CASE WHEN i.AKJ = 'AKJ' THEN i.SALESORDER_AKJ5 ELSE NULL END AS SALESORDER_AKJ5,

                CASE WHEN i.AKJ = 'NON AKJ' THEN i.ADDITIONALDATA  ELSE NULL END AS ADDITIONALDATA,
                CASE WHEN i.AKJ = 'NON AKJ' THEN i.ADDITIONALDATA2 ELSE NULL END AS ADDITIONALDATA2,
                CASE WHEN i.AKJ = 'NON AKJ' THEN i.ADDITIONALDATA3 ELSE NULL END AS ADDITIONALDATA3,
                CASE WHEN i.AKJ = 'NON AKJ' THEN i.ADDITIONALDATA4 ELSE NULL END AS ADDITIONALDATA4,
                CASE WHEN i.AKJ = 'NON AKJ' THEN i.ADDITIONALDATA5 ELSE NULL END AS ADDITIONALDATA5
            FROM ITXVIEWKK i
            WHERE i.PRODUCTIONORDERCODE = '$prodOrder'
            AND i.PRODUCTIONDEMANDCODE = '$prodDemand'";

$res  = db2_exec($conn1, $qPOGreige);
$row  = db2_fetch_assoc($res) ?: [];

// Tentukan subcode04 sesuai tipe
$subcode04 = ($row['ITEMTYPEAFICODE'] ?? '') === 'KFF'
    ? ($row['RESERVATION_SUBCODE04'] ?? '')
    : ($row['SUBCODE04'] ?? '');

// RAJUT
$qRajut = "SELECT
                SUMMARIZEDDESCRIPTION AS BENANG,
                CODE AS PO_GREIGE,
                COALESCE(SUBSTR(CAST(TGLRENCANA AS DATE), 1,10), '') || ', ' ||
                COALESCE(SUBSTR(CAST(TGLPOGREIGE AS DATE), 1,10), '') AS TGL
            FROM ITXVIEW_RAJUT
            WHERE SUBCODE01 = '{$row['SUBCODE01']}'
                AND SUBCODE02 = '{$row['SUBCODE02']}'
                AND SUBCODE03 = '{$row['SUBCODE03']}'
                AND SUBCODE04 = '$subcode04'
                AND ORIGDLVSALORDLINESALORDERCODE = '{$row['PROJECTCODE']}'
                AND (ITEMTYPEAFICODE='KGF' OR ITEMTYPEAFICODE='FKG')";
$rRajut = db2_exec($conn1, $qRajut);
$rowRajut = db2_fetch_assoc($rRajut) ?: [];

// BOOKING BELUM READY (ambil sampai 5 kemungkinan)
function fetchBlmReady($conn, $row, $keyAdditional)
{
    $q = "SELECT
            ORIGDLVSALORDLINESALORDERCODE AS PO_GREIGE,
            COALESCE(SUMMARIZEDDESCRIPTION,'') || COALESCE(ORIGDLVSALORDLINESALORDERCODE,'') AS BENANG,
            COALESCE(SUBSTR(CAST(TGLRENCANA AS DATE), 1,10),'') || ', ' ||
            COALESCE(SUBSTR(CAST(TGLPOGREIGE AS DATE), 1,10),'') AS TGL
        FROM ITXVIEW_BOOKING_BLM_READY
        WHERE SUBCODE01 = '{$row['SUBCODE01']}'
            AND SUBCODE02 = '{$row['SUBCODE02']}'
            AND SUBCODE03 = '{$row['SUBCODE03']}'
            AND SUBCODE04 = '{$row['RESERVATION_SUBCODE04']}'
            AND ORIGDLVSALORDLINESALORDERCODE = '{$row[$keyAdditional]}'
            AND (ITEMTYPEAFICODE='KGF' OR ITEMTYPEAFICODE='FKG')";
    $res = db2_exec($conn, $q);
    return db2_fetch_assoc($res) ?: [];
}
$rowBlmReady  = $row['ADDITIONALDATA']  ? fetchBlmReady($conn1, $row, 'ADDITIONALDATA')  : [];
$rowBlmReady2 = $row['ADDITIONALDATA2'] ? fetchBlmReady($conn1, $row, 'ADDITIONALDATA2') : [];
$rowBlmReady3 = $row['ADDITIONALDATA3'] ? fetchBlmReady($conn1, $row, 'ADDITIONALDATA3') : [];
$rowBlmReady4 = $row['ADDITIONALDATA4'] ? fetchBlmReady($conn1, $row, 'ADDITIONALDATA4') : [];
$rowBlmReady5 = $row['ADDITIONALDATA5'] ? fetchBlmReady($conn1, $row, 'ADDITIONALDATA5') : [];

// BOOKING READY
$qReady = "SELECT
            PROJECTCODE AS PO_GREIGE,
            SUMMARIZEDDESCRIPTION AS BENANG,
            COALESCE(SUBSTR(CAST(TGLRENCANA AS DATE), 1,10),'') || ', ' ||
            COALESCE(SUBSTR(CAST(TGLPOGREIGE AS DATE), 1,10),'') AS TGL
        FROM ITXVIEW_BOOKING_NEW
        WHERE SALESORDERCODE = '{$row['PROJECTCODE']}' AND ORDERLINE = '{$row['ORDERLINE']}'";
$rReady = db2_exec($conn1, $qReady);
$rowReady = db2_fetch_assoc($rReady) ?: [];

// ====== HELPERS ======
function h($v)
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
function d10($v)
{
    if (!$v) return '';
    return substr((string)$v, 0, 10);
} // aman untuk DATE/TIMESTAMP string
function nonEmpty($v)
{
    return isset($v) && $v !== '' && $v !== null;
}

// Kumpulkan semua tanggal KK yang ada (hapus kolom _7 jika DB tidak punya)
$dateFields = [
    'DATE_AKTUAL',
    'DATE_AKTUAL_TO',
    'DATE_AKTUAL2',
    'DATE_AKTUAL_TO2',
    'DATE_AKTUAL3',
    'DATE_AKTUAL_TO3',
    'DATE_AKTUAL4',
    'DATE_AKTUAL_TO4',
    'DATE_AKTUAL5',
    'DATE_AKTUAL_TO5',
    'DATE_AKTUAL6',
    'DATE_AKTUAL_TO6',
    'DATE_AKTUAL7','DATE_AKTUAL_TO7'
];
$datesKK = [];
foreach ($dateFields as $k) {
    if (nonEmpty($row[$k] ?? null)) $datesKK[] = d10($row[$k]);
}
sort($datesKK); // ascending

$earliestKK  = $datesKK[0] ?? null;
$bookingInfo = implode(', ', array_filter($datesKK));

// Bila KK tak punya tanggal, tampilkan fallback sumber luar (Rajut/Booking)
$fallbackSumber = array_filter([
    $rowRajut['TGL']    ?? '',
    $rowBlmReady['TGL'] ?? '',
    $rowBlmReady2['TGL'] ?? '',
    $rowBlmReady3['TGL'] ?? '',
    $rowBlmReady4['TGL'] ?? '',
    $rowBlmReady5['TGL'] ?? '',
    $rowReady['TGL']    ?? '',
]);

// Lead Time = Delivery Actual - Earliest Greige (KK)
// Jika Earliest Greige (KK) kosong, ambil tanggal terkecil dari fallback sumber (rowRajut, rowReady, rowBlmReady dst)
function ambilTanggalTerkecilFallback($fallbackSumber) {
    $tgls = [];
    foreach ($fallbackSumber as $str) {
        // Pisahkan jika ada koma (misal: "2025-09-01, 2025-09-01")
        foreach (explode(',', $str) as $tgl) {
            $tgl = trim($tgl);
            // Validasi format tanggal sederhana (YYYY-MM-DD)
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl)) {
                $tgls[] = $tgl;
            }
        }
    }
    sort($tgls);
    return $tgls[0] ?? null;
}

$leadTime = '-';
$earliestGreigeUsed = $earliestKK;
if (!nonEmpty($earliestKK)) {
    // Ambil dari fallback sumber
    $earliestGreigeUsed = ambilTanggalTerkecilFallback($fallbackSumber);
}
echo $earliestGreigeUsed;
if (nonEmpty($delActual) && nonEmpty($earliestGreigeUsed)) {
    $d1 = new DateTime($earliestGreigeUsed);
    $d2 = new DateTime(d10($delActual));
    $leadTime = $d2->diff($d1)->days . ' hari';
}

// Kumpulan AKJ
$prodOrders = array_filter([
    $row['PROD_ORDER_AKJ']  ?? '',
    $row['PROD_ORDER_AKJ2'] ?? '',
    $row['PROD_ORDER_AKJ3'] ?? '',
    $row['PROD_ORDER_AKJ4'] ?? '',
    $row['PROD_ORDER_AKJ5'] ?? '',
]);
$salesOrders = array_filter([
    $row['SALESORDER_AKJ']  ?? '',
    $row['SALESORDER_AKJ2'] ?? '',
    $row['SALESORDER_AKJ3'] ?? '',
    $row['SALESORDER_AKJ4'] ?? '',
    $row['SALESORDER_AKJ5'] ?? '',
]);
$adds = array_filter([
    $row['ADDITIONALDATA']  ?? '',
    $row['ADDITIONALDATA2'] ?? '',
    $row['ADDITIONALDATA3'] ?? '',
    $row['ADDITIONALDATA4'] ?? '',
    $row['ADDITIONALDATA5'] ?? '',
]);

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Booking & Greige Timeline</title>
    <style>
        :root {
            --bg: #0b1220;
            --panel: #0f172a;
            --card: #111827;
            --muted: #9ca3af;
            --text: #e5e7eb;
            --brand: #22d3ee;
            --violet: #a78bfa;
            --green: #34d399;
            --amber: #fbbf24;
            --red: #f87171;
            --line: #1f2937;
            --chip: #162032;
            --chip-br: #2a3a55;
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Arial;
            background:
                radial-gradient(1000px 500px at -10% -10%, rgba(34, 211, 238, .18), transparent 45%),
                radial-gradient(800px 480px at 120% 0%, rgba(167, 139, 250, .16), transparent 40%),
                var(--bg);
            color: var(--text);
            padding: 24px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            gap: 18px
        }

        .header {
            background: linear-gradient(180deg, rgba(255, 255, 255, .02), transparent 40%), var(--panel);
            border: 1px solid rgba(255, 255, 255, .06);
            border-radius: 18px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap
        }

        .title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            letter-spacing: .3px
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--brand);
            box-shadow: 0 0 16px var(--brand)
        }

        .meta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap
        }

        .pill {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .12);
            background: rgba(255, 255, 255, .03);
            font-size: 12px
        }

        .grid {
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 18px
        }

        @media (max-width:900px) {
            .grid {
                grid-template-columns: 1fr
            }
        }

        .card {
            background: linear-gradient(180deg, rgba(255, 255, 255, .02), transparent 30%), var(--card);
            border: 1px solid rgba(255, 255, 255, .06);
            border-radius: 18px;
            padding: 16px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, .35)
        }

        h3 {
            margin: 0 0 10px;
            font-size: 15.5px;
            font-weight: 800;
            letter-spacing: .3px
        }

        .muted {
            color: var(--muted)
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px
        }

        .chip {
            padding: 6px 10px;
            border-radius: 999px;
            background: var(--chip);
            border: 1px solid var(--chip-br);
            font-size: 12px
        }

        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 10px 0;
            border-bottom: 1px dashed rgba(255, 255, 255, .08)
        }

        .row:last-child {
            border-bottom: none
        }

        .mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, "Courier New", monospace
        }

        .kpis {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 10px
        }

        @media (max-width:720px) {
            .kpis {
                grid-template-columns: 1fr
            }
        }

        .kpi {
            padding: 12px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, .12);
            background: linear-gradient(180deg, rgba(34, 211, 238, .08), transparent), rgba(255, 255, 255, .03)
        }

        .kpi .lbl {
            font-size: 12px;
            color: var(--muted)
        }

        .kpi .val {
            font-weight:900; 
            margin-top:4px; 
            color: var(--text); /* default */
        }

        /* Earliest Greige: warna cyan terang */
        .kpi:first-child .val {
            color: #06b6d4; /* cyan-500 */
            text-shadow: 0 0 6px rgba(6,182,212,0.7);
        }

        /* Lead Time: warna hijau terang */
        .kpi.green .val {
            color: #4ade80; /* green-400 */
            text-shadow: 0 0 6px rgba(74,222,128,0.7);
        }

        .kpi.green {
            background: linear-gradient(180deg, rgba(52, 211, 153, .10), transparent), rgba(255, 255, 255, .03)
        }

        .kpi.violet {
            background: linear-gradient(180deg, rgba(167, 139, 250, .10), transparent), rgba(255, 255, 255, .03)
        }

        .section {
            margin-top: 12px
        }

        .list {
            display: grid;
            gap: 10px
        }

        .item {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 12px;
            border: 1px dashed rgba(255, 255, 255, .12);
            border-radius: 12px;
            background: rgba(255, 255, 255, .02)
        }

        .badge {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .14);
            background: rgba(255, 255, 255, .03)
        }

        .warn {
            color: var(--amber)
        }

        .ok {
            color: var(--green)
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- HEADER -->
        <div class="header">
            <div class="title"><span class="dot"></span><span>Booking & Greige Timeline</span></div>
            <div class="meta">
                <div class="pill">Prod Order: <span class="mono"><?= h($prodOrder) ?></span></div>
                <div class="pill">Demand: <span class="mono"><?= h($prodDemand) ?></span></div>
                <div class="pill">Project: <span class="mono"><?= h($row['PROJECTCODE'] ?? '-') ?></span></div>
                <div class="pill">Order Line: <span class="mono"><?= h($row['ORDERLINE'] ?? '-') ?></span></div>
            </div>
        </div>

        <div class="grid">
            <!-- KIRI -->
            <div class="card">
                <h3>Ringkasan</h3>
                <div class="kpis">
                    <div class="kpi">
                        <div class="lbl">Earliest Greige (KK)</div>
                        <div class="val mono">
                            <?php
                            if ($earliestKK) {
                                echo h($earliestKK);
                            } elseif ($earliestGreigeUsed) {
                                echo h($earliestGreigeUsed);
                            } else {
                                echo '<span class="muted">-</span>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="kpi violet">
                        <div class="lbl">Delivery Actual</div>
                        <div class="val mono"><?= nonEmpty($delActual) ? h(d10($delActual)) : '<span class="muted">-</span>' ?></div>
                    </div>
                    <div class="kpi green">
                        <div class="lbl">Lead Time</div>
                        <div class="val mono"><?= h($leadTime) ?></div>
                    </div>
                </div>

                <div class="section">
                    <div class="row">
                        <div class="muted">Informasi PO Greige & Delivery</div>
                        <div class="badge"><?= count($datesKK) ?> item</div>
                    </div>
                    <div class="chips">
                        <?php if (empty($datesKK)): ?>
                            <span class="muted">Tidak ada tanggal pada KK.</span>
                            <?php else: foreach ($datesKK as $d): ?>
                                <span class="chip"><?= h($d) ?></span>
                        <?php endforeach; endif; ?>
                    </div>
                    <div class="chips">
                        <?php if (empty($adds)): ?><span class="chip">-</span>
                            <?php else: foreach ($adds as $v): ?><span class="chip"><?= h($v) ?></span><?php endforeach;
                                                        endif; ?>
                    </div>
                    <div class="chips">
                        <?php if (empty($prodOrders)): ?><span class="chip">-</span>
                            <?php else: foreach ($prodOrders as $v): ?><span class="chip"><?= h($v) ?></span><?php endforeach;
                                                                                                    endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fallback Sumber (jika KK kosong) -->
        <?php if (empty($datesKK)): ?>
            <div class="card">
                <h3>Rajut, Booking Blm Ready, Booking Ready.</h3>
                <div class="list">
                    <?php
                    $srcs = [
                        'Rajut'         => $rowRajut,
                        'Booking Ready' => $rowReady,
                        'Blm Ready #1'  => $rowBlmReady,
                        'Blm Ready #2'  => $rowBlmReady2,
                        'Blm Ready #3'  => $rowBlmReady3,
                        'Blm Ready #4'  => $rowBlmReady4,
                        'Blm Ready #5'  => $rowBlmReady5,
                    ];
                    $any = false;
                    foreach ($srcs as $lbl => $r):
                        if (!nonEmpty($r['TGL'] ?? '')) continue;
                        $any = true;
                    ?>
                        <div class="item">
                            <div>
                                <div class="mono"><?= h($lbl) ?></div>
                                <div class="muted" style="font-size:12px">
                                    <?= h($r['BENANG'] ?? ($r['PO_GREIGE'] ?? '-')) ?>
                                </div>
                            </div>
                            <div class="mono"><?= h($r['TGL']) ?></div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (!$any): ?>
                        <div class="muted">Silahkan periksa data anda kembali pada system NOW</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</body>

</html>
<?php
/**
 * Helper untuk monitoring koneksi agar bisa dipakai ulang (web page & AJAX).
 */
function statusRow($label, $target, $type, $status, $detail = '', $durationMs = null)
{
    $badgeClass = [
        'UP'     => 'badge-success',
        'ISSUE'  => 'badge-warning',
        'DOWN'   => 'badge-danger'
    ][$status] ?? 'badge-secondary';

    return [
        'label'  => $label,
        'target' => $target,
        'type'   => $type,
        'status' => $status,
        'class'  => $badgeClass,
        'detail' => $detail,
        'ms'     => $durationMs
    ];
}

function checkSqlsrv($label, $target, $conn)
{
    $start = microtime(true);
    if (!$conn) {
        return statusRow($label, $target, 'SQL Server', 'DOWN', 'Tidak bisa connect', round((microtime(true) - $start) * 1000, 1));
    }

    $stmt = @sqlsrv_query($conn, 'SELECT 1');
    if ($stmt === false) {
        $err = sqlsrv_errors();
        $msg = $err[0]['message'] ?? 'Tes query gagal';
        return statusRow($label, $target, 'SQL Server', 'ISSUE', $msg, round((microtime(true) - $start) * 1000, 1));
    }
    sqlsrv_free_stmt($stmt);
    return statusRow($label, $target, 'SQL Server', 'UP', 'OK', round((microtime(true) - $start) * 1000, 1));
}

function checkMysqli($label, $target, $conn)
{
    $start = microtime(true);
    if (!$conn) {
        return statusRow($label, $target, 'MySQL', 'DOWN', 'Tidak bisa connect', round((microtime(true) - $start) * 1000, 1));
    }

    if (!@mysqli_ping($conn)) {
        return statusRow($label, $target, 'MySQL', 'ISSUE', mysqli_error($conn), round((microtime(true) - $start) * 1000, 1));
    }

    return statusRow($label, $target, 'MySQL', 'UP', 'OK', round((microtime(true) - $start) * 1000, 1));
}

function checkDb2($label, $target, $conn)
{
    $start = microtime(true);
    if (!$conn) {
        return statusRow($label, $target, 'DB2', 'DOWN', 'Tidak bisa connect', round((microtime(true) - $start) * 1000, 1));
    }

    $stmt = @db2_exec($conn, 'SELECT 1 FROM SYSIBM.SYSDUMMY1');
    if (!$stmt) {
        return statusRow($label, $target, 'DB2', 'ISSUE', db2_stmt_errormsg(), round((microtime(true) - $start) * 1000, 1));
    }
    db2_free_stmt($stmt);
    return statusRow($label, $target, 'DB2', 'UP', 'OK', round((microtime(true) - $start) * 1000, 1));
}

function checkPdo($label, $target, $pdo)
{
    global $pdo_errors;
    $start = microtime(true);
    if (!$pdo) {
        $detail = isset($pdo_errors[$label]) ? $pdo_errors[$label] : 'Tidak bisa connect';
        return statusRow($label, $target, 'PDO (SQL Server)', 'DOWN', $detail, round((microtime(true) - $start) * 1000, 1));
    }

    try {
        $pdo->query('SELECT 1');
        return statusRow($label, $target, 'PDO (SQL Server)', 'UP', 'OK', round((microtime(true) - $start) * 1000, 1));
    } catch (PDOException $e) {
        return statusRow($label, $target, 'PDO (SQL Server)', 'ISSUE', $e->getMessage(), round((microtime(true) - $start) * 1000, 1));
    }
}

function getConnectionStatuses()
{
    global $conn_sql, $conn_sql2, $conn_cams, $conn1,
           $con_invoice, $con_db_dyeing, $con_db_finishing, $con_db_lab, $con_dbnow_mkt,
           $con_rec, $con_db_qc, $con_hrd, $con_now_gerobak,
           $con_nowprd, $con_db_ppc, $con_finishing,
           $pdo, $pdo_invoice, $pdo_orgatex, $pdo_orgatex_main;

    return [
        checkSqlsrv('TICKET', '10.0.4.8 / TICKET', $conn_sql),
        checkSqlsrv('LA1000_Exchange', '10.0.4.8 / LA1000_Exchange', $conn_sql2),
        checkSqlsrv('TAICHEN_CAMS_LIVE', 'S-CATS / TAICHEN_CAMS_LIVE', $conn_cams),
        checkDb2('DB2 NOWPRD', '10.0.0.21:25000 / NOWPRD', $conn1),
        checkMysqli('Invoice', '10.0.0.10 / invoice', $con_invoice),
        checkSqlsrv('DB Dyeing (SQLSVR19)', '10.0.0.221 / db_dying', $con_db_dyeing),
        checkSqlsrv('DB Finishing (SQLSVR19)', '10.0.0.221 / db_finishing', $con_db_finishing),
        checkSqlsrv('DB Laborat (SQLSVR19)', '10.0.0.221 / db_laborat', $con_db_lab),
        checkMysqli('DB NOW MKT', '10.0.0.10 / dbnow_mkt', $con_dbnow_mkt),
        checkMysqli('Approval Document', '10.0.0.10 / approval_document', $con_rec),
        checkSqlsrv('DB QC (SQLSVR19)', '10.0.0.221 / db_qc', $con_db_qc),
        checkMysqli('HRD', '10.0.0.10 / hrd', $con_hrd),
        checkMysqli('DB NOW Gerobak', '10.0.0.10 / dbnow_gerobak', $con_now_gerobak),
        checkSqlsrv('NOWPRD (SQLSVR19)', '10.0.0.221 / nowprd', $con_nowprd),
        checkSqlsrv('DB_PPC (SQLSVR19)', '10.0.0.221 / test_ppc', $con_db_ppc),
        checkSqlsrv('DB Finishing (SQLSVR19)', '10.0.0.221 / db_finishing', $con_finishing),
        checkPdo('PDO NOWPRD', '10.0.0.221 / nowprd', $pdo),
        checkPdo('PDO Invoice', '10.0.0.221 / invoice', $pdo_invoice),
        checkPdo('PDO ORGATEX INTEG', '10.0.0.210 / ORGATEX-INTEG', $pdo_orgatex),
        checkPdo('PDO ORGATEX', '10.0.0.210 / ORGATEX', $pdo_orgatex_main),
    ];
}

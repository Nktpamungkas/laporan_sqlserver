<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
date_default_timezone_set('Asia/Jakarta');

/**
 * Log connection failures without stopping the app, so the report page still opens.
 */
function noteConnectionFailure($name, $errors = null)
{
    $message = $name . " connection failed";
    if ($errors) {
        $message .= " - " . (is_array($errors) ? print_r($errors, true) : $errors);
    }
    error_log($message);
}

function safeSqlsrvConnect($host, array $connInfo, $label)
{
    // Batas waktu login supaya tidak nge-hang kalau server mati
    $conn = sqlsrv_connect($host, array_merge(["LoginTimeout" => 2, "ConnectRetryCount" => 1, "ConnectRetryInterval" => 1], $connInfo));
    if ($conn === false) {
        noteConnectionFailure($label, sqlsrv_errors());
    }
    return $conn;
}

function safeMysqliConnect($host, $user, $password, $db, $label)
{
    // Set timeout singkat agar tidak lama saat host mati
    $conn = mysqli_init();
    mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, 2);
    @mysqli_real_connect($conn, $host, $user, $password, $db);
    if (!$conn) {
        noteConnectionFailure($label, mysqli_connect_error());
    }
    return $conn;
}

function safeDb2Connect($connString, $label)
{
    // Tambahkan CONNECTTIMEOUT jika belum ada
    if (stripos($connString, 'CONNECTTIMEOUT=') === false) {
        $connString .= ';CONNECTTIMEOUT=2';
    }
    $conn = @db2_connect($connString, '', '');
    if (!$conn) {
        noteConnectionFailure($label, db2_conn_errormsg());
    }
    return $conn;
}

// Simpan pesan error PDO per label agar bisa ditampilkan di monitoring
$pdo_errors = [];

function safePdo($dsn, $user, $password, $label)
{
    global $pdo_errors;
    try {
        // Tambah LoginTimeout di DSN jika belum ada
        if (stripos($dsn, 'LoginTimeout=') === false) {
            $dsn .= ';LoginTimeout=5';
        }
        $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        return $pdo;
    } catch (PDOException $e) {
        noteConnectionFailure($label, $e->getMessage());
        $pdo_errors[$label] = $e->getMessage();
        return null;
    }
}

$host     = "10.0.4.8";
$username = "sa";
$password = "Q?o*]vdjDb#w";
$db_name  = "TICKET";
$connInfo = ["Database" => $db_name, "UID" => $username, "PWD" => $password];
$conn_sql = safeSqlsrvConnect($host, $connInfo, "TICKET");

$host      = "10.0.4.8";
$username  = "sa";
$password  = "Q?o*]vdjDb#w";
$db_name   = "LA1000_Exchange";
$connInfo  = ["Database" => $db_name, "UID" => $username, "PWD" => $password];
$conn_sql2 = safeSqlsrvConnect($host, $connInfo, "LA1000_Exchange");

$host      = "S-CATS";
$username  = "progm";
$password  = "BW#bbfW";
$db_name   = "TAICHEN_CAMS_LIVE";
$connInfo  = ["Database" => $db_name, "UID" => $username, "PWD" => $password];
$conn_cams = safeSqlsrvConnect($host, $connInfo, "TAICHEN_CAMS_LIVE");

$hostname = "10.0.0.21";
// $database = "NOWTEST"; // SERVER NOW 20
$database    = "NOWPRD"; // SERVER NOW 22
$user        = "db2admin";
$passworddb2 = "Sunkam@24809";
$port        = "25000";
$conn_string = "DRIVER={IBM ODBC DB2 DRIVER}; HOSTNAME=$hostname; PORT=$port; PROTOCOL=TCPIP; UID=$user; PWD=$passworddb2; DATABASE=$database;";
// $conn1 = db2_pconnect($conn_string,'', '');
$conn1 = safeDb2Connect($conn_string, "DB2 NOWPRD");

// $con_nowprd         = safeMysqliConnect("10.0.0.10", "dit", "4dm1n", "nowprd", "nowprd");
$con_invoice      = safeMysqliConnect("10.0.0.10", "dit", "4dm1n", "invoice", "invoice");
// Dialihkan ke SQL Server (lihat bagian bawah).
// $con_db_dyeing    = safeMysqliConnect("10.0.0.10", "dit", "4dm1n", "db_dying", "db_dying");
// Dialihkan ke SQL Server (lihat $con_finishing / $con_db_finishing di bawah).
// $con_db_finishing = safeMysqliConnect("10.0.0.10", "dit", "4dm1n", "db_finishing", "db_finishing");
// db_laborat sudah pindah ke SQL Server (lihat $con_db_lab di bawah)
// $con_db_lab       = safeMysqliConnect("10.0.0.10", "dit", "4dm1n", "db_laborat", "db_laborat");
$con_dbnow_mkt    = safeMysqliConnect("10.0.0.10", "dit", "4dm1n", "dbnow_mkt", "dbnow_mkt");
$con_rec          = safeMysqliConnect("10.0.0.10", "dit", "4dm1n", "approval_document", "approval_document");
$con_db_qc        = safeMysqliConnect("10.0.0.10", "dit", "4dm1n", "db_qc", "db_qc");
$con_hrd          = safeMysqliConnect("10.0.0.10", "dit", "4dm1n", "hrd", "hrd");
$con_now_gerobak  = safeMysqliConnect("10.0.0.10", "dit", "4dm1n", "dbnow_gerobak", "dbnow_gerobak");

$hostSVR19     = "10.0.0.221";
$usernameSVR19 = "sa";
$passwordSVR19 = "Ind@taichen2024";
$invoice       = "invoice";
$nowprd        = "nowprd";
$dying         = "db_dying";
$finishing     = "db_finishing";
$qc            = "db_qc";
$lab           = "db_laborat";
$hrd           = "hrd";
$db_nowmkt     = "dbnow_mkt";
$db_ppc        = "test_ppc";

$nowprdd = ["Database" => $nowprd, "UID" => $usernameSVR19, "PWD" => $passwordSVR19];
$nowppc  = ["Database" => $db_ppc, "UID" => $usernameSVR19, "PWD" => $passwordSVR19];

$db_dying = ["Database" => $dying, "UID" => $usernameSVR19, "PWD" => $passwordSVR19];
$dbLab = ["Database" => $lab, "UID" => $usernameSVR19, "PWD" => $passwordSVR19];
// $db_qc = array("Database" => $qc, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);
// $db_hrd = array("Database" => $hrd, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);
// $db_invoice = array("Database" => $invoice, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);
$db_finishing = ["Database" => $finishing, "UID" => $usernameSVR19, "PWD" => $passwordSVR19];
// $db_nowmkt = array("Database" => $db_nowmkt, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);

$con_nowprd = safeSqlsrvConnect($hostSVR19, $nowprdd, "con_nowprd");
$con_db_ppc = safeSqlsrvConnect($hostSVR19, $nowppc, "con_db_ppc");
$con_db_dyeing = safeSqlsrvConnect($hostSVR19, $db_dying, "con_db_dyeing");
// $con_db_qc = safeSqlsrvConnect($hostSVR19, $db_qc, "con_db_qc");
$con_db_lab = safeSqlsrvConnect($hostSVR19, $dbLab, "con_db_lab");
// $con_hrd = safeSqlsrvConnect($hostSVR19, $db_hrd, "con_hrd");
// $con_invoice = safeSqlsrvConnect($hostSVR19, $db_invoice, "con_invoice");
$con_finishing = safeSqlsrvConnect($hostSVR19, $db_finishing, "con_finishing");
// Alias agar kompatibel dengan kode lama yang masih memakai $con_db_finishing
$con_db_finishing = $con_finishing;
// $con_dbnow_mkt = safeSqlsrvConnect($hostSVR19, $db_nowmkt, "con_dbnow_mkt");

// orgatex
$pdo_orgatex = safePdo("sqlsrv:server=10.0.0.210;Database=ORGATEX-INTEG", "orgatex", "kYrgEP6@", "pdo_orgatex_integ");

// orgatex
$pdo_orgatex_main = safePdo("sqlsrv:server=10.0.0.210;Database=ORGATEX", "orgatex", "kYrgEP6@", "pdo_orgatex_main");

// online pdo
$pdo = safePdo("sqlsrv:server=10.0.0.221;Database=nowprd", "sa", "Ind@taichen2024", "pdo_nowprd");

$pdo_invoice = safePdo("sqlsrv:server=10.0.0.221;Database=invoice", "sa", "Ind@taichen2024", "pdo_invoice");

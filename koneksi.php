<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
date_default_timezone_set('Asia/Jakarta');
$host       = "10.0.4.8";
$username   = "sa";
$password   = "Q?o*]vdjDb#w";
$db_name    = "TICKET";
$connInfo   = array("Database" => $db_name, "UID" => $username, "PWD" => $password);
$conn_sql   = sqlsrv_connect($host, $connInfo);
$host       = "10.0.4.8";
$username   = "sa";
$password   = "Q?o*]vdjDb#w";
$db_name    = "TICKET";
$connInfo   = array("Database" => $db_name, "UID" => $username, "PWD" => $password);
$conn_sql   = sqlsrv_connect($host, $connInfo);

$host       = "10.0.4.8";
$username   = "sa";
$password   = "Q?o*]vdjDb#w";
$db_name    = "LA1000_Exchange";
$connInfo   = array("Database" => $db_name, "UID" => $username, "PWD" => $password);
$conn_sql2  = sqlsrv_connect($host, $connInfo);
$host       = "10.0.4.8";
$username   = "sa";
$password   = "Q?o*]vdjDb#w";
$db_name    = "LA1000_Exchange";
$connInfo   = array("Database" => $db_name, "UID" => $username, "PWD" => $password);
$conn_sql2  = sqlsrv_connect($host, $connInfo);

$host       = "S-CATS";
$username   = "progm";
$password   = "BW#bbfW";
$db_name    = "TAICHEN_CAMS_LIVE";
$connInfo   = array("Database" => $db_name, "UID" => $username, "PWD" => $password);
$conn_cams  = sqlsrv_connect($host, $connInfo);
$host       = "S-CATS";
$username   = "progm";
$password   = "BW#bbfW";
$db_name    = "TAICHEN_CAMS_LIVE";
$connInfo   = array("Database" => $db_name, "UID" => $username, "PWD" => $password);
$conn_cams  = sqlsrv_connect($host, $connInfo);

$hostname       = "10.0.0.21";
// $database = "NOWTEST"; // SERVER NOW 20
$database       = "NOWPRD"; // SERVER NOW 22
$user           = "db2admin";
$passworddb2    = "Sunkam@24809";
$port           = "25000";
$conn_string    = "DRIVER={IBM ODBC DB2 DRIVER}; HOSTNAME=$hostname; PORT=$port; PROTOCOL=TCPIP; UID=$user; PWD=$passworddb2; DATABASE=$database;";
// $conn1 = db2_pconnect($conn_string,'', '');
$conn1          = db2_connect($conn_string, '', '');

// $con_invoice        = mysqli_connect("10.0.0.10", "dit", "4dm1n", "invoice");
// $con_nowprd         = mysqli_connect("10.0.0.10", "dit", "4dm1n", "nowprd");
// $con_db_dyeing      = mysqli_connect("10.0.0.10", "dit", "4dm1n", "db_dying");
// $con_db_finishing   = mysqli_connect("10.0.0.10", "dit", "4dm1n", "db_finishing");
// $con_db_lab         = mysqli_connect("10.0.0.10", "dit", "4dm1n", "db_laborat");
$con_dbnow_mkt      = mysqli_connect("10.0.0.10", "dit", "4dm1n", "dbnow_mkt");
// $con_db_qc          = mysqli_connect("10.0.0.10", "dit", "4dm1n", "db_qc");
// $con_hrd            = mysqli_connect("10.0.0.10", "dit", "4dm1n", "hrd");

$hostSVR19          = "10.0.0.221";
$usernameSVR19      = "sa";
$passwordSVR19      = "Ind@taichen2024";
$invoice            = "invoice";
$nowprd             = "nowprd";
$dying              = "db_dying";
$finishing          = "db_finishing";
$qc                 = "db_qc";
$lab                = "db_laborat";
$hrd                = "hrd";

$nowprdd        = array("Database" => $nowprd, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);
$db_dying       = array("Database" => $dying, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);
$dbLab          = array("Database" => $lab, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);
$db_qc          = array("Database" => $qc, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);
$db_hrd         = array("Database" => $hrd, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);
// $db_invoice     = array("Database" => $invoice, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);
$db_finishing   = array("Database" => $finishing, "UID" => $usernameSVR19, "PWD" => $passwordSVR19);

$con_nowprd     = sqlsrv_connect($hostSVR19, $nowprdd);
$con_db_dyeing  = sqlsrv_connect($hostSVR19, $db_dying);
$con_db_qc      = sqlsrv_connect($hostSVR19, $db_qc);
$con_db_lab     = sqlsrv_connect($hostSVR19, $dbLab);
$con_hrd        = sqlsrv_connect($hostSVR19, $db_hrd);
// $con_invoice    = sqlsrv_connect($hostSVR19, $db_invoice);
$con_finishing  = sqlsrv_connect($hostSVR19, $db_finishing);

if ($conn1) {
    // echo "koneksi berhasil";
} else {
    exit("DB2 Connection failed");
}

if ($con_nowprd) {
} else {
    exit("SQLSVR19 Connection failed to con_nowprd");
}
if ($con_db_dyeing) {
} else {
    exit("SQLSVR19 Connection failed to con_db_dyeing");
}
if ($con_db_qc) {
} else {
    exit("SQLSVR19 Connection failed to con_db_qc");
}
if ($con_db_lab) {
} else {
    exit("SQLSVR19 Connection failed to con_db_lab");
}
if ($con_hrd) {
} else {
    exit("SQLSVR19 Connection failed to con_hrd");
}
// if ($con_invoice) {
// } else {
//     exit("SQLSVR19 Connection failed to con_invoice");
// }
if ($con_finishing) {
} else {
    exit("SQLSVR19 Connection failed to con_finishing");
}

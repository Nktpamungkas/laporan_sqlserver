<?php
require_once "koneksi.php";
require_once "utils/helper.php";
$now = new DateTime();

$startDate = $now;
$endDate = $now;

if (isset($_GET['tgl']) && isset($_GET['time']) && isset($_GET['tgl2']) && isset($_GET['time2'])) {
    if ($_GET['tgl'] && $_GET['time']) {
        $startDate = $_GET['tgl'] . ' ' . $_GET['time'];
    }

    if ($_GET['tgl2'] && $_GET['time2']) {
        $endDate = $_GET['tgl2'] . ' ' . $_GET['time2'];
    }
}

if (isset($_GET['time_range'])) {
    $time_range = $_GET['time_range'];

    if ($time_range != 'custom') {
        switch ($time_range) {
            case '24_hours':
                $start_date = clone $now;
                $start_date->modify('-24 hours');
                $end_date = $now;
                $startDate = $start_date->format('Y-m-d H:i:s');
                $endDate = $end_date->format('Y-m-d H:i:s');
                break;

            case 'week':
                $start_date = clone $now;
                $start_date->modify('-1 week');
                $end_date = $now;
                $startDate = $start_date->format('Y-m-d H:i:s');
                $endDate = $end_date->format('Y-m-d H:i:s');
                break;

            case 'month':
                $start_date = clone $now;
                $start_date->modify('-1 month');
                $end_date = $now;
                $startDate = $start_date->format('Y-m-d H:i:s');
                $endDate = $end_date->format('Y-m-d H:i:s');
                break;

            default:
                echo "Please select a valid option.";
        }
    }
}

$data = [];

$machineID = $_GET['machine_no'];

$sqlLogs = "SELECT LogTimeStamp as logTimeStamp,
            MachineProtocol.AlarmNo as value,
            AlarmList.AlarmText as reason
            FROM MachineProtocol
            LEFT JOIN AlarmList ON AlarmList.AlarmNo = MachineProtocol.AlarmNo
            WHERE MachineProtocol.Machine = :machineID AND
            MachineProtocol.LogTimeStamp BETWEEN :startDate AND :endDate
            ORDER BY MachineProtocol.LogTimeStamp";

$stmtLogs = $pdo_orgatex->prepare($sqlLogs);
$stmtLogs->bindParam(':machineID', $machineID);
$stmtLogs->bindParam(':startDate', $startDate);
$stmtLogs->bindParam(':endDate', $endDate);
$stmtLogs->execute();

$rows = $stmtLogs->fetchAll(PDO::FETCH_ASSOC);

$totalSeconds = 0;
foreach ($rows as $key => $row) {
    if ($row['value'] > 500 && isset($rows[$key + 1]) && $rows[$key + 1]['value'] == 0) {
        $date1 = new DateTime($row['logTimeStamp']);
        $date2 = new DateTime($rows[$key + 1]['logTimeStamp']);
        $interval = $date1->diff($date2);

        $seconds = ($interval->days * 24 * 60 * 60) +
        ($interval->h * 60 * 60) +
        ($interval->i * 60) +
        $interval->s;

        $totalSeconds += $seconds;
    }
}

$hours = floor($totalSeconds / 3600);
$minutes = floor(($totalSeconds % 3600) / 60);
$seconds = $totalSeconds % 60;
$totalStop = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

$startDateTime = new DateTime($startDate);
$endDateTime = new DateTime($endDate);
$timeSpanInterval = $startDateTime->diff($endDateTime);

$totalSecondsCalculate = ($timeSpanInterval->days * 24 * 60 * 60) +
($timeSpanInterval->h * 60 * 60) +
($timeSpanInterval->i * 60) +
$timeSpanInterval->s;

if ($totalSeconds > 0 && $totalSecondsCalculate > 0) {
    $totalPercentage = (($totalSecondsCalculate - $totalSeconds) / $totalSecondsCalculate) * 100;
    $statusMachine = true;
} else {
    $totalPercentage = 0;
    $statusMachine = false;
}

$resultDataMachine = mysqli_query($con_db_dyeing, "SELECT kapasitas as machine_capacity,
        ket as machine_description FROM tbl_mesin WHERE no_mesin = '$machineID'");

if (mysqli_num_rows($resultDataMachine) > 0) {
    $row = mysqli_fetch_assoc($resultDataMachine);
    $machine_capacity = $row["machine_capacity"];
    $machine_description = $row["machine_description"];
} else {
    $machine_capacity = null;
    $machine_description = null;
}

$data = [
    "machine_number" => $machineID,
    "persentase_efficiency_machine" => round($totalPercentage, 2),
    "status_machine" => $statusMachine,
];

header('Content-Type: application/json');

echo json_encode($data);

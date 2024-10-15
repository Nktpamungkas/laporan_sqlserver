<?php
    require_once 'koneksi.php';
    require_once "utils/helper.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if($_POST['tgl']&&$_POST['time']){
        $startDate = $_POST['tgl'].' '.$_POST['time'];
    }

    if($_POST['tgl2']&&$_POST['time2']){
        $endDate = $_POST['tgl2'].' '.$_POST['time2'];
    }

    $data = [];

    $machineID = $_POST['machine_id'];

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

    foreach ($rows as $key => $row) {
        if ($row['value'] > 500 && isset($rows[$key + 1]) && $rows[$key + 1]['value'] == 0) {
            $date1 = new DateTime($row['logTimeStamp']);
            $date2 = new DateTime($rows[$key + 1]['logTimeStamp']);

            $log_timestamp_start = $date1->format('Y-m-d H:i:s');
            $log_timestamp_stop = $date2->format('Y-m-d H:i:s');

            $interval = $date1->diff($date2);
            
            $seconds = ($interval->days * 24 * 60 * 60) +
                    ($interval->h * 60 * 60) +
                    ($interval->i * 60) +
                    $interval->s;
            
            $totalSeconds = $seconds;

            if(!empty($totalSeconds)){
                $reason=$row['reason'];
                $log_timestamp= $row['logTimeStamp'];

                $totalMinutes = floor($totalSeconds / 60);
                $remainingSeconds = $totalSeconds % 60;
                $totalHours = floor($totalMinutes / 60);
                $remainingMinutes = $totalMinutes % 60;

                $totalStopTime = sprintf("%d minutes %d seconds", $totalMinutes, $remainingSeconds);
                $totalStopHour = sprintf("%d hours %d minutes", $totalHours, $remainingMinutes);

                $startDateTime = new DateTime($startDate);
                $endDateTime = new DateTime($endDate);
                $timeSpanInterval = $startDateTime->diff($endDateTime);

                $totalHoursInTimeSpan = ($timeSpanInterval->days * 24) + $timeSpanInterval->h + ($timeSpanInterval->i / 60);

                $totalPercentage = ($totalHours / $totalHoursInTimeSpan) * 100;

                $data[] = [
                    "machine_number" => $machineID,
                    "log_timestamp"=> $log_timestamp,
                    "log_timestamp_start"=> $log_timestamp_start,
                    "log_timestamp_stop"=> $log_timestamp_stop,
                    "total_seconds" => $totalSeconds,
                    "total_minutes" => $totalStopTime,
                    "total_hour" => $totalStopHour,
                    "total_percentage" => round($totalPercentage, 2),
                    "reason"=>$reason
                ];
            }
        }
    }

    echo json_encode($data);
}
?>
<?php
    require_once 'koneksi.php';
    require_once "utils/helper.php";

    $now = new DateTime();
    
    $startDate = $now;
    $endDate = $now;

    if(isset($_POST['tgl'])&&isset($_POST['time'])&&isset($_POST['tgl2'])&&isset($_POST['time2'])){
        if($_POST['tgl']&&$_POST['time']){
            $startDate = $_POST['tgl'].' '.$_POST['time'];
        }

        if($_POST['tgl2']&&$_POST['time2']){
            $endDate = $_POST['tgl2'].' '.$_POST['time2'];
        }
    }

    if(isset($_POST['time_range'])){
        $time_range = $_POST['time_range'];

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
            $totalStop = '00:00:00';
            
            if(!empty($totalSeconds)){
                $reason=$row['reason'];
                $log_timestamp= $row['logTimeStamp'];

                $hours = floor($totalSeconds / 3600);
                $minutes = floor(($totalSeconds % 3600) / 60);
                $seconds = $totalSeconds % 60;
                $totalStop = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

                $data[] = [
                    "machine_number" => $machineID,
                    "log_timestamp"=> $log_timestamp,
                    "log_timestamp_start"=> $log_timestamp_start,
                    "log_timestamp_stop"=> $log_timestamp_stop,
                    "reason"=>$reason,
                    "total_stop"=>$totalStop
                ];
            }
        }
    }

    echo json_encode($data);

?>
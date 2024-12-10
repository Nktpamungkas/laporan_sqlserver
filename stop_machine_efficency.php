<?php 
    ini_set("error_reporting", 1);
    session_start();
    require_once "koneksi.php";
    require_once "utils/helper.php";
?>
<?php
// Mulai session
session_start();

// Set nilai-nilai $_POST ke dalam session saat formulir disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['tgl'] = $_POST['tgl'];
    $_SESSION['time'] = $_POST['time'];
    $_SESSION['tgl2'] = $_POST['tgl2'];
    $_SESSION['time2'] = $_POST['time2'];
    $_SESSION['time_range'] = $_POST['time_range'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>ORGATEX - EFFICENCY STOP MACHINE REPORT</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="#">
    <meta name="keywords"
        content="Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="#">
    <link rel="icon" href="files\assets\images\favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="files\bower_components\bootstrap\css\bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\icon\themify-icons\themify-icons.css">
    <link rel="stylesheet" type="text/css" href="files\assets\icon\icofont\css\icofont.css">
    <link rel="stylesheet" type="text/css" href="files\assets\icon\feather\css\feather.css">
    <link rel="stylesheet" type="text/css" href="files\assets\pages\prism\prism.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\style.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\jquery.mCustomScrollbar.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\pcoded-horizontal.min.css">
    <link rel="stylesheet" type="text/css"
        href="files\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\pages\data-table\css\buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="files\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
        href="files\assets\pages\data-table\extensions\buttons\css\buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\css\jquery.mCustomScrollbar.css">
</head>
<?php require_once 'header.php'; ?>

<body>
    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Filter Data</h5>
                                    </div>
                                    <div class="card-block">
                                        <form action="" method="post">
                                            <div class="row">
                                                <div class="col-sm-12 col-xl-2">
                                                    <h4 class="sub-title">Select Time Range</h4>
                                                    <div class="input-group input-group-sm">
                                                        <select id="timeRange" name="time_range" class="form-control" required onchange="toggleDateInputs()">
                                                            <option value="custom" <?php if (isset($_POST['submit']) && $_POST['time_range'] == 'custom') echo 'selected'; ?>>Custom Range</option>
                                                            <option value="24_hours" <?php if (isset($_POST['submit']) && $_POST['time_range'] == '24_hours') echo 'selected'; ?>>Last 24 Hours</option>
                                                            <option value="week" <?php if (isset($_POST['submit']) && $_POST['time_range'] == 'week') echo 'selected'; ?>>Last Week</option>
                                                            <option value="month" <?php if (isset($_POST['submit']) && $_POST['time_range'] == 'month') echo 'selected'; ?>>Last Month</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                        <div class="col-sm-12 col-xl-2 m-b-0">
                                                            <h4 class="sub-title">Tanggal Awal</h4>
                                                            <div class="input-group input-group-sm">
                                                                <input type="date" class="form-control" name="tgl" id="tglAwal"
                                                                    value="<?php if (isset($_POST['submit'])){ echo $_POST['tgl']; } ?>"
                                                                    onclick="this.showPicker()">
                                                                <input name="time" id="time" type="text" class="form-control" value="23:00" size="5" maxlength="5" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-xl-2 m-b-0">
                                                            <h4 class="sub-title">Tanggal Akhir</h4>
                                                            <div class="input-group input-group-sm">
                                                                <input type="date" class="form-control" name="tgl2" id="tglAkhir"
                                                                    value="<?php if (isset($_POST['submit'])){ echo $_POST['tgl2']; } ?>"
                                                                    onclick="this.showPicker()">
                                                                <input name="time2" id="time2" type="text" class="form-control" value="23:00" size="5" maxlength="5" required>
                                                            </div>
                                                        </div>
                                            
                                                <div class="col-sm-12 col-xl-2">
                                                    <h4 class="sub-title">&nbsp;</h4>
                                                    <button type="submit" name="submit" class="btn btn-primary btn-sm">
                                                        <i class="icofont icofont-search-alt-1"></i> Cari data
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php if (isset($_POST['submit'])) : ?>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header table-card-header">
                                                <h5>EFFICENCY STOP MACHINE REPORT</h5>
                                            </div>
                                            <div class="card-block">
                                                <div class="dt-responsive table-responsive">
                                                    <table id="basic-btn"
                                                        class="table compact table-striped table-bordered nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th>Ip Address</th>
                                                                <th>Machine Number New</th>
                                                                <th>Machine Capacity</th>
                                                                <th>Machine Description</th>
                                                                <th>Total Stop</th>
                                                                <th>Persentase Efficiency Machine</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $sqlMachine = "SELECT * FROM Machines";
                                                                $stmtMachine = $pdo_orgatex->prepare($sqlMachine);
                                                                $stmtMachine->execute();
                                                                $machines = $stmtMachine->fetchAll(PDO::FETCH_ASSOC);
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

                                                                foreach ($machines as $machine) {
                                                                    $machineID = $machine['MachineNo'];

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

                                                                    if($totalSeconds>0&&$totalSecondsCalculate>0){
                                                                        $totalPercentage=(($totalSecondsCalculate-$totalSeconds)/$totalSecondsCalculate)*100;
                                                                        $statusMachine=true;
                                                                    }else{
                                                                        $totalPercentage=0;
                                                                        $statusMachine=false;
                                                                    }

                                                                    $resultDataMachine = mysqli_query($con_db_dyeing, "SELECT kapasitas as machine_capacity,
                                                                    ket as machine_description FROM tbl_mesin WHERE no_mesin = '$machineID'");

                                                                    if (mysqli_num_rows($resultDataMachine) > 0) {
                                                                        $row = mysqli_fetch_assoc($resultDataMachine);
                                                                        $machine_capacity=$row["machine_capacity"];
                                                                        $machine_description=$row["machine_description"];
                                                                    } else {
                                                                        $machine_capacity=null;
                                                                        $machine_description=null;
                                                                    }

                                                                    $sqlScheduleDye  = "SELECT * FROM tbl_mesin WHERE no_mesin_lama = '$machineID'";
                                                                    $resultScheduleDye = mysqli_query($con_db_dyeing, $sqlScheduleDye);

                                                                    if(mysqli_num_rows($resultScheduleDye) > 0){
                                                                        $dataSchedule = mysqli_fetch_assoc($resultScheduleDye);
                                                                        $machineIDNew   = $dataSchedule['no_mesin_baru'];
                                                                    }else{
                                                                        $machineIDNew   = null;
                                                                    }

                                                                    $data[] = [
                                                                        "machine_number" => $machineID,
                                                                        "machine_number_new" => $machineIDNew,
                                                                        "machine_capacity"=>$machine_capacity,
                                                                        "machine_description"=>$machine_description,
                                                                        "total_stop"=>$totalStop,
                                                                        "total_percentage_running"=>round($totalPercentage, 2),
                                                                        "status_machine"=>$statusMachine
                                                                    ];
                                                                }

                                                            ?>
                                                            <?php foreach ($data as $item): ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($item['machine_number']); ?></td>
                                                                <td><?php echo htmlspecialchars($item['machine_number_new']); ?></td>
                                                                <td><?php echo $item['machine_capacity']; ?></td>
                                                                <td><?php echo $item['machine_description']; ?></td>

                                                                <td>
                                                                    <?php 
                                                                    if (!$item['status_machine']) { 
                                                                        echo '<span style="color:red;">OFFLINE</span>'; 
                                                                    } else { 
                                                                        echo $item['total_stop'];
                                                                    } 
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php 
                                                                    if (!$item['status_machine']) { 
                                                                        echo '<span style="color:red;">OFFLINE</span>'; 
                                                                    } else { 
                                                                        echo htmlspecialchars($item['total_percentage_running']) . '%'; 
                                                                    } 
                                                                    ?>
                                                                </td>

                                                                <td>
                                                                    <?php if ($item['status_machine']): ?>
                                                                        <!-- Button to open the modal (enabled and blue when status_machine is true) -->
                                                                        <button class="btn btn-info btn-sm view-details" 
                                                                                data-machine-id="<?php echo htmlspecialchars($item['machine_number']); ?>" 
                                                                                data-toggle="modal" 
                                                                                data-target="#machineDetailsModal">
                                                                            View Details
                                                                        </button>
                                                                    <?php else: ?>
                                                                        <button class="btn btn-danger btn-sm" disabled>
                                                                            OFFLINE
                                                                        </button>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal to show machine details -->
    <div class="modal fade" id="machineDetailsModal" tabindex="-1" role="dialog" aria-labelledby="machineDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-fullscreen" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="machineDetailsModalLabel">Machine Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="loadingSpinner" class="text-center" style="display:none;">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p>Loading data, please wait...</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="machineDetailsTable">
                            <thead>
                                <tr>
                                    <th>No Batch</th>
                                    <th>Ip Address</th>
                                    <th>Start Stop</th>
                                    <th>End Stop</th>
                                    <th>Total Stop</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        #machineDetailsModal .modal-dialog {
            max-width: 80%; 
            width: auto;   
        }
    </style>

    <script type="text/javascript" src="files\bower_components\jquery\js\jquery.min.js"></script>
    <script type="text/javascript" src="files\bower_components\jquery-ui\js\jquery-ui.min.js"></script>
    <script type="text/javascript" src="files\bower_components\popper.js\js\popper.min.js"></script>
    <script type="text/javascript" src="files\bower_components\bootstrap\js\bootstrap.min.js"></script>
    <script type="text/javascript" src="files\bower_components\jquery-slimscroll\js\jquery.slimscroll.js"></script>
    <script type="text/javascript" src="files\bower_components\modernizr\js\modernizr.js"></script>
    <script type="text/javascript" src="files\bower_components\modernizr\js\css-scrollbars.js"></script>
    <script src="files\bower_components\datatables.net\js\jquery.dataTables.min.js"></script>
    <script src="files\bower_components\datatables.net-buttons\js\dataTables.buttons.min.js"></script>
    <script src="files\assets\pages\data-table\js\jszip.min.js"></script>
    <script src="files\assets\pages\data-table\js\pdfmake.min.js"></script>
    <script src="files\assets\pages\data-table\js\vfs_fonts.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\dataTables.buttons.min.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\buttons.flash.min.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\jszip.min.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\vfs_fonts.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\buttons.colVis.min.js"></script>
    <script src="files\bower_components\datatables.net-buttons\js\buttons.print.min.js"></script>
    <script src="files\bower_components\datatables.net-buttons\js\buttons.html5.min.js"></script>
    <script src="files\bower_components\datatables.net-bs4\js\dataTables.bootstrap4.min.js"></script>
    <script src="files\bower_components\datatables.net-responsive\js\dataTables.responsive.min.js"></script>
    <script src="files\bower_components\datatables.net-responsive-bs4\js\responsive.bootstrap4.min.js"></script>
    <script type="text/javascript" src="files\bower_components\i18next\js\i18next.min.js"></script>
    <script type="text/javascript" src="files\bower_components\i18next-xhr-backend\js\i18nextXHRBackend.min.js">
    </script>
    <script type="text/javascript"
        src="files\bower_components\i18next-browser-languagedetector\js\i18nextBrowserLanguageDetector.min.js"></script>
    <script type="text/javascript" src="files\bower_components\jquery-i18next\js\jquery-i18next.min.js"></script>
    <script src="files\assets\pages\data-table\extensions\buttons\js\extension-btns-custom.js"></script>
    <script src="files\assets\js\pcoded.min.js"></script>
    <script src="files\assets\js\menu\menu-hori-fixed.js"></script>
    <script src="files\assets\js\jquery.mCustomScrollbar.concat.min.js"></script>
    <script type="text/javascript" src="files\assets\js\script.js"></script>

<script>
    function toggleDateInputs() {
        var timeRange = document.getElementById('timeRange').value;
        var tglAwal = document.getElementById('tglAwal');
        var tglAkhir = document.getElementById('tglAkhir');
        var time = document.getElementById('time');
        var time2 = document.getElementById('time2');

        if (timeRange === 'custom') {
            tglAwal.disabled = false;
            tglAkhir.disabled = false;
            time.disabled = false;
            time2.disabled = false;
            time.value = '23:00';
            time2.value = '23:00';
        } else {
            tglAwal.disabled = true;
            tglAkhir.disabled = true;
            time.disabled = true;
            time2.disabled = true;
            tglAwal.value = '';
            tglAkhir.value = '';
            time.value = '';
            time2.value = '';
        }
    }

    window.onload = toggleDateInputs;

    var dataTable;

    $('.view-details').on('click', function() {
        var machineID = $(this).data('machine-id');

        var postData = {
            tgl: "<?php echo isset($_SESSION['tgl']) ? $_SESSION['tgl'] : ''; ?>",
            time: "<?php echo isset($_SESSION['time']) ? $_SESSION['time'] : ''; ?>",
            tgl2: "<?php echo isset($_SESSION['tgl2']) ? $_SESSION['tgl2'] : ''; ?>",
            time2: "<?php echo isset($_SESSION['time2']) ? $_SESSION['time2'] : ''; ?>",
            time_range: "<?php echo isset($_SESSION['time_range']) ? $_SESSION['time_range'] : ''; ?>",
            machine_id: machineID
        };

        $('#loadingSpinner').show();
        $('#machineDetailsTable').hide();

        $.ajax({
            url: 'stop_machine_efficency_detail.php',
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(data) {
                var tableBody = $('#machineDetailsTable tbody');
                tableBody.empty(); 

                $.each(data, function(index, item) {

                    var row = '<tr>' +
                        '<td>' + item.dyelot + '</td>' +
                        '<td>' + item.machine_number + '</td>' +
                        '<td>' + item.log_timestamp_start + '</td>' +
                        '<td>' + item.log_timestamp_stop + '</td>' +
                        '<td>' + item.total_stop + '</td>' +
                        '<td>' + item.reason + '</td>' +
                        '</tr>';

                    tableBody.append(row);
                });

                if ($.fn.DataTable.isDataTable('#machineDetailsTable')) {
                    $('#machineDetailsTable').DataTable().destroy();
                }

                dataTable = $('#machineDetailsTable').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });

                $('#loadingSpinner').hide();
                $('#machineDetailsTable').show();
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + error);
                $('#loadingSpinner').hide();
                $('#machineDetailsTable').show();
            }
        });
    });

    $('#machineDetailsModal').on('hidden.bs.modal', function() {
        var tableBody = $('#machineDetailsTable tbody');
        tableBody.empty();

        if ($.fn.DataTable.isDataTable('#machineDetailsTable')) {
            $('#machineDetailsTable').DataTable().destroy();
        }

        $('#loadingSpinner').hide();
        $('#machineDetailsTable').hide();
    });
</script>

</body>
</html>
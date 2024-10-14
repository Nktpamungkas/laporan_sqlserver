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
                                                <div class="col-sm-12 col-xl-2 m-b-0">
                                                    <h4 class="sub-title">Tanggal Awal</h4>
                                                    <div class="input-group input-group-sm">
                                                        <input type="date" class="form-control" required
                                                            placeholder="input-group-sm" name="tgl"
                                                            value="<?php if (isset($_POST['submit'])){ echo $_POST['tgl']; } ?>"
                                                            required>
                                                        <input name="time" type="text" class="form-control" id="time"
                                                        value="07:00" size="5" maxlength="5" required readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-xl-2 m-b-0">
                                                    <h4 class="sub-title">Tanggal Akhir</h4>
                                                    <div class="input-group input-group-sm">
                                                        <input type="date" class="form-control" required
                                                            placeholder="input-group-sm" name="tgl2"
                                                            value="<?php if (isset($_POST['submit'])){ echo $_POST['tgl2']; } ?>"
                                                            required>
                                                        <input name="time2" type="text" class="form-control" id="time"
                                                            value="07:00" size="5" maxlength="5" required readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-xl-2">
                                                    <h4 class="sub-title">&nbsp;</h4>
                                                    <button type="submit" name="submit"
                                                        class="btn btn-primary btn-sm"><i
                                                            class="icofont icofont-search-alt-1"></i> Cari data</button>
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
                                                                <th>Machine Number</th>
                                                                <th>Total Stop Second</th>
                                                                <th>Total Stop Minutes</th>
                                                                <th>Total Stop Hour</th>
                                                                <th>Total Stop Percentage</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
<?php
    $sqlMachine = "SELECT * FROM Machines";
    $stmtMachine = $pdo_orgatex->prepare($sqlMachine);
    $stmtMachine->execute();

    $machines = $stmtMachine->fetchAll(PDO::FETCH_ASSOC);
    
    // $startDate = '2024-10-07 07:00:00';
    // $endDate = '2024-10-08 07:00:00';

    if($_POST['tgl']&&$_POST['time']){
        $startDate = $_POST['tgl'].' '.$_POST['time'];
    }

    if($_POST['tgl2']&&$_POST['time2']){
        $endDate = $_POST['tgl2'].' '.$_POST['time2'];
    }

    $data = [];

    foreach ($machines as $machine) {
        $machineID = $machine['MachineNo'];

        $sqlLogs = "SELECT LogTimeStamp as logTimeStamp, 
                    AlarmNo as value
                    FROM MachineProtocol
                    WHERE Machine = :machineID
                    AND LogTimeStamp BETWEEN :startDate AND :endDate
                    ORDER BY LogTimeStamp";
        
        $stmtLogs = $pdo_orgatex->prepare($sqlLogs);
        $stmtLogs->bindParam(':machineID', $machineID);
        $stmtLogs->bindParam(':startDate', $startDate);
        $stmtLogs->bindParam(':endDate', $endDate);
        $stmtLogs->execute();

        $totalSeconds = 0;
        $rows = $stmtLogs->fetchAll(PDO::FETCH_ASSOC);

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
            "total_second" => $totalSeconds,
            "total_minutes" => $totalStopTime,
            "total_hour" => $totalStopHour,
            "total_percentage" => round($totalPercentage, 2)
        ];
    }

?>
                                                            <?php foreach ($data as $item): ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($item['machine_number']); ?></td>
                                                                <td><?php echo htmlspecialchars($item['total_second']); ?></td>
                                                                <td><?php echo htmlspecialchars($item['total_minutes']); ?></td>
                                                                <td><?php echo htmlspecialchars($item['total_hour']); ?></td>
                                                                <td><?php echo htmlspecialchars($item['total_percentage']); ?>%</td>
                                                                <td>
                                                                    <!-- Button to open the modal -->
                                                                    <button class="btn btn-info btn-sm view-details" data-machine-id="<?php echo htmlspecialchars($item['machine_number']); ?>" data-toggle="modal" data-target="#machineDetailsModal">View Details</button>
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
                    <div class="table-responsive">
                        <table class="table table-bordered" id="machineDetailsTable">
                            <thead>
                                <tr>
                                    <th>Machine Number</th>
                                    <th>Time</th>
                                    <th>Total Stop Second</th>
                                    <th>Total Stop Minutes</th>
                                    <th>Total Stop Hour</th>
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
    $(document).ready(function() {
        var dataTable;

        $('.view-details').on('click', function() {
            var machineID = $(this).data('machine-id');

            var postData = {
                tgl: "<?php echo isset($_SESSION['tgl']) ? $_SESSION['tgl'] : ''; ?>",
                time: "<?php echo isset($_SESSION['time']) ? $_SESSION['time'] : ''; ?>",
                tgl2: "<?php echo isset($_SESSION['tgl2']) ? $_SESSION['tgl2'] : ''; ?>",
                time2: "<?php echo isset($_SESSION['time2']) ? $_SESSION['time2'] : ''; ?>",
                machine_id: machineID
            };

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
                            '<td>' + item.machine_number + '</td>' +
                            '<td>' + item.log_timestamp + '</td>' +
                            '<td>' + item.total_seconds + '</td>' +
                            '<td>' + item.total_minutes + '</td>' +
                            '<td>' + item.total_hour + '</td>' +
                            '<td>' + item.reason + '</td>' +
                            '</tr>';
                        tableBody.append(row);
                    });

                    if ($.fn.DataTable.isDataTable('#machineDetailsTable')) {
                        $('#machineDetailsTable').DataTable().destroy(); // Destroy previous instance
                    }

                    dataTable = $('#machineDetailsTable').DataTable({
                        paging: true,
                        searching: true,
                        ordering: true
                    });
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + error);
                }
            });
        });

        // Reset modal when closed
        $('#machineDetailsModal').on('hidden.bs.modal', function() {
            var tableBody = $('#machineDetailsTable tbody');
            tableBody.empty(); // Clear the table content

            if ($.fn.DataTable.isDataTable('#machineDetailsTable')) {
                $('#machineDetailsTable').DataTable().destroy(); // Destroy DataTable instance
            }
        });
    });
</script>



</body>
</html>
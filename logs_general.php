<?php
ini_set("error_reporting", 0);
session_start();
require_once "koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Log General</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="files\bower_components\bootstrap\css\bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="files\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css">
  <link rel="stylesheet" type="text/css" href="files\assets\pages\data-table\css\buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="files\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css">
  <link rel="stylesheet" type="text/css" href="files\assets\css\style.css">
</head>

<body>
  <div class="container mt-4">
    <div class="card">
      <div class="card-header">
        <h4>📜 Log General Viewer</h4>
      </div>
      <div class="card-body">
        <form id="filterForm" class="row mb-3">
          <div class="col-md-3">
            <label>Dari Tanggal</label>
            <input type="date" id="start_date" class="form-control" name="start_date" required>
          </div>
          <div class="col-md-3">
            <label>Sampai Tanggal</label>
            <input type="date" id="end_date" class="form-control" name="end_date" required>
          </div>
          <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
          </div>
          <div class="col-md-3 align-self-end">
            <button type="button" id="resetBtn" class="btn btn-secondary w-100">Reset</button>
          </div>
        </form>

        <div class="table-responsive">
          <table id="logsTable" class="table table-striped table-bordered" style="width:100%">
            <thead class="thead-dark">
              <tr>
                <th>No</th>
                <th>Entity</th>
                <th>Entity ID</th>
                <th>Action</th>
                <th>Data</th>
                <th>Created At</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="files\bower_components\datatables.net\js\jquery.dataTables.min.js"></script>
  <script src="files\bower_components\datatables.net-bs4\js\dataTables.bootstrap4.min.js"></script>
  <script src="files\bower_components\datatables.net-responsive\js\dataTables.responsive.min.js"></script>
  <script src="files\bower_components\datatables.net-responsive-bs4\js\responsive.bootstrap4.min.js"></script>

  <script>
    $(document).ready(function() {
      let table = $('#logsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: 'ajax/fetch_logs_general.php',
          type: 'POST',
          data: function(d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [{
            data: 'no'
          },
          {
            data: 'entity'
          },
          {
            data: 'entity_id'
          },
          {
            data: 'action'
          },
          {
            data: 'data',
            render: function(data) {
              try {
                const obj = JSON.parse(data);
                return `<pre>${JSON.stringify(obj, null, 2)}</pre>`;
              } catch (e) {
                return data;
              }
            }
          },
          {
            data: 'created_at'
          }
        ]
      });

      $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
      });

      $('#resetBtn').on('click', function() {
        $('#start_date').val('');
        $('#end_date').val('');
        table.ajax.reload();
      });
    });
  </script>
</body>

</html>
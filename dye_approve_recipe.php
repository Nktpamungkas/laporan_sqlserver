<?php
    ini_set("error_reporting", 0);
    session_start();
    require_once "koneksi.php";
    include "utils/helper.php";

    $productionorder = $_GET['prod_order'] ?? $_GET['prod_order'] ?? $_POST['prod_order'] ?? '';
    $sel_line        = $_GET['line'] ?? $_GET['line'] ?? $_POST['line'] ?? '';
    // echo $sel_line;
?>
<!DOCTYPE html>
<html lang="en" class="pcoded-main-container">

<head>
    <title>DYE - Approved Recipe </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="#">
    <meta name="keywords" content="Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
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
    <link rel="stylesheet" type="text/css" href="files\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="files\assets\pages\data-table\css\buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="files\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css">
    <!-- <style>
        .button-container {
            position: relative;
            display: inline-block;
        }

        .new-label {
            background-color: yellow; /* Warna latar belakang label */
            color: black; /* Warna teks label */
            padding: 5px 10px; /* Padding untuk label */
            border-radius: 5px; /* Sudut melengkung */
            position: absolute; /* Posisi absolut untuk label */
            top: -10px; /* Atur posisi vertikal */
            right: -10px; /* Atur posisi horizontal */
            font-weight: bold; /* Tebal */
            font-size: 12px; /* Ukuran font */
        }
        /* CSS untuk spinner loader */
    </style> -->
    <style>
        /* Spinner style */
        .loader2 {
            border: 8px solid #f3f3f3; /* Light gray */
            border-top: 8px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -25px; /* Half of the height */
            margin-left: -25px; /* Half of the width */
        }

        /* Animasi untuk spinner */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Membuat posisi loader di tengah tabel */
        .table-responsive {
            position: relative;
        }

        .bg-green-soft {
            background-color: #9acc99;
            /* color: #0d47a1; */
            padding: 1rem;
            border-radius: 0.25rem;
        }
    </style>

</head>
<?php require_once 'header.php'; 
    $recipe = [];
    $qlist =  "SELECT 
                    * 
                FROM 
                    data_upload d
                where 
                    d.status_resep = '1'";
    $result = mysqli_query($con_rec, $qlist);

    while ($list= mysqli_fetch_assoc($result)){
        $recipe[] = "'" . $list['recipe'] . "'";
    }
    $recipe_string = implode(',', $recipe);

    $qdye = "SELECT
        *
        from
            (
            SELECT
                no_resep as resep,
                suffix as suffix
            from
                tbl_schedule ts
        union all
            SELECT
                no_resep2 as resep,
                suffix2 as suffix
            from
                tbl_schedule ts) s
        where
            s.resep in ($recipe_string)
    ";
    
    $stmt_dye = mysqli_query($con_db_dyeing, $qdye);
?>

<body>
    <div class="pcoded-content">
        <div class="pcoded-inner-content bg-green-soft">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Data Approved Recipe</h5>
                                    </div>
                                </div>
                                 <div class="d-flex align-items-center">
                                    <!-- Logo -->
                                    <div class="text-start">
                                        <img src="img/logo-tektok.png" alt="logo-tektok" height="80">
                                    </div>

                                    <div class="ms-3">
                                        <h5 class="mb-0" style='font-family:"Microsoft Sans Serif"'>Approved Recipes</h5>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-block">
                                        <div class="table-responsive dt-responsive">
                                            <table border="1" id='detail_recipe' style='font-family:"Microsoft Sans Serif"' width="100%">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">No</th>
                                                        <th class="text-center">No Recipe</th>
                                                        <th class="text-center">Suffix</th>
                                                        <th class="text-center">Tanggal Masuk Dye</th>
                                                        <th class="text-center">Approve User</th>
                                                        <th class="text-center">Tanggal Approve</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        $no=1;
                                                        while ($data = mysqli_fetch_assoc($stmt_dye)) {
                                                            list($prod_order, $line) = explode('-', $data['resep']);
                                                            $qapprove =  "SELECT
                                                                                d.*,
                                                                                u.name as user_approve,
                                                                                d2.name,
                                                                                d2.dept 
                                                                            FROM
                                                                                data_upload d
                                                                            LEFT JOIN users u on user_updated = u.id 
                                                                            LEFT JOIN departement d2 on d2.id = u.dept 
                                                                            WHERE
                                                                                d.status_resep = 1
                                                                                AND d.recipe = '$data[resep]'";
                                                            $result_approve = mysqli_query($con_rec, $qapprove);
                                                            $detail_approve = mysqli_fetch_assoc($result_approve);
                                                        ?>
                                                        <tr>
                                                            <td style="text-align: center;"><?= $no++ ?></td>
                                                            <td style="text-align: center;"><?= $data['resep']?></td>
                                                            <td style="text-align: center;"><?= $data['suffix']?></td>
                                                            <td style="text-align: center;"><?= $detail_approve['tanggal_upload']?></td>
                                                            <td style="text-align: center;"><?= $detail_approve['user_approve']?></td>
                                                            <td style="text-align: center;"><?= $detail_approve['updated_at']?></td>
                                                            <td style="text-align: center;">
                                                                <?php if($detail_approve['status_resep']=1){
                                                                echo 'Approve DYE';}?>
                                                            </td>
                                                           <td style="text-align: center;">
                                                                <a href="https://online.indotaichen.com/laporan/dye_search_detail_recipe.php?prod_order=<?= urlencode($prod_order) ?>&line=<?= urlencode($line) ?>" target="_blank" 
                                                                    title="Lihat Detail" style="display: inline-block; background-color: #0d6efd;  color: white; padding: 6px 8px; border-radius: 50%; font-size: 16px; line-height: 1; text-decoration: none;">
                                                                    <i class="feather icon-eye"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php }?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="files\assets\js\pcoded.min.js"></script>
<script type="text/javascript" src="files\assets\js\script.js"></script>
<script>// Fungsi untuk mengambil parameter dari URL
function getParameterByName(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

document.addEventListener('DOMContentLoaded', function() {
    // Ambil nilai prod_order dan line dari URL query string
    const prodOrderFromURL = getParameterByName('prod_order');
    const lineFromURL = getParameterByName('line');

    // Jika prod_order ada di URL, set nilai pada elemen input
    if (prodOrderFromURL) {
        document.getElementById('prod_order').value = prodOrderFromURL;
        var lineSelect = document.getElementById('line');
        lineSelect.disabled = false;
        lineSelect.innerHTML = '<option value="">Silakan pilih Line Reservation</option>';
        fetchLineReservations(prodOrderFromURL, lineFromURL); // Pass lineFromURL as well
    }
});

// Event listener untuk prod_order input
document.getElementById('prod_order').addEventListener('input', function() {
    var productionOrder = this.value.trim();
    var lineSelect = document.getElementById('line');

    // Cek apakah Production Order sudah diisi
    if (productionOrder !== '') {
        // Mengaktifkan dropdown Line Reservation dan kosongkan pilihan sebelumnya
        lineSelect.disabled = false;  // Mengaktifkan dropdown Line Reservation
        lineSelect.innerHTML = '<option value="">Silakan pilih Line Reservation</option>'; // Kosongkan dropdown

        // Lakukan AJAX request untuk mengambil Line Reservation berdasarkan Production Order
        fetchLineReservations(productionOrder);
    } else {
        // Jika Production Order kosong, nonaktifkan Line Reservation dan kosongkan dropdown
        lineSelect.disabled = true;
        lineSelect.innerHTML = '<option value="">Silakan pilih Production Order terlebih dahulu</option>';
    }
});

// Fungsi untuk mengambil data Line Reservation berdasarkan Production Order
function fetchLineReservations(productionOrder, lineFromURL = null) {
    fetch('ajax/lineReservationRecipe.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'prod_order=' + encodeURIComponent(productionOrder),
    })
    .then(response => response.json())
    .then(data => {
        // Debugging: Cek data yang diterima dari server
        console.log("Data Line Reservations:", data);

        if (data.success) {
            var lineSelect = document.getElementById('line');
            // Kosongkan dropdown dan isi dengan data dari server
            lineSelect.innerHTML = '<option value="">Silakan pilih Line Reservation</option>';
            data.lines.forEach(function(line) {
                var option = document.createElement('option');
                option.value = line.GROUPLINE;
                option.textContent = line.GROUPLINE;
                lineSelect.appendChild(option);
            });

            // Jika ada line dari URL, set nilai pada dropdown
            if (lineFromURL) {
                lineSelect.value = lineFromURL;
                fetchDataRecipe(productionOrder, lineFromURL); // Panggil data resep jika line ada
            }
        } else {
            alert(data.message);  // Tampilkan pesan error jika tidak ada data
        }
    })
    .catch(error => {
        console.error('Error fetching line reservations:', error);
        alert('Terjadi kesalahan saat mengambil data Line Reservations.');
    });
}

// Fungsi untuk menangani perubahan pilihan Line Reservation
document.getElementById('line').addEventListener('change', function() {
    var productionOrder = document.getElementById('prod_order').value.trim();
    var lineReservation = this.value.trim();

    // Cek apakah kedua field sudah dipilih
    if (productionOrder !== '' && lineReservation !== '') {
        // Panggil fetchDataRecipe untuk mengambil data berdasarkan Production Order dan Line Reservation
        fetchDataRecipe(productionOrder, lineReservation);
    } else {
        // Berikan peringatan jika salah satu belum dipilih
        alert('Pastikan Line Reservation dipilih terlebih dahulu.');
    }
});

function fetchDataRecipe(productionOrder, line) {
    document.getElementById('loader').style.display = 'block'; // Menampilkan loader

    fetch('ajax/fetchDataRecipe.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'prod_order=' + encodeURIComponent(productionOrder) + '&line=' + encodeURIComponent(line),
    })
    .then(response => response.json())
    .then(data => {
        // Sembunyikan loader setelah data diterima
        document.getElementById('loader').style.display = 'none';

        try {
            // Debugging: Cek data yang diterima dari server
            console.log("Data dari server:", data);

            if (data.success) {
                if (data.recipes) {
                    fillTableRecipe(data.recipes);
                    fillHeader(data.dataheader);
                } else {
                    alert("Data tidak ditemukan.");
                }
            } else {
                alert(data.message);  // Tampilkan pesan error jika tidak ada data
            }
        } catch (e) {
            console.error('Terjadi kesalahan dalam parsing JSON:', e);
            alert('Terjadi kesalahan saat menerima data.');
        }
    })
    .catch(error => {
        console.error('Error fetching recipe data:', error);
        document.getElementById('loader').style.display = 'none';
        alert('Terjadi kesalahan saat mengambil data resep.');
    });
}

function fillTableRecipe(data) {
    var tbody = document.querySelector("#detail_recipe tbody");
    tbody.innerHTML = '';
    console.log("Data untuk tabel:", data);
    if (!Array.isArray(data)) {
        data = [data];
    }
    if (data.length > 0) {
        document.getElementById('loader').style.display = 'none';
        data.forEach(function(item) {
            var row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.CODE || ''}</td>
                <td>${item.COMMENTLINE || ''}</td>
                <td>${item.SUBCODE || ''}</td>
                <td>${item.LONGDESCRIPTION || ''}</td>
                <td>${item.CONSUMPTION || ''}</td>
                <td>${item.CONSUMPTIONTYPE || ''}</td>
                <td>${item.QUANTITY || ''}</td>
                <td>${item.CONSUMPTIONTYPEQTY || ''}</td>
            `;
            tbody.appendChild(row);
        });
    } else {
        var row = document.createElement('tr');
        row.innerHTML = '<td colspan="8">Data tidak ditemukan.</td>';
        tbody.appendChild(row);
    }
}

function fillHeader(data) {
    var colorCell = document.querySelector('#color');
    var recipenoCell = document.querySelector('#recipeno');
    var dyelotCell = document.querySelector('#dyelot');
    colorCell.innerHTML = '';
    recipenoCell.innerHTML = '';
    dyelotCell.innerHTML = '';
    console.log("Data untuk tabel:", data);
    if (!Array.isArray(data)) {
        data = [data];
    }
    if (data.length > 0) {
        var item = data[0];
        colorCell.innerHTML = (item.color || '') + ' - ' + (item.warna || '') || 'Tidak ada data warna';
        recipenoCell.innerHTML = item.recipeno || 'Tidak ada nomor resep';
        dyelotCell.innerHTML = item.dyelot || 'Tidak ada produksi order';
    } else {
        colorCell.innerHTML = 'Data tidak ditemukan';
        recipenoCell.innerHTML = 'Data tidak ditemukan';
        dyelotCell.innerHTML = 'Data tidak ditemukan';
    }
}
</script>

<?php require_once 'footer.php'; ?>
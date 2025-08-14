<?php
    session_start();
    require_once "koneksi.php";
    include "utils/helper.php";
    $menu = 'dye_create_topping.php'; // Set the menu for this login
    $date = date('Y-m-d H:i:s');
        // Tidak ada session, cek apakah IP-nya masih terdaftar di log
        $q_cek_login = sqlsrv_query($con_nowprd, "SELECT COUNT(*) OVER() AS COUNT, DATEDIFF(MINUTE, CREATEDATETIME, GETDATE()) AS selisih_menit FROM nowprd.log_activity_users WHERE IPADDRESS = ? AND menu = ?", [$_SERVER['REMOTE_ADDR'], $menu]);
        $data_login = sqlsrv_fetch_array($q_cek_login);

    if ($data_login['COUNT'] == 1 && $data_login['selisih_menit'] > 15) {
            sqlsrv_query($con_nowprd, "DELETE FROM nowprd.log_activity_users WHERE IPADDRESS = ? AND menu = ?", [$_SERVER['REMOTE_ADDR'], $menu]);
            header("Location: login_toping.php");
            exit();
        }else if(empty($data_login['COUNT'])){
            header("Location: login_toping.php");
            exit();
        }


    // Ambil data dari session
    $loggedInUser = $_SESSION['username'];
    $option = $_GET['option'];

    $recipe_code    = $_POST['recipecode'] ?? null;
    $suffix         = $_POST['suffixcode'] ?? null;

    $queryMainRecipe    = "SELECT * FROM RECIPE r WHERE SUBCODE01 = '$recipe_code' AND SUFFIXCODE = '$suffix'";
    $execMainRecipe     = db2_exec($conn1, $queryMainRecipe);
    $dataRecipe         = db2_fetch_assoc($execMainRecipe);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>DYE - Create Topping</title>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    

</head>
<style>
    #recipeComponents_table {
        font-family: "Tahoma";
        font-size: 12px; /* Mengubah ukuran font */
    }

    #recipeComponents_table td, #recipeComponents_table th {
        padding: 0px; /* Mengurangi padding sel */
    }

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

    .input-error {
        border: 2px solid red;
        background-color: #ffe6e6;
    }
</style>
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
                                        <center><h5 class="sub-title">Create Topping in Recipe NOW</h5></center>
                                    </div>
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-sm-6 m-auto">
                                                <div class="input-group input-group-button input-group-sm">
                                                    <input type="text" name="recipecode" id="recipecode" placeholder="Masukan Recipe Code" class="form-control form-control-sm form-txt-primary" value="<?php if (isset($_POST['submit'])){ echo $_POST['recipe_code']; } ?>" autofocus>
                                                    <input type="text" name="suffixcode" id="suffixcode" placeholder="Masukan Suffix" class="form-control form-control-sm form-txt-primary" value="<?php if (isset($_POST['submit'])){ echo $_POST['suffix']; } ?>">
                                                    <span class="input-group-addon btn btn-primary" id="basic-addon10">
                                                        <span class="">Cari data</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4 mobile-inputs">
                                                <h4 class="sub-title">Recipe</h4>
                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control form-control-sm" id="recipecode_before" readonly>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control form-control-sm" id="suffix_before" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control form-control-sm form-control-danger" id="lr_new" placeholder="Liquor Ratio">
                                                        <span class="new-label">Liquor Ratio</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-8 mobile-inputs">
                                                <h4 class="sub-title">Recipe To Create</h4>
                                                <form>
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control form-control-sm form-control-danger" name="recipecode_new" id="recipecode_new" onkeydown="cekBonResep()" placeholder="Recipe Code">
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm form-control-danger" name="user_login" id="user_login" 
                                                            placeholder="User" value="<?php echo htmlspecialchars($loggedInUser); ?>" hidden>
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control form-control-sm form-control-danger" name="suffix_new" id="suffix_new" onkeydown="cekBonResep()" placeholder="Suffix">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control form-control-sm form-control-danger" name="long_new" id="long_new" onkeydown="cekBonResep()" placeholder="Long Description*">
                                                        </div>
                                                    <?php if($option == 'Toping' or $option == 'toping'):?>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control form-control-sm form-control-danger" name="short_new" id="short_new" onkeydown="cekBonResep()" placeholder="Short Description">
                                                        </div>
                                                    <?php endif;?>
                                                    <?php if($option == 'Adjust' or $option == 'adjust'):?>
                                                        <div class="col-sm-4">
                                                            <select class="form-control form-control-sm form-control-danger" name="short_new" id="short_new" onchange="cekBonResep()">
                                                                <option value="">-- Pilih Colorist --</option>
                                                                <?php
                                                                $queryColorist = "SELECT * FROM tbl_user WHERE jabatan = 'Colorist' AND status = 'Aktif' ORDER BY username ASC";
                                                                $stmtColorist = mysqli_query($con_db_lab, $queryColorist);
                                                                if(mysqli_num_rows($stmtColorist) > 0) {
                                                                    while ($colorist = mysqli_fetch_assoc($stmtColorist)) {
                                                                        $selected = (isset($_POST['short_new']) && $_POST['short_new'] == $colorist['username']) ? 'selected' : '';
                                                                        echo '<option value="'.htmlspecialchars($colorist['username']).'" '.$selected.'>'.htmlspecialchars($colorist['username']).'</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    <?php endif;?>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control form-control-sm form-control-danger" name="search_new" id="search_new" onkeydown="cekBonResep()" placeholder="Search Description">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                                <div class="btn-group" id="before">
                                                    <button type="button" class="btn btn-warning btn-xs btn-flat" onclick=addNewRow()>
                                                        <i class="feather icon-plus"></i> Add Row Recipe
                                                    </button>
                                                </div> ▐&nbsp;
                                        </div>
                                        <table class="table table-striped table-bordered" id="recipeComponents_table">
                                            <thead>
                                                <tr>
                                                    <th>Gr</th>
                                                    <th>GrTp</th>
                                                    <th>Sq</th>
                                                    <th>Sub sq</th>
                                                    <th>IT</th>
                                                    <th>Item code</th>
                                                    <th>Suffix</th>
                                                    <th>Comment</th>
                                                    <th>Description</th>
                                                    <th>Cons type</th>
                                                    <th>UoM</th>
                                                    <th>Cons</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                        <hr style="border: 1px solid #000;">
                                        <center>
                                            <span style="background-color: #fff0ba; padding: 5px;">
                                                <input type="checkbox" id="confirmCheck">
                                                <label for="confirmCheck">Pastikan semua data telah diisi dengan benar sebelum menyimpan.</label>
                                            </span><br>

                                            <button type="button" id="exsecute" class="btn btn-danger btn-sm text-black" disabled>
                                                <strong>SUBMIT FOR IMPORT TO NOW ! <i class="fa fa-save"></i></strong>
                                            </button>
                                            <!-- buat tes tobi -->
                                            <!-- <button class="btn btn-primary" onclick="generateInsertQueries()">Simpan Semua</button> -->
                                        </center>

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
<?php require_once 'footer.php'; ?>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Bootstrap 4 theme for Select2 (optional) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">

<!-- Script Untuk Save Header -->
<script>
    const option = <?= json_encode($_SESSION['option'] ?? $_GET['option'] ?? 'Toping') ?>;
    $(document).ready(function() {
        $('#confirmCheck').on('change', function() {
            $('#exsecute').prop('disabled', !this.checked);
        });
        
    $('#exsecute').on('click', async function () {
        const formData = {
            recipe_code_new: $('#recipecode_new').val(),
            suffix_new: $('#suffix_new').val(),
            long_new: $('#long_new').val(),
            short_new: $('#short_new').val(),
            search_new: $('#search_new').val(),
            lr_new: $('#lr_new').val(),
            user_login: $('#user_login').val()
        };

        if (formData.recipe_code_new && formData.suffix_new) {
            const queries = await generateInsertQueries();
            if (queries.length === 0) {
                Swal.fire('Error', 'Tidak ada data untuk diexport.', 'error');
                return;
            }
            formData.query_sql = queries.join(";\n");

            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin mengeksport data ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Export!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "export_recipe_to_now.php",
                        data: formData,
                        success: function (response) {
                            Swal.fire({
                                title: 'Sukses!',
                                text: 'Data berhasil diexport ke NOW.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = "dye_create_topping.php?option=" + option;
                            });
                        },
                        error: function (xhr, status, error) {
                            Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data.', 'error');
                        }
                    });
                }
            });
        } else {
            const recipeCodeVal = $('#recipecode_new').val().trim();
            const suffixVal = $('#suffix_new').val().trim();

            const recipeCodeEl = document.getElementById('recipecode_new');
            const suffixEl = document.getElementById('suffix_new');

            recipeCodeEl.classList.remove('input-error');
            suffixEl.classList.remove('input-error');

            Swal.fire({
                title: 'Peringatan!',
                text: 'Harap isi dulu Recipe Code dan Suffix sebelum melanjutkan.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });

            if (!recipeCodeVal) {
                recipeCodeEl.classList.add('input-error');
            }

            if (!suffixVal) {
                suffixEl.classList.add('input-error');
            }

            return;
        }
    });
        
        $('#recipeComponents_table').DataTable({
            paging: false,           // hilangkan pagination
            compact: true,           // class DataTables, agar lebih kecil
            info: false,
        });
    });
</script>

<!-- Untuk Bon Resep Gatau buat apa nni-->
<script>
    function cekBonResep() {
            // var recipecode  = document.getElementById('recipecode_before').value.trim();
            // var suffix      = document.getElementById('suffix_before').value.trim();
            // var long        = document.getElementById('long_new').value.trim();
            // var short       = document.getElementById('short_new').value.trim();
            // var search      = document.getElementById('search_new').value.trim();
            // if (recipecode === '' || suffix === '' || long === '' || short === '' || search === '') {
            //     Swal.fire({
            //         title: 'Peringatan!',
            //         text: 'Harap isi dulu Recipe Code dan Suffix sebelum melanjutkan.',
            //         icon: 'warning',
            //         confirmButtonText: 'OK'
            //     }).then(() => {
            //         // Setelah klik OK, fokuskan kembali ke input bon_resep
            //         document.getElementById('recipecode_new').focus();
            //     });
            // }
        }
</script>

<!-- Script Untuk FetchData -->
<script>
        document.getElementById('basic-addon10').addEventListener('click', function () {
        const recipeCode = document.getElementById('recipecode').value.trim();
        const suffix = document.getElementById('suffixcode').value.trim();

        if (recipeCode === '' || suffix === '') {
            Swal.fire('Error', 'Recipe Code dan Suffix harus diisi!', 'error');
            return;
        }

        Swal.fire({
            title: 'Memuat...',
            text: 'Mengambil data...',
            didOpen: () => { Swal.showLoading(); },
            allowOutsideClick: false
        });

        fetch('ajax/fetch_rff.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'recipecode=' + encodeURIComponent(recipeCode) + '&suffixcode=' + encodeURIComponent(suffix)
        })
        .then(response => {if (!response.ok) throw new Error('Jaringan bermasalah atau server error');return response.json();})
        .then(datas => {
            Swal.close();

            if (datas.success) {
                document.getElementById('recipecode_before').value = datas.recipecode_before;
                document.getElementById('suffix_before').value = datas.suffix_before;
                document.getElementById('lr_new').value = datas.lr_before;
                document.getElementById('long_new').value = datas.longdescription;
                document.getElementById('short_new').value = datas.shortdescription;
                document.getElementById('search_new').value = datas.searchdescription;
                data = datas.data.map(item => ({
                    Gr: parseInt(item.GROUPNUMBER),
                    GrTp: item.GROUPTYPECODE,
                    Sq: parseInt(item.SEQUENCE),
                    SubSq: parseInt(item.SUBSEQUENCE),
                    IT: item.ITEMTYPEAFICODE,
                    ItemCode: item.ITEMCODE,
                    Subcode01: item.SUBCODE01,
                    Subcode02: item.SUBCODE02,
                    Subcode03: item.SUBCODE03,
                    SuffixCode: item.SUFFIXCODE,
                    Description: item.LONGDESCRIPTION,
                    ConsType: item.CONSUMPTIONTYPE,
                    Comment: item.COMMENTLINE,
                    UoM: item.COMPONENTUOMCODE,
                    Cons: formatDecimal(item.CONSUMPTION)
                }));
                populateTable();

                Swal.fire('Berhasil', 'Data berhasil dimuat', 'success');
            } else {
                Swal.fire('Gagal', data.message || 'Data tidak ditemukan!', 'error');
            }
        })
        .catch(error => {
            Swal.close();
            console.error('Error:', error);
            Swal.fire('Error', 'Terjadi kesalahan saat mengambil data: ' + error.message, 'error');
        });
    });
</script>

<!-- Buat manipulasi tampilan supaya ada angka 0 di depan -->
<script>
    function formatDecimal(val) {
        if (val == null ) return '';
        if (typeof val === 'string' && val.startsWith('.')) {
            return '0' + val;
        }
        if (!isNaN(val)) {
            return parseFloat(val).toFixed(5);
        }
        return val;
    }
</script>

<!-- Untuk addrow -->
<script>
    function addNewRow() {
    const maxGr = Math.max(0, ...data.map(row => row.Gr));
    const newRow = {
        Gr: maxGr + 1,
        GrTp: '201',
        Sq: 0,
        SubSq: 10,
        IT: 'RFF',
        ItemCode: '',
        Subcode01: '',
        Subcode02: '',
        Subcode03: '',
        SuffixCode: '',
        Description: '',
        ConsType: '',
        Comment: '',
        UoM: '',
        Cons: ''
    };

    data.push(newRow);
    populateTable();
    }
</script>

<!-- Untuk Update GroupType -->
<script>
    function updateGrTp(selectEl, gr, sq) {
        const value = selectEl.value;
            const index = data.findIndex(item => item.Gr == gr && item.Sq == sq);
        if (index !== -1) {
            data[index].GrTp = value;
            data[index].IT =
                value === '100' ? "" :
                value === '010' ? "DYC" :
                value === '201' ? "RFF" :
                value === '001' ? "DYC" : "";
            populateTable();
        }
    }
</script>

<!-- Untuk Create Table -->
<script>
    function populateTable() {
    const tableBody = document.querySelector("#recipeComponents_table tbody");
    tableBody.innerHTML = "";

    // Pastikan .Gr berupa number agar sort bisa berfungsi benar
    data.sort((a, b) => {
        const grDiff = parseInt(a.Gr) - parseInt(b.Gr);
        if (grDiff !== 0) return grDiff; // Urutkan Gr dulu jika berbeda
        return parseInt(a.Sq) - parseInt(b.Sq); // Jika Gr sama, urutkan Sq
    });

    data.forEach((row, index) => {
        const tr = document.createElement("tr");
        // Hilangin dulu yang option row.GrTp Instruction 
        // <option value="100" ${row.GrTp === '100' ? 'selected' : ''}>100 (Instruction)</option>
        // ${row.Comment ?? ''}
        // <td>${row.IT === ''? row.IT: `<input type="text" value="${row.IT}" readonly>`}</td>
        tr.innerHTML = `
            <td>
                <input type="number" class="input-field gr-input" style="width: 65px;" value="${row.Gr}" onchange="updateGr(${row.Gr}, this.value, ${row.Sq}, ${row.GrTp} )">
            </td>
            <td>
                <select class="dropdown" data-oldgr="${row.Gr}" onchange="updateGrTp(this, ${row.Gr}, ${row.Sq})">
                    <option value="001" ${row.GrTp === '001' ? 'selected' : ''}>001 (Dyestuff/Chemical)</option>
                    <option value="201" ${row.GrTp === '201' ? 'selected' : ''}>201 (Sub Recipe - Fabric Dye)</option>
                    <option value="100" ${row.GrTp === '100' ? 'selected' : ''}>100 (Instruction)</option>
                    <option value="010" ${row.GrTp === '010' ? 'selected' : ''}>010 (Binder-Filler)</option>
                </select>
            </td>
            <td>
                <input type="number" value="${row.Sq}" onchange="updateSq(${row.Gr}, ${row.Sq}, this.value)">
            </td>
            <td><input type="number" value="${row.SubSq}" onchange="updateField(${row.Gr}, 'SubSq', this.value)"></td>
            <td style="width: 80px">${row.IT ?? ''}</td>
            <td>
                ${row.IT === ''? row.ItemCode: `<select class="select2-itemcode" data-gr="${row.Gr}" data-grouptype="${row.GrTp}" style="width: 150px">
                ${row.ItemCode ? `<option value="${row.ItemCode}" selected>${row.ItemCode}</option>` : ''}</select>`}
            </td>
            <td>${row.SuffixCode ?? ''}</td>
            <td>
                ${row.IT !== ''? row.Comment: `<select class="select2-comment" data-gr="${row.Gr}" data-grouptype="${row.GrTp}" style="width: 150px">
                ${row.Comment ? `<option value="${row.Comment}" selected>${row.Comment}</option>` : ''}</select>`}
                
            </td>
            <td>${row.Description ?? ''}</td>
            <td>${row.IT !== 'DYC'? '': `<select onchange="updateField(${row.Gr}, 'ConsType', this.value)" ${row.IT !== 'DYC' ? 'disabled' : ''}>
                    <option value="" ${row.ConsType === '' ? "selected" : ""}></option>
                    <option value="Quantity" ${row.ConsType === 'Quantity' ? "selected" : ""}>Quantity</option>
                    <option value="Percentage" ${row.ConsType === 'Percentage' ? "selected" : ""}>Percentage</option>
                </select>`}
            </td>
            <td>${row.UoM}</td>
            <td>${row.IT !== 'DYC'? row.Cons: `<input type="number" value="${row.Cons}" onchange="updateField(${row.Gr}, 'Cons', this.value)" ${row.IT !== 'DYC' ? 'disabled' : ''}>`}</td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="deleteRow(${row.Gr}, ${row.Sq}, ${row.SubSq})">Delete</button>
            </td>
        `;

        tableBody.appendChild(tr);
        initSelect2ForItemCode(tr.querySelector(".select2-itemcode"), index);
        initSelect2ForComment(tr.querySelector(".select2-comment"), index);

        if (row.IT === 'RFF') {
            loadRffDetail(row.ItemCode, row.SuffixCode, row.Gr, tr);
        }
    });
    }
</script>

<!-- Untuk Urutin berdasarkan Gr -->
<script>
    function updateGr(oldGr, newGr, sq, selectEl) {
    // Convert to numbers for consistent comparison
    const oldGrNum = Number(oldGr);
    const newGrNum = Number(newGr) || 0;
    const sqNum = Number(sq);
    const GrTp = Number(selectEl);
    
    // Find the exact item to update
    const index = data.findIndex(item => 
        Number(item.Gr) === oldGrNum && 
        Number(item.Sq) === sqNum &&
        Number(item.GrTp) === GrTp
    );
    
    if (index !== -1) {
        // Update the Gr value
        data[index].Gr = newGrNum;
        
        // Re-sort the data
        data.sort((a, b) => {
            const grDiff = Number(a.Gr) - Number(b.Gr);
            return grDiff !== 0 ? grDiff : Number(a.Sq) - Number(b.Sq);
        });
        
        populateTable();
    }
 }
</script>

<!-- Urutin Berdasarkan Sq -->
 <script>
//     function updateSq(grValue, field, newSqValue) {
//     // Cari index data berdasarkan Gr (karena Sq bisa sama di Gr berbeda)
//     const index = data.findIndex(item => item.Sq == grValue && item[field] == this.previousValue);
    
//     if (index !== -1) {
//         // Update nilai Sq
//         data[index][field] = parseInt(newSqValue);
//         populateTable(); // Refresh tabel
//     }
// }
function updateSq(grValue, sqValue, newSqValue) {
    // Cari item spesifik berdasarkan Gr dan Sq
    const index = data.findIndex(item => 
        item.Gr == grValue && 
        item.Sq == sqValue
    );
    
    if (index !== -1) {
        // Update nilai Sq
        data[index].Sq = parseInt(newSqValue) || 0;
        
        // Urutkan ulang data dalam Gr yang sama
        data.sort((a, b) => {
            if (a.Gr == b.Gr) {
                return parseInt(a.Sq) - parseInt(b.Sq);
            }
            return parseInt(a.Gr) - parseInt(b.Gr);
        });
        
        populateTable();
    }
    }
</script>

<!-- Untuk Select2 Item -->
<script>
    function initSelect2ForItemCode(selectElement, rowIndex) {
    $(selectElement).select2({
        theme: 'bootstrap4',
        placeholder: '',
        allowClear: true,
        minimumInputLength: 1,
        ajax: {
            url: 'ajax/search_recipe.php',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                const row = data[rowIndex];
                const groupTypeCode = row?.GrTp || '';
                console.log('Kirim GROUPTYPECODE:', groupTypeCode, 'Row Index:', params.term);
                return {
                    q: params.term,
                    GROUPTYPECODE: groupTypeCode
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $(selectElement).on('select2:select', function (e) {
        const selected = e.params.data;
        const row = data[rowIndex];
        if (!row) return;

        row.ItemCode = '';
        row.Subcode01 = '';
        row.Subcode02 = '';
        row.Subcode03 = '';
        row.SuffixCode = '';
        row.Description = '';
        row.Comment = '';
        row.UoM = '';
        row.Cons = '0.00000';

        row.ItemCode = selected.id;
        row.Subcode01 = selected.subcode01;
        row.Subcode02 = selected.subcode02 || '';
        row.Subcode03 = selected.subcode03 || '';
        row.SuffixCode = selected.suffixcode;
        row.Description = selected.longdescription || '';
        row.Comment = selected.commentline || '';
        row.UoM = selected.uom || '';
        row.Cons = formatDecimal(selected.consumption) || '';

        populateTable();
    });

    $(selectElement).on('select2:unselect', function () {
        const row = data[rowIndex];
        if (row) {
            row.ItemCode = '';
            row.Subcode01 = '';
            row.Subcode02 = '';
            row.Subcode03 = '';
            row.SuffixCode = '';
            row.Description = '';
            row.Comment = '';
            row.UoM = '';
            row.Cons = '0.00000';
            populateTable();
        }
    });   
    }
</script>

<!-- Untuk Select2 Comment -->
<script>
    function initSelect2ForComment(selectElement, rowIndex) {
    $(selectElement).select2({
        theme: 'bootstrap4',
        placeholder: '',
        allowClear: true,
        minimumInputLength: 1,
        ajax: {
            url: 'ajax/search_recipe.php',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                const row = data[rowIndex];
                const groupTypeCode = row?.GrTp || '';
                console.log('Kirim GROUPTYPECODE:', groupTypeCode, 'Row Index:', params.term);
                return {
                    q: params.term,
                    GROUPTYPECODE: groupTypeCode
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $(selectElement).on('select2:select', function (e) {
        const selected = e.params.data;
        const row = data[rowIndex];
        if (!row) return;

        row.ItemCode = '';
        row.Subcode01 = '';
        row.Subcode02 = '';
        row.Subcode03 = '';
        row.SuffixCode = '';
        row.Description = '';
        row.Comment = '';
        row.UoM = '';
        row.Cons = '0.00000';

        row.ItemCode = '';
        row.Subcode01 = selected.subcode01;
        row.Subcode02 = selected.subcode02 || '';
        row.Subcode03 = selected.subcode03 || '';
        row.SuffixCode = selected.suffixcode;
        row.Description = selected.longdescription || '';
        row.Comment = selected.commentline || '';
        row.UoM = selected.uom || '';
        row.Cons = formatDecimal(selected.consumption) || '';

        populateTable();
    });

    $(selectElement).on('select2:unselect', function () {
        const row = data[rowIndex];
        if (row) {
            row.ItemCode = '';
            row.Subcode01 = '';
            row.Subcode02 = '';
            row.Subcode03 = '';
            row.SuffixCode = '';
            row.Description = '';
            row.Comment = '';
            row.UoM = '';
            row.Cons = '0.00000';
            populateTable();
        }
    });   
    }
</script>

<!-- Script Untuk Load RFF Detail Each Item -->
<script>
    function loadRffDetail(subcode01, suffixcode, grNumber, insertAfterRow) {
    fetch('ajax/fetch_detail_rff.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `subcode01=${encodeURIComponent(subcode01)}&suffixcode=${encodeURIComponent(suffixcode)}`
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            result.data.forEach(detail => {
                const detailRow = document.createElement("tr");
                detailRow.style.backgroundColor = "#f3f3f3";
                detailRow.innerHTML = `
                    <td>${grNumber}</td>
                    <td>${detail.GROUPTYPECODE}</td>
                    <td>${detail.SEQUENCE}</td>
                    <td>${detail.SUBSEQUENCE}</td>
                    <td>${detail.ITEMTYPEAFICODE}</td>
                    <td>${detail.ITEMCODE}</td>
                    <td>${detail.SUFFIXCODE?? ''}</td>
                    <td>${detail.COMMENTLINE}</td>
                    <td>${detail.LONGDESCRIPTION ?? ''}</td>
                    <td>${detail.CONSUMPTIONTYPE}</td>
                    <td>${detail.COMPONENTUOMCODE}</td>
                    <td>${formatDecimal(detail.CONSUMPTION)??''}</td>
                    <td></td>
                `;
                insertAfterRow.parentNode.insertBefore(detailRow, insertAfterRow.nextSibling);
                insertAfterRow = detailRow;
            });
        }
    });
    }
</script>

<!-- Script Untuk Delete Row -->
<script>
    function deleteRow(gr, sq, subSq) {
        Swal.fire({
            title: 'Yakin?',
            text: 'Data ini akan dihapus dari tabel!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Filter untuk hapus berdasarkan Gr, Sq, dan SubSq
                data = data.filter(row => !(row.Gr === gr && row.Sq === sq && row.SubSq === subSq));
                populateTable(); // Update tabel
                Swal.fire('Dihapus!', 'Data telah dihapus.', 'success');
            } else {
                Swal.fire('Batal!', 'Data tidak jadi dihapus.', 'error');
            }
        });
    }
</script>

<!-- Script Untuk Insert Ke DB -->
<script>
 function getCellValue(cell) {
    const input = cell.querySelector('input');
    const select = cell.querySelector('select');
    if (input) return input.value.trim();
    if (select) return select.value.trim();
    return cell.textContent.trim();
}

async function fetchAutoCounter() {
    const res = await fetch('ajax/get_autocounter.php');
    const json = await res.json();
    if (!json.success) throw new Error(json.message || 'Gagal fetch auto counter');
    return json.nomor_urut;
}

async function updateAutoCounter(nomorUrut) {
    await fetch('ajax/update_autocounter.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({nomor_urut: nomorUrut })
    });
}

async function generateInsertQueries() {
    const table = document.getElementById("recipeComponents_table");
    if (!table) {
        console.error("Tabel dengan ID 'recipeComponents_table' tidak ditemukan.");
        return [];
    }

    const rows = Array.from(table.querySelectorAll("tbody tr"));
    const recipeCode = document.getElementById('recipecode_new').value.trim();
    const suffixCode = document.getElementById('suffix_new').value.trim();
    const importDatetime = new Date().toISOString().slice(0, 19).replace("T", " ");
    // let queryIndex = 0;
    const queries = [];
    const autoCounterStart = await fetchAutoCounter();
    let autoCounter = autoCounterStart;

    // 🧩 Grupkan baris berdasarkan GROUPNUMBER
    const groupedRows = {};
    rows.forEach((row) => {
        
        const groupNumber = getCellValue(row.querySelectorAll("td")[0]);
        if (!groupedRows[groupNumber]) {
            groupedRows[groupNumber] = [];
        }
        groupedRows[groupNumber].push(row);
    });

    let queryIndex = 0;

    // 🔁 Iterasi berdasarkan group
    for (const groupNumber in groupedRows) {
        const groupRows = groupedRows[groupNumber];
        const firstRowCells = groupRows[0].querySelectorAll("td");
        const firstItemType = getCellValue(firstRowCells[4]);
        const selectedRows = (firstItemType === 'RFF') ? [groupRows[0]] : groupRows;
        selectedRows.forEach((row) => {
            const cells = row.querySelectorAll("td");
            const groupTypeCode = getCellValue(cells[1]);
            const sequence = getCellValue(cells[2]);
            const subSequence = getCellValue(cells[3]);
            const itemTypeAfiCode = getCellValue(cells[4]);
            const rawSubCode = getCellValue(cells[5]);
            const suffixcode_dynamic = getCellValue(cells[6]);
            let comment = getCellValue(cells[7]).replace(/'/g, '`').replace(/"/g, '``');
            const compUom = getCellValue(cells[10]);
            let cons = getCellValue(cells[11]);
            cons = cons === '' ? 0 : parseFloat(cons);

            const contyp = getCellValue(cells[9]);
            const consType = contyp === 'Quantity' ? 1 : contyp === 'Percentage' ? 2 : '';
            let subCode01 = '', subCode02 = '', subCode03 = '', subCode04 = '';
            let subCode05 = '', subCode06 = '', subCode07 = '', subCode08 = '';

            let lineType;
                if (itemTypeAfiCode === 'DYC') {
                    lineType = 1;
                } else if (itemTypeAfiCode === 'RFF') {
                    lineType = 2;
                } else if (!itemTypeAfiCode || itemTypeAfiCode.trim() === '') {
                    lineType = 3;
                }
            let waterManagement = (itemTypeAfiCode === '') ?  0 : 1;

            if (itemTypeAfiCode === 'DYC') {
            const parts = rawSubCode.split('-');
                subCode01 = parts[0] ?? '';
                subCode02 = parts[1] ?? '';
                subCode03 = parts[2] ?? '';
                subCode04 = parts[3] ?? '';
                subCode05 = parts[4] ?? '';
                subCode06 = parts[5] ?? '';
                subCode07 = parts[6] ?? '';
                subCode08 = parts[7] ?? '';
            } else {
                subCode01 = rawSubCode;
            }
            
            const myCounter = autoCounter++;
            const query = `INSERT INTO RECIPECOMPONENTBEAN (
                FATHERID, IMPORTAUTOCOUNTER, OWNEDCOMPONENT, RECIPEITEMTYPECODE, RECIPESUBCODE01,
                RECIPESUFFIXCODE, GROUPNUMBER, GROUPTYPECODE, LINETYPE, SEQUENCE,
                SUBSEQUENCE, COMPONENTINCIDENCE, REFRECIPEGROUPNUMBER, REFRECIPESEQUENCE,
                REFRECIPESUBSEQUENCE, REFRECIPESTATUS, ITEMTYPEAFICODE, SUBCODE01, SUBCODE02, SUBCODE03, SUBCODE04, SUBCODE05, SUBCODE06, SUBCODE07, SUBCODE08, 
                SUFFIXCODE, COMMENTLINE, CONSUMPTIONTYPE, ASSEMBLYUOMCODE, COMPONENTUOMCODE, COMPONENTUOMTYPE,
                CONSUMPTION, WATERMANAGEMENT, BINDERFILLERCOMPONENT, PRODUCED, COSTINGPLANTCODE,
                FINALENGINEERINGCHANGE, INITIALDATE, FINALDATE, ALLOWDELETEBINDERFILLER,
                WSOPERATION, IMPORTSTATUS, IMPORTDATETIME, RETRYNR, NEXTRETRY,
                IMPORTID, RELATEDDEPENDENTID, IMPOPERATIONUSER
            ) VALUES (
                'Anjay29181', '${autoCounter}', '0','RFD',
                '${recipeCode}','${suffixCode}', 
                '${groupNumber}', 
                '${groupTypeCode}', 
                '${lineType}', 
                '${sequence}',
                '${subSequence}', 
                '100', 
                '0', 
                '0',
                '0', 
                '0', 
                '${itemTypeAfiCode}', 
                '${subCode01}', 
                '${subCode02}', 
                '${subCode03}', 
                '${subCode04}',
                '${subCode05}', 
                '${subCode06}', 
                '${subCode07}', 
                '${subCode08}', 
                '${suffixcode_dynamic}', 
                '${comment}',
                '${consType}', 
                'l', 
                '${compUom}', 
                '', 
                '${cons}',
                '${waterManagement}',
                '0', 
                '0', 
                '001', 
                '9999999999', 
                '1970-01-01', 
                '2100-12-31', 
                '0',
                '1', 
                '0', 
                '${importDatetime}', 
                '3', 
                '0',
                '0', 
                '${autoCounter}', 
                '10.0.5.135');`.trim();

            queries.push(query);
        });
    }

    if (queries.length > 0) {
        console.log("✅ Query berhasil dibuat:");
        console.log(queries.join("\n\n"));
    } else {
        console.warn("Tidak ada query yang dibuat.");
    }
    await updateAutoCounter(autoCounter);
    console.log('Update nomor_urut selesai:', autoCounter);
    return queries;
}

// await updateAutoCounter(autoCounter);

</script>


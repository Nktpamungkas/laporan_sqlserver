<?php
    require_once "koneksi.php";
    include "utils/helper.php";
    $date = date('Y-m-d H:i:s');

    $recipe_code    = $_POST['recipe_code'] ?? null;
    $suffix         = $_POST['suffix'] ?? null;

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
                                                    <input type="text" name="recipe_code" id="recipe_code" placeholder="Masukan Recipe Code" class="form-control form-control-sm form-txt-primary" value="<?php if (isset($_POST['submit'])){ echo $_POST['recipe_code']; } ?>" autofocus>
                                                    <input type="text" name="suffix" id="suffix" placeholder="Masukan Suffix" class="form-control form-control-sm form-txt-primary" value="<?php if (isset($_POST['submit'])){ echo $_POST['suffix']; } ?>">
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
                                                        <div class="col-sm-6">
                                                            <input type="text" class="form-control form-control-sm form-control-danger" name="suffix_new" id="suffix_new" onkeydown="cekBonResep()" placeholder="Suffix">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control form-control-sm form-control-danger" name="long_new" id="long_new" onkeydown="cekBonResep()" placeholder="Long Description*">
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control form-control-sm form-control-danger" name="short_new" id="short_new" onkeydown="cekBonResep()" placeholder="Short Description">
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control form-control-sm form-control-danger" name="search_new" id="search_new" onkeydown="cekBonResep()" placeholder="Search Description">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
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
                                                    <th>Comment</th>
                                                    <th>Description</th>
                                                    <th>Cons type</th>
                                                    <th>UoM</th>
                                                    <th>Cons</th>
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
<script>
    $(document).ready(function() {
        $('#confirmCheck').on('change', function() {
            $('#exsecute').prop('disabled', !this.checked);
        });
        
        $('#exsecute').on('click', function() {
            const formData = {
                recipe_code_new: $('#recipecode_new').val(),
                suffix_new: $('#suffix_new').val(),
                long_new: $('#long_new').val(),
                short_new: $('#short_new').val(),
                search_new: $('#search_new').val(),
                lr_new: $('#lr_new').val()
            };

            if (formData.recipe_code_new && formData.suffix_new) {
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
                            success: function(response) {
                                Swal.fire({
                                    title: 'Sukses!',
                                    text: 'Data berhasil diexport ke NOW.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Refresh halaman setelah klik OK
                                    location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
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

                // Reset style dulu
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

    document.getElementById('basic-addon10').addEventListener('click', function() {
        var recipeCode = document.getElementById('recipe_code').value.trim();
        var suffix = document.getElementById('suffix').value.trim();

        if (recipeCode === '' || suffix === '') {
            Swal.fire('Error', 'Recipe Code dan Suffix harus diisi!', 'error');
            return;
        }

        fetch('fetch_data_recipe.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'recipe_code=' + encodeURIComponent(recipeCode) + '&suffix=' + encodeURIComponent(suffix)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('recipecode_before').value = data.recipecode_before;
                document.getElementById('suffix_before').value = data.suffix_before;
                document.getElementById('lr_new').value = data.lr_before;
                document.getElementById('long_new').value = data.longdescription;
                document.getElementById('short_new').value = data.shortdescription;
                document.getElementById('search_new').value = data.searchdescription;
                console.log(data.recipecomponent);

                // Isi tabel RecipeComponent
                const tableBody = $('#recipeComponents_table tbody');
                tableBody.empty(); // Clear existing rows

                data.recipecomponent.forEach(row => {
                    let itemCode = '';
                    if (row.ITEMTYPEAFICODE === 'RFF') {
                        itemCode = row.SUBCODE01 ?? '';
                    } else if (row.ITEMTYPEAFICODE === 'DYC'){
                        itemCode = `${row.SUBCODE01 ?? ''}-${row.SUBCODE02 ?? ''}-${row.SUBCODE03 ?? ''}`;
                    }else{
                        itemCode = ``;
                    }

                    let consumtiontype = '';
                    if(row.CONSUMPTIONTYPE === '1'){
                        consumtiontype = 'Quantity';
                    }else if(row.CONSUMPTIONTYPE === '2'){
                        consumtiontype = 'Percentage';
                    }

                    let UoM = '';
                    if(row.COMPONENTUOMCODE === 'g'){
                        UoM = 'gram';
                    }else{
                        UoM = '';
                    }

                    tableBody.append(`
                        <tr>
                            <td>${row.GROUPNUMBER ?? ''}</td>
                            <td>${row.GROUPTYPECODE ?? ''}</td>
                            <td>${row.SEQUENCE ?? ''}</td>
                            <td>${row.SUBSEQUENCE ?? ''}</td>
                            <td>${row.ITEMTYPEAFICODE ?? ''}</td>
                            <td>${itemCode}</td>
                            <td>${row.COMMENTLINE ?? ''}</td>
                            <td>${row.LONGDESCRIPTION ?? ''}</td>
                            <td>${consumtiontype}</td>
                            <td>${UoM}</td>
                            <td>${(row.CONSUMPTION ?? '') !== '' ? Number(row.CONSUMPTION).toFixed(5) : ''}</td>
                        </tr>
                    `);
                });
            } else {
                Swal.fire('Error', 'Data tidak ditemukan!', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Terjadi kesalahan saat mengambil data.', 'error');
        });
    });

</script>
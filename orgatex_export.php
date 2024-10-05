<?php
ini_set("error_reporting", 1);
session_start();
require_once "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Export to Orgatex</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="#">
    <meta name="keywords" content="Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="#">
    <link rel="icon" href="files\assets\images\favicon.ico" type="image/x-icon">
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet"> -->
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
                                        <h5>Export to Orgatex</h5>
                                    </div>
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Bon Resep</h5>
                                                <input type="text" id="production_number" class="form-control">
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Dyelot</h5>
                                                <input type="text" id="dyelot" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Redye</h5>
                                                <input type="text" id="redye" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Machine Number</h5>
                                                <input type="text" id="machine_number" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Type Of Procedure</h5>
                                                <input type="text" id="procedure_type" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Procedure Number</h5>
                                                <input type="text" id="procedure_number" class="form-control" readonly>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Color</h5>
                                                <input type="text" id="color" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Recipe Number</h5>
                                                <input type="text" id="recipe_number" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Order Number</h5>
                                                <input type="text" id="order_number" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Customer Name</h5>
                                                <input type="text" id="customer_name" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Article</h5>
                                                <input type="text" id="article" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Color Number</h5>
                                                <input type="text" id="color_number" class="form-control" readonly>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Length</h5>
                                                <input type="text" id="length" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">LiquorRatio</h5>
                                                <input type="text" id="liquorRatio" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">LiquorQuantity</h5>
                                                <input type="text" id="liquorQuantity" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">PumpSpeed</h5>
                                                <input type="text" id="pumpSpeed" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">ReelSpeed</h5>
                                                <input type="text" id="reelSpeed" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Absorption</h5>
                                                <input type="text" id="absorption" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-xl-2 m-b-30">
                                                <h5 class="sub-title">Weight</h5>
                                                <input type="text" id="weight" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-header">
                                        <h5>Preview Recipe</h5>
                                    </div>
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-12">
                                                <table class="table table-sm table-bordered" id="recipe_table">
                                                    <thead>
                                                        <tr>
                                                            <th>Code</th>
                                                            <th>Subcode</th>
                                                            <th>Commentline</th>
                                                            <th>Description</th>
                                                            <th>Consumption</th>
                                                            <th>UoM</th>
                                                            <th>Qty</th>
                                                            <th>UoM</th>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Rows will be added here dynamically -->
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
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('#production_number').on('change', function() {
                const productionNumber = $(this).val();

                if (productionNumber) {
                    $.ajax({
                        url: 'fetch_data_for_orgatex.php',
                        type: 'POST',
                        data: {
                            production_number: productionNumber
                        },
                        success: function(response) {
                            const data = JSON.parse(response);

                            console.log(data);

                            if (data.success) {
                                // Populate input fields
                                $('#dyelot').val(data.dyelot).prop('disabled', false);
                                $('#redye').val(data.redye).prop('disabled', false);
                                $('#machine_number').val(data.machine).prop('disabled', false);
                                $('#procedure_type').val(data.type_of_procedure).prop('disabled', false);
                                $('#procedure_number').val(data.procedure_no).prop('disabled', false);
                                $('#color').val(data.color).prop('disabled', false);

                                $('#recipe_number').val(data.recipe_number).prop('disabled', false);
                                $('#order_number').val(data.order_number).prop('disabled', false);
                                $('#customer_name').val(data.customer_name).prop('disabled', false);
                                $('#article').val(data.article).prop('disabled', false);
                                $('#color_number').val(data.color_number).prop('disabled', false);
                                $('#weight').val(data.weight).prop('disabled', false);

                                $('#length').val(data.length).prop('disabled', false);
                                $('#liquorRatio').val(data.liquorRatio).prop('disabled', false);
                                $('#liquorQuantity').val(data.liquorQuantity).prop('disabled', false);
                                $('#pumpSpeed').val(data.pumpSpeed).prop('disabled', false);
                                $('#reelSpeed').val(data.reelSpeed).prop('disabled', false);
                                $('#absorption').val(data.absorption).prop('disabled', false);

                                // Populate the recipe table
                                const tableBody = $('#recipe_table tbody');
                                tableBody.empty(); // Clear existing rows

                                console.log(data.recipes);

                                data.recipes.forEach(recipe => {
                                    tableBody.append(`
                                    <tr>
                                        <td>${recipe.CODE || ""}</td>
                                        <td>${recipe.SUBCODE || ""}</td>
                                        <td>${recipe.COMMENTLINE || ""}</td>
                                        <td>${recipe.LONGDESCRIPTION || ""}</td>
                                        <td>${(recipe.CONSUMPTION === '0.00000' || recipe.CONSUMPTION === 0) ? "" : recipe.CONSUMPTION || ''}}</td>
                                        <td>${recipe.CONSUMPTIONTYPE || ''}</td>  
                                        <td>${recipe.QUANTITY || ""}</td>
                                        <td>${recipe.CONSUMPTIONTYPEQTY || ""}</td>
                                    </tr>
                                `);
                                });
                            } else {
                                alert('No data found.');
                            }
                        },
                        error: function() {
                            alert('Error fetching data.');
                        }
                    });
                }
            });

            // Add event listener for a submit button to send data to the stored procedure
            $('#submit_button').on('click', function() {
                const formData = {
                    dyelot: $('#dyelot').val(),
                    redye: $('#redye').val(),
                    machine: $('#machine_number').val(),
                    procedureType: $('#procedure_type').val(),
                    procedureNo: $('#procedure_number').val(),
                    color: $('#color').val(),
                    recipeNo: $('#recipe_number').val(),
                    orderNo: $('#order_number').val(),
                    customer: $('#customer_name').val(),
                    article: $('#article').val(),
                    colorNo: $('#color_number').val(),
                    weight: parseFloat($('#weight').val()), // Ensure numeric values
                    length: parseFloat($('#length').val()), // Ensure numeric values
                    liquorRatio: parseFloat($('#liquorRatio').val()), // Ensure numeric values
                    liquorQuantity: parseFloat($('#liquorQuantity').val()), // Ensure numeric values
                    pumpSpeed: parseFloat($('#pumpSpeed').val()), // Ensure numeric values
                    reelSpeed: parseFloat($('#reelSpeed').val()), // Ensure numeric values
                    absorption: parseFloat($('#absorption').val()), // Ensure numeric values
                    recipes: [] // Collect recipe data
                };

                $('#recipe_table tbody tr').each(function() {
                    const code = $(this).find('td:nth-child(1)').text();
                    const subcode = $(this).find('td:nth-child(2)').text();
                    const consumption = parseFloat($(this).find('td:nth-child(3)').text()); // Ensure numeric value
                    const productName = $(this).find('td:nth-child(4)').text(); // Add additional fields as needed
                    const consum = parseFloat($(this).find('td:nth-child(5)').text()); // Adjust based on your table structure
                    const consumType = $(this).find('td:nth-child(6)').text(); // Adjust based on your table structure
                    const qty = $(this).find('td:nth-child(7)').text(); // Adjust based on your table structure
                    const qtyType = $(this).find('td:nth-child(8)').text(); // Adjust based on your table structure

                    formData.recipes.push({
                        Dyelot: $('#dyelot').val(),
                        ReDye: $('#redye').val(), // or get it from somewhere if needed
                        CorrectionNumber: 1, // or get it from somewhere if needed
                        CallOff: 1, // or get it from somewhere if needed
                        Counter: formData.recipes.length + 1, // Increment counter
                        ProductName: productName,
                        Amount: consum,
                        Unit: consumType,
                        KindOfStation: 5, // or get it from somewhere if needed
                        NoOfStation: 5, // or get it from somewhere if needed
                        SpecificWeight: 1, // or get it from somewhere if needed
                        ProductCode: productName,
                        ProductShortName: productName, // or adjust accordingly
                        KindOfProduct: 2, // or get it from somewhere if needed
                        RecipeUnit: "g/L" // or adjust accordingly
                    });
                });

                // Make an AJAX call to your PHP script that calls the stored procedure
                $.ajax({
                    url: 'insert_data_to_orgatex.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        alert('Data successfully inserted!');
                    },
                    error: function() {
                        alert('Error inserting data.');
                    }
                });
            });
        });
    </script>
</body>
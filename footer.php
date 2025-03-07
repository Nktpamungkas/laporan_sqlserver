<?php

$currentIP = $_SERVER['REMOTE_ADDR'];
$allowedIPs = ['10.0.5.132', '10.0.6.247', '10.0.5.36', '10.0.7.75'];

echo '<script>';
echo 'var currentIP = "' . $currentIP . '";';
echo 'var allowedIPs = ' . json_encode($allowedIPs) . ';';
echo '</script>';

?>
<script type="text/javascript" src="files\bower_components\jquery\js\jquery.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="files\bower_components/jquery_validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="files\bower_components\jquery-ui\js\jquery-ui.min.js"></script>
<script type="text/javascript" src="files\bower_components\popper.js\js\popper.min.js"></script>
<script type="text/javascript" src="files\bower_components\bootstrap\js\bootstrap.min.js"></script>
<!-- jquery slimscroll js -->
<script type="text/javascript" src="files\bower_components\jquery-slimscroll\js\jquery.slimscroll.js"></script>
<!-- modernizr js -->
<script type="text/javascript" src="files\bower_components\modernizr\js\modernizr.js"></script>
<script type="text/javascript" src="files\bower_components\modernizr\js\css-scrollbars.js"></script>

<!-- Syntax highlighter prism js -->
<script type="text/javascript" src="files\assets\pages\prism\custom-prism.js"></script>
<!-- i18next.min.js -->
<script type="text/javascript" src="files\bower_components\i18next\js\i18next.min.js"></script>
<script type="text/javascript" src="files\bower_components\i18next-xhr-backend\js\i18nextXHRBackend.min.js"></script>
<script type="text/javascript" src="files\bower_components\i18next-browser-languagedetector\js\i18nextBrowserLanguageDetector.min.js"></script>
<script type="text/javascript" src="files\bower_components\jquery-i18next\js\jquery-i18next.min.js"></script>
<!-- Custom js -->
<script src="files\assets\js\pcoded.min.js"></script>
<script src="files\assets\js\menu\menu-hori-fixed.js"></script>
<script src="files\assets\js\jquery.mCustomScrollbar.concat.min.js"></script>
<!-- <script type="text/javascript" src="files\assets\js\script.js"></script> -->

<!-- data-table js -->
<script src="files\bower_components\datatables.net\js\jquery.dataTables.min.js"></script>
<script src="files\bower_components\datatables.net-buttons\js\dataTables.buttons.min.js"></script>
<script src="files\assets\pages\data-table\js\jszip.min.js"></script>
<script src="files\assets\pages\data-table\js\pdfmake.min.js"></script>
<script src="files\assets\pages\data-table\js\vfs_fonts.js"></script>
<script src="files\bower_components\datatables.net-buttons\js\buttons.print.min.js"></script>
<script src="files\bower_components\datatables.net-buttons\js\buttons.html5.min.js"></script>
<script src="files\bower_components\datatables.net-bs4\js\dataTables.bootstrap4.min.js"></script>
<script src="files\bower_components\datatables.net-responsive\js\dataTables.responsive.min.js"></script>
<script src="files\bower_components\datatables.net-responsive-bs4\js\responsive.bootstrap4.min.js"></script>

<!-- i18next.min.js -->
<script type="text/javascript" src="files\bower_components\i18next\js\i18next.min.js"></script>
<script type="text/javascript" src="files\bower_components\i18next-xhr-backend\js\i18nextXHRBackend.min.js"></script>
<script type="text/javascript" src="files\bower_components\i18next-browser-languagedetector\js\i18nextBrowserLanguageDetector.min.js"></script>
<script type="text/javascript" src="files\bower_components\jquery-i18next\js\jquery-i18next.min.js"></script>
<!-- Custom js -->
<script src="files\assets\pages\data-table\js\data-table-custom.js"></script>

<!-- sweet alert js -->
<script type="text/javascript" src="files\bower_components\sweetalert\js\sweetalert.min.js"></script>
<script type="text/javascript" src="files\assets\js\modal.js"></script>
<!-- sweet alert modal.js intialize js -->
<!-- modalEffects js nifty modal window effects -->
<script type="text/javascript" src="files\assets\js\modalEffects.js"></script>
<script type="text/javascript" src="files\assets\js\classie.js"></script>
<script src="dist/js/select2.min.js"></script>
<script type="text/javascript" src="files\bower_components\select2\js\select2.full.min.js"></script>
<script type="text/javascript" src="files\assets\pages\advance-elements\select2-custom.js"></script>
<script type="text/javascript" src="xeditable/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<!-- Multiselect js -->
<script type="text/javascript" src="files\bower_components\bootstrap-multiselect\js\bootstrap-multiselect.js">


</script>
<script type="text/javascript" src="files\bower_components\multiselect\js\jquery.multi-select.js"></script>
<script type="text/javascript" src="files\assets\js\jquery.quicksearch.js"></script>
<script src="alert/toastr.js"></script>
<script src="alert/sweetalert2.all.min.js"></script>
<script>
  $.fn.editable.defaults.mode = 'inline';
  $(document).ready(function() {
    $('.note_edit').editable({
      type: 'text',
      showbuttons: false,
      url: 'editable/editable_bukupinjam_note.php',
    });
    $('.kode_edit').editable({
      type: 'select',
      showbuttons: false,
      url: 'editable/editable_bukupinjam_kode.php',
      disabled: false,
      showbuttons: false,
      source: [{
          value: "",
          text: ""
        },
        {
          value: "DL",
          text: "DL - Dye Lot Card"
        },
        {
          value: "RC",
          text: "RC - Recipe Card"
        },
        {
          value: "OR",
          text: "OR - Original"
        },
        {
          value: "LD",
          text: "LD - Lab Dip"
        },
        {
          value: "SL",
          text: "SL - Sample L/D"
        },
        {
          value: "TE",
          text: "TE - Tempelan Sample Celup"
        },
        {
          value: "FL",
          text: "FL - Frist Lot"
        }
      ]
    });
    $('.archive_edit').editable({
      type: 'select',
      showbuttons: false,
      url: 'editable/editable_archive.php',
      disabled: false,
      showbuttons: false,
      source: [{
          value: "",
          text: ""
        },
        {
          value: "Diarsipkan",
          text: "Diarsipkan"
        },
        {
          value: "Belum_Diarsipkan",
          text: "Belum Diarsipkan"
        }
      ]
    });
    $('.archive_edit_te').editable({
      type: 'select',
      showbuttons: false,
      url: 'editable/editable_archive_te.php',
      disabled: false,
      showbuttons: false,
      source: [{
          value: "",
          text: ""
        },
        {
          value: "Diarsipkan LAB",
          text: "Diarsipkan LAB"
        },
        {
          value: "Diarsipkan",
          text: "Diarsipkan"
        },
        {
          value: "Belum_Diarsipkan",
          text: "Belum Diarsipkan"
        }
      ]
    });
  })
</script>
<script>
  window.dataLayer = window.dataLayer || [];

  function gtag() {
    dataLayer.push(arguments);
  }
  gtag('js', new Date());

  gtag('config', 'UA-23581568-13');

  $(document).ready(function() {
    // Single Search Select
    $(".js-example-basic-single").select2();

    // Multi-select js start
    $('#my-select').multiSelect();
    $('#public-methods').multiSelect();
    $('#select-all').on('click', function() {
      $('#public-methods').multiSelect('select_all');
      return false;
    });
    $('#deselect-all').on('click', function() {
      $('#public-methods').multiSelect('deselect_all');
      return false;
    });

    $('#public-methods2').multiSelect();
    $('#select-all2').on('click', function() {
      $('#public-methods2').multiSelect('select_all');
      return false;
    });
    $('#deselect-all2').on('click', function() {
      $('#public-methods2').multiSelect('deselect_all');
      return false;
    });

  });
</script>
<script>
  $(document).ready(function() {
    $("#scouring_preset").attr('value','false');
    $("#scouring_preset").on('change', function() {
      if ($(this).is(':checked')) {
          $(this).attr('value', 'true');
      } else {
          $(this).attr('value', 'false');
      }
    });

    // Show loading overlay
    function showLoading() {
      $('#loadingOverlay').show();
    }

    // Hide loading overlay
    function hideLoading() {
      $('#loadingOverlay').hide();
    }

    // Show toast error
    function showToastError(message) {
      toastr.error(message, 'Error', {
        closeButton: true,
        progressBar: true,
      });
    }

    // Show toast success
    function showToastSuccess(message) {
      toastr.success(message, 'Success', {
        closeButton: true,
        progressBar: true,
      });
    }

    $(document).ready(function() {
      $('.js-example-basic-multiple').select2();
    });

    // Event saat item dipilih
    $('#group_line').on('select2:select', function(e) {
      let selectedValues = $(this).val();
      let productionNumber = $('#production_number').val();

      let formattedString = selectedValues.length > 1 ?
        selectedValues.map(num => `'${num}'`).join(',') :
        selectedValues[0];

      // Panggil fungsi AJAX
      jalankanFungsi2(productionNumber, formattedString, selectedValues[0], selectedValues);
    });

    // Event saat item di-unselect
    $('#group_line').on('select2:unselect', function(e) {
      let selectedValues = $(this).val() || []; // Handle case saat tidak ada yang terpilih
      let productionNumber = $('#production_number').val();

      let formattedString = selectedValues.length > 1 ?
        selectedValues.map(num => `'${num}'`).join(',') :
        selectedValues[0] || ''; // Jika kosong, kembalikan string kosong

      // Panggil fungsi AJAX
      jalankanFungsi2(productionNumber, formattedString, selectedValues[0] || '', selectedValues);
    });

    function getGroupLine(productionNumber, scouring_preset) {
      showLoading();
      $.ajax({
        url: 'fetch_cheking_groupline.php',
        type: 'POST',
        dataType: "json",
        data: {
          production_number: productionNumber,
          scouring_preset: scouring_preset
        },
        success: function(response) {

          if (response.success) {
            let groupLine = response.groupLine;
            let formattedString = '';
            if (groupLine.length > 1) {
              formattedString = groupLine.map(num => `'${num}'`).join(',');
            } else {
              formattedString = groupLine[0];
            }
            jalankanFungsi(productionNumber, formattedString, groupLine[0], groupLine);
          } else {
            hideLoading();
            showToastError('Data not found, please check your production number');
          }

        },
        error: function(e) {
          console.log(e);
          hideLoading();
          showToastError('Something when wrong, please try again');

        }
      });
    }

    // fungsi fetch data dari production number
    function jalankanFungsi(productionNumber, groupLineArray, groupLine, arrayDariGetGroup) {
      if (productionNumber) {
        $.ajax({
          url: 'fetch_data_for_orgatex.php',
          type: 'POST',
          data: {
            production_number: productionNumber,
            groupLineArray: groupLineArray,
            groupLine: groupLine,
            arrayDariGetGroup: arrayDariGetGroup
          },
          success: function(response) {
            const data = JSON.parse(response);

            if (data.success) {
              // Populate input fields
              $('#dyelot').val(data.dyelot).prop('disabled', false);

              let arrayDariGetGroup = data.arrayDariGetGroup;

              // Kosongkan select terlebih dahulu
              $('#group_line').empty();

              // Tambahkan options ke select tanpa mengatur val di dalam loop
              arrayDariGetGroup.forEach(element => {
                var newOption = new Option(element, element, false, false);
                $('#group_line').append(newOption);
              });

              // Setelah semua options ditambahkan, set val untuk yang terpilih
              $('#group_line').val(arrayDariGetGroup).trigger('change');

              $('#redye').val(data.redye).prop('disabled', false);
              $('#machine_number').val(data.machine).prop('disabled', false);
              $('#machine_number_new').val(data.machine_new).prop('disabled', false);
              $('#procedure_type').val(data.type_of_procedure).prop('disabled', false);
              $('#procedure_number').val(data.procedure_no).prop('disabled', false);
              $('#color').val(data.color).prop('disabled', false);
              $('#warna').val(data.warna).prop('disabled', false);

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

              let currentGroup = null; // Track the current group number
              let callOff = 0; // Start with a call off of 1
              let counter = 1; // Start counter from 1

              data.recipes.forEach(recipe => {
                const groupNumber = recipe.GROUPNUMBER;

                // If the group number changes, increment callOff and reset counter
                // pengecekan SUBCODE (untuk comment SUBCODEnya tidak ada / null), tidak perlu menghitung callof dan counter
                if (recipe.SUBCODE) {
                  if (currentGroup !== groupNumber) {
                    callOff++;
                    counter = 1; // Reset counter to 1
                    currentGroup = groupNumber; // Update current group
                  } else {
                    counter++; // Increment counter if the group number is the same
                  }
                }
                tableBody.append(`
                        <tr>
                            <td>${recipe.CODE || ""}</td>
                            <td>${recipe.SUBCODE || ""}</td>
                            <td>${recipe.COMMENTLINE || ""}</td>
                            <td>${recipe.LONGDESCRIPTION || ""}</td>
                            <td>${(recipe.CONSUMPTION === '0.00000' || recipe.CONSUMPTION === 0) ? "" : recipe.CONSUMPTION || ''}</td>
                            <td>${recipe.CONSUMPTIONTYPE || ''}</td>  
                            <td>${recipe.QUANTITY || ""}</td>
                            <td>${recipe.CONSUMPTIONTYPEQTY || ""}</td>
                            <td>${groupNumber || ""}</td>
                            <td>${recipe.SUBCODE ? callOff : ''}</td>
                            <td>${recipe.SUBCODE ? counter : ''}</td>
                        </tr>
                    `);
                // }
              });

              // Populate the recipe table
              const tableBodyTreatment = $('#treatment_table tbody');
              tableBodyTreatment.empty(); // Clear existing rows

              tableBodyTreatment.append(`
                                    <tr>
                                        <td></td>
                                        <td>9990</td>
                                        <td>Progarm Start</td>
                                        <td><span class="pcoded-micon"><i class="fa fa-check-circle" style="color: #0bdf0f;"></i></span> Available</td>
                                    </tr>
                                `);

              data.treatments.forEach(treatment => {
                tableBodyTreatment.append(`
                                    <tr>
                                        <td>${treatment.SUBCODE01} - ${treatment.SUFFIXCODE}</td>
                                        <td>${treatment.MAINPROGRAM || ""}</td>
                                        <td>${treatment.TREATMENTNAME || ""}</td>
                                        <td>${treatment.VALIDATION || ""}</td>
                                    </tr>
                                `);
              });

              tableBodyTreatment.append(`
                                    <tr>
                                        <td>-</td>
                                        <td>9991</td>
                                        <td>Program End</td>
                                        <td><span class="pcoded-micon"><i class="fa fa-check-circle" style="color: #0bdf0f;"></i></span> Available</td>
                                    </tr>
                                `);
              hideLoading();
            } else {
              hideLoading();
              // showToastError('Data not found, please check your production number');
              showToastError(data.message);
            }

          },
          error: function() {
            hideLoading();
            showToastError('Something when wrong, please try again');

          }
        });
      } else {
        $('#production_number,#dyelot, #redye, #machine_number, #machine_number_new, #procedure_type, #procedure_number, #color,#warna, #recipe_number, #order_number, #customer_name, #article, #color_number, #weight, #length, #liquorRatio, #liquorQuantity, #pumpSpeed, #reelSpeed, #absorption, #blower_speed, #move_speed').val('');
        $('#group_line').empty();
        // Populate the recipe table
        const tableBody = $('#recipe_table tbody');
        tableBody.empty(); // Clear existing rows

        // Populate the recipe table
        const tableBodyTreatment = $('#treatment_table tbody');
        tableBodyTreatment.empty(); // Clear existing rows
      }
    }

    // fungsi fetch data dari production number
    function jalankanFungsi2(productionNumber, groupLineArray, groupLine, arrayDariGetGroup) {
      showLoading();
      if (productionNumber) {
        $.ajax({
          url: 'fetch_data_for_orgatex.php',
          type: 'POST',
          data: {
            production_number: productionNumber,
            groupLineArray: groupLineArray,
            groupLine: groupLine,
            arrayDariGetGroup: arrayDariGetGroup
          },
          success: function(response) {
            const data = JSON.parse(response);

            if (data.success) {
              // Populate input fields
              $('#dyelot').val(data.dyelot).prop('disabled', false);

              let arrayDariGetGroup = data.arrayDariGetGroup;

              // Kosongkan select terlebih dahulu
              $('#group_line').empty();

              // Tambahkan options ke select tanpa mengatur val di dalam loop
              arrayDariGetGroup.forEach(element => {
                var newOption = new Option(element, element, false, false);
                $('#group_line').append(newOption);
              });

              // Setelah semua options ditambahkan, set val untuk yang terpilih
              $('#group_line').val(arrayDariGetGroup).trigger('change');

              $('#redye').val(data.redye).prop('disabled', false);
              $('#machine_number').val(data.machine).prop('disabled', false);
              $('#machine_number_new').val(data.machine_new).prop('disabled', false);
              $('#procedure_type').val(data.type_of_procedure).prop('disabled', false);
              $('#procedure_number').val(data.procedure_no).prop('disabled', false);
              $('#color').val(data.color).prop('disabled', false);
              $('#warna').val(data.warna).prop('disabled', false);

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

              let currentGroup = null; // Track the current group number
              let callOff = 0; // Start with a call off of 1
              let counter = 1; // Start counter from 1

              data.recipes.forEach(recipe => {
                const groupNumber = recipe.GROUPNUMBER;

                // If the group number changes, increment callOff and reset counter
                // pengecekan SUBCODE (untuk comment SUBCODEnya tidak ada / null), tidak perlu menghitung callof dan counter
                if (recipe.SUBCODE) {
                  if (currentGroup !== groupNumber) {
                    callOff++;
                    counter = 1; // Reset counter to 1
                    currentGroup = groupNumber; // Update current group
                  } else {
                    counter++; // Increment counter if the group number is the same
                  }
                }
                tableBody.append(`
                        <tr>
                            <td>${recipe.CODE || ""}</td>
                            <td>${recipe.SUBCODE || ""}</td>
                            <td>${recipe.COMMENTLINE || ""}</td>
                            <td>${recipe.LONGDESCRIPTION || ""}</td>
                            <td>${(recipe.CONSUMPTION === '0.00000' || recipe.CONSUMPTION === 0) ? "" : recipe.CONSUMPTION || ''}</td>
                            <td>${recipe.CONSUMPTIONTYPE || ''}</td>  
                            <td>${recipe.QUANTITY || ""}</td>
                            <td>${recipe.CONSUMPTIONTYPEQTY || ""}</td>
                            <td>${groupNumber || ""}</td>
                            <td>${recipe.SUBCODE ? callOff : ''}</td>
                            <td>${recipe.SUBCODE ? counter : ''}</td>
                        </tr>
                    `);
                // }
              });

              // Populate the recipe table
              const tableBodyTreatment = $('#treatment_table tbody');
              tableBodyTreatment.empty(); // Clear existing rows

              tableBodyTreatment.append(`
                                    <tr>
                                        <td></td>
                                        <td>9990</td>
                                        <td>Progarm Start</td>
                                        <td><span class="pcoded-micon"><i class="fa fa-check-circle" style="color: #0bdf0f;"></i></span> Available</td>
                                    </tr>
                                `);

              data.treatments.forEach(treatment => {
                tableBodyTreatment.append(`
                                    <tr>
                                        <td>${treatment.SUBCODE01} - ${treatment.SUFFIXCODE}</td>
                                        <td>${treatment.MAINPROGRAM || ""}</td>
                                        <td>${treatment.TREATMENTNAME || ""}</td>
                                        <td>${treatment.VALIDATION || ""}</td>
                                    </tr>
                                `);
              });

              tableBodyTreatment.append(`
                                    <tr>
                                        <td>-</td>
                                        <td>9991</td>
                                        <td>Program End</td>
                                        <td><span class="pcoded-micon"><i class="fa fa-check-circle" style="color: #0bdf0f;"></i></span> Available</td>
                                    </tr>
                                `);
              hideLoading();
            } else {
              hideLoading();
              showToastError('Data not found, please check your production number');
            }

          },
          error: function() {
            hideLoading();
            showToastError('Something when wrong, please try again');

          }
        });
      } else {
        $('#production_number,#dyelot, #redye, #machine_number, #machine_number_new, #procedure_type, #procedure_number, #color,#warna, #recipe_number, #order_number, #customer_name, #article, #color_number, #weight, #length, #liquorRatio, #liquorQuantity, #pumpSpeed, #reelSpeed, #absorption, #blower_speed, #move_speed').val('');
        $('#group_line').empty();
        // Populate the recipe table
        const tableBody = $('#recipe_table tbody');
        tableBody.empty(); // Clear existing rows

        // Populate the recipe table
        const tableBodyTreatment = $('#treatment_table tbody');
        tableBodyTreatment.empty(); // Clear existing rows
      }
    }



    var urlParams = new URLSearchParams(window.location.search);
    var productionNumber = urlParams.get('bonresep'); // ganti dengan nama parameter
    var scouring_preset = urlParams.get('scouring_preset'); // ganti dengan nama parameter

    // Check kondisi apakah ada get di url
    if (productionNumber) {
      // Ini jika ada url get
      // jalankan query untuk mencari berdasarkan no kk
      getGroupLine(productionNumber, scouring_preset);
    } else {
      // ini jika dari change input no kk
      $('#production_number').on('change keydown', function() {
        if (event.type === 'keydown' && (event.key === 'Enter' || event.key === 'Tab')) {
          const productionNumber = $(this).val();
          const scouring_preset = document.getElementById("scouring_preset").value;
          // jalankan query untuk mencari berdasarkan no kk
          console.log(scouring_preset);
          getGroupLine(productionNumber, scouring_preset);
        }
      });
    }

    // Trigger button ketika klik untuk export
    $('#submit_button').on('click', function() {
      // munculin loading
      showLoading();
      // ini data untuk menyiapkan data sesuai store prosedure
      const formData = {
        dyelot: $('#dyelot').val(),
        redye: $('#redye').val(),
        machine: $('#machine_number').val(),
        procedureType: $('#procedure_type').val(),
        procedureNo: $('#procedure_number').val(),
        color: $('#color').val(),
        warna: $('#warna').val(),
        recipeNo: $('#recipe_number').val(),
        orderNo: $('#order_number').val(),
        customer: $('#customer_name').val(),
        article: $('#article').val(),
        colorNo: $('#color_number').val(),
        weight: parseFloat($('#weight').val()), // Ensure numeric values
        blower_speed: parseFloat($('#blower_speed').val()), // Ensure numeric values
        move_speed: parseFloat($('#move_speed').val()), // Ensure numeric values
        nozle: parseFloat($('#nozle').val()), // Ensure numeric values
        length: parseFloat($('#length').val()), // Ensure numeric values
        liquorRatio: parseFloat($('#liquorRatio').val()), // Ensure numeric values
        liquorQuantity: parseFloat($('#liquorQuantity').val()), // Ensure numeric values
        pumpSpeed: parseFloat($('#pumpSpeed').val()), // Ensure numeric values
        reelSpeed: parseFloat($('#reelSpeed').val()), // Ensure numeric values
        absorption: parseFloat($('#absorption').val()), // Ensure numeric values
        recipes: [], // Collect recipe data
        treatments: [], // Collect recipe data
        currentIP: currentIP,
      };
      // Validasi bahwa nomor mesin tidak boleh kosong
      if (formData.machine) {
        if (formData.treatments.MAINPROGRAM = 1) {
          // ambil data dari table dan input
          $('#recipe_table tbody tr').each(function() {
            const code = $(this).find('td:nth-child(1)').text();
            const subcode = $(this).find('td:nth-child(2)').text();
            // const commentline = $(this).find('td:nth-child(3)').text(); // Ensure numeric value
            const productName = $(this).find('td:nth-child(4)').text(); // Add additional fields as needed
            const consum = $(this).find('td:nth-child(5)').text(); // Adjust based on your table structure
            const consumType = $(this).find('td:nth-child(6)').text(); // Adjust based on your table structure
            const qty = $(this).find('td:nth-child(7)').text(); // Adjust based on your table structure
            const qtyType = $(this).find('td:nth-child(8)').text(); // Adjust based on your table structure
            const callOff = $(this).find('td:nth-child(10)').text(); // Adjust based on your table structure
            const counter = $(this).find('td:nth-child(11)').text(); // Adjust based on your table structure

            // pilih selain treatment
            if (productName) {
              // masukan ke variable formData dari data table dan input sebelumnya
              formData.recipes.push({
                CorrectionNumber: 0,
                CallOff: callOff,
                Counter: counter,
                ProductName: productName,
                Amount: qty != '' ? Number(qty) : 0,
                Unit: qtyType,
                KindOfStation: 5,
                NoOfStation: 5,
                SpecificWeight: 1,
                ProductCode: subcode,
                ProductShortName: subcode,
                KindOfProduct: consumType == "%" ? 1 : 2,
                RecipeUnit: consumType,
                RecipeAmount: consum
              });
            }
          });
          // ini untuk ambil data dari table treatment
          $('#treatment_table tbody tr').each(function() {
            const code = $(this).find('td:nth-child(2)').text();
            formData.treatments.push({
              TreatmentCnt: formData.treatments.length + 1,
              TreatmentNo: code
            });
          });

          // ubah formdata terutama recipes dan treatments lalu parse ke bentuk json sebelum di export
          formData.recipes = JSON.stringify(formData.recipes);
          formData.treatments = JSON.stringify(formData.treatments);

          // panggil ajax insert untuk meng export
          $.ajax({
            url: 'insert_data_to_orgatex.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
              if (response.success) {
                hideLoading();
                jalankanFungsi("");
                showToastSuccess('Success export data, please check in program orgatex');
              } else {
                hideLoading();
                showToastError('Error export data, please try again');
              }
            },
            error: function() {
              hideLoading();
              showToastError('Something when wrong, please try again');
            }
          });
        } else {
          hideLoading();
          showToastError('Error export data, Silahkan periksa Nomor Treatment anda.');
        }
      } else {
        hideLoading();
        showToastError('Error export data, nomor mesin tidak boleh kosong, periksa apakah schedule sudah dibuat atau belum.');
      }

    });

  });
</script>
</body>

</html>
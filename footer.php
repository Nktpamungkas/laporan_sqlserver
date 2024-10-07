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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
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
    // Show loading overlay
    function showLoading() {
      $('#loadingOverlay').show();
    }

    // Hide loading overlay
    function hideLoading() {
      $('#loadingOverlay').hide();
    }


    $('#production_number').on('change', function() {
      const productionNumber = $(this).val();

      if (productionNumber) {
        showLoading();
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

              let currentGroup = null; // Track the current group number
              let callOff = 0; // Start with a call off of 1
              let counter = 1; // Start counter from 1

              data.recipes.forEach(recipe => {
                const groupNumber = recipe.GROUPNUMBER;


                // Append the row to the table
                // if (recipe.LONGDESCRIPTION) {
                // If the group number changes, increment callOff and reset counter
                if (currentGroup !== groupNumber) {
                  callOff++;
                  counter = 1; // Reset counter to 1
                  currentGroup = groupNumber; // Update current group
                } else {
                  counter++; // Increment counter if the group number is the same
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
                            <td>${callOff}</td>
                            <td>${counter}</td>
                        </tr>
                    `);
                // }
              });

              // Populate the recipe table
              const tableBodyTreatment = $('#treatment_table tbody');
              tableBodyTreatment.empty(); // Clear existing rows

              console.log(data.treatments);

              tableBodyTreatment.append(`
                                    <tr>
                                        <td>-</td>
                                        <td>9990</td>
                                    </tr>
                                `);

              data.treatments.forEach(treatment => {
                tableBodyTreatment.append(`
                                    <tr>
                                        <td>${treatment.SUBCODE01 || ""}</td>
                                        <td>${treatment.MAINPROGRAM || ""}</td>
                                    </tr>
                                `);
              });

              tableBodyTreatment.append(`
                                    <tr>
                                        <td>-</td>
                                        <td>9991</td>
                                    </tr>
                                `);
              hideLoading();
            } else {
              hideLoading();
              toastr.error('Data not found, please check your production number', 'Error', {
                "timeOut": 0,
                "debug": false,
                "progressBar": true,
                "showDuration": "300",
                "hideDuration": "1000",
                "positionClass": "toast-top-right",
                "closeButton": true,
                "extendedTimeOut": 0,
                "preventDuplicates": false,
                "disableTimeOut": true,
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
              });
            }

          },
          error: function() {
            hideLoading();
            toastr.error('Something when wrong, please try again', 'Error', {
              "timeOut": 0,
              "debug": false,
              "progressBar": true,
              "showDuration": "300",
              "hideDuration": "1000",
              "positionClass": "toast-top-right",
              "closeButton": true,
              "extendedTimeOut": 0,
              "preventDuplicates": false,
              "disableTimeOut": true,
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut"
            });
          }
        });
      }
    });

    // Add event listener for a submit button to send data to the stored procedure
    $('#submit_button').on('click', function() {
      showLoading();
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
        recipes: [], // Collect recipe data
        treatments: [] // Collect recipe data
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
        const callOff = $(this).find('td:nth-child(10)').text(); // Adjust based on your table structure
        const counter = $(this).find('td:nth-child(11)').text(); // Adjust based on your table structure

        if (productName) {
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
            RecipeUnit: consumType
          });
        }
      });

      $('#treatment_table tbody tr').each(function() {
        const code = $(this).find('td:nth-child(2)').text();
        formData.treatments.push({
          TreatmentCnt: formData.treatments.length + 1,
          TreatmentNo: code
        });
      });


      formData.recipes = JSON.stringify(formData.recipes);
      formData.treatments = JSON.stringify(formData.treatments);

      // Make an AJAX call to your PHP script that calls the stored procedure
      $.ajax({
        url: 'insert_data_to_orgatex.php',
        type: 'POST',
        data: formData,
        success: function(response) {
          if (response.success) {
            hideLoading();
            toastr.options = {
              "timeOut": "2",
            };
            toastr.success('Success export data, please check in program orgatex', 'Success', {
              "debug": false,
              "progressBar": true,
              "showDuration": "300",
              "hideDuration": "1000",
              "positionClass": "toast-top-right",
              "closeButton": true,
              "preventDuplicates": false,
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut"
            });
            console.log(response); // Log the received data
          } else {
            hideLoading();
            toastr.options = {
              "timeOut": "0",
            };
            toastr.error('Error export data, please try again', 'Error', {
              "timeOut": 0,
              "debug": false,
              "progressBar": true,
              "showDuration": "300",
              "hideDuration": "1000",
              "positionClass": "toast-top-right",
              "closeButton": true,
              "extendedTimeOut": 0,
              "preventDuplicates": false,
              "disableTimeOut": true,
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut"
            });
            console.log(response);
          }
        },
        error: function() {
          hideLoading();
          toastr.error('Something when wrong, please try again', 'Error', {
            "timeOut": 0,
            "debug": false,
            "progressBar": true,
            "showDuration": "300",
            "hideDuration": "1000",
            "positionClass": "toast-top-right",
            "closeButton": true,
            "extendedTimeOut": 0,
            "preventDuplicates": false,
            "disableTimeOut": true,
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
          });
        }
      });

    });

    $.ajax({
      url: 'fetch_data_dyelots.php',
      type: 'GET',
      success: function(response) {
        const data = JSON.parse(response);

        console.log(data);

        if (data.success) {
          // Populate the recipe table
          const tableBody = $('#dyelot_table tbody');
          tableBody.empty(); // Clear existing rows

          console.log(data.dyelots);

          data.dyelots.forEach(dyelot => {
            tableBody.append(`
                                    <tr>
                                        <td>${dyelot.Dyelot || ""}</td>
                                        <td>${dyelot.ReDye || ""}</td>
                                        <td>${dyelot.Machine || ""}</td>
                                        <td>${dyelot.Color || ""}</td>
                                        <td>${dyelot.ImportState || ""}</td>
                                        <td>
                                            <button class="btn btn-danger" id="update-btn" data-dyelot="${dyelot.Dyelot}" data-redye="${dyelot.ReDye}" data-importstate="30">Delete Batch</button>
                                        </td>
                                    </tr>
                                `);
          });
        } else {
          console.log("Failed get data");
        }

      },
      error: function() {
        console.log("Something when wrong");
      }
    });

    // Event delegation for dynamically created buttons
    $('#dyelot_table').on('click', '#update-btn', function() {
      console.log('Button clicked');
      showLoading();
      const dyelot = $(this).data('dyelot');
      const redye = $(this).data('redye');
      const importState = $(this).data('importstate');

      // Make AJAX request to update ImportState
      $.ajax({
        url: 'update_dyelot.php', // Adjust this to your PHP script path
        type: 'POST',
        data: {
          DyelotToDelete: dyelot,
          RedyeToDelete: redye,
          ImportState: importState
        },
        success: function(response) {
          const data = JSON.parse(response);
          setTimeout(() => {
            hideLoading(); // Hide loading indicator after 15 seconds

            if (data.success) {
              toastr.success('Update dyelot success', 'Success', {
                "debug": false,
                "progressBar": true,
                "showDuration": "300",
                "hideDuration": "1000",
                "positionClass": "toast-top-right",
                "closeButton": true,
                "preventDuplicates": false,
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
              });
            } else {
              toastr.error('Failed update dyelot', 'Error', {
                "timeOut": 0,
                "debug": false,
                "progressBar": true,
                "showDuration": "300",
                "hideDuration": "1000",
                "positionClass": "toast-top-right",
                "closeButton": true,
                "extendedTimeOut": 0,
                "preventDuplicates": false,
                "disableTimeOut": true,
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
              });
            }
            window.location.reload();
          }, 15000); // Wait for 15 seconds before hiding the loading indicator
        },
        error: function() {
          hideLoading();
          toastr.error('Something when wrong, please try again', 'Error', {
            "timeOut": 0,
            "debug": false,
            "progressBar": true,
            "showDuration": "300",
            "hideDuration": "1000",
            "positionClass": "toast-top-right",
            "closeButton": true,
            "extendedTimeOut": 0,
            "preventDuplicates": false,
            "disableTimeOut": true,
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
          });
        }
      });

    });
  });
</script>
</body>

</html>

  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
 

  //upload image function
  var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
  };

$(document).ready(function(){
     $.ajax({
     url: "/admin/materials/list", 
     dataType: "json", 
     success: function(data)
      {
        $('#select_category').empty();
        $('#select_category').append("<option value='' selected>Please wait..</option>");
        $('#select_category').empty();
        $('#select_category').append("<option value='' selected>Select Category</option>");
        $.each(data, function(key, value) {
        $('#select_category').append("<option value='" + value.id + "'>" + value.material_name + "</option>");
        });
      }   
    });  

//request - city radio button click event
$('#address_city').click(function () { 
 $('#address_province').prop('checked', false); 
     var cities    = $(this).val();
     $.ajax({
     url: "/admin/requests/cities/" + cities, 
     dataType: "json", 
     success: function(data)
      {
        $('#request_address').empty();
        $('#request_address').append("<option value='' selected>Please wait..</option>");
        $('#request_address').empty();
        $('#request_address').append("<option value='' selected>Select City</option>");
        $.each(data, function(key, value) {
        $('#request_address').append("<option value='" + value.city_name + "'>" + value.city_name + "</option>");
        });
      }   
    });  
  });

//request - province radio button click event
$('#address_province').click(function () { 
    $('#address_city').prop('checked', false); 
     var provinces    = $(this).val();
     $.ajax({
     url: "/admin/requests/provinces/" + provinces, 
     dataType: "json", 
     success: function(data)
      {
        $('#request_address').empty();
        $('#request_address').append("<option value='' selected>Please wait..</option>");
        $('#request_address').empty();
        $('#request_address').append("<option value='' selected>Select Province</option>");
        $.each(data, function(key, value) {
        $('#request_address').append("<option value='" + value.province_name + "'>" + value.province_name + "</option>");
        });
      }   
    });  
  });

//request - organization address
$('#txt_request_organization').change(function () { 
     var organization  = $('#txt_request_organization').val();
     $.ajax({
     url: "/admin/organizations/address/"+ $('#txt_request_organization').val(),
     dataType: "json", 
     success: function(data)
      {
        $('#txt_request_address').empty();
        $.each(data, function(key, value) {
        $('#txt_request_address').val(value.organization_address);
        });
      }   
    });  
  });

//client - organization address
// $('#txt_clients_org').change(function () { 
//      var organization  = $('#txt_clients_org').val();
//      $.ajax({
//      url: "/admin/organizations/address/"+ $('#txt_clients_org').val(),
//      dataType: "json", 
//      success: function(data)
//       {
//         $('#txt_clients_addr').empty();
//         $.each(data, function(key, value) {
//         $('#txt_clients_addr').val(value.organization_address);
//         });
//       }   
//     });  
//   });


//check stock quantity
$('#txt_material_onhand').change(function () { 
    var onhand = $('#txt_key_material_onhand').val();
    var material = $('#txt_key_iec_name').val();
    var materialID = $('#txt_key_material_id').val();
    if(onhand == 0){
      alert( material + ' is out of stock.');
      $('#txt_material_quantity').prop('disabled', true);
    } else {
      $('#txt_material_quantity').prop('disabled', false);
    }
});
//add new item on list of request table
/////
$('#btn_addItem').click(function () { 
  var material = '';
  var materialID = '';
  var quantity = 0;
  var onhand = $('#txt_material_onhand').val(); 
  var stockrequest = $('#txt_material_quantity').val();
  if(parseInt(onhand) < parseInt(stockrequest)){
    $('#lbl-error-message2').empty();
    $('#div-success-notification2').css('display', 'none');
    $('#lbl-error-message2').append('Insufficient stock.');
    $('#div-error-notification2').css('display', 'block');
    setTimeout(function () {
            $('#div-error-notification2').css('display', 'none');
            $('#label-error-message2').empty();
        },10000); 
  }  else {
  $('#modalBtnSave').prop('disabled', false);  
  if($('#txt_key_material_name').val() == ''){
    $('#lbl-error-message2').empty();
    $('#div-success-notification2').css('display', 'none');
    $('#lbl-error-message2').append('Error. Please fill-in the required field/s.');
    $('#div-error-notification2').css('display', 'block');
    setTimeout(function () {
            $('#div-error-notification2').css('display', 'none');
            $('#label-error-message2').empty();
        },10000); 
  } else {
    var material = $('#txt_key_iec_name').val();
    var materialID = $('#txt_material_id').val();
  }
    if($('#txt_material_quantity').val() == 0){
       $('#lbl-error-message2').empty();
       $('#div-success-notification2').css('display', 'none');
       $('#lbl-error-message2').append('Error. Please fill-in the required field/s.');
       $('#div-error-notification2').css('display', 'block');
       setTimeout(function () {
               $('#div-error-notification2').css('display', 'none');
               $('#label-error-message2').empty();
           },10000); 
    } else {
      var quantity = $('#txt_material_quantity').val();
    }

if ( $('#tblSelectedRequest td:contains(' + material + ')').length ) {
    //exists
    $('#lbl-error-message2').empty();
    $('#div-success-notification2').css('display', 'none');
    $('#lbl-error-message2').append('Already in the list.');
    $('#div-error-notification2').css('display', 'block');
    setTimeout(function () {
            $('#div-error-notification2').css('display', 'none');
            $('#label-error-message2').empty();
        },10000); 
} else {
    if($('#txt_key_iec_name').val() != '' &&  $('#txt_material_quantity').val() != 0) {
      var itemRemaining = onhand - stockrequest;
    $('#tblSummaryList').append('<tr><td><input type="hidden" name="txt_selectedMaterial[]" value="' + materialID + '"><input type="hidden" name="txt_selectedMaterialName[]" value="' + material + '"><input type="hidden" name="txt_selectedQuantity[]" value="' + quantity + '">' + '<input type="hidden" name="txt_totalRemaining[]" value="' + itemRemaining + '">' +  material + '</td><td>' + quantity + '</td><td><a href="#" class="btn_removeItem"><i class="fa fa-remove"> </i> <b>Remove</b></a><script type="text/javascript">$(".btn_removeItem").click(function () { $(this).closest("tr").remove(); });</script> </td></tr>');
    
    $('#itemCount').val(parseInt($('#itemCount').val()) + 1);
    if(onhand <= 50){
      $('#lbl-warning-message2').empty();
      $('#div-success-notification2').css('display', 'none');
      $('#div-error-notification2').css('display', 'none');
      $('#lbl-warning-message2').append('Stock onhand of ' + material + ' is in critical level.');
      $('#div-warning-notification2').css('display', 'block');
      setTimeout(function () {
              $('#div-warning-notification2').css('display', 'none');
              $('#label-warning-message2').empty();
          },10000); 
    }
    if(onhand == 0){
      $('#lbl-error-message2').empty();
      $('#div-success-notification2').css('display', 'none');
      $('#lbl-error-message2').append(material + ' is out of stock.');
      $('#div-error-notification2').css('display', 'block');
      setTimeout(function () {
              $('#div-error-notification2').css('display', 'none');
              $('#label-error-message2').empty();
          },10000); 
      $('#modalBtnSave').prop('disabled', true);
    }

    $('#txt_material_id').val('');
    $('#txt_material_onhand').val('');
    $('#txt_material_quantity').val('');
    $('#txt_key_iec_name').val('');
    }
  }
} //end if onhand and stock request
});

//material description empty textarea for request
$('#txt_material_desc').click(function () { 
  // $('#txt_material_desc').empty();
});

$('#test').click(function () { 
  $('#itemCount').val( parseInt($('#itemCount').val()) - 1 );
});
//find material - from add new material in request
$('#modalBtnFind').click(function () { 
     var material_name  = $('#txt_material_name').val();
     var material_desc  = $('#txt_material_desc').val();
     var material_stock  = $('#txt_material_stock').val();
     var materials = material_name + "_" + material_desc + "_" + material_stock;
     if(material_name == '' || material_desc == '' || material_stock == 0) {
      alert('Please fill in the required field.');
     }
     if(material_name != '' && material_desc != '' && material_stock > 0) {
     $.ajax({
     url: "/admin/materials/find/"+ materials,
     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
     dataType: "json", 
     success: function(data)
      {
        if(data == 0){ 
            $('#modalBtnSave').click();    
            } else {
            alert('Record already exist.');             
          }
      }   
    });  
    } // end if
  });

//add new material - save new material in request
$('#modalBtnSave').click(function () { 
     var material_name  = $('#txt_material_name').val();
     var material_desc  = $('#txt_material_desc').val();
     var material_stock  = $('#txt_material_stock').val();
     var materials = material_name + "_" + material_desc + "_" + material_stock;
     if(material_name != '' && material_desc != '' && material_stock > 0) {
     $.ajax({
     url: "/admin/materials/insert/"+ materials,
     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
     dataType: "json", 
     success: function(data)
      {
        alert('Record successfully added in the database.');
      }   
    });  
    } // end if
  });

//request - auto complete material name
$('#txt_key_iec_name').keyup(function () { 
     var keyword  = $('#txt_key_iec_name').val();
     if(keyword != ''){
     $.ajax({
     url: "/admin/iec/autocomplete/"+ keyword,
     dataType: "json", 
     success: function(data)
      {
        $('#result_iec_list').empty();
        $('#result_iec_list').append('<option value="">Select</option>');
        $.each(data, function(key, value) {
        $('#result_iec_list').append("<option value='" + value.iec_title + "'>" + value.iec_title + "</option>");
        });
      }   
    });  
   } //end if
  });

//request - populate iec material list lookup $.ajax({ url:

// var $pagination = $('#pagination'),
// totalRecords = 0,
// records = [],
// displayRecords = [],
// recPerPage = 10,
// page = 1,
// totalPages = 0;
// $.ajax({
//       url: "http://dummy.restapiexample.com/api/v1/employees",
//       async: true,
//       dataType: 'json',
//       success: function (data) {
//                   records = data;
//                   totalRecords = records.length;
//                   totalPages = Math.ceil(totalRecords / recPerPage);
//                   //apply_pagination();
//       }
// });

// $.ajax({
// url: "/admin/iec/look-up/", 
// dataType: "json",  
// success: function(data) {
//   $('#display-iec-lookup').empty(); 
//   $('#display-iec-lookup').append('<option value=""></option>');
//   $('#display-iec-lookup').append('');
//   $.each(data, function(key, value) {
//   var imgs = value.iec_image;
//   if(imgs == null){
//     var display_image = "/images/Image-Not-Available-Icon.jpg";
//   } else {
//     var display_image = imgs;
//   }
//   $('#display-iec-lookup').append('<tr><td><image src="' +display_image + '" height="30px width=30px;"></td><td>' +
// value.iec_title + '</td><td>' + value.iec_threshold +
// '</td><td><button class="btn btn-success" id="addToList' + value.id + '" value="' + value.iec_title + '" title="add to list"><i class="fa fa-check"></i></button> <script type="text/javascript">$(document).ready(function () {  $("#addToList' + value.id + '").click(function () { $("#txt_key_iec_name").val($("#addToList'+ value.id + '").val()); $("#txt_material_id").val(' + value.id + '); $("#txt_key_iec_name").click(); $(".btn-secondary").click(); }); }); </script> </td> </tr>');
//                     records = data;
//                   
//                   totalRecords = records.length;
//                   totalPages = Math.ceil(totalRecords / recPerPage);
//     }); 
//   }    
// });  

////request - populate iec material list lookup back to default (display all list)
// $('#display-iec-lookup').click(function (){ 
// $.ajax({
// url: "/admin/iec/look-up/", 
// dataType: "json",  
// success: function(data) {
//   $('#display-iec-lookup').empty(); 
//   $('#display-iec-lookup').append('<option value=""></option>');
//   $('#display-iec-lookup').append('');
//   $.each(data, function(key, value) {
//   var imgs = value.iec_image;
//   if(imgs == null){
//     var display_image = "/images/Image-Not-Available-Icon.jpg";
//   } else {
//     var display_image = imgs;
//   }
// $('#display-iec-lookup').append('<tr><td><image src="' +display_image + '" height="30px width=30px;"></td><td>' +
// value.iec_title + '</td><td>' + value.iec_threshold +
// '</td><td><button class="btn btn-success" id="addToList' + value.id + '" value="' + value.iec_title + '" title="add to list"><i class="fa fa-check"></i></button> <script type="text/javascript">$(document).ready(function () {  $("#addToList' + value.id + '").click(function () { $("#txt_key_iec_name").val($("#addToList'+ value.id + '").val()); $("#txt_material_id").val(' + value.id + '); $("#txt_key_iec_name").click(); $(".btn-secondary").click(); }); }); </script> </td> </tr>');
//       }); 
//     }    
//   });  
// });

//IEC materials lookup - random search 
// $('#lookup_key_iec_name').keyup(function (){ 
// if($('#lookup_key_iec_name').val() == '') {
//    $('#display-iec-lookup').click();
// }
// if($('#lookup_key_iec_name').val() != ''){
//   var iec_name = $('#lookup_key_iec_name').val();

// $.ajax({
// url: "/admin/iec/random-search/" + iec_name, 
// dataType: "json",  
// success: function(data) {
//   $('#display-iec-lookup').empty(); 
//   $('#display-iec-lookup').append(''); 
//   $.each(data, function(key, value) {
//   if(data == 0){
//   $('#display-iec-lookup').empty(); 
//   $('#display-iec-lookup').append('<h4>No result found.</h4>');    
//   }
//   if(data != 0){
//   var imgs = value.iec_image;
//   if(imgs == null){
//     var display_image = "/images/Image-Not-Available-Icon.jpg";
//   } else {
//     var display_image = imgs;
//   }
// $('#display-iec-lookup').append('<tr><td><image src="' +display_image + '" height="30px width=30px;"></td><td>' +
// value.iec_title + '</td><td>' + value.iec_threshold +
// '</td><td><button class="btn btn-success" id="addToList' + value.id + '" value="' + value.iec_title + '" title="add to list"><i class="fa fa-check"></i></button> <script type="text/javascript">$(document).ready(function () {  $("#addToList' + value.id + '").click(function () { $("#txt_key_iec_name").val($("#addToList'+ value.id + '").val()); $("#txt_material_id").val(' + value.id + '); $("#txt_key_iec_name").click(); $(".btn-secondary").click(); }); }); </script> </td> </tr>');
//   }
//     // else {

//     //     }    
//       });
//     }
//   });
// }// endif
// });
//request - populate organzation list lookup
      $.ajax({
     url: "/admin/organization/look-up/",
     dataType: "json", 
     success: function(data)
      {
        //old
        $('#txt_clients_org2').empty();
        $('#txt_clients_org2').append('<option value="">Select Type</option>');
        $.each(data, function(key, value) {
        $('#txt_clients_org2').append("<option value='" + value.organization_name + "'>");
        });
      }   
    });  
 
 
//find available stock - request
$('#txt_key_iec_name').change(function (){ 
     var iec  = $('#txt_key_iec_name').val();
     if(iec != ''){
     $.ajax({
     url: "/admin/iec/available-stocks/"+ iec,
     dataType: "json", 
     success: function(data)
      {
        $('#txt_material_onhand').empty();
        $.each(data, function(key, value) {
        $('#txt_material_onhand').val(value.iec_threshold);
        $('#txt_material_id').val(value.id);
        });
      }   
    });  
   } //end if
});

//find available stock - request
$('#txt_key_iec_name').click(function () { 
     var iec  = $('#txt_key_iec_name').val();
     if(iec != ''){
     $.ajax({
     url: "/admin/iec/available-stocks/"+ iec,
     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
     dataType: "json", 
     success: function(data)
      {
        $('#txt_material_onhand').empty();
        $.each(data, function(key, value) {
        $('#txt_material_onhand').val(value.iec_threshold);
        });
      }   
    });  
   } //end if
});

//validate clients info (textboxes)
$('#txt_clients_name').keyup(function () {
if($('#txt_clients_name').val() != ''){
    $('#txt_clients_name').css('border-color', '');
} else {
    $('#txt_clients_name').css('border-color', "green");
    $('#txt_clients_name').css('borderWidth', '1px');
        setTimeout(function () {
            $('#txt_clients_name').css('border-color', '');
            $('#txt_clients_name').css('borderWidth', '');
        },5000);  
  }
});

// $('#txt_clients_contact_no').change(function () {
// if($('#txt_clients_org').val() != ''){
//     $('#txt_clients_org').css('border-color', '');
// } else {
//   $('#txt_clients_org').css('border-color', "green");
//     $('#txt_clients_org').css('borderWidth', '1px');
//         setTimeout(function () {
//             $('#txt_clients_org').css('border-color', '');
//             $('#txt_clients_org').css('borderWidth', '');
//         },5000);  
//   }
// });

$('#txt_clients_designation').keyup(function () {
if($('#txt_clients_designation').val() != ''){
  $('#txt_clients_designation').css('border-color', '');
} else {
  $('#txt_clients_designation').css('border-color', "green");
    $('#txt_clients_designation').css('borderWidth', '1px');
        setTimeout(function () {
            $('#txt_clients_designation').css('border-color', '');
            $('#txt_clients_designation').css('borderWidth', '');
        },5000);  
  }
});

// $('#txt_clients_contact_no').keyup(function () {

// if($('#txt_clients_contact_no').val() != ''){
//     $('#txt_clients_contact_no').css('border-color', '');
// } else {
//     $('#txt_clients_contact_no').css('border-color', "green");
//     $('#txt_clients_contact_no').css('borderWidth', '1px');
//         setTimeout(function () {
//             $('#txt_clients_contact_no').css('border-color', '');
//             $('#txt_clients_contact_no').css('borderWidth', '');
//         },5000);  
//   }
// });
// --- end validate clients info (textboxes)

//validate clients info
$('#validateClientInfo').click(function () { 
    var clients_name = $('#txt_clients_name').val();
    var clients_org  = $('#txt_clients_org').val();
    var clients_designation = $('#txt_clients_designation').val();
    var clients_contact_no = $('#txt_clients_contact_no').val();
    var client_info = clients_name + "_" + clients_org + "_" + clients_designation + "_" + clients_contact_no;

if(clients_name == '' || clients_org == '' || txt_clients_designation ==''){
    $('#lbl-error-message').empty();
    $('#div-success-notification').css('display', 'none');
    $('#lbl-error-message').append('Error. Please fill-in the required field/s.');
    $('#div-error-notification').css('display', 'block');
    setTimeout(function () {
            $('#div-error-notification').css('display', 'none');
            $('#label-error-message').empty();
        },10000);  

if($('#txt_clients_name').val() != ''){
    $('#txt_clients_name').css('border-color', '');
} else {
    $('#txt_clients_name').css('border-color', "red");
    $('#txt_clients_name').css('borderWidth', '1px');
        setTimeout(function () {
            $('#txt_clients_name').css('border-color', '');
            $('#txt_clients_name').css('borderWidth', '');
        },5000);  
  }

if($('#txt_clients_org').val() != ''){
  $('#txt_clients_org').css('border-color', '');
} else {
    $('#txt_clients_org').css('border-color', "red");
    $('#txt_clients_org').css('borderWidth', '1px');
        setTimeout(function () {
            $('#txt_clients_org').css('border-color', '');
            $('#txt_clients_org').css('borderWidth', '');
        },5000);  
  }

if($('#txt_clients_designation').val() != ''){
    $('#txt_clients_designation').css('border-color', '');
} else {
    $('#txt_clients_designation').css('border-color', "red");
    $('#txt_clients_designation').css('borderWidth', '1px');
    setTimeout(function () {
            $('#txt_clients_designation').css('border-color', '');
            $('#txt_clients_designation').css('borderWidth', '');
        },5000);  
  }

// if($('#txt_clients_contact_no').val() != ''){
//     $('#txt_clients_contact_no').css('border-color', '');
// } else {
//     $('#txt_clients_contact_no').css('border-color', "red");
//     $('#txt_clients_contact_no').css('borderWidth', '1px');
//     setTimeout(function () {
//             $('#txt_clients_contact_no').css('border-color', '');
//         },10000);  
//   }

} else {
    $('#div-error-notification').css('display', 'none');
    $('#label-error-message').empty();
    setTimeout(function () {
            $('#div-error-notification').css('display', 'none');
            $('#lbl-error-message').empty();
        },5000);  

     $.ajax({
     url: "/admin/requests/validate-client-info/"+ client_info,
     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
     dataType: "json", 
     success: function(data)
      {
        if(data == 0){ 
          $('#saveClientInfo').click();
        } else  {
              $('#div-success-notification').css('display', 'none');
              $('#lbl-error-message').empty();
              $('#div-error-notification').css('display', 'block');
              $('#lbl-error-message').append('Record already exists.');
            setTimeout(function () {
              $('#div-notification-notification').empty();
              $('#div-error-notification').css('display', 'none');
              $('#lbl-error-message').empty();
            },10000);          
          }
      }   
    });  
    }
});

//save clients info
$('#saveClientInfo').click(function () { 
  var clients_name = $('#txt_clients_name').val();
  var clients_org  = $('#txt_clients_org').val();
  var clients_designation = $('#txt_clients_designation').val();
  var clients_contact_no = $('#txt_clients_contact_no').val();
  var client_info = clients_name + "_" + clients_org + "_" + clients_designation + "_" + clients_contact_no;
     $.ajax({
     url: "/admin/requests/save-client-info/"+ client_info,
     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
     dataType: "json", 
     success: function(data)
      {
              $('#lbl-error-message').empty();
              $('#div-error-notification').css('display', 'none');
              $('#lbl-success-message').append('Record successfully added in the database.');
              $('#div-success-notification').css('display', 'block');
              $("#btn_client_name_refresh").click();
            setTimeout(function () {
              $('#div-success-notification').css('display', 'none');
              $('#lbl-success-message').empty();
            },5000);  
      }   
    });  

});


//request - clients name dropdown
$('#txt_request_name').change(function () { 
     $.ajax({
     url: "/admin/clients/find-info/"+ $('#txt_request_name').val(),
     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
     dataType: "json", 
     success: function(data)
      {
        if(data.length == 0){
          $('#displayClientResult').empty();
          $('#displayClientResult').css('display', 'block');
          $('#displayClientResult').append('No result found.');
          $('#txt_request_organization').val('');
          $('#txt_request_designation').val('');
          $('#btnSaveRequest').prop('disabled', true);
        } else {
          $('#displayClientResult').empty();
          $('#displayClientResult').css('display', 'none');
          $('#txt_request_organization').val('');
          $('#txt_request_designation').val('');
          $('#btnSaveRequest').prop('disabled', false);
        }

        $.each(data, function(key, value) {
        $('#txt_request_organization').empty();
        $('#txt_request_designation').empty();
        $('#txt_request_organization').val(value.organization_name);
        $('#txt_request_designation').val(value.client_designation);
        });
      } 
    });  
  });


     //request - clients dropdown
     $.ajax({
     url: "/admin/clients/clientrecordlist/",
     dataType: "json", 
     success: function(data)
      {
          $('#txt_request_name2').empty();
          $.each(data, function(key, value) {
          $('#txt_request_name2').append('<option value="' + value.client_name + '">');
        });
      }   
    });  


$('#btn_client_name_refresh').click(function () { 
     $.ajax({
     url: "/admin/clients/clientrecordlist/",
     dataType: "json", 
     success: function(data)
      {
          $('#txt_request_name2').empty(); 
          $.each(data, function(key, value) {
          $('#txt_request_name2').append('<option value="' + value.client_name + '">');
        });
      }   
    }); 
  });

$('#iec_restock').click(function () { 
  $('#iec_restock').attr('checked', true);
  $('#iec_adjust').removeAttr('checked');
  $('#iec_option').val(1);
  $('#divPrintingInfo').css('display', 'block');
  $('#iec_adjustPieces').css('display', 'none');
  $('#iec_adjustPieces').removeAttr('required');
  $('#iec_restockPieces').css('display', 'block');
  $('#iec_restockPieces').attr('required');
  $('#txt_iec_printing_date').attr('required');
  $('#txt_iec_printing_contractor').attr('required');
  $('#txt_iec_printing_cost').attr('required'); 
  $('#divPieces').css('display', 'none');
  $('#divAdditionalPieces').css('display', 'block');
   
});

$('#iec_adjust').click(function () { 
  $('#iec_adjust').attr('checked', true);
  $('#iec_restock').removeAttr('checked');
  $('#iec_option').val(2);
 $('#divPrintingInfo').css('display', 'none');
  $('#iec_adjustPieces').css('display', 'block');
  $('#iec_adjustPieces').attr('required');
  $('#iec_restockPieces').css('display', 'none');
  $('#iec_restockPieces').removeAttr('required');
  $('#txt_iec_printing_date').removeAttr('required');
  $('#txt_iec_printing_contractor').removeAttr('required');
  $('#txt_iec_printing_cost').removeAttr('required');
  $('#divPieces').css('display', 'block');
  $('#divAdditionalPieces').css('display', 'none');
});


$('#lblSave').click(function () { 
    var iec_adjustPieces = parseInt($('#iec_adjustPieces').val());
    var iec_stock = parseInt($('#txt_iec_stock').val());
  if($('#iec_option').val() == ''){
        $('#lbl-error-message').empty();
        $('#div-success-notification').css('display', 'none');
        $('#lbl-error-message').append('Please select option to proceed.');
        $('#div-error-notification').css('display', 'block');
        setTimeout(function () {
            $('#div-error-notification').css('display', 'none');
            $('#label-error-message').empty();
          },10000);  
  }
  // if($('#iec_restockPieces').val() == ''){
  //       $('#lbl-error-message').empty();
  //       $('#div-success-notification').css('display', 'none');
  //       $('#lbl-error-message').append('Please fill in the required field.');
  //       $('#div-error-notification').css('display', 'block');
  //       setTimeout(function () {
  //           $('#div-error-notification').css('display', 'none');
  //           $('#label-error-message').empty();
  //         },10000);  
  // }

//  else if($('#iec_adjustPieces').val() == ''){
  // else if($('#iec_adjustPieces').val() == ''){
  //       $('#lbl-error-message').empty();
  //       $('#div-success-notification').css('display', 'none');
  //       $('#lbl-error-message').append('Please fill in the required field.');
  //       $('#div-error-notification').css('display', 'block');
  //       setTimeout(function () {
  //           $('#div-error-notification').css('display', 'none');
  //           $('#label-error-message').empty();
  //         },10000);  
  // } else {
  else {
  if(iec_adjustPieces > iec_stock){
        // $('#lbl-error-message').empty();
        // $('#div-success-notification').css('display', 'none');
        // $('#lbl-error-message').append('Invalid entry. Pieces entered is greater than the stock onhand.');
        // $('#div-error-notification').css('display', 'block');
        // setTimeout(function () {
        //     $('#div-error-notification').css('display', 'none');
        //     $('#label-error-message').empty();
        //   },10000);  
    var iec_total = iec_stock - iec_adjustPieces;
    $('#btnSave').click();
  } else {
    var iec_total = iec_stock - iec_adjustPieces;
    $('#btnSave').click();
  }
}
});

//request - search request using auto complete
$('#txt_iec_keyword').keyup(function () { 
  $('.input-sm').val($('#txt_iec_keyword').val());
  $('.input-sm').keyup();  
  //    var keyword  = $('#txt_iec_keyword').val();
  // if(keyword == ''){
  //   $('#lbl-error-message').empty();
  //   $('#div-success-notification').css('display', 'none');
  //   //$('#lbl-error-message').append('No result found.');
  //   $('#lbl-error-message').append('Error. Please fill-in the required field/s.');
  //   $('#div-error-notification').css('display', 'block');    
  // } else {
  //   var recid = new Array();
  //   var recid = new Array();
  //   var amonth = new Array();
  //   var aday = new Array();
  //   var ayear = new Array();
  //   var ahours = new Array();
  //   var aminutes = new Array();
  //   var aampm = new Array();
  //    $.ajax({
  //    url: "/admin/requests/autocomplete/"+ keyword,
  //    dataType: "json", 
  //    success: function(data)
  //     {
  //       if(data == 0){ 
  //       $('#lbl-error-message').empty();
  //       $('#div-success-notification').css('display', 'none');
  //       $('#lbl-error-message').append('No result found.');
  //     //  $('#lbl-error-message').append('Error. Please fill-in the required field/s.');
  //       $('#div-error-notification').css('display', 'block');
  //       $('#table').css('display', 'none');
  //       $('#tbodyRequestDisplay').empty();
  //       $('#table_info').empty();
  //       $('#btnPrintAll').css('display', 'none');
  //       setTimeout(function () {
  //           $('#div-error-notification').css('display', 'none');
  //           $('#label-error-message').empty();
  //         },10000);  
  //       $('#displayResultCount').empty();
  //       $('#displayResultCount').append(data.length + " Result/s found.");
  //       } else {
  //         $('#table').css('display', 'block');
  //         $('#tbodyRequestDisplay').empty();
  //         $('#table_info').empty();
  //         $('#btnPrintAll').css('display', 'block');
  //         $.each(data, function(key, value) {
  //           recid.push(value.rec_id);
  //           amonth = new Date(value.created_at).getMonthName();
  //           aday = new Date(value.created_at).getDay();
  //           ayear = new Date(value.created_at).getFullYear();
  //           ahours = new Date(value.created_at).getHours();
  //           aminutes = new Date(value.created_at).getMinutes();
  //         $('#tbodyRequestDisplay').append('<tr><td>' +  amonth + " " + aday + ", " + ayear + " " + ahours + ":" + aminutes + " " + aampm + '</td><td>' + value.client_name + '</td><td>' + value.organization_name +'</td><td>' + value.iec_title + '</td><td>' + value.request_material_quantity + '</td><td>' + value.name + '</td><td><button class="form-control btn btn-success" id="printOne' + value.rec_id + '"><i class="fa fa-print"> </i> Print</button></td></tr> <script type="text/javascript"> $("#printOne' + value.rec_id + '").click(function () {  $("#txtPrintOne").empty(); $("#txtPrintOne").val(' + value.rec_id + '); $("#txtPrintOne").click() });  </script></td></tr>');
  //       });
  //         $('#displayResultCount').empty();
  //         $('#displayResultCount').append(data.length + " Result/s found.");
  //         if(recid.length > 0){
  //           recid = recid.join(",");
  //         }
  //         $('#txt_id_all').val(recid);
  //       }// end if data
  //     }
  // });
  //  }
});

//search requests by date
// $('#btn_iec_find').click(function () { 
//   var dateFrom = $('#txt_iec_date_from').val();
//   var dateTo = $('#txt_iec_date_to').val(); 
//   if(dateFrom == '' || dateTo == ''){
//     $('#lbl-error-message').empty();
//     $('#div-success-notification').css('display', 'none');
//     //$('#lbl-error-message').append('No result found.');
//     $('#lbl-error-message').append('Error. Please fill-in the required field/s.');
//     $('#div-error-notification').css('display', 'block');  
//     $('#btnPrintAll').css('display', 'none');  
//   } else {
//   var idate = dateFrom + "_" + dateTo;
//   var recid = new Array();
//   var amonth = new Array();
//   var aday = new Array();
//   var ayear = new Array();
//   var ahours = new Array();
//   var aminutes = new Array();
//   var aampm = new Array();
//        $.ajax({
//      url: "/admin/requests/find-by-date/" + idate,
//      dataType: "json", 
//      success: function(data)
//       {
//         if(data == 0){ 
//         $('#lbl-error-message').empty();
//         $('#div-success-notification').css('display', 'none');
//         $('#lbl-error-message').append('No result found.');
//       //  $('#lbl-error-message').append('Error. Please fill-in the required field/s.');
//         $('#div-error-notification').css('display', 'block');
//         $('#table').css('display', 'none');
//         $('#tbodyRequestDisplay').empty();
//         $('#table_info').empty();
//         $('#displayResultCount').empty();
//         $('#displayResultCount').append(data.length + " Result/s found.");
//         setTimeout(function () {
//             $('#div-error-notification').css('display', 'none');
//             $('#label-error-message').empty();
//           },10000);  
//         } else {
//           $('#table').css('display', 'block');
//           $('#tbodyRequestDisplay').empty();
//           $('#table_info').empty();
//           $('#btnPrintAll').css('display', 'block');
//           $.each(data, function(key, value) {
//             recid.push(value.rec_id);
//             amonth = new Date(value.created_at).getMonthName();
//             aday = new Date(value.created_at).getDay();
//             ayear = new Date(value.created_at).getFullYear();
//             ahours = new Date(value.created_at).getHours();
//             aminutes = new Date(value.created_at).getMinutes();
//             aampm = ahours >= 12 ? 'PM' : 'AM';
//           $('#tbodyRequestDisplay').append('<tr><td>' +  amonth + " " + aday + ", " + ayear + " " + ahours + ":" + aminutes + " " + aampm + '</td><td>' + value.client_name + '</td><td>' + value.organization_name +'</td><td>' + value.iec_title + '</td><td>' + value.request_material_quantity + '</td><td>' + value.name + '</td><td><button class="form-control btn btn-success" id="printOne' + value.rec_id + '"><i class="fa fa-print"> </i> Print</button></td></tr> <script type="text/javascript"> $("#printOne' + value.rec_id + '").click(function () {  $("#txtPrintOne").empty(); $("#txtPrintOne").val(' + value.rec_id + '); $("#txtPrintOne").click() });  </script></td></tr>');
//         });
//           $('#displayResultCount').empty();
//           $('#displayResultCount').append(data.length + " Result/s found.");
//           if(recid.length > 0){
//             recid = recid.join(",");
//           }
//           $('#txt_id_all').val(recid);
//         }// end if data
//       }
//     }); 
//    }
//});

$('#btnPrintAll').click(function (){ 
  var id_all = $('#txt_id_all').val();
     $.ajax({
     url: "/admin/requests/print-all/" + id_all,
     dataType: "json", 
     success: function(data)
      {
        $('#print_divbox').empty();
        $('#print_tbody').empty();
         $('#print_divbox').append('<table class="table table bordered"> <thead> <tr> <th>Date/Time of Request</th> <th>Name of Client</th>  <th>Organization</th>    <th>Requested Material</th> <th>Pcs</th>  <th>Staff in Charge</th>   </tr> </thead><tbody>');
          $.each(data, function(key, value) {

            $('#print_divbox').append('<tr><td>' +  value.created_at + '</td><td>' + value.client_name + '</td><td>' + value.client_organization +'</td><td>' + value.iec_title + '</td><td>' + value.request_material_quantity + '</td><td>' + value.name + '</td></tr>');
        });
            $('#print_divbox').append('</tbody></table>');
    } 
  });
            var redirectWindow = window.open('/admin/requests/print-all/' + id_all, '_blank');
            redirectWindow.location;
});


// print preview function & layout
  $('#btnPrintPreview').click(function() {
  Clickheretoprint();
  });

  // print preview function & layout
  $('#btnPrintingLogsPreview').click(function() {
  ClickheretoprintingLogs();
  });

//inventory History Preview
  $('#btnInventoryLogsPreview').click(function() {
  ClickheretoinventoryLogs();
  });

//IEC Materials inventory report Preview
  $('#btnInventoryIECLogsPreview').click(function() {
  ClickheretoinventoryIECLogs();
  });

//IEC Materials Preview
  $('#btnIecsPreview').click(function() {
  ClickheretoIecs();
  });

//Clients Preview
  $('#btnClientsPreview').click(function() {
  ClickheretoClients();
  });
  
//Organizations Preview
  $('#btnOrganizationsPreview').click(function() {
  ClickheretoOrganizations();
  });

//Contractors Preview
  $('#btnContractorsPreview').click(function() {
  ClickheretoContractors();
  });

//Audit Logs Preview
  $('#btnAuditLogsPreview').click(function() {
  ClickheretoAuditLogs();
  });

//IEC Material Acknowledgement Receipt Preview
  $('#btnIecMaterialsPreview').click(function() {
  ClickheretoIecMaterialsReceipt();
  });


  $('#txtPrintOne').click(function() {
    var id_all = $('#txtPrintOne').val();
     $.ajax({
     url: "/admin/requests/print-one/" + id_all,
     dataType: "json", 
     success: function(data)
      {
        $('#print_divbox').empty();
        $('#print_tbody').empty();
        // $('#print_divbox').append('<table class="table table bordered"> <thead> <tr> <th>Date/Time of Request</th> <th>Name of Client</th>  <th>Organization</th>    <th>Material</th> <th>Pcs</th>  <th>Staff in Charge</th>   </tr> </thead><tbody>');
          $.each(data, function(key, value) {

            // $('#print_divbox').append('<tr><td>' +  value.created_at + '</td><td>' + value.client_name + '</td><td>' + value.client_organization +'</td><td>' + value.iec_title + '</td><td>' + value.request_material_quantity + '</td><td>' + value.name + '</td></tr>');
        });
            // $('#print_divbox').append('</tbody></table>');
    } 
  });
            var redirectWindow = window.open('/admin/requests/print-one/' + id_all, '_blank');
            redirectWindow.location;
  });

  function Clickheretoprint()
  { 
  var today = new Date();
  var ydate = today.getFullYear();
  var mdate = new Date().getMonthName();
  var divToPrint=document.getElementById('print_content');

  var docPrint=window.open('','Print-Window');

  docPrint.document.open();

  docPrint.document.write('<html><body onload="window.print()">');
  docPrint.document.write('<link rel="stylesheet" href="/AdminLTE/bootstrap/css/bootstrap.min.css">');
  docPrint.document.write('<br><div class="box-primary"><center><table class="table table-bordered"><tr style="border:1px solid black;"><td style="width:30%; border:1px solid black !important;"><table class="table table-borderless"><tr style="border:none !important; padding:0px !important;"><td style="width:50%; border:none !important; padding:0px !important;"> <br> <center> <img src="/images/pcw_logo.png" alt="pcw_header" height="80px" width="200px"> </center> </td></tr></table></td><td style="width:70%; border:1px solid black !important;" text-align:center;"> <br><br> <center><h4> <b>Request History Report</b> </h4></center> </td></tr></table></center></div>');   
  docPrint.document.write(divToPrint.innerHTML);
  docPrint.document.write('<br><br><br><br><br><br><br>');
  docPrint.document.write('</body></html>');
  docPrint.document.close();
  }  

  function ClickheretoprintingLogs()
  { 
  var today = new Date();
  var ydate = today.getFullYear();
  var mdate = new Date().getMonthName();
  var divToPrint=document.getElementById('print_content');

  var docPrint=window.open('','Print-Window');

  docPrint.document.open();

  docPrint.document.write('<html><body onload="window.print()">');
  docPrint.document.write('<link rel="stylesheet" href="/AdminLTE/bootstrap/css/bootstrap.min.css">');
  docPrint.document.write('<br><div class="box-primary"><center><table class="table table-bordered"><tr style="border:1px solid black;"><td style="width:30%; border:1px solid black !important;"><table class="table table-borderless"><tr style="border:none !important; padding:0px !important;"><td style="width:50%; border:none !important; padding:0px !important;"> <br> <center> <img src="/images/pcw_logo.png" alt="pcw_header" height="80px" width="200px"> </center> </td></tr></table></td><td style="width:70%; border:1px solid black !important;" text-align:center;"> <br><br> <center><h4> <b>Reprinting History</b> </h4></center> </td></tr></table></center></div>');   
  docPrint.document.write(divToPrint.innerHTML);
  docPrint.document.write('<br><br><br><br><br><br><br>');
  docPrint.document.write('</body></html>');
  docPrint.document.close();
} 

  function ClickheretoinventoryLogs()
  { 
  var today = new Date();
  var ydate = today.getFullYear();
  var mdate = new Date().getMonthName();
  var divToPrint=document.getElementById('print_content');

  var docPrint=window.open('','Print-Window');

  docPrint.document.open();

  docPrint.document.write('<html><body onload="window.print()">');
  docPrint.document.write('<link rel="stylesheet" href="/AdminLTE/bootstrap/css/bootstrap.min.css">');
  docPrint.document.write('<br><div class="box-primary"><center><table class="table table-bordered"><tr style="border:1px solid black;"><td style="width:30%; border:1px solid black !important;"><table class="table table-borderless"><tr style="border:none !important; padding:0px !important;"><td style="width:50%; border:none !important; padding:0px !important;"> <br> <center> <img src="/images/pcw_logo.png" alt="pcw_header" height="80px" width="200px"> </center> </td></tr></table></td><td style="width:70%; border:1px solid black !important;" text-align:center;"> <br><br> <center><h4> <b>Inventory Logs</b> </h4></center> </td></tr></table></center></div>');   
  docPrint.document.write(divToPrint.innerHTML);
  docPrint.document.write('<br><br><br><br><br><br><br>');
  docPrint.document.write('</body></html>');
  docPrint.document.close();
}  

  function ClickheretoinventoryIECLogs()
  { 
  var today = new Date();
  var ydate = today.getFullYear();
  var mdate = new Date().getMonthName();
  var divToPrint=document.getElementById('print_content');

  var docPrint=window.open('','Print-Window');

  docPrint.document.open();

  docPrint.document.write('<html><body onload="window.print()">');
  docPrint.document.write('<link rel="stylesheet" href="/AdminLTE/bootstrap/css/bootstrap.min.css">');
  docPrint.document.write('<br><div class="box-primary"><center><table class="table table-bordered"><tr style="border:1px solid black;"><td style="width:30%; border:1px solid black !important;"><table class="table table-borderless"><tr style="border:none !important; padding:0px !important;"><td style="width:50%; border:none !important; padding:0px !important;"> <br> <center> <img src="/images/pcw_logo.png" alt="pcw_header" height="80px" width="200px"> </center> </td></tr></table></td><td style="width:70%; border:1px solid black !important;" text-align:center;"> <br><br> <center><h4> <b>IEC Materials Inventory Report</b> </h4></center> </td></tr></table></center></div>');   
  docPrint.document.write(divToPrint.innerHTML);
  docPrint.document.write('<br><br><br><br><br><br><br>');
  docPrint.document.write('</body></html>');
  docPrint.document.close();
}  
  function ClickheretoIecs()
  { 
  var today = new Date();
  var ydate = today.getFullYear();
  var mdate = new Date().getMonthName();
  var divToPrint=document.getElementById('print_content');

  var docPrint=window.open('','Print-Window');

  docPrint.document.open();

  docPrint.document.write('<html><body onload="window.print()">');
  docPrint.document.write('<link rel="stylesheet" href="/AdminLTE/bootstrap/css/bootstrap.min.css">');
  docPrint.document.write('<br><div class="box-primary"><center><table class="table table-bordered"><tr style="border:1px solid black;"><td style="width:30%; border:1px solid black !important;"><table class="table table-borderless"><tr style="border:none !important; padding:0px !important;"><td style="width:50%; border:none !important; padding:0px !important;"> <br> <center> <img src="/images/pcw_logo.png" alt="pcw_header" height="80px" width="200px"> </center> </td></tr></table></td><td style="width:70%; border:1px solid black !important;" text-align:center;"> <br><br> <center><h4> <b>List of Available IEC Materials</b> </h4></center> </td></tr></table></center></div>');   
  docPrint.document.write(divToPrint.innerHTML);
  docPrint.document.write('<br><br><br><br><br><br><br>');
  docPrint.document.write('</body></html>');
  docPrint.document.close();
} 

  function ClickheretoClients()
  { 
  var today = new Date();
  var ydate = today.getFullYear();
  var mdate = new Date().getMonthName();
  var divToPrint=document.getElementById('print_content');

  var docPrint=window.open('','Print-Window');

  docPrint.document.open();

  docPrint.document.write('<html><body onload="window.print()">');
  docPrint.document.write('<link rel="stylesheet" href="/AdminLTE/bootstrap/css/bootstrap.min.css">');
  docPrint.document.write('<br><div class="box-primary"><center><table class="table table-bordered"><tr style="border:1px solid black;"><td style="width:30%; border:1px solid black !important;"><table class="table table-borderless"><tr style="border:none !important; padding:0px !important;"><td style="width:50%; border:none !important; padding:0px !important;"> <br> <center> <img src="/images/pcw_logo.png" alt="pcw_header" height="80px" width="200px"> </center> </td></tr></table></td><td style="width:70%; border:1px solid black !important;" text-align:center;"> <br><br> <center><h4> <b>Clients List</b> </h4></center> </td></tr></table></center></div>');   
  docPrint.document.write(divToPrint.innerHTML);
  docPrint.document.write('<br><br><br><br><br><br><br>');
  docPrint.document.write('</body></html>');
  docPrint.document.close();
} 

  function ClickheretoOrganizations()
  { 
  var today = new Date();
  var ydate = today.getFullYear();
  var mdate = new Date().getMonthName();
  var divToPrint=document.getElementById('print_content');

  var docPrint=window.open('','Print-Window');

  docPrint.document.open();

  docPrint.document.write('<html><body onload="window.print()">');
  docPrint.document.write('<link rel="stylesheet" href="/AdminLTE/bootstrap/css/bootstrap.min.css">');
  docPrint.document.write('<br><div class="box-primary"><center><table class="table table-bordered"><tr style="border:1px solid black;"><td style="width:30%; border:1px solid black !important;"><table class="table table-borderless"><tr style="border:none !important; padding:0px !important;"><td style="width:50%; border:none !important; padding:0px !important;"> <br> <center> <img src="/images/pcw_logo.png" alt="pcw_header" height="80px" width="200px"> </center> </td></tr></table></td><td style="width:70%; border:1px solid black !important;" text-align:center;"> <br><br> <center><h4> <b>Organizations List</b> </h4></center> </td></tr></table></center></div>');   
  docPrint.document.write(divToPrint.innerHTML);
  docPrint.document.write('<br><br><br><br><br><br><br>');
  docPrint.document.write('</body></html>');
  docPrint.document.close();
} 

  function ClickheretoContractors()
  { 
  var today = new Date();
  var ydate = today.getFullYear();
  var mdate = new Date().getMonthName();
  var divToPrint=document.getElementById('print_content');

  var docPrint=window.open('','Print-Window');

  docPrint.document.open();

  docPrint.document.write('<html><body onload="window.print()">');
  docPrint.document.write('<link rel="stylesheet" href="/AdminLTE/bootstrap/css/bootstrap.min.css">');
  docPrint.document.write('<br><div class="box-primary"><center><table class="table table-bordered"><tr style="border:1px solid black;"><td style="width:30%; border:1px solid black !important;"><table class="table table-borderless"><tr style="border:none !important; padding:0px !important;"><td style="width:50%; border:none !important; padding:0px !important;"> <br> <center> <img src="/images/pcw_logo.png" alt="pcw_header" height="80px" width="200px"> </center> </td></tr></table></td><td style="width:70%; border:1px solid black !important;" text-align:center;"> <br><br> <center><h4> <b>Contractors List</b> </h4></center> </td></tr></table></center></div>');   
  docPrint.document.write(divToPrint.innerHTML);
  docPrint.document.write('<br><br><br><br><br><br><br>');
  docPrint.document.write('</body></html>');
  docPrint.document.close();
} 

  function ClickheretoAuditLogs()
  { 
  var today = new Date();
  var ydate = today.getFullYear();
  var mdate = new Date().getMonthName();
  var divToPrint=document.getElementById('print_content');

  var docPrint=window.open('','Print-Window');

  docPrint.document.open();

  docPrint.document.write('<html><body onload="window.print()">');
  docPrint.document.write('<link rel="stylesheet" href="/AdminLTE/bootstrap/css/bootstrap.min.css">');
  docPrint.document.write('<br><div class="box-primary"><center><table class="table table-bordered"><tr style="border:1px solid black;"><td style="width:30%; border:1px solid black !important;"><table class="table table-borderless"><tr style="border:none !important; padding:0px !important;"><td style="width:50%; border:none !important; padding:0px !important;"> <br> <center> <img src="/images/pcw_logo.png" alt="pcw_header" height="80px" width="200px"> </center> </td></tr></table></td><td style="width:70%; border:1px solid black !important;" text-align:center;"> <br><br> <center><h4> <b>Audit Logs</b> </h4></center> </td></tr></table></center></div>');   
  docPrint.document.write(divToPrint.innerHTML);
  docPrint.document.write('<br><br><br><br><br><br><br>');
  docPrint.document.write('</body></html>');
  docPrint.document.close();
} 

  function ClickheretoIecMaterialsReceipt()
  { 
  var today = new Date();
  var ydate = today.getFullYear();
  var mdate = new Date().getMonthName();
  var divToPrint=document.getElementById('print_content');

  var docPrint=window.open('','Print-Window');

  docPrint.document.open();

  docPrint.document.write('<html><body onload="window.print()">');
  docPrint.document.write('<link rel="stylesheet" href="/AdminLTE/bootstrap/css/bootstrap.min.css">');
  docPrint.document.write('<br><div class="box-primary"><center><table class="table table-bordered"><tr style="border:1px solid black;"><td style="width:30%; border:1px solid black !important;"><table class="table table-borderless"><tr style="border:none !important; padding:0px !important;"><td style="width:50%; border:none !important; padding:0px !important;"> <br> <center> <img src="/images/pcw_logo.png" alt="pcw_header" height="80px" width="200px"> </center> </td></tr></table></td><td style="width:70%; border:1px solid black !important;" text-align:center;"> <br><br> <center><h4> <b>PCW-Publication Inventory Management System - IEC Material Request Acknowledgement Receipt</b> </h4></center> </td></tr></table></center></div>');   
  docPrint.document.write(divToPrint.innerHTML);
  docPrint.document.write('<br><br><br><br><br><br><br>');
  docPrint.document.write('</body></html>');
  docPrint.document.close();
} 

  Date.prototype.getMonthName = function() {
    var monthNames = [ "January", "February", "March", "April", "May", "June", 
                       "July", "August", "September", "October", "November", "December" ];
                       
    return monthNames[this.getMonth()];
}



  //print all button
  $('#btnPrintPreview').click();
  
  //print all button on Printing Logs
  $('#btnPrintingLogsPreview').click();
  $('#btnInventoryLogsPreview').click();
  // $('#btnInventoryIECLogsPreview').click();

  $('#btn_client_name_refresh').click(function () { 
     $.ajax({
     url: "/admin/clients/clientlist/",
     dataType: "json", 
     success: function(data)
      {
          $('#txt_request_name').empty();
          $('#txt_request_name').append('<option value="" selected hidden>Select</option>');
          $.each(data, function(key, value) {
          $('#txt_request_name').append('<option value="' + value.id + '">' + value.client_name + '</option>');
        });
      }   
    }); 
  });
//print request history select all and print
    $('#btn_aprint_all').click(function () {
    if($('input:checkbox').filter(':checked').length < 1){
      $('#lbl-error-message').empty();
      $('#div-success-notification').css('display', 'none');
      $('#lbl-error-message').append('Please check atleast one or more records to print.');
      $('#div-error-notification').css('display', 'block');
      setTimeout(function () {
          $('#div-error-notification').css('display', 'none');
          $('#label-error-message').empty();
        },10000);     
    } else {
      $('#btn_print_all').click();
    }
  });
//end ---- print request history select all and print
});

//print request history select all and print
    function selectAll(){
      if(document.getElementById('approveAll').checked){
        var checks = document.getElementsByName('chk_selectOne[]');
        for(var i=0; i < checks.length; i++){
          checks[i].checked = true; 
        }
      }else{
        var checks = document.getElementsByName('chk_selectOne[]');
        for(var i=0; i < checks.length; i++){
          checks[i].checked = false; 
        }
      }
 }

$('#btn_aprint_all').click(function () {

  if($('input:checkbox').filter(':checked').length < 1){
    $('#lbl-error-message').empty();
    $('#div-success-notification').css('display', 'none');
    $('#lbl-error-message').append('Please check atleast one or more records to print.');
    $('#div-error-notification').css('display', 'block');
    setTimeout(function () {
        $('#div-error-notification').css('display', 'none');
        $('#label-error-message').empty();
      },10000);     
  } else {
    $('#btn_print_all').click();
  }
});
//end ---- print request history select all and print



//dashboard chart//

    //end most distributed IEC materials

$(document).ready(function(){


$('#select_category1').change(function(){
  if($('#select_category1').val() == 1){ //region
    $.ajax({
     url: "/admin/regions/list/",
     dataType: "json", 
     success: function(data)
      {
   $('#regions_list').css('display', 'block');
   $('#provinces_list').css('display', 'none');
   $('#organizations_list').css('display', 'none');
   $.each(data, function(key, value) {
        $('#regions_list').empty();
        $('#regions_list').append("<option value='' selected>Please wait..</option>");
        $('#regions_list').empty();
        $('#regions_list').append("<option value='' selected>Select Region</option>");
        $('#regions_list').append("<option value='ALL'>ALL</option>");
        $.each(data, function(key, value) {
        $('#regions_list').append("<option value='" + value.region_code + "'>" + value.region_code + "</option>");
        });
       }); //each data
  
        } // success
    });
  } if($('#select_category1').val() == 2){ //Provinces
        $('#regions_list').css('display', 'none');  
        $('#organizations_list').css('display', 'none'); 
        $('#provinces_list').append("<option value='' selected>Please wait..</option>");
        $('#provinces_list').css('display', 'block');

    $.ajax({
     url: "/admin/provinces/list/",
     dataType: "json", 
     success: function(data)
      {
        $.each(data, function(key, value) {
        $('#provinces_list').empty();
        $('#provinces_list').append("<option value='' selected>Please wait..</option>");
        $('#provinces_list').empty();
        $('#provinces_list').append("<option value='' selected>Select</option>");
        $('#provinces_list').append("<option value='ALL'>ALL</option>");
        $.each(data, function(key, value) {
        $('#provinces_list').append("<option value='" + value.id + "'>" + value.province_name + "</option>");
        });
       }); //each data
  
        } // success
    });
  } if($('#select_category1').val() == 3){ //organizations
    $.ajax({
     url: "/admin/organizations/list/",
     dataType: "json", 
     success: function(data)
      {
   $('#organizations_list').css('display', 'block');
   $('#regions_list').css('display', 'none');
   $('#provinces_list').css('display', 'none');
   $.each(data, function(key, value) {
        $('#organizations_list').empty();
        $('#organizations_list').append("<option value='' selected>Please wait..</option>");
        $('#organizations_list').empty();
        $('#organizations_list').append("<option value='' selected>Select</option>");
        $('#organizations_list').append("<option value='ALL'>ALL</option>");
        $.each(data, function(key, value) {
        $('#organizations_list').append("<option value='" + value.organization_name + "'>" + value.organization_name + "</option>");
        });
       }); //each data
  
        } // success
    });
  }
});

$('#organizations_list').change(function(){
  var org_name = $('#organizations_list').val();
  if(org_name == ''){
    $('#lbl-error-message1').empty();
    $('#div-success-notification1').css('display', 'none');
    $('#lbl-error-message1').append('Invalid entry / No result found.');
    $('#div-error-notification1').css('display', 'block');
      setTimeout(function () {
        $('#div-error-notification1').css('display', 'none');
              $('#label-error-message1').empty();
      },5000); 
    $('#iecMaterialsAllOrganization').css('display', 'none');
    $('#iecMaterialsPerOrganization').css('display', 'none');
    $('#iecMaterialsPerProvince').css('display', 'none');
    $('#iecMaterialsPerRegion').css('display', 'none');
    $('#iecMaterialsPerPROHorizontal').css('display', 'none');
  } else {
  //most distributed IEC Materials per organizations list
  var aw = [];
  var qe = [];
     $.ajax({
     url: "/admin/iec/org/" + org_name,
     dataType: "json", 
     success: function(data)
      {
        if(data==0){
            $('#lbl-error-message1').empty();
            $('#div-success-notification1').css('display', 'none');
            $('#lbl-error-message1').append('No result found.');
            $('#div-error-notification1').css('display', 'block');
              setTimeout(function () {
                $('#div-error-notification1').css('display', 'none');
                      $('#label-error-message1').empty();
              },5000); 
            $('#iecMaterialsAllOrganization').css('display', 'none');
            $('#iecMaterialsPerOrganization').css('display', 'none');
            $('#iecMaterialsPerProvince').css('display', 'none');
            $('#iecMaterialsPerRegion').css('display', 'none');
            $('#iecMaterialsPerPROHorizontal').css('display', 'none');
    } else {
      if(org_name == 'ALL'){
       $('#iecMaterialsAllOrganization').css('display', 'block'); 
       $('#iecMaterialsPerOrganization').css('display', 'none');
      } else {
       $('#iecMaterialsAllOrganization').css('display', 'none');
       $('#iecMaterialsPerOrganization').css('display', 'block'); 
      }
       $('#iecMaterialsPerProvince').css('display', 'none');
       $('#iecMaterialsPerRegion').css('display', 'none');
       $('#iecMaterialsPerPROHorizontal').css('display', 'none');
  if(org_name =='ALL'){
      for (var i in data) {
        aw.push(data[i].org);
        qe.push(data[i].total_req);
    }
  } else {
    for (var i in data) {
        aw.push(data[i].material);
        qe.push(data[i].total_req);
    }
  }
//   $.each(data, function(key, value) {
 
//       }); //each data
if(org_name =='ALL'){
var ctx = document.getElementById("iecMaterialsAllOrganization").getContext('2d');
} else {
var ctx = document.getElementById("iecMaterialsPerOrganization").getContext('2d');
}
var myChart = new Chart(ctx, {
type: 'bar',
data: {
labels: aw,
datasets: [{
label: 'Most Distributed IEC Materials Per Organization',
data: qe,
backgroundColor: '#0275D8',
borderColor: '#0275D8',
borderWidth: 1
}]
},
options: {
scales: {
yAxes: [{
ticks: {
beginAtZero: true
}
}]
}
}
});  
        }//endif 
      } // success
    }); // end $.ajax
   } //end if org_name val() == NULL
  }); //end most distributed IEC materials per organizations list

$('#regions_list').change(function(){
  var region_name = $('#regions_list').val();
  //most distributed IEC Materials per organizations list
  var aw = [];
  var qe = [];
     $.ajax({
     url: "/admin/iec/region/" + region_name,
     dataType: "json", 
     success: function(data)
      {
        if(data == 0)
        {
            $('#lbl-error-message1').empty();
            $('#div-success-notification1').css('display', 'none');
            $('#lbl-error-message1').append('No result found.');
            $('#div-error-notification1').css('display', 'block');
              setTimeout(function () {
                $('#div-error-notification1').css('display', 'none');
                      $('#label-error-message1').empty();
              },5000); 
            $('#iecMaterialsAllOrganization').css('display', 'none');
            $('#iecMaterialsPerOrganization').css('display', 'none');
            $('#iecMaterialsPerProvince').css('display', 'none');
            $('#iecMaterialsPerRegion').css('display', 'none');
            $('#iecMaterialsPerPROHorizontal').css('display', 'none');
        } else { 
            $('#iecMaterialsAllOrganization').css('display', 'none');
            $('#iecMaterialsPerOrganization').css('display', 'none');
            $('#iecMaterialsPerProvince').css('display', 'none');
            $('#iecMaterialsPerRegion').css('display', 'block');
            $('#iecMaterialsPerPROHorizontal').css('display', 'none');
    for (var i in data) {
        aw.push(data[i].org_addr);
        qe.push(data[i].total_req);
    }
//   $.each(data, function(key, value) {
 
//       }); //each data.
if($('#regions_list').val() == 'ALL'){
var region_title = 'Most Distributed IEC Materials in All Region';  
} else {
var region_title = 'Most Distributed IEC Materials in Region ' + $('#regions_list').val(); 
}

var ctx = document.getElementById("iecMaterialsPerRegion").getContext('2d');
var myChart = new Chart(ctx, {
type: 'bar',
data: {
labels: aw,
datasets: [{
label: region_title,
data: qe,
backgroundColor: '#0275D8',
borderColor: '#0275D8',
borderWidth: 1
}]
},
options: {
scales: {
yAxes: [{
ticks: {
beginAtZero: true
}
}]
}
}
});  
        } //endif 
      } // success
    }); // end $.ajax
  }); //end most distributed IEC materials per regions list

$('#provinces_list').change(function(){
  var province_name = $('#provinces_list').val();
  //most distributed IEC Materials per province list
  var aw = [];
  var qe = [];
     $.ajax({
     url: "/admin/iec/province/" + province_name,
     dataType: "json", 
     success: function(data)
      {
    if(data == 0) {
      $('#lbl-error-message1').empty();
      $('#div-success-notification1').css('display', 'none');
      $('#lbl-error-message1').append('No result found.');
      $('#div-error-notification1').css('display', 'block');
        setTimeout(function () {
          $('#div-error-notification1').css('display', 'none');
                $('#label-error-message1').empty();
        },5000); 
            $('#iecMaterialsAllOrganization').css('display', 'none');
            $('#iecMaterialsPerOrganization').css('display', 'none');
            $('#iecMaterialsPerProvince').css('display', 'none');
            $('#iecMaterialsPerRegion').css('display', 'none');
            $('#iecMaterialsPerPROHorizontal').css('display', 'none');
    } else {
            $('#iecMaterialsAllOrganization').css('display', 'none');
            $('#iecMaterialsPerOrganization').css('display', 'none');
            $('#iecMaterialsPerProvince').css('display', 'block');
            $('#iecMaterialsPerRegion').css('display', 'none');
            $('#iecMaterialsPerPROHorizontal').css('display', 'none');
    for (var i in data) {
        aw.push(data[i].org_name);
        qe.push(data[i].total_req);
        var prov_name = data[i].org_addr;
    }
//   $.each(data, function(key, value) {
 
//       }); //each data
var ctx = document.getElementById("iecMaterialsPerProvince").getContext('2d');
if(province_name == 'ALL'){
  var prov_title = 'Most Distributed IEC Materials in all Provinces';
  } else {
  var prov_title = 'Most Distributed IEC Materials in the Province of ' + prov_name;
  }
var myChart = new Chart(ctx, {
type: 'bar',
data: {
labels: aw,
datasets: [{
label: prov_title,
data: qe,
backgroundColor: '#0275D8',
borderColor: '#0275D8',
borderWidth: 1
}]
},
options: {
scales: {
yAxes: [{
ticks: {
beginAtZero: true
}
}]
}
}
});  
        }//endif 
      } // success
    }); // end $.ajax
  }); //end most distributed IEC materials per province list

$('#btnNewOrganization').click(function(){
  $('#div-add-new-organization').css('display', 'block');
  $('#btnCancelOrganization').css('display', 'block');
  $('#btnNewOrganization').css('display', 'none');
});

$('#btnCancelOrganization').click(function(){
  $('#div-add-new-organization').css('display', 'none');
  $('#btnCancelOrganization').css('display', 'none');
  $('#btnNewOrganization').css('display', 'block'); 
});

$('#submitNewOrg').click(function(){
var orgName =   $('#txt_organization_name').val();
var orgType =   $('#txt_organization_type').val();
var orgCity =   $('#txt_org_city').val();


  if(orgName == ''){
    $('#txt_organization_name').css('border-color', 'red');
    $('#txt_organization_name').css('borderWidth', '1px');
  } else {
    $('#txt_organization_name').css('border-color', 'green');
    $('#txt_organization_name').css('borderWidth', '1px');
  }
  if(orgType == ''){
    $('#txt_organization_type').css('border-color', 'red');
    $('#txt_organization_type').css('borderWidth', '1px');
  } else {
    $('#txt_organization_type').css('border-color', 'green');
    $('#txt_organization_type').css('borderWidth', '1px');
  }
  if(orgCity == ''){
    $('#txt_org_city').css('border-color', 'red');
    $('#txt_org_city').css('borderWidth', '1px');
  } else {
    $('#txt_org_city').css('border-color', 'green');
    $('#txt_org_city').css('borderWidth', '1px');
  }
if(orgName == '' || orgType == '' || orgCity == ''){
    $('#lbl-error-message').empty();
    $('#div-success-notification').css('display', 'none');
    $('#lbl-error-message').append('Error. Please fill-in the required field/s.');
    $('#div-error-notification').css('display', 'block');
    setTimeout(function () {
            $('#div-error-notification').css('display', 'none');
            $('#label-error-message').empty();
        },10000); 
}
if(orgName != '' && orgType != '' && orgCity != ''){
var var_all =   $('#txt_organization_name').val() + "_" + $('#txt_organization_type').val() + "_" + $('#txt_org_city').val();
       $.ajax({
     url: "/admin/organization/new/store/" + var_all, 
     dataType: "json", 
     success: function(data)
      {
        if(data == 0){
          $('#lbl-success-message').empty();
          $('#div-error-notification').css('display', 'none');
          $('#lbl-success-message').append('Record successfully added.');
          $('#div-success-notification').css('display', 'block');
          $('#div-add-new-organization').css('display', 'none');
          $('#btnCancelOrganization').click();
          setTimeout(function () {
                  $('#div-success-notification').css('display', 'none');
                  $('#label-success-message').empty();
              },10000); 
    
      $.ajax({
     url: "/admin/organization/look-up/",
     dataType: "json", 
     success: function(data)
      {
        $('#txt_clients_org2').empty();
        $.each(data, function(key, value) {
        $('#txt_clients_org2').append("<option value='" + value.organization_name + "'>");
        });
      }   
    }); 
    
        } else {
          $('#lbl-error-message').empty();
          $('#div-success-notification').css('display', 'none');
          $('#lbl-error-message').append('Record already exists.');
          $('#div-error-notification').css('display', 'block');
          setTimeout(function () {
                  $('#div-error-notification').css('display', 'none');
                  $('#label-error-message').empty();
              },10000);           
        }
      }   
    });  
}
});

$('#user_rpassword').keyup(function(){
  if($('#user_rpassword').val().length <=5){
   $('#user_rpassword').css('border-color', '#ffc107');
   $('#user_rpassword').css('borderWidth', '1px');
  }
  if($('#user_rpassword').val().length >=6){
   $('#user_rpassword').css('border-color', 'green');
   $('#user_rpassword').css('borderWidth', '1px');
   $('#btnCornfirmPassword').removeAttr('disabled');
  }
});
$('#user_cpassword').keyup(function(){
  if($('#user_cpassword').val().length <=5){
   $('#user_cpassword').css('border-color', '#ffc107');
   $('#user_cpassword').css('borderWidth', '1px');
  }
  if($('#user_cpassword').val().length >=6){
   $('#user_cpassword').css('border-color', 'green');
   $('#user_cpassword').css('borderWidth', '1px');
  }
});
$('#btnCornfirmPassword').click(function(){
  if($('#user_rpassword').val().length <=5){
    $('#lbl-error-message').empty();
    $('#div-success-notification').css('display', 'none');
    $('#lbl-error-message').append('Password must be at least 6 characters.');
    $('#div-error-notification').css('display', 'block');
      setTimeout(function () {
        $('#div-error-notification').css('display', 'none');
              $('#label-error-message').empty();
      },5000);    
    $('#btnCornfirmPassword').prop('disabled', true);
  }
//user accounts
  if($('#user_rpassword').val() == '' || $('#user_cpassword').val() == ''){
    $('#btnUserInfoSubmit').click(); 
  }
  if($('#user_rpassword').val() != $('#user_cpassword').val()){
   $('#user_rpassword').css('border-color', 'red');
   $('#user_rpassword').css('borderWidth', '1px');
   $('#user_cpassword').css('border-color', 'red');
   $('#user_cpassword').css('borderWidth', '1px');
    $('#lbl-error-message').empty();
    $('#div-success-notification').css('display', 'none');
    $('#lbl-error-message').append('Password and confirm password does not matched.');
    $('#div-error-notification').css('display', 'block');
      setTimeout(function () {
        $('#div-error-notification').css('display', 'none');
              $('#label-error-message').empty();
      },5000); 
  } else {
   $('#user_rpassword').css('border-color', 'green');
   $('#user_rpassword').css('borderWidth', '1px');
   $('#user_cpassword').css('border-color', 'green');
   $('#user_cpassword').css('borderWidth', '1px');    
  }
  if($('#user_rpassword').val() == $('#user_cpassword').val() && $('#user_cpassword').val().length >=6){
    $('#btnUserInfoSubmit').click(); 
  }
});
//end user accounts

  $('#txt_view_iec_id').click(function () { 
    var currentThreshold = 0;
    $.ajax({
     url: "/admin/iec/current/threshold/" + $('#txt_view_iec_id').val(),
     dataType: "json", 
     success: function(data)
      {
        $.each(data, function(key, value) {
         currentThreshold  = value.iec_threshold;
         $('#txt_iec_title').val(value.iec_title);
         $('#txt_iec_author').val(value.iec_author);
         $('#txt_iec_publisher').val(value.iec_publisher);
         $('#txt_iec_copyright_date').val(value.iec_copyright_date);
         $('#txt_iec_pages').val(value.iec_page);
         $('#txt_iec_material_type').val(value.material_name);
         $('#txt_iec_threshold').val(value.iec_threshold);
       });
      }   
    }); 

    $('#iec_history_body_display').empty();
    $.ajax({
     url: "/admin/iec/history-look-up/" + $('#txt_view_iec_id').val(),
     dataType: "json", 
     success: function(data)
      {
        $('#iec_history_body_display').empty();
        if(data){
          $('#tblHistory').css('display','block');
          $('#NoRecordDisplay').css('display','none');
        // $('#txt_clients_org').append('<option value="">Select Type</option>');
        $.each(data, function(key, value) {
          var pcs_sign = '';
          if(value.iec_update_type == 1) { 
            var iecUpdateType = 'Restocked'; 
          }  
          if(value.iec_update_type == 2) {
          var iecUpdateType =  'Adjusted'; 
          } 
          if(value.iec_update_type == 3) { 
            var iecUpdateType = 'Update Details';
          } 
          if(value.iec_update_type == 4) { 
            var iecUpdateType = 'Requested';
          } 
          if(value.iec_update_pieces !=''){
            var iecUpdatePcs = value.iec_update_pieces;
          } else if(value.iec_update_pieces == 0){
            var iecUpdatePcs = '';
          }else {
            var iecUpdatePcs = '';
          }
              var cdate = new Date(value.created_at);
              var yy = new Date(value.created_at).getFullYear();
              var mm = new Date(value.created_at).getMonth();
              var dd = new Date(value.created_at).getDay();
              var date = new Date(value.created_at);
              var created_date = date.getMonthName() + ' ' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + ', ' + date.getFullYear() + '<br>' + date.getHours() + ':' + date.getMinutes()+ ':' + date.getSeconds();
             $('#iec_history_body_display').append('<tr><td>' + value.id + '</td><td>' + iecUpdateType + '</td><td>' + value.iec_update_threshold + '</td><td>' + iecUpdatePcs + '</td><td>' + value.iec_current_threshold +'</td><td>' +  created_date  + '</td><td>' +  value.name + '</td><td>' + value.iec_update_remarks+ '</td></tr>');
       });
      } if(data==0) {
          $('#tblHistory').css('display', 'none');
          $('#NoRecordDisplay').css('display','block');
          $('#tblHistory').css('display','none');
      }
    } 
    }); 
 
    $('#iec_printing_logs_body_display').empty();
    $.ajax({
     url: "/admin/iec/printing_logs-look-up/" + $('#txt_view_iec_id').val(),
     dataType: "json", 
     success: function(data)
      {
        $('#iec_printing_logs_body_display').empty();
        if(data){
          $('#tblPrintingLogs').css('display','block');
          $('#NoPrintingRecordDisplay').css('display','none');
        $.each(data, function(key, value) {
              var cdate = new Date(value.created_at);
              var yy = new Date(value.created_at).getFullYear();
              var mm = new Date(value.created_at).getMonth();
              var dd = new Date(value.created_at).getDay();
              var date = new Date(value.created_at);
              var printing_date = new Date(value.iec_printing_date);
              var created_date = date.getMonthName() + ' ' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + ', ' + date.getFullYear() + '<br>' + date.getHours() + ':' + date.getMinutes()+ ':' + date.getSeconds();
              var printing_date = printing_date.getMonthName() + ' ' + ((printing_date.getDate() > 9) ? printing_date.getDate() : ('0' + printing_date.getDate())) + ', ' + printing_date.getFullYear() + '<br>' + printing_date.getHours() + ':' + printing_date.getMinutes()+ ':' + printing_date.getSeconds();
             $('#iec_printing_logs_body_display').append('<tr><td>' + value.iec_id + '</td><td>' +  created_date  + '</td><td>' + printing_date + '</td><td>' + value.iec_printing_contractor + '</td><td>' + value.iec_printing_cost + '</td><td>' + value.iec_printing_pcs +'</td><td>' +  value.name + '</td><td>' + value.iec_printing_remarks+ '</td></tr>');
       });
      } if(data==0) {
          $('#tblPrintingLogs').css('display', 'none');
          $('#NoPrintingRecordDisplay').css('display','block');
          $('#tblPrintingLogs').css('display','none');
      }
    } 
    }); 

    });
//user roles functions
    $('#add_user_material_request').click(function(){
      if ($('#add_user_material_request').prop('checked') == true){
          $('#add_user_material_request').val(1);
        } else {
          $('#add_user_material_request').val(0);
        }
    });
    $('#add_user_inventory').click(function(){
      if ($('#add_user_inventory').prop('checked') == true){
          $('#add_user_inventory').val(1);
        } else {
          $('#add_user_inventory').val(0);
        }
    });

    $('#add_user_code_library').click(function(){
      if ($('#add_user_code_library').prop('checked') == true){
          $('#add_user_code_library').val(1);
        } else {
          $('#add_user_code_library').val(1);
        }
    });
    $('#add_user_management').click(function(){
      if ($('#add_user_management').prop('checked') == true){
          $('#add_user_management').val(1);
        } else {
          $('#add_user_management').val(0);
        }
    });
    $('#add_user_reports').click(function(){
      if ($('#add_user_reports').prop('checked') == true){
          $('#add_user_reports').val(1);
        } else {
          $('#add_user_reports').val(0);
        }
    });

    $('#add_user_audit_log').click(function(){
      if ($('#add_user_audit_log').prop('checked') == true){
          $('#add_user_audit_log').val(1);
        } else {
          $('#add_user_audit_log').val(0);
        }
    });

    $('#add_user_email_notif_iec_material').click(function(){
      if ($('#add_user_email_notif_iec_material').prop('checked') == true){
          $('#add_user_email_notif_iec_material').val(1);
        } else {
          $('#add_user_email_notif_iec_material').val(0);
        }
    });


    $('#edit_user_material_request').click(function(){
        if($('#edit_user_material_request').val() == 1){
          $('#edit_user_material_request').val(2);
          $('#txt_edit_user_material_request').val(2);
        } else {
          $('#edit_user_material_request').val(1);
          $('#txt_edit_user_material_request').val(1);
        }
    });

    $('#edit_user_inventory').click(function(){
        if($('#edit_user_inventory').val() == 1){
          $('#edit_user_inventory').val(2);
          $('#txt_edit_user_inventory').val(2);
        } else {
          $('#edit_user_inventory').val(1);
          $('#txt_edit_user_inventory').val(1);
        }
    });
    $('#edit_user_code_library').click(function(){
        if($('#edit_user_code_library').val() == 1){
          $('#edit_user_code_library').val(2);
          $('#txt_edit_user_code_library').val(2);
        } else {
          $('#edit_user_code_library').val(1);
          $('#txt_edit_user_code_library').val(1);
        }
    });
    $('#edit_user_management').click(function(){
        if($('#edit_user_management').val() == 1){
          $('#edit_user_management').val(2);
          $('#txt_edit_user_management').val(2);
        } else {
          $('#edit_user_management').val(1);
          $('#txt_edit_user_management').val(1);
        }
    });
    $('#edit_user_reports').click(function(){
        if($('#edit_user_reports').val() == 1){
          $('#edit_user_reports').val(2);
          $('#txt_edit_user_reports').val(2);
        } else {
          $('#edit_user_reports').val(1);
          $('#txt_edit_user_reports').val(1);
        }
    });
    $('#edit_user_audit_log').click(function(){
        if($('#edit_user_audit_log').val() == 1){
          $('#edit_user_audit_log').val(2);
          $('#txt_edit_user_audit_log').val(2);
        } else {
          $('#edit_user_audit_log').val(1);
          $('#txt_edit_user_audit_log').val(1);
        }
    });

    $('#edit_user_email_notif_iec_material').click(function(){
        if($('#edit_user_email_notif_iec_material').val() == 1){
          $('#edit_user_email_notif_iec_material').val(2);
          $('#txt_edit_user_email_notif_iec_material').val(2);
        } else {
          $('#edit_user_email_notif_iec_material').val(1);
          $('#txt_edit_user_email_notif_iec_material').val(1);
        }
    });

    //end user roles functions






  $('#txt_uid_val').click(function(){
    $.ajax({
     url: "/admin/user/roles/list/",
     dataType: "json", 
     success: function(data)
      {
        $.each(data, function(key, value) {
            var user_material_request = value.user_material_request;
            var user_inventory = value.user_inventory;
            var user_code_library = value.user_code_library;
            var user_management = value.user_management;
            var user_reports = value.user_reports;
            var user_audit_log = value.user_audit_log;


            if(user_management == '1'){
              $('#div_users').css('display', 'block');
              $('#div_user_roles').css('display', 'block');
            } else {
              $('#div_users').css('display', 'none');
              $('#div_user_roles').css('display', 'none');
            }
/////
            if(user_reports == '1'){
              $('#div_printing_logs').css('display', 'block');
              $('#div_request_history').css('display', 'block');
              $('#div_request_report').css('display', 'block');
              

            } else {
              $('#div_printing_logs').css('display', 'none');
              $('#div_request_history').css('display', 'none');
              $('#div_request_report').css('display', 'none');
            }

            if(user_audit_log == '1'){
              $('#div_audit_trail').css('display', 'block');
              $('#div_request_history').css('display', 'block');
            } else {
              $('#div_audit_trail').css('display', 'none');
              $('#div_request_history').css('display', 'none');
            }

            if(user_material_request == '1') {
              $('#div_material_request').css('display', 'block');
              $('#div_material_inventory_report').css('display', 'block');
            } else {
              $('#div_material_request').css('display', 'none');
              $('#div_material_inventory_report').css('display', 'none');
            }

            if(user_inventory == '1'){
              $('#div_item_inventory').css('display', 'block');
              $('#div_item_files').css('display', 'block');
              $('#div_contractor').css('display', 'block');             
            } else {
              $('#div_item_inventory').css('display', 'none');
              $('#div_item_none').css('display', 'none');
              $('#div_contractor').css('display', 'none');
            }
            
            if(user_code_library == '1'){
              $('#div_code_library').css('display', 'block');
              $('#div_clients').css('display', 'block');
              $('#div_asset_type').css('display', 'block');
              $('#div_organization').css('display', 'block');
            } else {
              $('#div_code_library').css('display', 'none');
              $('#div_clients').css('display', 'none');
              $('#div_asset_type').css('display', 'none');
              $('#div_organization').css('display', 'none');
            }
       });
              $('#div_pageLoader').css('display', 'none'); 
              setTimeout(function () {
                $('#div_pageLoader').css('display', 'none');
              },1000); 
    } 
    }); 
});
}); // end document ready
//end dashboard chart// 
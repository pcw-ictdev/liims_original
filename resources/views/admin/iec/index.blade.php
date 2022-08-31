@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include('layouts.inc.messages')
<script src="/js/jquery-1.12.4.min.js"></script>
         <script type="text/javascript">
          $(document).ready(function(){
            $('#btnaddnewcontractor').click(function(){
              $('#iecrestocking').css('display', 'none');
              $('#addnewcontactor').css('display', 'block');
            });

            $('#btncanceladdnewcontactor').click(function(){
              $('#addnewcontactor').css('display', 'none');
              $('#iecrestocking').css('display', 'block');
            });
          });

  $(document).ready(function(){
  $('#divCreateNewButton').empty();
  $('#divCreateNewButton').append('<br><center><a href="/admin/iec/create" class="btn btn-success">CREATE NEW</a></center>');

    $('#btn_submitContractor').click(function(){
      var contactor_name = $('#contactor_name').val();
       var contactor_contact_person = $('#contactor_contact_person').val();
      var contactor_contact_no = $('#contactor_contact_no').val();
      if(contactor_name == '' || contactor_contact_person == ''){
        $('#lbl-error-message22').empty();
        $('#div-success-notification22').css('display', 'none');
        $('#lbl-error-message22').append('Please fill in all required fields.');
        $('#div-error-notification22').css('display', 'block');
        setTimeout(function () {
                $('#div-error-notification22').css('display', 'none');
                $('#label-error-message22').empty();
            },5000); 
      }  else {
    var contractor = contactor_name + "_" + contactor_contact_person + "_" + "_" + contactor_contact_no;
     $.ajax({
     url: "/admin/contractor/addnew/"+ contractor,
     dataType: "json", 
     success: function(data)
      {
        if(data == 2){
        $('#lbl-error-message22').empty();
        $('#div-success-notification22').css('display', 'none');
        $('#lbl-error-message22').append('Record already exists.');
        $('#div-error-notification22').css('display', 'block');
        setTimeout(function () {
                $('#div-error-notification22').css('display', 'none');
                $('#label-error-message22').empty();
            },5000);           
        }
        if(data == 1){

     //request - clients dropdown
     $.ajax({
     url: "/admin/contractor/lists/",
     dataType: "json", 
     success: function(data)
      {
          $('#txt_iec_printing_contractor').empty();
          $('#txt_iec_printing_contractor').append('<option value="" selected hidden>Select</option>');
          $.each(data, function(key, value) {
          $('#txt_iec_printing_contractor').append('<option value="' + value.contractor_id + '">' + value.contractor_name + '</option>');
        });
      }   
    });  

        $('#lbl-error-message').empty();
        $('#div-error-notification').css('display', 'none');
        $('#lbl-success-message').append('Record successfully added in the database.');
        $('#div-success-notification').css('display', 'block');
        $("#btncanceladdnewcontactor").click();
        $('#contactor_name').val('');
        $('#contactor_contact_person').val('');
        $('#contactor_contact_no').val('');
        setTimeout(function () {
          $('#div-success-notification').css('display', 'none');
          $('#lbl-success-message').empty();
        },5000); 
        }
      }   
    }); 
    }
                });
              });
</script>

<script type="text/javascript">
$(document).ready(function() {
 $('#table').DataTable({
   "order": [[ 0, "asc" ]]
  })
});

</script>

<script type="text/javascript">
$(document).ready(function() {
 $('#tblHistory').DataTable({
   "order": [[ 0, "desc" ]]
  })
});

</script>
<style>
#tblHistory {
  max-height: 400px;
  max-height: auto;
  overflow-y: auto;
  overflow-x: auto;
 
}
#tblPrintingLogs {
  max-height: 400px;
  max-height: auto;
  overflow-y: auto;
  overflow-x: auto;
 
}
  .img-wrapper {display:inline-block; height:159px; overflow:hidden; width:153px;}
  .img-wrapper img {height:159px;}
  .img-wrapper img {border:2px solid #D3D3D3 !important;}

  #table th {background-color:#00c0ef; !important;}
  #tblHistory th {background-color:#00c0ef; !important;}
  #tblPrintingLogs th {background-color:#00c0ef; !important;}
  
</style>
<div class="box box-primary">
  <div class="box-header with-border">
    <div class="col-md-10"></div>
    <div class="col-md-2">

    </div>
</div>
  <!-- /.box-header -->
    <div class="box-body" style="overflow-y: auto; overflow-x: auto; overflow-x: scroll; overflow-y: scroll;">
 
    <table class="display table table-bordered" id="tblHistory"> 
    <thead>
    <tr>
      <th>Title/Description</th>
      <th>Type</th>
      <th>Image</th>
      <th>Pcs. Available</th>
      <th>Action</th>
    </tr>
  </thead>
  @foreach($iecs as $iec)
<script type="text/javascript">
$(document).ready(function() {
 $('#table').DataTable({
   "order": [[ 1, "asc" ]]
  })
});

</script>
    @if($iec->is_deleted != 2)
        <tr>
      @else
        <tr style="background-color:#fcc0c0 !important;">    
      @endif
      <td>{{$iec->iec_title}}</td>
      <td>{{$iec->material_name}}</td>
      <td> 
    <div class="img-wrapper">
        @if($iec->iec_image == '') 
        <img src="/images/Image-Not-Available-Icon.jpg">
        @else 
        <img src="{{$iec->iec_image}}">
        @endif
      </div>
      </td>
      <td>
        @php echo number_format($iec->iec_threshold); @endphp
       @if($iec->is_deleted != 1)
        <label class="btn btn-info" data-toggle="modal" data-target="#iecModal" id="lblEdit{{$iec->id}}" value="{{$iec->id}}" title="Update stock"> <i class="fa fa-edit" aria-hidden="true"> </i></label>
      @endif

<script type="text/javascript">
$('#lblEdit{{$iec->id}}').click(function () { 
  $('#txt_iec_id').val({{$iec->id}});
  $('#txt_iec_stock').val({{$iec->iec_threshold}});
  $('#iec_restock').click();
  });
</script>

      </td>
      <td>
        <div class="row">
          <div class="col-md-12">
      @if($iec->is_deleted == 1) <b>Deleted</b> @endif
       @if($iec->is_deleted != 1)
            <a href="#" data-toggle="modal" data-target="#iecModalViewHistory{{$iec->id}}" id="lblView{{$iec->id}}" value="{{$iec->id}}" title="View"> View </a> |
            <a href="/admin/iec/edit/{{$iec->id}}">Edit</a> |
            <a href="/admin/iec/delete/{{  $iec->id }}" onclick="return confirm('Are you sure to delete this record?')">Delete</a>
        @endif
            <script type="text/javascript">
              $('#lblView{{$iec->id}}').click(function () { 
              $('#txt_view_iec_id').val({{ $iec->id }});
              
              $('#txt_view_iec_id').click();
              });
            </script>
          </div>
        </div>

<div class="modal" tabindex="-1" id="iecModalAddNewEcopy{{$iec->id}}" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" style="text-align:left;"> 
           Add New E-Copy 
          </h4>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
        </div>
      </div>
       <div class="row">
        <div class="col-md-4">
         
        </div>
        <div class="col-md-10">
        </div>
      </div>     
      <form method="POST" action="/admin/files/new/{{$iec->id}}" class="form-horizontal"  enctype="multipart/form-data">
      {{ csrf_field() }}
      <div class="row">
        <div class="col-md-2" style="text-align: left !important;">Title <label style="color:red !important;"><b>*</b></label></div>
         <div class="col-md-6" style="text-align: left">
          <input type="hidden" class="form-control" style="width:100% !important;" name="txt_ecopy_title[]" id="txt_ecopy_title[]" value="{{$iec->id}}" readonly>
          {{$iec->iec_title}}
      </div>
    </div>
      <div class="row">
        <div class="col-md-12">
          &nbsp;
        </div>
      </div>
      <div class="row">
        <div class="col-md-2" style="text-align: left !important;">
          Version <label style="color:red !important;"><b>*</b></label></div>
        <div class="col-md-6" style="text-align: left"><input type="text" class="form-control" name="txt_ecopy_version_no[]" id="txt_ecopy_version_no[]"></div>
      </div>
      <div class="row">
        <div class="col-md-12">
          &nbsp;
        </div>
      </div>
      <div class="row">
        <div class="col-md-2" style="text-align: left !important;">
          Upload File <label style="color:red !important;"><b>*</b></label></div>
        <div class="col-md-4" style="text-align: left">
          <input type="file" class="form-control" name="txt_iec_soft_copy[]" id="txt_iec_soft_copy[]" accept="image/jpeg,image/gif,image/png,application/pdf,image/">
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          
        </div>
        <div class="col-md-2">
          <button class="btn btn-success" id="btnEcopySave{{$iec->id}}">Save</button>
        </div>
      </div>
      </div>
      <div class="modal-footer">
      
    </form>
          <script type="text/javascript">
            $(document).ready(function(){
            $('#test{{$iec->id}}').click(function(){
              $('#txt_ecopy_version_no{{$iec->id}}').val('');
              $('#txt_ecopy_title{{$iec->id}}').val('');
              $('input[type=file]').val('');
              $('#btnEcopySave{{$iec->id}}').css('display', 'none');
              $('#btnEcopyClear{{$iec->id}}').css('display', 'none');
            });
          });
        </script>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" data-toggle="modal" href="#iecModalViewHistory{{$iec->id}}" id="test{{$iec->id}}">Close</button>
      </div>
    </div>
  </div>
</div>



 <!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="iecModalViewHistory{{$iec->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>      
      <div class="row">
        <div class="col-md-4">
         <h5 style="text-align:left !important;"><b>View IEC Material</b></h5>
        </div>
      </div>

  <div class="box-body">
    <div class="row">
      <div class="col-md-12">
      </div>
    </div>
  </div>

        <div class="box-body">
          <div class="box box-success collapsed-box">
            <div class="box-header with-border">
              <h3 class="box-title">E-Copy Versions</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" title="Click + to display records"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
        
        <script type="text/javascript">
          $(document).ready(function(){
            $('#button_add_new_ecopy{{$iec->id}}').click(function(){
              console.log('test');
              $('#button_add_new_ecopy{{$iec->id}}').css('display', 'none');
              $('#div_add_new_ecopy{{$iec->id}}').css('display', 'block');
              $('#btnEcopySave{{$iec->id}}').css('display', 'block');
              $('#btnEcopyClear{{$iec->id}}').css('display', 'block');
            });  
 
            $('#btnEcopyClear{{$iec->id}}').click(function(){
              console.log('cancel');
              $('#button_add_new_ecopy{{$iec->id}}').css('display', 'block');
              $('#div_add_new_ecopy{{$iec->id}}').css('display', 'none');
              $('#txt_ecopy_version_no{{$iec->id}}').val('');
              $('#txt_ecopy_title{{$iec->id}}').val('');
              $('input[type=file]').val('');
              $('#btnEcopySave{{$iec->id}}').css('display', 'none');
              $('#btnEcopyClear{{$iec->id}}').css('display', 'none');
            $('#btnEcopySave{{$iec->id}}').click(function(){
              console.log('save');
 
            });
       
          });     
         });    
        </script>

    <div class="box-body" style="overflow-x:auto !important;"> 
      <div class="row">
        <div class="col-md-1">
            <button type="button" class="btn btn-success" data-dismiss="modal" data-toggle="modal" href="#iecModalAddNewEcopy{{$iec->id}}">Add New</button>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
        </div>
      </div>

     <table class="display table table-bordered" id="tblHistory" style="width:100% !important;">
        <thead>
          <tr>
            <th>Version</th>
            <th>File</th>
            <th>Uploaded By</th>
            <th>Date Uploaded</th>
            <th>Actions</th>
           </tr>
        </thead>
        <tbody>
        @foreach($ecopies as $ecopy)
          @if($iec->id == $ecopy->ecopy_iec_title)
        @if($ecopy->is_deleted ==0)
            <tr>
          @else
            <tr style="background-color:#fcc0c0 !important;">    
          @endif
            <td>{{$ecopy->ecopy_version_no}}</td>
            <td>{{$ecopy->iec_title}}</td>
            <td>{{$ecopy->userName}}</td>
            <td>@php echo date('M d, Y', strtotime($ecopy->created_at)); @endphp</td>
            <td>
        @if($ecopy->is_deleted == 1) <b>Deleted</b> @endif
        @if($ecopy->is_deleted != 1)
              <a href="/admin/iec/download/{{$ecopy->ecopy_id}}"><i class="fa fa-download" aria-hidden="true" title="Download"></i></a> 
              &nbsp; &nbsp;  
              <a href="/admin/files/delete-file/{{$ecopy->ecopy_id}}" title="Delete" onclick="return confirm('Are you sure to delete this record?')"><i class="fa fa-trash" aria-hidden="true"></i></a> </td>
        @endif
          </tr>
          @endif
        @endforeach 
        </tbody>
      </table>
      </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

        <div class="box-body" style="overflow-x: auto;">
          <div class="box box-info collapsed-box">
            <div class="box-header with-border">
              <h3 class="box-title">Inventory History</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" title="Click + to display records"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
    <div class="box-body" style="overflow-x: auto !important;">  

<form action="/admin/inventory-history/print-all/2" method="POST" class="form-horizontal">
        {{ csrf_field() }}
    @foreach($inventories as $inventory)
    @if($iec->id == $inventory->iec_update_id)
    @php 
    @endphp
    <input type="hidden" name="chk_selectOneIec[]" value="{{$inventory->history_id}}">
    @endif
    @endforeach
    
    <button id="btn_aprint_allrecIec" class="btn btn-success" style="margin-top:16px !important; margin-left:-412px !important; position:absolute;">SELECT ALL</button>    
</form>
<form action="/admin/inventory-history/print-all/1" method="POST" class="form-horizontal">
        {{ csrf_field() }}
     <table class="display table table-bordered" id="tblHistory" style="width:100% !important;">
        <thead>
          <tr>
            <th style="display: none;">ID</th>
            <th>
              <input id = 'approveAll' type='checkbox' name ='approveAll' onchange='selectAll()' style="display:none;"> 
        <script type="text/javascript">
            function selectAll(){
              if(document.getElementById('approveAll').checked){
                var checks = document.getElementsByName('chk_selectOneIec[]');
                var numberNotChecked =  $('.checkboxes:checked:not(:hidden)').length;
                  for(var i=0; i < checks.length; i++){
                    checks[i].checked = true;            
                    }            
              } else {
                var checks = document.getElementsByName('chk_selectOneIec[]');
                for(var i=0; i < checks.length; i++){
                  checks[i].checked = false; 
                }
              }
        }
        </script>
            </th>
            <th>Inventory Type</th>
            <th>Title</th>
            <th>Previous</th>
            <th>Pc(s)</th>
            <th>Current</th>
            <th>Date Modified</th>
            <th>Modified By</th>
            <th>Remarks</th>
           </tr>
        </thead>
        <tbody>
          @foreach($inventories as $inventory)
          @if($iec->id == $inventory->iec_update_id)
          <tr>
            <td style="display: none;">{{$inventory->iec_update_id}}</td>
            <td>
              <input type="checkbox" name="chk_selectOneIec[]" id="chk_selectOneIec{{$inventory->history_id}}" value="{{$inventory->history_id}}">
            </center>
            </td>
            <td>
              @php if($inventory->iec_update_type == 1)
                $iecUpdateType = 'Restocked';
              @endphp  

              @php if($inventory->iec_update_type == 2)
                $iecUpdateType = 'Adjusted';
              @endphp  

              @php if($inventory->iec_update_type == 3)
                $iecUpdateType = 'Update Details';
              @endphp  

              @php if($inventory->iec_update_type == 4)
                $iecUpdateType = 'Provide to Client';
              @endphp 

                {{$iecUpdateType}} 
            </td>
            <td>{{$inventory->iec_title}}</td>
            <td>
              @php 
                echo number_format($inventory->iec_update_threshold); 
              @endphp
            </td>
            <td>
              @php 
                echo number_format($inventory->iec_update_pieces); 
              @endphp
            </td>
            <td>
              @php
                echo number_format($inventory->iec_current_threshold);  
              @endphp
            </td>
            <td>@php 
              echo date('M d, Y h:i:s', strtotime($inventory->created_at)); @endphp
            </td>
            <td>{{$inventory->name}}</td>
            <td>{{$inventory->iec_update_remarks}}</td>
           </tr>
           @endif
          @endforeach
        </tbody>
      </table>
         <button type="submit" name="btn_print_allrec{{$inventory->id}}" id="btn_print_allrecIec" class="btn btn-success" style="display:none;">Print</button>   
<script type="text/javascript">
$(document).ready(function () {
    $('#btn_aprint_allrecIec').click(function () {
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
      console.log('check');
      $('#btn_print_allrecIec').click();
    }
  });
//end ---- print request history select all and print
});
</script>
<div class="row">
 <div class="col-md-2">
    <button id="btn_aprint_allrecIec" class="btn btn-info">PRINT SELECTED</button>    
 
</form> 
 </div>
 <div class="col-md-10">
 </div>
  </div>
  </div>
 
      </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <div class="box-body" style="overflow-x: auto !important;">
          <div class="box box-default collapsed-box">
            <div class="box-header with-border">
              <h3 class="box-title">Reprinting History</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" title="Click + to display records"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
<form action="/admin/printing-logs/print-all/1" method="POST" class="form-horizontal">
        {{ csrf_field() }}
          @foreach($printinglogs as $printinglog)
          @if($iec->id == $printinglog->iec_id)
    @php 
    @endphp
    <input type="hidden" name="chk_selectOne[]" value="{{$printinglog->recordid}}">
    @endif
    @endforeach
    
    <button id="btn_aprint_allrecIec" class="btn btn-success" style="margin-top:16px !important; margin-left:-426px !important; position:absolute;">SELECT ALL</button>    
</form>
        <form action="/admin/printing-logs/print-all/1" method="POST" class="form-horizontal">
        {{ csrf_field() }}

        <table class="display table table-bordered" id="tblHistory" style="width:100% !important;">
        <thead>
          <tr>
            <th>
            </th>
            <th>title</th>
            <th>Copyright</th>
            <th>Date Received</th>
            <th>Title</th>
            <th>Contractor/Donor</th>
            <th>Printing Cost/Piece</th>
            <th>Pc(s)</th>
            <th>Remarks</th>
           </tr>
        </thead>
          @foreach($printinglogs as $printinglog)
          @if($iec->id == $printinglog->iec_id)
          <tr>
           <td>
            <center>
            <input type="checkbox" name="chk_selectOne[]" id="chk_selectOne{{$printinglog->recordid}}" value="{{$printinglog->recordid}}">
            </center>
           </td>
            <td>{{$printinglog->iec_title}}</td>
            <td>@php 
              echo date('Y', strtotime($printinglog->created_at)); @endphp
            </td>
            <td>@php 
              echo date('M d, Y', strtotime($printinglog->iec_printing_date)); @endphp
            </td>
            <td>{{$printinglog->iec_title}}</td>
            <td>{{$printinglog->contractor_name}}</td>
            <td>@php 
                  if($printinglog->iec_printing_cost == 'N/A')
                    echo 'N/A'; 
                  if($printinglog->iec_printing_cost != 'N/A')
                    echo number_format($printinglog->iec_printing_cost); 
                  @endphp
            </td>
            <td>@php 
              echo number_format($printinglog->iec_printing_pcs);
              @endphp</td>
            <td>{{$printinglog->iec_printing_remarks}}</td>
           </tr>
           @endif
          @endforeach 
      </table>
         <button type="submit" name="btn_print_allrec{{$iec->id}}" id="btn_print_allrec" class="btn btn-success" style="display:none;">Print</button>   
<script type="text/javascript">
$(document).ready(function () {
    $('#btn_aprint_allrec').click(function () {
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
      console.log('check');
      $('#btn_print_allrec').click();
    }
  });
//end ---- print request history select all and print
});
</script>
<div class="row">
  <div class="col-md-2">
    <button id="btn_aprint_allrec" class="btn btn-info">PRINT SELECTED</button>  
  </div>
  <div class="col-md-10">
  </div>
</div>

      </form>      



        <div class="row">
    <div class="col-md-12">
  <!-- header success notification -->
<div class="form-group"  id="div-success-notification" style="display: none;">
    <div class="alert alert-success alert-dismissible" id="divMessage">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <p><i class="fa fa-check"></i>&nbsp;
           <label id="lbl-success-message"></label>            
        </p>
    </div> <!-- /.alert -->
</div> <!-- /.form-group -->
<!-- header error notification -->

<!-- header error notification -->
<div class="form-group" id="div-error-notification" style="display: none;">
    <div class="alert alert-danger alert-dismissible" id="divMessage">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <p><i class="fa fa-warning"></i>&nbsp;
        <label id="lbl-error-message"> asd</label>
        </p>
    </div> <!-- /.alert -->
</div> <!-- /.form-group -->
<!-- end header error notification -->
    </div>

</div>

 
          </div>
          <!-- /.box -->
 
</div>

      <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
        </div>

      </div>
    </div>
  </div>
</div>
</div>
      </td>
</tr>
  @endforeach
  </table>
    </div> <!-- /.box-body -->
    <div class="box-footer"></div> <!-- /.box-footer -->
</div> <!-- /.box box-primary -->
 
@endsection





 <!-- Modal -->
<div class="modal fade" id="iecModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
  <div class="box-header with-border">
    <h4><b>Inventory Type</b></h4>
  </div> <!-- /.box-header -->
  <div class="box-body" id="addnewcontactor" style="display: none;">
    <div class="row">
      <div class="co-md-12">
  <!-- header success notification -->
<div class="form-group"  id="div-success-notification22" style="display: none;">
    <div class="alert alert-success alert-dismissible" id="divMessage22">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <p><i class="fa fa-check"></i>&nbsp;
           <label id="lbl-success-message22"></label>            
        </p>
    </div> <!-- /.alert -->
</div> <!-- /.form-group -->
<!-- header error notification -->

<!-- header error notification -->
<div class="form-group" id="div-error-notification22" style="display: none;">
    <div class="alert alert-danger alert-dismissible" id="divMessage22">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <p><i class="fa fa-warning"></i>&nbsp;
        <label id="lbl-error-message22"> asd</label>
        </p>
    </div> <!-- /.alert -->
</div> <!-- /.form-group -->
<!-- end header error notification -->
      </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">
          
            <h4>
              <b>
              Add New Printing Contractor
            </b>
            </h4>
      </div>
    </div>
    </div>
        <div class="row">
          <div class="col-md-3">
            <b>Contractor/Donor</b> <b style="color:#FF0000; font-size:18px;">*</b>
          </div>
          <div class="col-md-8">
            <input type="text" id="contactor_name" class="form-control">
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            &nbsp;
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <b>Contact Person</b> <b style="color:#FF0000; font-size:18px;">*</b>
          </div>
          <div class="col-md-6">
            <textarea id="contactor_contact_person" class="form-control"></textarea>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            &nbsp;
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">
            <b>Contact No</b> <b style="color:#FF0000; font-size:18px;">*</b>
          </div>
          <div class="col-md-4">
            <input type="text" id="contactor_contact_no" class="form-control" required>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            &nbsp;
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
          </div>
          <div class="col-md-2">
                  <label id="btncanceladdnewcontactor" class="btn btn-default form-control">Cancel</label>
          </div>

          <div class="col-md-2">
            <button id="btn_submitContractor" class="btn btn-success form-control">Save</button>

          </div>
        </div>
        </div>
      </div>
 
     <form method="POST" action="/admin/iec/update-stock" class="form-horizontal">
      {{ csrf_field() }}
    <div id="iecrestocking">
    <div class="box-body">    
  <!-- header success notification -->
<div class="form-group"  id="div-success-notification" style="display: none;">
    <div class="alert alert-success alert-dismissible" id="divMessage">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <p><i class="fa fa-check"></i>&nbsp;
           <label id="lbl-success-message"></label>            
        </p>
    </div> <!-- /.alert -->
</div> <!-- /.form-group -->
<!-- header error notification -->

<!-- header error notification -->
<div class="form-group" id="div-error-notification" style="display: none;">
    <div class="alert alert-danger alert-dismissible" id="divMessage">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <p><i class="fa fa-warning"></i>&nbsp;
        <label id="lbl-error-message"></label>
        </p>
    </div> <!-- /.alert -->
</div> <!-- /.form-group -->
<!-- end header error notification -->

      <input type="text" id="txt_iec_id" name="txt_iec_id" style="display:none;">
      <input type="text" id="txt_iec_stock" name="txt_iec_stock" style="display:none;">
      <div class="row">
        <div class="col-md-4">
         <h4><b> <input type="radio" name="iec_restock" id="iec_restock">
          Restock</b></h4>
          </div>
        <div class="col-md-4">
         <h4><b><input type="radio" name="iec_adjust" id="iec_adjust">
          Adjust</b></h4>
          </div>
        </div> 
        <input type="text" id="iec_option" name="iec_option" style="display:none;">
      </div>
    <div class="box-body" id="divPrintingInfo" style="display:none;">
    <div class="row">
      <div class="col-md-4">
        <b>Date Received</b><b style="color:#FF0000; font-size:18px;">*</b>
      </div>
      <div class="col-md-4">
        <input type="date" class="form-control" name="txt_iec_printing_date" id="txt_iec_printing_date" required>
      </div>
    </div>  
    <div class="row">
      <div class="col-md-2">
        &nbsp;
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <b>Contractor/Donor</b><b style="color:#FF0000; font-size:18px;">*</b>
      </div>
      <div class="col-md-6">
      <input list="contactors" name="txt_iec_printing_contractor" id="txt_iec_printing_contractor"  class="form-control" placeholder="Contractor">
      <datalist id="contactors">
      @foreach($contractors as $contractor)
        <option value="" selected>{{$contractor->contractor_name}}</option>
        <option value="{{$contractor->contractor_name}}">
      @endforeach
      </datalist>
       </div>
      <div class="col-md-2">
        <label id="btnaddnewcontractor" class="btn btn-info form-control"><i class="fa fa-plus"></i></label>
      </div> 
    </div>  
    <div class="row">
      <div class="col-md-2">
        &nbsp;
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <b>Printing Cost/Piece </b><b style="color:#FF0000; font-size:18px;">*</b>
      </div>
      <div class="col-md-3">
        <input type="text" class="form-control" name="txt_iec_printing_cost" id="txt_iec_printing_cost" required>
      </div>
    </div> 
    <div class="row">
      <div class="col-md-2">
        &nbsp;
      </div>
    </div> 
    </div>
    <hr>
    <div class="box-body">          
      <div class="row">
         <div class="col-md-4">
          <span id="divPieces" style="display: none;">
            <b>Actual Pieces Remaining </b><b style="color:#FF0000; font-size:18px;">*</b>
          </span>
          <span id="divAdditionalPieces">
            <b>Additional Piece(s) </b><b style="color:#FF0000; font-size:18px;">*</b>
          </span>          
          </div>       
        <div class="col-md-3">
              <input type="number" name="iec_adjust_pieces" id="iec_adjustPieces" class="form-control" required>
              <input type="number" name="iec_restock_pieces" id="iec_restockPieces" class="form-control" style="display:none;" required>
          </div>
        </div>
      </div>
    <div class="box-body">   
      <div class="row">
         <div class="col-md-4">
          <b>Remarks</b><b style="color:#FF0000; font-size:18px;">*</b>
          </div>       
        <div class="col-md-8">
          <textarea name="iec_remarks" id="iec_remarks" class="form-control" style="height:120px;" required></textarea>
          </div>
        </div>
      </div>
    <div class="box-body">   
      <div class="row">  
        <div class="col-md-10"></div>
        <div class="col-md-2">
          <label class="btn btn-success" id="lblSave">Save</label>
          <button type="submit" class="btn btn-info" name="btnSave" id="btnSave" style="display:none;">Save</button>
        </div>
        </div>
      </div>
    </form>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

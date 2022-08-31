@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include('layouts.inc.messages')
<script src="/js/jquery-1.12.4.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
 $('#tblHistory').DataTable({
   "order": [[ 0, "desc" ]]
  })
});

$(document).ready(function() {
  $('#btnboxtool').click();
});

$('#btnboxtool').click(function(){
    $('#btnboxtool').toggle();
})
</script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#txt_report_menu').change(function(){
      var amenu = $('#txt_report_menu').val();
      if(amenu != 1 && amenu != 2 && amenu != 3) {
        $('#show_date_range').css('display', 'block');
        $('#btn_submit').css('display', 'none');
        $('#txt_report_menu2').val($('#txt_report_menu').val());
        $('#txt_report_menu3').val($('#txt_report_menu').val());
      } else {
        $('#show_date_range').css('display', 'none'); 
        $('#txt_report_menu2').val($('#txt_report_menu').val());
        $('#txt_report_menu3').val($('#txt_report_menu').val());  
        $('#btn_submit1').click();    
      }
    });
  });
</script>
<style>
  #tblHistory th {background-color:#00c0ef; !important;}

#tblHistory {
  max-height: 400px;
  max-height: auto;
  overflow-y: auto;
  overflow-x: auto;
}
</style>
<div class="box box-primary">

  <div class="box-header with-border">
    <div class="col-md-10">
    </div>
    <div class="col-md-2">
    </div>
</div>
  <!-- /.box-header -->
    <div class="box-body">
      <div class="box-body">
          <div class="row">
            <div class="col-md-2">
              Report Type
            </div>
            <div class="col-md-4">

              <select class="form-control" id="txt_report_menu" name="txt_report_menu" required>
                <option value="">Select</option>
                <option value="1">Clients</option>
                <option value="2">Organizations</option>
                <option value="3">Contractor/Printing Company</option>
                <option value="4">IEC List</option>
                <option value="5">Audit Log</option>
                <option value="6">Inventory History</option>
                <option value="9">IEC Materials Inventory Report</option>
                <option value="7">Reprinting History</option>
                <option value="8">Request History</option>
                
              </select>
             </div>
            <div class="col-md-2">
            <form method="POST" action="/admin/reports" class="form-horizontal">
              {{ csrf_field() }} 
              <input type="hidden" name="txt_report_menu" id="txt_report_menu2"> 
              <button type="submit" name="btn_submit2" id="btn_submit1" class="btn btn-info" style="display:none;">Find</button>
            </form>
            </div>
          </div>
      <div id="show_date_range" style="display: none;">
        <form method="POST" action="/admin/reports" class="form-horizontal">
          {{ csrf_field() }}
          <input type="hidden" name="txt_report_menu" id="txt_report_menu3"> 
      <div class="row">
      <div class="col-md-6">
        <h4>Filter</h4>
      </div>
      </div>
      <div class="row">
        <div class="col-xs-3">
        Date From
        <input type="date" class="form-control input-xs" name="txt_iec_date_from" id="txt_iec_date_from" required> 
        </div>
        <div class="col-xs-3">
        Date To 
        <input type="date" class="form-control input-xs" name="txt_iec_date_to" id="txt_iec_date_to" required>
        </div>
        <div class="col-xs-1">
        <br>
        <button class="btn btn-info" id="btn_iec_find">Find</button> 
        </div>
        <div class="col-xs-1">
         &nbsp;
        </div>
      </form>
      </div>
        </div>
        <div class="box-body">

  <!--Clients List !-->
  @if(isset($selected) == 1 && isset($clients))
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Clients List</h3>
            </div>
    <div class="box-body">
<form action="/admin/clients/print-all/1" method="POST" class="form-horizontal">
    {{ csrf_field() }}
      <table class="display table table-bordered" id="tblHistory" style="width:100% !important;"> 
    <thead>
    <tr>
      <th>
        <input id = 'approveAll' type='checkbox' name ='approveAll' onchange='selectAll()' style="display:none;"> 
        <script type="text/javascript">
            function selectAll(){
              if(document.getElementById('approveAll').checked){
                var checks = document.getElementsByName('chk_selectOneClient[]');
                var numberNotChecked =  $('.checkboxes:checked:not(:hidden)').length;
                  for(var i=0; i < checks.length; i++){
                    checks[i].checked = true;            
                    }            
              } else {
                var checks = document.getElementsByName('chk_selectOneClient[]');
                for(var i=0; i < checks.length; i++){
                  checks[i].checked = false; 
                }
              }
        }
        </script>
      </th>
      <th>Clients Name</th>
      <th>Ogranization</th>
      <th>Designation</th>
      <th>Contact No</th>
      <th>Created By</th>
    </tr>
  </thead>
  <tbody>
    @foreach($clients as $client)
    <tr>
     <td>
        <input type="checkbox" name="chk_selectOneClient[]" id="chk_selectOneClient{{$client->id}}" value="{{$client->id}}">
     </td>
      <td>{{$client->client_name}}</td>
      <td>{{$client->organization_name}}</td>
      <td>{{$client->client_designation}}</td>
      <td>{{$client->client_contact_no}}</td>
      <td>{{$client->name}}</td>
    </tr>
  </tbody>
   @endforeach
  </table>

 <button type="submit" name="btn_print_allrec{{$client->id}}" id="btn_print_allrecIec" class="btn btn-success" style="display:none;">Print</button>

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
      $('#btn_print_allrecIec').click();
    }
  });
 
});
</script>

  {{-- report -> client lists --}}
  <div class="row">
  <div class="col-md-2">
      <button id="btn_aprint_allrecIec" class="btn btn-info">PRINT SELECTED</button>    
  </div>

</form>
  <div class="col-md-2">
    <form action="/admin/clients/print-all/2" method="POST" class="form-horizontal">
          {{ csrf_field() }}
      @foreach($clients as $client)
      @php 
      @endphp
      <input type="hidden" name="chk_selectOneClient[]" value="{{$client->id}}">
      @endforeach
      <button id="btn_aprint_allrecIec" class="btn btn-success">PRINT ALL</button>  
    </form>
  </div>  
</div>

    </div> <!-- /.box-body -->
  </div>
  @endif
  <!--End Clients List !-->

<!--Organizations List !-->
 
  @if(isset($selected) == 2 && isset($organizations))
  <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Organizations List</h3>
      </div>
  <div class="box-body">
  <form action="/admin/organizations/preview-list/1" method="POST" class="form-horizontal">
  {{ csrf_field() }}
     <table class="display table table-bordered" id="tblHistory" style="width:100% !important;">
    <thead>
    <tr>
      <th>
          <input id = 'approveAll' type='checkbox' name ='approveAll' onchange='selectAll()' style="display:none;"> 
        <script type="text/javascript">
            function selectAll(){
              if(document.getElementById('approveAll').checked){
                var checks = document.getElementsByName('chk_selectOneOrg[]');
                var numberNotChecked =  $('.checkboxes:checked:not(:hidden)').length;
                  for(var i=0; i < checks.length; i++){
                    checks[i].checked = true;            
                    }            
              } else {
                var checks = document.getElementsByName('chk_selectOneOrg[]');
                for(var i=0; i < checks.length; i++){
                  checks[i].checked = false; 
                }
              }
        }
        </script>
            </th>
      <th style="display:none;">ID</th>

      <th>Organization</th>
      <th>Organization Type</th>
      <th>Address</th>
      <th>Created By</th>
    </tr>
  </thead>
  @foreach($organizations as $organization)
        <tr>
      <td style="display:none;">{{$organization->id}}</td>
      <td>
        <input type="checkbox" name="chk_selectOneOrg[]" id="chk_selectOneOrg{{$organization->id}}" value="{{$organization->id}}">
      </td>
      <td style="text-align:left !important;">{{$organization->organization_name}}</td>
      <td>{{$organization->org_type_code}}</td>
      <td>{{$organization->organization_address}}</td>
      <td>{{$organization->name}}</td>
    </tr>
   @endforeach
  </table>
         <button type="submit" name="btn_print_allrec{{$organization->id}}" id="btn_print_allrecIec" class="btn btn-success" style="display:none;">Print</button>   
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
      $('#btn_print_allrecIec').click();
    }
  });
//end ---- print request history select all and print
});
</script>
<div class="row">
 <div class="col-md-2">
    <button id="btn_aprint_allrecIec" class="btn btn-info">PRINT SELECTED</button>    
 </div>
</form>
<div class="col-md-2">
  <form action="/admin/organizations/preview-list/2" method="POST" class="form-horizontal">
    {{ csrf_field() }}
    @foreach($organizations as $organization)
    @php 
    @endphp
    <input type="hidden" name="chk_selectOneOrg[]" value="{{$organization->id}}">
    @endforeach
    <button id="btn_aprint_allrecIec" class="btn btn-success">PRINT ALL</button>    
</form>
</div>
</div>
    </div> <!-- /.box-body -->
  </div>
@endif
    <!--End Organizations List!-->

  <!--Contractors List!-->
  @if(isset($selected) == 3 && isset($contractors))
    <div class="box-body">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Contractors List</h3>
            </div>

    <form action="/admin/contractors/print-all/1" method="POST" class="form-horizontal">
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
                var checks = document.getElementsByName('chk_selectOneContractor[]');
                var numberNotChecked =  $('.checkboxes:checked:not(:hidden)').length;
                  for(var i=0; i < checks.length; i++){
                    checks[i].checked = true;            
                    }            
              } else {
                var checks = document.getElementsByName('chk_selectOneContractor[]');
                for(var i=0; i < checks.length; i++){
                  checks[i].checked = false; 
                }
              }
        }
        </script>
      </th>
      <th>Contractor's Name</th>
      <th>Contractor's Contact Person</th>
      <th>Contractor's Contact No</th>
    </tr>
  </thead>
  @foreach($contractors as $contractor)
    <tr>
      <td style="display:none;"></td>
    <td>
      <input type="checkbox" name="chk_selectOneContractor[]" id="chk_selectOneContractor{{$contractor->contractor_id}}" value="{{$contractor->contractor_id}}">
    </td>
      <td>{{$contractor->contractor_name}}</td>
      <td>{{$contractor->contractor_contact_person}}</td>
      <td>{{$contractor->contractor_contact_no}}</td>
    </tr>
   @endforeach
  </table>
         <button type="submit" name="btn_print_allrec{{$contractor->contractor_id}}" id="btn_print_allrecIec" class="btn btn-success" style="display:none;">Print</button>   
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
      $('#btn_print_allrecIec').click();
    }
  });
//end ---- print request history select all and print
});
</script>
<div class="row">
 <div class="col-md-2">
    <button id="btn_aprint_allrecIec" class="btn btn-info">PRINT SELECTED</button>    
 </div>
</form>
<div class="col-md-2">
  <form action="/admin/contractors/print-all/2" method="POST" class="form-horizontal">
    {{ csrf_field() }}
    @foreach($contractors as $contractor)
    @php 
    @endphp
    <input type="hidden" name="chk_selectOneContractor[]" value="{{$contractor->contractor_id}}">
    @endforeach
    <button id="btn_aprint_allrecIec" class="btn btn-success">PRINT ALL</button></form>
   </div>
  </div> 
    </div> <!-- /.box-body -->
  @endif
  <!--End Contractors List!-->


<!-- IEC List -->
@if(isset($selected) == 4 && isset($iecs))
  <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">List of Available IEC Materials</h3>
      </div>
<div class="box-body" style="overflow-y: auto; overflow-x: auto; overflow-x: scroll; overflow-y: scroll;">
<form action="/admin/iecs/print-all/1" method="POST" class="form-horizontal">
        {{ csrf_field() }}
          @foreach($iecs as $iec)
    @endforeach
     <h4 style="text-align: left !important"> Period Covered: @php echo date('M d, Y', strtotime($date_range_from)); @endphp - @php echo date('M d, Y', strtotime($date_range_to)); @endphp</h4>
    <input type="hidden" name="txt_iec_date_from" value="{{$date_range_from}}">
    <input type="hidden" name="txt_iec_date_to" value="{{$date_range_to}}">
     <table class="display table table-bordered" id="table" style="width:100% !important;">
        <thead>
          <tr>
            <th style="display: none;">ID</th>
            <th  style="background-color:#00c0ef !important;">
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
            <th  style="background-color:#00c0ef !important;">Title</th>
            <th  style="background-color:#00c0ef !important;">Material Type</th>
            <th  style="background-color:#00c0ef !important;">Beginning Balance of the month</th>
            <th  style="background-color:#00c0ef !important;">No. of Materials Released</th>
            <th  style="background-color:#00c0ef !important;">Ending Balance of the month</th>
           </tr>
        </thead>
        <tbody>
          @foreach($iecs as $iec)

          <tr>
            <td style="display: none;">{{$iec->id}}</td>
            <td>
              <input type="checkbox" name="chk_selectOneIec[]" id="chk_selectOneIec{{$iec->id}}" value="{{$iec->id}}">
            </td>
            <td style="text-align:left !important;">
              {{$iec->iec_title}}</td>
            <td style="text-align:left !important;">
              {{$iec->material_name}}</td>
            
              <input type="hidden" id="txt{{$iec->id}}" value="0">
            <td id="td{{$iec->id}}">{{$iec->iec_threshold}}</td>
            <td id="td2{{$iec->id}}">0

            <?php foreach($aiecinventoriesPieces as $inventoryiec11) { if($iec->id == $inventoryiec11->iec_update_id) { echo $inventoryiec11->total_requested_pieces; } } ?>
              

            </td>
            <td id="td3{{$iec->id}}">{{$iec->iec_threshold}}</td>
              @foreach($aiecinventoriesEndingBalance as $endbalance)
              @if($iec->id == $endbalance->iec_update_id)
            @foreach($aiecinventories as $inventoryiec)
            @php
            $acount = [];
            @endphp
              @if($inventoryiec->iec_update_id == $iec->id)
                <script type="text/javascript">
                 $(document).ready(function(){
                  $('#txt{{$iec->id}}').val({{$inventoryiec->iec_update_threshold}});
                     if($('#txt{{$iec->id}}').val()!= null){
                      $('#td{{$iec->id}}').empty();
                      $('#td2{{$iec->id}}').empty();
                      $('#td3{{$iec->id}}').empty();
                      $('#td{{$iec->id}}').append({{$inventoryiec->iec_update_threshold}});

                      $('#td2{{$iec->id}}').append(<?php foreach($aiecinventoriesPieces as $inventoryiec11) { if($iec->id == $inventoryiec11->iec_update_id) {echo $inventoryiec11->total_requested_pieces; break;} } ?>);

                      $('#td3{{$iec->id}}').append(<?php foreach($aiecinventoriesEndingBalance as $inventoryiec2) { if($inventoryiec2->iec_update_id == $iec->id) {echo number_format($inventoryiec2->iec_current_threshold); break; } } ?>);
                     }
                   });
                </script>
 
                @break
              @endif  
            @endforeach
 
          @break
          @endif
          @endforeach

 
             </tr>
           @endforeach
         </tbody>
      </table>

  <div class="row">
 <div class="col-md-2">
    <button id="btn_aprint_allrecIec" class="btn btn-info">PRINT SELECTED</button>    
 </div>
 </form>
<div class="col-md-2">
  <form action="/admin/iecs/print-all/1" method="POST" class="form-horizontal">
        {{ csrf_field() }}
    @foreach($iecs as $iec)
     <input type="hidden" name="chk_selectOneIec[]" value="{{$iec->id}}">
    @endforeach
    <input type="hidden" name="txt_iec_date_from" value="{{$date_range_from}}">
    <input type="hidden" name="txt_iec_date_to" value="{{$date_range_to}}">
    <button id="btn_aprint_allrecIec" class="btn btn-success">PRINT ALL</button>    
</form>
   </div>
  </div>
</div> <!-- /.box-body -->
</form>
@endif
<!-- End IEC List -->

<!--Audit Logs List -->
      @if(isset($selected) == 5 && isset($logs))
  <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Audit Logs</h3>
      </div>
<div class="box-body">
<form action="/admin/audit-logs/print-all/1" method="POST" class="form-horizontal">
        {{ csrf_field() }}
     <h4 style="text-align: left !important"> Period Covered: @php echo date('M d, Y', strtotime($date_range_from)); @endphp - @php echo date('M d, Y', strtotime($date_range_to)); @endphp</h4>
    <input type="hidden" name="txt_iec_date_from" value="{{$date_range_from}}">
    <input type="hidden" name="txt_iec_date_to" value="{{$date_range_to}}">
     <table class="display table table-bordered" id="tblHistory" style="width:100% !important;"> 
    <thead>
    <tr>
      <th></th>
      <th>Date</th>
      <th>Module</th>
      <th>Activity</th>
      <th>Field Affected</th>
      <th>Old Value</th>
      <th>New Value</th>      
      <th>Modified By</th>
    </tr>
  </thead>
  @foreach($logs as $log)
    <tr>
      <td>
        <input type="checkbox" name="chk_selectOneLog[]" id="chk_selectOneLog{{$log->audit_id}}" value="{{$log->audit_id}}">
      </td>
      <td>@php echo date('M d, Y h:i:s', strtotime($log->created_at)); @endphp</td>
      <td>{{$log->audit_module}}</td>
      <td>{{$log->audit_activity}}</td>
      <td>{{$log->audit_field_affected}}</td>
      <td>{{$log->audit_old_value}}</td>
      <td>{{$log->audit_new_value}}</td>
      <td>{{$log->name}}</td>
    </tr>
   @endforeach
  </table>
         <button type="submit" name="btn_print_allrec{{$log->audit_id}}" id="btn_print_allrecLog" class="btn btn-success" style="display:none;">Print</button>   
<script type="text/javascript">
$(document).ready(function () {
    $('#btn_print_allrecLog').click(function () {
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
      $('#btn_print_allrecLog').click();
    }
  });
//end ---- print request history select all and print
});
</script>
<div class="row">
 <div class="col-md-2">
    <button id="btn_aprint_allrecLog" class="btn btn-info">PRINT SELECTED</button>    
 </div>
</form>
<div class="col-md-2">
  <form action="/admin/audit-logs/print-all/2" method="POST" class="form-horizontal">
        {{ csrf_field() }}
    @foreach($logs as $log)
    @php 
    @endphp
    <input type="hidden" name="chk_selectOneLog[]" value="{{$log->audit_id}}">
    @endforeach
    <input type="hidden" name="txt_iec_date_from" value="{{$date_range_from}}">
    <input type="hidden" name="txt_iec_date_to" value="{{$date_range_to}}">    
    <button id="btn_aprint_allrecLog" class="btn btn-success">PRINT ALL</button>    
    </form>
    </div> <!-- /.box-body -->
  </div>
    @endif
<!--End Audit Logs -->

  <!--Inventory History !-->
      @if(isset($selected) == 6 && isset($inventories))
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Inventory History</h3>
            </div>
            <div class="box-body">
 

<form action="/admin/inventory-history/print-all/1" method="POST" class="form-horizontal">
        {{ csrf_field() }}
     <h4 style="text-align: left !important"> Period Covered: @php echo date('M d, Y', strtotime($date_range_from)); @endphp - @php echo date('M d, Y', strtotime($date_range_to)); @endphp</h4>
    <input type="hidden" name="txt_iec_date_from" value="{{$date_range_from}}">
    <input type="hidden" name="txt_iec_date_to" value="{{$date_range_to}}">
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
            <th>Title</th>
            <th>Inventory Type</th>
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
          <tr>
            <td style="display: none;">{{$inventory->iec_update_id}}</td>
            <td>
              <input type="checkbox" name="chk_selectOneIec[]" id="chk_selectOneIec{{$inventory->history_id}}" value="{{$inventory->history_id}}">
            </td>
            <td style="text-align:left !important;">{{$inventory->iec_title}}</td>
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
            <td>@php echo number_format($inventory->iec_update_threshold); @endphp</td>
            <td>@php echo number_format($inventory->iec_update_pieces); @endphp</td>
            <td>@php echo number_format($inventory->iec_current_threshold); @endphp</td>
            <td>@php 
              echo date('M d, Y h:i:s', strtotime($inventory->created_at)); @endphp
            </td>
            <td>{{$inventory->name}}</td>
            <td>{{$inventory->iec_update_remarks}}</td>
           </tr>
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
      $('#btn_print_allrecIec').click();
    }
  });
//end ---- print request history select all and print
});
</script>
<div class="row">
 <div class="col-md-2">
    <button id="btn_aprint_allrecIec" class="btn btn-info">PRINT SELECTED</button>    
 </div>
</form>
<div class="col-md-2">
  <form action="/admin/inventory-history/print-all/2" method="POST" class="form-horizontal">
        {{ csrf_field() }}
    <input type="hidden" name="txt_iec_date_from" value="{{$date_range_from}}">
    <input type="hidden" name="txt_iec_date_to" value="{{$date_range_to}}">
    @foreach($inventories as $inventory)
    @php 
    @endphp
    <input type="hidden" name="chk_selectOneIec[]" value="{{$inventory->history_id}}">
    @endforeach
    
    <!-- fixed print all inventory history -chano -->
    <button id="btn_aprint_allrecIec" class="btn btn-success">PRINT ALLx</button>    
</form>
</div>
</div> 
            </div>
            <!-- /.box-body-->
          </div>
          <!-- /.box -->
        @endif

  <!--End Inventory History !-->

  <!--Printing Logs !-->
        @if(isset($selected) == 7 && isset($printinglogs))
          <div class="box-body">
            <div class="box-header with-border">
              <h3 class="box-title">Reprinting History</h3>
            </div>
            <div class="box-body">
        <form action="/admin/printing-logs/print-all/1" method="POST" class="form-horizontal">
        {{ csrf_field() }}
     <h4 style="text-align: left !important"> Period Covered: @php echo date('M d, Y', strtotime($date_range_from)); @endphp - @php echo date('M d, Y', strtotime($date_range_to)); @endphp</h4>
    <input type="hidden" name="txt_iec_date_from" value="{{$date_range_from}}">
    <input type="hidden" name="txt_iec_date_to" value="{{$date_range_to}}">
        <table class="display table table-bordered" id="tblHistory" style="width:100% !important;">
        <thead>
          <tr>
            <th>
            </th>
            <th>title</th>
            <th>Copyright</th>
            <th>Printing Date</th>
            <th>Title</th>
            <th>Printing Contractor</th>
            <th>Printing Cost/Piece</th>
            <th>Pc(s)</th>
            <th>Remarks</th>
           </tr>
        </thead>
          @foreach($printinglogs as $printinglog)
          <tr>
           <td>
            <center>
            <input type="checkbox" name="chk_selectOne[]" id="chk_selectOne{{$printinglog->recordid}}" value="{{$printinglog->recordid}}">
            </center>
           </td>
            <td style="text-align:left !important;">{{$printinglog->iec_title}}</td>
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
          @endforeach 
      </table>
         <button type="submit" name="btn_print_allrec" id="btn_print_allrec" class="btn btn-success" style="display:none;">Print</button>   
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
  </form>

  <div class="col-md-2">
  <form action="/admin/printing-logs/print-all/1" method="POST" class="form-horizontal">
  {{ csrf_field() }}
  @foreach($printinglogs as $printinglog)
    @php 
    @endphp
    <input type="hidden" name="chk_selectOne[]" value="{{$printinglog->recordid}}">
    <input type="hidden" name="txt_iec_date_from" value="{{$date_range_from}}">
    <input type="hidden" name="txt_iec_date_to" value="{{$date_range_to}}">
    @endforeach
    <button id="btn_aprint_allrecIec" class="btn btn-success">SELECT ALL</button></form>
  </div>
</div>  
          </div>
            <!-- /.box-body-->
          </div>
        @endif
           <!-- /.box -->
    <!--End Printing Logs !-->

<!-- Request History-->
 @if(isset($selected) == 8 && isset($transactions))
 <div class="box-body">
    <div class="box-header with-border">
      <h3 class="box-title">Request History</h3>
    </div>
  <form action="/admin/print-all-requests/1" method="POST" class="form-horizontal">
    {{ csrf_field() }}
    <!-- Request history tab -chano -->
    <h4 style="text-align: left !important"> Period Coveredtest: @php echo date('M d, Y', strtotime($date_range_from)); @endphp - @php echo date('M d, Y', strtotime($date_range_to)); @endphp</h4>
    <input type="hidden" name="txt_iec_date_from" value="{{$date_range_from}}">
    <input type="hidden" name="txt_iec_date_to" value="{{$date_range_to}}">
    <table class="display table table-bordered" id="tblHistory" style="width:100% !important;">
    <thead>
    <tr>
      <th>
      </th>
      <th style="display:none;">ID</th>
      <th>Trans No.</th>
      <th>Date/time of Request</th>
      <th>Client Name</th>
      <th>Organization Name</th>
      <th>Title</th>
      <th>Pcs</th>
      <th>Staff Incharge</th>
    </tr>
  </thead>
            @foreach($transactions as $transaction)  
            <tr>
              <td>
                <center>
                <input type="checkbox" name="chk_selectOneRequest[]" id="chk_selectOneRequest{{$transaction->recordID}}" value="{{$transaction->recordID}}">
              </center>
              </td> 
              <td style="display:none;"></td>
              <td>{{$transaction->requestID}}</td>
              <td>@php echo date('M d, Y h:i A', strtotime($transaction->created_at)); @endphp</td>
              <td>{{$transaction->client_name}}</td>
              <td>{{$transaction->organization_name}}</td>
              <td style="text-align:left !important;">{{$transaction->iec_title}}</td>
              <td>
                @php echo number_format($transaction->request_material_quantity); 
                @endphp
              </td>
              <td>{{$transaction->name}}</td>
            </tr> 
             @endforeach
      </table> 
<div class="row">
 <div class="col-md-2">
    <button id="btn_aprint_all" class="btn btn-info">PRINT SELECTED</button>    
 </div>
</form>
<div class="col-md-2">
  <form action="/admin/print-all-requests/2" method="POST" class="form-horizontal">
    {{ csrf_field() }}
    @foreach($transactions as $transaction) 
    <input type="hidden" name="chk_selectOneRequest[]" value="{{$transaction->recordID}}"> 
    @endforeach
    <input type="hidden" name="txt_iec_date_from" value="{{$date_range_from}}">
    <input type="hidden" name="txt_iec_date_to" value="{{$date_range_to}}">    
    <button id="btn_aprint_all" class="btn btn-success">PRINT ALL</button>    
    </form>
    </div> <!-- /.box-body -->
</div>
 </div>
 @endif
<!-- end Request History-->


  <!--IEC Materials Inventory Report!-->
      @if(isset($selected) == 9 && isset($iecinventories))
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">IEC Materials Inventory Report</h3>
            </div>
            <div class="box-body">
 

<form action="/admin/inventoryiec-history/print-all/1" method="POST" class="form-horizontal">
        {{ csrf_field() }}
     <h4 style="text-align: left !important"> Period Covered: @php echo date('M d, Y', strtotime($date_range_from)); @endphp - @php echo date('M d, Y', strtotime($date_range_to)); @endphp</h4>
    <input type="hidden" name="txt_iec_date_from" value="{{$date_range_from}}">
    <input type="hidden" name="txt_iec_date_to" value="{{$date_range_to}}">
     <table class="display table table-bordered" id="table" style="width:100% !important;">
        <thead>
          <tr>
            <th style="display: none;">ID</th>
            <th  style="background-color:#00c0ef !important;">
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
            <th  style="background-color:#00c0ef !important;">Title</th>
            <th  style="background-color:#00c0ef !important;">Beginning Balance</th>
            <th  style="background-color:#00c0ef !important;">No. of Materials Released</th>
            <th  style="background-color:#00c0ef !important;">Ending Balance</th>
           </tr>
        </thead>
        <tbody>
          @foreach($aiecs as $iec)

          <tr>
            <td style="display: none;">{{$iec->id}}</td>
            <td>
              <input type="checkbox" name="chk_selectOneIec[]" id="chk_selectOneIec{{$iec->id}}" value="{{$iec->id}}">
            </td>
            <td style="text-align:left !important;">
              {{$iec->iec_title}}</td>
            
              <input type="hidden" id="txt{{$iec->id}}" value="0">
            <td id="td{{$iec->id}}">{{$iec->iec_threshold}}</td>
            <td id="td2{{$iec->id}}">0

            <?php foreach($iecinventoriesPieces as $inventoryiec11) { if($iec->id == $inventoryiec11->iec_update_id) { echo $inventoryiec11->total_requested_pieces; } } ?>
              

            </td>
            <td id="td3{{$iec->id}}">{{$iec->iec_threshold}}</td>
              @foreach($iecinventoriesEndingBalance as $endbalance)
              @if($iec->id == $endbalance->iec_update_id)
            @foreach($iecinventories as $inventoryiec)
            @php
            $acount = [];
            @endphp
              @if($inventoryiec->iec_update_id == $iec->id)
                <script type="text/javascript">
                 $(document).ready(function(){
                  $('#txt{{$iec->id}}').val({{$inventoryiec->iec_update_threshold}});
                     if($('#txt{{$iec->id}}').val()!= null){
                      $('#td{{$iec->id}}').empty();
                      $('#td2{{$iec->id}}').empty();
                      $('#td3{{$iec->id}}').empty();
                      $('#td{{$iec->id}}').append({{$inventoryiec->iec_update_threshold}});

                      $('#td2{{$iec->id}}').append(<?php foreach($iecinventoriesPieces as $inventoryiec11) { if($iec->id == $inventoryiec11->iec_update_id) {echo $inventoryiec11->total_requested_pieces; break;} } ?>);

                      $('#td3{{$iec->id}}').append(<?php foreach($iecinventoriesEndingBalance as $inventoryiec2) { if($inventoryiec2->iec_update_id == $iec->id) {echo number_format($inventoryiec2->iec_current_threshold); break; } } ?>);
                     }
                   });
                </script>
 
                @break
              @endif  
            @endforeach
 
          @break
          @endif
          @endforeach

 
             </tr>
           @endforeach
         </tbody>
      </table>
         <button type="submit" name="btn_print_allrec{{$iec->id}}" id="btn_print_allrecIec" class="btn btn-success" style="display:none;">Print</button>   
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
      $('#btn_print_allrecIec').click();
    }
  });
//end ---- print request history select all and print
});
</script>
<div class="row">
 <div class="col-md-2">
    <button id="btn_aprint_allrecIec" class="btn btn-info">PRINT SELECTED</button>    
 </div>
</form>
<div class="col-md-2">
  <form action="/admin/inventoryiec-history/print-all/2" method="POST" class="form-horizontal">
        {{ csrf_field() }}
    @foreach($aiecs as $iec)
    @php 
    @endphp
    <input type="hidden" name="chk_selectOneIec[]" value="{{$iec->id}}">
    @endforeach
    <input type="hidden" name="txt_iec_date_from" value="{{$date_range_from}}">
    <input type="hidden" name="txt_iec_date_to" value="{{$date_range_to}}">
    <button id="btn_aprint_allrecIec" class="btn btn-success">PRINT ALL</button>    
</form>
</div>
</div> 
            </div>
            <!-- /.box-body-->
          </div>
          <!-- /.box -->
        @endif

  <!--End IEC Materials Inventory Report !-->
      </div>
    </div> <!-- /.box-body -->
    <div class="box-footer">  </div> <!-- /.box-footer -->
</div> <!-- /.box box-primary -->

@endsection

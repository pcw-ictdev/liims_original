@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include('layouts.inc.messages')
<script src="/js/jquery-1.12.4.min.js"></script>
<script src="/js/form-scripts.js"></script>
<style>
  #table th {background-color:#00c0ef; !important;}
</style>

<script type="text/javascript">
$(document).ready(function() {
 $('#table').DataTable({
   "order": [[ 1, "desc" ]]
  })
});

</script>
<div class="box box-primary">
  <div class="box-header with-border">

  <!-- header success notification -->
<div class="form-group"  id="div-success-notification" style="display: none;">
    <div class="alert alert-success alert-dismissible" id="divMessage">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <p><i class="fa fa-check"></i>&nbsp;
           <label id="lbl-success-message"></label>            
        </p>
    </div> <!-- /.alert -->
</div> <!-- /.form-group -->
<!-- header error notification -->

<!-- header error notification -->
<div class="form-group" id="div-error-notification" style="display: none;">
    <div class="alert alert-danger alert-dismissible" id="divMessage">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <p><i class="fa fa-warning"></i>&nbsp;
        <label id="lbl-error-message"></label>
        </p>
    </div> <!-- /.alert -->
</div> <!-- /.form-group -->
<!-- end header error notification -->

      <div class="row">
      <div class="col-md-6">
        <h4>Filter</h4>
      </div>
      </div>
      <form method="POST" action="/admin/requests" class="form-horizontal">
      {{ csrf_field() }}
      <div class="row">
        <div class="col-xs-3">
        Date From
        <input type="date" class="form-control input-xs" name="txt_iec_date_from" id="txt_iec_date_from"> 
        </div>
        <div class="col-xs-3">
        Date To 
        <input type="date" class="form-control input-xs" name="txt_iec_date_to" id="txt_iec_date_to">
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
    <hr>
  </div>
  <!-- /.box-header -->
    <div class="box-body">
  <form action="/admin/print-all-requests/1" method="POST" class="form-horizontal">
    {{ csrf_field() }}
      <table class="table table-bordered" style="display:table; width:100%;" id="table"> 
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
              <td style="text-align: left !important;">{{$transaction->iec_title}}</td>
              <td>{{$transaction->request_material_quantity}}</td>
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
    
    <button id="btn_aprint_all" class="btn btn-success">PRINT ALL</button>    
    </form>
    </div> <!-- /.box-body -->
</div>
 </div>
</div> <!-- /.box box-primary -->

@endsection

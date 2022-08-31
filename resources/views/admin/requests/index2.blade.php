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
@if(isset($transactions) || isset($transactiondate))      
  <form  action="/admin/print-all-requests/1"  method="POST" class="form-horizontal">
@endif
 
  {{ csrf_field() }}
      <table class="table table-bordered" style="display:table; width:100%;" id="table"> 
    <thead>
    <tr>
      <th>
      <center>
        Select All
        <input id = 'approveAll' type='checkbox' name ='approveAll' onchange='selectAll()'>
      </center>
      </th>
      <th>Trans No.</th>
      <th>Date/time of Request</th>
      <th>Client Name</th>
      <th>Organization Name</th>
      <th>Title</th>
      <th>Pcs</th>
      <th>Staff Incharge</th>
    </tr>
  </thead>
          @if(isset($transactions))
            @foreach($transactions as $transaction)  
            <tr>
              <td>
                <center>
                <input type="checkbox" name="chk_selectOne[]" id="chk_selectOne{{$transaction->rec_id}}" value="{{$transaction->rec_id}}">
              </center>

              <script type="text/javascript">
                $(document).ready(function() {
                  $('#chk_selectOne{{$transaction->rec_id}}').click(function(){
                    $('#approveAll').removeAttr('checked','false');
                });
              });
              </script>
              </td> 
              <td>{{$transaction->request_id}}</td>
              <td>@php echo date('M d, Y h:i A', strtotime($transaction->created_at)); @endphp</td>
              <td>{{$transaction->client_name}}</td>
              <td>{{$transaction->organization_name}}</td>
              <td>{{$transaction->iec_title}}</td>
              <td>{{$transaction->request_material_quantity}}</td>
              <td>{{$transaction->name}}</td>
            </tr> 
             @endforeach
            @endif
          @if(isset($transactiondate))
            @foreach($transactiondate as $transaction)  
            <tr>
              <td>
                <center>
                <input type="checkbox" name="chk_selectOne[]" id="chk_selectOne{{$transaction->rec_id}}" value="{{$transaction->rec_id}}">
              </center>

              <script type="text/javascript">
                $(document).ready(function() {
                  $('#chk_selectOne{{$transaction->rec_id}}').click(function(){
                    $('#approveAll').removeAttr('checked','false');
                });
              });
              </script>
              </td> 
              <td>{{$transaction->request_id}}</td>
              <td>@php echo date('M d, Y h:i A', strtotime($transaction->created_at)); @endphp</td>
              <td>{{$transaction->client_name}}</td>
              <td>{{$transaction->organization_name}}</td>
              <td>{{$transaction->iec_title}}</td>
              <td>{{$transaction->request_material_quantity}}</td>
              <td>{{$transaction->name}}</td>
            </tr> 
             @endforeach
            @endif
      </table>
    @if(isset($transactions))
      <button type="submit" name="btn_print_all" id="btn_print_all" class="btn btn-info" style="display:none;">Print</button>
    @endif  
    @if(isset($transactiondate))
      <button type="submit" name="btn_print_all" id="btn_print_all" class="btn btn-info" style="display:none;">Print</button>
    @endif      
    </form>      
    @if(isset($transactions)) 
      <button id="btn_aprint_all" class="btn btn-success">Print selected record</button>
    </div> <!-- /.box-body -->
    <div class="box-footer">
      <button class="btn btn-info form-control" id="btnPrintAll" style="display: none;">
        Print All
    </button>
    @endif
    @if(isset($transactiondate)) 
      <button id="btn_aprint_all" class="btn btn-success">Print selected record</button>
    </div> <!-- /.box-body -->
    <div class="box-footer">
      <button class="btn btn-info form-control" id="btnPrintAll" style="display: none;">
        Print All
    </button>
    @endif
          <input type="hidden" id="txtPrintOne"> 
  </div> <!-- /.box-footer -->
</div> <!-- /.box box-primary -->

@endsection

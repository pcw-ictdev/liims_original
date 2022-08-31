@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include('layouts.inc.messages')
<script src="/js/jquery-1.12.4.min.js"></script>
<script src="/js/form-scripts.js"></script>
<div class="box box-primary">
  <div class="box-header with-border">
    <div class="col-md-6">
    <h4>IEC Materials Logs</h4>
    </div>
</div>
  <!-- /.box-header -->

    <div class="box-body">
      <div class="row">
      <div class="col-md-6">
        <h4>Filter</h4>
      </div>
      </div>
      <form method="POST" action="/admin/iec/printing-logs/date" class="form-horizontal">
      {{ csrf_field() }}
      <div class="row">
        <div class="col-xs-3">
        Date From
        <input type="date" class="form-control input-xs" name="txt_iec_printing_date_from" id="txt_iec_printing_date_from"> 
        </div>
        <div class="col-xs-3">
        Date To 
        <input type="date" class="form-control input-xs" name="txt_iec_printing_date_to" id="txt_iec_printing_date_to">
        </div>
        <div class="col-xs-1">
        <br>
        <button class="btn btn-info" id="btn_iec_printing_find">Find</button> 
        </div>
        <div class="col-xs-1">
         &nbsp;
        </div>
      </form>
       </div>
    <hr>
      @if(isset($printings))    
        <form method="POST" action="/admin/printing-logs/print-all/" class="form-horizontal">
        {{ csrf_field() }}
      @endif
      <table class="table table-bordered">
        <thead>
      @if(isset($printings))
      <th>
          <center>
            Select All
            <input id = 'approveAll' type='checkbox' name ='approveAll' onchange='selectAll()'>
          </center>
      </th>
      @endif
          <th>Date Received</th>
          <th>Contractor/Donor</th>
          <th>Printing Cost</th>
          <th>Pcs</th>
          <th>Remarks</th>
          <th>Created by</th>
          <th>Date & Time Created</th>
        </thead>
        <tbody>
@if(isset($printings))
          @foreach($printings as $printing)
          <tr>
            @if(isset($printings))
           <td>
            <center>
            <input type="checkbox" name="chk_selectOne[]" id="chk_selectOne{{$printing->id}}" value="{{$printing->id}}">
            </center>

           <script type="text/javascript">
             $(document).ready(function() {
               $('#chk_selectOne{{$printing->id}}').click(function(){
                     $('#approveAll').removeAttr('checked','false');
             });
           });
           </script>
           </td>
           @endif
            <td>@php echo date('M d, Y', strtotime($printing->iec_printing_date)); @endphp</td>
            <td style="text-align: left !important;">{{$printing->contractor_name}}</td>   
            <td> {{$printing->iec_printing_cost}} </td>         
            <td>{{$printing->iec_printing_pcs}}</td>
            <td>{{$printing->iec_printing_remarks}}</td>
            <td>{{$printing->name}}</td>
            <td>@php 
              echo date('M d, Y', strtotime($printing->created_at)); 
              echo '<br>';
              echo date('h:i:s', strtotime($printing->created_at));
            @endphp</td>
          </tr>
          @endforeach
@else
          @foreach($printingresults as $printing)
          <tr>
           <td>
            <center>
            <input type="checkbox" name="chk_selectOne[]" id="chk_selectOne{{$printing->id}}" value="{{$printing->id}}">
            </center>

           <script type="text/javascript">
             $(document).ready(function() {
               $('#chk_selectOne{{$printing->id}}').click(function(){
                     $('#approveAll').removeAttr('checked','false');
             });
           });
           </script>
           </td> 
            <td>@php echo date('M d, Y', strtotime($printing->iec_printing_date)); @endphp</td>
            <td>{{$printing->iec_printing_contractor}}</td>   
            <td> {{$printing->iec_printing_cost}} </td>         
            <td>{{$printing->iec_printing_pcs}}</td>
            <td>{{$printing->iec_printing_remarks}}</td>
            <td>{{$printing->name}}</td>
            <td>@php 
              echo date('M d, Y', strtotime($printing->created_at)); 
              echo '<br>';
              echo date('h:i:s', strtotime($printing->created_at));
            @endphp</td>
          </tr>
          @endforeach
@endif



        </tbody>
      </table>

    @if(isset($printings))
      <button type="submit" name="btn_print_all" id="btn_print_all" class="btn btn-info" style="display:none;">Print</button>
    @endif      
    </form>      
    @if(isset($printings)) 
      <button id="btn_aprint_all" class="btn btn-success">Print selected record</button>
    </div> <!-- /.box-body -->
    <div class="box-footer">
      <button class="btn btn-info form-control" id="btnPrintAll" style="display: none;">
        Print All
    </button>
    @endif

          <input type="hidden" id="txtPrintOne"> 

    </div> <!-- /.box-body -->
    <div class="box-footer"></div> <!-- /.box-footer -->
</div> <!-- /.box box-primary -->

@endsection

@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include('layouts.inc.messages')
<script src="/js/jquery-1.12.4.min.js"></script>
<style>
  #table th {background-color:#00c0ef; !important;}
</style>
<div class="box box-primary">
  <div class="box-header with-border">
    <div class="col-md-6">
    <h4></h4>
    </div>
</div>
  <!-- /.box-header -->
    <div class="box-body">
      <table id="table" class="table table-bordered">
        <thead>
          <th>Date & Time Created</th>
          <th>User</th>
          <th>Type</th>
          <th>Title</th>
          <th>Qty.</th>
          <th>Qty. Before Update</th>
          <th>Remarks</th>
        </thead>
        <tbody>
          @foreach($iecs as $iec)
          <tr>
            <td>@php echo date('M d, Y - h:i:s A', strtotime($iec->created_at)); @endphp</td>
            <td>{{$iec->name}}</td>   
            <td>
              @if($iec->iec_update_type == 1)Restocked @endif
              @if($iec->iec_update_type == 2)Adjusted @endif
              @if($iec->iec_update_type == 3)Update Details @endif
            </td>         
            <td>{{$iec->iec_title}}</td>
            <td>{{$iec->iec_update_pieces}}</td>
            <td>{{$iec->iec_update_threshold}}</td>
            <td>{{$iec->iec_update_remarks}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div> <!-- /.box-body -->
    <div class="box-footer"></div> <!-- /.box-footer -->
</div> <!-- /.box box-primary -->

@endsection
 
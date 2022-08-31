@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include('layouts.inc.messages')
<script src="/js/jquery-1.12.4.min.js"></script>
<div class="box box-primary">
  <div class="box-header with-border">

</div>
  <!-- /.box-header -->
    <div class="box-body">
      <table id="table" class="table table-bordered"> 
    <thead>
    <tr>
      <th>Date</th>
      <th>Module</th>
      <th>Modified By</th>
      <th>Action</th>
    </tr>
  </thead>
  @foreach($logs as $log)
    <tr>
      <td>{{$log->activity_id_no}}</td>
      <td>{{$log->activity_module}}</td>
      <td>{{$log->name}}</td>
      <td>
        <a href="#" id="viewLog{{ $log->activity_id_no }}"  data-toggle="modal" data-target="#ModalView{{ $log->activity_id_no }}" title="Edit"value="{{$log->activity_id_no}}">View</a> 

 <!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="ModalView{{ $log->activity_id_no }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
       <h5><b>Audit Log Details</b></h5>
      <div class="box-body">
      <table class="table table-bordered">
       <thead>
        <tr>
            <th>Activity</th>
            <th>Field Affected</th>
            <th>Old Value</th>
            <th>New Value</th>
            <th>Modified By</th>
        </tr>  
        </thead>
        <tbody>       
          <tr>
            <td>{{$log->activity_title}}</td>
            <td>{{$log->activity_field}}</td>
            <td>{{$log->activity_old_value}}</td>
            <td>{{$log->activity_new_value}}</td>
            <td>{{$log->name}}</td>
          </tr>
        </tbody>
        </table>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
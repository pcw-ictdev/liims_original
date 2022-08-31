@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include('layouts.inc.messages')
<script src="/js/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
 $('#table').DataTable({
   "order": [[ 0, "desc" ]]
  })
});

</script>
<style>
  #table th {background-color:#00c0ef; !important;}

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
    <div class="col-md-1">
    </div>
</div>
  <!-- /.box-header -->
    <div class="box-body">

      <table id="table" class="table table-bordered"> 
    <thead>
    <tr>
      <th style="display:none !important;">ID</th>
      <th>DATE</th>
      <th>MODULE</th>
      <th>ACTIVITY</th>
      <th>Modified By</th>
      <th>Action</th>
    </tr>
  </thead>
  @foreach($logs as $log)


    <tr>
      <td style="display:none !important;">{{$log->audit_id}}</td>
      <td>@php echo date('M d, Y, h:i:s A', strtotime($log->created_at)); @endphp</td>
      <td>{{$log->audit_module}}</td>
      <td>{{$log->audit_activity_title}}</td>
      <td>{{$log->name}}</td>
      <td>
        <label class="btn btn-info" data-toggle="modal" data-target="#ModalLog{{$log->audit_id}}"  title="View" id="btnModal{{$log->audit_id}}"> <i class="fa fa-eye" aria-hidden="true"> </i></label>
 
 <!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="ModalLog{{$log->audit_id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5><b>Audit Log Details</b></h5>
        <div class="box-body">
          <table class="display table table-bordered" id="tblHistory">
            <thead>
              <tr>
                <th>Field Affected</th>
                <th>Old Value</th>
                <th>New Value</th>
                <th>Modified By</th>
              </tr>
            </thead>
            <tbody>
                @foreach($logs as $logg)
                @if($logg->audit_id == $log->audit_id)
              <tr>
                <td>{{$logg->audit_field_affected}}</td>
                <td>{{$logg->audit_old_value}}</td>
                <td>{{$logg->audit_new_value}}</td>
                <td>{{$logg->name}}</td>
              </tr>
              @endif
              @endforeach
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
    <div class="box-footer">  </div> <!-- /.box-footer -->
</div> <!-- /.box box-primary -->

@endsection




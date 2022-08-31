@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include('layouts.inc.messages')
<script src="/js/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
  $('#divCreateNewButton').empty();
  $('#divCreateNewButton').append('<br><center><a href="/admin/files/create" class="btn btn-success">CREATE NEW</a></center>');
  });
</script>
<script type="text/javascript">
$(document).ready(function() {
 $('#table').DataTable({
   "order": [[ 2, "asc" ]]
  })
});

</script>

<style>
  #table th {background-color:#00c0ef; !important;}
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
      <th>ID</th>
      <th>Version No</th>
      <th>File</th>
      <th>Action</th>
    </tr>
  </thead>
  @foreach($ecopies as $ecopy)
    @if($ecopy->ecopy_status != 2)
        <tr>
      @else
        <tr style="background-color:#fcc0c0 !important;">    
      @endif
      <td>{{$ecopy->ecopy_id}}</td>
      <td>{{$ecopy->ecopy_version_no}}</td>
      <td>{{$ecopy->iec_title}}</td>
      <td>
        @if($ecopy->ecopy_status == 2) <b>Deleted</b> @endif
        @if($ecopy->ecopy_status != 2)
        <a href="/admin/files/edit/{{$ecopy->ecopy_id}}">Edit</a> 
 
        | <a href="/admin/files/delete/{{$ecopy->ecopy_id}}" onclick="return confirm('Are you sure to delete this record?')">Delete</a> 
        @endif
    </td>
    </tr>
   @endforeach
  </table>
    </div> <!-- /.box-body -->
    <div class="box-footer">  </div> <!-- /.box-footer -->
</div> <!-- /.box box-primary -->

@endsection








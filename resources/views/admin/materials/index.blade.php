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
  $('#divCreateNewButton').append('<br><center><a href="/admin/assets/create" class="btn btn-success">CREATE NEW</a></center>');
  });
</script>

<script type="text/javascript">
$(document).ready(function() {
 $('#table').DataTable({
   "order": [[ 0, "asc" ]]
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
 </div>
  <!-- /.box-header -->
    <div class="box-body">
      <table id="table" class="table table-bordered"> 
    <thead>
    <tr>
      <th>Asset Type</th>
      <th style="width:25%;">Description</th>
      <th>Created By</th>
      <th>Action</th>
    </tr>
  </thead>
  @foreach($materials as $material)
 @if($material->is_deleted != 1)
    <tr>
  @else
    <tr style="background-color:#fcc0c0 !important;">    
  @endif
      <td>{{$material->material_name}}</td>
      <td>{{$material->material_desc}}</td>
      <td>{{$material->name}}</td>
      <td>
        @if($material->is_deleted == 1) <b>Deleted</b> @endif

        @if($material->is_deleted != 1)
          <a href="/admin/assets/edit/{{$material->id}}" >Edit</a>  
          @php $asd = 0; @endphp

          @foreach ($materialsRequestsLists as $element)
            @if($element->materialID == $material->id)
            @php $asd = 1; @endphp   
            @endif

          @endforeach    
          @if($asd == 0)
          | <a href="/admin/assets/delete/{{$material->id}}" onclick="return confirm('Are you sure to delete this record?')">Delete</a> 
          @endif
        @endif

 
      </td>
    </tr>
   @endforeach
  </table>
    </div> <!-- /.box-body -->
    <div class="box-footer"></div> <!-- /.box-footer -->
</div> <!-- /.box box-primary -->

@endsection
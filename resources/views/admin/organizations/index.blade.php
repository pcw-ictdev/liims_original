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
  $('#divCreateNewButton').append('<br><center><a href="/admin/organizations/create" class="btn btn-success">CREATE NEW</a></center>');
  });
</script>

<script type="text/javascript">
$(document).ready(function() {
 $('#table').DataTable({
   "order": [[ 1, "asc" ]]
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
      <th style="display:none;">ID</th>
      <th>Organization</th>
      <th>Organization Type</th>
      <th>Address</th>
      <th>Created By</th>
      <th>Action</th>
    </tr>
  </thead>
  @foreach($organizations as $organization)
    @if($organization->is_deleted != 1)
        <tr>
      @else
        <tr style="background-color:#fcc0c0 !important;">    
      @endif
      <td style="display:none;">{{$organization->id}}</td>
      <td>{{$organization->organization_name}}</td>
      <td>{{$organization->org_type_code}}</td>
      <td>{{$organization->organization_address}}</td>
      <td>{{$organization->name}}</td>
      <td>
        @if($organization->is_deleted == 1) <b>Deleted</b> @endif

        @if($organization->is_deleted != 1)
          <a href="/admin/organizations/edit/{{$organization->id}}">Edit</a>  
          @php $asd = 0; @endphp

          @foreach($organizationsLists as $element)
            @if($element->orgID == $organization->id)
            @php $asd = 1; @endphp   
            @endif

          @endforeach    
          @if($asd == 0)
          | <a href="/admin/organizations/delete/{{$organization->id}}" onclick="return confirm('Are you sure to delete this record?')">Delete</a> 
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
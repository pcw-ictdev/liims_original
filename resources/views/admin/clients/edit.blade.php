@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include ('layouts.inc.messages')
<script src="/js/form-scripts.js"></script>
 <script type="text/javascript">
  $(document).ready(function(){
    $('#cancel1').click(function(){

    if($('#txt_clients_name').val()!= ''){
        $('#cancel2').click();
    } else if($('#txt_clients_organization').val()!= ''){
        $('#cancel2').click();
    }  else if($('#txt_clients_designation').val()!= ''){
        $('#cancel2').click();
    } else if($('#txt_clients_contact_no').val()!= ''){
        $('#cancel2').click();
    } else {
      window.location.href = "{{ url()->previous() }}";   
    }
   });
});
   function btnCancelFunction() {
  var r = confirm("Do you want to cancel?");
  if (r == true) {
   window.location.href = "{{ url()->previous() }}";
  } 
}
 </script>
<div class="box box-primary">
   <form method="POST" action="/admin/clients/update/{{ $clients->id }}" class="form-horizontal">
  {{ csrf_field() }}
  <div class="box-header with-border">
    <h4><b></b></h4>
  </div> <!-- /.box-header -->
  <div class="box-body">
    <div class="form-group">
      <div class="col-md-2">
      <label style="text-align:left !important;"> Client's Name <b style="color:#FF0000; font-size:18px;">*</b> </label>
      </div>
      <div class="col-md-6">
       <input type="text" class="form-control" name="txt_clients_name" placeholder="Client's Name" value="{{$clients->client_name}}"required>
      </div>
    </div>
  <div class="form-group">
     <div class="col-md-2">
        <label  style="text-align:left !important;">Agency/Organization <b style="color:#FF0000; font-size:18px;">*</b> </label>
     </div>
      <div class="col-md-4">
      <select class="form-control" name="txt_clients_organization" id="txt_clients_organization" required>
        <option value="{{$clients->client_organization}}">{{$clients->organization_name}}</option>
        @foreach($organizations as $organization)
        <option value="{{$organization->id}}">{{$organization->organization_name}}</option>
        @endforeach
      </select>
      </div>
    </div>
  <div class="form-group">
     <div class="col-md-2">
        <label style="text-align:left !important;">Designation <b style="color:#FF0000; font-size:18px;">*</b> </label>
     </div>
      <div class="col-md-6">
      <input type="text" name="txt_clients_designation" id="txt_clients_designation" value="{{$clients->client_designation}}" class="form-control" required>
      </div>
    </div>
  <div class="form-group">
     <div class="col-md-2">
        <label>Contact No (Optional)</label>
     </div>
      <div class="col-md-4">
      <input type="number" name="txt_clients_contact_no" id="txt_clients_contact_no" value="{{$clients->client_contact_no}}" class="form-control" required>
      </div>
    </div>
  </div> <!-- /.box-body -->
    <div class="box-footer">
      <div class="row">
        <div class="col-md-6">
          &nbsp;
        </div>
        <div class="col-md-2">
          <label class="btn btn-default form-control" id="cancel1">Cancel</label>
          <label class="btn btn-default form-control" id="cancel2" onclick="btnCancelFunction()" style="display: none;">Cancel ALL</label>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-success form-control">
            Save 
          </button>
        </div>
      </div>
    </div> <!-- /.box-footer -->
 </form>
</div>
@endsection
 
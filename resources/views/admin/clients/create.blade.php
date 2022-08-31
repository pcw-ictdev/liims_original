@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')
 
@include ('layouts.inc.messages')
 <script type="text/javascript">
  $(document).ready(function(){
    $('#cancel1').click(function(){

    if($('#txt_clients_name').val()!= '' || $('#txt_clients_organization').val()!= '' || $('#txt_clients_designation').val()!= '' ||  $('#txt_clients_contact_no').val()!= ''){
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
  <meta charset=utf-8 />
<div class="box box-primary">
  <div class="form-horizontal">
  <div class="box-body">
  <form method="POST" action="/admin/clients/store" class="form-horizontal">
  {{ csrf_field() }}
  <div class="box-body">
  <div class="form-group">
    <label for="" class="col-md-2 control-label" style="text-align:left !important;">Client's Name <b style="color:#FF0000; font-size:18px;">*</b> </label>
      <div class="col-md-6">
       <input type="text" class="form-control" name="txt_clients_name" id="txt_clients_name" required>  
      </div>
    </div>
  <div class="form-group">
    <label for="" class="col-md-2 control-label"  style="text-align:left !important;">Agency/Organization<b style="color:#FF0000; font-size:18px;">*</b></label>
      <div class="col-md-4">
      <select class="form-control" name="txt_clients_organization" id="txt_clients_organization" required>
        <option value="">Select</option>
        @foreach($organizations as $organization)
        <option value="{{$organization->id}}">{{$organization->organization_name}}</option>
        @endforeach
      </select>
      </div>
    </div>
  <div class="form-group">
    <label for="" class="col-md-2 control-label"  style="text-align:left !important;">Designation <b style="color:#FF0000; font-size:18px;">*</b> </label>
      <div class="col-md-6">
      <input type="text" name="txt_clients_designation" id="txt_clients_designation" class="form-control" required>
      </div>
    </div>
  <div class="form-group">
    <label for="" class="col-md-2 control-label"  style="text-align:left !important;"> Contact No (Optional) </label>
      <div class="col-md-4">
      <input type="number" name="txt_clients_contact_no" id="txt_clients_contact_no" class="form-control">
      </div>
    </div>
    </div>
    <!-- /.box-body -->

      <div class="box-footer">
      <div class="row">
        <div class="col-md-4">
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
    </form>
  </div> <!-- /.box-body -->

@endsection
 

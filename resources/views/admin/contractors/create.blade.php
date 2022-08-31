@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')
 
@include ('layouts.inc.messages')
<script src="/js/jquery-1.12.4.min.js"></script>

 <script type="text/javascript">
  $(document).ready(function(){
    $('#cancel1').click(function(){
  if($('#txt_contractors_name').val()  != '' || $('#txt_contractors_contact_person').val() != ''|| $('#txt_contractors_contact_no').val() != ''){
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
  <div class="box-header with-border">
    <h4><b></b></h4>
  </div> <!-- /.box-header -->

  <div class="box-body">
  <form method="POST" action="/admin/contractors/store" class="form-horizontal">
  {{ csrf_field() }}
 
  <div class="box-body">

  <div class="form-group">
    <label for="" class="col-md-2 control-label" style="text-align:left !important;">Contractor's Name <b style="color:#FF0000; font-size:18px;">*</b> </label>
      <div class="col-md-6">
       <input type="text" class="form-control" name="txt_contractors_name" id="txt_contractors_name" required>  
      </div>
    </div>
  <div class="form-group">
    <label for="" class="col-md-2 control-label"  style="text-align:left !important;">Contact Person <b style="color:#FF0000; font-size:18px;">*</b> </label>
      <div class="col-md-6">
       <input type="text" class="form-control" name="txt_contractors_contact_person" id="txt_contractors_contact_person" required>   
      </div>
    </div>
  <div class="form-group">
    <label for="" class="col-md-2 control-label"  style="text-align:left !important;">Contact No <b style="color:#FF0000; font-size:18px;">*</b> </label>
      <div class="col-md-4">
       <input type="text" class="form-control" name="txt_contractors_contact_no" id="txt_contractors_contact_no" required>
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
  </div>
  </div> <!-- /.box-body -->

@endsection
 

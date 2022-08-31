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
  if($('#txt_ecopy_version_no').val()  == '' && $('#txt_ecopy_title').val() == '' && $('#txt_iec_soft_copy').val() == ''){
 window.location.href = "{{ url()->previous() }}"; 
  } else {
       if($('#txt_ecopy_version_no').val()!='' || $('#txt_ecopy_title').val()!='' || $('#txt_iec_soft_copy').val()!=''){
          btnCancelFunction();
        }   
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
  {{ csrf_field() }}
  <div class="box-header with-border">
    <h4><b>ADD NEW IEC FILE</b></h4>
  </div> <!-- /.box-header -->

  <div class="box-body">
  <form method="POST" action="/admin/files/store" class="form-horizontal"  enctype="multipart/form-data">
  {{ csrf_field() }}
 
  <div class="box-body">

  <div class="form-group">
    <label for="" class="col-md-2 control-label">Version No.<b style="color:#FF0000; font-size:18px;">*</b> </label>
      <div class="col-md-4">
       <input type="text" class="form-control" name="txt_ecopy_version_no" id="txt_ecopy_version_no" required>  
      </div>
    </div>
  <div class="form-group">
    <label for="" class="col-md-2 control-label">Title<b style="color:#FF0000; font-size:18px;">*</b> </label>
      <div class="col-md-8">
      <select class="form-control" name="txt_ecopy_title" id="txt_ecopy_title" required>
        <option value="">Select</option>
        @foreach($iecs as $iec)
          <option value="{{$iec->id}}">{{$iec->iec_title}}</option>
        @endforeach
      </select>
      </div>
    </div>
  <div class="form-group">
    <label for="" class="col-md-2 control-label">Soft Copy<b style="color:#FF0000; font-size:18px;">*</b> </label>
      <div class="col-md-8">
      <input type="file" name="txt_iec_soft_copy" id="txt_iec_soft_copy" class="form-control" accept="image/jpeg,image/gif,image/png,application/pdf,image/" required>
      </div>
    </div>
    </div>
    <!-- /.box-body -->

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
    </form>
  </div>
  </div> <!-- /.box-body -->

@endsection
 

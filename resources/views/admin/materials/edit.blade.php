@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include ('layouts.inc.messages')
<script src="/js/jquery-1.12.4.min.js"></script>
 <script type="text/javascript">
  $(document).ready(function(){
    console.log($('#txt_material_name').val());
    $('#cancel1').click(function(){
 if($('#txt_material_name').val() == '' && $('#txt_material_desc').val() == ''){
      window.location.href = "{{ url()->previous() }}";  
    } else {
      $('#cancel2').click();  
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
   <form method="POST" action="/admin/materials/update/{{ $materials->id }}" class="form-horizontal">
  {{ csrf_field() }}
  <div class="box-header with-border">
    <h4><b></b></h4>
  </div> <!-- /.box-header -->
  <div class="box-body">
    <div class="form-group">
      <div class="col-md-2">
      <label style="text-align:left !important;">Type of Material<b style="color:#FF0000; font-size:18px;">*</b> </label>
      </div>
      <div class="col-md-6">
       <input type="text" class="form-control" name="txt_material_name" id="txt_material_name" placeholder="Type of Material" value="{{$materials->material_name}}" required>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-2">
      <label style="text-align:left !important;">Description <b style="color:#FF0000; font-size:18px;">*</b> </label>
      </div>
      <div class="col-md-6">
       <textarea class="form-control" name="txt_material_desc" id="txt_material_desc"  placeholder="Description" required style="height:200px !important;">{{$materials->material_desc}}</textarea>
      </div>
    </div>
  </div> <!-- /.box-body -->
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
    </div> <!-- /.box-footer -->
 </form>
</div>
@endsection
 
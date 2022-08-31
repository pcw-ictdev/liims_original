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
    if($('#txt_organization_name').val()!= '' || $('#txt_organization_type').val()!= '' || $('#txt_organization_city').val()!= ''){
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
  <form method="POST" action="/admin/organizations/store" class="form-horizontal">
  {{ csrf_field() }}
          
 <div class="box-body">
  <div class="form-group">
    <label for="" class="col-md-2 control-label"  style="text-align:left !important;">Organization <b style="color:#FF0000; font-size:18px;">*</b> </label>
      <div class="col-md-6">
       <input type="text" class="form-control" name="txt_organization_name" id="txt_organization_name" required>  
      </div>
    </div>
  <div class="form-group">
    <label for="" class="col-md-2 control-label"  style="text-align:left !important;">Organization Type <b style="color:#FF0000; font-size:18px;">*</b> </label>
      <div class="col-md-4">         
        <select class="form-control" name="txt_organization_type" id="txt_organization_type" required> 
              <option value="">Select</option>
          @foreach($orgtypes as $orgtype)
              <option value="{{$orgtype->org_type_id}}">{{$orgtype->org_type_code}}</option>
          @endforeach
        </select>  
      </div>
    </div>
  <div class="form-group">
    <label for="" class="col-md-2 control-label"  style="text-align:left !important;">Address (City) <b style="color:#FF0000; font-size:18px;">*</b> </label>
      <div class="col-md-4">         
        <select class="form-control" name="txt_organization_city" id="txt_organization_city" required> 
              <option value="">Select</option>
          @foreach($acities as $acity)
              <option value="{{$acity->city_name}}">{{$acity->city_name}}</option>
          @endforeach
        </select>  
      </div>
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
 

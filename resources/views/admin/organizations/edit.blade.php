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
      window.location.href = "/admin/organizations/";
    }
   });
});
   function btnCancelFunction() {
  var r = confirm("Do you want to cancel?");
  if (r == true) {
   window.location.href = "/admin/organizations/";
  } 
}
 </script>

<div class="box box-primary">
   <form method="POST" action="/admin/organizations/update/{{ $organizations->id }}" class="form-horizontal">
  {{ csrf_field() }}
  <div class="box-header with-border">
    <h4><b></b></h4>
  </div> <!-- /.box-header -->
  <div class="box-body">
  <div class="form-group">
    <label for="" class="col-md-2 control-label" style="text-align:left !important;"> Organization <b style="color:#FF0000; font-size:18px;">*</b> </label>
      <div class="col-md-6">
       <input type="text" class="form-control" name="txt_organization_name" id="txt_organization_name" value="{{$organizations->organization_name}}" required>  
      </div>
    </div>
  <div class="form-group">
    <label for="" class="col-md-2 control-label" style="text-align:left !important;">Organization Type <b style="color:#FF0000; font-size:18px;">*</b> </label>
      <div class="col-md-4">         
        <select class="form-control" name="txt_organization_type" required> 
              <option value="{{$organizations->organization_type}}" selected>{{$organizations->org_type_code}}</option> 
          @foreach($orgtypes as $orgtype)
              <option value="{{$orgtype->org_type_id}}">{{$orgtype->org_type_code}}</option>
          @endforeach
        </select>  
      </div>
    </div>
  <div class="form-group">
    <label for="" class="col-md-2 control-label" style="text-align:left !important;">Address (City) <b style="color:#FF0000; font-size:18px;">*</b> </label>
      <div class="col-md-4">         
        <select class="form-control" name="txt_organization_city" required> 
              <option value="{{$organizations->organization_address}}" selected>{{$organizations->organization_address}}</option>
          @foreach($acities as $acity)
              <option value="{{$acity->city_name}}">{{$acity->city_name}}</option>
          @endforeach
        </select>  
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
          <label class="btn btn-default form-control" id="cancel2" onclick="btnCancelFunction()" style="display: none;">
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
 
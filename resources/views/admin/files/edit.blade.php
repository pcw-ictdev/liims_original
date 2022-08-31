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
   <form method="POST" action="/admin/files/update/{{ $ecopies->ecopy_id }}" class="form-horizontal" enctype="multipart/form-data">
  {{ csrf_field() }}
  <div class="box-header with-border">
    <h4><b></b></h4>
  </div> <!-- /.box-header -->
  <div class="box-body">
    <div class="form-group">
      <div class="col-md-2">
      <label> Version No.<b style="color:#FF0000; font-size:18px;">*</b> </label>
      </div>
      <div class="col-md-4">
       <input type="text" class="form-control" name="txt_ecopy_version_no" value="{{ $ecopies->ecopy_version_no }}"required>
      </div>
    </div>
  <div class="form-group">
     <div class="col-md-2">
        <label>Title<b style="color:#FF0000; font-size:18px;">*</b> </label>
     </div>
      <div class="col-md-8">
      <select class="form-control" name="txt_ecopy_title" id="txt_ecopy_title" required>
        <option value="{{$ecopies->ecopy_iec_title}}" selected>
            {{$ecopies->iec_title}}
        </option>
        @foreach($iecs as $iec)
        <option value="{{$iec->id}}">{{$iec->iec_title}}</option>
        @endforeach
      </select>
      </div>
    </div>
  <div class="form-group">
     <div class="col-md-2">
        <label>File
     </div>
      <div class="col-md-8">
      <input type="file" name="txt_iec_soft_copy" id="txt_iec_soft_copy" class="form-control"  accept="application/pdf">
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
 

 
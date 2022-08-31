@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')
 
@include ('layouts.inc.messages')
<script src="/js/jquery-1.12.4.min.js"></script>
<script src="/js/form-scripts.js"></script>
 <script type="text/javascript">
  $(document).ready(function(){
    $('#cancel1').click(function(){
    if($('#file_img').val()!= '' || $('#txt_iec_refno').val()!= '' || $('#txt_iec_author').val()!= '' || $('#txt_iec_publisher').val()!= '' || $('#txt_iec_title').val() !='' || $('#txt_iec_copyright_date').val() !='' || $('#txt_iec_page').val() !='' || $('#txt_iec_type_of_materials').val() !='' || $('#txt_iec_threshold').val() !=''){
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
 
 <div class="box-body">
  <form method="POST" action="/admin/iec/store" class="form-horizontal" enctype="multipart/form-data">
  {{ csrf_field() }}
          
 <div class="box box-default box-solid">
  <div class="box-body">
  <div class="box-header with-border">
    <h4><b></b></h4>
  </div> <!-- /.box-header -->
      <div class="form-group">
      <label class="col-md-2 control-label">Upload Cover Image<span style="color:red; font-size: 20px;"><b>*</b></span></label>
      <div class="col-md-4">
        <img id="output" src="/images/Image-Not-Available-Icon.jpg" class="img-thumbnail" style="width:180px; height:120px; background-color:#f0f0f0;" border="2px" alt="Image"/><br><br>
        <input type="file" id="file_img" name="txt_iec_image" accept="image/*" onchange="loadFile(event)" class="form-control" required> 
      </div>
    </div>
  <div class="form-group">
    <div class="col-md-12">
     <br>
  </div>
</div>
  <div class="form-group">
    <label for="" class="col-md-2 control-label">Title <span style="color:red; font-size: 20px;"><b> * </b></span> </label>
      <div class="col-md-6">
       <input type="text" class="form-control" name="txt_iec_title" id="txt_iec_title" required>  
      </div>
    </div>
  <div class="form-group">
    <label for="" class="col-md-2 control-label">Author</label>
      <div class="col-md-6">
       <input type="text" class="form-control" name="txt_iec_author" id="txt_iec_author">  
      </div>
    </div>
  <div class="form-group">
      <label for="" class="col-md-2 control-label">Publisher</label>
      <div class="col-md-6">
       <input type="text" class="form-control" name="txt_iec_publisher" id="txt_iec_publisher">  
      </div>
    </div>
  <div class="form-group">
      <label for="" class="col-md-2 control-label"> Copyright Date</label>
      <div class="col-md-2">
       <input type="number" class="form-control" name="txt_iec_copyright_date" id="txt_iec_copyright_date">  
      </div>
    </div>
  <div class="form-group">
      <label for="" class="col-md-2 control-label">No. of Pages <span style="color:red; font-size: 20px;"><b> * </b></span> </label>
      <div class="col-md-2">
       <input type="number" class="form-control input-sm" id="txt_iec_page" name="txt_iec_page" required>
      </div>
    </div>
  <div class="form-group">
      <label for="" class="col-md-2 control-label">Specifications<span style="color:red; font-size: 20px;"><b> * </b></span></label>
      <div class="col-md-6">
       <textarea class="form-control input-sm" id="txt_iec_specifications" name="txt_iec_specifications" required></textarea>
      </div>
    </div>
      <div class="form-group">
      <label for="" class="col-md-2 control-label">Type of Materials <span style="color:red; font-size: 20px;"><b> * </b></span> </label>
      <div class="col-md-4">
        <select class="form-control input-sm" name="txt_iec_type_of_materials" id="txt_iec_type_of_materials" required>
          <option value="">Select</option>
          @foreach($materials as $material)
            <option value="{{ $material->id }}"> {{ $material->material_name }} </option>
          @endforeach
        </select> 
      </div>
    </div>
  <div class="form-group">
      <label for="" class="col-md-2 control-label">Threshold <span style="color:red; font-size: 20px;"><b> * </b></span> </label>
      <div class="col-md-2">
       <input type="number" class="form-control input-sm" id="txt_iec_threshold" name="txt_iec_threshold" required>
      </div>
    </div>  
  <div class="form-group">
      <label for="" class="col-md-2 control-label">Upload Soft Copy <span style="color:red; font-size: 20px;"><b> * </b></span> </label>
      <div class="col-md-4">
       <input type="file" class="form-control input-sm" id="txt_iec_soft_copy" name="txt_iec_soft_copy" accept="application/pdf"  required>
      </div>
    </div> 
  <div class="form-group">
      <label for="" class="col-md-2 control-label">Version No <span style="color:red; font-size: 20px;"><b> * </b></span> </label>
      <div class="col-md-4">
       <input type="text" class="form-control input-sm" id="txt_iec_version_no" name="txt_iec_version_no" required>
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
            Save </button>
      </div>
    </form>
  </div> <!-- /.box-body -->

@endsection
 

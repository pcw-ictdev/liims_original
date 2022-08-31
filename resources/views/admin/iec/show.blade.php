@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include ('layouts.inc.messages')


<div class="box box-primary">
   <div class="box-body">
  <div class="box-header with-border">
    <h4><b></b></h4>
  </div> <!-- /.box-header -->
      <div class="form-group">
      <label for="" class="col-md-2 control-label">Image</label>
      <div class="col-md-10">
        @if($iecs->iec_image == '') 
          <img src="/images/Image-Not-Available-Icon.jpg" style="height:120px; width:160px;" id="output">
        @else 
          <img src="{{$iecs->iec_image}}" style="height:120px; width:160px;">
        @endif
      </div>
    <div class="col-md-12">
      <br>
    </div>
    </div>
  <div class="form-group">
    <label for="" class="col-md-2 control-label">Reference No.</label>
      <div class="col-md-10">
       <input type="text" class="form-control" name="txt_iec_refno" id="txt_iec_refno" required value="{{$iecs->iec_refno}}" readonly>  
      </div>
    </div>
  <div class="form-group">
    <label for="" class="col-md-2 control-label">Title</label>
      <div class="col-md-10">
       <input type="text" class="form-control" name="txt_iec_title" id="txt_iec_title" required value="{{$iecs->iec_title}}" readonly>  
      </div>
    </div>
  <div class="form-group">
    <label for="" class="col-md-2 control-label">Author</label>
      <div class="col-md-10">
       <input type="text" class="form-control" name="txt_iec_author" id="txt_iec_author" required value="{{$iecs->iec_author}}" readonly>  
      </div>
    </div>
  <div class="form-group">
      <label for="" class="col-md-2 control-label">Publisher</label>
      <div class="col-md-10">
       <input type="text" class="form-control" name="txt_iec_publisher" id="txt_iec_title" required value="{{$iecs->iec_publisher}}" readonly>  
      </div>
    </div>
  <div class="form-group">
      <label for="" class="col-md-2 control-label"> Copyright Date</label>
      <div class="col-md-10">
       <input type="number" class="form-control" name="txt_iec_copyright_date" id="txt_iec_copyright_date" required value="{{$iecs->iec_copyright_date}}" readonly>  
      </div>
    </div>
  <div class="form-group">
      <label for="" class="col-md-2 control-label">No. of Pages</label>
      <div class="col-md-10">
       <input type="number" class="form-control input-sm" id="txt_iec_page" name="txt_iec_page" required value="{{$iecs->iec_page}}" readonly>
      </div>
    </div>
  <div class="form-group">
      <label for="" class="col-md-2 control-label">Threshold</label>
      <div class="col-md-10">
       <input type="number" class="form-control input-sm" id="txt_iec_threshold" name="txt_iec_threshold" required value="{{$iecs->iec_threshold}}" readonly>
      </div>
    </div>
      <div class="form-group">
      <label for="" class="col-md-2 control-label">Type of Materials</label>
      <div class="col-md-10">
       <input type="text" class="form-control input-sm" id="txt_iec_materials" name="txt_iec_page" required value="{{$iecs->material_name}}" readonly>
      </div>
    </div>
  
    </div> 
    <div class="box-footer">
        <div class="form-group">
        <div class="col-md-3">&nbsp;</div>
        <div class="col-md-8">
          <div class="form-group">
            <div class="col-md-1">
              <a href="/admin/iec/edit/{{  $iecs->id }}" class="btn btn-flat btn-default">EDIT</a>
            </div>
            <div class="col-md-1">
            </div>
            <div class="col-md-1">
              <a href="/admin/iec/delete/{{  $iecs->id }}" class="btn btn-flat btn-danger"onclick="return confirm('Are you sure to delete this record?')">DELETE</a>
            </div>
        </div>
        </div>
      </div>
    </div> <!-- /.box-footer -->
@endsection
 
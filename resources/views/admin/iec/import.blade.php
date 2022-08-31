 
 @extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include ('layouts.inc.messages')
<div class="box box-primary">
  <div class="box-header with-border">
    <h4><b>Upload File</b></h4>
  </div> <!-- /.box-header -->
  <div class="box-body">
       <form action="/admin/iec/import/submit" method="POST" class="form-horizontal" enctype="multipart/form-data">
        {{csrf_field()}}
      <div class="row">
        <div class="col-md-4">
          <input type="file" name="iec_file" class="form-control" required>
        </div>
        <div class="col-md-1">
          <button type="submit" name="btn_submit" class="btn btn-info">
          Import
        </button>
      </div>
      </div>
    </form>
  </div> <!-- /.box-body -->
    <div class="box-footer">
    </div> <!-- /.box-footer -->

</div>
@endsection

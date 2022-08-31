@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include ('layouts.inc.messages')
<div class="box box-primary">
  {{ csrf_field() }}
  <div class="box-header with-border">
    <h4><b>DETAILS</b></h4>
  </div> <!-- /.box-header -->
  <div class="box-body">

    <div class="form-group">
    <div class="col-md-3">
      <label>Organization</label>
    </div>
      <div class="col-md-8">
        <input type="text" class="form-control" value="{{ $organizations->organization_name }}" disabled>
      </div>
    </div>
    <div class="form-group">
    <div class="col-md-3">
      <label>Organization Type</label>
    </div>
      <div class="col-md-8">
        <input type="text" class="form-control" value="{{ $organizations->org_type_code }}" disabled>
      </div>
    </div>
    <div class="form-group">
    <div class="col-md-3">
      <label>Address</label>
    </div>
      <div class="col-md-8">
        <input type="text" class="form-control" value="{{ $organizations->organization_address }}" disabled>
      </div>
    </div>

  </div> <!-- /.box-body -->
    <div class="box-footer">
        <div class="form-group">
        <div class="col-md-3">&nbsp;</div>
        <div class="col-md-8">
          <div class="form-group">
            <div class="col-md-1">
              <a href="/admin/organizations/edit/{{  $organizations->id }}" class="btn btn-flat btn-warning">EDIT</a>
            </div>
            <div class="col-md-1">
            </div>
            <div class="col-md-1">
              <a href="/admin/organization/delete/{{  $organizations->id }}" class="btn btn-flat btn-danger"onclick="return confirm('Are you sure to delete this record?')">DELETE</a>
            </div>
        </div>
        </div>
      </div>
    </div> <!-- /.box-footer -->

</div>
@endsection

@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include ('layouts.inc.messages')
<div class="box box-primary">
  {{ csrf_field() }}
  <div class="box-header with-border">
    <h4><b>VIEW</b></h4>
  </div> <!-- /.box-header -->
  <div class="box-body">

    <div class="row">
    <div class="col-md-3">
      <label>Name</label>
    </div>
      <div class="col-md-8">
        <input type="text" class="form-control" value="{{ $users->name }}" disabled>
      </div>
    </div>

    <div class="row">
    <div class="col-md-3">
      <label>Agency ID</label>
    </div>
      <div class="col-md-8">
        <input type="text" class="form-control" value="{{ $users->username }}" disabled>
      </div>
    </div>

    <div class="row">
    <div class="col-md-3">
      <label>Email</label>
    </div>
      <div class="col-md-8">
        <input type="text" class="form-control" value="{{ $users->email }}" disabled>
      </div>
    </div>

    <div class="row">
    <div class="col-md-3">
      <label>Usertype</label>
    </div>
      <div class="col-md-8">
        <input type="text" class="form-control" value="{{ $users->user_role }}" disabled>
      </div>
    </div>

  </div> <!-- /.box-body -->
    <div class="box-footer">
        <div class="row">
        <div class="col-md-3">&nbsp;</div>
        <div class="col-md-8">
          <div class="row">
            <div class="col-md-1">
              <a href="/admin/users/edit/{{  $users->id }}" class="btn btn-flat btn-default">EDIT</a>
            </div>
            <div class="col-md-1">
            </div>
            <div class="col-md-1">
              <a href="/admin/users/delete/{{  $users->id }}" class="btn btn-flat btn-danger"onclick="return confirm('Are you sure to delete this record?')">DELETE</a>
            </div>
        </div>
        </div>
      </div>
    </div> <!-- /.box-footer -->

</div>
@endsection

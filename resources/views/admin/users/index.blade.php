@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include('layouts.inc.messages')
<script src="/js/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
  $('#divCreateNewButton').empty();
  $('#divCreateNewButton').append('<br><center><a href="/admin/users/create" class="btn btn-success">CREATE NEW</a></center>');
  });
</script>
<style>
  #table th {background-color:#00c0ef; !important;}
</style>
<div class="box box-primary">
  <div class="box-header with-border">
    <div class="col-md-10">
    </div>
    <div class="col-md-2">
    </div>
</div>
  <!-- /.box-header -->
    <div class="box-body">
      <table id="table" class="table table-bordered"> 
        <thead>
          <tr>
            <th>Agency ID</th>
            <th>Name</th>
            <th>Position</th>
            <th>Email</th>
            <th>User type</th>
            <th>Created by</th>
            <th>Date Created</th>
            <th style="width:4%;">Action</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
       @if($user->is_deleted != 1)
      <tr>
      @else
      <tr style="background-color:#fcc0c0 !important;">    
      @endif
              <td>{{ $user->username }}</td>
              <td>{{ $user->name }}</td>
              <td>{{ $user->user_position }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->user_role }}</td>
              <td>{{ $user->authorName }}</td>
              <td>@php echo date('M d, Y', strtotime($user->created_at)) @endphp </td>
              <td>
                <a href="/admin/users/edit/{{ $user->id }}">Edit</a> 
              </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div> <!-- /.box-body -->
    <div class="box-footer"></div> <!-- /.box-footer -->
</div> <!-- /.box box-primary -->

@endsection
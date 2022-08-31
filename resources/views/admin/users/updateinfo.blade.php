@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include ('layouts.inc.messages')


<div class="box box-primary">
<input type="hidden" name="application_url" id="application_url" value="{{URL::to(Request::route()->getPrefix()) }}"/>
   <form method="POST" action="/users/updatepasswordinfo/{{ $users->id }}" class="form-horizontal">
  {{ csrf_field() }}
  <input type="hidden" name="txt_user_id" value="{{ $users->id }}">
  <div class="box-header with-border">
    <h4><b>Update User Information</b></h4>
  </div> <!-- /.box-header -->
  <div class="box-body">
    <div class="form-group">
      <div class="col-md-3">
      <label for="datasource" class="col-md-2 control-label">Name</label>
      </div>
      <div class="col-md-6">
       <input type="text" class="form-control" name="user_name" placeholder="Name" value="{{$users->name}}"readonly>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-3">
      <label for="datasource" class="col-md-2 control-label">New Password</label>
      </div>
      <div class="col-md-6">
       <input type="password" class="form-control" name="rpassword" id="rpassword" placeholder="Password" required>
      </div>
      <div class="col-md-1">
         <label id="rplbl"></label>
      </div>
      </div>
    <div class="form-group">
      <div class="col-md-3">
      <label for="datasource" class="col-md-2 control-label">Confirm Password</label>
      </div>

      <div class="col-md-6">
       <input type="password" class="form-control" name="rcpassword" id="rcpassword" placeholder="Password" required>
      </div>
      <div class="col-md-1">
         <label id="rcplbl"></label>
      </div>
     <div class="form-group">
    <div class="col-md-3">
         <label></label>
      </div>
      <div class="col-md-7">
         <label id="lblvalidate"></label>
    </div>
  </div>
  </div> <!-- /.box-body -->
    <div class="box-footer">
    <center>
      <button type="submit" name="submit" class="btn btn-success" id="rsubmit">
        <i class="fa fa-arrow-circle-right"></i> Update
      </button>
    </center>
    </div> <!-- /.box-footer -->
 </form>
</div>
@endsection


<script src="/js/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
      $('#rpassword').keyup(function() {
    if($(this).val().length < 1) { 
      $('#rpassword').css("border", "#d2d6de solid 1px");
      $('#rplbl').empty();
      $('#rplbl').val('');
      $('#rcplbl').click();
      $('#lblvalidate').empty(); 
    }
    else if($(this).val().length < 6) {
      $('#rpassword').css("border", "RED solid 1px");
      $('#rsubmit').attr("disabled",true);
      $('#rplbl').empty();
      $('#rplbl').append('<i class="glyphicon glyphicon-remove"></i>');
      $('#rplbl').val('NO');
      $('#rcplbl').click();
    } 
    else if($(this).val().length >= 6) {
        $('#rplbl').empty();
        $('#rplbl').append('<i class="glyphicon glyphicon-ok"></i>');
        $('#rplbl').val('OK');
        $('#rpassword').css("border", "GREEN solid 1px");
        $('#rcplbl').click();
    }
});

    $('#rcpassword').keyup(function() {
    if($(this).val().length < 1) { 
      $('#rcpassword').css("border", "#d2d6de solid 1px");
      $('#rcplbl').empty();
      $('#rcplbl').val('');
      $('#rcplbl').click();
      $('#lblvalidate').empty();    
    }
    else if($(this).val().length < 6) {
      $('#rcpassword').css("border", "RED solid 1px");
      $('#rcplbl').empty();
      $('#rcplbl').append('<i class="glyphicon glyphicon-remove"></i>');
      $('#rcplbl').val('NO');
      $('#rcplbl').click();
    }
    else if($(this).val().length >= 6) {
        $('#rcplbl').empty();
        $('#rcplbl').append('<i class="glyphicon glyphicon-ok"></i>');
        $('#rcplbl').val('OK');
        $('#rcpassword').css("border", "GREEN solid 1px");
        $('#rcplbl').click();
    }
  });
    $('#rcplbl').click(function() {

    if($('#rpassword').val() == $('#rcpassword').val() && $('#rpassword').val().length >= 6 && $('#rcpassword').val().length >= 6) {
       $('#lblvalidate').empty();  
       $('#lblvalidate').append('Password matched.');
       $('#rpassword').css("border", "GREEN solid 1px");
       $('#rcpassword').css("border", "GREEN solid 1px");
       $('#rplbl').empty();
       $('#rcplbl').empty();
       $('#rplbl').append('<i class="glyphicon glyphicon-ok"></i>');
       $('#rcplbl').append('<i class="glyphicon glyphicon-ok"></i>');
       $('#rsubmit').attr("disabled",false);
    } else {
        if($('#rpassword').val().length <=5 && $('#rcpassword').val().length <= 5) {
         $('#lblvalidate').empty();
         $('#lblvalidate').append('Password too short.');
      } else {
        $('#lblvalidate').empty();  
        $('#lblvalidate').append('Password mismatch.'); 
        $('#rpassword').css("border", "RED solid 1px");
        $('#rcpassword').css("border", "RED solid 1px");
        $('#rplbl').empty();
        $('#rcplbl').empty();
        $('#rplbl').append('<i class="glyphicon glyphicon-remove"></i>');
        $('#rcplbl').append('<i class="glyphicon glyphicon-remove"></i>');
        $('#rsubmit').attr("disabled",true); 
      }
    }
  }); 
});   
 </script>
 
@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include ('layouts.inc.messages')
<script src="/js/jquery-1.12.4.min.js"></script>
 <script type="text/javascript">
  $(document).ready(function(){
    $('#cancel1').click(function(){
    if($('#user_empid').val()!= '' || $('#user_name').val()!= '' || $('#user_type').val()!= '' || $('#user_email').val()!= '' || $('#user_rpassword').val()!= '' || $('#rrpassword').val()!= '' || $('#rpassword').val()!= ''){
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
   <form method="POST" action="/admin/users/update/{{ $users->id }}" class="form-horizontal">
  {{ csrf_field() }}
  <div class="box-header with-border">
    <h4><b>EDIT</b></h4>
  </div> <!-- /.box-header -->
  <div class="box-body">
    <div class="form-group">
      <div class="col-md-2">
      <label style="text-align:left !important;">Agency ID <b style="color:#FF0000; font-size:18px;">*</b> </label>
      </div>
      <div class="col-md-3">
       <input type="text" class="form-control" name="user_empid" id="user_empid" placeholder="Agency ID" value="{{$users->username}}"required>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-2">
      <label>Name<b style="color:#FF0000; font-size:18px;">*</b> </label>
      </div>
      <div class="col-md-4">
       <input type="text" class="form-control" name="user_name" placeholder="Name" value="{{$users->name}}"required>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-2">
      <label>Position<b style="color:#FF0000; font-size:18px;">*</b> </label>
      </div>
      <div class="col-md-4">
       <input type="text" class="form-control" name="user_position" placeholder="Position" value="{{$users->user_position}}"required>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-2">
      <label style="text-align:left !important;">Email <b style="color:#FF0000; font-size:18px;">*</b> </label>
      </div>
      <div class="col-md-4">
       <input type="email" class="form-control" name="user_email" id="user_email" placeholder="E-mail Address" value="{{$users->email}}" required>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-2">
      <label style="text-align:left !important;">Usertype <b style="color:#FF0000; font-size:18px;">*</b> </label>
      </div>
      <div class="col-md-4">
              <select class="form-control" name="user_type" id="user_type">
           <option value="{{$users->user_role_id}}">{{$users->user_role}}</option>
               @foreach($usertypes as $usertype)
           <option value="{{ $usertype->user_role_id }}">{{ $usertype->user_role }} </option>
          @endforeach
        </select> 
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-2">
      <label style="text-align:left !important;">Report Signatory</label>
      </div>
      <div class="col-md-4">
          <select class="form-control" name="user_signatory" id="user_signatory">           
           <option value="0" @php if($users->user_signatory == 0)Echo 'Selected'; @endphp> Disable</option>
           <option value="1"  @php if($users->user_signatory == 1)Echo 'Selected'; @endphp> Enable</option>
          </select> 
      </div>
      </div>
    <div class="form-group">
      <div class="col-md-2">
      <label style="text-align:left !important;">Record Status</label>
      </div>
      <div class="col-md-4">
          <select class="form-control" name="user_status" id="user_status">
           <option value="0" @php if($users->is_deleted == 0)Echo 'Selected'; @endphp> Active</option>
           <option value="1"  @php if($users->is_deleted == 1)Echo 'Selected'; @endphp> Inactive</option>
          </select> 
      </div>
      </div>
    <div class="form-group">
      <div class="col-md-2">
      <label style="text-align:left !important;">Password (Optional) </label>
      </div>
      <div class="col-md-4">
       <input type="hidden" class="form-control" name="user_password" id="rrpassword" placeholder="Password" value="{{ $users->password }}" readonly required>
       <input type="password" class="form-control" name="user_newpassword" id="rpassword" placeholder="Password" readonly>
      </div>
      <div class="col-md-2">
         <label class="form control btn btn-info" id="lblreset" style="text-align:left !important;">Reset </label>
      </div>
      </div>
     <div class="form-group">
      <div class="col-md-2">
         <label></label>
    </div>
      <div class="col-md-4">
         <label id="lblvalidate"></label>
    </div>
  </div>
  </div> <!-- /.box-body -->
    <div class="box-footer">
      <div class="row">
        <div class="col-md-2">
          &nbsp;
        </div>
        <div class="col-md-2">
          <label class="btn btn-default form-control" onclick="btnCancelFunction()">Cancel</label>
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
        $('#rsubmit').removeAttr("disabled");
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

 <script>
$(document).ready(function() {
  $('#lblreset').click(function() {
  $("#rrpassword").val( $("#rpassword").val());
  $('#lblreset').attr("disabled",true);  
  $('#rpassword').removeAttr("readonly",true);
  $('#rpassword').attr("required",true);  

  });
});
</script>
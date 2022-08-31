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
    if($('#user_empid').val()!= '' || $('#user_name').val()!= '' || $('#user_type').val()!= '' || $('#user_email').val()!= '' || $('#user_rpassword').val()!= '' || $('#user_cpassword').val()!= ''){
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
<script src="/js/form-scripts.js"></script>
<div class="box box-primary">
  <!-- header success notification -->
<div class="form-group"  id="div-success-notification" style="display: none;">
    <div class="alert alert-success alert-dismissible" id="divMessage">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <p><i class="fa fa-check"></i>&nbsp;
           <label id="lbl-success-message"></label>            
        </p>
    </div> <!-- /.alert -->
</div> <!-- /.form-group -->
<!-- header error notification -->

<!-- header error notification -->
<div class="form-group" id="div-error-notification" style="display: none;">
    <div class="alert alert-danger alert-dismissible" id="divMessage">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <p><i class="fa fa-warning"></i>&nbsp;
        <label id="lbl-error-message"> asd</label>
        </p>
    </div> <!-- /.alert -->
</div> <!-- /.form-group -->
<!-- end header error notification -->

<!-- header warning notification -->
<div class="form-group" id="div-warning-notification" style="display: none;">
    <div class="alert alert-warning alert-dismissible" id="divMessage">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <p><i class="fa fa-warning"></i>&nbsp;
        <label id="lbl-warning-message"> asd</label>
        </p>
    </div> <!-- /.alert -->
</div> <!-- /.form-group -->
<!-- end header warning notification -->


  <form method="POST" action="/admin/users/store" class="form-horizontal">
  {{ csrf_field() }}
 
  <div class="box-body">
    <div class="form-group">
      <div class="col-md-2">
      <label>Agency ID <b style="color:#FF0000; font-size:18px;">*</b> </label>
      </div>
      <div class="col-md-3">
       <input type="text" class="form-control" name="user_empid" id="user_empid" placeholder="Agency ID" required>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-2">
      <label>Name <b style="color:#FF0000; font-size:18px;">*</b> </label>
      </div>
      <div class="col-md-4">
       <input type="text" class="form-control" name="user_name" id="user_name" placeholder="Name" required>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-2">
      <label>Position <b style="color:#FF0000; font-size:18px;">*</b> </label>
      </div>
      <div class="col-md-4">
       <input type="text" class="form-control" name="user_position" id="user_position" placeholder="Position" required>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-2">
      <label>Email <b style="color:#FF0000; font-size:18px;">*</b> </label>
      </div>
      <div class="col-md-4">
       <input type="email" class="form-control" name="user_email" id="user_email" placeholder="E-mail Address" required>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-2">
      <label>Usertype <b style="color:#FF0000; font-size:18px;">*</b> </label>
      </div>
      <div class="col-md-4">
              <select class="form-control" name="user_type" id="user_type" equired>
                <option value="" selected>Select</option>
          @foreach($usertypes as $usertype)
            <option value="{{ $usertype->user_role_id }}"> {{ $usertype->user_role }} </option>
          @endforeach
        </select> 
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-2">
      <label>Password <b style="color:#FF0000; font-size:18px;">*</b> </label>
      </div>
      <div class="col-md-4">
       <input type="password" class="form-control" name="user_password" id="user_rpassword" placeholder="Password" required>
      </div>
      </div>
    <div class="form-group">
      <div class="col-md-2">
      <label>Confirm Password <b style="color:#FF0000; font-size:18px;">*</b> </label>
      </div>
      <div class="col-md-4">
       <input type="password" class="form-control" name="user_cpassword" id="user_cpassword" placeholder="Confirm Password" required>
      </div>
      </div>
  </div> <!-- /.box-body -->
    <div class="box-footer">
      <div class="row">
        <div class="col-md-2">
          &nbsp;
        </div>
        <div class="col-md-2">
          <label class="btn btn-default form-control" id="cancel1">Cancel</label>
          <label class="btn btn-default form-control" id="cancel2" onclick="btnCancelFunction()" style="display: none;">
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary form-control" id="btnUserInfoSubmit" style="display:none;">
            Save 
          </button>
           </form>
          <label class="btn btn-success form-control" id="btnCornfirmPassword">
            Save
          </label>
        </div>
      </div>
    </div> <!-- /.box-footer -->

</div>
@endsection

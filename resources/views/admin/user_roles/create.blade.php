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
    if($('#txt_organization_name').val()!= '' || $('#txt_organization_type').val()!= '' || $('#txt_organization_city').val()!= ''){
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
  <div class="box-header with-border">
    <h4><b>CREATE</b></h4>
  </div> <!-- /.box-header -->
  <div class="box-body">
    <div class="form-group">
      <div class="col-md-3">
      <label>Agency ID</label>
      </div>
      <div class="col-md-8">
       <input type="text" class="form-control" name="user_empid" id="user_empid" placeholder="Agency ID" required>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-3">
      <label>Name</label>
      </div>
      <div class="col-md-8">
       <input type="text" class="form-control" name="user_name" id="user_name"placeholder="Name" required>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-3">
      <label>Email</label>
      </div>
      <div class="col-md-8">
       <input type="email" class="form-control" name="user_email" id="user_email"placeholder="E-mail Address" required>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-3">
      <label>Usertype</label>
      </div>
      <div class="col-md-8">
              <select class="form-control" name="user_type" id="user_type"required>
                <option value="" selected hidden>Usertype</option>
          @foreach($usertypes as $usertype)
            <option value="{{ $usertype->id }}"> {{ $usertype->utype }} </option>
          @endforeach
        </select> 
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-3">
      <label>Password</label>
      </div>
      <div class="col-md-8">
       <input type="password" class="form-control" name="user_password" id="user_rpassword" placeholder="Password" required>
      </div>
      </div>
    <div class="form-group">
      <div class="col-md-3">
      <label>Confirm Password</label>
      </div>
      <div class="col-md-8">
       <input type="password" class="form-control" name="user_cpassword" id="user_cpassword" placeholder="Confirm Password" required>
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
    </form>
  </div> <!-- /.box-body -->

</div>
@endsection

@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include('layouts.inc.messages')
 
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- <script src="/js/form-scripts.js"></script> -->
<script src="/js/jquery-1.12.4.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
  $('#divCreateNewButton').empty();
  $('#divCreateNewButton').append('<br><center><a href="#" data-toggle="modal" data-target="#ModalAddNew" class="btn btn-success" title="Add New Role">CREATE NEW</a></center>');
  });
</script>

<style>
  #example1 th {background-color:#00c0ef; !important;}
</style>

<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 26px;
}

.slider.round:before {
  border-radius: 50%;
}

#table th {background-color:#00c0ef; !important;}
</style>
<div class="box box-primary">
  <div class="box-header with-border">
    <div class="col-md-10"></div>
    <div class="col-md-1">
    </div>
</div>
  <!-- /.box-header -->
    <div class="box-body">
      <table id="example1" class="table table-bordered"> 
        <thead>
          <tr>
            <th style="text-align: center !important;">User Role</th>
            <th style="text-align: center !important;">Description</th>
            <th style="text-align: center !important;">Date Modified</th>
            <th style="text-align: center !important;">Modified By</th>
            <th style="text-align: center !important;">Action</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
<script type="text/javascript">
$(document).ready(function() {
 $('#table').DataTable({
   "order": [[ 1, "asc" ]]
  })
});

</script>
    @if($user->is_deleted != 1)
        <tr>
      @else
        <tr style="background-color:#fcc0c0 !important;">    
      @endif
              <td>{{ $user->user_role }}</td>
              <td>{{ $user->user_description }}</td>
              <td>@if($user->updated_at == '')
                  @else
                  @php echo date('M d, Y h:i A',strtotime($user->updated_at)); @endphp 
                  @endif</td>
              <td>{{ $user->name }}</td>
              <td>
        @if($user->is_deleted == 1) <b>Deleted</b> @endif
          @if($user->is_deleted != 1)
                  <a href="#" id="edit{{ $user->user_role_id }}" data-toggle="modal" data-target="#ModalEdit" title="Edit">Edit </a> |
                  <a href="/user/roles/dele-record/{{ $user->user_role_id }}" onclick="return confirm('Are you sure to delete this record?')">Delete</a>  
          @endif 
                <script type="text/javascript">
                  $(document).ready(function(){
                   // $("#edit_user_material_request").prop("checked", false);
                    $('#edit{{ $user->user_role_id }}').click(function(){
                      var userRoleToggle = {{ $user->user_role_id }};
                      var userRole = "{{$user->user_role}}";
                      var userDesc = "{{$user->user_description}}";
                      $('#edit_userRoleID').val({{ $user->user_role_id }});
                      $('#edit_txt_user_role').val(userRole);
                      $('#edit_txt_user_role_desc').val(userDesc);

                      if({{$user->user_material_request}} == 1){         
                        $('#edit_user_material_request').val(1);
                        $('#txt_edit_user_material_request').val(1);
                        $('#edit_user_material_request').prop('checked', true);
                       } else {
                        $('#edit_user_material_request').removeAttr('checked');
                        $('#edit_user_material_request').val(2); 
                        $('#txt_edit_user_material_request').val(2); 
                      }
                      if({{$user->user_inventory}} == '1'){
                        $('#edit_user_inventory').prop('checked', true);
                        $('#edit_user_inventory').val(1);
                        $('#txt_edit_user_inventory').val(1);
                      } else {
                        $('#edit_user_inventory').removeAttr('checked', true);
                        $('#edit_user_inventory').val(2);
                        $('#txt_edit_user_inventory').val(2);
                      }
                      if({{$user->user_code_library}} == '1'){
                        $('#edit_user_code_library').val(1);
                        $('#edit_user_code_library').prop('checked', true);
                        $('#txt_edit_user_code_library').val(1);
                      } else {
                        $('#edit_user_code_library').removeAttr('checked', true);
                        $('#edit_user_code_library').val(2);
                        $('#txt_edit_user_code_library').val(2);
                      }   
                      if({{$user->user_management}} == '1'){
                        $('#edit_user_management').prop('checked', true);
                        $('#edit_user_management').val(1);
                        $('#txt_edit_user_management').val(1);
                      } else {
                        $('#edit_user_management').removeAttr('checked', true);
                        $('#edit_user_management').val(2);
                        $('#txt_edit_user_management').val(2);
                      }       
                      if({{$user->user_reports}} == '1'){
                        $('#edit_user_reports').prop('checked', true);
                        $('#edit_user_reports').val(1);
                        $('#txt_edit_user_reports').val(1);
                      } else {
                        $('#edit_user_reports').removeAttr('checked', true);
                        $('#edit_user_reports').val(2);
                        $('#txt_edit_user_reports').val(2);
                      }  
                      if({{$user->user_audit_log}} == '1'){
                        $('#edit_user_audit_log').prop('checked', true);
                        $('#edit_user_audit_log').val(1);
                        $('#txt_edit_user_audit_log').val(1);
                      } else {
                        $('#edit_user_audit_log').removeAttr('checked', true);
                        $('#edit_user_audit_log').val(2);
                        $('#txt_edit_user_audit_log').val(2);
                      }   
                      if({{$user->user_email_notif_iec_material}} == '1'){
                        $('#edit_user_email_notif_iec_material').prop('checked', true);
                        $('#edit_user_email_notif_iec_material').val(1);
                        $('#txt_edit_user_email_notif_iec_material').val(1);
                      } else {
                        $('#edit_user_email_notif_iec_material').removeAttr('checked', true);
                        $('#edit_user_email_notif_iec_material').val(2);
                        $('#txt_edit_user_email_notif_iec_material').val(2);
                      }   

                  });
                });
                </script>
              </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div> <!-- /.box-body -->
    <div class="box-footer"></div> <!-- /.box-footer -->
</div> <!-- /.box box-primary -->

@endsection


 <!-- Modal -->
<div class="modal fade" id="ModalAddNew" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5><b>Add User Role</b></h5>
    <form method="POST" action="/admin/user_roles/store" class="form-horizontal">
      {{ csrf_field() }}    <div class="box-body">    
      <input type="hidden" id="txt_view_iec_id">
      <div class="row">
        <div class="col-md-3">
        User Role<b style="color:#FF0000; font-size:18px;">*</b> 
        </div>
        <div class="col-md-8">
          <input type="text" name="add_txt_user_role" id="add_txt_user_role" class="form-control" required>
        </div>
      </div>
        <div class="row">
        <div class="col-md-12">
          &nbsp;
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
        Description<b style="color:#FF0000; font-size:18px;">*</b> 
        </div>
        <div class="col-md-8">
          <textarea name="add_txt_user_role_desc" id="add_txt_user_role_desc" class="form-control" required></textarea>
        </div>
      </div>
        <div class="row">
        <div class="col-md-12">
          &nbsp;
        </div>
      </div>
      <h5><b>User Access Rights</b></h5>
       <table class="table">
        <tr>
        <td>Modules</td>
        <td>Enabled</td>
        </tr>         
          <tr>
            <td>
              Material Request
            </td>
            <td> 
              <label class="switch">
                <input type="checkbox" id="add_user_material_request" name="add_user_material_request">
                  <span class="slider round"></span>
              </label>
            </td>
          </tr>
          <tr>
            <td>
              Inventory
            </td>
            <td>
              <label class="switch">
                <input type="checkbox" id="add_user_inventory" name="add_user_inventory">
                  <span class="slider round"></span>
              </label>
            </td>
          </tr>
          <tr>
            <td>
              Code Library
            </td>
            <td>
              <label class="switch">
                <input type="checkbox" id="add_user_code_library" name="add_user_code_library">
                  <span class="slider round"></span>
              </label>
            </td>
          </tr>
          <tr>
            <td>
              User Management
            </td>
            <td>
              <label class="switch">
                <input type="checkbox" id="add_user_management" name="add_user_management">
                  <span class="slider round"></span>
              </label>
            </td>
          </tr>
          <tr>
            <td>
              Reports
            </td>
            <td>
              <label class="switch">
                <input type="checkbox" id="add_user_reports" name="add_user_reports">
                  <span class="slider round"></span>
              </label>
            </td>
          </tr>
          <tr>
            <td>
              Audit Log
            </td>
            <td>
              <label class="switch">
                <input type="checkbox" id="add_user_audit_log" name="add_user_audit_log">
                  <span class="slider round"></span>
              </label>
            </td>
          </tr>
          <tr>
            <td>
              IEC Materials Email Notification
            </td>
            <td>
              <label class="switch">
                <input type="checkbox" id="add_user_email_notif_iec_material" name="add_user_email_notif_iec_material">
                  <span class="slider round"></span>
              </label>
            </td>
          </tr>
        </table>
        <div class="row">
        <div class="col-md-12">
          &nbsp;
        </div>
      </div>
        <div class="row">
        <div class="col-md-10">
          &nbsp;
        </div>
        <div class="col-md-2">
         <center>
            <button type="submit" name="btnsubmit" class="btn btn-success">
              Save
            </button>
          </center>
        </div>
      </div>
    </div>
    </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


 <!-- Modal -->
<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5><b>Edit User Role</b></h5>
        
    <form method="POST" action="/admin/user_roles/update" class="form-horizontal">
      {{ csrf_field() }}    
      <input type="hidden" id="edit_userRoleID" name="edit_userRoleID">
      <div class="box-body">    
      <div class="row">
        <div class="col-md-3">
        User Role<b style="color:#FF0000; font-size:18px;">*</b> 
        </div>
        <div class="col-md-8">
          <input type="text" name="edit_txt_user_role" id="edit_txt_user_role" class="form-control" required>
        </div>
      </div>
        <div class="row">
        <div class="col-md-12">
          &nbsp;
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
       Description<b style="color:#FF0000; font-size:18px;">*</b> 
        </div>
        <div class="col-md-8">
          <textarea name="edit_txt_user_role_desc" id="edit_txt_user_role_desc" class="form-control" required></textarea>
        </div>
      </div>
        <div class="row">
        <div class="col-md-12">
          &nbsp;
        </div>
      </div>
      <h5><b>User Access Rights</b></h5>
      <table class="table">
        <tr>
        <td>Modules</td>
        <td>Enabled</td>
        </tr>         
          <tr>
            <td>
              Material Request
            </td>
            <td> 
              <label class="switch">
                <input type="checkbox" id="edit_user_material_request" name="edit_user_material_request">
                  <span class="slider round"></span>
              </label>
              <input type="hidden" id="txt_edit_user_material_request" name="txt_edit_user_material_request">
            </td>
          </tr>
          <tr>
            <td>
              Inventory
            </td>
            <td>
              <label class="switch">
                <input type="checkbox" id="edit_user_inventory" name="edit_user_inventory">
                  <span class="slider round"></span>
              </label>
              <input type="hidden" id="txt_edit_user_inventory" name="txt_edit_user_inventory">
            </td>
          </tr>
          <tr>
            <td>
              Code Library
            </td>
            <td>
              <label class="switch">
                <input type="checkbox" id="edit_user_code_library" name="edit_user_code_library">
                  <span class="slider round"></span>
              </label>
              <input type="hidden" id="txt_edit_user_code_library" name="txt_edit_user_code_library">
            </td>
          </tr>
          <tr>
            <td>
              User Management
            </td>
            <td>
              <label class="switch">
                <input type="checkbox" id="edit_user_management" name="edit_user_management">
                  <span class="slider round"></span>
              </label>
              <input type="hidden" id="txt_edit_user_management" name="txt_edit_user_management">
            </td>
          </tr>
          <tr>
            <td>
              Reports
            </td>
            <td>
              <label class="switch">
                <input type="checkbox" id="edit_user_reports" name="edit_user_reports">
                  <span class="slider round"></span>
              </label>
              <input type="hidden" id="txt_edit_user_reports" name="txt_edit_user_reports">
            </td>
          </tr>
          <tr>
            <td>
              Audit Log
            </td>
            <td>
              <label class="switch">
                <input type="checkbox" id="edit_user_audit_log" name="edit_user_audit_log">
                  <span class="slider round"></span>
              </label>
              <input type="hidden" id="txt_edit_user_audit_log" name="txt_edit_user_audit_log">
            </td>
          </tr>
            <tr>
            <td>
              IEC Materials Email Notification
            </td>
            <td>
              <label class="switch">
                <input type="checkbox" id="edit_user_email_notif_iec_material" name="edit_user_email_notif_iec_material">
                  <span class="slider round"></span>
              </label>
              <input type="hidden" id="txt_edit_user_email_notif_iec_material" name="txt_edit_user_email_notif_iec_material">
            </td>
          </tr>
        </table>

        <div class="row">
        <div class="col-md-12">
          &nbsp;
        </div>
      </div>
        <div class="row">
        <div class="col-md-10">
          &nbsp;
        </div>
        <div class="col-md-2">
            <button type="submit" name="btnsubmit" class="btn btn-success">
              Save
            </button>
        </div>
      </div>
    </div>
    </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

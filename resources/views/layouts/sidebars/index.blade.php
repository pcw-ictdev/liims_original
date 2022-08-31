<style>
.notif{
  background-color:red;
  border-radius:30px;
  width:20px;
  height:20px;
  text-align:center;
  color:#fff;
}
</style>
<script src="/js/jquery-1.12.4.min.js"></script>
<script src="/js/form-scripts.js"></script>

<input type="hidden" id="txt_uid_val" value="{{Auth::user()->uid}}">

<script type="text/javascript">
  $(document).ready(function(){
    $('#txt_uid_val').click();
  });
</script>
 <input type="hidden" name="application_url" id="application_url" value="{{URL::to(Request::route()->getPrefix()) }}"/>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li> 
            <li class="{{ ($page['parent']==='/' ? 'active' : 'inactive') }}">
                <a href="/home"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>
        <li class="treeview" id="div_material_request" style="display:none;">
           <a href="/admin/requests/create">
            <i class="fa fa-folder"></i>
             <span>Material Request</span>
           </a>
        </li> 

        <li class="treeview" id="div_item_inventory" style="display:none;">
           <a href="/admin/iec/">
            <i class="fa fa-folder"></i>
             <span>Item Inventory</span>
           </a>
        </li>
<!-- 
        <li class="treeview" id="div_item_files" style="display:none;">
           <a href="/admin/files/">
            <i class="fa fa-folder"></i>
             <span>E-Copies</span>
           </a>
        </li>
 -->
        <li id="div_audit_trail" style="display:none;"><a href="/admin/auditlogs"><i class="fa fa-folder"></i>Audit Logs</a></li>

        <li class="treeview" id="div_request_history" style="display:none;">
           <a href="/admin/requests/">
            <i class="fa fa-folder"></i>
             <span>Request History</span>
           </a>
        </li> 
        <li class="treeview" id="div_request_report" style="display:none;">
           <a href="/admin/reports/">
            <i class="fa fa-folder"></i>
             <span>Report</span>
           </a>
        </li> 
        <li class="treeview" id="div_code_library" style="display:none;">
          <a href="#">
            <i class="fa fa-share"></i> <span>Code Library</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>

        <ul class="treeview-menu">
          <li id="div_iec_resources" style="display:none;">
            <a href="#"><i class="fa fa-circle-o" ></i> PCW IEC Resources
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
          <ul class="treeview-menu">
            <li id="div_iec_resources_create" style="display:none;"><a href="/admin/iec/create"><i class="fa fa-circle-o"></i>Add New</a></li>
            <li id="div_iec_resources_list" style="display:none;"><a href="/admin/iec"><i class="fa fa-circle-o"></i>List</a></li>
            <li id="div_printing_logs" style="display:none;"><a href="/admin/iec/printing-logs"><i class="fa fa-circle-o"></i>Printing Logs</a></li>
         </ul>
        </li> 
      </ul>
          <ul class="treeview-menu">
            <li id="div_asset_type" style="display:none;">
              <a href="#"><i class="fa fa-circle-o"></i> Asset Type
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li>
                  <a href="/admin/assets/create"><i class="fa fa-circle-o"></i>Add New</a>
                </li>
                <li>
                  <a href="/admin/assets"><i class="fa fa-circle-o"></i>List</a>
                </li>
              </ul>
                </li>
              </ul>
        <ul class="treeview-menu">
         <li id="div_clients" style="display:none;">
              <a href="#"><i class="fa fa-circle-o"></i> Clients
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
          <ul class="treeview-menu">
            <li><a href="/admin/clients/create"><i class="fa fa-circle-o"></i>Add New</a></li>
            <li><a href="/admin/clients"><i class="fa fa-circle-o"></i>List</a></li>
         </ul>
        </li>
       </ul>

       <ul class="treeview-menu">
        <li id="div_organization" style="display:none;">
            <a href="#"><i class="fa fa-circle-o"></i> Organization
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
          <ul class="treeview-menu">
            <li><a href="/admin/organizations/create"><i class="fa fa-circle-o"></i>Add New</a></li>
            <li><a href="/admin/organizations"><i class="fa fa-circle-o"></i>List</a></li>
         </ul>
        </li> 
      </ul>

       <ul class="treeview-menu">
        <li id="div_contractor" style="display:none;">
            <a href="#"><i class="fa fa-circle-o"></i> Contractor
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
          <ul class="treeview-menu">
            <li><a href="/admin/contractors/create"><i class="fa fa-circle-o"></i>Add New</a></li>
            <li><a href="/admin/contractors"><i class="fa fa-circle-o"></i>List</a></li>
         </ul>
        </li> 
      </ul>
      </li>

        <li class="treeview" id="div_users" style="display:none;">
          <a href="#">
            <i class="fa fa-folder"></i> <span>Users</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="/admin/users/create"><i class="fa fa-circle-o"></i>Add New</a></li>
            <li><a href="/admin/users"><i class="fa fa-circle-o"></i>Users</a></li>
         </ul>
        </li> 
        <li class="treeview" id="div_user_roles" style="display:none;"><a href="/admin/user_roles"><i class="fa fa-circle-o"></i>User Roles</a></li> 
 
          <li class="treeview">
                <a href="/logout"><i class="fa fa-dashboard"></i> <span>Logout</span></a>
            </li>

          <li class="treeview" id="div_pageLoader">
            <div class="spinner-border text-light" role="status">
              <center>
                <img src="/images/pageloader.gif" style="height:30px; width:30px;">
                <h6 style="color:#fff;">Loading..</h6>
              </center>
            </div>            
          </li>

        <!-- /.sidebar-menu -->

    </section>
    <!-- /.sidebar -->
    
</aside>
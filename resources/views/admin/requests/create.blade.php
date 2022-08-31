@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')
 
@include ('layouts.inc.messages')
<script src="/js/jquery-1.12.4.min.js"></script>
<style>
  .img-wrapper {display:inline-block; height:159px; overflow:hidden; width:153px;}
  .img-wrapper img {height:159px;}
  .img-wrapper img {border:2px solid #0275db !important;}
  #table th {background-color:#00c0ef; !important;}
</style>
<meta charset=utf-8 />
<div class="box box-primary">
  <div class="form-horizontal">
  <div class="box-body">

  <!-- header success notification -->
<div class="form-group"  id="div-success-notification2" style="display: none;">
    <div class="alert alert-success alert-dismissible" id="divMessage2">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <p><i class="fa fa-check"></i>&nbsp;
           <label id="lbl-success-message2"></label>            
        </p>
    </div> <!-- /.alert -->
</div> <!-- /.form-group -->
<!-- header error notification -->

<!-- header error notification -->
<div class="form-group" id="div-error-notification2" style="display: none;">
    <div class="alert alert-danger alert-dismissible" id="divMessage2">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <p><i class="fa fa-warning"></i>&nbsp;
        <label id="lbl-error-message2"> asd</label>
        </p>
    </div> <!-- /.alert -->
</div> <!-- /.form-group -->
<!-- end header error notification -->

<!-- header warning notification -->
<div class="form-group" id="div-warning-notification2" style="display: none;">
    <div class="alert alert-warning alert-dismissible" id="divMessage2">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <p><i class="fa fa-warning"></i>&nbsp;
        <label id="lbl-warning-message2"> asd</label>
        </p>
    </div> <!-- /.alert -->
</div> <!-- /.form-group -->
<!-- end header warning notification -->

  <form method="POST" action="/admin/requests/store" class="form-horizontal">
  {{ csrf_field() }}

      <div class="row">  
        <div class="col-md-12">
          <h4><b>Request No. {{$recid}}</b> 
          <input type="hidden" name="txt_request_no" value="{{$recid}}"></h4>
      </div>
      </div>
      <div class="row">
        <div class="col-md-6">
            <div class="box-body box-profile">
              <h4 style="text-transform: uppercase !important;">REQUESTER'S INFO</h4>
              <ul class="list-group">
                <li class="list-group-item">
                  <div class="row">
                    <div class="col-md-2">
                    <b>Name</b> 
                  </div>
                  </div>
                  <div class="row">
                  <div class="col-md-9">
                  <input type="text" name="txt_request_name" list="txt_request_name2" id="txt_request_name" class="form-control" placeholder="Enter Client's Name" required>
                   <datalist id="txt_request_name2">
                  </datalist>
                  <label  class="box-body" id="displayClientResult"></label>
                  </div>
                  <div class="col-md-3">
                  <label class="btn btn-success form-control" data-toggle="modal" data-target="#clientsModal" title="Add New Client" id="lblClientsModal"> <i class="fa fa-plus" aria-hidden="true"> </i> ADD</label>
                </div>
              </div>
                  <br>

                </li>
                <li class="list-group-item">
                  <b>Agency/Organization</b> 
                  <input type="text" class="form-control" name="txt_request_organization" id="txt_request_organization" readonly requried>
                </li>
                <li class="list-group-item">
                <b> Designation </b>            
                <input type="text" class="form-control" name="txt_request_designation" id="txt_request_designation" readonly required>
                </li>
              </ul>
            </div>
            <!-- /.box-body -->
       </div>
        <div class="col-md-6">
            <div class="box-body box-profile">
              <h4>IEC MATERIAL TO REQUEST</h4>
              <ul class="list-group">
                <li class="list-group-item">
                  <b>IEC Material</b> 
                  <div class="form-group">
                  <div class="col-md-10">
                  <input type="text" name="txt_iec_name" list="result_iec_list" id="txt_key_iec_name" class="form-control" placeholder="Enter Material Name">
                  <datalist id="result_iec_list"></datalist>
                </div>
                <div class="col-md-2">
                  <label class="btn btn-default" data-toggle="modal" data-target="#iecModal" title="IEC Materials Look up"> <i class="fa fa-search" aria-hidden="true"> </i></label>
                </div>
              </div>
                </li>
                <li class="list-group-item">
                  <div class="row">
                  <div class="col-md-6">
                  <b>No. of items on hand</b>
                  <input type="hidden" class="form-control" name="txt_material_id" id="txt_material_id" readonly>
                  <input type="number" class="form-control" name="txt_material_onhand" id="txt_material_onhand" readonly>
                  </div>
                  <div class="col-md-6">
                  <b>No. of items requested</b>
                  <input type="number" class="form-control" name="txt_material_quantity" id="txt_material_quantity">
                  </div>
                </div>
                </li>
                <li class="list-group-item">
                  <b style="visibility:hidden;">Action</b>
                  <div class="row">
                    <div class="col-md-8">
                    </div>
                    <div class="col-md-4">
                    <label class="btn btn-success" id="btn_addItem" title="Add to list of request"> 
                      <i class="fa fa-plus"> </i> 
                      <b>ADD TO LIST</b>
                    </label>
                  </div>
                </div>
                </li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>  
        </div>
    <!-- /.box-body -->
            <div class="box-body box-profile">
              <h4>Summary of Request</h4>
                <table class="table table-striped" style="text-align:center !important;" id="tblSelectedRequest"> 
                    <thead>
                      <tr>
                        <th style ="background-color:#00c0ef; !important;">IEC Material</th>
                        <th style ="background-color:#00c0ef; !important;">Quantity (Requested)</th>
                        <th style ="background-color:#00c0ef; !important;">Action</th>
                      </tr>
                    </thead>
                    <tbody id="tblSummaryList">
                    </tbody>
                  </table>
            </div>
 
            <div class="box-body box-profile">
              <h4>Purpose of request</h4>
                <textarea class="form-control" name="txt_request_purpose" required style="height:40px;">{{ old('txt_request_purpose') }}</textarea>
              </div>
            <!-- /.box-body -->
 
          <!-- /.box -->
      <div class="box-footer">
     <div class="row">
      <div class="col-md-10">
        &nbsp;
      </div>
      <div class="col-md-2">    
        <button type="submit" class="btn btn-success form-control" id="btnSaveRequest">
          <i class="fa fa-arrow-circle-right"></i> SAVE
        </button>
      </div>
    </div>
    </form>
  </div> <!-- /.box-body -->

@endsection
 

 <!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="iecModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5>Lookup IEC Materials</h5>
      </div>
        <div class="box-body">
        <table class="table table-bordered" style="display:table; width:100%;" id="table"> 
          <thead>
            <tr>
              <th style="width:30%">Image</th>
              <th class="col-md-7" style="width:40%">Title</th>
              <th class="col-md-2" style="width:25%">Type</th>
              <th class="col-md-2" style="width:25%">Pcs. Available</th>
              <th class="col-md-2"style="width:15%">Action</th> 
            </tr>
          </thead>   
          <tbody>
            @foreach($iecs as $iec)
              <tr>        
                <td  style="width:30%">
                <div class="img-wrapper">
                    @if($iec->iec_image == '') 
                    <img src="/images/Image-Not-Available-Icon.jpg">
                    @else 
                    <img src="{{$iec->iec_image}}">
                    @endif
                 </div>
              </td>
              <td style="width:40%">{{$iec->iec_title}}</td>
              <td style="width:40%">{{$iec->material_name}}</td>
              <td style="width:25%">{{$iec->iec_threshold}}</td>
              <td style="width:15%">
                <button class="btn btn-success" id="addToList{{$iec->id}}" value="{{$iec->iec_title}}" title="add to list"><i class="fa fa-check"></i></button> 
                <script src="/js/jquery-1.12.4.min.js"></script>
                <script type="text/javascript">
                  $(document).ready(function () {  
                    $("#addToList{{$iec->id}}").click(function () { 
                    $("#txt_key_iec_name").val($("#addToList{{$iec->id}}").val()); 
                    $("#txt_material_id").val({{$iec->id}}); 
                    $("#txt_key_iec_name").click(); 
                    $(".btn-secondary").click(); 
                  }); 
                }); 
              </script>
              </td>
              </tr>
            @endforeach
      </tbody>
    </table>
  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


 <!-- Modal -->
<div class="modal fade" id="clientsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Requestor Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
<div class="box-body">
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
        <label id="lbl-error-message"></label>
        </p>
    </div> <!-- /.alert -->
</div> <!-- /.form-group -->
<!-- end header error notification -->
</div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-3">
           Name<b style="color:#FF0000; font-size:18px;">*</b>
          </div>
          <div class="col-md-8">
            <button style="display:none;" id="btn_client_name_refresh">Refresh</button>
            <input type="text" class="form-control" id="txt_clients_name">
          </div>
        </div>
         <div class="row">
          <div class="col-md-12">
            &nbsp;
          </div>
        </div>
         <div class="row">
          <div class="col-md-3">
          Agency / Organization<b style="color:#FF0000; font-size:18px;">*</b>
          </div>
          <div class="col-md-6">
            <input type="text" name="txt_clients_org" list="txt_clients_org2" id="txt_clients_org" class="form-control" placeholder="Enter Organization Name">
            <datalist id="txt_clients_org2">
            </datalist>
          </div>
          <div class="col-md-2">
            <button class="btn btn-info form-control" title="Add New Organization" id="btnNewOrganization">
              <i class="fa fa-plus"> </i>ADD
            </button>
            <button class="btn btn-success form-control" title="Cancel Creating New Organization" id="btnCancelOrganization" style="display:none;">
              <i class="fa fa-minus"> </i>
            </button>
          </div>
        </div>
          <div class="row">
                <div class="col-md-12">
                  <div class="box-body"  id="div-add-new-organization" style="display:none;">
              <hr>
              <div class="row">
                <div class="col-md-12">
                  <center>
                    <h4>Add New Organization</h4>
                  </center>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                Agency / Organization<b style="color:#FF0000; font-size:18px;">*</b>
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" name="txt_organization_name" id="txt_organization_name">
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                Organization Type<b style="color:#FF0000; font-size:18px;">*</b>
                </div>
                <div class="col-md-8">
                  <select class="form-control" name="txt_organization_type" id="txt_organization_type">
                    <option value="" selected hidden>Select</option>
                    @foreach($orgtypes as $orgtype)
                    <option value="{{$orgtype->org_type_id}}">{{$orgtype->org_type_code}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                City Address<b style="color:#FF0000; font-size:18px;">*</b>
                </div>
                <div class="col-md-8">
                  <input type="text" name="txt_org_city" id="txt_org_city" list="cities" class="form-control" placeholder="Enter City/Municipality">
                  <datalist id="cities">
                   @foreach($cities as $htown)
                     <option value="{{$htown->cityName}}">{{$htown->cityName}} 
                    @endforeach    
                  </datalist>
                   @php echo count($cities); @endphp
                </div>
              </div>
              <div class="row">
                <div class="col-md-12"> &nbsp;
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-4">
                  <center>
                    <button id="submitNewOrg" class="btn btn-primary form-control">Save</button>
                  </center>
                </div>
                <div class="col-md-4">&nbsp;</div>
              </div>
          </div> <!-- /.alert -->
          <hr>
      </div> <!-- /.form-group -->
          </div>
         <div class="row">
          <div class="col-md-3">
          Designation<b style="color:#FF0000; font-size:18px;">*</b>
          </div>
           <div class="col-md-8">
            <input type="text" class="form-control" id="txt_clients_designation">
          </div> 
        </div> 
         <div class="row">
          <div class="col-md-12">
            &nbsp;
          </div>
        </div>
         <div class="row">
          <div class="col-md-3">
          Contact No (Optional)
          </div>
           <div class="col-md-8">
            <input type="number" class="form-control" id="txt_clients_contact_no">
          </div> 
        </div> 
         <div class="row">
          <div class="col-md-12">
            <br>
          </div>
        </div>
         <div class="row">
          <div class="col-md-9">
            &nbsp
          </div>
          <div class="col-md-2">
              <button type="button" id="validateClientInfo" class="btn btn-success form-control">SAVE</button>
              <button type="button" id="saveClientInfo" class="btn btn-info form-control" style="display:none;">SAVE</button>
            </right>
          </div>
          </div>  
        </div>  
       <div class="modal-footer">
         <div class="row">
          <div class="col-md-10">
            &nbsp
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
      </div>
    </div>
  </div>
</div>

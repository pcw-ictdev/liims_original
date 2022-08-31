@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include('layouts.inc.messages')
<script src="/js/jquery-1.12.4.min.js"></script>
<script src="/js/form-scripts.js"></script>

<style>
  .img-wrapper {display:inline-block; height:159px; overflow:hidden; width:153px;}
  .img-wrapper img {height:159px;}
  .img-wrapper img {border:2px solid #0275db !important;}
</style>

<div class="box box-primary">
  <div class="box-header with-border">
    <div class="row">
    <div class="col-md-6">
      <h4><b>{{count($iecCriticalItems)}} Critical Item/s</b></h4>
    </div>
  </div>
</div>
  <!-- /.box-header -->
    <div class="box-body">
      <table id="table" class="table table-bordered"> 
    <thead>
    <tr>
      <th>Image</th>
      <th>Title/Description</th>
      <th>Pcs Available</th>
    </tr>
  </thead>
  @foreach($iecCriticalItems as $critems)
    <tr>
      <td> 
       <div class="img-wrapper">
          @if($critems->iec_image == '') 
           <img src="/images/Image-Not-Available-Icon.jpg">
          @else 
          <img src="{{$critems->iec_image}}">
           @endif
      </div> 

      </td>
      <td>{{$critems->iec_title}}</td>
      <td>
        {{$critems->iec_threshold}}
        <label class="btn btn-info" data-toggle="modal" data-target="#iecModal" id="lblEdit{{$critems->id}}" value="{{$critems->id}}" title="Update stock"> <i class="fa fa-edit" aria-hidden="true"> </i></label>

<script type="text/javascript">
$('#lblEdit{{$critems->id}}').click(function () { 
  $('#txt_iec_id').val({{$critems->id}});
  $('#txt_iec_stock').val({{$critems->iec_threshold}});
  });
</script>

      </td>
    </tr>
   @endforeach
  </table>
    </div> <!-- /.box-body -->
    <div class="box-footer"></div> <!-- /.box-footer -->
</div> <!-- /.box box-primary -->

@endsection


 <!-- Modal -->
<div class="modal fade" id="iecModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5>Inventory Type</h5>
    <form method="GET" action="/admin/iec/items-update" class="form-horizontal">
      {{ csrf_field() }}
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

      <input type="text" id="txt_iec_id" name="txt_iec_id" style="display:none;">
      <input type="text" id="txt_iec_stock" name="txt_iec_stock" style="display:none;">
      <div class="row">
        <div class="col-md-3">
          <input type="radio" class="btn btn-default" name="iec_restock" id="iec_restock">
           Restock
          </div>
        <div class="col-md-3">
         <input type="radio" class="btn btn-default" name="iec_adjust" id="iec_adjust">
          Adjust
          </div>
        </div> 
        <input type="text" id="iec_option" name="iec_option" style="display:none;">
      </div>
    <div class="box-body" id="divPrintingInfo" style="display:none;">
    <div class="row">
      <div class="col-md-2">
        Printing date
      </div>
      <div class="col-md-6">
        <input type="date" class="form-control" name="txt_iec_printing_date" id="txt_iec_printing_date" required>
      </div>
    </div>  
    <div class="row">
      <div class="col-md-2">
        Contractor
      </div>
      <div class="col-md-6">
        <input type="text" class="form-control" name="txt_iec_printing_contractor" id="txt_iec_printing_contractor" required>
      </div>
    </div>  
    <div class="row">
      <div class="col-md-2">
        Cost
      </div>
      <div class="col-md-6">
        <input type="number" class="form-control" name="txt_iec_printing_cost" id="txt_iec_printing_cost" required>
      </div>
    </div>  
    </div>
    <hr>
    <div class="box-body">          
      <div class="row">
         <div class="col-md-2">
          Pieces
          </div>       
        <div class="col-md-4">
              <input type="number" name="iec_adjust_pieces" id="iec_adjustPieces" class="form-control" required>
              <input type="number" name="iec_restock_pieces" id="iec_restockPieces" class="form-control" style="display:none;" required>
          </div>
        </div>
      </div>
    <div class="box-body">   
      <div class="row">
         <div class="col-md-2">
          Remarks
          </div>       
        <div class="col-md-8">
          <textarea name="iec_remarks" id="iec_remarks" class="form-control" style="height:120px;" required></textarea>
          </div>
        </div>
      </div>
    <div class="box-body">   
      <div class="row">  
        <div class="col-md-12">
          <center>
          <label class="btn btn-info" id="lblSave">Save</label>
          <button type="submit" class="btn btn-info" name="btnSave" id="btnSave" style="display:none;">Save</button>
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

@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')
 
@include ('layouts.inc.messages')
 <script src="/js/jquery-1.12.4.min.js"></script>
<script src="/js/form-scripts.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#btnIecMaterialsPreview').click();
  });
</script>
<style>
  .img-wrapper {display:inline-block; height:159px; overflow:hidden; width:153px;}
  .img-wrapper img {height:159px;}
  .img-wrapper img {border:2px solid #0275db !important;}
  #table th {background-color:#00c0ef; !important;}
</style>
<meta charset=utf-8 />
<div class="box box-primary">
  <div id='print_content' class="print_content">
  <div class="form-horizontal">
  <div class="box-body">
 @foreach($requests as $request)
 @endforeach
      <table>
        <tr>
          <td style="font-size:10px !important;"><b>Request No. </b></td><td>&nbsp;&nbsp;</td><td style="font-size:10px !important;" > {{$request->transactionID}} </td>
          </tr>
        </table>
      </div>
      <div class="row">
        <div class="col-md-12">&nbsp;</div>
        <div class="col-md-6">
            <div class="box-body box-profile">
              <h5 style="font-size:10px !important; text-transform: uppercase !important;"><b style="font-size:10px !important;">REQUESTER'S INFO</b></h5>
<table>
  <tr> 
    <th style="font-size:10px !important;">Name: </th>
    <td>&nbsp;&nbsp;</td>
    <td style="font-size:10px !important; text-transform: uppercase;">{{$request->client_name}}</td>
  </tr>
  <tr>
    <th style="font-size:10px !important;">Agency/Organization: </th>
    <td>&nbsp;&nbsp;</td>
    <td style="font-size:10px !important; text-transform: uppercase;">{{$request->organization_name}}</td>
  </tr>
  <tr>
    <th style="font-size:10px !important;">Designation: </th>
    <td>&nbsp;&nbsp;</td>
    <td style="font-size:10px !important; text-transform: uppercase;">{{$request->client_designation}}</td>
  </tr>
 </table>
                  </div>
                  </div>
                  <div class="row">
                  <div class="col-md-12">
                    <br>
                  </div>
               </div>
             </div>
            <!-- /.box-body -->
       </div>
    <!-- /.box-body -->
            <div class="box-body box-profile">
              <h5><b  style="font-size:10px !important;">SUMMARY OF REQUEST</b></h5>
                <table class="table table-striped"> 
                    <thead>
                      <tr>
                        <th style =" font-size:10px !important;background-color:#00c0ef !important; text-align: left !important; border:1px solid black !important;">IEC Material</th>
                        <th style =" font-size:10px !important;background-color:#00c0ef !important; text-align: center !important; border:1px solid black !important;">Quantity (Requested)</th>
                      </tr>
                    </thead>
                    <tbody>
                       @foreach($requests as $request)
                      <tr>
                        <td style=" font-size:10px !important;text-align: left !important; border:1px solid black !important;">{{$request->iec_title}}</td>
                        <td style=" font-size:10px !important;text-align: center !important; border:1px solid black !important;">@php echo number_format($request->request_material_quantity); @endphp</td>
                      </tr>
                      @endforeach
                      <tr>
                        <td style="font-size:10px !important; border:1px solid black !important; text-align:center !important;" colspan="2">
                        *** Nothing Follows ***
                        </td>
    </tr>
                    </tbody>
                  </table>
              <table>
                <tr><td>
                  <br><br>
                </td></tr>
              </table>
               @foreach($requests as $request)
               @endforeach    
              <table>
                <tr>
                  <td style="font-size:10px !important;">
                    <b>PURPOSE OF REQUEST:</b>
                    <br><br>
                  </td>
                </tr>
                <tr>
                  <td style="font-size:10px !important;text-align: left !important;">{{$request->request_purpose}}</td>
                </tr>
              </table>
              
  <br>
  <br> 
 <table style="width:100%; background-color: none !important;">
  <tr>
    <td style="width:50%; background-color: none !important; text-align:center !important;">
     <br>
     <br>
    </td>
    <td style="width:20%; background-color: none !important; text-align:center !important;">
     <br>
     <br>
    </td>    
    <td style="font-size:10px !important; width:30%; background-color: none !important; text-align:center !important;">
      <b>Date Generated:</b>
      <br>
     @php
     date_default_timezone_set("Asia/Manila");
     echo date("F d, Y");  @endphp
     <br>
     <br>
    </td>
  </tr>
</table>
 <table style="font-size:10px !important; width:100%; background-color: none !important;">
  <tr>
    <td>
      <br>
     <br>
    </td>
    <td>
      <br>
     <br>
    </td>
  </tr>
  <tr>
    <td style="font-size:10px !important; width:50%; background-color: none !important; text-align:center !important;">
     <b>Received By:</b> 
     <br>
     <br>
     <br>
     <br>     
     {{$request->client_name}}
    </td>
    <td style="font-size:10px !important; width:50%; background-color: none !important; text-align:center !important;">
     <b>Prepared By:</b> 
     <br>
     <br>
     <br>
     <br>
     {{Auth::user()->name}}
     <br>
     {{Auth::user()->user_position}}
    </td>
  </tr>
</table>  
<table style="width:100%;">
<tr>
  <td>
  <br>
  <br>
  <br>
  <br>
  <br>
  <hr style="width: 100% !important;">
  <b style="font-size:10px !important; width:100%;">
    <center>
    Copyright © 2020 Philippine Commission on Women 1145 J.P. Laurel St., San Miguel Manila 1005 Philippines
    </center>
  </b>
  </td>
</tr>
</table>

            </div>
 
            <!-- /.box-body -->
 
          <!-- /.box -->
      <div class="box-footer">
  </div> <!-- /.box-body -->
</div>
 <button id="btnIecMaterialsPreview" style="visibility:hidden;">Print</button>

</div>
</div>
@endsection

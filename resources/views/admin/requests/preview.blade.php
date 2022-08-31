@extends('layouts.master')

 

@section('content')
 <script src="/js/jquery-1.12.4.min.js"></script>
<script src="/js/form-scripts.js"></script>
<meta charset=utf-8 />
<div class="box box-primary">
  <div class="form-horizontal">
  <div id='print_content' class="print_content">
    <center>
  <div class="box-body">
 <br> 
 @foreach($transactions as $transaction)
    <table style="text-align:left;">
      <tr>
        <th>
         Name of Client 
       </th> 
       <td> 
        : <u>
         {{$transaction->client_name}} 
       </u> 
     </td>
     </tr>
      <tr>
        <th>
         Organization
       </th> 
       <td> 
        : <u> {{$transaction->organization_name}} 
        </u> 
      </td>
     </tr>
      <tr>
        <th>
         Date/Time of Request
       </th> 
       <td> 
        : <u> 
            @php echo date('M d, Y H:i A', strtotime($transaction->created_at)); @endphp 
        </u> 
       </td>
     </tr>
    </table>
 @endforeach       
 <br>
<table class="table" style="width:100%; border:1px solid black !important;">
  <thead style="text-align:left !important;">
  <tr style="vertical-align: middle; border:1px solid black;">
  <th style="vertical-align: middle; border:1px solid black !important; align:center !important;">Trans. No.</th>
  <th style="vertical-align: middle; border:1px solid black !important;">Requested Material</th>
  <th style="vertical-align: middle; border:1px solid black !important;">Pc(s)</th>
</tr>
</thead>
<tbody>
      @foreach($transactions as $transaction)
    <tr>
      <td style="border:1px solid black !important; text-align:center !important;"> 
        {{$transaction->requestID}}
      </td>    
        @foreach($requests as $req)    
      @if($transaction->request_id == $req->request_id)         
            <td style="border:1px solid black !important; text-align:left !important;"> {{$req->iec_title}}
              <br><br>
            </td>
            <td style="border:1px solid black !important; text-align:center !important;">  {{$req->request_material_quantity}}
            </td>
      @endif          
    </tr>
      @endforeach
  @endforeach
    <tr>
      <td style="border:2px solid black !important; text-align:center !important;" colspan="3">
      *** Nothing Follows ***
      </td>
    </tr>
 </tbody>
</table>
  <br>
  <br> 
 <table>
  <tr>
    <td style="width:40%;">
    &nbsp;
    </td>
    <td style="width:40%;">
     <b>Prepared by:</b> 
     <br>
     <br>
     {{Auth::user()->name}}
     <br>
     {{Auth::user()->user_position}}
    </td>
  </tr>
</table>
        </div>
  </div> 
   
  </div> <!-- /.box-body -->
</center>
</div>
 <button id="btnPrintPreview" style="visibility:hidden;">Print</button>
@endsection
 
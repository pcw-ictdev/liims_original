@extends('layouts.master')
@section('content')
 <script src="/js/jquery-1.12.4.min.js"></script>
<script src="/js/form-scripts.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#print_title').text('IEC Materials Inventory Report');
  });
</script>

<meta charset=utf-8 />
<div class="box box-primary">
  <div class="form-horizontal">
  <div id='print_content' class="print_content">
    <center>
<div class="box-body">
<br>   

<h4 style="text-align: left !important"> Period Covered: @php echo date('M d, Y', strtotime($date_range_from)); @endphp - @php echo date('M d, Y', strtotime($date_range_to)); @endphp</h4>
<table class="table table-bordered" style="margin:0; width:100%; border:1px solid black !important;">
  <thead style="text-align:left !important;">
  <tr style="border:1px solid black;"> 
            <th  style="border:1px solid black !important; text-align:center !important;">No.</th>
            <th  style="border:1px solid black !important; text-align:center !important;">Title</th>
            <th  style="border:1px solid black !important; text-align:center !important;">Beginning Balance of the month</th>
            <th  style="border:1px solid black !important; text-align:center !important;">No. of Materials Released</th>
            <th  style="border:1px solid black !important; text-align:center !important;">Ending Balance of the month</th>
           </tr  style="border:1px solid black !important; text-align:center !important;">
        </thead>
        <tbody>
        @php $rowCount = 0; @endphp
          @foreach($aiecs as $iec)
          <tr>
            <td style="border:1px solid black !important; text-align: center !important;">
             @php $rowCount = $rowCount + 1;
               echo $rowCount; 
             @endphp
            </td>
            <td style="border:1px solid black !important; text-align: center !important;">
              {{$iec->iec_title}}</td>
            
              <input type="hidden" id="txt{{$iec->id}}" value="0">
            <td id="td{{$iec->id}}" style="border:1px solid black !important; text-align: center !important;">{{$iec->iec_threshold}}</td>
            <td id="td2{{$iec->id}}" style="border:1px solid black !important; text-align: center !important;">0

            <?php foreach($iecinventoriesPieces as $inventoryiec11) { if($iec->id == $inventoryiec11->iec_update_id) { echo $inventoryiec11->total_requested_pieces; } } ?>
              

            </td>
            <td id="td3{{$iec->id}}"  style="border:1px solid black !important; text-align: center !important;">{{$iec->iec_threshold}}</td>
              @foreach($iecinventoriesEndingBalance as $endbalance)
              @if($iec->id == $endbalance->iec_update_id)
            @foreach($iecinventories as $inventoryiec)
            @php
            $acount = [];
            @endphp
              @if($inventoryiec->iec_update_id == $iec->id)
                <script type="text/javascript">
                 $(document).ready(function(){
                  $('#txt{{$iec->id}}').val({{$inventoryiec->iec_update_threshold}});
                     if($('#txt{{$iec->id}}').val()!= null){
                      $('#td{{$iec->id}}').empty();
                      $('#td2{{$iec->id}}').empty();
                      $('#td3{{$iec->id}}').empty();
                      $('#td{{$iec->id}}').append({{$inventoryiec->iec_update_threshold}});

                      $('#td2{{$iec->id}}').append(<?php foreach($iecinventoriesPieces as $inventoryiec11) { if($iec->id == $inventoryiec11->iec_update_id) {echo $inventoryiec11->total_requested_pieces; break; } } ?>);

                      $('#td3{{$iec->id}}').append(<?php foreach($iecinventoriesEndingBalance as $inventoryiec2) { if($inventoryiec2->iec_update_id == $iec->id) {echo number_format($inventoryiec2->iec_current_threshold); break; } } ?>);
                     }
                   });
                </script>
 
                @break
              @endif  
            @endforeach
 
          @break
          @endif
          @endforeach

 
             </tr>
           @endforeach
    <tr>
      <td style="border:1px solid black !important; text-align:center !important;" colspan="7">
      *** Nothing Follows ***
      </td>
    </tr>
  </tbody>
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
    <td style="width:30%; background-color: none !important; text-align:center !important;">
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
 <table style="width:100%; background-color: none !important;">
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
  <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
  <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
  <tr>
    <td style="width:50%; background-color: none !important; text-align:center !important;">
     <b>Report Generated By:</b> 
     <br>
     <br>
     <br>
     <br>
     {{Auth::user()->name}} 
     <br>
     {{Auth::user()->user_position}}
    </td>
    <td style="width:50%; background-color: none !important; text-align:center !important;">
     <b>Noted By:</b> 
     <br>
     <br>
     <br>
     <br>
     {{$usigns}}
     <br>
     {{$uposition}}
    </td>
  </tr>
</table>
        </div>
  </div> 
   
  </div> <!-- /.box-body -->
</center>
</div>
 <button id="btnInventoryIECLogsPreview" style="visibility:hidden;">Print</button>

<script type="text/javascript">
  $(document).ready(function(){
    $('#btnInventoryIECLogsPreview').click();
  });
</script>
@endsection
 
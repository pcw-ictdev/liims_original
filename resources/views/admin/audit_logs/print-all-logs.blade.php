@extends('layouts.master')

 

@section('content')
 <script src="/js/jquery-1.12.4.min.js"></script>
<script src="/js/form-scripts.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#print_title').text('Audit Logs');
    $('#btnAuditLogsPreview').click();
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
          <th style="border:1px solid black !important; text-align:center !important;">No</th>
          <th style="border:1px solid black !important; text-align:center !important;">Date</th>
           <th style="border:1px solid black !important; text-align:center !important;">Module</th>
          <th style="border:1px solid black !important; text-align:center !important;">Activity</th>
          <th style="border:1px solid black !important; text-align:center !important;">Field Affected</th>
          <th style="border:1px solid black !important; text-align:center !important;">Old Value</th>
          <th style="border:1px solid black !important; text-align:center !important;">New Value</th>
          <th style="border:1px solid black !important; text-align:center !important;">Modified By</th>
		</tr>
</thead>
<tbody>
         <tr>
	  @php $rowCount = 0; @endphp
      @foreach($logs as $log)
      <tr>
      <td style="border:1px solid black !important; text-align: center !important;"> 
 		@php $rowCount = $rowCount + 1;
 			echo $rowCount; 
 		@endphp
      </td>
      <td style="border:1px solid black !important; text-align: center !important;"> @php echo date('M d, Y h:i:s', strtotime($log->created_at)); @endphp
      </td>
      <td style="border:1px solid black !important; text-align: center !important;">{{$log->audit_module}}</td>   
      <td style="border:1px solid black !important; text-align: center !important;">{{$log->audit_activity}}</td>    
      <td style="border:1px solid black !important; text-align: center !important;">{{$log->audit_field_affected}}</td>
      <td style="border:1px solid black !important; text-align: center !important;">{{$log->audit_old_value}}</td> 
      <td style="border:1px solid black !important; text-align: center !important;">{{$log->audit_new_value}}</td> 
      <td style="border:1px solid black !important; text-align: center !important;">{{$log->name}}</td>           
   </tr>
    @endforeach
    <tr>
      <td style="border:1px solid black !important; text-align:center !important;" colspan="8">
      *** Nothing Follows ***
      </td>
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
 <button id="btnAuditLogsPreview" style="visibility:hidden;">Print</button>
@endsection
 
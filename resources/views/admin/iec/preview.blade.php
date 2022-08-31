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
 @foreach($printings as $printing)
    <table style="text-align:left;">
      <tr>
        <th>
         Printing Contractor
       </th> 
       <td> 
        : <u>
         {{$printing->iec_printing_contractor}} 
       </u> 
     </td>
     </tr>
      <tr>
        <th>
         Printing Cost
       </th> 
       <td> 
        : <u> {{$printing->iec_printing_cost}} 
        </u> 
      </td>
     </tr>
      <tr>
        <th>
         Pcs
       </th> 
       <td> 
        : <u> {{$printing->iec_printing_pcs}} 
        </u> 
      </td>
     </tr>
        <th>
         Remarks
       </th> 
       <td> 
        : <u> {{$printing->iec_printing_remarks}} 
        </u> 
      </td>
     </tr>
        <th>
         Created by
       </th> 
       <td> 
        : <u> {{$printing->name}} 
        </u> 
      </td>
     </tr>
      <tr>
        <th>
         Date & Time Created
       </th> 
       <td> 
        : <u> 
            @php echo date('M d, Y H:i A', strtotime($transaction->created_at)); @endphp 
        </u> 
       </td>
     </tr>
  @endforeach
    <tr>
      <td style="border:2px solid black !important; text-align:center !important;" colspan="2">
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
 
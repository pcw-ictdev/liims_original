@extends('layouts.master')

@section('head_title', $page['crumb'])

@section('page_title', $page['title'])

@section('page_subtitle', $page['subtitle'])

@section('content')

@include('layouts.inc.messages')
<script src="/js/jquery-1.12.4.min.js"></script>

<script src="/js/chart/Chart.min.js"></script>
<script src="/js/chart/jquery.min.js"></script>
 

 <script type="text/javascript">
  $(document).ready(function(){
    $('#btnHome').click();
  });
</script>

<style>
  .img-wrapper {display:inline-block; height:159px; overflow:hidden; width:153px;}
  .img-wrapper img {height:159px;}
  .img-wrapper img {border:2px solid #0275db !important;}
  #iecmaterials th {background-color:#00c0ef; !important;}
</style>

  <!-- Bar chart -->
  <div class="box box-primary">
    <button style="display:none;" id="btnHome"></button>

  <!-- /.IEC Materials Needed for Restocking -->
   <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-solid">
            <div class="box-header">
              <h4 class="box-title">IEC Materials Needed for Restocking</h4>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-default" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body"  style="overflow-y: auto; overflow-x: auto; height:240px;">
               <table class="table table=bordered" id="iecmaterials">
                <thead>
                  <tr>
                    <th style="text-align:left;">Title</th>
                    <th style="text-align:left;">Image</th>
                    <th style="text-align:left;">Pcs. Available</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($iecCriticalItems as $iecs)
                  <tr>
                    <td>
                        {{$iecs->iec_title}}
                    </td>
                    <td>
                      <div class="img-wrapper">
                          @if($iecs->iec_image == '') 
                          <img src="/images/Image-Not-Available-Icon.jpg">
                          @else 
                          <img src="{{$iecs->iec_image}}">
                          @endif
                      </div> 
                    </td>
                    <td>
                        {{$iecs->iec_threshold}} 
                        <a href="/admin/iec/critical-items/" id="iecEdit" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
               </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
 </div>
</div>
  <!-- /.END IEC Materials Needed for Restocking -->

<div class="row">
  <div class="col-md-12">
    <hr>
  </div>
</div>


  <!-- /.Distributed per Materials -->
   <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-solid">
            <div class="box-header">
              <h4 class="box-title">Distribution of IEC per Material</h4>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-default" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
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
        <label id="lbl-error-message"> asd</label>
        </p>
    </div> <!-- /.alert -->
</div> <!-- /.form-group -->
<!-- end header error notification -->
              <div class="row">
                <div class="col-md-2">
                  Material
                </div>
                <div class="col-md-3">
                  <select class="form-control" name="txt_select_material" id="txt_select_material">
                    <option value="" selected>Select</option>
                    <option value="ALL">ALL</option>
                    @foreach($asds as $asd)
                      <option value="{{$asd->id}}">{{$asd->material_name}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-1">
                  Year
                </div>
                <div class="col-md-3">
                  <select class="form-control" name="txt_select_year" id="txt_select_year">
                    <option value="@php echo date('Y'); @endphp" selected>@php echo date('Y'); @endphp</option>
                    @foreach($years as $year)
                    @if($year->year_id != date('Y'))
                      <option value="{{$year->year_id}}">{{$year->year_id}}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-info" id="btnFind1">Find</button>
                </div>
              </div>
          <div id="displayChartHere">
            <canvas id="myChart1"></canvas>
            <br>
            </div>
            </div>
            </div>
            <!-- /.box-body -->
 <script type="text/javascript">
$(document).ready(function(){
    qe = [];

    var jan = 0;
    var feb = 0;
    var mar = 0;
    var apr = 0;
    var may = 0;
    var jun = 0;
    var jul = 0;
    var aug = 0;
    var sep = 0;
    var oct = 0;
    var nov = 0;
    var dec = 0;

  $('#btnFind1').click(function(){
    var amaterial = $('#txt_select_material').val();
    var ayear = $('#txt_select_year').val();
  // console.log(ayear + "_" + amaterial);
    aw = [];
    qe = []; 
     $.ajax({
     url: "/admin/requests/graph/1/" + ayear + "_" + amaterial,
     dataType: "json", 
     success: function(data)
      {
        if(data.length >= 1){
        //  console.log(data.length);
           
        } else {
        $('#lbl-error-message').empty();
        $('#div-success-notification').css('display', 'none');
        $('#lbl-error-message').append('No Result found.');
        $('#div-error-notification').css('display', 'block');
        setTimeout(function () {
            $('#div-error-notification').css('display', 'none');
            $('#label-error-message').empty();
        },5000); 
        }

        if(data == 0){
          $('#myChart1display').css('display', 'none');
          $('#myChart1').css('display', 'none');
          $('#myChart1').empty();
          $('#myChart1display').empty();
          $('#displayChartHere').empty();
          

          var jan = 0;
          var feb = 0;
          var mar = 0;
          var apr = 0;
          var may = 0;
          var jun = 0;
          var jul = 0;
          var aug = 0;
          var sep = 0;
          var oct = 0;
          var nov = 0;
          var dec = 0;

        } else {
      for (var ii in data) {
            if(data[ii].new_date == "01"){
              jan = data[ii].total_req;
            } else {
            }  

            if(data[ii].new_date == "02"){
              feb = data[ii].total_req;          
            } else {
            }

            if(data[ii].new_date == "03"){
              mar = data[ii].total_req;           
            }  else {
            }

            if(data[ii].new_date == "04"){
              apr = data[ii].total_req;
            } else {
            } 
  

            if(data[ii].new_date == "05"){
              may = data[ii].total_req;
            } else {
            } 
 

            if(data[ii].new_date == "06"){
              jun = data[ii].total_req;
            } else {
            } 
 
 
            if(data[ii].new_date == "07"){
              jul = data[ii].total_req;
            }  else {
            }
 

            if(data[ii].new_date == "08"){
              aug = data[ii].total_req;
            } else {
            } 
 
 
            if(data[ii].new_date == "09"){
              sep = data[ii].total_req;
            } else {
            } 
 

            if(data[ii].new_date =="10"){
              oct = data[ii].total_req;
            } else {
            } 
 
            if(data[ii].new_date == "11"){
              nov = data[ii].total_req;
            } else {
            } 
 
            if(data[ii].new_date == "12"){
              dec= data[ii].total_req;
            } else {
            } 

            
  } //end for
} //end if 
 
///

$('#displayChartHere').empty();
$('#myChart1').empty();
$('#myChart1').empty();
$('#displayChartHere').css('display', 'block');
$('#displayChartHere').append('<br><canvas id="myChart1"></canvas>');
var ctx = document.getElementById("myChart1").getContext('2d');
var myChart = new Chart(ctx, {
type: 'bar',
data: {
labels: ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"],
datasets: [{
label: 'Year ' + ayear,
data: [jan, feb, mar, apr, may, jun, jul, aug, sep, oct, nov, dec],
backgroundColor: [
'#809fff',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)'
],
borderColor: [
'#809fff',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)'
],
borderWidth: 1
}]
},
options: {
scales: {
yAxes: [{
ticks: {
beginAtZero: true
}
}]
}
}
});    
///
        }
    });
   });
});
$(document).ready(function(){
     var amaterial = 'ALL';
    var ayear = '<?php echo date("Y")?>';
    qe = [];

    var jan = 0;
    var feb = 0;
    var mar = 0;
    var apr = 0;
    var may = 0;
    var jun = 0;
    var jul = 0;
    var aug = 0;
    var sep = 0;
    var oct = 0;
    var nov = 0;
    var dec = 0;

     $.ajax({
     url: "/admin/requests/graph/1/" + ayear + "_" + amaterial,
     dataType: "json", 
     success: function(data)
      {
      for (var ii in data) {
            if(data[ii].new_date == "01"){
              jan = data[ii].total_req;
            } else {
            }  

            if(data[ii].new_date == "02"){
              feb = data[ii].total_req;          
            } else {
            }

            if(data[ii].new_date == "03"){
              mar = data[ii].total_req;           
            }  else {
            }

            if(data[ii].new_date == "04"){
              apr = data[ii].total_req;
            } else {
            } 
  

            if(data[ii].new_date == "05"){
              may = data[ii].total_req;
            } else {
            } 
 

            if(data[ii].new_date == "06"){
              jun = data[ii].total_req;
            } else {
            } 
 
 
            if(data[ii].new_date == "07"){
              jul = data[ii].total_req;
            }  else {
            }
 

            if(data[ii].new_date == "08"){
              aug = data[ii].total_req;
            } else {
            } 
 
 
            if(data[ii].new_date == "09"){
              sep = data[ii].total_req;
            } else {
            } 
 

            if(data[ii].new_date =="10"){
              oct = data[ii].total_req;
            } else {
            } 
 
            if(data[ii].new_date == "11"){
              nov = data[ii].total_req;
            } else {
            } 
 
            if(data[ii].new_date == "12"){
              dec= data[ii].total_req;
            } else {
            } 

            
 } 
 
///
var ctx = document.getElementById("myChart1").getContext('2d');
var myChart = new Chart(ctx, {
type: 'bar',
data: {
labels: ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"],
datasets: [{
label: 'Year ' + ayear,
data: [jan, feb, mar, apr, may, jun, jul, aug, sep, oct, nov, dec],
backgroundColor: [
'#809fff',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)'
],
borderColor: [
'#809fff',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)',
'#809fff)'
],
borderWidth: 1
}]
},
options: {
scales: {
yAxes: [{
ticks: {
beginAtZero: true
}
}]
}
}
});    
///
        }
 
  });
});
</script>
 
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
 </div>
 

    <!-- /.Distributed per Materials -->
   <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-solid">
            <div class="box-header">
              <h4 class="box-title">Distribution of IEC Materials per Organization</h4>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-default" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
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
              <div class="row">
                <div class="col-md-2">
                  Organization
                </div>
                <div class="col-md-3">
                  <select class="form-control" name="txt_select_org_type" id="txt_select_org_type">
                    <option value="" selected>Select</option>
                    <option value="ALL">ALL</option>
                    @foreach($orgtypes as $orgtype)
                      <option value="{{$orgtype->org_type_id}}">{{$orgtype->org_type_code}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-1">
                  Year
                </div>
                <div class="col-md-3">
                  <select class="form-control" name="txt_select_year2" id="txt_select_year2">
                    <option value="@php echo date('Y'); @endphp" selected>@php echo date('Y'); @endphp</option>
                    @foreach($years as $year)
                      @if($year->year_id != date('Y'))
                      <option value="{{$year->year_id}}">{{$year->year_id}}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-info" id="btnFindOrg">Find</button>
                </div>
              </div>
          <div id="displayChartHere2">
            <canvas id="myChart2"></canvas>
            <br>
            </div>
            </div>
            <!-- /.box-body -->

 <script type="text/javascript">
$(document).ready(function(){
    qe2 = [];

    var jan2 = 0;
    var feb2 = 0;
    var mar2 = 0;
    var apr2 = 0;
    var may2 = 0;
    var jun2 = 0;
    var jul2 = 0;
    var aug2 = 0;
    var sep2 = 0;
    var oct2 = 0;
    var nov2 = 0;
    var dec2 = 0;

  $('#btnFindOrg').click(function(){
   var aorg = $('#txt_select_org_type').val();
    var ayear2 = $('#txt_select_year2').val();
    aw = [];
    qe2 = [];
  //  console.log(ayear2 + "_" + aorg);
     $.ajax({
     url: "/admin/requests/graph/org/" + ayear2 + "_" + aorg,
     dataType: "json", 
     success: function(data2)
      {
        if(data2.length >= 1){
           
        } else {
        $('#lbl-error-message2').empty();
        $('#div-success-notification2').css('display', 'none');
        $('#lbl-error-message2').append('No Result found.');
        $('#div-error-notification2').css('display', 'block');
        setTimeout(function () {
            $('#div-error-notification2').css('display', 'none');
            $('#label-error-message2').empty();
        },5000); 
        }

        if(data2 == 0){
          $('#myChart2display').css('display', 'none');
          $('#myChart2').css('display', 'none');
          $('#myChart2').empty();
          $('#myChart2display').empty();
          $('#displayChartHere2').empty();
          

          var jan2 = 0;
          var feb2 = 0;
          var mar2 = 0;
          var apr2 = 0;
          var may2 = 0;
          var jun2 = 0;
          var jul2 = 0;
          var aug2 = 0;
          var sep2 = 0;
          var oct2 = 0;
          var nov2 = 0;
          var dec2 = 0;

        } else {
      for (var iii in data2) {
            if(data2[iii].new_date == "01"){
              jan2 = data2[iii].total_req;
            } else {
            }  

            if(data2[iii].new_date == "02"){
              feb2 = data2[iii].total_req;          
            } else {
            }

            if(data2[iii].new_date == "03"){
              mar2 = data2[iii].total_req;           
            }  else {
            }

            if(data2[iii].new_date == "04"){
              apr2 = data2[iii].total_req;
            } else {
            } 
  

            if(data2[iii].new_date == "05"){
              may2 = data2[iii].total_req;
            } else {
            } 
 

            if(data2[iii].new_date == "06"){
              jun2 = data2[iii].total_req;
            } else {
            } 
 
 
            if(data2[iii].new_date == "07"){
              jul2 = data2[iii].total_req;
            }  else {
            }
 

            if(data2[iii].new_date == "08"){
              aug2 = data2[iii].total_req;
            } else {
            } 
 
 
            if(data2[iii].new_date == "09"){
              sep2 = data2[iii].total_req;
            } else {
            } 
 

            if(data2[iii].new_date =="10"){
              oct2 = data2[iii].total_req;
            } else {
            } 
 
            if(data2[iii].new_date == "11"){
              nov2 = data2[iii].total_req;
            } else {
            } 
 
            if(data2[iii].new_date == "12"){
              dec2 = data2[iii].total_req;
            } else {
            } 

            
  } //end for
} //end if 
 
///

$('#displayChartHere2').empty();
$('#myChart2').empty();
$('#myChart2').empty();
$('#displayChartHere2').css('display', 'block');
$('#displayChartHere2').append('<br><canvas id="myChartOrg"></canvas>');
var ctx2 = document.getElementById("myChartOrg").getContext('2d');
var myChart2 = new Chart(ctx2, {
type: 'bar',
data: {
labels: ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"],
datasets: [{
label: 'Year ' + ayear2,
data: [jan2, feb2, mar2, apr2, may2, jun2, jul2, aug2, sep2, oct2, nov2, dec2],
backgroundColor: [
'#fcc0c0',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)'
],
borderColor: [
'#fcc0c0',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)'
],
borderWidth: 1
}]
},
options: {
scales: {
yAxes: [{
ticks: {
beginAtZero: true
}
}]
}
}
});    
///
        }
    });
   });
});
$(document).ready(function(){
     var aorg = 'ALL';
    var ayear2 = '<?php echo date("Y")?>';
    qe = [];

    var jan2 = 0;
    var feb2 = 0;
    var mar2 = 0;
    var apr2 = 0;
    var may2 = 0;
    var jun2 = 0;
    var jul2 = 0;
    var aug2 = 0;
    var sep2 = 0;
    var oct2 = 0;
    var nov2 = 0;
    var dec2 = 0;

     $.ajax({
     url: "/admin/requests/graph/org/" + ayear2 + "_" + aorg,
     dataType: "json", 
     success: function(dataOrg)
      {
    //  console.log(dataOrg);
      for (var ii in dataOrg) {
            if(dataOrg[ii].new_date == "01"){
              jan2 = dataOrg[ii].total_req;
            } else {
            }  

            if(dataOrg[ii].new_date == "02"){
              feb2 = dataOrg[ii].total_req;          
            } else {
            }

            if(dataOrg[ii].new_date == "03"){
              mar2 = dataOrg[ii].total_req;           
            }  else {
            }

            if(dataOrg[ii].new_date == "04"){
              apr2 = dataOrg[ii].total_req;
            } else {
            } 
  

            if(dataOrg[ii].new_date == "05"){
              may2 = dataOrg[ii].total_req;
            } else {
            } 
 

            if(dataOrg[ii].new_date == "06"){
              jun2 = dataOrg[ii].total_req;
            } else {
            } 
 
 
            if(dataOrg[ii].new_date == "07"){
              jul2 = dataOrg[ii].total_req;
            }  else {
            }
 

            if(dataOrg[ii].new_date == "08"){
              aug2 = dataOrg[ii].total_req;
            } else {
            } 
 
 
            if(dataOrg[ii].new_date == "09"){
              sep2 = dataOrg[ii].total_req;
            } else {
            } 
 

            if(dataOrg[ii].new_date =="10"){
              oct2 = dataOrg[ii].total_req;
            } else {
            } 
 
            if(dataOrg[ii].new_date == "11"){
              nov2 = dataOrg[ii].total_req;
            } else {
            } 
 
            if(dataOrg[ii].new_date == "12"){
              dec2 = dataOrg[ii].total_req;
            } else {
            } 

            
 } 
 
///
var ctx = document.getElementById("myChart2").getContext('2d');
var myChart = new Chart(ctx, {
type: 'bar',
data: {
labels: ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"],
datasets: [{
label: 'Year ' + ayear2,
data: [jan2, feb2, mar2, apr2, may2, jun2, jul2, aug2, sep2, oct2, nov2, dec2],
backgroundColor: [
'#fcc0c0',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)'
],
borderColor: [
'#fcc0c0',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)',
'#fcc0c0)'
],
borderWidth: 1
}]
},
options: {
scales: {
yAxes: [{
ticks: {
beginAtZero: true
}
}]
}
}
});    
///
        }
 
  });
});
</script>
  <!-- /.END Distributed per Organization Types -->

</div>
 


 

</div>
 <script type="text/javascript">
 $(document).ready(function(){
  $('#btnFindOrg').click(function(){
    //2
    var aorg = $('#txt_select_org_type').val();
    var ayear = $('#txt_select_year2').val();
    aw = [];
    qe = [];
  //  console.log(ayear + "_" + aorg);
     $.ajax({
     url: "/admin/requests/graph/org/" + ayear + "_" + aorg,
     dataType: "json", 
     success: function(data)
      {
        if(data ==0){
          $('#divGraph2').empty();
          $('#divGraph2').append('<h4>No result/s found.</h4>');
        } else {
  // $.each(data, function(key, value) {
    var asd = [];
    asd = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
 
 
$.each(asd, function(index, val) {
 
$.each(data, function(ii, val) {
         if(asd[index] == "01"){
            aw.push('JAN');
            if(data[ii].new_date == "01"){
            qe.push(data[ii].total_req);
            
            } else {
              qe.push(0);
            }  
         }
         if(asd[index] == "02"){
            aw.push('FEB');
            if(data[ii].new_date == "02"){
            qe.push(data[ii].total_req);
            
            } else {
              qe.push(0);
            }
         }
         if(asd[index] == "03"){
            aw.push('MAR');
            if(data[ii].new_date == "03"){
            qe.push(data[ii].total_req);
           
            }  else {
              qe.push(0);
            }
         }
         if(asd[index] == "04"){
            aw.push('APR');
            if(data[ii].new_date == "04"){
            qe.push(data[ii].total_req);
            
            } else {
              qe.push(0);
            } 
         }
         if(asd[index] == "05"){
            aw.push('MAY');
            if(data[ii].new_date == "05"){
            qe.push(data[ii].total_req);
            
            } else {
              qe.push(0);
            } 
         }
         if(asd[index] == "06"){
            aw.push('JUN');
            if(data[ii].new_date == "06"){
            qe.push(data[ii].total_req);
            
            } else {
              qe.push(0);
            } 
         }
         if(asd[index] == "07"){
            aw.push('JUL');
            if(data[ii].new_date == "07"){
            qe.push(data[ii].total_req);
            
            }  else {
              qe.push(0);
            }
         }
         if(asd[index] == "08"){
            aw.push('AUG');
            if(data[ii].new_date == "08"){
            qe.push(data[ii].total_req);
            
            } else {
              qe.push(0);
            } 
         }
         if(asd[index] == "09"){
            aw.push('SEP');
            if(data[ii].new_date == "09"){
            qe.push(data[ii].total_req);
            
            } else {
              qe.push(0);
            } 
         }
         if(asd[index] == "10"){
            aw.push('OCT');
            if(data[ii].new_date == "10"){
            qe.push(data[ii].total_req);
            
            } else {
              qe.push(0);
            } 
         }
         if(asd[index] == "11"){
            aw.push('NOV');
            if(data[ii].new_date == "11"){
            qe.push(data[ii].total_req);
            
            } else {
              qe.push(0);
            } 
         }         
         if(asd[index] == "12"){
            aw.push('DEC');
            if(data[ii].new_date == "12"){
            qe.push(data[ii].total_req);
            
            } else {
              qe.push(0);
            } 
         }
     
  });
});
var ctx = '';
var myChart = '';
if(aorg =='ALL') {
  alabel = ayear;
} else {
  alabel = ayear;
}
$('#divGraph2').empty();
$('#divGraph2').append("<canvas id='iecMaterialsPerPROHorizontalOrg'></canvas>");
ctx = document.getElementById("iecMaterialsPerPROHorizontalOrg").getContext('2d');
myChart = new Chart(ctx, {
type: 'bar',
data: {
labels: aw,
datasets: [{
label: alabel,
data: qe,
backgroundColor: '#fcc0c0',
borderColor: '#fcc0c0',
borderWidth: 2
}]
},
options: {
responsive: true,
scales: {
xAxes: [{
            barPercentage: 2
        }],
yAxes: [{
ticks: {
beginAtZero: true
}
}]
}
}
});  
//end 
          } //endif
        } // success
    });
  });
    $('#btnFind11').click();
    $('#btnFindOrg2').click();

});

 </script>

          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
 </div>
 

 
<div class="box-footer">

    </div> <!-- /.box-footer -->
<!-- page script -->
</div> <!-- /.box box-primary -->

@endsection




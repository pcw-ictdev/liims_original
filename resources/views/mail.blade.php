 
<!DOCTYPE html>
<html>
<head>
  <title>Mail Sending</title>

<style>
tr:nth-child(even) {background-color: #f2f2f2 !important;}
</style>

</head>
<body>

  <h4>
    <p>
    Hello!
 
    </p>
  </h4>

  <h4 style="text-align:left; color:#000;">Please see below table for IEC Material(s) that already reached the restocking threshold.</h4>
            <div class="box-body"  style="overflow-y: auto; overflow-x: auto; height:240px;">
               <table style="border: 1px solid black !important; width:100% !important;">
                <thead>
                   
                    <th style="text-align:center; color:#000; border: 1px solid black !important;">IEC Material</th>
                    <th style="text-align:center; color:#000; border: 1px solid black !important;">Pcs. Available</th>
                   
                </thead>
                <tbody>
                  @foreach($iecCriticalItems as $iecs)
                  <tr>
                    <td style="text-align: center !important; color:#000; border: 1px solid black !important;">
                        {{$iecs->iec_title}}
                    </td>
                    <td style="text-align: center !important; color:#000; border: 1px solid black !important;">
                        {{$iecs->iec_threshold}} 
                    </td>
                  </tr>
                  @endforeach
                </tbody>
            </table>
        </div>
          <div class="box-body">
            <h4><p  style="text-align:left; color:#000;"><i>[NOTE: This is an auto generated email from PIMS]</p></h4>
          </div>
                
</body>
</html>
<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controllers;
use Auth;
use App\Post;
use App\organizations;
use App\organization_type;
use App\iecrequests;
use App\provinces;
use App\cities;
use App\materials;
use App\requests;
use App\iec;
use App\clients;
use App\transaction;
use App\user_roles;
use App\activity_logs;
use App\User;
use App\Mail\sendMail;
use App\auditLog;
use Mail;
use Lang;
class requestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function permission()
    {
      $userTypes = user_roles::userRolesList();
      $user_material_request = array_column($userTypes, 'user_material_request');
      $user_material_request = array_merge($user_material_request);
      if($user_material_request[0] == "2"){
        abort(403, 'Page not available');
        }
    }


public function EmailNotif()
{ 
    $email = array();
    $user_name = array();
    $emails = array();
    $emails = User::uselectAllInventoryUsers();
    $user_names = array_column($emails, 'name');
    $user_name = array_merge($user_names);
    $user_email = array_column($emails, 'email');
    $user_email = array_merge($user_email);
  $iecCriticalItems = iec::selectCriticalItems();
  if(count($iecCriticalItems) > 0){
  $data = array('iecCriticalItems'=>$iecCriticalItems, 'emails'=>$emails);

  $email =$user_email;
    Mail::send('mail',$data, function($message) use ($email) {
    $message->to($email)
        ->subject('List of IEC Materials needed for restocking'); 
    $message->from('systemadmin@pcw.gov.ph','Publication Inventory Management System');
 
      });
    }
}


    public function index()
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        requestController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Request History',
            'title'     => 'Request History',
            'subtitle'  => ''
        ];
           $transactions = requests::AllTransactionList();
           return view('admin.requests.index', compact('page','transactions'));   
    }
}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        requestController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Create New Request',
            'title'     => 'Create New Request',
            'subtitle'  => ''
        ];
          // $recid = requests::orderBy('rec_id',  'DESC')->first();   
           $ryear = date("Y");
           $requestID = requests::selectTransactionCurrentYear($ryear);  
           $transactionNo = count($requestID) + 1;
           if($transactionNo >=  10000) {
            $txnNo = 'PIMS-' . $ryear . '-' . $transactionNo;
           }
           if($transactionNo <= 9999) {
            $txnNo = 'PIMS-' . $ryear . '-' . '0' . $transactionNo;
           }
           if($transactionNo <= 999) {
            $txnNo = 'PIMS-' . $ryear . '-' . '00' . $transactionNo;
           }
           if($transactionNo <= 99) {
            $txnNo = 'PIMS-' . $ryear . '-' . '000' . $transactionNo;
           }
           if($transactionNo <= 9) {
            $txnNo = 'PIMS-' . $ryear . '-' . '0000' . $transactionNo;
           }

           $recid = $txnNo;
           $cities = cities::searchCityList();
           $orgtypes = organization_type::selectOrganizationType();
           $iecs = iec::selectAllRecord();
           return view('admin.requests.create', compact('page', 'recid', 'cities', 'orgtypes', 'iecs'));    
    }
}
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->txt_request_organization =='' || $request->txt_request_designation =='' || $request->txt_request_name=='' ||$request->txt_request_purpose=='') {
               $message = "Oops, sorry. Something went wrong. Please try again.";
               return Redirect::back()->withErrors($message);
            }

         if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        requestController::permission();
        $requestedMaterial = array(); 
        $requestedMaterial = $request->txt_selectedMaterial;
 
        if($requestedMaterial == null) {
          $message = "Invalid transaction. Please add item on the list.";
          return Redirect::back()->withErrors($message)->withInput($request->input());     
        } else {
        
             $validate = materials::validateNewEntryDetails($request);
             $validateRequest = requests::validateRequest($request);
             if(count($validateRequest) >= 1){
             return redirect('/admin/requests/create'); 
             } else {
             $activityLog = activity_logs::insertMaterialRequestLogs($request); 
             $activityRequestLog = activity_logs::insertMaterialLogs($request); 
             auditLog::searchIECRequestRecord($request);
             $result = requests::insertNewRecord($request);

        if ($result === true) {
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'IEC Material Acknowledgement Receipt',
            'title'     => 'IEC Material Acknowledgement Receipt',
            'subtitle'  => ''
        ];
          $requests = requests::previewRequest($request);
           $ausigns = User::selectReportSignatory(); 
           if(count($ausigns) ==0){
             $name = '';  
             $position = '';        
             $usigns = $name;
             $uposition = $position;
           } else {
             $usigns = $ausigns[0]->name;
             $uposition = $ausigns[0]->user_position;
           }
            return view('admin.requests.preview-request', compact('page', 'requests', 'usigns', 'uposition')); 
            requestController::EmailNotif();
            
        } else {
            return Redirect::back()->withErrors($result);
        }
      }
      }
    }
}
 
    public function previewRequest()
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        requestController::permission();
           return view('admin.requests.preview-request');   
    }
}
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function searchProvince()
    {
        $result = provinces::searchProvince();
        return $result;
    }

    public function searchCity()
    {
        $result = cities::searchCity();
        return $result;
    }

    public function searchCityName()
    {
        $result = cities::searchCity();
        return $result;
    }

    public function validateClientInfo($client)
    {
        $result = clients::validateNewClientInfo($client);
        return $result;
    }

    public function saveClientInfo($client)
    {

        $result = clients::insertClientInfo($client);
        auditLog::StoreNewClientOnTheFlyLogs($client);
         return $result;
    }

    public function findClientInfos($client)
    {

        $result = clients::selectAllClientsList();
         return $result;
    }
    public function findClientInfo($client)
    {
        $result = clients::findInfo($client);
         return $result;
    }

    public function findTransactionByKeyword(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
    requestController::permission();
    $transactions = requests::selectTransactionKeywordResult($request);
 
        
        $organizations = organizations::organizationsList();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Request List',
            'title'     => 'Request List',
            'subtitle'  => ''
        ]; 
        return view('admin.requests.index', compact('page', 'transactions', 'organizations'));  
    }
}

    public function findTransactionByDate(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        requestController::permission();
        $transactions = requests::findTransactionByDate($request);
         $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Request List',
            'title'     => 'Request List',
            'subtitle'  => ''
        ]; 
        return view('admin.requests.index', compact('page', 'transactions')); 
    }
}
    public function printAllRequests(Request $request)
    {       
      if(!$request->chk_selectOneRequest){
        $message='Please check atleast one or more records to print.';
        return Redirect::back()->withErrors($message);
      }
      if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
    requestController::permission();
   $resu = requests::printSelectedTransaction($request);
   $asd = array_column($resu, 'id');
   $asd = array_merge($asd);
   $collection_rec_id = collect($asd);
   $recids = $collection_rec_id->unique()->values()->all(); 
   $recids = implode(",", $recids);
   $transactions = transaction::selectTransactionByRecID($recids);
   $asd2 = array_column($transactions, 'id');
   $asd2 = array_merge($asd2);
   $requests = requests::findMultipleTransaction($asd2);
   $organizations = organizations::organizationsList();
   $ausigns = User::selectReportSignatory(); 
   $date_range_from = date('M d, Y', strtotime($request->txt_iec_date_from));
   $date_range_to = date('M d, Y', strtotime($request->txt_iec_date_to));           
           if(count($ausigns) ==0){
             $name = '';  
             $position = '';        
             $usigns = $name;
             $uposition = $position;
           } else {
             $usigns = $ausigns[0]->name;
             $uposition = $ausigns[0]->user_position;
           }
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Request',
            'title'     => 'Request',
            'subtitle'  => ''
        ]; 
       //  if(count($transactions) == 1) {
       //     return view('admin.requests.preview', compact('page', 'requests', 'transactions','organizations', 'usigns'));             
       // } else {
           return view('admin.requests.preview-all', compact('page', 'requests', 'transactions','organizations', 'usigns', 'uposition', 'date_range_from', 'date_range_to'));  
       // }
   }
}
    public function printOneTransaction($id)
    {
  if(Auth::user()->is_deleted == 1){
      auth()->logout();
      return redirect('/login');
  } else {
   requestController::permission();
   $resu= requests::printSingleTransaction($id);
   $asd = array_column($resu, 'id');
   $asd = array_merge($asd);
   $collection_rec_id = collect($asd);
   $recids = $collection_rec_id->unique()->values()->all(); 
   $recids = implode(",", $recids);
   
   $transactions = transaction::selectTransactionByRecID($recids);
   $asd2 = array_column($transactions, 'request_id');
   $asd2 = array_merge($asd2);
   $requests = requests::findSingleTransaction($asd2);
   $organizations = organizations::organizationsList();
   $ausigns = User::selectReportSignatory(); 
           if(count($ausigns) ==0){
             $name = '';  
             $position = '';        
             $usigns = $name;
             $uposition = $position;
           } else {
             $usigns = $ausigns[0]->name;
             $uposition = $ausigns[0]->user_position;
           }

        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Request List',
            'title'     => 'Request List',
            'subtitle'  => ''
        ]; 
           return view('admin.requests.preview', compact('page', 'requests', 'transactions','organizations', 'usigns', 'uposition'));  

    }
}


  public function materialInventoryReport()
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        requestController::permission();
       // $transactions = requests::findTransactionByDate($request);
         $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Request List',
            'title'     => 'Request List',
            'subtitle'  => ''
        ]; 
        return view('admin.requests.material_inventory_report', compact('page')); 
    }
}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}

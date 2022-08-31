<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controllers;
use App\Post;
use App\user_roles;
use App\auditLog;
use App\requests;
use App\clients;
use App\organizations;
use App\contractors;
use App\iec;
use Auth;
use App\iecStockUpdate;
use App\iecPrintingLogs;
class reportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function permission()
    {
      $userTypes = user_roles::userRolesList();
      $user_reports = array_column($userTypes, 'user_reports');
      $user_reports = array_merge($user_reports);
      if($user_reports[0] == "2"){
        abort(403, 'Page not available');
        }
    }

    public function index()
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        reportsController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => "Reports",
            'title'     => "Reports",
            'subtitle'  => ''
        ];
           //$logs = auditLog::selectAllList();
           return view('admin.reports.index', compact('page')); 
        }
    }

    public function indexSelectReport(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        reportsController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => "Reports",
            'title'     => "Reports",
            'subtitle'  => ''
        ];
          $selected = $request->txt_report_menu;
           if($request->txt_report_menu == 1){
            $userTypes = user_roles::userRolesList();
            $user_code_library = array_column($userTypes, 'user_code_library');
            $user_code_library = array_merge($user_code_library);
            if($user_code_library[0] == "2"){
                abort(403, 'Page not available');
            }
            $clients = clients::selectAllLists($request);
            return view('admin.reports.index', compact('page', 'clients', 'selected')); 
           }   
           if($request->txt_report_menu == 2){
            $userTypes = user_roles::userRolesList();
            $user_code_library = array_column($userTypes, 'user_code_library');
            $user_code_library = array_merge($user_code_library);
            if($user_code_library[0] == "2"){
                abort(403, 'Page not available');
            }
            $organizations = organizations::selectAllLists($request);
            return view('admin.reports.index', compact('page', 'organizations', 'selected')); 
           }        
           if($request->txt_report_menu == 3){
            $userTypes = user_roles::userRolesList();
            $user_code_library = array_column($userTypes, 'user_code_library');
            $user_code_library = array_merge($user_code_library);
            if($user_code_library[0] == "2"){
                abort(403, 'Page not available');
            }
            $contractors = contractors::selectAllRecords($request);
            return view('admin.reports.index', compact('page', 'contractors', 'selected')); 
           }  
           if($request->txt_report_menu == 4){
            $iecs = iec::selectAllRecords();
            if(count($iecs) == 0){
            $message = "No result/s found.";
            return Redirect::back()->withErrors($message);
            }
            $date_range_from = date('M d, Y', strtotime($request->txt_iec_date_from));
            $date_range_to = date('M d, Y', strtotime($request->txt_iec_date_to));
            $iecs = iec::selectAllRecords();
            $aiecsCounts = iec::selectAllRecordsCount($request);
            $aiecinventories = iecStockUpdate::selectIECAllRecords21($request);
            $aiecinventoriesPieces = iecStockUpdate::selectIECAllRecords212($request);
            $aiecinventoriesEndingBalance = iecStockUpdate::selectIECAllRecords213($request);
            return view('admin.reports.index', compact('page','aiecinventories', 'aiecinventoriesPieces', 'aiecinventoriesEndingBalance', 'selected', 'date_range_from', 'date_range_to', 'iecs', 'aiecsCounts')); 
           }    
           if($request->txt_report_menu == 5){
            $userTypes = user_roles::userRolesList();
            $user_audit_log = array_column($userTypes, 'user_audit_log');
            $user_audit_log = array_merge($user_audit_log);
            if($user_audit_log[0] == "2"){
                abort(403, 'Page not available');
                }
            $logs = auditLog::selectAllList2($request);
            if(count($logs) == 0){
            $message = "No result/s found.";
            return Redirect::back()->withErrors($message);
            }
            $date_range_from = date('M d, Y', strtotime($request->txt_iec_date_from));
           $date_range_to = date('M d, Y', strtotime($request->txt_iec_date_to));
            return view('admin.reports.index', compact('page', 'logs', 'selected', 'date_range_from', 'date_range_to')); 
           }           
           if($request->txt_report_menu == 6){
            $inventories = iecStockUpdate::HistoryLogsLookup2($request);
            if(count($inventories) == 0){
            $message = "No result/s found.";
            return Redirect::back()->withErrors($message);
            }
            $date_range_from = date('M d, Y', strtotime($request->txt_iec_date_from));
           $date_range_to = date('M d, Y', strtotime($request->txt_iec_date_to));
            return view('admin.reports.index', compact('page', 'inventories', 'selected', 'date_range_from', 'date_range_to')); 
           }
           if($request->txt_report_menu == 7){
           $printinglogs = iecPrintingLogs::selectAllPrintingLogsList2($request);
            if(count($printinglogs) == 0){
            $message = "No result/s found.";
            return Redirect::back()->withErrors($message);
            }
            $date_range_from = date('M d, Y', strtotime($request->txt_iec_date_from));
           $date_range_to = date('M d, Y', strtotime($request->txt_iec_date_to));
           return view('admin.reports.index', compact('page', 'printinglogs', 'selected', 'date_range_from', 'date_range_to'));             
           }
           if($request->txt_report_menu == 8){
            $transactions = requests::AllTransactionList2($request);
            if(count($transactions) == 0){
            $message = "No result/s found.";
            return Redirect::back()->withErrors($message);
            }
            $date_range_from = date('M d, Y', strtotime($request->txt_iec_date_from));
           $date_range_to = date('M d, Y', strtotime($request->txt_iec_date_to));
           return view('admin.reports.index', compact('page', 'transactions', 'selected', 'date_range_from', 'date_range_to'));             
           }
           if($request->txt_report_menu == 9){
            $date_range_from = $request->txt_iec_date_from;
            $date_range_to = $request->txt_iec_date_to;

            if(date($date_range_from) > date($date_range_to)){
            $message = "Invalid entry. Please try again.";
            return Redirect::back()->withErrors($message);                
            }

            $aiecs = iec::selectAllRecords();
            $aiecsCounts = iec::selectAllRecordsCount($request);
            $iecinventories = iecStockUpdate::selectIECAllRecords21($request);
            $iecinventoriesPieces = iecStockUpdate::selectIECAllRecords212($request);
            $iecinventoriesEndingBalance = iecStockUpdate::selectIECAllRecords213($request);
            if(count($iecinventories) == 0){
            $aiecs = iec::selectAllRecords();
            $aiecsCounts = iec::selectAllRecordsCount($request);
            $iecinventories = iecStockUpdate::selectIECAllRecords21($request);
            $iecinventoriesPieces = iecStockUpdate::selectIECAllRecords212($request);
            $iecinventoriesEndingBalance = iecStockUpdate::selectIECAllRecords213($request);
            }
            return view('admin.reports.index', compact('page', 'iecinventories', 'iecinventoriesPieces', 'iecinventoriesEndingBalance', 'selected', 'date_range_from', 'date_range_to', 'aiecs', 'aiecsCounts')); 
           } 
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

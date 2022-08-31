<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controllers;
use Illuminate\Support\Facades\Response;
use App\materials;
use App\iec;
use App\iecStockUpdate;
use App\iecPrintingLogs;
use App\regions;
use App\provinces;
use App\Post;
use Auth;
use App\user_roles;
use App\activity_logs;
use App\contractors;
use App\iec_ecopy;
use App\auditLog;
use App\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Protection;
class iecController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function permission()
    {
      $userTypes = user_roles::userRolesList();
      $user_inventory = array_column($userTypes, 'user_inventory');
      $user_inventory = array_merge($user_inventory);
      if($user_inventory[0] == "2"){
        abort(403, 'Page not available');
        }
    }

    public function permissionReports()
    {
      $userTypes = user_roles::userRolesList();
      $user_reports = array_column($userTypes, 'user_reports');
      $user_reports = array_merge($user_reports);
      if($user_reports[0] == "2"){
        abort(403, 'Page not available');
        }
    }
    public function permissionAuditLog()
    {
      $userTypes = user_roles::userRolesList();
      $user_audit_log = array_column($userTypes, 'user_audit_log');
      $user_audit_log = array_merge($user_audit_log);
      if($user_audit_log[0] == "2"){
        abort(403, 'Page not available');
        }
    }
    public function index()
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'IEC Lists',
            'title'     => 'IEC Lists',
            'subtitle'  => ''
        ];
           $iecs = iec::selectAllRecords();
           $inventories = iecStockUpdate::HistoryLogsLookup();
           $printinglogs = iecPrintingLogs::selectAllPrintingLogsList();
           $contractors = contractors::selectAllRecord();
           $ecopies = iec_ecopy::selectAllRecordsList();
           return view('admin.iec.index', compact('page', 'iecs', 'inventories', 'printinglogs', 'contractors', 'ecopies'));
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
        iecController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Create New IEC',
            'title'     => 'Create New IEC',
            'subtitle'  => ''
        ];
           $materials = materials::materialsList();
           return view('admin.iec.create', compact('page', 'materials'));    
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
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permission();
        $validate = iec::validateNewEntryDetails($request);
 
        if ($validate === true) {
            $message = "Record already exists!";
            return Redirect::back()->withErrors($message);
        } else {
            $result = iec::insertNewRecord($request);

        if ($result === true) {
             $resulta = iec_ecopy::insertNewRecord($request);
             auditLog::addNewIECRecord($request);
            $message = "New Record added successfully!";
            return Redirect('/admin/iec/create')->with('formSuccess', $message);
        } else {
            return Redirect::back()->withErrors($result);
        }
      }
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
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permission();
        $page = [
            'parent'   => '/dashboard',
            'child'    => '/',
            'crumb'    => 'IEC Details',
            'title'    => 'IEC Details',
            'subtitle' => ''
        ];

        $iecs = iec::selectIECDetails($id);
        $materials = materials::materialsList();
        if ($iecs) {
            $iecs = $iecs[0];
            return view('admin.iec.show', compact('page', 'iecs', 'materials'));
        } else {
            return Redirect('/admin/iec')->withErrors("The record you are trying to view does not exists!");
        }
    }
}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permission();
        $page = [
            'parent'   => '/dashboard',
            'child'    => '/',
            'crumb'    => 'Edit IEC Details',
            'title'    => 'Edit IEC Details',
            'subtitle' => ''
        ];

        $iecs = iec::selectedIEC($id);
        $materials = materials::materialsList();
        if ($iecs) {
            $iecs = $iecs[0];
            return view('admin.iec.edit', compact('page', 'iecs', 'materials'));
        } else {
            return Redirect('/admin/iec')->withErrors("The record you are trying to view does not exists!");
        }
    }
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
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permission();
        $this->validate($request, [
            'txt_iec_title' =>'required',
            'txt_iec_page' =>'required',
            'txt_iec_type_of_materials' =>'required',
        ]);  

        $validate = iec::validateIECDetails($request, $id);
        if ($validate === true) {
            $message = "Record type already exists!";
            return Redirect::back()->withErrors($message);
        } else {

        $image_file = '';
        // Random Strings
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pin = mt_rand(1000000, 9999999)
        . mt_rand(1000000, 9999999)
        . $characters[rand(0, strlen($characters) - 1)];
        $string = str_shuffle($pin);
 
        if($request->file('txt_iec_image')) {
            $image = $request->file('txt_iec_image');
            $img_name = $string.'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images/uploads');
            $imagePath = $destinationPath. "/".  $img_name;
            $image->move($destinationPath, $img_name);
            $image_file = '/images/uploads/' . $img_name;
        } else {
            $image_file = '';
        }


        auditLog::searchIECRecord($request, $id, $image_file);
      //  iecStockUpdate::insertUpdatedIECSInfo($request,$id);
        $result = iec::updateIECDetails($request, $id, $image_file);
            if ($result === true) {
                
                $message = "Record Successfully Updated!";
                return Redirect('/admin/iec')->with('formSuccess', $message);
            } else {
                return Redirect::back()->withErrors($result);
            }
        }
    }
}

    public function delete($id)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        auditLog::IECDeleteRec($id);
        $result = iec::deleteIECDetails($id);
            if ($result === true) {
                $message = "Record Successfully Deleted!";
                return Redirect('/admin/iec')->with('formSuccess', $message);
            } else {
                return Redirect::back()->withErrors($result);
            }

    }
}
     public function stocksUpdate(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permission();
        $this->validate($request, [
            'txt_iec_id' =>'required',
            'iec_remarks' =>'required',
        ]);  

        $id = $request->txt_iec_id;
        $iec_rec =array();
        $activityLog = activity_logs::insertIECStocksUpdateLogs($request, $id); 
        auditLog::IECRestockUpdate($request, $id);
        $iec_rec = iec::selectIECDetails($id);
        $result = iecStockUpdate::insertUpdatedIECStocks($request, $iec_rec);
        $result_printing = iecPrintingLogs::insertPrintingInventory($request, $iec_rec);
        $result = iec::updateIECStocks($request, $iec_rec);
            if ($result === true) {               
                $message = "Record Successfully Updated!";
                return Redirect('/admin/iec')->with('formSuccess', $message);
            } else {
                return Redirect::back()->withErrors($result);
            }
    }
}
     public function criticalItemsUpdate(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permission();
        $this->validate($request, [
            'txt_iec_id' =>'required',
            'iec_remarks' =>'required',
        ]);  

        $id = $request->txt_iec_id;
        $iec_rec =array();
        $iec_rec = iec::selectIECDetails($id);
        $result = iec::updateIECStocks($request, $iec_rec);
            if ($result === true) {
        $result = iecStockUpdate::insertUpdatedIECStocks($request, $iec_rec);
        $result_printing = iecPrintingLogs::insertPrintingInventory($request, $iec_rec);
                $message = "Record Successfully Updated!";
                return Redirect('/admin/iec/critical-items')->with('formSuccess', $message);
            } else {
                return Redirect::back()->withErrors($result);
            }
    }
}
    public function autocomplete($id)
    {
        $result = iec::selectIecKeywordDetails($id);
        return $result;
    }

    public function iecGraph()
    {
        $result = iec::itemsGraph();
        // $result2 = iec::selectIECName($result);
        // $result3=array_merge($result, $result2);
        return $result;
    }

    public function iecGraphOrg($id)
    {
        $result = iec::graphOrgList($id);
        return $result;
    }
 
    public function iecGraphOrg1($id)
    {
        $result = iec::graphOrgList1($id);
        return $result;
    }

    public function iecChartCategory($id)
    {
        $result = iec::chartCategoryList($id);
        return $result;
    }

    public function iecChartCategoryAll()
    {
        $result = iec::chartCategoryAllList();
        return $result;
    }
    public function findStocks($iec)
    {
        $result = iec::findIecStocks($iec);
        return $result;
    }

    public function lookup()
    {
           $result = iec::selectAllRecord();
           return $result;  
    }

    public function randomsearch($keyword)
    {
           $result = iec::selectRandomTitle($keyword);
           return $result;  
    }

    public function onstock()
    {
           $result = iec::iecstocks();
           return $result;  
    }
    public function findID($id)
    {
           $result = iec::selectIECDetails($id);
           return $result;  
    }

    public function SelectIECStockAvailableChart()
    {
           $result = iec::SelectIECStockAvailable();
           return $result;  
    }


    public function criticalItems()
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
            iecController::permission();
            $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Critical Items',
            'title'     => 'Critical Items',
            'subtitle'  => ''
        ];
           $iecCriticalItems = iec::selectCriticalItems();
           return view('admin.iec.critical-items', compact('page', 'iecCriticalItems')); 
    }
}
    public function SelectRegionsList()
    {
           $result = regions::SelectRegions();
           return $result;  
    }

    public function SelectProvincesList()
    {
           $result = provinces::SelectProvinces();
           return $result;  
    }
    public function SelectIECOrganizationsList($id)
    {
           $result = iec::SelectIECOrganizationChart($id);
           return $result;  
    }

    public function SelectIECRegionsList($id)
    {
           $resu = [];
           $asd = [];
           $recids = [];
           $result = iec::SelectIECRegionChart($id);
           $asd = array_column($result, 'organization_name');
           $asd = array_merge($asd);
           $collection_rec_id = collect($asd);
           $recids = $collection_rec_id->unique()->values()->all(); 
           $recids =implode("', '", $recids);  
           $result = iec::SelectIECRegionRequestChart($recids);
           return $result;  
    }

    public function SelectIECProvincesList($id)
    {
           $resu = [];
           $asd = [];
           $recids = [];
           $result = iec::SelectIECProvinceChart($id);
           $asd = array_column($result, 'organization_name');
           $asd = array_merge($asd);
           $collection_rec_id = collect($asd);
           $recids = $collection_rec_id->unique()->values()->all(); 
           $recids =implode("', '", $recids);  
           $result = iec::SelectIECProvinceRequestChart($recids);
           return $result;  
    }

    public function logs()
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permissionAuditLog();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Audit Trail',
            'title'     => 'Autid Trail',
            'subtitle'  => ''
        ];
           $iecs = iecStockUpdate::selectAllLogs();
           return view('admin.iec.logs', compact('page', 'iecs'));    
    }
}
    public function printingLogs()
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permissionReports();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Printing Logs',
            'title'     => 'Printing Logs',
            'subtitle'  => ''
        ];
           $printingresults = iecPrintingLogs::selectAllPrintingLogs();
           return view('admin.iec.printing-logs', compact('page', 'printingresults'));    
    }
}
    public function printingLogsList()
    {
        iecController::permissionReports();
           $result = iecPrintingLogs::selectAllPrintingLogsList();
           return $result;    
    }

    public function printingLogsDate(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permissionReports();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Printing Logs',
            'title'     => 'Printing Logs',
            'subtitle'  => ''
        ];
           $printings = iecPrintingLogs::selectPrintingLogsByDate($request);
           return view('admin.iec.printing-logs', compact('page', 'printings'));    
    }
}
    public function printingLogsAutoComplete(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permissionReports();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Printing Logs',
            'title'     => 'Printing Logs',
            'subtitle'  => ''
        ];
           $printings = iecPrintingLogs::selectPrintingLogsByKeyword($request);
           return view('admin.iec.printing-logs', compact('page', 'printings'));    
    }
}

    public function PrintAllIecs(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permissionReports();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'IECS',
            'title'     => 'IEC Materials List',
            'subtitle'  => ''
        ];
        if ($request->chk_selectOneIec) {
           $iecs = iec::PrintAllIecs($request);
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
            $date_range_from = date('M d, Y', strtotime($request->txt_iec_date_from));
           $date_range_to = date('M d, Y', strtotime($request->txt_iec_date_to));       
           return view('admin.iec.print-all-iecs', compact('page', 'iecs', 'usigns', 'uposition','date_range_from', 'date_range_to'));         
           } else {
                $message='Please check atleast one or more records to print.';
            return Redirect::back()->withErrors($message);
        }  
    }
}
    public function PrintAllIecs2(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permissionReports();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'IECS',
            'title'     => 'IEC Materials List',
            'subtitle'  => ''
        ];
        if ($request->chk_selectOneIec) {
            $date_range_from = date('M d, Y', strtotime($request->txt_iec_date_from));
            $date_range_to = date('M d, Y', strtotime($request->txt_iec_date_to));       

//           $aiecs = iec::selectAllRecords();
          $inventories = iecStockUpdate::selectIECAllRecordstoPrint2($request);
//           $iecinventoriesDates = iecStockUpdate::selectIECAllRecordsDate2($request);    
            $iecs = iec::selectAllRecords();
            $aiecsCounts = iec::selectAllRecordsCount2($request);
            $iecinventories = iecStockUpdate::selectIECAllRecords211($request);
            $iecinventoriesPieces = iecStockUpdate::selectIECAllRecords2121($request);
            $iecinventoriesEndingBalance = iecStockUpdate::selectIECAllRecords2131($request);
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
           $inventories = iecStockUpdate::selectIECAllRecordstoPrint3($request);
           $iecinventoriesDates = iecStockUpdate::selectIECAllRecordsDate3($request);
           return view('admin.iec.print-all-iecs', compact('usigns', 'uposition', 'page', 'inventories','iecinventories', 'iecinventoriesPieces', 'iecinventoriesEndingBalance','date_range_from', 'date_range_to', 'iecs', 'aiecsCounts'));         
           } else {
                $message='Please check atleast one or more records to print.';
            return Redirect::back()->withErrors($message);
        }  
    }
}


    public function PrintAllIecs21(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permissionReports();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'IECS',
            'title'     => 'IEC Materials List',
            'subtitle'  => ''
        ];
        if ($request->chk_selectOneIec) {
            $date_range_from = date('M d, Y', strtotime($request->txt_iec_date_from));
            $date_range_to = date('M d, Y', strtotime($request->txt_iec_date_to));       

//           $aiecs = iec::selectAllRecords();
          $inventories = iecStockUpdate::selectIECAllRecordstoPrint2($request);
//           $iecinventoriesDates = iecStockUpdate::selectIECAllRecordsDate2($request);    
            $iecs = iec::PrintAllIecs($request);
            $aiecsCounts = iec::selectAllRecordsCount2($request);
            $iecinventories = iecStockUpdate::selectIECAllRecords211($request);
            $iecinventoriesPieces = iecStockUpdate::selectIECAllRecords2121($request);
            $iecinventoriesEndingBalance = iecStockUpdate::selectIECAllRecords2131($request);
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
           $inventories = iecStockUpdate::selectIECAllRecordstoPrint3($request);
           $iecinventoriesDates = iecStockUpdate::selectIECAllRecordsDate3($request);
           return view('admin.iec.print-all-iecs', compact('usigns', 'uposition', 'page', 'inventories','iecinventories', 'iecinventoriesPieces', 'iecinventoriesEndingBalance','date_range_from', 'date_range_to', 'iecs', 'aiecsCounts'));         
           } else {
                $message='Please check atleast one or more records to print.';
            return Redirect::back()->withErrors($message);
        }  
    }
}



    public function PrintAllprintingLogs(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permissionReports();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Printing Logs',
            'title'     => 'Printing Logs',
            'subtitle'  => ''
        ];
        if ($request->chk_selectOne) {
           $printings = iecPrintingLogs::PrintAllLogs($request);
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
            $date_range_from = date('M d, Y', strtotime($request->txt_iec_date_from));
           $date_range_to = date('M d, Y', strtotime($request->txt_iec_date_to));      
           return view('admin.iec.print-all', compact('page', 'printings', 'usigns', 'uposition','date_range_from', 'date_range_to'));         
           } else {
                $message='Please check atleast one or more records to print.';
            return Redirect::back()->withErrors($message);
        }  
    }
}

    // Chano
    public function PrintAllInventoryLogs(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permissionReports();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Inventory Logs',
            'title'     => 'InventoryLogs',
            'subtitle'  => ''
        ];
        if ($request->chk_selectOneIec) {
           $inventories = iecStockUpdate::PrintAllInventoryLogs($request);
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
           return view('admin.iec.print-all-inventory-history', compact('page', 'inventories', 'usigns', 'uposition'));         
           } else {
                $message='Please check atleast one or more records to print.';
            return Redirect::back()->withErrors($message);
        }  
    }
}

    public function PrintAllInventoryLogs2(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permissionReports();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Inventory Logs',
            'title'     => 'InventoryLogs',
            'subtitle'  => ''
        ];
        if ($request->chk_selectOneIec) {
           $inventories = iecStockUpdate::PrintAllInventoryLogs($request);
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
           $date_range_from = date('M d, Y', strtotime($request->txt_iec_date_from));
           $date_range_to = date('M d, Y', strtotime($request->txt_iec_date_to));       
           return view('admin.iec.print-all-inventory-history', compact('page', 'inventories', 'usigns', 'uposition', 'date_range_from', 'date_range_to'));         
           } else {
                $message='Please check atleast one or more records to print.';
            return Redirect::back()->withErrors($message);
        }  
    }
}
    public function lookUpHistoryLogs($id)
    {
           $result = iecStockUpdate::HistoryLogsLookup($id);
           return $result;  
    }
    public function lookUpIECthreshold($id)
    {
           $result = iec::lookUpIECthreshold($id);
           return $result;  
    }

    public function import()
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
    iecController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Import IEC Materials',
            'title'     => 'Import IEC Materials',
            'subtitle'  => ''
        ];
           return view('admin.iec.import', compact('page')); 
    }
}

    public function insertBulkRecord(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permission();
        $this->validate($request, [
            'iec_file' => 'required'
        ]); 
        $result = iec::importIECSpreadsheetFile($request);
        if ($result->message === 'File uploaded successfully!') {
            return Redirect('/admin/iec/import/')->with('formSuccess', $result->message);
        } else {
            return Redirect::back()->withErrors($result->message);
        }
    }
}
    public function addnewContractor($id)
    {
           $resulta = contractors::validateNewRecord($id);
           if($resulta == true){
              $result = 2;
              return $result;
           } else {
           $result = contractors::insertNewRecord($id);
           auditLog::StoreNewContractorOnTheFlyLogs($id);
             return $result;
           }  
    }

    public function selectAllContractorRecord()
    {
        $result = contractors::selectAllRecord();
        return $result;
    }

public function getDownload($id)
{
    $ecopies = iec_ecopy::selectEcopyRecord($id);
    foreach($ecopies as $ecopy){}
    //PDF file is stored under project/public/download/info.pdf
    $file=public_path($ecopy->ecopy_iec_soft_copy);
     
    $headers = array(
              'Content-Type: application/pdf',
            );
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pin = mt_rand(1000000, 9999999)
            . mt_rand(1000000, 9999999)
            . $characters[rand(0, strlen($characters) - 1)];
        $string = str_shuffle($pin);

    $filename = $string;
    return Response::download($file);
}

    public function PrintAllInventoryIECLogs2(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permissionReports();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'IEC Materials Inventory Report',
            'title'     => 'IEC Materials Inventory Report',
            'subtitle'  => ''
        ];
        if ($request->chk_selectOneIec) {
//           $aiecs = iec::selectAllRecords();
          $inventories = iecStockUpdate::selectIECAllRecordstoPrint2($request);
//           $iecinventoriesDates = iecStockUpdate::selectIECAllRecordsDate2($request);    


            $aiecs = iec::selectAllRecords();
            $aiecsCounts = iec::selectAllRecordsCount($request);
            $iecinventories = iecStockUpdate::selectIECAllRecords21($request);
            $iecinventoriesPieces = iecStockUpdate::selectIECAllRecords212($request);
            $iecinventoriesEndingBalance = iecStockUpdate::selectIECAllRecords213($request);


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

 
           $date_range_from = date('M d, Y', strtotime($request->txt_iec_date_from));
           $date_range_to = date('M d, Y', strtotime($request->txt_iec_date_to));
           return view('admin.iec.print-all-iec-inventory-history', compact('usigns', 'uposition', 'page', 'inventories','iecinventories', 'iecinventoriesPieces', 'iecinventoriesEndingBalance','date_range_from', 'date_range_to', 'aiecs', 'aiecsCounts'));         
           } else {
                $message='Please check atleast one or more records to print.';
            return Redirect::back()->withErrors($message);
        }  
    }
}



    public function PrintAllInventoryIECLogs21(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        iecController::permissionReports();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'IEC Materials Inventory Report',
            'title'     => 'IEC Materials Inventory Report',
            'subtitle'  => ''
        ];
        if ($request->chk_selectOneIec) {
//           $aiecs = iec::selectAllRecords();
          $inventories = iecStockUpdate::selectIECAllRecordstoPrint2($request);
//           $iecinventoriesDates = iecStockUpdate::selectIECAllRecordsDate2($request);    


            $aiecs = iec::PrintAllIecs($request);
            $aiecsCounts = iec::selectAllRecordsCount($request);
            $iecinventories = iecStockUpdate::selectIECAllRecords21($request);
            $iecinventoriesPieces = iecStockUpdate::selectIECAllRecords212($request);
            $iecinventoriesEndingBalance = iecStockUpdate::selectIECAllRecords213($request);


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

 
           $date_range_from = date('M d, Y', strtotime($request->txt_iec_date_from));
           $date_range_to = date('M d, Y', strtotime($request->txt_iec_date_to));
           return view('admin.iec.print-all-iec-inventory-history', compact('usigns', 'uposition', 'page', 'inventories','iecinventories', 'iecinventoriesPieces', 'iecinventoriesEndingBalance','date_range_from', 'date_range_to', 'aiecs', 'aiecsCounts'));         
           } else {
                $message='Please check atleast one or more records to print.';
            return Redirect::back()->withErrors($message);
        }  
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

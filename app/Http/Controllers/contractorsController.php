<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controllers;
use App\contractors;
use App\Post;
use App\user_roles;
use App\auditLog;
use Auth;
use App\User;
class contractorsController extends Controller
{
 
    public function permission()
    {
      $userTypes = user_roles::userRolesList();
      $user_code_library = array_column($userTypes, 'user_code_library');
      $user_code_library = array_merge($user_code_library);
      if($user_code_library[0] == "2"){
        abort(403, 'Page not available');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        contractorsController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Contractors List',
            'title'     => 'Contractors List',
            'subtitle'  => ''
        ];
           $contractors = contractors::selectAllRecords();
           $contractorsListLogs = contractors::selectAllContractorPrintingLogsList();
           return view('admin.contractors.index', compact('page', 'contractors', 'contractorsListLogs')); 
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
        contractorsController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Create New Contractor',
            'title'     => 'Create New Contractor',
            'subtitle'  => ''
        ];
           return view('admin.contractors.create', compact('page'));
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
        contractorsController::permission();
        $validate = contractors::validateNewEntryDetails($request);
        if ($validate === true) {
            $message = "Record already exists!";
            return Redirect::back()->withErrors($message);
        } else {
            $result = contractors::insertNewFileRecord($request);
            $resulta = auditLog::StoreNewContractorslLogs($request);
        if ($result === true) {
            $message = "New Record added successfully!";
            return Redirect('/admin/contractors/create')->with('formSuccess', $message);
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
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        contractorsController::permission();
        $page = [
            'parent'   => '/dashboard',
            'child'    => '/',
            'crumb'    => 'Edit Contractors Details',
            'title'    => 'Edit Contractors Details',
            'subtitle' => ''
        ];

           $contractors = contractors::selectContractorsRecord($id);
        if ($contractors) {
            $contractors = $contractors[0];
            return view('admin.contractors.edit', compact('page', 'contractors'));
        } else {
            return Redirect('/admin/contractors')->withErrors("The record you are trying to view does not exists!");
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
        contractorsController::permission();
        $validate = contractors::validateEntryDetails($request, $id);
        if ($validate === true) {
            $message = "Record already exists!";
            return Redirect::back()->withErrors($message);
        } else {
        //iecStockUpdate::insertUpdatedIECSInfo($request,$id);          
        $resulta = auditLog::UpdateContractor($request, $id);
        $result = contractors::updateContractorsDetails($request, $id);
            if ($result === true) {
                
                $message = "Record Successfully Updated!";
                return Redirect('/admin/contractors')->with('formSuccess', $message);
            } else {
                return Redirect::back()->withErrors($result);
            }
        }
    }
}
    public function deleteRec($id)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        contractorsController::permission();
        $result = contractors::deleteRec($id);
            if ($result === true) {
                $resulta = auditLog::deleteContractorLogs($id);
                $message = "Record Successfully Deleted!";
                return Redirect('/admin/contractors')->with('formSuccess', $message);
            } else {
                return Redirect::back()->withErrors($result);
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

    public function PrintAllContractors(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        contractorsController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Reports',
            'title'     => 'Reports',
            'subtitle'  => ''
        ];
        if ($request->chk_selectOneContractor) {
           $contractors = contractors::selectAllContractorsList($request);
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
           return view('admin.contractors.print-all-contractors', compact('page', 'contractors', 'usigns', 'uposition'));         
           } else {
                $message='Please check atleast one or more records to print.';
            return Redirect::back()->withErrors($message);
        }  
    }
  }
}

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
use Auth;
use App\User;
class auditlogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function permission()
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
            auditlogsController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => "Audit Logs",
            'title'     => "Audit Logs",
            'subtitle'  => ''
        ];
           $logs = auditLog::selectAllList();
           return view('admin.audit_logs.index', compact('page', 'logs')); 
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

    public function PrintAllLogs(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        auditLogsController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Reports',
            'title'     => 'Reports',
            'subtitle'  => ''
        ];
        if ($request->chk_selectOneLog) {
           $logs = auditLog::selectAllLogsList($request);
           $date_range_from = date('M d, Y', strtotime($request->txt_iec_date_from));
           $date_range_to = date('M d, Y', strtotime($request->txt_iec_date_to));
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
           return view('admin.audit_logs.print-all-logs', compact('page', 'logs', 'usigns', 'uposition', 'date_range_from', 'date_range_to'));         
           } else {
                $message='Please check atleast one or more records to print.';
            return Redirect::back()->withErrors($message);
        }  
    }
  }
}

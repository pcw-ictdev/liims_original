<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controllers;
use Auth;
use App\user_roles;
use App\User;
use App\activity_logs;
use App\auditLog;
class userRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function permission()
    {
      $userTypes = user_roles::userRolesList();
      $user_management = array_column($userTypes, 'user_management');
      $user_management = array_merge($user_management);
      if($user_management[0] == "2"){
        abort(403, 'Page not available');
        }
    }

    public function index()
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
     userRolesController::permission();
     $page = [
            'parent'    => '/user_roles',
            'child'     => '/',
            'crumb'     => 'User Roles List',
            'title'     => 'User Roles List',
            'subtitle'  => ''
        ];
        $users = user_roles::selectAllUser_Roles();
        return view('admin.user_roles.index', compact('page', 'users'));
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
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        userRolesController::permission();
        $validate = user_roles::validateNewEntryDetails($request);
 
        if ($validate === true) {
            $message = "Record already exists!";
            return Redirect::back()->withErrors($message);
        } else {

            $result = user_roles::insertNewRecord($request);
            auditLog::storeNewUserRoleLogs($request);
        if ($result === true) {
            $message = "New Record added successfully!";
            return Redirect('/admin/user_roles')->with('formSuccess', $message);
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
         userRolesController::permission();
        // Check if stat name was already taken
        $validate = user_roles::validateExistingRecord($request);
        if ($validate === true) {
            $message = "Material type already exists!";
            return Redirect::back()->withErrors($message);
        } else {
        auditLog::updateUserRoleLogs($request);
        $activityLog = activity_logs::insertUserROlesLogs($request); 
        $result = user_roles::updateUserRoleDetails($request);
            if ($result === true) {
                $message = "Record Successfully Updated!";
                return Redirect('/admin/user_roles')->with('formSuccess', $message);
            } else {
                return Redirect::back()->withErrors($result);
            }
        }
    }
}

    public function deleteUserRole($id)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else { 
        $result = user_roles::deleteUserRoleDetails($id);
            if ($result === true) {
                auditLog::deleteUserRoleLogs($id);
                $message = "Record Successfully Deleted!";
                return Redirect('/admin/user_roles')->with('formSuccess', $message);
            } else {
                return Redirect::back()->withErrors($result);
            }

    }
}
    public function rolesList()
    {
        $result = user_roles::userRolesList();
        return $result;
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

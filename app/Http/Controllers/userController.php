<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controllers;
use Auth;
use App\usertypes;
use App\user_roles;
use App\User;
use App\activity_logs;
use Mail;
use App\Mail\mailNotification;
use App\auditLog;
class userController extends Controller
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
     userController::permission();
     $page = [
            'parent'    => '/users',
            'child'     => '/',
            'crumb'     => 'User Lists',
            'title'     => 'User Lists',
            'subtitle'  => ''
        ];

        $usertypes = Usertypes::selectAllUsertypes();
        if(auth::user()->uid == 1){
            $users = User::SuselectAllUsers();
        }
        if(auth::user()->uid == 2){ 
            $users = User::selectAllUsers();
        }
        return view('admin.users.index', compact('page', 'usertypes','users'));
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
    userController::permission();
     $page = [
            'parent'    => '/users',
            'child'     => '/',
            'crumb'     => 'Create New User',
            'title'     => 'Create New User',
            'subtitle'  => ''
        ];


        $usertypes = user_roles::selectList(); 
        return view('admin.users.create', compact('page', 'usertypes'));
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
        $this->validate($request, [
            'user_name'         => 'required',
            'user_type'         => 'required',
            ]); 
 
      $validate = User::validateNewuserDetails($request);
     
        if ($validate === true) {
            $message = "User/Email already exists!";
            return Redirect::back()->withErrors($message);
        } else {
            $result = User::insertNewUser($request);
            if ($result === true) {
                $result2 = auditLog::storeNewUserLogs($request);
                $message = "User Added Successfully!";
                return Redirect('/admin/users/create')->with('formSuccess', $message);
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
    userController::permission();
    $page = [
            'parent'   => '/users',
            'child'    => '/users',
            'crumb'    => "User's Details",
            'title'    => "User's Details",
            'subtitle' => ''
        ];

        $users = User::selectUsersDetails($id);
        if ($users) {
            $users = $users[0];
            return view('admin.users.show', compact('page', 'users'));
        } else {
            return Redirect('/admin/users')->withErrors("The record you are trying to view does not exists!");
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
         userController::permission();
         $page = [
            'parent'   => '/users',
            'child'    => '/users',
            'crumb'    => "Edit User's Details",
            'title'    => "Edit User's Details",
            'subtitle' => ''
        ];

        $users = User::selectUsersDetails($id); 
        $usertypes = user_roles::selectList();
        if ($users) {
            $users = $users[0];
        return view('admin.users.edit', compact('page','users' ,'usertypes'));
        } else {
            return Redirect('/admin/users')->withErrors("The record you are trying to view does not exists!");
        }
    }
}
    //edit user details for user account
    public function updateinfo($id)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        if(Auth::user()->id != $id){
         abort(403);
        }

         $page = [
            'parent'   => '/users',
            'child'    => '/users',
            'crumb'    => 'Edit User Details',
            'title'    => 'Edit User Details',
            'subtitle' => ''
        ];
        $id = Auth::user()->id;
        $users = User::selectUsersDetails($id);
        if ($users) {
            $users = $users[0];
        return view('admin.users.updateinfo', compact('page','users'));
        } else {
            return Redirect('/admin/users')->withErrors("The record you are trying to view does not exists!");
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
        $this->validate($request, [
            'user_email' =>'required',
        ]);  

        // Check if stat name was already taken
        $validate = User::validateUserDetails($request, $id);
        if ($validate === true) {
            $message = "Email Address already exists!";
            return Redirect::back()->withErrors($message);
        } else {
            $activityLog = activity_logs::insertUserLogs($request, $id);
            auditLog::updateUserLogs($request, $id);
            $result = User::updateusersDetails($request, $id);
            if ($result === true) {    
                $message = "User Details Successfully Updated!";
                return Redirect('/admin/users')->with('formSuccess', $message);
            } else {
                return Redirect::back()->withErrors($result);
            }
        }
    }
}
//update user pw w/ id
    public function updatepwdetails(Request $request, $id)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        $this->validate($request, [
            'rpassword' =>'required',
            'rcpassword' =>'required',
        ]);  

          $id = Auth::user()->uid;
        $result = User::updateUserspwDetails($request, $id);

            if ($result === true) {
                $message = "User Details Successfully Updated!";
                return Redirect::back()->with('formSuccess', $message);
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
    //find user info eg. password

    public function delrec($id)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        $result = User::deleteUserDetails($id);
            if ($result === true) {
                auditLog::deleteUserLogs($id);
                $message = "User Info Successfully Deleted!";
                return Redirect('/admin/users')->with('formSuccess', $message);
            } else {
                return Redirect::back()->withErrors($result);
            }
    }
}
    public function destroy($id)
    {
        //
    }
}

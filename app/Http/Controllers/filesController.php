<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controllers;
use App\iec_ecopy;
use App\iec;
use App\Post;
use App\user_roles;
use App\auditLog;
use Auth;
class filesController extends Controller
{

    public function permission()
    {
      $userTypes = user_roles::userRolesList();
      $user_inventory = array_column($userTypes, 'user_inventory');
      $user_inventory = array_merge($user_inventory);
      if($user_inventory[0] == "2"){
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
        return redirect('/home');
        
    //     if(Auth::user()->user_status == 2){
    //         auth()->logout();
    //         return redirect('/login');
    //     } else {
    //     filesController::permission();
    //     $page = [
    //         'parent'    => '/dashboard',
    //         'child'     => '/',
    //         'crumb'     => 'E-Copy List ',
    //         'title'     => 'E-Copy List',
    //         'subtitle'  => ''
    //     ];
    //        $ecopies = iec_ecopy::selectEcopyAllRecords();
    //        $iecs = iec::selectAllRecord();
    //        return view('admin.files.index', compact('page', 'ecopies', 'iecs')); 
    // }
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
        filesController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Create New File',
            'title'     => 'Create New File',
            'subtitle'  => ''
        ];
           $iecs = iec::selectEcopyTitleRecord();
           return view('admin.files.create', compact('page', 'iecs'));
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
        filesController::permission();
        $validate = iec_ecopy::validateNewEntryDetails($request);
        if ($validate === true) {
            $message = "Record already exists!";
            return Redirect::back()->withErrors($message);
        } else {
            $result = iec_ecopy::insertNewFileRecord($request);
            auditLog::StoreNewIECEcopyLogs($request);
        if ($result === true) {
            $message = "New Record added successfully!";
            return Redirect('/admin/files/create')->with('formSuccess', $message);
        } else {
            return Redirect::back()->withErrors($result);
         }
       }
    }
}
    public function storeNew(Request $request, $id)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        filesController::permission();
        $validate = iec_ecopy::validateNewIECEntryDetails($request);
        if ($validate === true) {
            $message = "Record already exists!";
            return Redirect::back()->withErrors($message);
        } else {
            $result = iec_ecopy::insertNewIECFileRecord($request, $id);
            auditLog::StoreNewEcopyLogs($request);
        if ($result === true) {
            $message = "New Record added successfully!";
            return Redirect('/admin/iec/')->with('formSuccess', $message);
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
        filesController::permission();
        $page = [
            'parent'   => '/dashboard',
            'child'    => '/',
            'crumb'    => 'Files List',
            'title'    => 'Files List',
            'subtitle' => ''
        ];

           $ecopies = iec_ecopy::selectEcopyRecord($id);
        if ($ecopies) {
            $ecopies = $ecopies[0];
            $iecs = iec::selectAllRecord();
            return view('admin.files.edit', compact('page', 'ecopies', 'iecs'));
        } else {
            return Redirect('/admin/files')->withErrors("The record you are trying to view does not exists!");
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
        filesController::permission();
        $validate = iec_ecopy::validateEntryDetails($request, $id);
        if ($validate === true) {
            $message = "Record already exists!";
            return Redirect::back()->withErrors($message);
        } else {
        //iecStockUpdate::insertUpdatedIECSInfo($request,$id);

    if($request->txt_iec_soft_copy){
        // Random Strings
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pin = mt_rand(1000000, 9999999)
            . mt_rand(1000000, 9999999)
            . $characters[rand(0, strlen($characters) - 1)];
        $string = str_shuffle($pin);
 

        $iec_soft_copy = $request->file('txt_iec_soft_copy');
        $img_soft_copy_name = $string.'.'.$iec_soft_copy->getClientOriginalExtension();
        $destinationPath = public_path('/files/uploads/');
        $iec_soft_copy->move($destinationPath, $img_soft_copy_name);
        $image_soft_copy_file = '/files/uploads/' . $img_soft_copy_name;
    } else {
        $image_soft_copy_file = '';
    }
        auditLog::updateEcopyLogs($request, $id, $image_soft_copy_file);
        $result = iec_ecopy::updateEcopyDetails($request, $id, $image_soft_copy_file);
            if ($result === true) {
                
                $message = "Record Successfully Updated!";
                return Redirect('/admin/files')->with('formSuccess', $message);
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
        filesController::permission();
        $result = iec_ecopy::deleteRec($id);
            if ($result === true) {
                auditLog::deleteEcopyLogs($id);
                $message = "Record Successfully Deleted!";
                return Redirect('/admin/files')->with('formSuccess', $message);
            } else {
                return Redirect::back()->withErrors($result);
            }

    }
}
    public function deleteFileRec($id)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        filesController::permission();
        $result = iec_ecopy::deleteFileRec($id);
            if ($result === true) {
                auditLog::deleteEcopyLogs($id);
                $message = "Record Successfully Deleted!";
                return Redirect('/admin/iec')->with('formSuccess', $message);
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
}

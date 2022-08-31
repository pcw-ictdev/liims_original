<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controllers;
use Auth;
use App\materials;
use App\Post;
use App\user_roles;
use App\activity_logs;
use App\auditLog;
use App\requests;
class materialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function permission()
    {
      $userTypes = user_roles::userRolesList();
      $user_code_library = array_column($userTypes, 'user_code_library');
      $user_code_library = array_merge($user_code_library);
      if($user_code_library[0] == "2"){
        abort(403, 'Page not available');
        }
    }

    public function index()
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        materialController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Asset Types List',
            'title'     => 'Asset Types List',
            'subtitle'  => ''
        ];
           $materials = materials::selectAllRecords();
           $materialsRequestsLists = requests::AllTransactionMaterialList();
           return view('admin.materials.index', compact('page', 'materials', 'materialsRequestsLists')); 
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
        materialController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Create New Asset Type',
            'title'     => 'Create New Asset Type',
            'subtitle'  => ''
        ];
           return view('admin.materials.create', compact('page'));    
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
        materialController::permission();
        $validate = materials::validateNewEntryDetails($request);
 
        if ($validate === true) {
            $message = "Record already exists!";
            return Redirect::back()->withErrors($message);
        } else {
            $result = materials::insertNewRecord($request);
            auditLog::StoreNewMaterialLogs($request);

        if ($result === true) {
            $message = "New Record added successfully!";
            return Redirect('/admin/assets/create')->with('formSuccess', $message);
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
        materialController::permission();
        $page = [
            'parent'   => '/dashboard',
            'child'    => '/',
            'crumb'    => 'Asset Types Details',
            'title'    => 'Asset Types Details',
            'subtitle' => ''
        ];

        $materials = materials::selectMaterialDetails($id);
        if ($materials) {
            $materials = $materials[0];
            return view('admin.materials.show', compact('page', 'materials'));
        } else {
            return Redirect('/admin/assets')->withErrors("The record you are trying to view does not exists!");
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
        materialController::permission();
        $page = [
            'parent'   => '/dashboard',
            'child'    => '/',
            'crumb'    => 'Edit Asset Type',
            'title'    => 'Edit Asset Type',
            'subtitle' => ''
        ];

        $materials = materials::selectMaterialDetails($id);
        if ($materials) {
            $materials = $materials[0];
            return view('admin.materials.edit', compact('page', 'materials'));
        } else {
            return Redirect('/admin/assets')->withErrors("The record you are trying to view does not exists!");
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
        materialController::permission();
        $this->validate($request, [
            'txt_material_name' =>'required',
        ]);  

        // Check if stat name was already taken
        $validate = materials::validateMaterialDetails($request, $id);
        if ($validate === true) {
            $message = "Material type already exists!";
            return Redirect::back()->withErrors($message);
        } else {
        auditLog::updateMaterialLogs($request, $id);
        $activityLog = activity_logs::insertAssetsLogs($request, $id);    
        $result = materials::updateMaterialDetails($request, $id);
            if ($result === true) {
                $message = "Record Successfully Updated!";
                return Redirect('/admin/assets')->with('formSuccess', $message);
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
        $result = materials::deleteAssetsDetails($id);

            if ($result === true) {
                $aresult = auditLog::deleteMaterialLogs($id);
                $message = "Record Successfully Deleted!";
                return Redirect('/admin/assets')->with('formSuccess', $message);
            } else {
                return Redirect::back()->withErrors($result);
        }
    }
}

    public function find($id)
    {
        $result = materials::validateNewModalEntryDetails($id);
        return $result;
    }

    public function save($id)
    {
        $result = materials::insertNewModalRecord($id); 
        return $result;
    }

    public function materialLists()
    {
        $result = materials::selectAllRecord(); 
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

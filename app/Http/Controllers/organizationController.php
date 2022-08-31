<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controllers;
use Auth;
use App\organizations;
use App\organization_type;
use App\cities;
use App\Post;
use App\user_roles;
use App\activity_logs;
use App\auditLog;
use App\requests;
use App\User;
class organizationController extends Controller
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
        organizationController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => "Organizations List",
            'title'     => "Organizations List",
            'subtitle'  => ''
        ];
           $organizations = organizations::selectAllRecords();
           $organizationsLists = requests::AllTransactionOrganizationList();
           return view('admin.organizations.index', compact('page', 'organizations','organizationsLists')); 
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
        organizationController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Create New Organization',
            'title'     => 'Create New Organization',
            'subtitle'  => ''
        ];
           $acities = cities::searchCityList();
           $orgtypes = organization_type::selectOrganizationType();
           return view('admin.organizations.create', compact('page', 'acities', 'orgtypes'));   
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
        organizationController::permission();
        $validate = organizations::validateNewEntryDetails($request);
 
        if ($validate === true) {
            $message = "Record already exists!";
            return Redirect::back()->withErrors($message);
        } else {
            $result = organizations::insertNewRecord($request);
            auditLog::StoreNewOrganization($request);
        if ($result === true) {
            $message = "New Record added successfully!";
            return Redirect('/admin/organizations/create')->with('formSuccess', $message);
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
        organizationController::permission();
        $page = [
            'parent'   => '/dashboard',
            'child'    => '/',
            'crumb'    => 'Organizations Details',
            'title'    => 'Organizations Details',
            'subtitle' => ''
        ];

        $organizations = organizations::selectOrganizationDetails($id);
        if ($organizations) {
            $organizations = $organizations[0];
            return view('admin.organizations.show', compact('page', 'organizations'));
        } else {
            return Redirect('/admin/organizations')->withErrors("The record you are trying to view does not exists!");
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
        organizationController::permission();
        $page = [
            'parent'   => '/dashboard',
            'child'    => '/',
            'crumb'    => 'Edit Organizations Details',
            'title'    => 'Edit Organizations Details',
            'subtitle' => ''
        ];

        $organizations = organizations::selectOrganizationDetails($id);
        $acities = cities::searchCity();
        $orgtypes = organization_type::selectOrganizationType();
        if ($organizations) {

            $organizations = $organizations[0];
            return view('admin.organizations.edit', compact('page', 'organizations', 'acities', 'orgtypes'));
        } else {
            return Redirect('/admin/organizations')->withErrors("The record you are trying to view does not exists!");
        }
    }
}
    public function delete($id)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        $result = organizations::deleteOrganizationDetails($id);
        
            if ($result === true) {
                auditLog::deleteOrganizationlLogs($id);
                $message = "Record Successfully Deleted!";
                return Redirect('/admin/organizations')->with('formSuccess', $message);
            } else {
                return Redirect::back()->withErrors($result);
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
        organizationController::permission();
        $this->validate($request, [
            'txt_organization_name' =>'required',
        ]);  

        // Check if stat name was already taken
        $validate = organizations::validateOrganizationDetails($request, $id);
        if ($validate === true) {
            $message = "Organization already exists!";
            return Redirect::back()->withErrors($message);
        } else {
        auditLog::UpdateOrganization($request, $id);
        $activityLog = activity_logs::insertOrganizationLogs($request, $id); 
        $result = organizations::updateOrganizationDetails($request, $id);
            if ($result === true) {
                $message = "Record Successfully Updated!";
                return Redirect('/admin/organizations')->with('formSuccess', $message);
            } else {
                return Redirect::back()->withErrors($result);
            }
        }
    }
}

    public function searchAddress($id)
    {
        $result = organizations::searchAddress($id);
        return $result;
    }

    public function lookup()
    {
        $result = organizations::lookUplist();
        return $result;
    }

    public function SelectOrganizationsList()
    {
        $result = organizations::selectOrganizationList();
        return $result;
    }

    public function selectCities()
    {
        $result = cities::searchCity();
        return $result;
    }

    public function searchCityList()
    {
        $result = cities::searchCityList();
        return $result;
    }
    public function selectOrganizationType()
    {
        $result = organization_type::selectOrganizationType();
        return $result;
    }

    public function selectOrganizationTypeList()
    {
        $result = organization_type::selectOrganizationTypeList();
        return $result;
    }
    public function storeOrgNew($id)
    {

        $validate = organizations::validateNewOrgDetails($id);
        if ($validate === true) {
            $result = 1;
            return $result;
        } else {
            $resultOrg = organizations::insertNewOrg($id);
         if ($resultOrg === true) {
            auditLog::StoreNewOrganizationOnTheFlyLogs($id);
            $result = 0;
            return $result;
        } else {
            $result = 1;
            return $result;
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

    public function PrintAllOrganizations(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        organizationController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Reports',
            'title'     => 'Reports',
            'subtitle'  => ''
        ];
        if ($request->chk_selectOneOrg) {
           $organizations = organizations::selectAllOrganizationsList($request);
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
           return view('admin.organizations.print-all-organizations', compact('page', 'organizations', 'usigns', 'uposition'));         
           } else {
                $message='Please check atleast one or more records to print.';
            return Redirect::back()->withErrors($message);
        }  
    }
  }
}

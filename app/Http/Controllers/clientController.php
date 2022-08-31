<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controllers;
use App\organizations;
use App\clients;
use App\Post;
use App\user_roles;
use App\activity_logs;
use App\requests;
use App\auditLog;
use App\User;
use Auth;
class clientController extends Controller
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
    clientController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => "Client's List",
            'title'     => "Client's List",
            'subtitle'  => ''
        ];
           $clients = clients::selectAllLists();
           $clientsRequest = requests::AllTransactionClientList();
           $asd = array_column($clientsRequest, 'request_client_name');
           $clientsRequest = $clientsRequest;
           return view('admin.clients.index', compact('page', 'clients', 'clientsRequest')); 
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
        clientController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => "Create New Client",
            'title'     => "Create New Client",
            'subtitle'  => ''
        ];
           $organizations = organizations::selectAllRecord();
           return view('admin.clients.create', compact('page', 'organizations'));    
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
        clientController::permission();
        $validate = clients::validateNewEntryDetails($request);
        if ($validate === true) {
            $message = "Record already exists!";
            return Redirect::back()->withErrors($message);
        } else {
            $result = clients::insertNewRecord($request);

        if ($result === true) {
            auditLog::newClientLogs($request);
            $message = "New Record added successfully!";
            return Redirect('/admin/clients/create')->with('formSuccess', $message);
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
    public function show($client)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        clientController::permission();
        $page = [
            'parent'   => '/dashboard',
            'child'    => '/',
            'crumb'    => "Client's Details",
            'title'    => "Client's Details",
            'subtitle' => ''
        ];
        $clients = clients::selectClientsDetails($client);
        if ($clients) {
            $clients = $clients[0];
            return view('admin.clients.show', compact('page', 'clients'));
        } else {
            return Redirect('/admin/clients')->withErrors("The record you are trying to view does not exists!");
        }
    }
}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($client)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        clientController::permission();
        $page = [
            'parent'   => '/dashboard',
            'child'    => '/',
            'crumb'    => "Edit Client's Details",
            'title'    => "Edit Client's Details",
            'subtitle' => ''
        ];

        $clients = clients::selectClientsDetails($client);
        $organizations = organizations::selectAllRecord();
        if ($clients) {
            $clients = $clients[0];
            return view('admin.clients.edit', compact('page', 'clients','organizations'));
        } else {
            return Redirect('/admin/clients')->withErrors("The record you are trying to view does not exists!");
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
        clientController::permission();
        $this->validate($request, [
            'txt_clients_name' =>'required',
        ]);  

        // Check if stat name was already taken
        $validate = clients::validateEntryDetails($request, $id);
        if ($validate === true) {
            $message = "Record type already exists!";
            return Redirect::back()->withErrors($message);
        } else {
        auditLog::updateClientLogs($request, $id);
        $activityLog = activity_logs::insertClientsLogs($request, $id);
        $result = clients::updateClientDetails($request, $id);
            if ($result === true) {
                $message = "Record Successfully Updated!";
                return Redirect('/admin/clients')->with('formSuccess', $message);
            } else {
                return Redirect::back()->withErrors($result);
            }
        }
    }
}
    public function find($id)
    {
        $result = organizations::searchAddress($id);
        return $result;
    }

    public function allList()
    {
        $result = clients::selectAllList();
        return $result;
    }

    public function allRecordList()
    {
        $result = clients::selectAllClientsRecordList();
        return $result;
    }


    public function delete($id)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        $result = clients::deleteClientDetails($id);
            if ($result === true) {
                auditLog::deleteClientLogs($id);
                $message = "Record Successfully Deleted!";
                return Redirect('/admin/clients')->with('formSuccess', $message);
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

    public function indexSelectAllClients(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        clientController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Inventory Logs',
            'title'     => 'InventoryLogs',
            'subtitle'  => ''
        ];
        if ($request->chk_selectOneClient) {
           $clients = clients::selectAllClientsList($request);
           return view('admin.reports.index', compact('page', 'clients'));        
           } else {
                $message='Please check atleast one or more records to print.';
            return Redirect::back()->withErrors($message);
        }  
    }
}

    public function PrintAllClients(Request $request)
    {
        if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        clientController::permission();
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Clients',
            'title'     => 'Clients',
            'subtitle'  => ''
        ];
        if ($request->chk_selectOneClient) {
           $clients = clients::selectAllClientsList($request);
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
           return view('admin.clients.print-all-clients', compact('page', 'clients','usigns', 'uposition'));         
           } else {
                $message='Please check atleast one or more records to print.';
            return Redirect::back()->withErrors($message);
        }  
    }
}

}

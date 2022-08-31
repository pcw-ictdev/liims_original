<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controllers;
use App\iec;
use App\User;
use App\usertypes;
use App\materials;
use App\organizations;
use App\organization_type;
use Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     public function index()
    {
         if(Auth::user()->is_deleted == 1){
            auth()->logout();
            return redirect('/login');
        } else {
        $page = [
            'parent'    => '/dashboard',
            'child'     => '/',
            'crumb'     => 'Dashboard',
            'title'     => 'Dashboard',
            'subtitle'  => ''
        ];
           $iecstocks = count(iec::iecstocks());
           $iecAllRecords = iec::selectAllRecord();
           $iecCriticalItems = iec::selectCriticalItems();
           $asds = materials::selectAllRecord();
           $orgtypes = organization_type::selectOrganizationTypeList();
           $years = iec::selectAllYears();
           return view('admin.index', compact('page', 'iecstocks', 'iecAllRecords','iecCriticalItems', 'asds', 'years', 'orgtypes')); 
       }
    }

    public function destroy()
    {
        auth()->logout();
        // abort(403, 'Page not available');
        return redirect('/login');
    }
}

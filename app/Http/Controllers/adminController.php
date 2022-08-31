<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controllers;
use App\iec;
use App\organizations;
use App\clients;
use App\materials;
use App\provinces;
use App\cities;
use App\requests;
use App\User;
use App\usertypes;
use App\Post;
class adminController extends Controller
{
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
           $iecOrgGraphs = iec::graphOrgList();
           return view('admin.home', compact('page', 'iecstocks', 'iecAllRecords','iecCriticalItems', 'iecOrgGraphs')); 
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
}

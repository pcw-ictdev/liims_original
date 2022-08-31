<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
   // return view('welcome');
   // Route::get('/logout', 'HomeController@destroy');
    return redirect('/login');
});
Route::middleware(['auth'])->group(function () {
Route::GET('/admin/iec/create','iecController@create');
Route::GET('/admin/iec/material_inventory_report','requestController@materialInventoryReport');
Route::GET('/admin/iec/','iecController@index');
Route::GET('/admin/iec/create','iecController@create');
Route::GET('/admin/iec/show/{id}','iecController@show');
Route::GET('/admin/iec/edit/{id}','iecController@edit');
Route::POST('/admin/iec/store','iecController@store');
Route::POST('/admin/iec/update/{id}','iecController@update');
Route::GET('/admin/iec/delete/{id}','iecController@delete');
Route::GET('/admin/iec/critical-items','iecController@criticalItems');
Route::GET('/admin/iec/autocomplete/{id}','iecController@autocomplete');
Route::GET('/admin/iec/available-stocks/{id}','iecController@findStocks');
Route::GET('/admin/iec/look-up/','iecController@lookup');
Route::GET('/admin/iec/random-search/{id}','iecController@randomsearch');
Route::GET('/admin/iec/find-id/{id}','iecController@findID');
Route::POST('/admin/iec/update-stock','iecController@stocksUpdate');
// Route::POST('/admin/iec/items-update/','iecController@criticalItemsUpdate');
Route::GET('/admin/iec/items-update','iecController@criticalItemsUpdate');
Route::GET('/admin/iec/logs/','iecController@logs');
Route::GET('/admin/iec/printing-logs/','iecController@printingLogs');
Route::GET('/admin/iec/printing_logs-look-up/{id}','iecController@printingLogsList');
Route::POST('/admin/iec/printing-logs/date','iecController@printingLogsDate');
Route::POST('/admin/iec/printing-logs/autocomplete','iecController@printingLogsAutoComplete');
Route::POST('/admin/iecs/print-all/1','iecController@PrintAllIecs21');
Route::POST('/admin/printing-logs/print-all/1','iecController@PrintAllprintingLogs');
Route::POST('/admin/inventory-history/print-all/1','iecController@PrintAllInventoryLogs2');

// chano
// Route::POST('/admin/inventory-history/print-all/2','iecController@PrintAllInventoryLogs21'); - original code for print all in inventory history
// Added code
Route::POST('/admin/inventory-history/print-all/2','iecController@PrintAllInventoryLogs');
// end

Route::GET('/admin/iec/import/', 'iecController@import');
Route::POST('/admin/inventoryiec-history/print-all/1','iecController@PrintAllInventoryIECLogs21');
Route::POST('/admin/iec/import/submit', 'iecController@insertBulkRecord');
Route::POST('/admin/inventoryiec-history/print-all/2','iecController@PrintAllInventoryIECLogs2');

Route::GET('/admin','homeController@index');
Route::GET('/admin/users/','userController@index');
Route::GET('/admin/users/create','userController@create');
Route::GET('/admin/users/show/{id}','userController@show');
Route::GET('/admin/users/edit/{id}','userController@edit');
Route::POST('/admin/users/store','userController@store');
Route::POST('/admin/users/update/{id}','userController@update');
Route::GET('/users/updateinfo/{id}','userController@updateinfo');
Route::POST('/users/updatepasswordinfo/{id}','userController@updatepwdetails');
Route::GET('/admin/users/delete/{id}','userController@delrec');

Route::GET('/admin/organizations/','organizationController@index');
Route::GET('/admin/organizations/create','organizationController@create');
Route::GET('/admin/organizations/show/{id}','organizationController@show');
Route::GET('/admin/organizations/edit/{id}','organizationController@edit');
Route::POST('/admin/organizations/store','organizationController@store');
Route::POST('/admin/organizations/update/{id}','organizationController@update');
Route::GET('/admin/organizations/delete/{id}','organizationController@delete');
Route::GET('/admin/organization/look-up/','organizationController@lookup');


Route::GET('/admin/assets/','materialController@index');
Route::GET('/admin/assets/create','materialController@create');
Route::GET('/admin/assets/show/{id}','materialController@show');
Route::GET('/admin/assets/edit/{id}','materialController@edit');
Route::POST('/admin/materials/store','materialController@store');
Route::POST('/admin/materials/update/{id}','materialController@update');
Route::GET('/admin/assets/delete/{id}','materialController@delete');
Route::GET('/admin/materials/find/{id}','materialController@find');
Route::GET('/admin/materials/insert/{id}','materialController@save');
Route::GET('/admin/materials/list','materialController@materialLists');

Route::GET('/admin/clients/','clientController@index');
Route::GET('/admin/clients/create','clientController@create');
Route::GET('/admin/clients/show/{id}','clientController@show');
Route::GET('/admin/clients/edit/{id}','clientController@edit');
Route::POST('/admin/clients/store','clientController@store');
Route::POST('/admin/clients/update/{id}','clientController@update');
Route::GET('/admin/clients/delete/{id}','clientController@delete');
Route::GET('/admin/clients/find/{id}','clientController@find');
Route::GET('/admin/clients/insert/{id}','clientController@save');
Route::GET('/admin/clients/clientlist/','clientController@allList');
Route::GET('/admin/clients/clientrecordlist/','clientController@allRecordList');



Route::GET('/admin/requests/create','requestController@create');
Route::GET('/admin/requests/','requestController@index');
Route::GET('/admin/requests/provinces/{id}','requestController@searchProvince');
Route::GET('/admin/requests/cities/{id}','requestController@searchCity'); 
Route::GET('/admin/requests/cities/','requestController@searchCityName');
Route::GET('/admin/organizations/address/{id}','organizationController@searchAddress');

Route::POST('/admin/requests/preview-request','requestController@previewRequest');

// Route::GET('/admin/requests/autocomplete/{id}','requestController@findTransactionByK41eyword');
//Route::POST('/admin/requests/autocomplete','requestController@findTransactionByKeyword');
Route::GET('/admin/requests/find-by-date/','requestController@index');
Route::POST('/admin/requests','requestController@findTransactionByDate');
//Route::GET('/admin/requests/print-all/{id}','requestController@printAllTransaction');
Route::POST('/admin/print-all-requests/{id}','requestController@printAllRequests');
//Route::GET('/admin/requests/print-one/{id}','requestController@printOneTransaction');
Route::GET('/admin/clients/find-info/{id}','requestController@findClientInfo');


Route::GET('/admin/requests/validate-client-info/{id}','requestController@validateClientInfo');
Route::GET('/admin/requests/save-client-info/{id}','requestController@saveClientInfo');

Route::GET('/admin/requests/store/','requestController@store');
Route::POST('/admin/requests/store/','requestController@store');
Auth::routes();

Route::GET('/admin/requests/graph/','iecController@iecGraph');

Route::GET('/admin/requests/graph/iec/org','iecController@iecGraphOrg');

Route::GET('/admin/requests/chart/category/{id}','iecController@iecChartCategory');
Route::GET('/admin/requests/chart/category/all','iecController@iecGraphOrg');

Route::GET('/admin/iecs/available-stock','iecController@SelectIECStockAvailableChart');

///
Route::GET('/admin/regions/list/','iecController@SelectRegionsList');
Route::GET('/admin/provinces/list/','iecController@SelectProvincesList');
Route::GET('/admin/organizations/list/','organizationController@SelectOrganizationsList');
Route::GET('/admin/iec/org/{id}','iecController@SelectIECOrganizationsList');
Route::GET('/admin/iec/region/{id}','iecController@SelectIECRegionsList');
Route::GET('/admin/iec/province/{id}','iecController@SelectIECProvincesList');

Route::GET('/admin/organizations/cities/','organizationController@searchCityList');
Route::GET('/admin/organizations/type/','organizationController@selectOrganizationType');
///
Route::GET('/admin/organization/new/store/{id}','organizationController@storeOrgNew');

Route::GET('/admin/iec/history-look-up/{id}','iecController@lookUpHistoryLogs');
Route::GET('/admin/iec/current/threshold/{id}','iecController@lookUpIECthreshold');
Route::get('/home', 'HomeController@index')->name('home');

Route::GET('/user/roles/dele-record/{id}','userRolesController@deleteUserRole');
Route::GET('/admin/user_roles','userRolesController@index');
Route::POST('/admin/user_roles/store','userRolesController@store');
Route::GET('/admin/user_roles/find/{$id}','userRolesController@find');
Route::GET('/admin/user_roles/find/{$id}','userRolesController@update');
Route::POST('/admin/user_roles/update','userRolesController@update');
Route::GET('/admin/user/roles/list','userRolesController@rolesList');

Route::GET('/admin/activity_logs','activityLogsController@index');

Route::GET('/admin/auditlogs','auditlogsController@index');

Route::GET('/admin/reports','reportsController@index');

Route::GET('/admin/requests/graph/1/{id}','iecController@iecGraphOrg1');
Route::GET('/admin/requests/graph/org/{id}','iecController@iecGraphOrg');

Route::GET('/admin/contractor/addnew/{id}','iecController@addnewContractor');
Route::GET('/admin/contractor/lists/','iecController@selectAllContractorRecord');

Route::get('sendbasicemail','MailController@fnmail');

Route::get('/admin/iec/download/{id}','iecController@getDownload');


Route::GET('/admin/files/','filesController@index');
Route::GET('/admin/files/create','filesController@create');
Route::GET('/admin/files/show/{id}','filesController@show');
Route::GET('/admin/files/edit/{id}','filesController@edit');
Route::POST('/admin/files/store','filesController@store');
Route::POST('/admin/files/update/{id}','filesController@update');
Route::GET('/admin/files/delete/{id}','filesController@deleteRec');
Route::GET('/admin/files/delete-file/{id}','filesController@deleteFileRec');
Route::POST('/admin/files/new/{id}','filesController@storeNew');


Route::GET('/admin/contractors/','contractorsController@index');
Route::GET('/admin/contractors/create','contractorsController@create');
Route::GET('/admin/contractors/show/{id}','contractorsController@show');
Route::GET('/admin/contractors/edit/{id}','contractorsController@edit');
Route::POST('/admin/contractors/store','contractorsController@store');
Route::POST('/admin/contractors/update/{id}','contractorsController@update');
Route::GET('/admin/contractors/delete/{id}','contractorsController@deleteRec');
Route::GET('/admin/contractors/delete-file/{id}','contractorsController@deleteFileRec');

Route::POST('/admin/reports/','reportsController@indexSelectReport');

Route::POST('/admin/clients/print-all/2','clientController@PrintAllClients');
Route::POST('/admin/clients/print-all/1','clientController@PrintAllClients');

Route::POST('/admin/organizations/preview-list/2','organizationController@PrintAllOrganizations');
Route::POST('/admin/organizations/preview-list/1','organizationController@PrintAllOrganizations');

Route::POST('/admin/contractors/print-all/2','contractorsController@PrintAllContractors');
Route::POST('/admin/contractors/print-all/1','contractorsController@PrintAllContractors');

Route::POST('/admin/audit-logs/print-all/2','auditLogsController@PrintAllLogs');
Route::POST('/admin/audit-logs/print-all/1','auditLogsController@PrintAllLogs');
});
Route::get('/logout', 'HomeController@destroy');
Auth::routes();

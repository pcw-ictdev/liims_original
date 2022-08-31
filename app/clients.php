<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
class clients extends Model
{

    public static function selectAllRecord() {
        $result = DB::select("
        SELECT `id`, 
        `client_name`,
        `client_organization`,
        `client_designation`,
        `client_contact_no`,
        `is_deleted`,
        `authorID`,
        `created_at`,
        `updated_at`
        FROM `clients`
        WHERE is_deleted = 0
        ORDER BY `client_name` ASC
        ");
        return $result;
    }   
    public static function selectAllLists() {
        $result = DB::select("
        SELECT `C`.`id`, 
        `C`.`client_name`,
        `C`.`client_organization`,
        `C`.`client_designation`,
        `C`.`client_contact_no`,
        `C`.`is_deleted`,
        `C`.`created_at`,
        `C`.`authorID`,
        `O`.`organization_name`,
        `O`.`id`,
        `U`.`id`,
        `U`.`name`,
        `C`.`id`
        FROM `clients` `C`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        JOIN `users` `U`
        ON `U`.`id` = `C`.`authorID`
        WHERE `C`.`is_deleted` = 0
        ORDER BY `C`.`client_name` ASC
        ");
        return $result;
    }   

    public static function selectAllClientsRecordList() {
        $result = DB::select("
        SELECT `C`.`id`, 
        `C`.`client_name`
        FROM `clients` `C`
        WHERE `C`.`is_deleted` = 0
        ORDER BY `C`.`client_name` ASC
        ");
        return $result;
    }  

    public static function selectAllList() {
        $result = DB::select("
        SELECT `C`.`id`, 
        `C`.`client_name`,
        `C`.`client_organization`,
        `C`.`client_designation`,
        `C`.`client_contact_no`,
        `C`.`is_deleted`,
        `C`.`created_at`,
        `C`.`authorID`,
        `O`.`organization_name`,
        `O`.`id`,
        `U`.`id`,
        `U`.`name`,
        `C`.`id`
        FROM `clients` `C`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        JOIN `users` `U`
        ON `U`.`id` = `C`.`authorID`
        WHERE `C`.`is_deleted` = 0
        ORDER BY `C`.`client_name` ASC
        ");
        return $result;
    }   


    public static function validateNewClientInfo($client) {
    	$clientInfo = array();
    	$clientInfo = explode("_", $client);

      $result1 = DB::select("
        SELECT `id`, 
        `organization_name`
 
        FROM `organizations`
        WHERE `organization_name` = ?
        AND   `is_deleted`       = ?",
        
         [ 
           $clientInfo[1],
           0,
         ]
    );
        $result = DB::select("
        SELECT `id`, 
        `client_name`,
        `client_organization`,
        `client_designation`,
        `client_contact_no`,   
        `is_deleted`,
        `created_at`,
        `updated_at`
        FROM `clients`
        WHERE `client_name`          = ?
        AND   `client_organization`  = ?
        AND `is_deleted`            = ?",
        
         [ 
           $clientInfo[0],
           $result1[0]->id,
           0
         ]
    );
        return $result;
    }	

    public static function validateEntryDetails($request, $id) {
        $result = DB::select("
        SELECT `id`, 
        `client_name`,
        `client_organization`,
        `client_designation`,
        `client_contact_no`,
        `is_deleted`,
        `created_at`,
        `updated_at`
        FROM `clients`
        WHERE `client_name`          = ?
        AND   `client_organization`  = ?
        AND   `is_deleted`           = ?
        AND   `id`                  != ?",
        
         [ 
           $request->txt_clients_name,
           $request->txt_clients_organization,
           0,
           $id
         ]
    );
        if ( !empty($result) ) {
            return true;
        } else { 
            return false;
        }
    }   

    public static function validateNewEntryDetails($request) {
        $result = DB::select("
        SELECT `id`, 
        `client_name`,
        `client_organization`,
        `client_designation`,
        `client_contact_no`,
        `is_deleted`,
        `created_at`,
        `updated_at`
        FROM `clients`
        WHERE `client_name`          = ?
        AND   `client_organization`  = ?
        AND   `is_deleted`    = ?",
        
         [ 
           $request->txt_clients_name,
           $request->txt_clients_organization,
           1
         ]
    );
        if ( !empty($result) ) {
            return true;
        } else { 
            return false;
        }
    }
    public static function insertNewRecord($request) {
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `clients`
                     (
                         `client_name`,
                         `client_organization`,
        				 `client_designation`,
        				 `client_contact_no`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?)", [
                        $request->txt_clients_name,
                        $request->txt_clients_organization,
                        $request->txt_clients_designation,
						$request->txt_clients_contact_no,
                        Auth::user()->id,
                        Carbon::now('Asia/Manila')
                     ]
        );
    DB::commit();

    $result = true;
    } catch (\Exception $e) {
        DB::rollBack();
        $result = $e->getMessage();
    }
    return $result;
  }

    public static function insertClientInfo($client) {
    	$clientInfo = array();
    	$clientInfo = explode("_", $client);
 
      $result1 = DB::select("
        SELECT `id`, 
        `organization_name`
 
        FROM `organizations`
        WHERE `organization_name` = ?
        AND   `is_deleted`       = ?",
        
         [ 
           $clientInfo[1],
           0,
         ]
    );
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `clients`
                     (
                         `client_name`,
                         `client_organization`,
                         `client_designation`,
                         `client_contact_no`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?)", [
 						$clientInfo[0],
 						$result1[0]->id,
 						$clientInfo[2],
 						$clientInfo[3],
                        Auth::user()->id,
                        Carbon::now('Asia/Manila')
                     ]
        );
    DB::commit();

    $result = true;
    } catch (\Exception $e) {
        DB::rollBack();
        $result = $e->getMessage();
    }
    return $result;
  }



     public static function selectClientsDetails($client) {
        $result = DB::select("
        SELECT `C`.`id`, 
        `C`.`client_name`,
        `C`.`client_organization`,
        `C`.`client_designation`,
        `C`.`client_contact_no`,
        `C`.`is_deleted`,
        `C`.`created_at`,
        `C`.`updated_at`,
        `O`.`id`,
        `O`.`organization_name`,
        `O`.`organization_address`,
        `C`.`id`
        FROM `clients` `C`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        WHERE `C`.`id` = ?
        AND   `C`.`is_deleted` = ?",
        [
          $client,
          0
        ]
        );
        return $result;
    }  
     public static function findInfo($client) {
 
 $result = DB::select("
        SELECT `C`.`id`, 
        `C`.`client_name`,
        `C`.`client_organization`,
        `C`.`client_designation`,
        `C`.`client_contact_no`,
        `C`.`is_deleted`,
        `C`.`created_at`,
        `C`.`updated_at`,
        `O`.`organization_name`,
        `O`.`id`,
        `O`.`organization_name`,
        `O`.`organization_address`
        FROM `clients` `C`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        WHERE `C`.`client_name` = ?
        AND   `C`.`is_deleted`  = ?",
        [
          $client,
          0
        ]
        );
    return $result;
    } 


    public static function updateClientDetails($request, $id) {   
    $result = array();

    DB::beginTransaction();


    try {

        $affected = DB::update("
                UPDATE `clients`
                SET
                    `client_name`         = ?,
                    `client_organization` = ?,
                    `client_designation`  = ?,
                    `client_contact_no`   = ?,
                    `updated_at`          = ?,
                    `editorID`            = ?
                WHERE 
                    `id` = ?", [
                    $request->input('txt_clients_name'),
                    $request->input('txt_clients_organization'),
                    $request->input('txt_clients_designation'),
                    $request->input('txt_clients_contact_no'),
                    Carbon::now('Asia/Manila'),
                    Auth::user()->id,
                    $id

                ]
        ); 
    DB::commit();

    $result = true;


    } catch (\Exception $e) {
        DB::rollBack();
        $result = $e->getMessage();
    }

    return $result;
    }

    public static function deleteClientDetails($id) {   
    $result = array();

    DB::beginTransaction();


    try {

        $affected = DB::update("
                UPDATE `clients`
                SET
                    `is_deleted`          = ?,
                    `updated_at`          = ?,
                    `editorID`            = ?
                WHERE 
                    `id` = ?
                    AND `is_deleted` !=?", 
                    [
                        1,
                        Carbon::now('Asia/Manila'),
                        Auth::user()->id,
                        $id,
                        1
                    ]
        ); 
    DB::commit();
    if($affected == 0){
      $result = 'Record already deleted.';
    } else {
      $result = true;   
    }
    } catch (\Exception $e) {
        DB::rollBack();
        $result = $e->getMessage();
    }

    return $result;
    }

    public static function selectAllClientsList($request) {
        $iid = array();
        $iid = $request->chk_selectOneClient;
        $iid = implode(",", $iid);
        $result = DB::select("
        SELECT `C`.`id`, 
        `C`.`client_name`,
        `C`.`client_organization`,
        `C`.`client_designation`,
        `C`.`client_contact_no`,
        `C`.`is_deleted`,
        `C`.`created_at`,
        `C`.`authorID`,
        `O`.`organization_name`,
        `O`.`id`,
        `U`.`id`,
        `U`.`name`,
        `C`.`id`
        FROM `clients` `C`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        JOIN `users` `U`
        ON `U`.`id` = `C`.`authorID`
        WHERE `C`.`id`  IN  ($iid)
        AND `C`.`is_deleted` = 0
        ORDER BY `C`.`client_name` ASC
        ");
        return $result;
    }   

    public static function selectAllLists2($request) {
        $result = DB::select("
        SELECT `C`.`id`, 
        `C`.`client_name`,
        `C`.`client_organization`,
        `C`.`client_designation`,
        `C`.`client_contact_no`,
        `C`.`is_deleted`,
        `C`.`created_at`,
        `C`.`authorID`,
        `O`.`organization_name`,
        `O`.`id`,
        `U`.`id`,
        `U`.`name`,
        `C`.`id`
        FROM `clients` `C`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        JOIN `users` `U`
        ON `U`.`id` = `C`.`authorID`
        WHERE (date(`C`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND `C`.`is_deleted`           =  ?
        ORDER BY `C`.`client_name` DESC",        
        [
            date($request->txt_iec_date_from),
            date($request->txt_iec_date_to),
            0
        ]
        );
        return $result;
    }   
}
 
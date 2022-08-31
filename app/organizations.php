<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
class organizations extends Model
{
      public static function organizationsList() {
        $result = DB::select("
        SELECT `id`, 
        `organization_name`,
        `organization_address`,
        `organization_type`,
        `is_deleted`,
        `authorID`,
        `editorID`,
        `created_at`,
        `updated_at`
        FROM `organizations`

        ");
            if ( !empty($result) ) {
                return $result;
            } else { 
                return false;
            }
    }
    public static function selectAllRecord() {
        $result = DB::select("
        SELECT `O`.`id`,
        `O`.`organization_name`,
        `O`.`organization_address`,
        `O`.`organization_type`,
        `O`.`is_deleted`,
        `O`.`authorID`,
        `O`.`created_at`,
        `U`.`id`,
        `U`.`name`,
        `O`.`id`,
        `OT`.`org_type_id`,
        `OT`.`org_type_code`,
        `OT`.`org_type_desc`,
        `O`.`id`
        FROM `organizations` `O`
        JOIN `users` `U`
        ON `U`.`id` = `O`.`authorID`
        JOIN `organization_type` `OT`
        ON `OT`.`org_type_id` = `O`.`organization_type`
        WHERE `O`.`is_deleted` = 0
        ORDER BY `O`.`created_at` DESC
        ");
        return $result;
    }

    public static function selectAllRecords() {
        $result = DB::select("
        SELECT `O`.`id`,
        `O`.`organization_name`,
        `O`.`organization_address`,
        `O`.`organization_type`,
        `O`.`is_deleted`,
        `O`.`authorID`,
        `O`.`created_at`,
        `U`.`id`,
        `U`.`name`,
        `O`.`id`,
        `OT`.`org_type_id`,
        `OT`.`org_type_code`,
        `OT`.`org_type_desc`,
        `O`.`id`
        FROM `organizations` `O`
        JOIN `users` `U`
        ON `U`.`id` = `O`.`authorID`
        JOIN `organization_type` `OT`
        ON `OT`.`org_type_id` = `O`.`organization_type`
        WHERE `O`.`is_deleted` = 0
        ORDER BY `O`.`created_at` DESC
        ");
        return $result;
    }

      public static function selectOrganizationDetails($id) {
        $result = DB::select("
        SELECT `O`.`id`, 
        `O`.`organization_name`,
        `O`.`organization_address`,
        `O`.`organization_type`,
        `O`.`is_deleted`,
        `O`.`authorID`,
        `O`.`editorID`,
        `O`.`created_at`,
        `O`.`updated_at`,
        `U`.`id`,
        `U`.`name`,
        `OT`.`org_type_id`,
        `OT`.`org_type_code`,
        `OT`.`org_type_desc`,
        `O`.`id`
        FROM `organizations` `O`
        JOIN `users` `U`
        ON `U`.`id` = `O`.`authorID`
        JOIN `organization_type` `OT`
        ON `OT`.`org_type_id` = `O`.`organization_type`
        WHERE `O`.`id` = ?
        AND `O`.`is_deleted` = ?",
        [
          $id,
          0
        ]
        );
        return $result;
    }

      public static function validateNewEntryDetails($request) {
        $result = DB::select("
        SELECT `id`, 
        `organization_name`,
        `organization_type`,
        `organization_address`,
        `is_deleted`,
        `authorID`,
        `editorID`,
        `created_at`,
        `updated_at`
        FROM `organizations`
        WHERE `organization_name`       = ?
        AND   `organization_type`       = ?
        AND   `organization_address`    = ?
        AND   `is_deleted` = ?",
        
         [ 
           $request->input('txt_organization_name'),
           $request->input('txt_organization_type'),
           $request->input('txt_organization_city'),
           0
         ]
    );
            if ( !empty($result) ) {
                return true;
            } else { 
                return false;
            }
    }

      public static function validateNewOrgDetails($id) {
        $idd = explode("_", $id);
        $result = DB::select("
        SELECT `id`, 
        `organization_name`,
        `organization_type`,
        `organization_address`,
        `is_deleted`,
        `authorID`,
        `editorID`,
        `created_at`,
        `updated_at`
        FROM `organizations`
        WHERE `organization_name`       = ?
        AND   `organization_type`       = ?
        AND   `organization_address`    = ?
        AND   `is_deleted` = ?",
        
         [ 
           $idd[0],
           $idd[1],
           $idd[2],
           0,
         ]
    );
            if ( !empty($result) ) {
                return true;
            } else { 
                return false;
            }
    }    

    public static function insertNewOrg($id) {
    $idd = explode("_", $id);
 
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `organizations`
                     (
                         `organization_name`,
                         `organization_type`,
                         `organization_address`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?)", [
                        $idd[0],
                        $idd[1],
                        $idd[2],
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

    public static function insertNewRecord($request) {
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `organizations`
                     (
                         `organization_name`,
                         `organization_address`,
                         `organization_type`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?)", [
 						$request->input('txt_organization_name'),
 						$request->input('txt_organization_city'),
                        $request->input('txt_organization_type'),
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

     public static function validateOrganizationDetails($request) {
        $result = DB::select("
        SELECT `id`, 
        `organization_name`,
        `organization_address`,
        `organization_type`,
        `is_deleted`
        FROM `organizations`
        WHERE `organization_name`     = ?
        AND `organization_type`       = ?
        AND `organization_address`    = ?
        AND `is_deleted`              = ?",
        
         [ 
           $request->input('txt_organization_name'),
           $request->input('txt_organization_type'),
           $request->input('txt_organization_city'),
           0,
         ]
    );
            if ( !empty($result) ) {
                return true;
            } else { 
                return false;
            }
    }

    public static function updateOrganizationDetails($request, $id) {   
    $result = array();

    DB::beginTransaction();


    try {

        $affected = DB::update("
                UPDATE `organizations`
                SET
                    `organization_name`    = ?,
                    `organization_address` = ?, 
                    `organization_type`    = ?,
                    `updated_at`           = ?,
                    `editorID`             = ?
                WHERE 
                    `id` = ?", [
                    $request->input('txt_organization_name'),
                    $request->input('txt_organization_city'),
                    $request->input('txt_organization_type'),
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


    public function searchProvince()
    {
        $result = provinces::searchProvince();
        return $result;
    }

    public function searchCity()
    {
        $result = cities::searchCity();
        return $result;
    }


     public static function searchAddress($id) {
        $result = DB::select("
        SELECT `id`, 
        `organization_name`,
        `organization_type`,
        `organization_address`,
        `is_deleted`,
        `created_at`,
        `updated_at`
        FROM `organizations`
        WHERE `id` = ?
        AND `is_deleted` = ?
        ORDER BY `id` ASC",
        [
        	$id,
            0
        ]);
        return $result;
    }
      public static function lookUplist() {
        $result = DB::select("
        SELECT `id`, 
        `organization_name`,
        `organization_type`,
        `organization_address`,
        `is_deleted`,
        `authorID`,
        `editorID`,
        `created_at`,
        `updated_at`
        FROM `organizations`
        WHERE `is_deleted` = 0

        ");
            if ( !empty($result) ) {
                return $result;
            } else { 
                return false;
            }
    }

      public static function selectOrganizationList() {
        $result = DB::select("
        SELECT `id`, 
        `organization_name`,
        `is_deleted`
        FROM `organizations`
        WHERE `is_deleted` = 0
        ORDER BY `organization_name` ASC
    ");
        return $result;
    }
    public static function deleteOrganizationDetails($id) {   
    $result = array();

    DB::beginTransaction();


    try {

        $affected = DB::update("
                UPDATE `organizations`
                SET
                    `is_deleted`                = ?,
                    `updated_at`                = ?,
                    `editorID`                  = ?
                WHERE 
                    `id`                        = ?
                     AND `is_deleted`          != ?", 
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


    
      public static function selectAllLists($request) {
        $result = DB::select("
        SELECT `O`.`id`, 
        `O`.`organization_name`,
        `O`.`organization_address`,
        `O`.`organization_type`,
        `O`.`is_deleted`,
        `O`.`authorID`,
        `O`.`editorID`,
        `O`.`created_at`,
        `O`.`updated_at`,
        `U`.`id`,
        `U`.`name`,
        `OT`.`org_type_id`,
        `OT`.`org_type_code`,
        `OT`.`org_type_desc`,
        `O`.`id`
        FROM `organizations` `O`
        JOIN `users` `U`
        ON `U`.`id` = `O`.`authorID`
        JOIN `organization_type` `OT`
        ON `OT`.`org_type_id` = `O`.`organization_type`
        WHERE `O`.`is_deleted` = ?",
        [
          0
        ]
        );
        return $result;
    }

      public static function selectAllOrganizationsList($request) {
        $iid = array();
        $iid = $request->chk_selectOneOrg;
        $iid = implode(",", $iid);
        $result = DB::select("
        SELECT `O`.`id`, 
        `O`.`organization_name`,
        `O`.`organization_address`,
        `O`.`organization_type`,
        `O`.`is_deleted`,
        `O`.`authorID`,
        `O`.`editorID`,
        `O`.`created_at`,
        `O`.`updated_at`,
        `U`.`id`,
        `U`.`name`,
        `OT`.`org_type_id`,
        `OT`.`org_type_code`,
        `OT`.`org_type_desc`,
        `O`.`id`
        FROM `organizations` `O`
        JOIN `users` `U`
        ON `U`.`id` = `O`.`authorID`
        JOIN `organization_type` `OT`
        ON `OT`.`org_type_id` = `O`.`organization_type`
        WHERE `O`.`id` IN ($iid)
        AND `O`.`is_deleted` = ?",
        [
          0
        ]
        );
        return $result;
    }

      public static function selectAllLists2($request) {
        $result = DB::select("
        SELECT `O`.`id`, 
        `O`.`organization_name`,
        `O`.`organization_address`,
        `O`.`organization_type`,
        `O`.`is_deleted`,
        `O`.`authorID`,
        `O`.`editorID`,
        `O`.`created_at`,
        `O`.`updated_at`,
        `U`.`id`,
        `U`.`name`,
        `OT`.`org_type_id`,
        `OT`.`org_type_code`,
        `OT`.`org_type_desc`,
        `O`.`id`
        FROM `organizations` `O`
        JOIN `users` `U`
        ON `U`.`id` = `O`.`authorID`
        JOIN `organization_type` `OT`
        ON `OT`.`org_type_id` = `O`.`organization_type`
        WHERE (date(`O`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND `O`.`is_deleted`           =  ?
        ORDER BY `O`.`organization_name` DESC",        
        [
            date($request->txt_iec_date_from),
            date($request->txt_iec_date_to),
            0
        ]
        );
        return $result;
    }
}

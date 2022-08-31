<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
class user_roles extends Model
{
    public static function selectList() {
     $result = DB::select("
                SELECT  `R`.`user_role_id`,
                  `R`.`user_role`,
                  `R`.`user_description`
                FROM `user_roles` `R` 
                WHERE  `R`.`user_role_id` != ?
                AND `R`.`is_deleted`       = ?",
                [
                    1,
                    0
                ]
            );
    return $result;
    }
    public static function selectAllUserRoles() {
     $result = DB::select("
                SELECT  `R`.`user_role_id`,
                  `R`.`user_role`,
                  `R`.`user_description`,
                  `R`.`user_material_request`,
                  `R`.`user_inventory`,
                  `R`.`user_code_library`,
                  `R`.`user_management`,
                  `R`.`user_reports`,
                  `R`.`user_audit_log`,
                  `R`.`user_email_notif_iec_material`,
                  `R`.`authorID`,
                  `R`.`editorID`,
                  `R`.`created_at`,
                  `R`.`updated_at`,
                  `R`.`is_deleted`,
                  `U`.`id`,
                  `U`.`name`
                FROM `user_roles` `R`
                JOIN `users` `U`
                ON `U`.`id` = `R`.`authorID`
                WHERE  `R`.`user_role_id` != ?
                AND `R`.`is_deleted`       = ?",
                [
                    1,
                    0
                ]);
    return $result;
    }

    public static function selectAllUser_Roles() {
     $result = DB::select("
                SELECT  `R`.`user_role_id`,
                  `R`.`user_role`,
                  `R`.`user_description`,
                  `R`.`user_material_request`,
                  `R`.`user_inventory`,
                  `R`.`user_code_library`,
                  `R`.`user_management`,
                  `R`.`user_reports`,
                  `R`.`user_audit_log`,
                  `R`.`user_email_notif_iec_material`,
                  `R`.`is_deleted`,
                  `R`.`authorID`,
                  `R`.`editorID`,
                  `R`.`created_at`,
                  `R`.`updated_at`,
                  `U`.`id`,
                  `U`.`name`
                FROM `user_roles` `R`
                JOIN `users` `U`
                ON `U`.`id` = `R`.`authorID`
                WHERE `R`.`is_deleted` = 0
                ORDER by `R`.`user_role` ASC",
              );
    return $result;
    }

      public static function validateNewEntryDetails($request) {
        $result = DB::select("
        SELECT `user_role_id`,
            `user_role`,
            `user_description`,
            `user_material_request`,
            `user_inventory`,
            `user_code_library`,
            `user_management`,
            `user_reports`,
            `user_audit_log`,
            `user_email_notif_iec_material`,
            `authorID`,
            `editorID`,
            `created_at`,
            `updated_at`,
            `is_deleted`
        FROM `user_roles`
        WHERE `user_role`    = ?
        AND   `is_deleted`   = ?",
        
         [ 
           $request->input('add_txt_user_role'),
           0
         ]
    );
            if ( !empty($result) ) {
                return true;
            } else { 
                return false;
            }
    }
    public static function insertNewRecord($request) {
        if($request->input('add_user_material_request') == 0){
            $user_material_request = 2;
        } else {
            $user_material_request = $request->input('add_user_material_request');
        }

        if($request->input('add_user_inventory') == 0){
            $user_inventory = 2;
        } else {
            $user_inventory = $request->input('add_user_inventory');
        }

        if($request->input('add_user_code_library') == 0){
            $user_code_library = 2;
        } else {
            $user_code_library = $request->input('add_user_code_library');
        }

        if($request->input('add_user_management') == 0){
            $user_management =  2;
        } else {
            $user_management = $request->input('add_user_management');
        }

        if($request->input('add_user_reports') == 0){
            $user_reports = 2;
        } else {
            $user_reports = $request->input('add_user_reports');
        }

        if($request->input('add_user_audit_log') == 0){
            $user_audit_log = 2;
        } else {
            $user_audit_log = $request->input('add_user_audit_log');
        } 

        if($request->input('add_user_email_notif_iec_material') == 0){
            $user_email_notif_iec_material = 2;
        } else {
            $user_email_notif_iec_material = $request->input('add_user_email_notif_iec_material');
        }         

        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `user_roles`
                     (
                     `user_role`,
                     `user_description`,
                     `user_material_request`,
                     `user_inventory`,
                     `user_code_library`,
                     `user_management`,
                     `user_reports`,
                     `user_audit_log`,
                     `user_email_notif_iec_material`, 
                     `authorID`,
                     `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        $request->input('add_txt_user_role'),
                        $request->input('add_txt_user_role_desc'),
                        $user_material_request,
                        $user_inventory,
                        $user_code_library,
                        $user_management,
                        $user_reports,
                        $user_audit_log,
                        $user_email_notif_iec_material,
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
      public static function validateExistingRecord($request) {
        $result = DB::select("
        SELECT `user_role_id`,
            `user_role`,
            `user_description`,
            `user_material_request`,
            `user_inventory`,
            `user_code_library`,
            `user_management`,
            `user_reports`,
            `user_audit_log`,
            `user_email_notif_iec_material`,
            `authorID`,
            `editorID`,
            `created_at`,
            `updated_at`
        FROM `user_roles`
        WHERE `user_role`    = ? 
        AND   `user_role_id`!= ?",
        
         [ 
           $request->input('edit_txt_user_role'),
           $request->input('edit_userRoleID'),
         ]
    );
            if ( !empty($result) ) {
                return true;
            } else { 
                return false;
            }
    }

    public static function updateUserRoleDetails($request) {   
     $result = array();
    DB::beginTransaction();


    try {

        $affected = DB::update("
                UPDATE `user_roles`
                SET
                    `user_role`                     = ?,
                    `user_description`              = ?,
                    `user_material_request`         = ?,
                    `user_inventory`                = ?,
                    `user_code_library`             = ?,
                    `user_management`               = ?,
                    `user_reports`                  = ?,
                    `user_audit_log`                = ?,
                    `user_email_notif_iec_material` = ?,
                    `editorID`                      = ?,
                    `updated_at`                    = ?
                WHERE 
                    `user_role_id` = ?", [
                    $request->input('edit_txt_user_role'),
                    $request->input('edit_txt_user_role_desc'),
                              $request->input('txt_edit_user_material_request'),
                              $request->input('txt_edit_user_inventory'),
                              $request->input('txt_edit_user_code_library'),
                              $request->input('txt_edit_user_management'),
                              $request->input('txt_edit_user_reports'),
                              $request->input('txt_edit_user_audit_log'),
                              $request->input('txt_edit_user_email_notif_iec_material'),
                              Auth::user()->id,
                    Carbon::now('Asia/Manila'),
                    $request->input('edit_userRoleID')

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

    public static function userRolesList() {

                $result = DB::select("
                SELECT  `R`.`user_role_id`,
                  `R`.`user_role`,
                  `R`.`user_description`,
                  `R`.`user_material_request`,
                  `R`.`user_inventory`,
                  `R`.`user_code_library`,
                  `R`.`user_management`,
                  `R`.`user_reports`,
                  `R`.`user_audit_log`,
                  `R`.`user_email_notif_iec_material`
                FROM `user_roles` `R` 
                WHERE `R`.`user_role_id` = ?",
                    [
                        Auth::user()->uid
                    ]
                );
            return $result;
        }
    public static function deleteUserRoleDetails($id) {   
    $result = array();
    DB::beginTransaction();
    try {

        $affected = DB::update("
                UPDATE `user_roles`
                SET
                    `is_deleted`         = ?,
                    `updated_at`         = ?,
                    `editorID`           = ?
                WHERE 
                    `user_role_id`     = ?
                AND `is_deleted`!= ?", [
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
}

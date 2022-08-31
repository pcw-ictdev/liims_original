<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
class materials extends Model
{
    public static function selectAllRecords() {
        $result = DB::select("
        SELECT `M`.`id`,
        `M`.`material_name`,
        `M`.`material_desc`,
        `M`.`is_deleted`,
        `M`.`authorID`,
        `M`.`created_at`,
        `U`.`id`,
        `U`.`name`,
        `M`.`id`
        FROM `materials` `M`
        JOIN `users` `U`
        ON `U`.`id` = `M`.`authorID`
        WHERE `M`.`is_deleted` = 0
        ORDER BY `M`.`material_name` ASC
        ");
        return $result;
    }

    public static function selectAllRecord() {
        $result = DB::select("
        SELECT `M`.`id`,
        `M`.`material_name`,
        `M`.`material_desc`,
        `M`.`is_deleted`,
        `M`.`authorID`,
        `M`.`created_at`,
        `U`.`id`,
        `U`.`name`,
        `M`.`id`
        FROM `materials` `M`
        JOIN `users` `U`
        ON `U`.`id` = `M`.`authorID`
        WHERE `M`.`is_deleted` = 0
        ORDER BY `M`.`material_name` ASC
        ");
        return $result;
    }

    
      public static function selectMaterialDetails($id) {
        $result = DB::select("
        SELECT `id`, 
        `material_name`,
        `material_desc`,
        `is_deleted`,
        `authorID`,
        `editorID`,
        `created_at`,
        `updated_at`
        FROM `materials`
        WHERE `id` = ?
        AND `is_deleted` = ?",
        [
          $id,
          0
        ]
        );

        return $result;
    }

      public static function materialsList() {
        $result = DB::select("
        SELECT `id`, 
        `material_name`,
        `material_desc`,
        `is_deleted`,
        `authorID`,
        `editorID`,
        `created_at`,
        `updated_at`
        FROM `materials`
        WHERE `is_deleted` = 0
        ORDER BY `material_name` ASC

        ");
            if ( !empty($result) ) {
                return $result;
            } else { 
                return false;
            }
    }

      public static function validateNewEntryDetails($request) {
        $result = DB::select("
        SELECT `id`, 
        `material_name`,
        `material_desc`,
        `is_deleted`,
        `authorID`,
        `editorID`,
        `created_at`,
        `updated_at`
        FROM `materials`
        WHERE `material_name`     = ?
        AND `is_deleted` = ?",
        
         [ 
           $request->input('txt_material_name'),
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
                     INSERT INTO `materials`
                     (
                         `material_name`,
                         `material_desc`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?)", [
 						$request->input('txt_material_name'),
 						$request->input('txt_material_desc'),
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
      public static function validateMaterialDetails($request, $id) {
        $result = DB::select("
        SELECT `id`, 
        `material_name`
        `material_desc`,
        `is_deleted`
        FROM `materials`
        WHERE `material_name`     = ?
        AND `id` != ?
        AND `is_deleted` = ?", 
        
         [ 
           $request->input('txt_material_name'),
           $id,
           0
         ]
    );
            if ( !empty($result) ) {
                return true;
            } else { 
                return false;
            }
    }

    public static function updateMaterialDetails($request, $id) {   

    $result = array();

    DB::beginTransaction();


    try {

        $affected = DB::update("
                UPDATE `materials`
                SET
                    `material_name`   = ?,
                    `material_desc`   = ?,
                    `updated_at`      = ?,
                    `editorID`        = ?
                WHERE 
                    `id` = ?", [
                    $request->input('txt_material_name'),
                    $request->input('txt_material_desc'),
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

      public static function validateNewModalEntryDetails($id) {
      	$material = array();
        $material = explode("_", $id);
        $result = DB::select("
        SELECT `id`, 
        `material_name`,
        `material_desc`,
        `is_deleted`,
        `authorID`,
        `editorID`,
        `created_at`,
        `updated_at`
        FROM `materials`
        WHERE `material_name`     = ?
        AND `is_deleted`          = ?",
        
         [ 
           $material[0],
           0
         ]
    );
        return $result;
    }

    
    public static function insertNewModalRecord($id) {
      	$material = array();
        $material = explode("_", $id);
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `materials`
                     (
                         `material_name`,
                         `material_desc`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?)", [
 						            $material[0],
 						            $material[1],
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

    public static function deleteAssetsDetails($id) {   
    $result = array();

    DB::beginTransaction();


    try {

        $affected = DB::update("
                UPDATE `materials`
                SET
                    `is_deleted`                = ?,
                    `updated_at`                = ?,
                    `editorID`                  = ?
                WHERE `id`                      = ?
                AND `is_deleted`               != ?", 
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

}

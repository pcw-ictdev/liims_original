<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Image;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Auth;
use App\iec_ecopy;
class iec_ecopy extends Model
{


    public static function selectAllRecord() {
        $result = DB::select("
        SELECT `E`.`ecopy_id`, 
        `E`.`ecopy_iec_title`,
        `E`.`ecopy_iec_soft_copy`,
        `E`.`ecopy_version_no`,
        `E`.`is_deleted`,
        `E`.`authorID`,
        `E`.`created_at`,
        `I`.`id`,
        `I`.`iec_title`,
        `U`.`id` as `userID`,
        `U`.`name` as `userName`
        FROM `iec_ecopies` `E`
        JOIN `IECS` `I`
        ON `I`.`id` = `E`.`ecopy_iec_title`
        JOIN `users` `U`
        ON `U`.`id` = `E`.`authorID`
        WHERE `E`.`is_deleted` = 0", 
    );
    return $result;
   }  

    public static function selectAllRecordsList() {
        $result = DB::select("
        SELECT `E`.`ecopy_id`, 
        `E`.`ecopy_iec_title`,
        `E`.`ecopy_iec_soft_copy`,
        `E`.`ecopy_version_no`,
        `E`.`is_deleted`,
        `E`.`created_at`,
        `I`.`id`,
        `I`.`iec_title`,
        `U`.`id` as `userID`,
        `U`.`name` as `userName`
        FROM `iec_ecopies` `E`
        JOIN `IECS` `I`
        ON `I`.`id` = `E`.`ecopy_iec_title`
        JOIN `users` `U`
        ON `U`.`id` = `E`.`authorID` 
        WHERE `E`.`is_deleted` = 0
        ORDER BY `ecopy_iec_title` ASC", 
    );
    return $result;
   }
    public static function selectAllRecords() {
        $result = DB::select("
        SELECT `E`.`ecopy_id`, 
        `E`.`ecopy_iec_title`,
        `E`.`ecopy_iec_soft_copy`,
        `E`.`ecopy_version_no`,
        `E`.`is_deleted`,
        `E`.`authorID`,
        `E`.`created_at`,
        `I`.`id`,
        `I`.`iec_title`,
        `U`.`id` as `userID`,
        `U`.`name` as `userName`
        FROM `iec_ecopies` `E`
        JOIN `IECS` `I`
        ON `I`.`id` = `E`.`ecopy_iec_title`
        JOIN `users` `U`
        ON `U`.`id` = `E`.`authorID`
        WHERE `E`.`is_deleted` = 0", 
    );
    return $result;
   } 


    public static function validateNewEntryDetails($request) {
        $result = DB::select("
        SELECT `ecopy_iec_title`,
               `ecopy_iec_soft_copy`,
               `ecopy_version_no`
        FROM `iec_ecopies`
        WHERE `ecopy_iec_title`   = ?
        AND   `ecopy_version_no`  = ?
        AND   `is_deleted`      = ?",
         [ 
           $request->txt_ecopy_title,
           $request->txt_ecopy_version_no,
           0
         ]
        );
        if ( !empty($result) ) {
            return true;
        } else { 
            return false;
        }
    }   

    public static function validateNewIECEntryDetails($request) {
        $result = DB::select("
        SELECT `ecopy_iec_title`,
               `ecopy_iec_soft_copy`,
               `ecopy_version_no`,
               `is_deleted`
        FROM `iec_ecopies`
        WHERE `ecopy_iec_title`   = ?
        AND   `ecopy_version_no`  = ?
        AND   `is_deleted`        = ?",
         [ 
           $request->txt_ecopy_title[0],
           $request->txt_ecopy_version_no[0],
           0
         ]
        );
        if ( !empty($result) ) {
            return true;
        } else { 
            return false;
        }
    } 

    public static function validateEntryDetails($request, $id) {
        $result = DB::select("
        SELECT `ecopy_id`,
               `ecopy_iec_title`,
               `ecopy_iec_soft_copy`,
               `ecopy_version_no`
        FROM `iec_ecopies`
        WHERE `ecopy_id`         != ?
        AND   `ecopy_iec_title`   = ?
        AND   `ecopy_version_no`  = ?
        AND   `is_deleted`        = ?
        ",
         [ 
           $id, 
           $request->txt_ecopy_title,
           $request->txt_ecopy_version_no,
           0
         ]
        );
        if ( !empty($result) ) {
            return true;
        } else { 
            return false;
        }
    }
    public static function insertNewFileRecord($request) {
        // Random Strings
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pin = mt_rand(1000000, 9999999)
            . mt_rand(1000000, 9999999)
            . $characters[rand(0, strlen($characters) - 1)];
        $string = str_shuffle($pin);
 

        $iec_soft_copy = $request->file('txt_iec_soft_copy');
        $img_soft_copy_name = $string.'.'.$iec_soft_copy->getClientOriginalExtension();
        $destinationPath = public_path('/files/uploads/');
        $iec_soft_copy->move($destinationPath, $img_soft_copy_name);
        $image_soft_copy_file = '/files/uploads/' . $img_soft_copy_name;

        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `iec_ecopies`
                     (
                         `ecopy_iec_title`,
                         `ecopy_iec_soft_copy`,
                         `ecopy_version_no`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?)", [
                        $request->txt_ecopy_title,
                        $image_soft_copy_file,
                        $request->txt_ecopy_version_no,
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

    public static function insertNewIECFileRecord($request, $id) {
        // Random Strings
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pin = mt_rand(1000000, 9999999)
            . mt_rand(1000000, 9999999)
            . $characters[rand(0, strlen($characters) - 1)];
        $string = str_shuffle($pin);

        $iec_soft_copy = $request->txt_iec_soft_copy[0];
        $img_soft_copy_name = $string.'.'.$iec_soft_copy->getClientOriginalExtension();
        $destinationPath = public_path('/files/uploads/');
        $iec_soft_copy->move($destinationPath, $img_soft_copy_name);
        $image_soft_copy_file = '/files/uploads/' . $img_soft_copy_name;

        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `iec_ecopies`
                     (
                         `ecopy_iec_title`,
                         `ecopy_iec_soft_copy`,
                         `ecopy_version_no`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?)", [
                        $id,
                        $image_soft_copy_file,
                        $request->txt_ecopy_version_no[0],
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
        // Random Strings
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pin = mt_rand(1000000, 9999999)
            . mt_rand(1000000, 9999999)
            . $characters[rand(0, strlen($characters) - 1)];
        $string = str_shuffle($pin);
 

        $iec_soft_copy = $request->file('txt_iec_soft_copy');
        $img_soft_copy_name = $string.'.'.$iec_soft_copy->getClientOriginalExtension();
        $destinationPath = public_path('/files/uploads/');
        $iec_soft_copy->move($destinationPath, $img_soft_copy_name);
        $image_soft_copy_file = '/files/uploads/' . $img_soft_copy_name;

        $iecresults = DB::select("
        SELECT `I`.`id`, 
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`is_deleted`
        FROM `iecs` `I`
        WHERE `I`.`is_deleted` = ?
        AND `I`.`iec_title`    = ?",
        [
            0,
            $request->txt_iec_title
        ]
        );
        foreach($iecresults as $iecresult){}
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `iec_ecopies`
                     (
                         `ecopy_iec_title`,
                         `ecopy_iec_soft_copy`,
                         `ecopy_version_no`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?)", [
                        $iecresult->id,
                        $image_soft_copy_file,
                        $request->txt_iec_version_no,
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
                        $clientInfo[1],
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



    public static function selectEcopyRecord($id) {
        $result = DB::select("
        SELECT `E`.`ecopy_id`, 
        `E`.`ecopy_iec_title`,
        `E`.`ecopy_iec_soft_copy`,
        `E`.`ecopy_version_no`,
        `E`.`is_deleted`,
        `E`.`authorID`,
        `E`.`created_at`,
        `I`.`id`,
        `I`.`iec_title`
        FROM `iec_ecopies` `E`
        JOIN `IECS` `I`
        ON `I`.`id` = `E`.`ecopy_iec_title`
        WHERE `E`.`is_deleted`   = ?
        AND   `E`.`ecopy_id`     = ?", 
        [
            0,
            $id
        ]
        );
    return $result;
   }  

    public static function selectEcopyAllRecord() {
        $result = DB::select("
        SELECT `E`.`ecopy_id`, 
        `E`.`ecopy_iec_title`,
        `E`.`ecopy_iec_soft_copy`,
        `E`.`ecopy_version_no`,
        `E`.`is_deleted`,
        `E`.`authorID`,
        `E`.`created_at`,
        `I`.`id`,
        `I`.`iec_title`
        FROM `iec_ecopies` `E`
        JOIN `IECS` `I`
        ON `I`.`id` = `E`.`ecopy_iec_title`
        WHERE `E`.`is_deleted` = ?", 
        [
            0,
        ]
        );
    return $result;
   }  

    public static function selectEcopyAllRecords() {
        $result = DB::select("
        SELECT `E`.`ecopy_id`, 
        `E`.`ecopy_iec_title`,
        `E`.`ecopy_iec_soft_copy`,
        `E`.`ecopy_version_no`,
        `E`.`is_deleted`,
        `E`.`authorID`,
        `E`.`created_at`,
        `I`.`id`,
        `I`.`iec_title`
        FROM `iec_ecopies` `E`
        JOIN `IECS` `I`
        ON `I`.`id` = `E`.`ecopy_iec_title` 
        WHERE `E`.`is_deleted` = 0
        ORDER BY `E`.`created_at`",
        );
    return $result;
   }  

    public static function updateEcopyDetails($request, $id, $image_soft_copy_file) {   



    $result = array();

    DB::beginTransaction();


    try {
    if($request->file('txt_iec_soft_copy')){


        $affected = DB::update("
                UPDATE `iec_ecopies`
                SET
                    `ecopy_iec_title`     = ?,
                    `ecopy_iec_soft_copy` = ?,
                    `ecopy_version_no`    = ?,
                    `updated_at`          = ?,
                    `editorID`            = ?
                WHERE 
                    `ecopy_id` = ?", [
                    $request->input('txt_ecopy_title'),
                    $image_soft_copy_file,
                    $request->input('txt_ecopy_version_no'),
                    Carbon::now('Asia/Manila'),
                    Auth::user()->id,
                    $id

                ]
        ); 
    } else {
        $affected = DB::update("
                UPDATE `iec_ecopies`
                SET
                    `ecopy_iec_title`     = ?,
                    `ecopy_version_no`    = ?,
                    `updated_at`          = ?,
                    `editorID`            = ?
                WHERE 
                    `ecopy_id` = ?", [
                    $request->input('txt_ecopy_title'),
                    $request->input('txt_ecopy_version_no'),
                    Carbon::now('Asia/Manila'),
                    Auth::user()->id,
                    $id

                ]
        );  
    }
     DB::commit();

    $result = true;

    } catch (\Exception $e) {
        DB::rollBack();
        $result = $e->getMessage();
    }
    return $result;
    }

    public static function deleteRec($id) {   
        $result = array();

    DB::beginTransaction();


    try {

        $affected = DB::update("
                UPDATE `iec_ecopies`
                SET
                    `is_deleted`    = ?,
                    `updated_at`    = ?,
                    `editorID`      = ?
                WHERE 
                    `ecopy_id`      = ?
                AND `is_deleted  ` !=?", [
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
    public static function deleteFileRec($id) {   
        $result = array();

    DB::beginTransaction();


    try {

        $affected = DB::update("
                UPDATE `iec_ecopies`
                SET
                    `is_deleted`    = ?,
                    `updated_at`    = ?,
                    `editorID`      = ?
                WHERE 
                    `ecopy_id`      = ?
                AND `is_deleted`   !=?", [
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
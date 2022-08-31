<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use Image;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\contractors;
class contractors extends Model
{

    public static function insertNewRecord($id) {
    	$iid = array();
    	$iid = explode("_", $id);
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `contractors`
                     (
                         `contractor_name`,
                         `contractor_contact_person`,
                         `contractor_contact_no`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?)", [
 						$iid[0],
 						$iid[1],
 						$iid[2],
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
    public static function insertNewFileRecord($request) {
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `contractors`
                     (
                         `contractor_name`,
                         `contractor_contact_person`,
                         `contractor_contact_no`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?)", [
                        $request->txt_contractors_name,
                        $request->txt_contractors_contact_person,
                        $request->txt_contractors_contact_no,
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

    public static function validateNewRecord($id) {
        $iid = array();
        $iid = explode("_", $id);
        $result = DB::select("
        SELECT `contractor_id`,
               `contractor_name`,
               `contractor_contact_person`,
               `contractor_contact_no`,
               `is_deleted`
        FROM `contractors`
        WHERE `contractor_name`            = ?
        AND   `contractor_contact_person`  = ?
        AND   `is_deleted`          = ?",
         [ 
           $iid[0],
           $iid[1],
           0
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
        SELECT `contractor_id`,
               `contractor_name`,
               `contractor_contact_person`,
               `contractor_contact_no`,
               `is_deleted`
        FROM `contractors`
        WHERE `contractor_name`            = ?
        AND   `contractor_contact_person`  = ?
        AND   `is_deleted`                 = ?",
         [ 
           $request->txt_contractors_name,
           $request->txt_contractors_contact_person,
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
        SELECT `contractor_id`,
               `contractor_name`,
               `contractor_contact_person`,
               `contractor_contact_no`,
               `is_deleted`
        FROM `contractors`
        WHERE `contractor_id`             != ?
        AND   `contractor_name`            = ?
        AND   `contractor_contact_person`  = ?
        AND   `is_deleted`                 = ?",
         [ 
           $id,
           $request->txt_contractors_name,
           $request->txt_contractors_contact_person,
           0
         ]
        );
        if ( !empty($result) ) {
            return true;
        } else { 
            return false;
        }
    }
    public static function selectAllRecord() {
        $result = DB::select("
        SELECT `contractor_id`, 
        `contractor_name`,
        `contractor_contact_person`,
        `contractor_contact_no`,
        `is_deleted`  
        FROM `contractors`
        WHERE `is_deleted`  = ?
        ORDER BY contractor_name ASC",
         [ 
           0
         ]

    );
        return $result;
    }

    public static function selectAllRecords() {
        $result = DB::select("
        SELECT `contractor_id`, 
        `contractor_name`,
        `contractor_contact_person`,
        `contractor_contact_no`,
        `is_deleted`  
        FROM `contractors`
        WHERE `is_deleted` = 0
        ORDER BY contractor_name ASC",
    );
        return $result;
    }
    public static function selectContractorsRecord($id) {
        $result = DB::select("
        SELECT `contractor_id`, 
        `contractor_name`,
        `contractor_contact_person`,
        `contractor_contact_no`,
        `is_deleted`
        FROM `contractors`
        WHERE `contractor_id`    = ?
        AND `is_deleted`         = ?
        ORDER BY contractor_name ASC",
         [ 
           $id,
           0
         ]

    );
        return $result;
    }

    public static function updateContractorsDetails($request, $id) { 
    $result = array();
    DB::beginTransaction();

    try {
        $affected = DB::update("
                UPDATE `contractors`
                SET
                    `contractor_name`              = ?,
                    `contractor_contact_person`    = ?,
                    `contractor_contact_no`        = ?,
                    `updated_at`                   = ?,
                    `editorID`                     = ?
                WHERE 
                    `contractor_id` = ?", [
                    $request->txt_contractors_name,
                    $request->txt_contractors_contact_person,
                    $request->txt_contractors_contact_no,
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

    public static function deleteRec($id) {   
        $result = array();

    DB::beginTransaction();


    try {

        $affected = DB::update("
                UPDATE `contractors`
                SET
                    `is_deleted`       = ?,
                    `updated_at`       = ?,
                    `editorID`         = ?
                WHERE 
                    `contractor_id`    = ?
                    AND `is_deleted`  != ?", 
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

      public static function selectAllContractorPrintingLogsList() {
        $result = DB::select("
        SELECT 
        `C`.`contractor_id`,
        `C`.`contractor_name`,
        `C`.`is_deleted`,
        `P`.`id`,
        `P`.`iec_printing_contractor`
        FROM `contractors` `C` 
        JOIN `iec_printing_logs` `P`
        ON `P`.`iec_printing_contractor` = `C`.`contractor_id` 
        WHERE  `C`.`is_deleted` = 0
        ");
        return $result;
    }
      public static function selectAllContractorsList($request) {
        $iid = array();
        $iid = $request->chk_selectOneContractor;
        $iid = implode(",", $iid);
        $result = DB::select("
        SELECT 
        `C`.`contractor_id`,
        `C`.`contractor_name`,
        `C`.`contractor_contact_no`,
        `C`.`contractor_contact_person`,
        `C`.`is_deleted`
        FROM `contractors` `C` 
        WHERE  `C`.`contractor_id` IN ($iid)
        AND    `C`.`is_deleted` = 0
        ");
        return $result;
    }

    public static function selectAllRecords2($request) {
        $result = DB::select("
        SELECT `contractor_id`, 
        `contractor_name`,
        `contractor_contact_person`,
        `contractor_contact_no`,
        `is_deleted`  
        FROM `contractors`
        WHERE (date(`created_at`) BETWEEN ?
        AND                                   ?)
        AND `is_deleted`           =  ?
        ORDER BY `contractor_name` DESC",        
        [
            date($request->txt_iec_date_from),
            date($request->txt_iec_date_to),
            0
        ]
    );
        return $result;
    }


}

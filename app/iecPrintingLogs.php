<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
class iecPrintingLogs extends Model
{
public static function iecstocks() {  
        $result_iec = DB::select("
        SELECT `id`, 
        `iec_title`,
        `iec_threshold`
        FROM `iecs`
    ");
        return $result;
    } 

public static function insertPrintingInventory($request, $iec_rec) {
if($request->iec_option == 1){	
 
   $contractor_id = DB::select("
        SELECT `contractor_id`, 
        `contractor_name`,
        `is_deleted`
        FROM `contractors`
        WHERE `contractor_name` = ?",
        [
        $request->txt_iec_printing_contractor,
        ]
    );
    $contractor_id = $contractor_id[0]->contractor_id;
    $pieces = $request->input('iec_restock_pieces');
    $iec_res = array();
    $result = array();
    foreach($iec_rec as $iec_res); 
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `iec_printing_logs`
                     (
                         `iec_id`,
                         `iec_printing_date`,
                         `iec_printing_contractor`,
                         `iec_printing_cost`,
                         `iec_printing_pcs`,
                         `iec_printing_remarks`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?)", [
                     	$request->txt_iec_id,
                     	$request->txt_iec_printing_date,
                     	$contractor_id,
                     	$request->txt_iec_printing_cost,
                     	$pieces,
                     	$request->iec_remarks,
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
}

      public static function selectAllPrintingLogs() {
        $result = DB::select("
        SELECT `P`.`id`, 
        `P`.`iec_id`,
        `P`.`iec_printing_date`,
        `P`.`iec_printing_contractor`,
        `P`.`iec_printing_cost`,
        `P`.`iec_printing_pcs`,
        `P`.`iec_printing_remarks`,
        `P`.`authorID`,
        `P`.`editorID`,
        `P`.`created_at`,
        `U`.`id`,
        `U`.`name`
        FROM `iec_printing_logs` `P`   
        JOIN `users` `U`
        ON `U`.`id` = `P`.`authorID` 
        ORDER BY `P`.`created_at` DESC 
        ");
        return $result;
    }

      public static function selectPrintingLogsByDate($request) {
        $result = DB::select("
        SELECT `P`.`id`, 
        `P`.`iec_id`,
        `P`.`iec_printing_date`,
        `P`.`iec_printing_contractor`,
        `P`.`iec_printing_cost`,
        `P`.`iec_printing_pcs`,
        `P`.`iec_printing_remarks`,
        `P`.`authorID`,
        `P`.`editorID`,
        `P`.`created_at`,
        `U`.`id`,
        `U`.`name`,
        `P`.`id`
        FROM `iec_printing_logs` `P`   
        JOIN `users` `U`
        ON `U`.`id` = `P`.`authorID` 
        WHERE DATE(`P`.`created_at`) BETWEEN ?
        AND                                  ?
        ORDER BY `P`.`created_at` DESC",
          [
            $request->txt_iec_printing_date_from,
            $request->txt_iec_printing_date_to
          ]
        );
        return $result;
    }
      public static function selectPrintingLogsByKeyword($request) {
        $result = DB::select("
        SELECT `P`.`id`, 
        `P`.`iec_id`,
        `P`.`iec_printing_date`,
        `P`.`iec_printing_contractor`,
        `P`.`iec_printing_cost`,
        `P`.`iec_printing_pcs`,
        `P`.`iec_printing_remarks`,
        `P`.`authorID`,
        `P`.`editorID`,
        `P`.`created_at`,
        `U`.`id`,
        `U`.`name`,
        `P`.`id`
        FROM `iec_printing_logs` `P`   
        JOIN `users` `U`
        ON `U`.`id` = `P`.`authorID` 
        WHERE `P`.`iec_printing_contractor` LIKE ?
        OR   `P`.`iec_printing_cost`       LIKE ?
        OR   `U`.`name`                    LIKE ?  
        OR   `P`.`authorID`                LIKE ?    
        ORDER BY `P`.`created_at` DESC",
            [
            '%' . $request->txt_iec_printing_keyword . '%',
            '%' . $request->txt_iec_printing_keyword . '%',
            '%' . $request->txt_iec_printing_keyword . '%',
            '%' . $request->txt_iec_printing_keyword . '%',
            ]
        );
        return $result;
    }

      public static function PrintAllLogs($request) {
        $idd = array();
        $idd = implode(",", $request->chk_selectOne);
        $result = DB::select("
        SELECT `P`.`id`, 
        `P`.`iec_id`,
        `P`.`iec_printing_date`,
        `P`.`iec_printing_contractor`,
        `P`.`iec_printing_cost`,
        `P`.`iec_printing_pcs`,
        `P`.`iec_printing_remarks`,
        `P`.`authorID`,
        `P`.`editorID`,
        `P`.`created_at`,
        `U`.`id`,
        `U`.`name`,
        `P`.`id`,
        `I`.`id`,
        `I`.`iec_title`,
        `C`.`contractor_id`,
        `C`.`contractor_name`
        FROM `iec_printing_logs` `P`   
        JOIN `users` `U`
        ON `U`.`id` = `P`.`authorID` 
        JOIN `iecs` `I`
        ON `I`.`id` = `P`.`iec_id` 
        JOIN `contractors` `C`
        ON `C`.`contractor_id` = `P`.`iec_printing_contractor` 
        WHERE `P`.`id` IN ($idd) 
        ORDER BY `P`.`created_at` DESC
        ");
        return $result;
    }

      public static function selectAllPrintingLogsList() {
        $result = DB::select("
        SELECT `P`.`id` as `recordid`, 
        `P`.`iec_id`,
        `P`.`iec_printing_date`,
        `P`.`iec_printing_contractor`,
        `P`.`iec_printing_cost`,
        `P`.`iec_printing_pcs`,
        `P`.`iec_printing_remarks`,
        `P`.`authorID`,
        `P`.`editorID`,
        `P`.`created_at`,
        `I`.`id`,
        `I`.`iec_title`,
        `U`.`id`,
        `U`.`name` as `createdby`,
        `C`.`contractor_id`,
        `C`.`contractor_name`
        FROM `iec_printing_logs` `P`   
        JOIN `users` `U`
        ON `U`.`id` = `P`.`authorID` 
        JOIN `iecs` `I`
        ON `I`.`id` = `P`.`iec_id` 
        JOIN `contractors` `C`
        ON `C`.`contractor_id` = `P`.`iec_printing_contractor` 
        ORDER BY `P`.`created_at` DESC 
        ");
        return $result;
    }

      public static function selectAllPrintingLogsList2($request) {
        $result = DB::select("
        SELECT `P`.`id` as `recordid`, 
        `P`.`iec_id`,
        `P`.`iec_printing_date`,
        `P`.`iec_printing_contractor`,
        `P`.`iec_printing_cost`,
        `P`.`iec_printing_pcs`,
        `P`.`iec_printing_remarks`,
        `P`.`authorID`,
        `P`.`editorID`,
        `P`.`created_at`,
        `I`.`id`,
        `I`.`iec_title`,
        `U`.`id`,
        `U`.`name` as `createdby`,
        `C`.`contractor_id`,
        `C`.`contractor_name`
        FROM `iec_printing_logs` `P`   
        JOIN `users` `U`
        ON `U`.`id` = `P`.`authorID` 
        JOIN `iecs` `I`
        ON `I`.`id` = `P`.`iec_id` 
        JOIN `contractors` `C`
        ON `C`.`contractor_id` = `P`.`iec_printing_contractor` 
        WHERE (date(`P`.`iec_printing_date`) BETWEEN ?
        AND                                   ?)",
        [
            date($request->txt_iec_date_from),
            date($request->txt_iec_date_to),
        ]
        );
        return $result;
    }
}
 
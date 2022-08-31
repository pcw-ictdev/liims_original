<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
class iecStockUpdate extends Model
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

    public static function insertUpdatedIECStocks($request, $iec_rec) {
        $req_resu = DB::select("
               SELECT  `I`.`id`,
                        `I`.`iec_refno`,
                        `I`.`iec_title`,
                        `I`.`iec_author`,
                        `I`.`iec_publisher`,
                        `I`.`iec_copyright_date`,
                        `I`.`iec_material_type`,
                        `I`.`iec_threshold`
            FROM `iecs` `I`
            WHERE  `I`.`id` = $request->txt_iec_id",
            );
            foreach($req_resu as $iecResu) 
            {  }    
        if($request->iec_option  == 1){
           // $pieces = $request->input('iec_restock_pieces');
           $pieces = $iecResu->iec_threshold + $request->input('iec_restock_pieces');
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `iec_stock_updates`
                     (
                         `iec_update_id`,
                         `iec_update_threshold`,
                         `iec_update_type`,
                         `iec_update_pieces`,
                         `iec_current_threshold`,
                         `iec_update_remarks`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?)", [
                        $request->txt_iec_id,
                        $iecResu->iec_threshold,
                        $request->iec_option,
                        $request->iec_restock_pieces,
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
        }
        if($request->iec_option  == 2){
            $pieces = $request->input('iec_adjust_pieces');
        // $pieces = $iecResu->iec_threshold - $request->input('iec_adjust_pieces');
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `iec_stock_updates`
                     (
                         `iec_update_id`,
                         `iec_update_threshold`,
                         `iec_update_type`,
                         `iec_update_pieces`,
                         `iec_current_threshold`,
                         `iec_update_remarks`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?)", [
                        $request->txt_iec_id,
                        $iecResu->iec_threshold,
                        $request->iec_option,
                        $request->iec_adjust_pieces,
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
        }
        return $result;

  } 
    public static function insertUpdatedIECSInfo($request, $id) { //111
        $remarks = 'Update IEC details';
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `iec_stock_updates`
                     (
                         `iec_update_id`,
                         `iec_update_threshold`,
                         `iec_update_type`,
                         `iec_update_pieces`,
                         `iec_current_threshold`,
                         `iec_update_remarks`,
                         `authorID`,
                         `created_at`

                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?)", [
                        $id,
                        $request->txt_iec_threshold_old,
                        '3',
                        $request->txt_iec_threshold,
                        $request->txt_iec_threshold,
                        $remarks,
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

      public static function selectAllLogs() {
        $result = DB::select("
        SELECT `S`.`id`, 
        `S`.`iec_update_id`,
        `S`.`iec_update_title`,
        `S`.`iec_update_threshold`,
        `S`.`iec_update_type`,
        `S`.`iec_update_pieces`,
        `S`.`iec_update_remarks`,
        `S`.`authorID`,
        `S`.`editorID`,
        `S`.`created_at`,
        `U`.`id`,
        `U`.`name`,
        `I`.`id`,
        `I`.`iec_title`
        FROM `iec_stock_updates` `S`
        JOIN `users` `U`
        ON `U`.`id` = `S`.`authorID`
        JOIN `iecs` `I`
        ON `I`.`id` = `S`.`iec_update_id`      
        ORDER BY `S`.`created_at` DESC 
        ");
        return $result;
    }

      public static function HistoryLogsLookup() {
        $result = DB::select("
        SELECT `S`.`id` as 'history_id', 
        `S`.`iec_update_id`,
        `S`.`iec_update_title`,
        `S`.`iec_update_threshold`,
        `S`.`iec_update_type`,
        `S`.`iec_update_pieces`,
        `S`.`iec_current_threshold`,
        `S`.`iec_update_remarks`,
        `S`.`authorID`,
        `S`.`editorID`,
        `S`.`created_at`,
        `U`.`id`,
        `U`.`name`,
        `I`.`id`,
        `I`.`iec_title`,
        `I`.`iec_threshold`
        FROM `iec_stock_updates` `S`
        JOIN `users` `U`
        ON `U`.`id` = `S`.`authorID`
        JOIN `iecs` `I`
        ON `I`.`id` = `S`.`iec_update_id`  
        ORDER BY `S`.`id` DESC",
        );
        return $result;
    }

      public static function HistoryLogsLookup2($request) {
        $result = DB::select("
        SELECT `S`.`id` as 'history_id', 
        `S`.`iec_update_id`,
        `S`.`iec_update_title`,
        `S`.`iec_update_threshold`,
        `S`.`iec_update_type`,
        `S`.`iec_update_pieces`,
        `S`.`iec_current_threshold`,
        `S`.`iec_update_remarks`,
        `S`.`authorID`,
        `S`.`editorID`,
        `S`.`created_at`,
        `U`.`id`,
        `U`.`name`,
        `I`.`id`,
        `I`.`iec_title`,
        `I`.`iec_threshold`
        FROM `iec_stock_updates` `S`
        JOIN `users` `U`
        ON `U`.`id` = `S`.`authorID`
        JOIN `iecs` `I`
        ON `I`.`id` = `S`.`iec_update_id`  
        WHERE (date(`S`.`created_at`) BETWEEN ?
        AND                                   ?)
        ORDER BY `S`.`created_at` DESC",
        [
            date($request->txt_iec_date_from),
            date($request->txt_iec_date_to),
        ]
        );
        return $result;
    }

      public static function PrintAllInventoryLogs($request) {
        $iid = array();
        $iid = implode(',', $request->chk_selectOneIec);
        $result = DB::select("
        SELECT `S`.`id` as 'history_id', 
        `S`.`iec_update_id`,
        `S`.`iec_update_title`,
        `S`.`iec_update_threshold`,
        `S`.`iec_update_type`,
        `S`.`iec_update_pieces`,
        `S`.`iec_current_threshold`,
        `S`.`iec_update_remarks`,
        `S`.`authorID`,
        `S`.`editorID`,
        `S`.`created_at`,
        `S`.`updated_at`,
        `U`.`id`,
        `U`.`name`,
        `I`.`id`,
        `I`.`iec_title`,
        `I`.`iec_threshold`
        FROM `iec_stock_updates` `S`
        JOIN `users` `U`
        ON `U`.`id` = `S`.`authorID`
        JOIN `iecs` `I`
        ON `I`.`id` = `S`.`iec_update_id`  
        WHERE `S`.`id` IN ($iid) 
        ORDER BY `S`.`id` DESC",
        );
        return $result;
    }

   
       public static function selectIECAllRecords2($request) {
 
        $result = DB::select("
        SELECT `S`.`id` as 'history_id', 
        `S`.`iec_update_id`,
        `S`.`iec_update_title`,
        `S`.`iec_update_threshold`,
        `S`.`iec_update_type`,
        `S`.`iec_update_pieces`,
        `S`.`iec_current_threshold`,
        `S`.`iec_update_remarks`,
        `S`.`authorID`,
        `S`.`editorID`,
        `S`.`created_at`,
        `S`.`updated_at`,
        `U`.`id`,
        `U`.`name`,
        `I`.`id`,
        `I`.`iec_title`,
        `I`.`iec_threshold`
        FROM `iec_stock_updates` `S`
        JOIN `users` `U`
        ON `U`.`id` = `S`.`authorID`
        JOIN `iecs` `I`
        ON `I`.`id` = `S`.`iec_update_id`  
        WHERE (date(`I`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND `S`.`iec_update_type`           = ?
        ORDER BY `S`.`id` DESC",
        [
            date($request->txt_iec_date_from),
            date($request->txt_iec_date_to),
            4
        ]
        );
        return $result;
    }


   
       public static function selectIECAllRecords21($request) {
        $result = DB::select("
         SELECT 
         `R`.`id`,
         `R`.`iec_current_threshold`,          
         `R`.`iec_update_pieces`,
         `R`.`iec_update_threshold`,
         `R`.`iec_update_id`,
         `I`.`id` as 'iecID',
         `I`.`iec_title`,
         `I`.`iec_threshold` AS 'ending_balance',
         `I`.`created_at`
        FROM `iec_stock_updates` `R`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`iec_update_id`  
        WHERE (date(`R`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND   `R`.`iec_update_type`         = ?
        ORDER BY `R`.`id` ASC",
        [
            date($request->txt_iec_date_from),
            date($request->txt_iec_date_to),
            4
        ]
        );
        return $result;
}

       public static function selectIECAllRecords211($request) {
        $result = DB::select("
         SELECT 
         `R`.`id`,
         `R`.`iec_current_threshold`,          
         `R`.`iec_update_pieces`,
         `R`.`iec_update_threshold`,
         `R`.`iec_update_id`,
         `I`.`id` as 'iecID',
         `I`.`iec_title`,
         `I`.`iec_threshold` AS 'ending_balance',
         `I`.`created_at`
        FROM `iec_stock_updates` `R`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`iec_update_id`  
        WHERE (date(`R`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND   `R`.`iec_update_type`         = ?
        ORDER BY `R`.`id` ASC",
        [
            date('Y-m-d', strtotime($request->txt_iec_date_from)),
            date('Y-m-d', strtotime($request->txt_iec_date_to)),
            4
        ]
        );
        return $result;
}


       public static function selectIECAllRecords212($request) {
        $result = DB::select("
        SELECT  SUM(REPLACE(`R`.`iec_update_pieces`, ',', '')
         ) AS 'total_requested_pieces',
         `R`.`iec_update_id`,
         `R`.`iec_update_type`
        FROM `iec_stock_updates` `R`
        WHERE (date(`R`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND   `R`.`iec_update_type`         = ?
        GROUP BY `R`.`iec_update_id`,`R`.`iec_update_type`",
        [
            date($request->txt_iec_date_from),
            date($request->txt_iec_date_to),
            4
        ]
        );
        return $result;
}

       public static function selectIECAllRecords2121($request) {
        $result = DB::select("
        SELECT  SUM(REPLACE(`R`.`iec_update_pieces`, ',', '')
         ) AS 'total_requested_pieces',
         `R`.`iec_update_id`,
         `R`.`iec_update_type`
        FROM `iec_stock_updates` `R`
        WHERE (date(`R`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND   `R`.`iec_update_type`         = ?
        GROUP BY `R`.`iec_update_id`,`R`.`iec_update_type`",
        [
            date('Y-m-d', strtotime($request->txt_iec_date_from)),
            date('Y-m-d', strtotime($request->txt_iec_date_to)),
            4
        ]
        );
        return $result;
}

       public static function selectIECAllRecords213($request) {
        $result = DB::select("
         SELECT 
         `R`.`id`,
         `iec_update_id`,
         `R`.`iec_current_threshold`         
        FROM `iec_stock_updates` `R`
        WHERE (date(`R`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND   `R`.`iec_update_type`         = ?
        ORDER BY `R`.`id` DESC",
        [
            date($request->txt_iec_date_from),
            date($request->txt_iec_date_to),
            4
        ]
        );
        return $result;
}

       public static function selectIECAllRecords2131($request) {
        $result = DB::select("
         SELECT 
         `R`.`id`,
         `iec_update_id`,
         `R`.`iec_current_threshold`         
        FROM `iec_stock_updates` `R`
        WHERE (date(`R`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND   `R`.`iec_update_type`         = ?
        ORDER BY `R`.`id` DESC",
        [
            date('Y-m-d', strtotime($request->txt_iec_date_from)),
            date('Y-m-d', strtotime($request->txt_iec_date_to)),
            4
        ]
        );
        return $result;
}

       public static function selectIECAllRecords21311($request) {
        $iiecs = array();
        $iiecs = implode(",",$request->chk_selectOneIec);
        $result = DB::select("
         SELECT 
         `R`.`id`,
         `iec_update_id`,
         `R`.`iec_current_threshold`         
        FROM `iec_stock_updates` `R`
        WHERE `R`.`iec_update_id`  IN ($iiecs)
        AND   `R`.`iec_update_type`         = ?
        ORDER BY `R`.`id` DESC",
        [
            4
        ]
        );
        return $result;
}


       public static function selectIECAllRecordsDate($request) {
        //test
        $result = DB::select("
        SELECT  SUM(REPLACE(`I`.`iec_current_threshold`, ',', '')
         ) AS 'total_current_threshold',
        `I`.`iec_update_id`,
         `I`.`iec_update_title`
        FROM `iec_stock_updates` `I`
        WHERE (date(`I`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND   `I`.`iec_update_type`         = ?
        GROUP BY `I`.`iec_update_title`, `I`.`iec_update_id`",
        [
            date($request->txt_iec_date_from),
            date($request->txt_iec_date_to),
            4
        ]
        );
        return $result;
}


       public static function selectIECAllRecordsDate2($request) {
        $iid = array();
        $iid = implode(",", $request->chk_selectOneIec);
        $result = DB::select("
        SELECT  `I`.`iec_update_id`,
         `I`.`iec_update_title`,
         `I`.`iec_current_threshold`,
         `I`.`created_at`
        FROM `iec_stock_updates` `I`
        WHERE (date(`I`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND `I`.`iec_update_type`  = ?
        AND `I`.`iec_update_id`  IN ($iid)
        ORDER BY   `I`.`created_at` ASC",
        [
            date($request->txt_iec_date_from),
            date($request->txt_iec_date_to),
            4
        ]
        );
        return $result;
}

       public static function selectIECAllRecordsDate3($request) {
        $iid = array();
        $iid = implode(",", $request->chk_selectOneIec);
        $date_range_from = date('Y-m-d', strtotime($request->txt_iec_date_from));
        $date_range_to = date('Y-m-d', strtotime($request->txt_iec_date_to)); 
        $result = DB::select("
        SELECT  `I`.`iec_update_id`,
         `I`.`iec_update_title`,
         `I`.`iec_current_threshold`,
         `I`.`created_at`
        FROM `iec_stock_updates` `I`
        WHERE (date(`I`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND `I`.`iec_update_type`  = ?
        AND `I`.`iec_update_id`  IN ($iid)
        ORDER BY   `I`.`created_at` ASC",
        [
            date($date_range_from),
            date($date_range_to),
            4
        ]
        );
        return $result;
}
       public static function selectIECAllRecordstoPrint($request) {
        $iid = array();
        $iid = implode(",", $request->chk_selectOneIec);
        $result = DB::select("
        SELECT `S`.`id` as 'history_id', 
        `S`.`iec_update_id`,
        `S`.`iec_update_title`,
        `S`.`iec_update_threshold`,
        `S`.`iec_update_type`,
        `S`.`iec_update_pieces`,
        `S`.`iec_current_threshold`,
        `S`.`iec_update_remarks`,
        `S`.`authorID`,
        `S`.`editorID`,
        `S`.`created_at`,
        `S`.`updated_at`,
        `U`.`id`,
        `U`.`name`,
        `I`.`id`,
        `I`.`iec_title`,
        `I`.`iec_threshold`
        FROM `iec_stock_updates` `S`
        JOIN `users` `U`
        ON `U`.`id` = `S`.`authorID`
        JOIN `iecs` `I`
        ON `I`.`id` = `S`.`iec_update_id`  
        WHERE  `S`.`id`  IN  ($iid) 
        AND `S`.`iec_update_type`  = ?
        ORDER BY `S`.`id` DESC",
        [
            4
        ]
        );
        return $result;
    }


       public static function selectIECAllRecordstoPrint2($request) {
        $iid = array();
        $iid = implode(",", $request->chk_selectOneIec);
        $result = DB::select("
         SELECT SUM(REPLACE(`R`.`iec_current_threshold`, ',', '')
         ) AS 'total_current_threshold',
           SUM(REPLACE(`R`.`iec_update_pieces`, ',', '')
         ) AS 'total_pieces',
           SUM(REPLACE(`R`.`iec_update_threshold`, ',', '')
         ) AS 'total_update_threshold',
         `R`.`iec_update_id`,
         `I`.`iec_title`
        FROM `iec_stock_updates` `R`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`iec_update_id`  
        WHERE (date(`R`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND `R`.`iec_update_type`  = ?
        AND `R`.`iec_update_id`  IN ($iid)
        GROUP BY `I`.`iec_title`, `R`.`iec_update_id`",
        [
            date($request->txt_iec_date_from),
            date($request->txt_iec_date_to),
            4
        ]
        );
        return $result;
    }

       public static function selectIECAllRecordstoPrint3($request) {
        $date_range_from = date('Y-m-d', strtotime($request->txt_iec_date_from));
        $date_range_to = date('Y-m-d', strtotime($request->txt_iec_date_to)); 
        $iid = array();
        $iid = implode(",", $request->chk_selectOneIec);
        $result = DB::select("
         SELECT SUM(REPLACE(`R`.`iec_current_threshold`, ',', '')
         ) AS 'total_current_threshold',
           SUM(REPLACE(`R`.`iec_update_pieces`, ',', '')
         ) AS 'total_pieces',
           SUM(REPLACE(`R`.`iec_update_threshold`, ',', '')
         ) AS 'total_update_threshold',
         `R`.`iec_update_id`,
         `I`.`iec_title`
        FROM `iec_stock_updates` `R`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`iec_update_id`  
        WHERE (date(`R`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND `R`.`iec_update_type`  = ?
        AND `R`.`iec_update_id`  IN ($iid)
        GROUP BY `I`.`iec_title`, `R`.`iec_update_id`",
        [
            date($date_range_from),
            date($date_range_to),
            4
        ]
        );
        return $result;
    }

       public static function selectIECAllRecords21211($request) {
        $iiecs = array();
        $iiecs = implode(",",$request->chk_selectOneIec);
        $result = DB::select("
        SELECT  SUM(REPLACE(`R`.`iec_update_pieces`, ',', '')
         ) AS 'total_requested_pieces',
         `R`.`iec_update_id`,
         `R`.`iec_update_type`
        FROM `iec_stock_updates` `R`
        WHERE `R`.`iec_update_id` IN ($iiecs)
        AND   `R`.`iec_update_type`         = ?
        GROUP BY `R`.`iec_update_id`,`R`.`iec_update_type`",
        [
            4
        ]
        );
        return $result;
}


}

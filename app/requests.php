<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
class requests extends Model
{

	public static function insertNewRecord($request) {
        $result1 = DB::select("
        SELECT `C`.`id`, 
        `C`.`client_name`
        FROM `clients` `C`
        WHERE `C`.`is_deleted` = ?
        AND `C`.`client_name`  = ?
        ORDER BY `C`.`client_name` ASC",
        [
            0,
            $request->txt_request_name
        ]
        );
 
		if($request->chk_address_city == 'on'){
			$chkaddress = 1;
		} else {
			$chkaddress = 2;
		}
                    $inserted = DB::insert("
                     INSERT INTO `transactions`
                     (
                        `request_id`,
                        `request_client_name`,
                        `request_client_organization`,
                        `authorID`,
                        `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?)", [
                        $request->input('txt_request_no'),
                        $result1[0]->id,
                        $request->input('txt_request_organization'),
                        Auth::user()->id,
                        Carbon::now('Asia/Manila')
                     ]
        );
//
     if($inserted == true){
        $requestedMaterial = array(); 
        $requestedQuantity = array(); 
        $totalThreshold = array();
        $requestedMaterial = $request->txt_selectedMaterial;
        $requestedQuantity = $request->txt_selectedQuantity;
        $requestedRemaining = $request->txt_totalRemaining;

        DB::beginTransaction();
        $req_db = DB::select("
        SELECT `id`,
               `request_id`
        FROM `transactions`
        WHERE `request_id`     = ?",
         [ 
           $request->input('txt_request_no')

         ]
  );
foreach($req_db as $recno) 
{  }  
        $iid = array();
        $iid = implode("_", $requestedMaterial);
     //   $iid = implode(",", $requestedMaterial);
        //dd($iid);
        $iiid = explode("_", $iid);

         for ($i=0; $i <=count($requestedMaterial); $i++) { 

            try {
 
    if($requestedMaterial[$i] != NULL) {

    DB::beginTransaction();


    try {
        $affected = DB::update("
                UPDATE `iecs`
                SET
                    `iec_threshold`   = ?,
                    `updated_at`      = ?,
                    `editorID`        = ?
                WHERE 
                    `id` =  ?", [
                    $requestedRemaining[$i],
                    Carbon::now('Asia/Manila'),
                    Auth::user()->id,
                    $requestedMaterial[$i]
 
                ]
        ); 
    DB::commit();

    $result = true;


    } catch (\Exception $e) {
        DB::rollBack();
        $result = $e->getMessage();
    }
 
                     $inserted = DB::insert("
                     INSERT INTO `requests`
                     (
                        `request_id`,
                        `request_name`,
                         `request_organization`,
                         `request_address`,
                         `request_chk_address`,
                         `request_purpose`,
                         `request_material_name`,
                         `request_material_quantity`,
                         `is_deleted`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?)", [
                        $recno->id,
                        $result1[0]->id,
                        $request->input('txt_request_organization'),
                        $request->input('txt_request_address'),
                        $chkaddress,
                        $request->input('txt_request_purpose'),
                        $requestedMaterial[$i],
                        $requestedQuantity[$i],
                        0,
                        Auth::user()->id,
                        Carbon::now('Asia/Manila')
                     ]
        );
            DB::commit();
 
            
            }
          } catch (\Exception $ee) {
            DB::rollBack();
             $result = $ee->getMessage();
        }
         
      } //end  for
      $result = true; 
  	} else {
  	$result = false; 
    }
	return $result; 
   }

    public static function selectAllTransaction() {
        $result = DB::select("
        SELECT 
        `T`.`id`,
        `T`.`request_id`,
        `T`.`request_status`,
        `T`.`authorID`,
        `T`.`created_at`,
        `R`.`rec_id`,
        `R`.`request_id`,
        `R`.`request_name`,
        `R`.`request_organization`,
        `R`.`request_material_name`,
        `R`.`request_material_quantity`,
        `R`.`is_deleted`,
        `R`.`authorID`,
        `R`.`editorID`,
        `R`.`created_at`,
        `I`.`id`,
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_image`,
        `U`.`id`,
        `U`.`name`, 
        `C`.`id`,
        `C`.`client_name`,
        `C`.`client_organization`,
        `O`.`id`,
        `O`.`organization_name`,
        `T`.`id`
        FROM `transactions` `T`
        JOIN `requests` `R`
        ON `R`.`request_id` = `T`.`request_id`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`request_material_name`
        JOIN `clients` `C`
        ON `C`.`id` = `R`.`request_name`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        JOIN `users` `U`
        ON `U`.`id` = `T`.`authorID`
        WHERE `R`.`is_deleted` = 0
        ORDER BY `T`.`created_at` ASC
    ");
        return $result;
    } 

    public static function findTransactionByDate($request) {
        $result = DB::select("
        SELECT 
        `R`.`rec_id` as 'recordID',
        `R`.`request_id`,
        `R`.`request_name`,
        `R`.`request_organization`,
        `R`.`request_material_name`,
        `R`.`request_material_quantity`,
        `R`.`is_deleted`,
        `R`.`authorID`,
        `R`.`editorID`,
        `R`.`created_at`,
        `T`.`id` as 'trasactionID',
        `T`.`request_id` as 'requestID',
        `T`.`is_deleted`,
        `T`.`request_client_name`,
        `T`.`request_client_organization`,
        `T`.`authorID`,
        `T`.`created_at`,
        `I`.`id`,
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_image`,
        `U`.`id`,
        `U`.`name`, 
        `C`.`id`,
        `C`.`client_name`,
        `C`.`client_organization`,
        `O`.`id`,
        `O`.`organization_name`
        FROM `requests` `R`
        JOIN `transactions` `T`
        ON `T`.`id` = `R`.`request_id`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`request_material_name`
        JOIN `clients` `C`
        ON `C`.`id` = `R`.`request_name`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        JOIN `users` `U`
        ON `U`.`id` = `T`.`authorID`
        WHERE (date(`R`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND `R`.`is_deleted`           =  ?
        ORDER BY `R`.`created_at` DESC",        
        [
            date($request->txt_iec_date_from),
            date($request->txt_iec_date_to),
            0
        ]
    );
        return $result;
    }
    public static function AllTransactionList() {
        $result = DB::select("
        SELECT 
        `T`.`id` as 'trasactionID',
        `T`.`request_id` as requestID,
        `T`.`is_deleted`,
        `T`.`request_client_name`,
        `T`.`request_client_organization`,
        `T`.`authorID`,
        `T`.`created_at`,
        `R`.`rec_id` as 'recordID',
        `R`.`request_id`,
        `R`.`request_name`,
        `R`.`request_organization`,
        `R`.`request_material_name`,
        `R`.`request_material_quantity`,
        `R`.`is_deleted`,
        `R`.`authorID`,
        `R`.`editorID`,
        `R`.`created_at`,
        `I`.`id`,
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_image`,
        `U`.`id`,
        `U`.`name`, 
        `C`.`id`,
        `C`.`client_name`,
        `C`.`client_organization`,
        `O`.`id`,
        `O`.`organization_name`,
        `T`.`id` as 'transactionID',
        `T`.`request_id`
        FROM `transactions` `T`
        JOIN `requests` `R`
        ON `R`.`request_id` = `T`.`id`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`request_material_name`
        JOIN `clients` `C`
        ON `C`.`id` = `R`.`request_name`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        JOIN `users` `U`
        ON `U`.`id` = `T`.`authorID`
        WHERE `R`.`is_deleted` = 0
        ORDER BY `R`.`created_at` DESC
        ");
        return $result;
    }


    public static function AllTransactionClientList() {
        $result = DB::select("
        SELECT 
        `T`.`id`,
        `T`.`request_id`,
        `T`.`is_deleted`,
        `T`.`request_client_name`,
        `T`.`request_client_organization`,
        `R`.`rec_id`,
        `R`.`request_id`,
        `R`.`request_name`,
        `R`.`is_deleted`,
        `I`.`id`,
        `U`.`id`,
        `U`.`name`, 
        `C`.`id`,
        `C`.`client_name`,
        `C`.`client_organization`,
        `O`.`id`,
        `O`.`organization_name`,
        `T`.`id`,
        `T`.`request_id`
        FROM `transactions` `T`
        JOIN `requests` `R`
        ON `R`.`request_id` = `T`.`id`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`request_material_name`
        JOIN `clients` `C`
        ON `C`.`id` = `R`.`request_name`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        JOIN `users` `U`
        ON `U`.`id` = `T`.`authorID`
        WHERE `T`.`is_deleted` = 0
        AND   `R`.`is_deleted` = 0
        ORDER BY `R`.`created_at` DESC
        ");
        return $result;
    }

    public static function selectTransactionKeywordResult($request) {
        $keyword = $request->txt_iec_keyword;
        $result = DB::select("
        SELECT 
        `T`.`id`,
        `T`.`request_id`,
        `T`.`is_deleted`,
        `T`.`request_client_name`,
        `T`.`request_client_organization`,
        `T`.`authorID`,
        `T`.`created_at`,
        `R`.`rec_id`,
        `R`.`request_id`,
        `R`.`request_name`,
        `R`.`request_organization`,
        `R`.`request_material_name`,
        `R`.`request_material_quantity`,
        `R`.`is_deleted`,
        `R`.`authorID`,
        `R`.`editorID`,
        `R`.`created_at`,
        `I`.`id`,
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_image`,
        `U`.`id`,
        `U`.`name`, 
        `C`.`id`,
        `C`.`client_name`,
        `C`.`client_organization`,
        `O`.`id`,
        `O`.`organization_name`,
        `T`.`id`
        FROM `transactions` `T`
        JOIN `requests` `R`
        ON `R`.`request_id` = `T`.`id`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`request_material_name`
        JOIN `clients` `C`
        ON `C`.`id` = `R`.`request_name`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        JOIN `users` `U`
        ON `U`.`id` = `T`.`authorID`
        WHERE `T`.`request_id`                   like ?
        OR    `C`.`client_name`                  like ?
        OR    `O`.`organization_name`            like ?
        OR    `I`.`iec_title`                    like ?
        OR    `U`.`name`                         like ?
        AND   `T`.`is_deleted`                      = ?
        ",
        [
            '%' . $keyword . '%',
            '%' . $keyword . '%',
            '%' . $keyword . '%',
            '%' . $keyword . '%',
            '%' . $keyword . '%',
            0
        ]
    );
        return $result;
    }
    public static function printSelectedTransaction($request) {
        $iiid = array();
        $iid = array();
        $iid = implode(",",$request->chk_selectOneRequest);
        $iiid = ($iid);
        $result = DB::select("
        SELECT `R`.`rec_id` as 'recordID',
        `R`.`request_id`,
        `R`.`request_name`,
        `R`.`request_organization`,
        `R`.`request_material_name`,
        `R`.`request_material_quantity`,
        `R`.`is_deleted`,
        `R`.`authorID`,
        `R`.`editorID`,
        `R`.`created_at`,
        `T`.`id` as 'transactionID',
        `T`.`request_id`,
        `T`.`is_deleted`,
        `T`.`authorID`,
        `T`.`created_at`,
        `I`.`id`,
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_image`,
        `U`.`id`,
        `U`.`name`, 
        `C`.`id`,
        `C`.`client_name`,
        `C`.`client_organization`,
        `O`.`id`,
        `O`.`organization_name`,
        `T`.`id`
        FROM `requests` `R`
        JOIN `transactions` `T`
        ON  `R`.`request_id` = `T`.`id`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`request_material_name`
        JOIN `clients` `C`
        ON `C`.`id` = `R`.`request_name`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        JOIN `users` `U`
        ON `U`.`id` = `T`.`authorID`
        WHERE `R`.`rec_id`   IN  ($iiid)
        ORDER BY `T`.`created_at` DESC"
    );
        return $result;
    }
    public static function printSingleTransaction($request) {
        $iiid = array();
        $iid = array();
        $iid =  implode(',',$request->chk_selectOne);
        $iiid = ($iid);
        $result = DB::select("
        SELECT `R`.`rec_id`,
        `R`.`request_id`,
        `R`.`request_name`,
        `R`.`request_organization`,
        `R`.`request_material_name`,
        `R`.`request_material_quantity`,
        `R`.`is_deleted`,
        `R`.`authorID`,
        `R`.`editorID`,
        `R`.`created_at`,
        `T`.`id`,
        `T`.`request_id`,
        `T`.`request_client_name`,
        `T`.`request_client_organization`,
        `T`.`is_deleted`,
        `T`.`authorID`,
        `T`.`created_at`,
        `I`.`id`,
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_image`,
        `U`.`id`,
        `U`.`name`, 
        `C`.`id`,
        `C`.`client_name`,
        `C`.`client_organization`,
        `O`.`id`,
        `O`.`organization_name`,
        `T`.`id`
        FROM `requests` `R`
        JOIN `transactions` `T`
        ON `T`.`id` = `R`.`request_id`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`request_material_name`
        JOIN `clients` `C`
        ON `C`.`id` = `R`.`request_name`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        JOIN `users` `U`
        ON `U`.`id` = `T`.`authorID`
        WHERE   `R`.`request_id` IN ($iiid)
        AND `R`.`is_deleted` = 0
        ORDER BY `T`.`created_at` ASC"
    );
        return $result;
    }

    public static function findMultipleTransaction($asd2) {
        $iid = array();
        $iid = (implode(",", $asd2)); 
        $result = DB::select("
        SELECT `R`.`rec_id`,
        `R`.`request_id`,
        `R`.`request_name`,
        `R`.`request_organization`,
        `R`.`request_material_name`,
        `R`.`request_material_quantity`,
        `R`.`is_deleted`,
        `R`.`authorID`,
        `R`.`editorID`,
        `R`.`created_at`,
        `T`.`id`,
        `T`.`request_id`,
        `T`.`request_client_name`,
        `T`.`request_client_organization`,
        `T`.`is_deleted`,
        `T`.`authorID`,
        `T`.`created_at`,
        `I`.`id`,
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_image`,
        `U`.`id`,
        `U`.`name`, 
        `C`.`id`,
        `C`.`client_name`,
        `C`.`client_organization`,
        `O`.`id`,
        `O`.`organization_name`,
        `T`.`request_id`,
        `T`.`id`
        FROM `requests` `R`
        JOIN `transactions` `T`
        ON `T`.`id` = `R`.`request_id`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`request_material_name`
        JOIN `clients` `C`
        ON `C`.`id` = `R`.`request_name`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        JOIN `users` `U`
        ON `U`.`id` = `T`.`authorID`
        WHERE   `R`.`request_id` IN ($iid)
        AND `R`.`is_deleted` = 0
        ORDER BY `T`.`created_at` DESC"
    );
        return $result;
    }

    public static function findSingleTransaction($transactions) {
    $request_id = $transactions[0]->id;
        $result = DB::select("
        SELECT `R`.`rec_id`,
        `R`.`request_id`,
        `R`.`request_name`,
        `R`.`request_organization`,
        `R`.`request_material_name`,
        `R`.`request_material_quantity`,
        `R`.`is_deleted`,
        `R`.`authorID`,
        `R`.`editorID`,
        `R`.`created_at`,
        `T`.`id`,
        `T`.`request_id`,
        `T`.`request_client_name`,
        `T`.`request_client_organization`,
        `T`.`is_deleted`,
        `T`.`authorID`,
        `T`.`created_at`,
        `I`.`id`,
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_image`,
        `U`.`id`,
        `U`.`name`, 
        `C`.`id`,
        `C`.`client_name`,
        `C`.`client_organization`,
        `O`.`id`,
        `O`.`organization_name`,
        `T`.`request_id`,
        `T`.`id`
        FROM `requests` `R`
        JOIN `transactions` `T`
        ON `T`.`id` = `R`.`request_id`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`request_material_name`
        JOIN `clients` `C`
        ON `C`.`id` = `R`.`request_name`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        JOIN `users` `U`
        ON `U`.`id` = `T`.`authorID`
        WHERE   `R`.`request_id` = ?
        AND `R`.`is_deleted`     = ?
        ORDER BY `T`.`created_at` ASC",
        [
            $request_id,
            0
        ]
    );
        return $result;
    }
    public static function FindTransactionIDFromPrintOne($id) {
        $iid = array();
        $iiid = array();
      //  $iid = implode(",",$id);
        $iiid = ($id);
        $result = DB::select("
        SELECT `R`.`rec_id`,
        `R`.`request_id`,
        `R`.`request_name`,
        `R`.`request_organization`,
        `R`.`request_material_name`,
        `R`.`request_material_quantity`,
        `R`.`is_deleted`,
        `R`.`authorID`,
        `R`.`editorID`,
        `R`.`created_at`,
        `T`.`id`,
        `T`.`request_id`,
        `T`.`request_client_name`,
        `T`.`request_client_organization`,
        `T`.`is_deleted`,
        `T`.`authorID`,
        `T`.`created_at`,
        `I`.`id`,
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_image`,
        `U`.`id`,
        `U`.`name`, 
        `C`.`id`,
        `C`.`client_name`,
        `C`.`client_organization`,
        `O`.`id`,
        `O`.`organization_name`,
        `T`.`id`
        FROM `requests` `R`
        JOIN `transactions` `T`
        ON `T`.`request_id` = `R`.`request_id`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`request_material_name`
        JOIN `clients` `C`
        ON `C`.`id` = `R`.`request_name`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        JOIN `users` `U`
        ON `U`.`id` = `T`.`authorID`
        WHERE `T`.`id`           = ? 
        AND `T`.`is_deleted`     = ?
        ORDER BY `T`.`created_at` ASC",
        [
            $iiid,
            0
        ]
    );
        return $result;
    }

    public static function selectTransactionCurrentYear($ryear) {
        $result = DB::select("
        SELECT 
        `T`.`id`,
        `T`.`request_id`,
        `T`.`is_deleted`,
        `T`.`authorID`,
        `T`.`created_at`
        FROM `transactions` `T`
        WHERE YEAR(`T`.`created_at`) = $ryear
        ORDER BY `T`.`created_at` ASC
    ");
        return $result;
    }

    public static function AllTransactionMaterialList() {
        $result = DB::select("
        SELECT 
        `T`.`id`,
        `T`.`request_id`,
        `T`.`is_deleted`,
        `R`.`rec_id`,
        `R`.`request_id`,
        `R`.`is_deleted`,
        `I`.`id`,
        `I`.`iec_material_type`,
        `T`.`id`,
        `T`.`request_id`,
        `M`.`id` as 'materialID',
        `M`.`material_name`,
        `M`.`is_deleted`
        FROM `transactions` `T`
        JOIN `requests` `R`
        ON `R`.`request_id` = `T`.`id`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`request_material_name`
        JOIN `materials` `M`
        ON `M`.`id` = `I`.`iec_material_type`
        WHERE `T`.`is_deleted` = 0
        AND   `R`.`is_deleted`    = 0
        ORDER BY `R`.`created_at` DESC
        ");
        return $result;
    }

    public static function AllTransactionOrganizationList() {
        $result = DB::select("
        SELECT 
        `T`.`id`,
        `T`.`request_id`,
        `T`.`is_deleted`,
        `R`.`rec_id`,
        `R`.`request_id`,
        `R`.`is_deleted`,
        `R`.`request_organization`,
        `T`.`id`,
        `T`.`request_id`,
        `O`.`id` as 'orgID',
        `O`.`organization_name`
        FROM `transactions` `T`
        JOIN `requests` `R`
        ON `R`.`request_id` = `T`.`id`
        JOIN `organizations` `O`
        ON `O`.`organization_name` = `R`.`request_organization`
        WHERE `T`.`is_deleted` = 0
        AND   `R`.`is_deleted` = 0
        ORDER BY `R`.`created_at` DESC
        ");
        return $result;
    }

    public static function previewRequest($request) {
        $result = DB::select("
        SELECT 
        `T`.`id`,
        `T`.`request_id` as 'transactionID',
        `T`.`is_deleted`,
        `R`.`rec_id`,
        `R`.`request_id`,
        `R`.`request_name`,
        `R`.`request_material_name`,
        `R`.`request_material_quantity`,
        `R`.`is_deleted`,
        `R`.`request_organization`,
        `R`.`request_purpose`,
        `O`.`id` as 'orgID',
        `O`.`organization_name`,
        `C`.`id`,
        `C`.`client_name`,
        `C`.`client_organization`,
        `C`.`client_designation`,
        `I`.`id`,
        `I`.`iec_title`
        FROM `transactions` `T`
        JOIN `requests` `R`
        ON `R`.`request_id` = `T`.`id`
        JOIN `organizations` `O`
        ON `O`.`organization_name` = `R`.`request_organization`
        JOIN `clients` `C`
        ON `C`.`id` = `R`.`request_name`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`request_material_name`
        WHERE `T`.`request_id` = ?",
        [
        $request->txt_request_no,
        ]
        );
        return $result;
    }
   
    public static function validateRequest($request) {
        $result = DB::select("
        SELECT 
        `T`.`id`,
        `T`.`request_id`,
        `T`.`is_deleted`,
        `R`.`rec_id`,
        `R`.`request_id`,
        `R`.`is_deleted`,
        `R`.`request_organization`,
        `T`.`id`,
        `T`.`request_id`,
        `O`.`id` as 'orgID',
        `O`.`organization_name`
        FROM `transactions` `T`
        JOIN `requests` `R`
        ON `R`.`request_id` = `T`.`id`
        JOIN `organizations` `O`
        ON `O`.`organization_name` = `R`.`request_organization`
        WHERE `T`.`request_id` = ?
        AND   `R`.`is_deleted` = ?
        ORDER BY `R`.`created_at` DESC",
        [
           $request->txt_request_no,
           0
        ]
        );
        return $result;
    }

    public static function AllTransactionList2($request) {
        $result = DB::select("
        SELECT 
        `T`.`id` as 'trasactionID',
        `T`.`request_id` as requestID,
        `T`.`is_deleted`,
        `T`.`request_client_name`,
        `T`.`request_client_organization`,
        `T`.`authorID`,
        `T`.`created_at`,
        `R`.`rec_id` as 'recordID',
        `R`.`request_id`,
        `R`.`request_name`,
        `R`.`request_organization`,
        `R`.`request_material_name`,
        `R`.`request_material_quantity`,
        `R`.`is_deleted`,
        `R`.`authorID`,
        `R`.`editorID`,
        `R`.`created_at`,
        `I`.`id`,
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_image`,
        `U`.`id`,
        `U`.`name`, 
        `C`.`id`,
        `C`.`client_name`,
        `C`.`client_organization`,
        `O`.`id`,
        `O`.`organization_name`,
        `T`.`id` as 'transactionID',
        `T`.`request_id`
        FROM `transactions` `T`
        JOIN `requests` `R`
        ON `R`.`request_id` = `T`.`id`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`request_material_name`
        JOIN `clients` `C`
        ON `C`.`id` = `R`.`request_name`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        JOIN `users` `U`
        ON `U`.`id` = `T`.`authorID`
        WHERE (date(`T`.`created_at`) BETWEEN ?

        AND                                   ?)
        ORDER BY `T`.`created_at`",
        [
            date($request->txt_iec_date_from),
            date($request->txt_iec_date_to),
        ]
        
        ); 
        return $result;
    }
}
 
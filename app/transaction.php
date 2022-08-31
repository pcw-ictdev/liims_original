<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
class transaction extends Model
{
  public static function selectTransactionByRecID($recids) {
 	$reci = array();
  	$reci = $recids;
        $result = DB::select("
        SELECT `T`.`id` as 'transactionID',
        `T`.`request_id` as 'transNO',
        `T`.`request_client_name`,
        `T`.`request_client_organization`,
        `T`.`is_deleted`,
        `T`.`authorID`,
        `T`.`created_at`,
        `U`.`id` as 'userID',
        `U`.`name`,
        `C`.`id` as 'clientID',
        `C`.`client_name`,
        `C`.`client_organization`,
        `O`.`id` as 'organizationID',
        `O`.`organization_name`,
        `R`.`request_id`,
        `R`.`request_material_name`,
        `R`.`request_material_quantity`,
        `I`.`id`,
        `I`.`iec_title`      
        FROM `transactions` `T`
        JOIN `users` `U`
        ON `U`.`id` = `T`.`authorID`
        JOIN `clients` `C`
        ON `C`.`id` = `T`.`request_client_name`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        JOIN `requests` `R`
        ON `T`.`id` = `R`.`request_id`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`request_material_name`
        WHERE  `T`.`id`  IN  ($reci) 
        ORDER BY `T`.`created_at` DESC  
    ");
        return $result;
    }

  public static function selectTransactionByRecID2($request) {
        $iid = array();
        $iid = (implode(",", $request)); 
        $result = DB::select("
        SELECT `T`.`id` as 'transactionID',
        `T`.`request_id` as 'transNO',
        `T`.`request_client_name`,
        `T`.`request_client_organization`,
        `T`.`is_deleted`,
        `T`.`authorID`,
        `T`.`created_at`,
        `U`.`id` as 'userID',
        `U`.`name`,
        `C`.`id` as 'clientID',
        `C`.`client_name`,
        `C`.`client_organization`,
        `O`.`id` as 'organizationID',
        `O`.`organization_name`,
        `R`.`request_id`,
        `R`.`request_material_name`,
        `R`.`request_material_quantity`,
        `I`.`id`,
        `I`.`iec_title`      
        FROM `transactions` `T`
        JOIN `users` `U`
        ON `U`.`id` = `T`.`authorID`
        JOIN `clients` `C`
        ON `C`.`id` = `T`.`request_client_name`
        JOIN `organizations` `O`
        ON `O`.`id` = `C`.`client_organization`
        JOIN `requests` `R`
        ON `T`.`id` = `R`.`request_id`
        JOIN `iecs` `I`
        ON `I`.`id` = `R`.`request_material_name`
        WHERE  `T`.`id`  IN  ($reci) 
        ORDER BY `T`.`created_at` DESC  
    ");
        return $result;
    }
}

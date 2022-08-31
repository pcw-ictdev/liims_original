<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use App\User;
class activity_logs extends Model
{
    public static function insertUserLogs($request, $id) {
        $result = DB::select("
               SELECT  `U`.`id`,
                        `U`.`name`,
                        `U`.`username`,
                        `U`.`email`,
                        `U`.`password`,
                        `U`.`uid`,
                        `U`.`authorID`,
                        `U`.`editorID`,
                        `UR`.`user_role_id`,
                        `UR`.`user_role`
            FROM `users` `U`
            JOIN `user_roles` `UR`
            ON `UR`.`user_role_id` = `U`.`uid`
            WHERE `U`.`id` = ?", [$id]
                 );
foreach($result as $userResu){

}
 //dd($userResu->name . " ". $request->user_name);
// dd($userResu->email . " " . $request->user_email);
if($userResu->name == $request->user_name){
	$userName = '';
} else {
	$userName = $userResu->name;
}

if($userResu->username == $request->user_empid){
	$userEmpid = '';
} else {
	$userEmpid =$request->user_empid;
}

if($userResu->email == $request->user_email){
	$userEmail = '';
} else {
	$userEmail = $request->user_email;
}
if($userResu->uid == $request->user_type){
	$userType = '';
} else {
        $resultURole = DB::select("
               SELECT  `UR`.`user_role_id`,
                        `UR`.`user_role`
            	FROM `user_roles` `UR`
            	WHERE `UR`.`user_role_id` = ?", 
            	[
            		$request->user_type
            	]
            );
foreach($resultURole as $usType){
}

	$userType = $usType->user_role;
}
if($request->input('user_newpassword') !='') {
$userPassword = 'Encrypted';
} else {
$userPassword = '';	
}

	//$userName != 0 ? $request->user_empid : '1'
	$activity_field = [];
	$activity_fieldNew =  ($userName !='' ? $request->user_name . ", " : '') . ($userEmpid !='' ? $request->user_empid . ", " : '') . ($userEmail !='' ? $request->user_email . ", " : '') . ($userType !='' ? $userType . ", " : '') . ($userPassword !='' ? $userPassword : '');

	$activity_fieldOld =  ($userName !='' ? $userResu->name . ", " : '') . ($userEmpid !='' ? $userResu->username . ", " : '') . ($userEmail !='' ? $userResu->email . ", " : '') . ($userType !='' ? $userResu->user_role . ", " : '') . ($userPassword !='' ? $userPassword : '');

	$activity_fields =  ($userName !='' ? 'Name' . ", " : '') . ($userEmpid !='' ? 'Username' . ", " : '') . ($userEmail !='' ? 'Email' . ", " : '') . ($userType !='' ? 'User Type' . ". " : '') . ($userPassword !='' ? 'Password' : '');
 
       DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `activity_logs`
                     (
                         `activity_title`,
                         `activity_module`,
                         `activity_field`,
                         `activity_old_value`,
                         `activity_new_value`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?)", [
 						'Updated an record in Users.' . $id,
 						'User Management',
						$activity_fields,
 						$activity_fieldOld,
 						$activity_fieldNew,
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
  
    public static function insertAssetsLogs($request, $id) {
        $result = DB::select("
               SELECT  `M`.`id`,
                        `M`.`material_name`,
                        `M`.`material_desc`,
                        `M`.`material_stock`
            FROM `materials` `M`
            WHERE `M`.`id` = ?", [$id]
                 );
foreach($result as $assetResu){

}
 //dd($userResu->name . " ". $request->user_name);
// dd($userResu->email . " " . $request->user_email);
if($assetResu->material_name == $request->txt_material_name){
	$assetName = '';
} else {
	$assetName = $assetResu->material_name;
}
if($assetResu->material_desc == $request->txt_material_desc){
	$assetDesc = '';
} else {
	$assetDesc = $assetResu->material_desc;
}
 
	//$userName != 0 ? $request->user_empid : '1'
	$activity_field = [];
	$activity_fieldNew =  ($assetName !='' ? $request->txt_material_name . ", " : '') . ($assetDesc !='' ? $request->txt_material_desc : '');

	$activity_fieldOld =  ($assetName !='' ? $assetResu->material_name . ", " : '') . ($assetDesc !='' ? $assetResu->material_desc : '');

	$activity_fields =  ($assetName !='' ? 'Asset Name' . ", " : '') . ($assetDesc !='' ? 'Description' : '');
 
       DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `activity_logs`
                     (
                         `activity_title`,
                         `activity_module`,
                         `activity_field`,
                         `activity_old_value`,
                         `activity_new_value`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?)", [
 						'Updated an record in Assets.' . $id,
 						'Asset Type Management',
						$activity_fields,
 						$activity_fieldOld,
 						$activity_fieldNew,
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

     public static function insertCLientsLogs($request, $id) {
        $result = DB::select("
               SELECT  `C`.`id`,
                        `C`.`client_name`,
                        `C`.`client_organization`,
                        `C`.`client_designation`,
                        `C`.`client_contact_no`
            FROM `clients` `C`
            WHERE `C`.`id` = ?", [$id]
                 );
foreach($result as $clientResu){

}
 //dd($userResu->name . " ". $request->user_name);
// dd($userResu->email . " " . $request->user_email);
if($clientResu->client_name == $request->txt_clients_name){
	$clientName = '';
} else {
	$clientName = $request->txt_clients_name;
}

if($clientResu->client_organization == $request->txt_clients_organization){
	$clientOrganization = '';
} else {
	$clientOrganization = $request->txt_clients_organization;
}

if($clientResu->client_designation == $request->txt_clients_designation){
	$clientDesignation = '';
} else {
	$clientDesignation = $request->txt_clients_designation;
} 

if($clientResu->client_contact_no == $request->txt_clients_contact_no){
	$clientContactNo = '';
} else {
	$clientContactNo = $request->txt_clients_contact_no;
} 
	//$userName != 0 ? $request->user_empid : '1'
	$activity_field = [];
	$activity_fieldNew =  ($clientName !='' ? $request->txt_clients_name . ", " : '') . ($clientOrganization !='' ? $request->txt_clients_organization . ", " : '') . ($clientDesignation !='' ? $request->txt_clients_designation . ", " : '') . ($clientContactNo !='' ? $request->txt_clients_contact_no : '');

	$activity_fieldOld =  ($clientName !='' ? $clientResu->client_name . ", " : '') . ($clientOrganization !='' ? $clientResu->client_organization . ", " : '') . ($clientDesignation !='' ? $clientResu->client_designation . ", " : '') . ($clientContactNo !='' ? $clientResu->client_contact_no : '');

	$activity_fields =  ($clientName !='' ? 'Clients Name' . ", " : '') . ($clientOrganization !='' ? 'Organization' . ", " : '') . ($clientDesignation !='' ? 'Designation' . ", " : '') . ($clientContactNo !='' ? 'Contact No' : '');
 
       DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `activity_logs`
                     (
                         `activity_title`,
                         `activity_module`,
                         `activity_field`,
                         `activity_old_value`,
                         `activity_new_value`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?)", [
 						'Updated an record in Clients.' . $id,
 						'Asset Type Management',
						$activity_fields,
 						$activity_fieldOld,
 						$activity_fieldNew,
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

     public static function insertOrganizationLogs($request, $id) {
        $result = DB::select("
               SELECT  `O`.`id`,
                        `O`.`organization_name`,
                        `O`.`organization_address`,
                        `O`.`organization_type`
            FROM `organizations` `O`
            WHERE `O`.`id` = ?", [$id]
                 );
foreach($result as $organizationResu){

}

        $resultOrgTypes = DB::select("
               SELECT  `OT`.`org_type_id`,
                        `OT`.`org_type_code`,
                        `OT`.`org_type_desc`
            FROM `organization_type` `OT`
            WHERE `OT`.`org_type_id` = ?", [$request->txt_organization_type]
                 );
foreach($resultOrgTypes as $orgTypeResul){

}

        $resultOrgType = DB::select("
               SELECT  `OT`.`org_type_id`,
                        `OT`.`org_type_code`,
                        `OT`.`org_type_desc`
            FROM `organization_type` `OT`
            WHERE `OT`.`org_type_id` = ?", [$request->organization_type]
                 );
foreach($resultOrgType as $orgTypeResu){

}

 //dd($userResu->name . " ". $request->user_name);
// dd($userResu->email . " " . $request->user_email);

if($organizationResu->organization_name == $request->txt_organization_name){
	$organizationName = '';
} else {
	$organizationName = $request->txt_organization_name;
}

if($organizationResu->organization_address == $request->txt_organization_city){
	$organizationAddress = '';
} else {
	$organizationAddress = $request->txt_organization_city;
} 

if($organizationResu->organization_type == $request->txt_organization_type){
	$organizationType = '';
} else {

        $resultOrgType = DB::select("
               SELECT  `OT`.`org_type_id`,
                        `OT`.`org_type_code`,
                        `OT`.`org_type_desc`
            FROM `organization_type` `OT`
            WHERE `OT`.`org_type_id` = ?", [$organizationResu->organization_type]
                 );
foreach($resultOrgType as $orgTypeRes){

}


	$organizationType =  $orgTypeRes->org_type_code;
} 

	//$userName != 0 ? $request->user_empid : '1'
	$activity_field = [];
	$activity_fieldNew =  ($organizationName !='' ? $request->txt_organization_name . ", " : '') . ($organizationAddress !='' ? $request->txt_organization_city . ", " : '') . ($organizationType !='' ? $orgTypeResul->org_type_code : '');

	$activity_fieldOld =  ($organizationName !='' ? $organizationResu->organization_name . ", " : '') . ($organizationAddress !='' ? $organizationResu->organization_address . ", " : '') . ($organizationType !='' ? $orgTypeRes->org_type_code : '');

	$activity_fields =  ($organizationName !='' ? 'Organization Name' . ", " : '') . ($organizationAddress !='' ? 'Address' . ", " : '') . ($organizationType !='' ? 'Organization Type' : '');
 
       DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `activity_logs`
                     (
                         `activity_title`,
                         `activity_module`,
                         `activity_field`,
                         `activity_old_value`,
                         `activity_new_value`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?)", [
 						'Updated an record in Organizations.' . $id,
 						'Organization Management',
						$activity_fields,
 						$activity_fieldOld,
 						$activity_fieldNew,
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


     public static function insertIECStocksUpdateLogs($request, $id) {
if($request->iec_restock == 'on'){
        $result = DB::select("
               SELECT  `I`.`id`,
                        `I`.`iec_refno`,
                        `I`.`iec_author`,
                        `I`.`iec_publisher`,
                        `I`.`iec_copyright_date`,
                        `I`.`iec_material_type`,
                        `I`.`iec_threshold`
            FROM `iecs` `I`
            WHERE `I`.`id` = ?", [$id]
                 );
foreach($result as $iecResu){

}
 //dd($userResu->name . " ". $request->user_name);
// dd($userResu->email . " " . $request->user_email);
if($iecResu->iec_threshold == $request->iec_restock_pieces){
	$iecRestockPieces = '';
} else {
	$iecRestockPieces = $request->iec_restock_pieces;
}

	$activity_field = [];
	$activity_fieldNew =  ($iecRestockPieces !='' ? $request->iec_restock_pieces  : '');

	$activity_fieldOld =  ($iecRestockPieces !='' ? $iecResu->iec_threshold : '');

	$activity_fields =  ($iecRestockPieces !='' ? 'Threshold' : '');
 
       DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `activity_logs`
                     (
                         `activity_title`,
                         `activity_module`,
                         `activity_field`,
                         `activity_old_value`,
                         `activity_new_value`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?)", [
 						'Restock an record in IECS.' . $id,
 						'Threshold',
						$activity_fields,
 						$activity_fieldOld,
 						$activity_fieldNew,
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
 } // endif
if($request->iec_adjust == 'on'){
	        $result = DB::select("
               SELECT  `I`.`id`,
                        `I`.`iec_refno`,
                        `I`.`iec_author`,
                        `I`.`iec_publisher`,
                        `I`.`iec_copyright_date`,
                        `I`.`iec_material_type`,
                        `I`.`iec_threshold`
            FROM `iecs` `I`
            WHERE `I`.`id` = ?", [$id]
                 );
foreach($result as $iecResu){

}
 //dd($userResu->name . " ". $request->user_name);
// dd($userResu->email . " " . $request->user_email);
if($iecResu->iec_threshold == $request->iec_adjust_pieces){
	$iecAdjustPieces = '';
} else {
	$iecAdjustPieces = $request->iec_adjust_pieces;
}

	$activity_field = [];
	$activity_fieldNew =  ($iecAdjustPieces !='' ? $request->iec_adjust_pieces  : '');

	$activity_fieldOld =  ($iecAdjustPieces !='' ? $iecResu->iec_threshold : '');

	$activity_fields =  ($iecAdjustPieces !='' ? 'Threshold' : '');
       DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `activity_logs`
                     (
                         `activity_title`,
                         `activity_module`,
                         `activity_field`,
                         `activity_old_value`,
                         `activity_new_value`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?)", [
 						'Adjust an record in IECS.' . $id,
 						'Threshold',
						$activity_fields,
 						$activity_fieldOld,
 						$activity_fieldNew,
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

     public static function insertMaterialRequestLogs($request) {
 // dd($request);
        $requestedMaterial = array(); 
        $requestedQuantity = array(); 
        $requestedMaterial = $request->txt_selectedMaterial;
        $requestedQuantity = $request->txt_selectedQuantity;

        DB::beginTransaction();

    for ($i=0; $i <=count($requestedMaterial); $i++) { 
  
            try {
                if($requestedMaterial[$i] != NULL) {
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
            WHERE  `I`.`id` = ($requestedMaterial[$i])",
			);

	foreach($req_resu as $iecResu) 
	{  }  

	$activity_fields =  'Material Request';
	$newThreshold =   $iecResu->iec_threshold - $requestedMaterial[$i];
                     $inserted = DB::insert("
                     INSERT INTO `activity_logs`
                     (
                         `activity_title`,
                         `activity_module`,
                         `activity_field`,
                         `activity_old_value`,
                         `activity_new_value`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
 						(?, ?, ?, ?, ?, ?, ?)", [
 						'Requests.',
 						'Material Request',
						'Request' . $iecResu->id,
 						$iecResu->iec_threshold,
 						$newThreshold,
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
	return $result; 
    }

     public static function insertUserROlesLogs($request) {
        $result = DB::select("
               SELECT  `U`.`user_role_id`,
                        `U`.`user_role`,
                        `U`.`user_description`,
                        `U`.`user_material_request`,
                        `U`.`user_inventory`,
                        `U`.`user_code_library`,
                        `U`.`user_management`,
                        `U`.`user_reports`,
                        `U`.`user_audit_log`
            FROM `user_roles` `U`
            WHERE `U`.`user_role_id` = ?", [$request->edit_userRoleID]
                 );
foreach($result as $userRoleResu){

}
 
 
 //    $request->input('edit_txt_user_role'),
 //    $request->input('edit_txt_user_role_desc'),
	// $request->input('txt_edit_user_material_request'),
	// $request->input('txt_edit_user_inventory'),
	// $request->input('txt_edit_user_code_library'),
	// $request->input('txt_edit_user_management'),
	// $request->input('txt_edit_user_reports'),
	// $request->input('txt_edit_user_audit_log'),


 //dd($userResu->name . " ". $request->user_name);
// dd($userResu->email . " " . $request->user_email);

if($userRoleResu->user_role == $request->edit_txt_user_role){
		$userRole = '';
} else {
	$userRole = $request->edit_txt_user_role;
}

if($userRoleResu->user_description == $request->edit_txt_user_role_desc){
	$userRoleDesc = '';
} else {
 	$userRoleDesc =$request->edit_txt_user_role_desc;
}

if($userRoleResu->user_material_request == $request->txt_edit_user_material_request){
	$userMaterialRequest = '';
} else {
	if($request->txt_edit_user_material_request ==1){
		$userMaterialRequest = 'On';
	}else {
		$userMaterialRequest = 'Off';
	}
}
if($userRoleResu->user_inventory == $request->txt_edit_user_inventory){
	$userInventory = '';
} else {
	if($request->txt_edit_user_inventory ==1){
		$userInventory = 'On';
	}else {
		$userInventory = 'Off';
	}
}

if($userRoleResu->user_code_library == $request->txt_edit_user_code_library){
	$userCodeLibrary = '';
} else {
	if($request->txt_edit_user_code_library ==1){
		$userCodeLibrary = 'On';
	}else {
		$userCodeLibrary = 'Off';
	}
}

if($userRoleResu->user_management == $request->txt_edit_user_management){
	$userManagement = '';
} else {
	if($request->txt_edit_user_management ==1){
		$userManagement = 'On';
	}else {
		$userManagement = 'Off';
	}
}

if($userRoleResu->user_reports == $request->txt_edit_user_reports){
	$userReports = '';
} else {
	if($request->txt_edit_user_reports ==1){
		$userReports = 'On';
	}else {
		$userReports = 'Off';
	}
}

if($userRoleResu->user_audit_log == $request->txt_edit_user_audit_log){
	$userAuditLog = '';
} else {
	if($request->txt_edit_user_audit_log ==1){
		$userAuditLog = 'On';
	}else {
		$userAuditLog = 'Off';
	}
}

if($userRoleResu->user_material_request == '1'){
	$OldUserMaterialRequest = 'On';
} else {
	$OldUserMaterialRequest = 'Off';
}

if($userRoleResu->user_inventory == '1'){
	$OldUserInventory = 'On';
} else {
	$OldUserInventory = 'Off';
}

if($userRoleResu->user_code_library == '1'){
	$OldUserCodeLibrary = 'On';
} else {
	$OldUserCodeLibrary = 'Off';
}

if($userRoleResu->user_management == '1'){
	$OldUserManagenent = 'On';
} else {
	$OldUserManagenent = 'Off';
}

if($userRoleResu->user_reports == '1'){
	$OldUserReports = 'On';
} else {
	$OldUserReports = 'Off';
}

if($userRoleResu->user_audit_log== '1'){
	$OldUserAuditLog = 'On';
} else {
	$OldUserAuditLog = 'Off';
}
 
	//$userName != 0 ? $request->user_empid : '1'
	$activity_field = [];
	$activity_fieldNew = ($userRole !='' ? $userRole . ", " : '') . ($userRoleDesc !='' ? $userRoleDesc . ", " : '') . ($userMaterialRequest !='' ? $userMaterialRequest . ", " : ''). ($userInventory !='' ? $userInventory . ", " : '') . ($userCodeLibrary !='' ? $userCodeLibrary . ", " : '') . ($userManagement !='' ? $userManagement . ", " : '') . ($userReports !='' ? $userReports . ", " : '') . ($userAuditLog !='' ? $userAuditLog . ", " : '');

	$activity_fieldOld =($userRole !='' ? $userRoleResu->user_role . ", " : '') .  ($userRoleDesc !='' ? $userRoleResu->user_description . ", " : '') .  ($userMaterialRequest !='' ? $OldUserMaterialRequest . ", " : '') .  ($userInventory !='' ? $OldUserInventory . ", " : '') .  ($userCodeLibrary !='' ? $OldUserCodeLibrary . ", " : '') .  ($userManagement !='' ? $OldUserManagenent . ", " : '') .  ($userReports !='' ? $OldUserReports . ", " : '') .  ($userAuditLog !='' ? $OldUserAuditLog . ", " : '');

	$activity_fields =  ($userRole !='' ? 'User Role' . ", " : '') . ($userRoleDesc !='' ? 'User Role Description' . ", " : '') . ($userMaterialRequest !='' ? 'User Material Request' . ", " : '') . ($userInventory !='' ? 'User Inventory' . ", " : '') . ($userCodeLibrary !='' ? 'User Code Library' . ", " : '') . ($userManagement !='' ? 'User Management' . ", " : '') . ($userReports !='' ? 'User Reports' . ", " : '') . ($userAuditLog !='' ? 'User Audit Log' . ", " : '');

       DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `activity_logs`
                     (
                         `activity_title`,
                         `activity_module`,
                         `activity_field`,
                         `activity_old_value`,
                         `activity_new_value`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?)", [
 						'Updated an record in User_roles.' .$request->edit_userRoleID,
 						'User Role Management',
						$activity_fields,
 						$activity_fieldOld,
 						$activity_fieldNew,
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
        SELECT `A`.`activity_id_no`, 
        `A`.`activity_title`,
        `A`.`activity_module`,
        `A`.`activity_field`,
        `A`.`activity_old_value`,
        `A`.`activity_new_value`,
        `A`.`authorID`,
        `A`.`created_at`,
        `U`.`id`,
        `U`.`name`
        FROM `activity_logs` `A`
        JOIN `users` `U`
        ON `A`.`authorID` = `U`.`id`
        ORDER BY `created_at` ASC
        ");
        return $result;
    }
 
     public static function insertMaterialLogs($request) {
        $requestedMaterial = array(); 
        $requestedQuantity = array(); 
        $requestedMaterial = $request->txt_selectedMaterial;
        $requestedQuantity = $request->txt_selectedQuantity;
        DB::beginTransaction();
    for ($i=0; $i <=count($requestedMaterial); $i++) { 
  
            try {
                if($requestedMaterial[$i] != NULL) {
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
            WHERE  `I`.`id` IN ($requestedMaterial[$i])",
            );

    foreach($req_resu as $iecResu) 
    {  }  
    $newThreshold =   $iecResu->iec_threshold - $requestedQuantity[$i];
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
                        $iecResu->id,    
                        $iecResu->iec_threshold,
                        4,
                        $requestedQuantity[$i],
                        $newThreshold,                        
                        $request->txt_request_purpose,
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
    return $result; 
    }
}
<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use App\User;
class auditLog extends Model
{
    public static function selectAllList() {
    $result = DB::select("
    	SELECT `A`.`audit_id`,
      	`A`.`audit_module`,
        `A`.`audit_activity`,
        `A`.`audit_activity_title`,
        `A`.`audit_field_affected`,
        `A`.`audit_old_value`,
        `A`.`audit_new_value`,
        `A`.`audit_rec_table_id`,
        `A`.`authorID`,
        `A`.`created_at`,
        `U`.`id`,
        `U`.`name`
        FROM `audit_logs` `A`
        JOIN `users` `U`
        ON `U`.`id` = `A`.`authorID`
        ORDER BY year(`A`.`created_at`)
        AND  month(`A`.`created_at`) 
        AND  DAY(`A`.`created_at`) DESC"
    );
    return $result;
    }
      public static function IECRestockUpdate($request, $id) {
        DB::beginTransaction();
        
        $iecs = DB::select("
        SELECT `id`, 
        `iec_refno`,
        `iec_title`,
        `iec_author`,
        `iec_publisher`,
        `iec_copyright_date`,
        `iec_page`,
        `iec_specifications`,
        `iec_material_type`,
        `iec_image`,
        `iec_threshold`
        FROM `iecs`
		WHERE `id` = ?",
		[
		  $id
        ]
        );
		foreach($iecs as $iec){}
        $restockPieces = $iec->iec_threshold + $request->iec_restock_pieces;
		if($request->iec_restock_pieces !='' && $request->iec_adjust_pieces ==''){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        'IEC Material',
                        'Updated an item in table [IECS] . ['. $id . ']',
                        'Updated a record [' . $iec->iec_title. ']',
 						'Threshold',
 						$iec->iec_threshold,
 						$restockPieces,
 						'IECS' . "_". $id,
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
		if($request->iec_adjust_pieces !='' && $request->iec_restock_pieces ==''){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        'IEC Material',
 						'Updated an item in table [IECS] . ['. $id . ']',
                        'Updated a record ['. $iec->iec_title . ']',
 						'Threshold',
 						$iec->iec_threshold,
 						$request->iec_adjust_pieces,
 						'IECS' . "_". $id,
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
}

      public static function addNewIECRecord($request) {
        $iecs = DB::select("
        SELECT `id`, 
        `iec_refno`,
        `iec_title`,
        `iec_author`,
        `iec_publisher`,
        `iec_copyright_date`,
        `iec_page`,
        `iec_specifications`,
        `iec_material_type`,
        `iec_image`,
        `iec_threshold`
        FROM `iecs`
        WHERE `iec_title`  = ?
        AND `iec_author`   = ?",
        [
          $request->txt_iec_title,
          $request->txt_iec_author
        ]
        );
        foreach($iecs as $iec){}
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        'IEC Material',
                        'Added an item in table [IECS] . ['. $iec->id . ']',
                        'Added a record ['. $iec->iec_title . ']',
                        'Details',
                        '',
                        $iec->iec_title,
                        'IECS' . "_". $iec->id,
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

      public static function searchIECRecord($request, $id, $image_file) {
        $iecs = DB::select("
        SELECT `id`, 
        `iec_refno`,
        `iec_title`,
        `iec_author`,
        `iec_publisher`,
        `iec_copyright_date`,
        `iec_page`,
        `iec_specifications`,
        `iec_material_type`,
        `iec_image`,
        `iec_threshold`,
        `iec_threshold_limit`
        FROM `iecs`
		WHERE `id` = ?",
		[
		  $id
        ]
        );

		foreach($iecs as $iec){}
 
if($request->txt_iec_title != $iec->iec_title){
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        'IEC Material',
 						'Updated an item in table [IECS] . ['. $id . ']',
                        'Updated a record [' . $iec->iec_title. ']',
 						'Title',
 						$iec->iec_title,
 						$request->txt_iec_title,
 						'IECS' . "_". $id,
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

if($request->txt_iec_author != $iec->iec_author){
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        'IEC Material',
 						'Updated an item in table [IECS] . ['. $id . ']',
                        'Updated a record [' . $iec->iec_title. ']',
 						'Author',
 						$iec->iec_author,
 						$request->txt_iec_author,
 						'IECS' . "_". $id,
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

if($request->txt_iec_publisher != $iec->iec_publisher){
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        'IEC Material',
 						'Updated an item in table [IECS] . ['. $id . ']',
                        'Updated a record [' . $iec->iec_title. ']',
                        'Publisher',
 						$iec->iec_publisher,
 						$request->txt_iec_publisher,
 						'IECS' . "_". $id,
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

if($request->txt_iec_copyright_date != $iec->iec_copyright_date){
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        'IEC Material',
 						'Updated an item in table [IECS] . ['. $id . ']',
                        'Updated a record [' . $iec->iec_title. ']',
 						'Copyright Date',
 						$iec->iec_copyright_date,
 						$request->txt_iec_copyright_date,
 						'IECS' . "_". $id,
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


if($request->txt_iec_page != $iec->iec_page){
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        'IEC Material',
 						'Updated an item in table [IECS] . ['. $id . ']',
                        'Updated a record [' . $iec->iec_title. ']',
 						'No. of Pages',
 						$iec->iec_page,
 						$request->txt_iec_page,
 						'IECS' . "_". $id,
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


if($request->txt_iec_specifications != $iec->iec_specifications){
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        'IEC Material',
 						'Updated an item in table [IECS] . ['. $id . ']',
                        'Updated a record [' . $iec->iec_title. ']',
 						'Specifications',
 						$iec->iec_specifications,
 						$request->txt_iec_specifications,
 						'IECS' . "_". $id,
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

if($request->txt_iec_type_of_materials != $iec->iec_material_type){
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        'IEC Material',
 						'Updated an item in table [IECS] . ['. $id . ']',
                        'Updated a record [' . $iec->iec_title. ']',
 						'Material Type',
 						$iec->iec_material_type,
 						$request->txt_iec_type_of_materials,
 						'IECS' . "_". $id,
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


if($request->txt_iec_image){
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        'IEC Material',
 						'Updated an item in table [IECS] . ['. $id . ']',
                        'Updated a record [' . $iec->iec_title. ']',
 						'Image',
 						$request->txt_iec_image2,
 						$image_file,
                        'IECS' . "_". $id,
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

if($request->txt_iec_threshold != $iec->iec_threshold){
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        'IEC Material',
 						'Updated an item in table [IECS] . ['. $id . ']',
                        'Updated a record [' . $iec->iec_title. ']',
 						'Threshold',
 						$iec->iec_threshold_limit,
 						$request->txt_iec_threshold,
 						'IECS' . "_". $id,
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

	$result = true;
    return $result;
  }

    public static function IECDeleteRec($id) {
        $iecs = DB::select("
        SELECT `id`, 
        `iec_refno`,
        `iec_title`,
        `iec_author`,
        `iec_publisher`,
        `iec_copyright_date`,
        `iec_page`,
        `iec_specifications`,
        `iec_material_type`,
        `iec_image`,
        `iec_threshold`
        FROM `iecs`
        WHERE `id` = ?",
        [
          $id
        ]
        );

        foreach($iecs as $iec){}

        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        'IEC Material',
                        'Deleted an item in table [IECS] . [' . $id . ']',
                        'Deleted a record [' . $iec->iec_title . ']',
 						'Status',
 						'N/A',
 						'N/A',
 						'IECS' . "_". $id,
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



    public static function searchIECRequestRecord($request) {
 
 		$iid = array();
 		$iiid = array();
 		$iid = implode(",", $request->txt_selectedMaterial);
 		$iiid = $iid;

 		$selectedRemaining = array();
 		$iecRemaining = array();
 		$iecRemaining = implode(",", $request->txt_totalRemaining);
 		$selectedRemaining = $iecRemaining;
		 
 

        $iecs = DB::select("
        SELECT `id`, 
        `iec_refno`,
        `iec_title`,
        `iec_author`,
        `iec_publisher`,
        `iec_copyright_date`,
        `iec_page`,
        `iec_specifications`,
        `iec_material_type`,
        `iec_image`,
        `iec_threshold`
        FROM `iecs`
		WHERE `id` IN ($iiid)
		AND `is_deleted` = 0" 
       );
 
  		for ($i=0; $i <=count($request->txt_selectedMaterial); $i++) { 
 
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
 						 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        'Material Request',
 						'Updated an item in table [IECS] . [' . $request->txt_selectedMaterial[$i] . ']',
                        'Requested a record [' . $request->txt_selectedMaterialName[$i] . ']',
 						'Threshold',
 						 $request->txt_totalRemaining[$i] + $request->txt_selectedQuantity[$i],
 						$request->txt_totalRemaining[$i],
                        'IECS' . "_". $request->txt_selectedMaterial[$i],
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
    	} //endfor
 
 

  }


    public static function StoreNewClientOnTheFlyLogs($client) {
    	$clientrec = array();
    	$clientrec = explode("_", $client);
        DB::beginTransaction();
        
        $clients = DB::select("
        SELECT `id`, 
        `client_name`,
        `client_organization`,
        `client_designation`,
        `client_contact_no`,
        `is_deleted`

        FROM `clients`
		WHERE `client_name`       = ?
		AND `client_organization` = ?
		AND `client_designation`  = ?
		AND `is_deleted`          = ?
		",
		[
		  $clientrec[0],
		  $clientrec[1],
		  $clientrec[2],
		  0
        ]
        );

		foreach($clients as $iclients){}

        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Requestor's Detail",
                        'Added an item in table [Clients] . [' . $iclients->id . ']',
                        'Added a record [' . $iclients->client_name . ']',
 						'Details',
 						'',
 						$clientrec[0],
 						'Clients' . "_". $iclients->id,
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

    public static function StoreNewMaterialLogs($request) {
        DB::beginTransaction();
        
        $materials = DB::select("
        SELECT `id`, 
        `material_name`,
        `material_desc`,
        `material_stock`,
        `is_deleted`
        FROM `materials`
		WHERE `material_name`      = ?
		AND `material_desc`        = ?
		AND `is_deleted`           = ?
		",
		[
		  $request->txt_material_name,
		  $request->txt_material_desc,
		  0
        ]
        );

		foreach($materials as $material){}
		if($request->txt_material_name) {
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Asset Type",
                        'Added an item in table [Materials] . [' . $material->id .']',
                        'Added a record [' . $material->material_name .']',
 						'Details',
 						'',
 						$request->txt_material_name,
 						'Materials' . "_". $material->id,
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
 	} // endif

}

    public static function updateMaterialLogs($request, $id) {
        DB::beginTransaction();
        
        $materials = DB::select("
        SELECT `id`, 
        `material_name`,
        `material_desc`,
        `material_stock`,
        `is_deleted`
        FROM `materials`
		WHERE `id`             = ?
		AND `is_deleted`       = ?
		",
		[
		  $id,
		  0
        ]
        );

		foreach($materials as $material){}
		if($request->txt_material_name != $material->material_name){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Asset Type",
                        'Updated an item in table [Materials] . ['. $id . ']',
                        'Updated a record ['. $material->material_name . ']',
 						'Material Name',
 						$material->material_name,
 						$request->txt_material_name,
 						'Materials' . "_". $id,
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
  } //endif

		if($request->txt_material_desc != $material->material_desc){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Asset Type",
                        'Updated an item in table [Materials] . ['. $id . ']',
                        'Updated a record ['. $material->material_name . ']',
 						'Material Description',
 						$material->material_desc,
 						$request->txt_material_desc,
 						'Materials' . "_". $id,
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
  } //endif
 }


     public static function deleteMaterialLogs($id) {
        $materials = DB::select("
        SELECT `id`, 
        `material_name`,
        `material_desc`,
        `material_stock`,
        `is_deleted`
        FROM `materials`
        WHERE `id`  = ?",
        [
          $id
        ]
        );

        foreach($materials as $material){}

        DB::beginTransaction();

        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Asset Type",
                        'Deleted an item in table Materials. ['. $id . ']',
                        'Deleted a record ['. $material->material_name . ']',
 						'Status',
 						'N/A',
 						'N/A',
 						'Materials' . "_". $id,
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



    public static function StoreNewOrganizationOnTheFlyLogs($id) {
    	$org = array();
    	$org = explode("_", $id);
        DB::beginTransaction();
        
        $orgs = DB::select("
        SELECT `id`, 
        `organization_name`,
        `organization_address`,
        `organization_type`,
        `is_deleted`

        FROM `organizations`
		WHERE `organization_name`      = ?
		AND `organization_type`        = ?
		AND `organization_address`     = ?
		AND `is_deleted`  = ?
		",
		[
		  $org[0],
		  $org[1],
		  $org[2],
		  0
        ]
        );
 
		foreach($orgs as $iorg){}

        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Organizations Detail",
                        'Added an item in table [Organizations] . [' . $iorg->id . ']',
                        'Added a record [' . $iorg->organization_name . ']',
 						'Details',
 						'',
 						$org[0],
 						'Organizations' . "_". $iorg->id,
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



    public static function StoreNewOrganization($request) {
        DB::beginTransaction();
        
        $orgs = DB::select("
        SELECT `id`, 
        `organization_name`,
        `organization_address`,
        `organization_type`,
        `is_deleted`

        FROM `organizations`
		WHERE `organization_name`      = ?
		AND `organization_type`        = ?
		AND `organization_address`     = ?
		AND `is_deleted`               = ?
		",
		[
		  $request->txt_organization_name,
		  $request->txt_organization_type,
		  $request->txt_organization_city,
		  0
        ]
        );
 
		foreach($orgs as $iorg){}

        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Organizations Detail",
                        'Added an item in table [Organizations] . [' . $iorg->id . ']',
                        'Added an record [' . $iorg->organization_name . ']',
 						'Details',
 						'',
 						$request->txt_organization_name,
 						'Organizations' . "_". $iorg->id,
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


    public static function UpdateOrganization($request, $id) {
        DB::beginTransaction();
        
        $orgs = DB::select("
        SELECT `id`, 
        `organization_name`,
        `organization_address`,
        `organization_type`,
        `is_deleted`

        FROM `organizations`
		WHERE `id`        = ?
		AND `is_deleted`  = ?
		",
		[
		  $id,
		  0
        ]
        );

		foreach($orgs as $iorg){}
		if($request->txt_organization_name != $iorg->organization_name){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Organizations Detail",
                        'Updated an item in table [Organizations] . ['. $id . ']',
                        'Updated a record ['. $iorg->organization_name . ']',
 						"Organization's Name",
 						$iorg->organization_name,
 						$request->txt_organization_name,
 						'Organizations' . "_". $id,
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
  } //endif

		if($request->txt_organization_type != $iorg->organization_type){
        try {

        $orgsold = DB::select("
        SELECT `org_type_id`, 
               `org_type_code`
        FROM `organization_type`
		WHERE `org_type_id`  = ?
		",
		[
		  $iorg->organization_type,
        ]

        );

        foreach($orgsold as $org_old) {}

        $orgsnew = DB::select("
        SELECT `org_type_id`, 
               `org_type_code`
        FROM `organization_type`
		WHERE `org_type_id`  = ?
		",
		[
		  $request->txt_organization_type,
        ]

        );

        foreach($orgsnew as $org_new) {}
 
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Organization's Detail",
                        'Updated an item in table [Organizations] . ['. $id . ']',
                        'Updated a record ['. $iorg->organization_name . ']',
 						"Organization Type",
 						$org_old->org_type_code,
 						$org_new->org_type_code,
						'Organizations' . "_". $id,
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
  } //endif
		if($request->txt_organization_city != $iorg->organization_address){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Organization's Detail",
                        'Updated an item in table [Organizations] . ['. $id . ']',
                        'Updated a record ['. $iorg->organization_name . ']',
 						"Organization's Address",
 						$iorg->organization_address,
 						$request->txt_organization_city,
 						'Organizations' . "_". $id,
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
  } //endif
 }

     public static function deleteOrganizationlLogs($id) {
        $orgs = DB::select("
        SELECT `id`, 
        `organization_name`,
        `organization_address`,
        `organization_type`,
        `is_deleted`

        FROM `organizations`
        WHERE `id`        = ?",
        [
          $id,
        ]
        );

        foreach($orgs as $iorg){}
        DB::beginTransaction();

        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`, 
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Organization's Detail",
                        'Deleted an item in table [Organizations] . ['. $id . ']',
                        'Deleted a record ['. $iorg->organization_name . ']',
 						'Status',
 						'N/A',
 						'N/A',
 						'Organizations' . "_". $id,
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

    public static function StoreNewContractorOnTheFlyLogs($id) {
    	$iid = array();
    	$iid = explode("_", $id);
    	$iiid = $iid;
        DB::beginTransaction();
        
        $contractors = DB::select("
        SELECT `contractor_id`, 
        `contractor_name`,
        `contractor_contact_person`,
        `contractor_contact_no`,
        `is_deleted`
        FROM `contractors`
		WHERE `contractor_name`          = ?
		AND `contractor_contact_person`  = ?
		AND `contractor_contact_no`      = ?
		AND `is_deleted`                 = ?
		",
		[
		  $iiid[0],
		  $iiid[1],
          $iiid[2],
		 0
        ]
        );
		foreach($contractors as $contractor){}
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Contractors",
                        'Added an item in table [Contractors] . [' . $contractor->contractor_id .']',
                        'Added a [' . $contractor->contractor_name .']',
 						'Details',
 						'',
 						$contractor->contractor_name,
 						'Contractors' . "_". $contractor->contractor_id,
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
    public static function StoreNewContractorslLogs($request) {
        DB::beginTransaction();
        
        $contractors = DB::select("
        SELECT `contractor_id`, 
        `contractor_name`,
        `contractor_contact_person`,
        `contractor_contact_no`,
        `is_deleted`
        FROM `contractors`
		WHERE `contractor_name`          = ?
		AND `contractor_contact_person`  = ?
		AND `contractor_contact_no`      = ?
		AND `is_deleted`                 = ?
		",
		[
		  $request->txt_contractors_name,
		  $request->txt_contractors_contact_person,
          $request->txt_contractors_contact_no,
		  0
        ]
        );
		foreach($contractors as $contractor){}
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Contractors",
                        'Added an item in table [Contractors] . [' . $contractor->contractor_id .']',
                        'Added a record [' . $contractor->contractor_name .']',
 						'Details',
 						'',
 						$contractor->contractor_name,
 						'Contractors' . "_". $contractor->contractor_id,
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

    public static function UpdateContractor($request, $id) {
        DB::beginTransaction();
        
        $conts = DB::select("
        SELECT `contractor_id`, 
        `contractor_name`,
        `contractor_contact_person`,
        `contractor_contact_no`,
        `is_deleted`

        FROM `contractors`
		WHERE `contractor_id`    = ?
		AND `is_deleted`         = ?
		",
		[
		  $id,
		  0
        ]
        );
		foreach($conts as $cont){}
		if($request->txt_contractors_name != $cont->contractor_name){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Contractors Detail",
                        'Updated an item in table [Contractors] . ['. $id . ']',
                        'Updated a record ['. $cont->contractor_name . ']',
 						"Contractors Name",
 						$cont->contractor_name,
 						$request->txt_contractors_name,
 						'Contractors' . "_". $id,
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
  } //endif

		if($request->txt_contractors_contact_person != $cont->contractor_contact_person){
        try {

         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Contractors Detail",
                        'Updated an item in table [Contractors] . ['. $id . ']',
                        'Updated a record ['. $cont->contractor_name . ']',
 						"Contact Person",
 						$cont->contractor_contact_person,
 						$request->txt_contractors_contact_person,
 						'Contractors' . "_". $id,
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
  } //endif
		if($request->txt_contractors_contact_no != $cont->contractor_contact_no){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Contractors Detail",
                        'Updated an item in table [Contractors] . ['. $id . ']',
                        'Updated a record ['. $cont->contractor_name . ']',
 						"Contact No.",
 						$cont->contractor_contact_no,
 						$request->txt_contractors_contact_no,
 						'Contractors' . "_". $id,
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
  } //endif
 }


     public static function deleteContractorLogs($id) {
        $conts = DB::select("
        SELECT `contractor_id`, 
        `contractor_name`,
        `contractor_contact_person`,
        `contractor_contact_no`,
        `is_deleted`

        FROM `contractors`
        WHERE `contractor_id`    = ?",
        [
          $id
        ]
        );
        foreach($conts as $cont){}
        DB::beginTransaction();

        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Contractors Detail",
                        'Deleted an item in table [Contractors] . ['. $id . ']',
                        'Deleted a record ['. $cont->contractor_name . ']',
 						'Status',
 						'N/A',
 						'N/A',
 						'Contractors' . "_". $id,
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


    public static function storeNewUserLogs($request) {
        DB::beginTransaction();
        
        $users = DB::select("
           SELECT  `id`,
            `name`,
            `username`,
            `email`,
		    `organization`,
            `uid`
            FROM `users`
		WHERE `name`          = ?
		AND `username`        = ?
		AND `email`           = ?",
		[
		  $request->user_name,
		  $request->user_empid,
          $request->user_email
        ]
        );
		foreach($users as $user){}
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`, 
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Users",
                        'Added an item in table [Users] . [' . $user->id .']',
                        'Added a record [' . $user->name .']',
 						'Details',
 						'',
 						$request->user_name,
 						'Users' . "_". $user->id,
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

    public static function updateUserLogs($request, $id) {
        DB::beginTransaction();
        $users = DB::select("
           SELECT  `id`,
            `name`,
            `user_position`,
            `username`,
            `email`,
		    `organization`,
            `user_signatory`,
            `is_deleted`,
            `uid`
        FROM `users`
		WHERE `id`    = ?
		",
		[
		  $id,
        ]
        );
		foreach($users as $user){}
        if($request->user_position != $user->user_position){
         try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Users Detail",
                        'Updated an item in table [Users] . ['. $id . ']',
                        'Updated a record ['. $user->name . ']',
                        "Position",
                        $user->user_position,
                        $request->user_position,
                        'Users' . "_". $id,
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
  } //endif
        if($request->user_signatory != $user->user_signatory){
            if($request->Signatory ==0){
                $reportSignatory = 'Disable';
            }
            if($request->Signatory ==1){
                $reportSignatory = 'Enable';
            }

            if($user->user_signatory ==0){
                $previousReportSignatory = 'Disable';
            }
            if($user->user_signatory ==1){
                $previousReportSignatory = 'Enable';
            }
         try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Users Detail",
                        'Updated an item in table [Users] . ['. $id . ']',
                        'Updated a record ['. $user->name . ']',
                        "Report Signatory",
                        $previousReportSignatory,
                        $reportSignatory,
                        'Users' . "_". $id,
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
  } //endif
        if($request->user_status != $user->is_deleted){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Users Detail",
                        'Deleted an item in table [Users] . ['. $id . ']',
                        'Deleted a record ['. $user->name . ']',
                        "User Status",
                        'N/A',
                        'N/A',
                        'Users' . "_". $id,
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
  } //endif
		if($request->user_empid != $user->username){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Users Detail",
                        'Updated an item in table [Users] . ['. $id . ']',
                        'Updated a record ['. $user->name . ']',
 						"Employee ID",
 						$user->username,
 						$request->user_empid,
 						'Users' . "_". $id,
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
  } //endif

		if($request->user_name != $user->name){
        try {

         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Users Detail",
                        'Updated an item in table [Users] . ['. $id . ']',
                        'Updated a record ['. $user->name . ']',
 						"Name",
 						$user->name,
 						$request->user_name,
 						'Users' . "_". $id,
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
  } //endif

		if($request->user_email != $user->email){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Users Detail",
                        'Updated an item in table [Users] . ['. $id . ']',
                        'Updated a record ['. $user->name . ']',
 						"Email Address",
 						$user->email,
 						$request->user_email,
 						'Users' . "_". $id,
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
  } //endif

	if($request->user_type != $user->uid){
        $utypesold = DB::select("
        SELECT `user_role_id`, 
               `user_role`
        FROM `user_roles`
		WHERE `user_role_id`      = ?
		",
		[
		  $user->uid,
        ]

        );

        $utypesnew = DB::select("
        SELECT `user_role_id`, 
               `user_role`
        FROM `user_roles`
		WHERE `user_role_id`      = ?
		",
		[
		  $request->user_type,
        ]

        );
        foreach($utypesold as $utype_old) {}
        foreach($utypesnew as $utype_new) {}
        try {


         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Users Detail",
                        'Updated an item in table [Users] . ['. $id . ']',
                        'Updated a record ['. $user->name . ']',
 						"User Type",
 						$utype_old->user_role,
 						$utype_new->user_role,
 						'Users' . "_". $id,
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
  } //endif

  		if($request->user_newpassword !=''){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Users Detail",
                        'Updated an item in table [Users] . ['. $id . ']',
                        'Updated a record ['. $user->name . ']',
 						"Password",
 						'N/A',
 						'N/A',
 						'Users' . "_". $id,
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
  } //endif
}
 
    public static function storeNewUserRoleLogs($request) {
        DB::beginTransaction();
        
        $userRoles = DB::select("
           SELECT  `user_role_id`,
            `user_role`,
            `user_description`,
            `user_material_request`,
            `user_inventory`,
            `user_code_library`,
            `user_management`,
            `user_reports`,
            `user_audit_log`,
            `user_email_notif_iec_material`,
			`is_deleted`            
		FROM `user_roles`
		WHERE `user_role`      = ?
		AND `user_description` = ?
		AND `is_deleted`       = ?",
		[
		  $request->add_txt_user_role,
		  $request->add_txt_user_role_desc,
          0
        ]
        );
		foreach($userRoles as $userRole){}
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`, 
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "User Roles",
                        'Added an item in table [User_roles] . [' . $userRole->user_role_id .']',
                        'Added a record [' . $userRole->user_role .']',
 						'Details',
 						'',
 						$request->add_txt_user_role,
 						'User_roles' . "_". $userRole->user_role_id,
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


    public static function updateUserRoleLogs($request) {
        DB::beginTransaction();
        $userRoles = DB::select("
           SELECT  `user_role_id`,
            `user_role`,
            `user_description`,
            `user_material_request`,
            `user_inventory`,
            `user_code_library`,
            `user_management`,
            `user_reports`,
            `user_audit_log`,
            `user_email_notif_iec_material`,
			`is_deleted`            
		FROM `user_roles`
		WHERE `user_role_id`      = ?
		AND   `is_deleted`        = ?",
		[
		  $request->edit_userRoleID,
          0
        ]
        );



		foreach($userRoles as $userRole){}
		if($request->edit_txt_user_role != $userRole->user_role){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "User Roles Detail",
                        'Updated an item in table [Users_roles] . ['. $userRole->user_role_id . ']',
                        'Updated a record ['. $userRole->user_role . ']',
 						"User Role",
 						$userRole->user_role,
 						$request->edit_txt_user_role,
 						'User_roles' . "_".  $userRole->user_role_id,
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
  } //endif


		if($request->edit_txt_user_role_desc != $userRole->user_description){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "User Roles Detail",
                        'Updated an item in table [Users_roles] . ['. $userRole->user_role_id . ']',
                        'Updated a record ['. $userRole->user_role . ']',
 						"User Description",
 						$userRole->user_description,
 						$request->edit_txt_user_role_desc,
 						'User_roles' . "_".  $userRole->user_role_id,
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
  } //endif
 

 		if($request->txt_edit_user_material_request != $userRole->user_material_request){
			$val1_user_material_request = '';
			$val2_user_material_request = '';
			

			if($userRole->user_material_request == 1){
				$val1_user_material_request = 'ON';
			} else {
				$val1_user_material_request = 'OFF';
			}

			if($request->txt_edit_user_material_request == 1){
				$val2_user_material_request = 'ON';
			} else {
				$val2_user_material_request = 'OFF';
			}

        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "User Roles Detail",
                        'Updated an item in table [Users_roles] . ['. $userRole->user_role_id . ']',
                        'Updated a record ['. $userRole->user_role . ']',
 						"Material Request Role",
 						$val1_user_material_request,
 						$val2_user_material_request,
 						'User_roles' . "_".  $userRole->user_role_id,
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
  } //endif


 		if($request->txt_edit_user_inventory != $userRole->user_inventory){
			$val1_user_inventory = '';
			$val2_user_inventory = '';
			

			if($userRole->user_inventory == 1){
				$val1_user_inventory = 'ON';
			} else {
				$val1_user_inventory = 'OFF';
			}

			if($request->txt_edit_user_inventory == 1){
				$val2_user_inventory = 'ON';
			} else {
				$val2_user_inventory = 'OFF';
			}

        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "User Roles Detail",
                        'Updated an item in table [Users_roles] . ['. $userRole->user_role_id . ']',
                        'Updated a record ['. $userRole->user_role . ']',
 						"Inventory Role",
 						$val1_user_inventory,
 						$val2_user_inventory,
 						'User_roles' . "_".  $userRole->user_role_id,
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
  } //endif

	if($request->txt_edit_user_code_library != $userRole->user_code_library){
			$val1_user_code_library = '';
			$val2_user_code_library = '';
			

			if($userRole->user_code_library == 1){
				$val1_user_code_library = 'ON';
			} else {
				$val1_user_code_library = 'OFF';
			}

			if($request->txt_edit_user_code_library == 1){
				$val2_user_code_library = 'ON';
			} else {
				$val2_user_code_library = 'OFF';
			}

        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "User Roles Detail",
                        'Updated an item in table [Users_roles] . ['. $userRole->user_role_id . ']',
                        'Updated a record ['. $userRole->user_role . ']',
 						"Code Library Role",
 						$val1_user_code_library,
 						$val2_user_code_library,
 						'User_roles' . "_".  $userRole->user_role_id,
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
  } //endif

	if($request->txt_edit_user_management != $userRole->user_management){
			$val1_user_management = '';
			$val2_user_management = '';
			

			if($userRole->user_management == 1){
				$val1_user_management = 'ON';
			} else {
				$val1_user_management = 'OFF';
			}

			if($request->txt_edit_user_management == 1){
				$val2_user_management = 'ON';
			} else {
				$val2_user_management = 'OFF';
			}

        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "User Roles Detail",
                        'Updated an item in table [Users_roles] . ['. $userRole->user_role_id . ']',
                        'Updated a record ['. $userRole->user_role . ']',
 						"User Management Role",
 						$val1_user_management,
 						$val2_user_management,
 						'User_roles' . "_".  $userRole->user_role_id,
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
  } //endif

	if($request->txt_edit_user_reports != $userRole->user_reports){
			$val1_user_reports = '';
			$val2_user_reports = '';
			

			if($userRole->user_reports == 1){
				$val1_user_reports = 'ON';
			} else {
				$val1_user_reports = 'OFF';
			}

			if($request->txt_edit_user_reports == 1){
				$val2_user_reports = 'ON';
			} else {
				$val2_user_reports = 'OFF';
			}

        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "User Roles Detail",
                        'Updated an item in table [Users_roles] . ['. $userRole->user_role_id . ']',
                        'Updated a record ['. $userRole->user_role . ']',
 						"User Reports Role",
 						$val1_user_reports,
 						$val2_user_reports,
 						'User_roles' . "_".  $userRole->user_role_id,
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
  } //endif

	if($request->txt_edit_user_audit_log != $userRole->user_audit_log){
			$val1_user_audit_log = '';
			$val2_user_audit_log = '';
			

			if($userRole->user_audit_log == 1){
				$val1_user_audit_log = 'ON';
			} else {
				$val1_user_audit_log = 'OFF';
			}

			if($request->txt_edit_user_audit_log == 1){
				$val2_user_audit_log = 'ON';
			} else {
				$val2_user_audit_log = 'OFF';
			}

        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "User Roles Detail",
                        'Updated an item in table [Users_roles] . ['. $userRole->user_role_id . ']',
                        'Updated a record ['. $userRole->user_role . ']',
 						"Audit Log Role",
 						$val1_user_audit_log,
 						$val2_user_audit_log,
 						'User_roles' . "_".  $userRole->user_role_id,
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
  } //endif

 		if($request->txt_edit_user_email_notif_iec_material != $userRole->user_email_notif_iec_material){
			$val1_user_email_notif_iec_material = '';
			$val2_user_email_notif_iec_material = '';
			

			if($userRole->user_email_notif_iec_material == 1){
				$val1_user_email_notif_iec_material = 'ON';
			} else {
				$val1_user_email_notif_iec_material = 'OFF';
			}

			if($request->txt_edit_user_email_notif_iec_material == 1){
				$val2_user_email_notif_iec_material = 'ON';
			} else {
				$val2_user_email_notif_iec_material = 'OFF';
			}

        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
        				 `audit_field_affected`,
        				 `audit_old_value`,
        				 `audit_new_value`,
        				 `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "User Roles Detail",
                        'Updated an item in table [Users_roles] . ['. $userRole->user_role_id . ']',
                        'Updated a record ['. $userRole->user_role . ']',
 						"Email Notification Role",
 						$val1_user_email_notif_iec_material,
 						$val2_user_email_notif_iec_material,
 						'User_roles' . "_".  $userRole->user_role_id,
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
  } //endif

	}

    public static function deleteUserRoleLogs($id) {
        $userRoles = DB::select("
           SELECT  `user_role_id`,
            `user_role`,
            `user_description`,
            `user_material_request`,
            `user_inventory`,
            `user_code_library`,
            `user_management`,
            `user_reports`,
            `user_audit_log`,
            `user_email_notif_iec_material`,
            `is_deleted`            
        FROM `user_roles`
        WHERE `user_role_id`      = ?",
        [
          $id,
        ]
        );


        foreach($userRoles as $userRole){}

        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        'User Roles Detail',
                        'Deleted an item in table [User_roles] . [' . $id . ']',
                        'Deleted a record [' . $userRole->user_role . ']',
                        'Status',
                        'N/A',
                        'N/A',
                        'User_roles' . "_". $id,
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

    public static function deleteClientLogs($id) {
        $clients = DB::select("
        SELECT `id`, 
        `client_name`,
        `client_organization`,
        `client_designation`,
        `client_contact_no`,
        `is_deleted`
        FROM `clients`
        WHERE `id`           = ?",
        [
          $id
        ]
        );
        foreach($clients as $client){}
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        'Clients',
                        'Deleted an item in table [Clients] . [' . $id . ']',
                        'Deleted a record [' . $client->client_name . ']',
                        'Status',
                        'N/A',
                        'N/A',
                        'Clients' . "_". $id,
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

    public static function updateClientLogs($request, $id) {
        DB::beginTransaction();
        $clients = DB::select("
        SELECT `id`, 
        `client_name`,
        `client_organization`,
        `client_designation`,
        `client_contact_no`,
        `is_deleted`

        FROM `clients`
        WHERE `id`           = ?
        AND `is_deleted`     = ?",
        [
          $id,
          0
        ]
        );

        foreach($clients as $client){}
        if($request->txt_clients_name != $client->client_name){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Clients",
                        'Updated an item in table [Clients] . ['. $id . ']',
                        'Updated a record ['. $client->client_name . ']',
                        "Client's Name",
                        $client->client_name,
                        $request->txt_clients_name,
                        'Clients' . "_". $id,
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
  } //endif
        if($request->txt_clients_organization != $client->client_organization){
        $old_client_names = DB::select("
        SELECT `id`, 
        `organization_name`,
        `is_deleted`
        FROM `organizations`
        WHERE `id`                    = ?",
        [
          $client->client_organization,
        ]
        );
 
        $new_client_names = DB::select("
        SELECT `id`, 
        `organization_name`,
        `is_deleted`
        FROM `organizations`
        WHERE `id`                    = ? ",
        [
          $request->txt_clients_organization,
        ]
        );
        foreach($old_client_names as $old_client_name){}
        foreach($new_client_names as $new_client_name){}

        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Clients",
                        'Updated an item in table [Clients] . ['. $id . ']',
                        'Updated a record ['. $client->client_name . ']',
                        "Client's Organization Name",
                        $old_client_name->organization_name,
                        $new_client_name->organization_name,
                        'Clients' . "_". $id,
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
  } //endif

        if($request->txt_clients_designation != $client->client_designation){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Clients",
                        'Updated an item in table [Clients] . ['. $id . ']',
                        'Updated a record ['. $client->client_name . ']',
                        "Client's Designation",
                        $client->client_designation,
                        $request->txt_clients_designation,
                        'Clients' . "_". $id,
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
  } //endif

        if($request->txt_clients_contact_no != $client->client_contact_no){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Clients",
                        'Updated an item in table [Clients] . ['. $id . ']',
                        'Updated a record ['. $client->client_name . ']',
                        "Client's Contact No.",
                        $client->client_contact_no,
                        $request->txt_clients_contact_no,
                        'Clients' . "_". $id,
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
  } //endif
 }

    public static function newClientLogs($request) {
        DB::beginTransaction();
        
        $clients = DB::select("
        SELECT `id`, 
        `client_name`,
        `client_organization`,
        `client_designation`,
        `client_contact_no`,
        `is_deleted`

        FROM `clients`
        WHERE `client_name`        = ?
        AND `client_organization`  = ?
        AND `client_designation`   = ?
        AND `is_deleted`           = ?",
        [
          $request->txt_clients_name,
          $request->txt_clients_organization,
          $request->txt_clients_designation,
          0
        ]
        );

        foreach($clients as $client){}
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "Client",
                        'Added an item in table [Clients] . [' . $client->id .']',
                        'Added a record [' . $client->client_name .']',
                        'Details',
                        '',
                        $request->txt_clients_name,
                        'Clients' . "_". $client->id,
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

    public static function StoreNewIECEcopyLogs($request) {
        DB::beginTransaction();
        $iecs = DB::select("
        SELECT `id`, 
        `iec_title`
        FROM `iecs`
        WHERE `id` = ?",
        [
          $request->txt_ecopy_title
        ]
        );
        foreach($iecs as $iec){}

        $ecopies = DB::select("
        SELECT `E`.`ecopy_id`, 
        `E`.`ecopy_iec_title`,
        `E`.`ecopy_iec_soft_copy`,
        `E`.`ecopy_version_no`,
        `E`.`is_deleted`,
        `I`.`id`,
        `I`.`iec_title`
        FROM `iec_ecopies` `E`
        JOIN `iecs` `I`
        ON `I`.`id` = `E`.`ecopy_iec_title`
        WHERE `ecopy_iec_title` = ?
        AND `ecopy_version_no`  = ?
        AND `is_deleted` = ?",
        [
          $request->txt_ecopy_title,
          $request->txt_ecopy_version_no,
          0
        ]
        );
        foreach($ecopies as $ecopy){}
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "E-Copy",
                        'Added an item in table [IEC_Ecopies] . [' . $ecopy->ecopy_id .']',
                        'Added a record [' . $ecopy->iec_title .']',
                        'Details',
                        '',
                        $iec->iec_title,
                        'IEC_Ecopies' . "_". $ecopy->ecopy_id,
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

    public static function StoreNewEcopyLogs($request) {
        DB::beginTransaction();
        $iecs = DB::select("
        SELECT `id`, 
        `iec_title`
        FROM `iecs`
        WHERE `id` = ?",
        [
          $request->txt_ecopy_title[0]
        ]
        );
        foreach($iecs as $iec){}

        $ecopies = DB::select("
        SELECT `E`.`ecopy_id`, 
        `E`.`ecopy_iec_title`,
        `E`.`ecopy_iec_soft_copy`,
        `E`.`ecopy_version_no`,
        `E`.`is_deleted`,
        `I`.`id`,
        `I`.`iec_title`
        FROM `iec_ecopies` `E`
        JOIN `iecs` `I`
        ON `I`.`id` = `E`.`ecopy_iec_title`
        WHERE `E`.`ecopy_iec_title` = ?
        AND `E`.`ecopy_version_no`  = ?
        AND `E`.`is_deleted` = ?",
        [
          $request->txt_ecopy_title[0],
          $request->txt_ecopy_version_no[0],
          0
        ]
        );
        foreach($ecopies as $ecopy){}
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "E-Copy",
                        'Added an item in table [IEC_Ecopies] . [' . $ecopy->ecopy_id .']',
                        'Added a record [' . $ecopy->iec_title .']',
                        'Details',
                        '',
                        $iec->iec_title,
                        'IEC_Ecopies' . "_". $ecopy->ecopy_id,
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

    public static function updateEcopyLogs($request, $id, $image_soft_copy_file) {
        DB::beginTransaction();
        $ecopies = DB::select("
        SELECT `E`.`ecopy_id`, 
        `E`.`ecopy_iec_title`,
        `E`.`ecopy_iec_soft_copy`,
        `E`.`ecopy_version_no`,
        `E`.`is_deleted`,
        `I`.`id`,
        `I`.`iec_title`
        FROM `iec_ecopies` `E`
        JOIN `iecs` `I`
        ON `I`.`id` = `E`.`ecopy_iec_title`
        WHERE `E`.`ecopy_id` = ?
        AND `E`.`is_deleted` = ?",
        [
          $id,
          0
        ]
        );
        foreach($ecopies as $ecopy){}
        if($request->txt_ecopy_version_no != $ecopy->ecopy_version_no){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "E-Copy",
                        'Updated an item in table [IEC_Ecopies] . ['. $id . ']',
                        'Updated a record ['. $ecopy->iec_title . ']',
                        "Version No.",
                        $ecopy->ecopy_version_no,
                        $request->txt_ecopy_version_no,
                        'IEC_Ecopies' . "_". $id,
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
  } //endif

        if($request->txt_ecopy_title != $ecopy->ecopy_iec_title){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "E-Copy",
                        'Updated an item in table [IEC_Ecopies] . ['. $id . ']',
                        'Updated a record ['. $ecopy->iec_title . ']',
                        "Title",
                        $ecopy->ecopy_iec_title,
                        $request->txt_ecopy_title,
                        'IEC_Ecopies' . "_". $id,
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
  } //endif
if($request->file('txt_iec_soft_copy')){
        if($image_soft_copy_file != $ecopy->ecopy_iec_soft_copy){
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "E-Copy",
                        'Updated an item in table [IEC_Ecopies] . ['. $id . ']',
                        'Updated a record ['. $ecopy->iec_title . ']',
                        "File",
                        $ecopy->ecopy_iec_soft_copy,
                        $image_soft_copy_file,
                        'IEC_Ecopies' . "_". $id,
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
    } //endif
    } //endif
  }

    public static function deleteEcopyLogs($id) {
         $ecopies = DB::select("
        SELECT `E`.`ecopy_id`, 
        `E`.`ecopy_iec_title`,
        `E`.`ecopy_iec_soft_copy`,
        `E`.`ecopy_version_no`,
        `E`.`is_deleted`,
        `I`.`id`,
        `I`.`iec_title`
        FROM `iec_ecopies` `E`
        JOIN `iecs` `I`
        ON `I`.`id` = `E`.`ecopy_iec_title`
        WHERE `E`.`ecopy_id` = ?",
        [
          $id,
        ]
        );
        foreach($ecopies as $ecopy){}
        DB::beginTransaction();
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        'E-Copy',
                        'Deleted an item in table [IEC_Ecopies] . [' . $id . ']',
                        'Deleted a record [' . $ecopy->iec_title . ']',
                        'Status',
                        'N/A',
                        'N/A',
                        'IEC_Ecopies' . "_". $id,
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


    public static function deleteUserLogs($id) {
        DB::beginTransaction();
        $users = DB::select("
           SELECT  `id`,
            `name`,
            `username`,
            `email`,
            `organization`,
            `uid`
        FROM `users`
        WHERE `id`    = ?
        ",
        [
          $id,
        ]
        );
        foreach($users as $user){}
        try {
         $inserted = DB::insert("
                     INSERT INTO `audit_logs`
                     (
                         `audit_module`,
                         `audit_activity`,
                         `audit_activity_title`,
                         `audit_field_affected`,
                         `audit_old_value`,
                         `audit_new_value`,
                         `audit_rec_table_id`,
                         `authorID`,
                         `created_at`
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        "User's Detail",
                        'Deleted an item in table [Users] . ['. $id . ']',
                        'Deleted a record ['. $user->name . ']',
                        "Status",
                        'N/A',
                        'N/A',
                        'Users' . "_". $id,
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

    public static function selectAllLogsList($request) {
        $iid = array();
        $iid = implode(',', $request->chk_selectOneLog);
    $result = DB::select("
        SELECT `A`.`audit_id`,
        `A`.`audit_module`,
        `A`.`audit_activity`,
        `A`.`audit_activity_title`,
        `A`.`audit_field_affected`,
        `A`.`audit_old_value`,
        `A`.`audit_new_value`,
        `A`.`audit_rec_table_id`,
        `A`.`is_deleted`,
        `A`.`authorID`,
        `A`.`created_at`,
        `U`.`id`,
        `U`.`name`
        FROM `audit_logs` `A`
        JOIN `users` `U`
        ON `U`.`id` = `A`.`authorID`
        WHERE `A`.`audit_id` IN ($iid)
        AND `A`.`is_deleted` = 0"
    );
    return $result;
    }

    public static function selectAllList2($request) {
    $result = DB::select("
        SELECT `A`.`audit_id`,
        `A`.`audit_module`,
        `A`.`audit_activity`,
        `A`.`audit_activity_title`,
        `A`.`audit_field_affected`,
        `A`.`audit_old_value`,
        `A`.`audit_new_value`,
        `A`.`audit_rec_table_id`,
        `A`.`authorID`,
        `A`.`created_at`,
        `U`.`id`,
        `U`.`name`
        FROM `audit_logs` `A`
        JOIN `users` `U`
        ON `U`.`id` = `A`.`authorID`
        WHERE (date(`A`.`created_at`) BETWEEN ?
        AND                           ?)
        ORDER BY month(`A`.`created_at`) 
        AND  DAY(`A`.`created_at`) DESC",        
        [
            date($request->txt_iec_date_from),
            date($request->txt_iec_date_to),
        ]
    );
    return $result;
    }
}
 
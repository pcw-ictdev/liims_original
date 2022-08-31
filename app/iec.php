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

//use Intervention\Image\ImageManagerStatic as Image;
 
class iec extends Model
{
      public static function validateNewEntryDetails($request) {
        $result = DB::select("
        SELECT `id`, 
        `iec_refno`,
        `iec_title`,
        `iec_author`,
        `iec_publisher`,
        `iec_copyright_date`,
        `iec_specifications`,
        `iec_material_type`,
        `authorID`,
        `editorID`,
        `is_deleted`,
        `created_at`,
        `updated_at`
        FROM `iecs`
        WHERE `iec_refno`    = ?
        AND   `iec_title`    = ?
        AND   `iec_author`   = ?
        AND   `iec_publisher`= ?",
        
         [ 
           $request->input('txt_iec_refno'),
           $request->input('txt_iec_title'),
           $request->input('txt_iec_author'),
           $request->input('txt_iec_publisher'),
         ]
    );
            if ( !empty($result) ) {
                return true;
            } else { 
                return false;
            }
    }

    
    public static function insertNewRecord($request) {
    $image_file = '';
    // Random Strings
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $pin = mt_rand(1000000, 9999999)
        . mt_rand(1000000, 9999999)
        . $characters[rand(0, strlen($characters) - 1)];
    $string = str_shuffle($pin);
    DB::beginTransaction();
    try {
     if ($request->hasFile('txt_iec_image')) {
        $image = $request->file('txt_iec_image');
        
        $img_name = $string.'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('images/uploads/');
        $resize_image = Image::make($image->getRealPath());
        $resize_image->resize(400, 300, function($constraint){
        $constraint->aspectRatio();
        })->save($destinationPath . $img_name, 80);
        $imagePath = $destinationPath. "/".  $img_name;
     //   $image->move($destinationPath, $img_name);
        $image_file = '/images/uploads/' . $img_name;
 

         $inserted = DB::insert("
                     INSERT INTO `iecs`
                     (
                        `iec_refno`,
                        `iec_title`,
                        `iec_author`,
                        `iec_publisher`,
                        `iec_copyright_date`,
                        `iec_material_type`,
                        `iec_page`,
                        `iec_specifications`,                       
                        `iec_image`,
                        `iec_threshold`,
                        `authorID`,
                        `created_at`                
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        $request->input('txt_iec_refno'),
                        $request->input('txt_iec_title'),
                        $request->input('txt_iec_author'),
                        $request->input('txt_iec_publisher'),
                        $request->input('txt_iec_copyright_date'),
                        $request->input('txt_iec_type_of_materials'),
                        $request->input('txt_iec_page'),
                        $request->input('txt_iec_specifications'),
                        $image_file,
                        $request->input('txt_iec_threshold'),
                        Auth::user()->id,
                        Carbon::now('Asia/Manila')
                     ]
        );
     DB::commit();
    $result = true;
       }  else { 
         $inserted = DB::insert("
                     INSERT INTO `iecs`
                     (
                        `iec_refno`,
                        `iec_title`,
                        `iec_author`,
                        `iec_publisher`,
                        `iec_copyright_date`,
                        `iec_material_type`,
                        `iec_page`,
                        `iec_specifications`,
                        `iec_threshold`,
                        `authorID`,
                        `created_at`                
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        $request->input('txt_iec_refno'),
                        $request->input('txt_iec_title'),
                        $request->input('txt_iec_author'),
                        $request->input('txt_iec_publisher'),
                        $request->input('txt_iec_copyright_date'),
                        $request->input('txt_iec_type_of_materials'),
                        $request->input('txt_iec_page'),
                        $request->input('txt_iec_specifications'),
                        $request->input('txt_iec_threshold'),
                        Auth::user()->id,
                        Carbon::now('Asia/Manila')
                     ]
        );
     DB::commit();
    $result = true;         
       }// end $request->hasFile('rimg')    

    } catch (\Exception $e) {
        DB::rollBack();
        $result = $e->getMessage();
    }
    return $result;

  }

    public static function selectAllRecords() {
        $result = DB::select("
        SELECT `I`.`id`, 
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_publisher`,
        `I`.`iec_copyright_date`,
        `I`.`iec_page`,
        `I`.`iec_specifications`,
        `I`.`iec_material_type`,
        `I`.`iec_image`,
        `I`.`iec_threshold`,
        `I`.`authorID`,
        `I`. `editorID`,
        `I`.`is_deleted`,
        `I`. `created_at`,
        `I`.`updated_at`,
        `M`.`id` as `materialIID`,
        `M`.`material_name`,
        `U`.`id` as 'userIID',
        `U`.`name`
        FROM `iecs` `I`
        JOIN `materials` `M`
        ON `M`.`id` = `I`.`iec_material_type`
        JOIN `users` `U`
        ON `U`.`id` = `I`.`authorID`
        WHERE `I`.`is_deleted`   = 0
        ORDER BY `I`.`iec_title` ASC
        ");
        return $result;
    }

    public static function selectAllRecords2($request) {
        $result = DB::select("
        SELECT `I`.`id`, 
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_publisher`,
        `I`.`iec_copyright_date`,
        `I`.`iec_page`,
        `I`.`iec_specifications`,
        `I`.`iec_material_type`,
        `I`.`iec_image`,
        `I`.`iec_threshold`,
        `I`.`authorID`,
        `I`. `editorID`,
        `I`.`is_deleted`,
        `I`. `created_at`,
        `I`.`updated_at`,
        `M`.`id` as `materialIID`,
        `M`.`material_name`,
        `U`.`id` as 'userIID',
        `U`.`name`
        FROM `iecs` `I`
        JOIN `materials` `M`
        ON `M`.`id` = `I`.`iec_material_type`
        JOIN `users` `U`
        ON `U`.`id` = `I`.`authorID`
        WHERE (date(`I`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND `I`.`is_deleted`   = 0
        ORDER BY `I`.`iec_title` ASC",

        [
            date($request->txt_iec_date_from),
            date($request->txt_iec_date_to),
            0
        ]
);
        return $result;
    }

    public static function selectAllRecordsCount($request) {
        $result = DB::select("
        SELECT 
        `I`.`id`,
        count(`R`.`iec_update_id`) as 'countID'
        FROM `iecs` `I`
        JOIN `iec_stock_updates` `R`
        ON `R`.`iec_update_id` = `I`.`id`
        WHERE (date(`I`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND `R`.`iec_update_type`            =?
        AND `I`.`is_deleted`   = 0
        GROUP BY `R`.`iec_update_id`,  `I`.`id`",
        [
            date($request->txt_iec_date_from),
            date($request->txt_iec_date_to),
            4,
            0
        ]
);
        return $result;
    }

    public static function selectAllRecordsCount2($request) {
        $date_range_from = date('Y-m-d', strtotime($request->txt_iec_date_from));
        $date_range_to = date('Y-m-d', strtotime($request->txt_iec_date_to)); 
        $result = DB::select("
        SELECT 
        `I`.`id`,
        count(`R`.`iec_update_id`) as 'countID'
        FROM `iecs` `I`
        JOIN `iec_stock_updates` `R`
        ON `R`.`iec_update_id` = `I`.`id`
        WHERE (date(`I`.`created_at`) BETWEEN ?
        AND                                   ?)
        AND `R`.`iec_update_type`            =?
        AND `I`.`is_deleted`   = 0
        GROUP BY `R`.`iec_update_id`,  `I`.`id`",
        [
            $date_range_from,
            $date_range_to,
            4,
            0
        ]
);
        return $result;
    }


    public static function selectAllRecord() {
        $result = DB::select("
        SELECT `I`.`id`, 
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_publisher`,
        `I`.`iec_copyright_date`,
        `I`.`iec_page`,
        `I`.`iec_specifications`,
        `I`.`iec_material_type`,
        `I`.`iec_image`,
        `I`.`iec_threshold`,
        `I`.`authorID`,
        `I`. `editorID`,
        `I`.`is_deleted`,
        `I`. `created_at`,
        `I`.`updated_at`,
        `M`.`id` as `materialIID`,
        `M`.`material_name`,
        `U`.`id` as 'userIID',
        `U`.`name`
        FROM `iecs` `I`
        JOIN `materials` `M`
        ON `M`.`id` = `I`.`iec_material_type`
        JOIN `users` `U`
        ON `U`.`id` = `I`.`authorID`
        WHERE `I`.`is_deleted` = 0
        ORDER BY `I`.`iec_title` ASC
        ");
   //     dd($result);
        return $result;
    }

    public static function selectCriticalItems() {
        $result = DB::select("
        SELECT `I`.`id`, 
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_material_type`,
        `I`.`iec_image`,
        `I`.`iec_threshold`,
        `I`.`iec_threshold_limit`,
        `I`.`is_deleted`

        FROM `iecs` `I`
        WHERE `I`.`iec_threshold` < `I`.`iec_threshold_limit`
        AND `I`.`is_deleted` = 0
        ORDER BY `I`.`iec_title` 
        AND `I`.`iec_threshold` ASC
        ");
        return $result;
    }

    public static function selectRandomTitle($keyword) {
        $result = DB::select("
        SELECT `I`.`id`, 
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_publisher`,
        `I`.`iec_copyright_date`,
        `I`.`iec_page`,
        `I`.`iec_specifications`,
        `I`.`iec_material_type`,
        `I`.`iec_image`,
        `I`.`iec_threshold`,
        `I`.`authorID`,
        `I`. `editorID`,
        `I`.`is_deleted`,
        `I`. `created_at`,
        `I`.`updated_at`,
        `M`.`id`,
        `M`.`material_name`,
        `U`.`id`,
        `U`.`name`,
        `I`.`id`
        FROM `iecs` `I`
        JOIN `materials` `M`
        ON `M`.`id` = `I`.`iec_material_type`
        JOIN `users` `U`
        ON `U`.`id` = `I`.`authorID`
        WHERE `I`.`iec_title` like ?
        AND `I`.`is_deleted` = ?
        ORDER BY `I`.`iec_title`
        AND `I`.`created_at` ASC",
        [
          '%' . $keyword . '%',
          0
        ]
         );
        return $result;
    }

    public static function selectedIEC($id) {
        $result = DB::select("
        SELECT `I`.`id`, 
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_publisher`,
        `I`.`iec_copyright_date`,
        `I`.`iec_page`,
        `I`.`iec_specifications`,
        `I`.`iec_material_type`,
        `I`.`iec_image`,
        `I`.`iec_threshold`,
        `I`.`iec_threshold_limit`,
        `I`.`authorID`,
        `I`. `editorID`,
        `I`.`is_deleted`,
        `I`. `created_at`,
        `I`.`updated_at`,
        `M`.`id` AS 'mid',
        `M`.`material_name`,
        `U`.`id` as 'userIID',
        `U`.`name`
        FROM `iecs` `I`
        JOIN `materials` `M`
        ON `M`.`id` = `I`.`iec_material_type`
        JOIN `users` `U`
        ON `U`.`id` = `I`.`authorID`
        WHERE `I`.`id` = ?
        AND `I`.`is_deleted` = ?
        ORDER BY `I`.`iec_title`
        AND `I`.`created_at` ASC",
        [
          $id,
          0
        ]
         );
        return $result;
    }

    public static function selectIECDetails($id) {
        $result = DB::select("
        SELECT `I`.`id`, 
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_publisher`,
        `I`.`iec_copyright_date`,
        `I`.`iec_page`,
        `I`.`iec_specifications`,
        `I`.`iec_material_type`,
        `I`.`iec_image`,
        `I`.`iec_threshold`,
        `I`.`authorID`,
        `I`. `editorID`,
        `I`.`is_deleted`,
        `I`. `created_at`,
        `I`.`updated_at`,
        REPLACE(`M`.`id`, ',', '') AS 'mid',
        `M`.`material_name`,
        `U`.`id`,
        `U`.`name`
        FROM `iecs` `I`
        JOIN `materials` `M`
        ON `M`.`id` = `I`.`iec_material_type`
        JOIN `users` `U`
        ON `U`.`id` = `I`.`authorID`
        WHERE `I`.`id` = ?
        AND `I`.`is_deleted` = ?
        ORDER BY `I`.`iec_title`
        AND `I`.`created_at` ASC",
        [
          $id,
          0
        ]
         );

        return $result;
    }

      public static function validateIECDetails($request, $id) {
        $result = DB::select("
        SELECT `I`.`id`, 
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_publisher`,
        `I`.`iec_copyright_date`,
        `I`.`iec_page`,
        `I`.`iec_material_type`,
        `I`.`iec_specifications`,
        `I`.`iec_image`,
        `I`.`iec_threshold`,
        `I`.`authorID`,
        `I`. `editorID`,
        `I`.`is_deleted`,
        `I`. `created_at`,
        `I`.`updated_at`
        FROM `iecs` `I`
        WHERE `iec_title` = ?
        AND `iec_author`  = ?
        AND `is_deleted`  = ?
        AND `id`         != ?",
        
         [ 
           $request->input('txt_iec_title'),
           $request->input('txt_iec_author'),
           0,
           $id
         ]
    );
            if ( !empty($result) ) {
                return true;
            } else { 
                return false;
            }
    }

    public static function updateIECDetails($request, $id, $image_file) {   
 
    DB::beginTransaction();
    $result = array();
    try {
    if ($request->hasFile('txt_iec_image')) {
         $affected = DB::update("
                UPDATE `iecs`
                SET `iec_title`          = ?,
                    `iec_author`         = ?,
                    `iec_publisher`      = ?,
                    `iec_copyright_date` = ?,
                    `iec_material_type`  = ?,
                    `iec_image`          = ?,
                    `iec_page`           = ?,
                    `iec_specifications` = ?,
                    `iec_threshold_limit`= ?,
                    `updated_at`         = ?,
                    `editorID`           = ? 
                WHERE 
                    `id` = ?", [
                    $request->input('txt_iec_title'),
                    $request->input('txt_iec_author'),
                    $request->input('txt_iec_publisher'),
                    $request->input('txt_iec_copyright_date'),
                    $request->input('txt_iec_type_of_materials'),
                    $image_file,
                    $request->input('txt_iec_page'),
                    $request->input('txt_iec_specifications'),
                    $request->input('txt_iec_threshold'),
                    Carbon::now('Asia/Manila'),
                    Auth::user()->id,
                    $id

                ]
        ); 
    DB::commit();
    $result = true;



    } else {
        $affected = DB::update("
                UPDATE `iecs`
                SET `iec_title`          = ?,
                    `iec_author`         = ?,
                    `iec_publisher`      = ?,
                    `iec_copyright_date` = ?,
                    `iec_material_type`  = ?,
                    `iec_page`           = ?,
                    `iec_specifications` = ?,
                    `iec_threshold_limit`= ?,
                    `updated_at`         = ?,
                    `editorID`           = ? 
                WHERE 
                    `id` = ?", [
                    $request->input('txt_iec_title'),
                    $request->input('txt_iec_author'),
                    $request->input('txt_iec_publisher'),
                    $request->input('txt_iec_copyright_date'),
                    $request->input('txt_iec_type_of_materials'),
                    $request->input('txt_iec_page'),
                    $request->input('txt_iec_specifications'),
                    $request->input('txt_iec_threshold'),
                    Carbon::now('Asia/Manila'),
                    Auth::user()->id,
                    $id

                ]
        ); 
    DB::commit();
    $result = true;

    }

    } catch (\Exception $e) {
        DB::rollBack();
        $result = $e->getMessage();
    }
    return $result;
    }


      public static function selectIecKeywordDetails($id) {
        $keyword = $id;     
        $result = DB::select("
        SELECT `id`, 
        `iec_refno`,
        `iec_title`,
        `iec_threshold`,
        `is_deleted`,
        `authorID`,
        `editorID`,
        `created_at`,
        `updated_at`
        FROM `iecs`
        WHERE `iec_title` like ?
        AND `is_deleted`     = ?",
        [
          '%'. $keyword . '%',
          0
        ]
        );
        return $result;
    }  

      public static function findIecStocks($iec) {  
        $result = DB::select("
        SELECT `id`, 
        `iec_refno`,
        `iec_title`,
        `iec_threshold`,
        `is_deleted`,
        `authorID`,
        `editorID`,
        `created_at`,
        `updated_at`
        FROM `iecs`
        WHERE `iec_title`  = ?
        AND   `is_deleted` = ?",
        [
          $iec,
          0
        ]
        );
        return $result;
    }  

    public static function insertNewModalRecord($id) {
    $image_file = '';
    // Random Strings
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $pin = mt_rand(1000000, 9999999)
        . mt_rand(1000000, 9999999)
        . $characters[rand(0, strlen($characters) - 1)];
    $string = str_shuffle($pin);
    DB::beginTransaction();
    try {
    if ($request->hasFile('txt_iec_image')) {
        $image = $request->file('txt_iec_image');
        $img_name = $string.'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/images/uploads');
        $imagePath = $destinationPath. "/".  $img_name;
        $image->move($destinationPath, $img_name);
        $image_file = '/images/uploads/' . $img_name;
 

         $inserted = DB::insert("
                     INSERT INTO `iecs`
                     (
                        `iec_title`,
                        `iec_author`,
                        `iec_publisher`,
                        `iec_copyright_date`,
                        `iec_material_type`,
                        `iec_page`,
                        `iec_specifications`,
                        `iec_image`,
                        `iec_threshold`,
                        `authorID`,
                        `created_at`                
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        $request->input('txt_iec_title'),
                        $request->input('txt_iec_author'),
                        $request->input('txt_iec_publisher'),
                        $request->input('txt_iec_copyright_date'),
                        $request->input('txt_iec_type_of_materials'),
                        $request->input('txt_iec_page'),
                        $request->input('txt_iec_specifications'),
                        $image_file,
                        $request->input('txt_iec_threshold'),
                        Auth::user()->id,
                        Carbon::now('Asia/Manila')
                     ]
        );
     DB::commit();
    $result = true;
       }  else { 
         $inserted = DB::insert("
                     INSERT INTO `iecs`
                     (
                        `iec_refno`,
                        `iec_title`,
                        `iec_author`,
                        `iec_publisher`,
                        `iec_copyright_date`,
                        `iec_material_type`,
                        `iec_page`,
                        `iec_specifications`,
                        `iec_threshold`,
                        `authorID`,
                        `created_at`                
                     ) 
                     VALUES 
                     (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                        $request->input('txt_iec_refno'),
                        $request->input('txt_iec_title'),
                        $request->input('txt_iec_author'),
                        $request->input('txt_iec_publisher'),
                        $request->input('txt_iec_copyright_date'),
                        $request->input('txt_iec_type_of_materials'),
                        $request->input('txt_iec_page'),
                        $request->input('txt_iec_specifications'),
                        $request->input('txt_iec_threshold'),
                        Auth::user()->id,
                        Carbon::now('Asia/Manila')
                     ]
        );
     DB::commit();
    $result = true;         
       }// end $request->hasFile('rimg')    

    } catch (\Exception $e) {
        DB::rollBack();
        $result = $e->getMessage();
    }
    return $result;

  }    

public static function iecstocks() {  
        $result = DB::select("
        SELECT `id`, 
        `iec_title`,
        `iec_threshold`,
        `is_deleted`
        FROM `iecs`
        WHERE `is_deleted` = 0
    ");
        return $result;
    }  

    public static function updateIECStocks($request, $iec_rec) { 
    $iec_res = array();
    $result = array();
        $req_resu = DB::select("
               SELECT  `I`.`id`,
                        `I`.`iec_refno`,
                        `I`.`iec_title`,
                        `I`.`iec_author`,
                        `I`.`iec_publisher`,
                        `I`.`iec_copyright_date`,
                        `I`.`iec_material_type`,
                        `I`.`iec_specifications`,
                        `I`.`iec_threshold`
            FROM `iecs` `I`
            WHERE  `I`.`id` = $request->txt_iec_id",
            );
            foreach($req_resu as $iecResu) 
            {  } 

    foreach($iec_rec as $iec_res); 
    if($request->iec_option  == 1){
        $totalStock = $request->txt_iec_stock + $request->input('iec_restock_pieces');
      //  $totalStock = $request->input('iec_restock_pieces');
    }
    if($request->iec_option  == 2){
        //$totalStock = $request->txt_iec_stock - $request->input('iec_adjust_pieces');
         $totalStock = $request->input('iec_adjust_pieces');
    }
 // if($request->iec_option == 1){
 //    $totalStock = $request->input('iec_restock_pieces');
 // }
 
 
    DB::beginTransaction();


    try {
        $affected = DB::update("
                UPDATE `iecs`
                SET
                    `iec_threshold`   = ?,
                    `updated_at`      = ?,
                    `editorID`        = ?
                WHERE 
                    `id` = ?", [
                    $totalStock,
                    Carbon::now('Asia/Manila'),
                    Auth::user()->id,
                    $request->input('txt_iec_id')    
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

    public static function itemsGraph() {
        $result = DB::select("
        SELECT REPLACE(`I`.`id`, ',', '') AS 'isource',
               REPLACE(`R`.`request_material_name`, ',', '') AS 'title',
           SUM(REPLACE(`R`.`request_material_quantity`, ',', '')
                ) AS 'total_req',
               REPLACE(`I`.`iec_refno`, ',', '') AS 'iec_refno'
        FROM `requests` `R`
        JOIN(`iecs` `I`)
        ON `I`.`id` = `R`.`request_material_name`
        WHERE `R`.`request_material_name` != ''
        GROUP BY title, isource, iec_refno
        "
        );
        return $result;
    }
 
     public static function chartCategoryList($id) {
        $result = DB::select("
        SELECT  REPLACE(year(`R`.`created_at`), ',', '') AS 'title',
        SUM(REPLACE(`R`.`request_material_quantity`, ',', '')
                ) AS 'total_req',
        REPLACE(`I`.`iec_material_type`, ',', '') AS 'material_type',
        REPLACE(`M`.`material_name`, ',', '') AS 'material_name',
        REPLACE(`R`.`request_material_name`, ',', '') AS 'material'
        FROM `iecs` `I`
        JOIN(`requests` `R`)
        ON `R`.`request_material_name` = `I`.`id`
        JOIN(`materials` `M`)
        ON `M`.`id` = `I`.`iec_material_type`
        WHERE `R`.`request_material_name`    !=  ?
        AND `I`.`iec_material_type`           =  ?
        GROUP BY title, material_type, material_name, material",
        [
            '',
            $id,
        ]
        );
        return $result;
    } 

     public static function chartCategoryAllList() {
        $result = DB::select("
        SELECT  REPLACE(year(`R`.`created_at`), ',', '') AS 'title',
        SUM(REPLACE(`R`.`request_material_quantity`, ',', '')
                ) AS 'total_req'
  
        FROM `requests` `R`
        WHERE `R`.`request_material_name` != ''
        GROUP BY title 
        "
        );
        return $result;
    }   

      public static function selectIECName($result) { 
        $asd = array_column($result, 'request_material_name');
        $asd = array_merge($asd);
        $collection_rec_id = collect($asd);
        $recids = $collection_rec_id->unique()->values()->all(); 
        $recids = implode(",", $recids);
        $result = DB::select("
        SELECT `id`, 
        `iec_title`,
        `is_deleted`
        FROM `iecs`
        WHERE `id`   IN  ($recids)
        ");
        return $result;
    }  

    public static function graphOrgList($id) {
 //2
        $iid = [];
        $iid = explode("_", $id);
        if($iid[1] == 'ALL'){
        $result = DB::select("
         SELECT SUM(REPLACE(`R`.`request_material_quantity`, ',', '')
         ) AS 'total_req',
        DATE_FORMAT(`R`.`created_at`, '%m') AS new_date
        FROM `organization_type` `OT`
        JOIN `organizations` `O`
        ON `O`.`organization_type` = `OT`.`org_type_id`
        JOIN `requests` `R`
        ON `R`.`request_organization` = `O`.`organization_name`
        WHERE YEAR(`R`.`created_at`)   = ?
        AND   `R`.`is_deleted`         = ?
        GROUP BY new_date",
        [
             $iid[0],
             0
        ]
        );
        return $result;


        } else {
        $result = DB::select("
         SELECT SUM(REPLACE(`R`.`request_material_quantity`, ',', '')
         ) AS 'total_req',
        DATE_FORMAT(`R`.`created_at`, '%m') AS new_date
        FROM `organization_type` `OT`
        JOIN `organizations` `O`
        ON `O`.`organization_type` = `OT`.`org_type_id`
        JOIN `requests` `R`
        ON `R`.`request_organization` = `O`.`organization_name`
        WHERE YEAR(`R`.`created_at`)   = ?
        AND `OT`.`org_type_id`         = ?
        AND   `R`.`is_deleted`         = ?
        GROUP BY new_date",
        [
             $iid[0],
             $iid[1],
             0
        ]
        );

        return $result;
        }
    }  

    public static function graphOrgList1($id) {
    
        $iid = [];
        $iid = explode("_", $id);
        if($iid[1] == 'ALL'){
        // $result = DB::select("
        //  SELECT SUM(REPLACE(`R`.`request_material_quantity`, ',', '')
        //  ) AS 'total_req',
        //  REPLACE(YEAR(`R`.`created_at`), ',', '') AS 'adate',
        // `I`.`iec_refno`                  
        // FROM `requests` `R`
        // JOIN `iecs` `I`
        // ON `I`.`id` = `R`.`request_material_name`
        // WHERE  YEAR(`R`.`created_at`)         = ?
        // GROUP BY  `I`.`iec_refno`, `R`.`request_material_name`,adate",
        // [
        //     $iid[0]
        // ]
        // );
        // return $result;
        $result = DB::select("
         SELECT SUM(REPLACE(`R`.`request_material_quantity`, ',', '')
         ) AS 'total_req',
        DATE_FORMAT(`R`.`created_at`, '%m') AS new_date
        FROM `materials` `M`
        JOIN `iecs` `I` 
        ON `I`.`iec_material_type` = `M`.`id`    
        JOIN `requests` `R`
        ON `R`.`request_material_name` = `I`.`id`  
        WHERE YEAR(`R`.`created_at`)   = ?
        AND `R`.`is_deleted` = ?
        GROUP BY new_date",
        [
             $iid[0],
             0
        ]
        );
        return $result;
      } else {
        $result = DB::select("
         SELECT SUM(REPLACE(`R`.`request_material_quantity`, ',', '')
         ) AS 'total_req',
        DATE_FORMAT(`R`.`created_at`, '%m') AS new_date,
        `M`.`id`
        FROM `materials` `M`
        JOIN `iecs` `I` 
        ON `I`.`iec_material_type` = `M`.`id`    
        JOIN `requests` `R`
        ON `R`.`request_material_name` = `I`.`id`  
        WHERE YEAR(`R`.`created_at`)   = ?
        AND `R`.`is_deleted`           = ?
        AND `M`.`id`                   = ?


        GROUP BY new_date, `M`.`id`",
        [
            $iid[0],
            0,
            $iid[1]
        ]

        );
        return $result;
    }
}

    public static function SelectIECStockAvailable() {
    $result = DB::select("
    SELECT `I`.`id`,
           `I`.`iec_refno`,
           `I`.`iec_title`,
           `I`.`iec_threshold`,
           `I`.`iec_specifications`,
           `I`.`is_deleted`
           FROM `iecs` `I`
           WHERE `I`.`is_deleted` = ?",
            [
                0
            ]
           );
           return $result;
 }

    public static function SelectIECOrganizationChart($id) {
if($id =='ALL'){
        $result = DB::select("
        SELECT SUM(REPLACE(`R`.`request_material_quantity`, ',', '')
                ) AS 'total_req',
               REPLACE(`R`.`request_organization`, ',', '') AS 'org',
        FROM `requests` `R`
        JOIN(`iecs` `I`)
        ON `I`.`id` = `R`.`request_material_name`
        JOIN(`materials` `M`)
        ON `M`.`id` = `I`.`iec_material_type`
        GROUP BY org
        ");
        return $result;
    } else {
        $result = DB::select("
        SELECT SUM(REPLACE(`R`.`request_material_quantity`, ',', '')
                ) AS 'total_req',
               REPLACE(`R`.`request_organization`, ',', '') AS 'org',
               REPLACE(`M`.`material_name`, ',', '') AS 'material',
        FROM `requests` `R`
        JOIN(`iecs` `I`)
        ON `I`.`id` = `R`.`request_material_name`
        JOIN(`materials` `M`)
        ON `M`.`id` = `I`.`iec_material_type`
        WHERE `R`.`request_material_name` != ?
        AND `R`.`request_organization`     = ?
        GROUP BY org, material",
        
        [
            '',
            $id,
        ]
        );
        return $result;
    }
} 
    public static function SelectIECRegionChart($id) {
        if($id == 'ALL'){
        $result = DB::select("
        SELECT `P`.`id`,
               `P`.`province_name`,
               `P`.`region`,
               `O`.`organization_address`,
               `O`.`organization_name`
        FROM `provinces` `P`
        JOIN(`organizations` `O`)
        ON `O`.`organization_address` = `P`.`province_name`
        ");
        return $result;
        } else {

       $result = DB::select("
        SELECT `P`.`id`,
               `P`.`province_name`,
               `P`.`region`,
               `O`.`organization_address`,
               `O`.`organization_name`
        FROM `provinces` `P`
        JOIN(`organizations` `O`)
        ON `O`.`organization_address` = `P`.`province_name`
        WHERE `P`.`region` = ?",
        
        [
            $id
        ]
        );
        return $result;
       }
    } 
    public static function SelectIECRegionRequestChart($recids) {
        $result = DB::select("
        SELECT SUM(REPLACE(`R`.`request_material_quantity`, ',', '')
                ) AS 'total_req',
               REPLACE(`O`.`organization_address`, ',', '') AS 'org_addr'
        FROM `requests` `R`
        JOIN(`organizations` `O`)
        ON `O`.`organization_name` = `R`.`request_organization`
        WHERE `R`.`request_organization` IN ('$recids')
        GROUP BY org_addr
        ");
        return $result;
    }

    public static function SelectIECProvinceChart($id) {
        if($id == 'ALL'){
        $result = DB::select("
        SELECT `P`.`id`,
               `P`.`province_name`,
               `P`.`region`,
               `O`.`organization_address`,
               `O`.`organization_name`        
        FROM `provinces` `P`
        JOIN(`organizations` `O`)
        ON `O`.`organization_address` = `P`.`province_name`
        ");
        return $result;
        } else {
        $result = DB::select("
        SELECT `P`.`id`,
               `P`.`province_name`,
               `P`.`region`,
               `O`.`organization_address`,
               `O`.`organization_name`        
        FROM `provinces` `P`
        JOIN(`organizations` `O`)
        ON `O`.`organization_address` = `P`.`province_name`
        WHERE `P`.`id` = ?",
            [
                $id
            ]
        );
        return $result;
        }
    } 
    public static function SelectIECProvinceRequestChart($recids) {
        $result = DB::select("
        SELECT SUM(REPLACE(`R`.`request_material_quantity`, ',', '')
                ) AS 'total_req',
               REPLACE(`O`.`organization_address`, ',', '') AS 'org_addr',
               REPLACE(`O`.`organization_name`, ',', '') AS 'org_name'
        FROM `requests` `R`
        JOIN(`organizations` `O`)
        ON `O`.`organization_name` = `R`.`request_organization`
        WHERE `R`.`request_organization` IN ('$recids')
        GROUP BY org_addr, `R`.`request_organization`, `O`.`organization_name`
        ");
        return $result;
    }

    
    public static function lookUpIECthreshold($id) {
    $result = DB::select("
    SELECT `I`.`id`,
           `I`.`iec_refno`,
           `I`.`iec_title`,
           `I`.`iec_author`,
           `I`.`iec_publisher`,
           `I`.`iec_copyright_date`,
           `I`.`iec_page`,
           `I`.`iec_specifications`,
           `I`.`iec_material_type`,
           `I`.`iec_threshold`,
           `I`.`is_deleted`,
           `M`.`id`,
           `M`.`material_name`,
           `U`.`id`,
           `U`.`name`,
           `I`.`id`
           FROM `iecs` `I`
           JOIN `materials` `M`
           ON `M`.`id` = `I`.`iec_material_type`
           JOIN `users` `U`
           ON `U`.`id` = `I`.`editorID`
           WHERE `I`.`is_deleted` = ?
           AND   `I`.`id`         = ?",
           [
            0,
            $id
            ]
           );
           return $result;
 }

    public static function deleteIECDetails($id) {   
    $result = array();

    DB::beginTransaction();


    try {

        $affected = DB::update("
                UPDATE `iecs`
                SET
                    `is_deleted`   = ?,
                    `updated_at`   = ?,
                    `editorID`     = ?
                WHERE 
                    `id` = ?", [
                    1,
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

      public static function selectAllYears() {
        $result = DB::select("
        SELECT `year_id`
        FROM `years`
        ORDER BY `year_id` ASC
        ");
        return $result;
    }

      public static function selectAllIecs() {
        $result = DB::select("
        SELECT 
         REPLACE(`id`, ',', '') AS 'aydi',
        `iec_title`
        FROM `iecs`
        ORDER BY `iec_title` ASC
        ");
        return $result;
    }
     public static function importIECSpreadsheetFile($request) {
        $result = true;

        $file = $request->file('iec_file');                       // Get File
        $filePath = $file->getPathName();                             // Get Path Name

        /* This will identify the reader for the file uploaded */
        $reader = IOFactory::createReaderForFile($filePath);          // @UIFactory line 163

        if ($reader) {
            $spreadsheet = $reader->load($filePath);                  // Load the file
            $sheetData   = $spreadsheet->getActiveSheet()->toArray(); // Get the sheet's content
        } else {
            $sheetData   = NULL;
        }

        /* This will check if sheet's data array has content */
        if ($sheetData) {
            foreach ($sheetData as $sheetKey => $sheetValue) {
                if ($sheetValue[0] && $sheetValue[1]) {
                    $iecsArray[] = [
                        'iec_refno'          => $sheetValue[0],
                        'iec_title'          => $sheetValue[1],
                        'iec_author'         => $sheetValue[2],
                        'iec_publisher'      => $sheetValue[3],
                        'iec_copyright_date' => $sheetValue[4],
                        'iec_page'           => $sheetValue[5],
                        'iec_specifications' => $sheetValue[6],
                        'iec_material_type'  => $sheetValue[7],
                        'iec_image'          => $sheetValue[8],
                        'iec_threshold'      => $sheetValue[9],
                        'authorID'           => Auth::user()->id,
                        'created_at'         => Carbon::now('Asia/Manila')   

                    ];
                } else {
                    $result = (object) array(
                        'status'  => false, 
                        'message' => 'Upload failed. Incomplete data!'
                    );
                    break;
                }
            }

            if ($result === true) {
                $iecs = DB::select("
                    SELECT `iec_refno`
                    FROM `iecs`
                    ");

                /* This will Search all the duplicates in the $officesArray
                 * and set them to false.
                 */
                foreach ($iecs as $iec) {
                    $key = array_search($iec->iec_refno, array_column($iecsArray, 'iec_refno'));
                    if ($key !== false) {
                        $iecsArray[$key]['iec_refno'] = false;
                    }
                }

                /* This will remove all the false office_name (duplicates) 
                 * in the $officesArray.
                 */
                foreach ($iecsArray as $key => $value) {
                    if ($value['iec_refno'] === false) {
                        unset($iecsArray[$key]); 
                    }
                }
                if (!empty($iecsArray)) {                   
                    DB::beginTransaction();

                    try {

                        $inserted = DB::table('iecs')->insert($iecsArray);

                        DB::commit(); 

                        $result = (object) array(
                            'status'  => true, 
                            'message' => 'File uploaded successfully!'
                        );

                    } catch (\Exception $e) {
                        DB::rollBack();
                        $result = $e->getMessage();
                    }

                } else {
                    $result = (object) array(
                        'status'  => false, 
                        'message' => 'Upload failed. File content already exists!'
                    );
                }

            }

        } else {
            $result = (object) array(
                'status'  => false, 
                'message' => 'Upload failed. File format is invalid!'
            );
        }
        return $result;
  } 

    public static function selectEcopyTitleRecord() {
        $result = DB::select("
        SELECT `I`.`id`, 
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`is_deleted`
        FROM `iecs` `I`
        WHERE `is_deleted` = 0
        ORDER BY `I`.`iec_title` ASC
        ");
        return $result;
    }


    public static function PrintAllIecs($request) {
        $iiecs = array();
        $iiecs = implode(",",$request->chk_selectOneIec);
        $result = DB::select("
        SELECT `I`.`id`, 
        `I`.`iec_refno`,
        `I`.`iec_title`,
        `I`.`iec_author`,
        `I`.`iec_publisher`,
        `I`.`iec_copyright_date`,
        `I`.`iec_page`,
        `I`.`iec_specifications`,
        `I`.`iec_material_type`,
        `I`.`iec_image`,
        `I`.`iec_threshold`,
        `I`.`authorID`,
        `I`. `editorID`,
        `I`.`is_deleted`,
        `I`. `created_at`,
        `I`.`updated_at`,
        `M`.`id` as `materialIID`,
        `M`.`material_name`,
        `U`.`id` as 'userIID',
        `U`.`name`
        FROM `iecs` `I`
        JOIN `materials` `M`
        ON `M`.`id` = `I`.`iec_material_type`
        JOIN `users` `U`
        ON `U`.`id` = `I`.`authorID`
        WHERE `I`.`is_deleted`   = 0
        AND `I`.`id` IN ($iiecs)
        ORDER BY `I`.`iec_title` ASC
        ");
        return $result;
    }

    public static function selectAllRecordsCount21($request) {
        $iiecs = array();
        $iiecs = implode(",",$request->chk_selectOneIec);
        $result = DB::select("
        SELECT 
        `I`.`id`,
        count(`R`.`iec_update_id`) as 'countID'
        FROM `iecs` `I`
        JOIN `iec_stock_updates` `R`
        ON `R`.`iec_update_id` = `I`.`id`
        WHERE `I`.`id` IN ($iiecs)
        AND `R`.`iec_update_type`   = ?
        AND `I`.`is_deleted`        = ?
        GROUP BY `R`.`iec_update_id`,  `I`.`id`",
        [
            4,
            0
        ]
);
        return $result;
    }
}



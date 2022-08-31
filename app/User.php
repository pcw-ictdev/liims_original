<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use Mail;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function selectAllUsertypes() {
     $result = DB::select("
                SELECT  `id`,
                        `uid`,
                        `utype`,
                        `created_at`,
                        `updated_at`
                FROM `usertypes`
        ");

    return $result;
    }
    public static function SuselectAllUsers() {
        $result = DB::select("
           SELECT  `U`.`id`,
            `U`.`name`,
            `U`.`user_position`,
            `U`.`username`,
            `U`.`email`,
            `U`.`password`,
            `U`.`uid`,
            `U`.`is_deleted`,
            `U`.`authorID`,
            `U`.`editorID`,
            `U`.`created_at`,
            `U`.`updated_at`,
            `UR`.`user_role_id`,
            `UR`.`user_role`,
            `US`.`id` as authorID,
            `US`.`name` as authorName
           FROM `users` `U`
           JOIN `user_roles` `UR`
           ON `UR`.`user_role_id` = `U`.`uid`
           JOIN `users` `US`
           ON `US`.`id` = `U`.`authorID`
           ORDER BY `U`.`uid` 
           AND `U`.`name` ASC 
           ");
           return $result;
   }    

   public static function uselectAllUsers() {
        $result = DB::select("
           SELECT  `U`.`id`,
            `U`.`name`,
            `U`.`user_position`,
            `U`.`username`,
            `U`.`email`,
            `U`.`password`,
            `U`.`uid`,
            `U`.`authorID`,
            `U`.`editorID`,
            `U`.`created_at`,
            `U`.`updated_at`,
            `U`.`id_deleted`,
            `UR`.`user_role_id`,
            `UR`.`user_role`,
            `UR`.`user_inventory`
            FROM `users` `U`
            JOIN `user_roles` `UR`
           ON `UR`.`user_role_id` = `U`.`uid`
            WHERE `UR`.`user_inventory` = 1
           ");
           return $result;
   }
   
    public static function selectAllUsers() {
         $result = DB::select("
    SELECT  `U`.`id`,
            `U`.`name`,
            `U`.`user_position`,
            `U`.`username`,
            `U`.`email`,
            `U`.`password`,
            `U`.`uid`,
            `U`.`authorID`,
            `U`.`editorID`,  
            `U`.`id_deleted`,                    
            `UR`.`user_role_id`,
            `UR`.`user_role`,
            `US`.`id`,
            `US`.`id` as authorID,
            `US`.`name` as authorName
            FROM `users` `U`
            JOIN `user_roles` `UR`
            ON `UR`.`user_role_id` = `U`.`uid`
            JOIN `users` `US`
            ON `US`.`id` = `U`.`authorID`
            WHERE `U`.`uid`       = ?
                ORDER BY `U`.`uid` 
                AND `U`.`name` ASC ", 
                [
                  3,
                ]
                 );
            return $result;
    }


    public static function validateUserDetails($request, $id) {
        $result = DB::select("
                SELECT  `id`,
                        `name`,
                        `username`
                        `email`,
                        `password`,
                        `uid`
                FROM `users`
                WHERE `username`  = ?
                AND    `id`   != ?", [
                        $request->input('user_empid'),
                        $id,
                        ]
             );

            if ( !empty($result) ) {
                return true;
            } else { 
                return false;
            }
    }

    public static function validateNewuserDetails($request) {
        $result = DB::select("
                SELECT  `id`,
                        `name`,
                        `username`,
                        `email`,
                        `password`,
                        `uid`,
                        `is_deleted`
                FROM `users`
                WHERE `username`    =   ? 
                AND `is_deleted`    =   ? 
                AND    `email`      =   ?", [
                        $request->input('user_empid'),
                        0,
                        $request->input('user_email')
                        ]
             );
            if ( !empty($result) ) {
                return true;
            } else { 
                return false;
            }
    }
   
public static function insertNewUser($request) {

    $result = array();

    DB::beginTransaction();


    try {
 
        // Insert unit info

        $inserted = DB::insert("
                INSERT INTO `users`
                (
                    `name`,
                    `user_position`,
                    `username`,
                    `email`,
                    `password`,
                    `uid`,
                    `authorID`,
                    `created_at`
                ) 
                VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?)", [
                    $request->input('user_name'),
                    $request->input('user_position'),
                    $request->input('user_empid'),
                    $request->input('user_email'),
                    bcrypt($request->input('user_password')),
                    $request->input('user_type'),
                    Auth::user()->id,
                    Carbon::now('Asia/Manila'),
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


    public static function selectUsersDetails($id) {    
        $result = DB::select("
               SELECT  `U`.`id`,
                        `U`.`name`,
                        `U`.`user_position`,
                        `U`.`username`,
                        `U`.`email`,
                        `U`.`password`,
                        `U`.`uid`,
                        `U`.`authorID`,
                        `U`.`editorID`,
                        `U`.`is_deleted`,
                        `U`.`user_signatory`,
                        `UR`.`user_role_id`,
                        `UR`.`user_role`
            FROM `users` `U`
            JOIN `user_roles` `UR`
            ON `UR`.`user_role_id` = `U`.`uid`
            WHERE `U`.`id` = ?
                ORDER BY `U`.`uid` 
                AND `name` ASC ", [$id]
                 );
    return $result;
    }
  
    public static function updateUsersDetails($request, $id) {   
    $result = array();
    DB::beginTransaction();

    if($request->input('user_newpassword') !='') {
    try {
        $affected = DB::update("
                UPDATE `users`
                SET
                    `name`         = ?,
                    `user_position`= ?,
                    `username`     = ?,
                    `email`        = ?,
                    `password`     = ?,
                    `uid`          = ?,
                    `is_deleted`   = ?,
                    `user_signatory`= ?,
                    `editorID`     = ?,
                    `updated_at`   = ?
                WHERE 
                    `id` = ?", [
                    $request->input('user_name'),
                    $request->input('user_position'),
                    $request->input('user_empid'),
                    $request->input('user_email'),
                    bcrypt($request->input('user_newpassword')),
                    $request->input('user_type'),
                    $request->input('user_status'),
                    $request->input('user_signatory'),
                    Auth::user()->id,
                    Carbon::now('Asia/Manila'),
                    $id,
                    
                ]
        );
    DB::commit();
    $result = true; 
    } 
    catch (\Exception $e) {
        DB::rollBack();
        $result = $e->getMessage();
    }
    return $result;
    } if($request->input('user_newpassword') =='') {
    try {
        $affected1 = DB::update("
                UPDATE `users`
                SET `user_signatory`   = ?,
                    `editorID`         = ?,
                    `updated_at`       = ?
                WHERE `is_deleted`     = ? 
                AND `user_signatory`   = ?", [
                    0,
                    Auth::user()->id,
                    Carbon::now('Asia/Manila'),
                    0,
                    1
                ]
        );

        $affected = DB::update("
                UPDATE `users`
                SET
                    `name`             = ?,
                    `user_position`    = ?,
                    `username`         = ?,
                    `email`            = ?,
                    `uid`              = ?,
                    `is_deleted`       = ?,
                    `user_signatory`   = ?,
                    `editorID`         = ?,
                    `updated_at`       = ?
                WHERE 
                    `id` = ?
                    AND
                    `password` = ?", [
                    $request->input('user_name'),
                    $request->input('user_position'),
                    $request->input('user_empid'),
                    $request->input('user_email'),
                    $request->input('user_type'),
                    $request->input('user_status'),
                    $request->input('user_signatory'),
                    Auth::user()->id,
                    Carbon::now('Asia/Manila'),
                    $id,
                    $request->input('user_password'),

                ]
        );
    DB::commit();
    $result = true;
        } 
    catch (\Exception $e) {
        DB::rollBack();
        $result = $e->getMessage();
    }
    return $result;
    }
}

// success user password update
    public static function updateUserspwDetails($request) {   
    $result = array();

    DB::beginTransaction();


    try {

        $affected = DB::update("
                UPDATE `users`
                SET
                    `password`   = ?,
                    `updated_at` = ?
                WHERE 
                    `id` = ?", [
                    bcrypt($request->input('rpassword')),
                    Carbon::now('Asia/Manila'),
                    $request->input('txt_user_id')

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

    public static function deleteUserDetails($id) {   
    $result = array();
    DB::beginTransaction();
    try {


        $affected = DB::update("
                UPDATE `users`
                SET
                    `is_deleted`    = ?,
                    `updated_at`    = ?
                WHERE 
                    `id` = ?", [
                    1,
                    Carbon::now('Asia/Manila'),
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

    public static function uselectAllInventoryUsers() {    
        $result = DB::select("
               SELECT   `U`.`id`,
                        `U`.`name`,
                        `U`.`user_position`,
                        `U`.`email`,
                        `U`.`uid`,
                        `UR`.`user_role_id`,
                        `UR`.`user_role`,
                        `UR`.`user_email_notif_iec_material`,
                        `UR`.`is_deleted`
            FROM `users` `U`
            JOIN `user_roles` `UR`
            ON `UR`.`user_role_id` = `U`.`uid`
            WHERE `UR`.`user_email_notif_iec_material`   = ?
            AND   `UR`.`is_deleted`                      = ?
            ORDER BY `name` ASC ", 
                [
                    1,
                    0               
                ]
                 );
            return $result;
    }

    public static function selectReportSignatory() {    
        $result = DB::select("
               SELECT   `U`.`id`,
                        `U`.`name`,
                        `U`.`user_position`,
                        `U`.`user_signatory`,
                        `U`.`is_deleted`
            FROM `users` `U`
            WHERE `U`.`is_deleted`      = 0
            AND   `U`.`user_signatory`  = 1", 
            );
            return $result;
    }
}

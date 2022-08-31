<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
class provinces extends Model
{
      public static function searchProvince() {
        $result = DB::select("
        SELECT `id`, 
        `province_name`,
        `created_at`,
        `updated_at`
        FROM `provinces`
        ORDER BY `province_name` ASC
        ");
        return $result;
    }

      public static function SelectProvinces() {
        $result = DB::select("
        SELECT `id`, 
        `province_name`
        FROM `provinces`
        ORDER BY `province_name` ASC
        ");
        return $result;
    }
}

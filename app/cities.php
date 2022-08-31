<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
class cities extends Model
{
      public static function searchCity() {
        $result = DB::select("
        SELECT `id`, 
        `city_name`,
        `created_at`,
        `updated_at`
        FROM `cities`
        ORDER BY `city_name` ASC
        ");
        return $result;
    }
      public static function searchCityList() {
        $result = DB::select("
        SELECT  `id` as 'cityID',
        `city_name`  as 'cityName'
        FROM `cities`
        ORDER BY `city_name` ASC
        ");
        return $result;
    }
}

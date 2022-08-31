<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
class regions extends Model
{
      public static function SelectRegions() {
        $result = DB::select("
        SELECT `id`, 
        `region_code`,
        `region_name`
        FROM `regions`
        ORDER BY length(`region_code`), `region_code`ASC
        ");
        return $result;
    }
}

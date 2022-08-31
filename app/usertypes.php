<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
class usertypes extends Model
{
 
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
}

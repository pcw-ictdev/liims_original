<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
class organization_type extends Model
{
    public static function selectOrganizationType() {
        $result = DB::select("
        SELECT `org_type_id`, 
        `org_type_code`,
        `org_type_desc`,
        `authorID`,
        `editorID`,
        `created_at`,
        `updated_at`
        FROM `organization_type`
        ORDER BY `org_type_code` ASC
        ");
       return $result;
    }

    public static function selectOrganizationTypeList() {
        $result = DB::select("
        SELECT `org_type_id`, 
        `org_type_code`
        FROM `organization_type`
        ORDER BY `org_type_code` ASC
        ");
       return $result;
    }
}

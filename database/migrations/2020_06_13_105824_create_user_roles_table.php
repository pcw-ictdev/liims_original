<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id('user_role_id');
            $table->string('user_role');
            $table->string('user_description');
            $table->string('user_material_request');
            $table->string('user_inventory');
            $table->string('user_code_library');
            $table->string('user_management');
            $table->string('user_reports');
            $table->string('user_audit_log');
            $table->string('user_role_status');
            $table->string('authorID');
            $table->string('editorID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
}

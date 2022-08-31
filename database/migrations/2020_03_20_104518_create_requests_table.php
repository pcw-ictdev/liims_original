<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id('rec_id');
            $table->string('request_id');
            $table->string('request_name');
            $table->string('request_organization');
            $table->string('request_address');
            $table->string('request_chk_address');
            $table->string('request_purpose');
            $table->string('request_material_name');
            $table->string('request_material_quantity');
            $table->string('request_status');
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
        Schema::dropIfExists('requests');
    }
}

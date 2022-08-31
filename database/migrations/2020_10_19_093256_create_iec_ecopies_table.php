<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIecEcopiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iec_ecopies', function (Blueprint $table) {
            $table->id('ecopy_id');
            $table->string('ecopy_iec_title');
            $table->string('ecopy_iec_soft_copy');
            $table->string('ecopy_version_no');
            $table->string('ecopy_status');
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
        Schema::dropIfExists('iec_ecopies');
    }
}

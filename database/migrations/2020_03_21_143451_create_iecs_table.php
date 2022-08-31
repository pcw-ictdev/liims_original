<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iecs', function (Blueprint $table) {
            $table->id();
            $table->string('iec_refno');
            $table->string('iec_title');
            $table->string('iec_author');
            $table->string('iec_publisher');
            $table->string('iec_copyright_date');
            $table->string('iec_page');
            $table->string('iec_specifications');
            $table->string('iec_material_type');
            $table->string('iec_image');
            $table->int('iec_threshold');
            $table->string('authorID');
            $table->string('editorID');
            $table->string('iec_status');
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
        Schema::dropIfExists('iecs');
    }
}

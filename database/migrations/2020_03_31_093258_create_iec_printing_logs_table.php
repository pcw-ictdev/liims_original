<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIecPrintingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iec_printing_logs', function (Blueprint $table) {
            $table->id();
            $table->string('iec_id');
            $table->string('iec_printing_date');
            $table->string('iec_printing_contractor');
            $table->string('iec_printing_cost');
            $table->string('iec_printing_pcs');
            $table->string('iec_printing_remarks');
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
        Schema::dropIfExists('iec_printing_logs');
    }
}

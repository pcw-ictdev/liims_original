<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIecStockUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iec_stock_updates', function (Blueprint $table) {
            $table->id();
            $table->string('iec_update_id');
            $table->string('iec_update_title');
            $table->string('iec_update_threshold');
            $table->string('iec_update_type');
            $table->string('iec_update_pieces');
            $table->string('iec_update_remarks');
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
        Schema::dropIfExists('iec_stock_updates');
    }
}

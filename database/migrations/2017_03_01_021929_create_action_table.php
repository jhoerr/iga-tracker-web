<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Action', function (Blueprint $table) {
            $table->increments('Id');
            $table->text('Link', 256);
            $table->text('Description', 256);
            $table->date('Date');
            $table->tinyInteger('Chamber');
            $table->tinyInteger('ActionType');
            $table->timestamp('Created')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('BillId')->unsigned()->nullable();
            $table->foreign('BillId')->references('Id')->on('Bill');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Action');
    }
}

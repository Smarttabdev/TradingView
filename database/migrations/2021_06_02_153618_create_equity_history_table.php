<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquityHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_equity_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_id');
            $table->double('equity');
            $table->double('max_equity');
            $table->double('min_equity');
            $table->double('openposition_profit');
            $table->double('dayprofit');
            $table->double('balance');
            $table->double('drawdown');
            $table->double('month');
            $table->double('year');
            $table->double('lastMonth');
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
        Schema::dropIfExists('tbl_equity_history');
    }
}
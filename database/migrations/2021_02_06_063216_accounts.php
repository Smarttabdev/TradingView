<?php

use App\Accounts as AccountModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Accounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_account', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_number');
            $table->string('broker');
            $table->integer('account_type')->default(0);
            $table->string('strategy_name');
            $table->enum('trading_style', ['Trend following', 'Breakout', 'High Frequency', 'Discretionary', 'Systematic', 'Intraday', 'Mean Reversion', 'Fundamental']);
            $table->integer('subscription_fee');
            $table->integer('performance_fee');
            $table->string('description');
            $table->double('miniSize');
            $table->enum('status', [AccountModel::STATUS_NONE, AccountModel::STATUS_COPY, AccountModel::STATUS_PROVIDE])
                ->default(AccountModel::STATUS_NONE);
            $table->integer('authorization')->nullable();
            $table->timestamp('expiry')->nullable();
            $table->integer('online_status')->nullable();
            $table->double('lots_traded')->nullable();
            $table->date('inception_date');
            $table->integer('strategy_of_month')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('tbl_account');
    }
}
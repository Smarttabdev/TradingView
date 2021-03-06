<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email', 191)->unique();
            $table->integer('isCanContact')->default(0);
            $table->integer('experience_year');
            $table->string('experience_history');
            $table->timestamp('email_verified_at')->nullable();
            $table->string("client_id");
            $table->string("client_secret");
            $table->string('phone')->default('');
            $table->string('country')->default('');
            $table->string('flag')->default('');
            $table->string('address')->default('');
            $table->string('zipcode')->default('');
            $table->string('avatar')->default('');
            $table->integer('active')->default(0);
            $table->integer('level')->default(0);
            $table->integer('group');
            $table->text('roles')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->integer('access_right')->default(0);
            $table->integer('created_by')->nullable();
            $table->string('password');
            $table->text('api_token')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('tbl_users');
    }
}
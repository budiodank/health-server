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
        Schema::create('users', function (Blueprint $table) {
            $table->string('user_id',10)->primary();
            $table->string('name');
            $table->string('telephone',15);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('street');
            $table->string('village');
            $table->string('city');
            $table->string('province');
            $table->string('country');
            $table->string('weight',3);
            $table->string('height',3);
            $table->string('disease'); //['ASTHMA', 'TBC', 'COVID-19']
            $table->string('snore',1);
            $table->string('access',6);
            $table->string('active',1);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

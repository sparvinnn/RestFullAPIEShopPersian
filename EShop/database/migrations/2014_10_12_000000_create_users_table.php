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
        Schema::create('counties', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('county_id');
            $table->foreign('county_id')->references('id')->on('counties')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('phones')->nullable();
            $table->string('county')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('fax')->nullable();
            $table->string('status')->default('active'); // active or inactive
            $table->softDeletes();
            $table->timestamps();
        });

//        Schema::create('roles', function (Blueprint $table) {
//            $table->id();
//            $table->string('name')->unique();
//            $table->softDeletes();
//            $table->timestamps();
//        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique()->nullable();
            $table->string('f_name')->nullable();
            $table->string('l_name')->nullable();
            $table->string('mobile')->unique();
            $table->string('national_code')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->string('county')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('avatar')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
//            $table->unsignedBigInteger('role_id')->default('4');
//            $table->foreign('role_id')->references('id')->on('roles')
//                ->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreign('branch_id')->references('id')->on('branches')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->string('status')->default('active'); // active or inactive
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
        Schema::dropIfExists('branches');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');
    }
}

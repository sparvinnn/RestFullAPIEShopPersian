<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_fa');
            $table->string('name_en')->nullable();
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('categories')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('category_id_giv')->nullable();
            $table->unsignedBigInteger('category_code_giv')->nullable();
            $table->unsignedBigInteger('parent_category_code_giv')->nullable();
            $table->boolean('category_is_active_giv')->nullable();
            $table->unsignedInteger('level_giv')->nullable();
            $table->date('last_date_giv')->nullable();
            $table->boolean('is_active')->default(0);
            $table->integer('priority')->nullable();
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
        Schema::dropIfExists('categories');
    }
}

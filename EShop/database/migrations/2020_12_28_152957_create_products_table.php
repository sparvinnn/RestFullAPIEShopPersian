<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sell_price');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('inventory_number')->default(0);
            $table->integer('total_number')->default(0);
            $table->integer('sales_number')->default(0);
            $table->integer('rate')->default(0);
            $table->integer('vote')->default(0);
            $table->dateTime('last_date_giv');
            $table->integer('item_code_giv');
            $table->integer('item_group_giv');
            $table->integer('item_parent_id_giv');
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
        Schema::dropIfExists('products');
    }
}

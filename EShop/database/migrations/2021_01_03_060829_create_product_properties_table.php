<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('size_id')->nullable();
            $table->foreignId('material_id')->nullable();
            $table->foreignId('color_id')->nullable();
            $table->foreignId('design_id')->nullable();
            $table->foreignId('alessve_id')->nullable();
            $table->foreignId('piece_id')->nullable();
            $table->foreignId('set_type_id')->nullable();
            $table->foreignId('description_id')->nullable();
            $table->foreignId('maintenance_id')->nullable();
            $table->foreignId('made_in_id')->nullable();
            $table->foreignId('origin_id')->nullable();
            $table->foreignId('type_id')->nullable();
            $table->foreignId('for_use_id')->nullable();
            $table->foreignId('collar_id')->nullable();
            $table->foreignId('height_id')->nullable();
            $table->foreignId('physical_feature_id')->nullable();
            $table->foreignId('production_time_id')->nullable();
            $table->foreignId('demension_id')->nullable();
            $table->foreignId('crotch_id')->nullable();
            $table->foreignId('close_id')->nullable();
            $table->foreignId('drop_id')->nullable();
            $table->foreignId('cumin_id')->nullable();
            $table->foreignId('close_shoes_id')->nullable();
            $table->integer('inventory_number')->default(0);
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
        Schema::dropIfExists('product_properties');
    }
}

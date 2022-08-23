<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnToProductProperties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_properties', function (Blueprint $table) {
            $table->foreignId('size_id')->nullable()->constrained();
            $table->foreignId('material_id')->nullable()->constrained();
            $table->foreignId('color_id')->nullable()->constrained();
            $table->foreignId('design_id')->nullable()->constrained();
            $table->foreignId('sleeve_id')->nullable()->constrained();
            $table->foreignId('piece_id')->nullable()->constrained();
            $table->foreignId('set_type_id')->nullable()->constrained();
            $table->foreignId('maintenance_id')->nullable()->constrained();
            $table->foreignId('made_in_id')->nullable()->constrained();
            $table->foreignId('origin_id')->nullable()->constrained();
            $table->foreignId('type_id')->nullable()->constrained();
            $table->foreignId('for_use_id')->nullable()->constrained();
            $table->foreignId('collar_id')->nullable()->constrained();
            $table->foreignId('height_id')->nullable()->constrained();
            $table->foreignId('physical_feature_id')->nullable()->constrained();
            $table->foreignId('demension_id')->nullable()->constrained();
            $table->foreignId('crotch_id')->nullable()->constrained();
            $table->foreignId('close_id')->nullable()->constrained();
            $table->foreignId('drop_id')->nullable()->constrained();
            $table->foreignId('cumin_material_id')->nullable()->constrained();
            $table->foreignId('close_shoe_id')->nullable()->constrained();
            $table->foreignId('typeـofـclothing_id')->nullable()->constrained('type_of_clothing');
            $table->foreignId('outerـpocket_id')->nullable()->constrained('outer_pockets');
            $table->foreignId('inner_pocket_id')->nullable()->constrained('inner_pockets');
            $table->foreignId('bag_handle_id')->nullable()->constrained('bag_handles');
            $table->foreignId('shower_strap_id')->nullable()->constrained('shower_straps');
            $table->foreignId('top_material_id')->nullable()->constrained('top_materials');
            $table->foreignId('heel_id')->nullable()->constrained('heels');
            $table->foreignId('bag_model_id')->nullable()->constrained('bag_models');
            $table->boolean('is_active')->default(1);
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
        Schema::table('product_properties', function (Blueprint $table) {
            //
        });
    }
}

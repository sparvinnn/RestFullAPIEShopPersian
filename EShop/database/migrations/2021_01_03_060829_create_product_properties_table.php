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

            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreign('branch_id')->references('id')->on('products')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->string('size')->nullable();//اندازه
            $table->string('material')->nullable();//جنس
            $table->string('color')->nullable();//رنگ
            $table->string('design')->nullable();//طرح
            $table->string('sleeve')->nullable();//آستین
            $table->string('piece')->nullable();//تعداد تکه
            $table->string('set_type')->nullable();//نوع ست
            $table->string('description')->nullable();//توضیحات
            $table->string('maintenance')->nullable();//نگهداری
            $table->string('made_in')->nullable();//تولید کننده
            $table->string('origin')->nullable();//مبدا
            $table->string('type')->nullable();//نوع
            $table->string('for_use')->nullable();//استفاده برای
            $table->string('collar')->nullable();//یقه
            $table->string('height')->nullable();//قد
            $table->string('physical_feature')->nullable();//ویژگی های ظاهری
            $table->string('production_time')->nullable();//زمان تولید
            $table->string('demension')->nullable();
            $table->string('crotch')->nullable();//فاق
            $table->string('close')->nullable();//بسته شدن
            $table->string('drop')->nullable();//دراپ
            $table->string('cumin')->nullable();//زیره
            $table->string('close_shoes')->nullable();//نوع بستن کفش
            $table->string('typeـofـclothing')->nullable();//نوع لباس 
            $table->string('specialized_features')->nullable();//ویژگی ها تخصصی
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

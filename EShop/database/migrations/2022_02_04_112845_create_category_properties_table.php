<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');//دسته بندی
            $table->boolean('size')->nullable();//اندازه
            $table->boolean('material')->nullable();//جنس
            $table->boolean('color')->nullable();//رنگ
            $table->boolean('design')->nullable();//طرح
            $table->boolean('sleeve')->nullable();//آستین
            $table->boolean('piece')->nullable();//تعداد تکه
            $table->boolean('set_type')->nullable();//نوع ست
            $table->boolean('description')->nullable();//توضیحات
            $table->boolean('maintenance')->nullable();//نگهداری
            $table->boolean('made_in')->nullable();//تولید کننده
            $table->boolean('origin')->nullable();//مبدا
            $table->boolean('type')->nullable();//نوع
            $table->boolean('for_use')->nullable();//استفاده برای
            $table->boolean('collar')->nullable();//یقه
            $table->boolean('height')->nullable();//قد
            $table->boolean('physical_feature')->nullable();//ویژگی های ظاهری
            $table->boolean('production_time')->nullable();//زمان تولید
            $table->boolean('demension')->nullable();
            $table->boolean('crotch')->nullable();//فاق
            $table->boolean('close')->nullable();//بسته شدن
            $table->boolean('drop')->nullable();//دراپ
            $table->boolean('cumin')->nullable();//زیره
            $table->boolean('close_shoes')->nullable();//نوع بستن کفش
            $table->boolean('typeـofـclothing')->nullable();//نوع لباس 
            $table->boolean('specialized_features')->nullable();//ویژگی ها تخصصی
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
        Schema::dropIfExists('category_properties');
    }
}

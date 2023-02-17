<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders'); //таблица связей orders, products
            $table->foreignId('product_id')->constrained('products');

            $table->unsignedMediumInteger('quantity'); //кол тов
            $table->unsignedFloat('single_price'); //цена за 1 на момент создания

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
        Schema::dropIfExists('order_product');
    }
};

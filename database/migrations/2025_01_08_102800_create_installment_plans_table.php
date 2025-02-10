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
        Schema::create('installment_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->string('city')->nullable();
            $table->string('Product_name');
            $table->string('Product_model');
            $table->decimal('product_price', 10, 2);
            $table->decimal('advance');
            $table->decimal('percentage', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2);
            $table->decimal('remaining_amount', 10, 2);
            $table->decimal('installment_price', 10, 2);
            $table->integer('installment_duration');
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
        Schema::dropIfExists('installment_plans');
    }
};

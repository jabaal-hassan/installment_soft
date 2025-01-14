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
        Schema::create('customer_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installment_plan_id')->constrained('installment_plans')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('product_name');
            $table->decimal('product_price', 10, 2);
            $table->integer('installment_duration');
            $table->decimal('installment_price', 10, 2);
            $table->decimal('installment_total_price', 10, 2);
            $table->decimal('remaining_amount', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0); // New column for amount paid by the customer
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
        Schema::dropIfExists('customer_accounts');
    }
};

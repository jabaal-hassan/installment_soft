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
        Schema::create('installment_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('customer_account_id')->constrained('customer_accounts')->onDelete('cascade');
            $table->foreignId('recived_officer_id')->constrained('employees')->onDelete('cascade');
            $table->string('product_name');
            $table->string('status');
            $table->date('installment_date')->nullable();
            $table->decimal('installment_price', 10, 2);
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
        Schema::dropIfExists('installment_tables');
    }
};

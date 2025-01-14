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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name');
            $table->foreignId('sell_officer_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('inquiry_officer_id')->constrained('employees')->onDelete('cascade')->nullable();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('phone_number')->unique();
            $table->string('cnic')->unique();
            $table->string('address');
            $table->string('office_address');
            $table->string('employment_type')->default('company');
            $table->string('company_name')->nullable();
            $table->integer('years_of_experience')->default(0);
            $table->string('cnic_Front_image');
            $table->string('cnic_Back_image');
            $table->string('customer_image');
            $table->string('check_image')->nullable();
            $table->string('video')->nullable();
            $table->string('finger_print')->nullable();
            $table->string('status')->default('processing');
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
        Schema::dropIfExists('customers');
    }
};

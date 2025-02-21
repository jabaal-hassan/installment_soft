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
        Schema::create('granters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete(); // Relationship with customers
            $table->string('name');
            $table->string('father_name');
            $table->string('cnic');
            $table->string('phone_number');
            $table->string('relationship'); // E.g., Friend, Family, etc.
            $table->text('address')->nullable();
            $table->string('office_address')->nullable();
            $table->string('employment_type')->default('company');
            $table->string('company_name')->nullable();
            $table->integer('years_of_experience')->default(0);
            $table->string('cnic_Front_image');
            $table->string('cnic_Back_image');
            $table->string('video')->nullable();
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
        Schema::dropIfExists('granters');
    }
};

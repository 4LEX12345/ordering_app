<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('business_tax_id');
            $table->string('contact_person')->nullable();
            $table->string('contact_person_contact_number')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->longText('billing_address');
            $table->longText('shipping_address');
            $table->string('wholesale_account_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

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
        Schema::create('order_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('restrict')->onUpdate('cascade');
            $table->date('order_date');
            $table->decimal('total_amount', 13,2);
            $table->string('payment_status');
            $table->string('payment_method');
            $table->string('delivery_status');
            $table->string('tracking_number');
            $table->timestamps();

            // $table->enum('payment_status', [
            //     'Pending',
            //     'Paid',
            //     'Failed',
            //     'Refunded',
            //     'Cancelled',
            //     'Authorized',
            //     'Captured',
            //     'On Hold',
            //     'Disputed',
            //     'Completed'
            // ]);
            // $table->enum('payment_method', [
            //     'Credit Card',
            //     'Debit Card',
            //     'Bank Transfer',
            //     'Cash on Delivery',
            // ]);
            // $table->enum('delivery_status', [
            //     'Pending',
            //     'Shipped',
            //     'In Transit',
            //     'Delivered',
            //     'Returned',
            //     'Cancelled',
            //     'On Hold',
            //     'Out for Delivery'
            // ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_headers');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('card_number', 4)->nullable(); // Son 4 hane
            $table->string('card_holder_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('status'); // success, failed, pending
            $table->string('transaction_id')->nullable();
            $table->string('auth_code')->nullable();
            $table->string('host_ref_num')->nullable();
            $table->string('proc_return_code')->nullable();
            $table->text('error_message')->nullable();
            $table->json('raw_response')->nullable();
            $table->string('ip_address');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
}; 
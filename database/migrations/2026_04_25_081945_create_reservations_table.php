<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email');
            $table->string('vehicle_type'); // mobil, motor, dll
            $table->string('car_brand');
            $table->string('plate_number');
            $table->date('reservation_date');
            $table->time('reservation_time');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending','confirmed','in_progress','completed','cancelled'])->default('pending');
            $table->boolean('use_free_wash')->default(false);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
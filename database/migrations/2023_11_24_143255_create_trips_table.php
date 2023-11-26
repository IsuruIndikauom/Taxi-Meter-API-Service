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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->dateTime('last_update_time');
            $table->decimal('start_latitude', 18, 15);
            $table->decimal('start_longitude', 18, 15);
            $table->decimal('end_latitude', 18, 15)->nullable();
            $table->decimal('end_longitude',18, 15)->nullable();
            $table->decimal('last_latitude', 18, 15);
            $table->decimal('last_longitude', 18, 15);
            $table->decimal('fix_rate', 10, 2);
            $table->decimal('rate_per_km', 10, 2);
            $table->decimal('rate_per_minute', 10, 2);
            $table->decimal('total_tarrif', 10, 2);
            $table->decimal('distance_tarrif', 10, 2);
            $table->decimal('waiting_tarrif', 10, 2);
            $table->decimal('ride_distance', 10, 2);
            $table->integer('total_waiting_time');
            $table->decimal('ride_speed', 10, 2);
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};

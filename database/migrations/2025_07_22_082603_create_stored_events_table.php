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
       Schema::create('stored_events', function (Blueprint $table) {
            $table->id();
            $table->uuid('aggregate_id'); // task ID
            $table->string('aggregate_type'); // Task::class
            $table->string('event_type');     // TaskWasCreated::class
            $table->json('payload');
            $table->timestamp('occurred_at');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stored_events');
    }
};

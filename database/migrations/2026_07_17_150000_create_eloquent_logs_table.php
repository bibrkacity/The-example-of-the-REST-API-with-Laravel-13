<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('eloquent_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('model');
            $table->string('model_id');
            $table->string('command', 7);
            $table->text('raw_sql');
            $table->text('attributes_before');
            $table->text('attributes_after');
            $table->timestamps();
            $table->engine('ARCHIVE');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eloquent_logs');
    }
};

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
        Schema::table('solutions', function (Blueprint $table) {
            $table->integer('time_taken')->nullable(); // In seconds
            $table->string('memory_used')->nullable(); 
        });

        Schema::table('problems', function (Blueprint $table) {
            $table->text('hint')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('total_points')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solutions', function (Blueprint $table) {
            $table->dropColumn(['time_taken', 'memory_used']);
        });

        Schema::table('problems', function (Blueprint $table) {
            $table->dropColumn('hint');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('total_points');
        });
    }
};

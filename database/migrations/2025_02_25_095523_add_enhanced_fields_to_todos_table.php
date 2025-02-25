<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id'); // For ownership
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('priority')->default('medium')->after('description'); // low, medium, high
            $table->dateTime('due_date')->nullable()->after('priority'); // Deadline
            $table->string('category')->nullable()->after('due_date'); // e.g., Work, Personal
            $table->text('notes')->nullable()->after('category'); // Additional details
        });
    }

    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'priority', 'due_date', 'category', 'notes']);
        });
    }
};
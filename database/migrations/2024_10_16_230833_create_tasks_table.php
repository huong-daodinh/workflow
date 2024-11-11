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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('priority')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('duel_date')->nullable();
            $table->string('status')->default('doing');
            $table->text('description')->nullable();
            $table->foreignId('assignee_id')->constrained(table: 'users', column: 'id');
            $table->foreignId('assigner_id')->constrained(table: 'users', column: 'id');
            $table->foreignId('task_list_id')->constrained(table: 'task_lists', column: 'id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

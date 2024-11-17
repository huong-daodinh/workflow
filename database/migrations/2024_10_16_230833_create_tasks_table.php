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
            $table->string('title');
            $table->integer('priority')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->string('status')->default('doing');
            $table->text('description');
            $table->text('result')->nullable();
            $table->foreignId('assignee_id')->constrained(table: 'users', column: 'id');
            $table->foreignId('assigner_id')->constrained(table: 'users', column: 'id');
            $table->foreignId('task_list_id')->nullable()->constrained(table: 'task_lists', column: 'id');
            $table->foreignId('project_id')->nullable()->constrained();
            $table->foreignId('created_by')->constrained(table: 'users', column: 'id');
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

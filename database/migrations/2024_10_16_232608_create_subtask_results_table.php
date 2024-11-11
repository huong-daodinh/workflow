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
        Schema::create('subtask_results', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->foreignId('subtask_id')->constrained();
            // join voi task -> assignee la bt dc ai tao
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subtask_results');
    }
};

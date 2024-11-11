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
        Schema::create('project_conversation', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->foreignId('sent_by')->constrained(table: 'users', column: 'id');
            $table->foreignId('project_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_conversation');
    }
};

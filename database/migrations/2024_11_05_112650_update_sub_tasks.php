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
      Schema::table('subtasks', function (Blueprint $table) {
        $table->foreignId('created_by')->constrained('users');
        $table->renameColumn('duel_date', 'due_date');
        $table->renameColumn('name', 'title');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

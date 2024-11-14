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
      Schema::dropIfExists('task_attachments');
      Schema::create('task_attachments', function (Blueprint $table) {
        $table->id();
        $table->string('url');
        $table->foreignId('task_id')->nullable()->constrained();
        $table->string('slug');
        $table->softDeletes();
        $table->timestamps();
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

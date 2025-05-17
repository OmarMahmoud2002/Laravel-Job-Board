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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
        
            // keep it strictly 1-to-1
            $table->foreignId('employer_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('candidate_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
        
            // optional: link to a specific job application
            $table->foreignId('job_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();
        
            $table->timestamps();
        
            // prevent duplicate conversations
            $table->unique(['employer_id','candidate_id','job_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};

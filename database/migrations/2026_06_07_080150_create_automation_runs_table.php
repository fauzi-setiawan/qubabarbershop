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
        Schema::create('automation_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('run_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('repo_url');
            $table->string('branch');
            $table->enum('environment', ['Development', 'Staging', 'Production']);
            $table->integer('total_pass')->default(0);
            $table->integer('total_fail')->default(0);
            $table->integer('total_error')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('automation_runs');
    }
};

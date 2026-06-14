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
        Schema::create('bug_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_execution_id')->constrained('test_executions')->onDelete('cascade');
            $table->foreignId('reported_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('trello_card_id');
            $table->string('trello_card_url');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('severity', ['Critical', 'Major', 'Minor']);
            $table->string('status')->default('Open');
            $table->string('current_trello_list')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bug_reports');
    }
};

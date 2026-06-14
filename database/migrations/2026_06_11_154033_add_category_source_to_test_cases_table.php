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
        Schema::table('test_cases', function (Blueprint $table) {
            $table->enum('category', [
                'Positive Test',
                'Negative Test',
                'Boundary Test',
                'Validation Test',
                'UI/UX Test'
            ])->nullable()->after('priority');

            $table->enum('source', ['manual', 'ai'])->default('manual')->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_cases', function (Blueprint $table) {
            //
        });
    }
};

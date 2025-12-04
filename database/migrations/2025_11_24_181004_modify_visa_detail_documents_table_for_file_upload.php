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
        Schema::table('visa_detail_documents', function (Blueprint $table) {
            // Make description nullable
            $table->longText('description')->nullable()->change();

            // Rename link column to file
            $table->renameColumn('link', 'file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visa_detail_documents', function (Blueprint $table) {
            // Rename file column back to link
            $table->renameColumn('file', 'link');

            // Make description not nullable again
            $table->longText('description')->nullable(false)->change();
        });
    }
};

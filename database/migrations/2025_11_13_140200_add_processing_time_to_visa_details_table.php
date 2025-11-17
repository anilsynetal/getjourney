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
        Schema::table('visa_details', function (Blueprint $table) {
            $table->string('processing_time', 255)->nullable()->after('logistic_charges')->comment('Processing time for visa (e.g., 5-7 business days)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visa_details', function (Blueprint $table) {
            $table->dropColumn('processing_time');
        });
    }
};

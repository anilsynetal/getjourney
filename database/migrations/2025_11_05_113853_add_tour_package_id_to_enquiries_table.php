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
        Schema::table('enquiries', function (Blueprint $table) {
            $table->bigInteger('tour_package_id')->unsigned()->nullable()->after('service_id');
            $table->foreign('tour_package_id')->references('id')->on('tour_packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropForeign(['tour_package_id']);
            $table->dropColumn('tour_package_id');
        });
    }
};

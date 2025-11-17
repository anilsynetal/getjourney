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
            // Add type field to categorize enquiries (visa_information, tour, service, etc)
            $table->string('type', 50)->nullable()->after('message');

            // Add country_id for visa-related enquiries
            $table->bigInteger('country_id')->unsigned()->nullable()->after('type');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');

            // Add status to track enquiry progress (pending, contacted, resolved, etc)
            $table->string('status', 50)->default('pending')->after('country_id');

            // Add tour_package_id if not already present
            if (!Schema::hasColumn('enquiries', 'tour_package_id')) {
                $table->bigInteger('tour_package_id')->unsigned()->nullable()->after('service_id');
                $table->foreign('tour_package_id')->references('id')->on('tour_packages')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            if (Schema::hasColumn('enquiries', 'tour_package_id')) {
                $table->dropForeign(['tour_package_id']);
                $table->dropColumn('tour_package_id');
            }
            if (Schema::hasColumn('enquiries', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('enquiries', 'country_id')) {
                $table->dropForeign(['country_id']);
                $table->dropColumn('country_id');
            }
            if (Schema::hasColumn('enquiries', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};

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
        Schema::create('boat_widget_enquiries', function (Blueprint $table) {
            $table->id();

            // Basic Contact Information
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            // Personal Information
            $table->integer('age')->nullable();
            $table->string('nationality')->nullable();
            $table->string('qualification')->nullable();
            $table->integer('work_experience')->nullable();
            $table->string('current_occupation')->nullable();
            $table->string('company')->nullable();

            // Visa Information
            $table->string('destination_country')->nullable();
            $table->string('visa_type')->nullable();
            $table->string('purpose')->nullable();
            $table->string('travel_date')->nullable();
            $table->string('duration')->nullable();

            // Travel History & Family
            $table->text('previous_visas')->nullable();
            $table->string('family_status')->nullable();

            // Financial & Additional
            $table->text('assets')->nullable();
            $table->longText('additional_info')->nullable();

            // Compiled message (all answers combined)
            $table->longText('message')->nullable();

            // Admin Management Fields
            $table->enum('status', ['new', 'read', 'responded', 'closed'])->default('new');
            $table->longText('admin_response')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->longText('internal_notes')->nullable();

            // Tracking
            $table->string('user_ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('email');
            $table->index('visa_type');
            $table->index('created_at');
            $table->index('assigned_to');

            // Foreign key
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boat_widget_enquiries');
    }
};

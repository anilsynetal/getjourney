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
        Schema::create('visa_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->string('city', 150);
            $table->foreignId('visa_category_id')->constrained('visa_categories')->onDelete('cascade');
            $table->longText('visa_fees')->nullable()->comment('TinyEditor content');
            $table->longText('logistic_charges')->nullable()->comment('TinyEditor content');
            $table->boolean('status')->default(1);

            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->ipAddress('created_by_ip')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->ipAddress('updated_by_ip')->nullable();
            $table->unsignedBigInteger('status_updated_by')->nullable();
            $table->ipAddress('status_updated_by_ip')->nullable();

            $table->timestamps();

            // Foreign keys for audit fields
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('status_updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_details');
    }
};

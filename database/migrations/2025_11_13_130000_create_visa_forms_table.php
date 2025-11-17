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
        Schema::create('visa_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->string('city', 150);
            $table->foreignId('visa_category_id')->constrained('visa_categories')->onDelete('cascade');
            $table->string('visa_form')->nullable()->comment('PDF file path');
            $table->longText('application_form_url')->nullable();
            $table->boolean('status')->default(1);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->ipAddress('created_by_ip')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->ipAddress('updated_by_ip')->nullable();
            $table->foreignId('status_updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->ipAddress('status_updated_by_ip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_forms');
    }
};

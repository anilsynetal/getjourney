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
        Schema::create('visa_detail_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visa_detail_id')->constrained('visa_details')->onDelete('cascade');
            $table->string('title', 255);
            $table->longText('description')->nullable();
            $table->string('link', 500)->nullable();
            $table->boolean('status')->default(1);

            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->ipAddress('created_by_ip')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->ipAddress('updated_by_ip')->nullable();

            $table->timestamps();

            // Foreign keys for audit fields
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_detail_documents');
    }
};

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
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 100);
            $table->string('mobile', 20);
            $table->string('subject', 255)->nullable();
            $table->text('message')->nullable();
            $table->bigInteger('tour_id')->unsigned()->nullable();
            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');
            $table->bigInteger('service_id')->unsigned()->nullable();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->string('ip_address', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};

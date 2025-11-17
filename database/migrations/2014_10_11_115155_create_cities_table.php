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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('city', 100);
            $table->unsignedBigInteger('state_id');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->string('state_name', 100)->nullable();
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->string('country_name', 100)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};

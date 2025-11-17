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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->unique();
            $table->text('description')->nullable();
            $table->string('button_text', 255)->nullable();
            $table->string('button_link', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->bigInteger('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->bigInteger('status_updated_by')->unsigned()->nullable();
            $table->foreign('status_updated_by')->references('id')->on('users');
            $table->string('created_by_ip', 50)->nullable();
            $table->string('updated_by_ip', 50)->nullable();
            $table->string('status_updated_by_ip', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};

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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('address', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->text('map')->nullable();
            $table->string('sunday', 100)->nullable();
            $table->string('monday', 100)->nullable();
            $table->string('tuesday', 100)->nullable();
            $table->string('wednesday', 100)->nullable();
            $table->string('thursday', 100)->nullable();
            $table->string('friday', 100)->nullable();
            $table->string('saturday', 100)->nullable();
            $table->string('facebook', 255)->nullable();
            $table->string('twitter', 255)->nullable();
            $table->string('linkedin', 255)->nullable();
            $table->string('instagram', 255)->nullable();
            $table->string('youtube', 255)->nullable();
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
        Schema::dropIfExists('contacts');
    }
};

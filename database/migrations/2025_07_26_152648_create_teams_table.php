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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('employee_id', 50)->unique()->nullable();
            $table->string('email', 100)->unique();
            $table->string('mobile', 20)->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('designation', 100)->nullable();
            $table->string('linkedin', 255)->nullable();
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
            $table->softDeletes();
            $table->unique(['email', 'deleted_at'], 'unique_email_deleted_at'); //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};

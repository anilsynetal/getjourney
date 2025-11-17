<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('full_name')->virtualAs('CONCAT(first_name, " ", last_name)')->nullable();
            $table->string('unique_code', 20)->nullable();
            $table->string('username', 20)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('company_name', 100)->nullable();
            $table->string('company_website', 100)->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('password');
            $table->string('image')->nullable();
            $table->tinyInteger('is_password_updated')->default(0);
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->string('address', 100)->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->unsignedBigInteger('state_id')->nullable();
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->string('designation', 100)->nullable();
            $table->rememberToken();
            $table->tinyInteger('status')->default(1);
            $table->bigInteger('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->bigInteger('status_updated_by')->unsigned()->nullable();
            $table->foreign('status_updated_by')->references('id')->on('users');
            $table->string('deleted_by_ip', 50)->nullable();
            $table->string('created_by_ip', 50)->nullable();
            $table->string('updated_by_ip', 50)->nullable();
            $table->string('status_updated_by_ip', 50)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['email', 'deleted_at']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

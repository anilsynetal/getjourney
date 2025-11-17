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
        Schema::create('sub_menus', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('main_menu_id')->unsigned();
            $table->foreign('main_menu_id')->references('id')->on('main_menus');
            $table->string('language_key', 100);
            $table->string('menu_name', 100);
            $table->string('menu_icon', 100)->nullable();
            $table->string('route_name', 100)->nullable();
            $table->string('table_name', 100)->nullable();
            $table->text('permissions')->nullable();
            $table->integer('order')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->bigInteger('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->bigInteger('created_by')->unsigned();
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_menus');
    }
};

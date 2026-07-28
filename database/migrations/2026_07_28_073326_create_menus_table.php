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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->string('icon')->nullable();
            $table->string('route')->nullable();
            $table->string('parent_key')->nullable();
            $table->unsignedSmallInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('menu_role', function (Blueprint $table) {
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->string('role_name');
            $table->primary(['menu_id', 'role_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_role');
        Schema::dropIfExists('menus');
    }
};

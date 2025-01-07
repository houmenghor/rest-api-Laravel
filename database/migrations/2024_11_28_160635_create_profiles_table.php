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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->unique(); // one to one relationship
            $table->string("phone")->nullable();
            $table->string("address")->nullable();
            $table->string("image")->nullable();
            $table->string("type")->nullable();
            // Foreign Key Contraint
            $table->foreign("user_id")->references('id')->on('users')->onDelete('cascade'); // cascade
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};

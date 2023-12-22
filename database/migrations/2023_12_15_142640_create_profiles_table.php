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
            $table->string('phone_number')->nullable()->default(0);
            $table->bigInteger('user_id')->unique()->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('expert_id')->nullable()->unsigned()->default(0);
            $table->foreign('expert_id')->references('id')->on('experts');
            $table->bigInteger('province_id')->nullable()->unsigned()->default(0);
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->string('address');
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

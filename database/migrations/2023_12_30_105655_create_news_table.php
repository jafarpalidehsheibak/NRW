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
//        news_title
//news_date
//news_short_description
//news_long_description
//news_image
//news_visit_count
//news_status

        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->dateTime('news_date');
            $table->string('news_title');
            $table->string('news_short_description',500);
            $table->string('news_long_description',5000);
            $table->string('news_image');
            $table->unsignedInteger('news_visit_count')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};

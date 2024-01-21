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
        Schema::create('contractor_requests', function (Blueprint $table) {
            $table->id();
            $table->string('contractor_name')->comment('نام پیمانکار');
            $table->unsignedTinyInteger('contractor_rank')->comment('رتبه پیمانکار');
            $table->bigInteger('province_id')->unsigned()->comment('آی دی استان');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->bigInteger('city_id')->unsigned()->comment('آی دی شهر');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->string('road_name')->comment('نام راه');
            $table->bigInteger('expert_id')->unsigned()->comment('رشته');
            $table->foreign('expert_id')->references('id')->on('experts');
            $table->unsignedInteger('workshop_location_kilometers')->comment('محل کارگاه (کیلومتراژ)');
            $table->string('workshop_begin_lat_long')->comment('لوکیشن شروع کارگاه');
            $table->string('workshop_end_lat_long')->comment('لوکیشن پایان کارگاه');
            $table->string('workshop_name')->comment('نام کارگاه');
            $table->string('full_name_connector')->comment('نام کامل رابط');
            $table->string('mobile_connector')->nullable()->comment('موبایل  رابط');
            $table->string('email_connector')->nullable()->comment(' ایمیل رابط');
            $table->date('approximate_start_date')->comment('تاریخ تقریبی شروع');
            $table->unsignedInteger('workshop_duration')->comment('مدت زمان اجرای کارگاه(تعداد روز)');
            $table->string('description')->nullable()->comment('توضیحات');
            $table->unsignedTinyInteger('status')->default('0')->comment('وضعیت');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractor_requests');
    }
};

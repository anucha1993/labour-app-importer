<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('action_logs')) {
            Schema::create('action_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('ID ผู้ใช้งานที่ทำ action');
            $table->string('action_type', 50)->comment('เช่น created, updated, deleted');
            $table->string('model_type', 100)->comment('ชื่อ Model ที่ถูกกระทำ เช่น App\\Models\\labour\\LabourModel');
            $table->unsignedBigInteger('model_id')->comment('Primary key ของเรคคอร์ดนั้น ๆ (labour_id)');
            $table->json('old_values')->nullable()->comment('ข้อมูลเดิมก่อนเปลี่ยน (JSON)');
            $table->json('new_values')->nullable()->comment('ข้อมูลใหม่หลังเปลี่ยน (JSON)');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_logs');
    }
};
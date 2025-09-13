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
        if (Schema::hasTable('labour_payment_histories')) {
            Schema::dropIfExists('labour_payment_histories');
        }
        if (Schema::hasTable('labour_payment_types')) {
            Schema::dropIfExists('labour_payment_types');
        }
        
        Schema::create('labour_payment_types', function (Blueprint $table) {
            $table->id('payment_type_id');
            $table->unsignedBigInteger('labour_id');
            $table->string('payment_name');
            $table->decimal('total_amount', 10, 2);
            $table->enum('deduction_type', ['salary', 'self_paid']);
            $table->enum('status', ['pending', 'partial', 'completed'])->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();

            // Foreign key จะเพิ่มทีหลัง
            // $table->foreign('labour_id')
            //       ->references('labour_id')
            //       ->on('labour')
            //       ->onDelete('cascade');
        });

        Schema::create('labour_payment_histories', function (Blueprint $table) {
            $table->id('payment_history_id');
            $table->unsignedBigInteger('payment_type_id');
            // Foreign key จะเพิ่มทีหลัง
            // $table->foreign('payment_type_id')
            //       ->references('payment_type_id')
            //       ->on('labour_payment_types')
            //       ->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->string('proof_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labour_payment_histories');
        Schema::dropIfExists('labour_payment_types');
    }
};
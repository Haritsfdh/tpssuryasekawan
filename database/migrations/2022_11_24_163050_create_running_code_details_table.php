<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('running_code_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('header_id')->nullable();
            $table->integer('date')->nullable();
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();
            $table->integer('sequence')->default(1);
            $table->timestamps();
            
            $table->index('month', 'running_code_details_month_index');
            $table->index('year', 'running_code_details_year_index');
            
            $table->foreign('header_id')->references('id')->on('running_code_headers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('running_code_details');
    }
};

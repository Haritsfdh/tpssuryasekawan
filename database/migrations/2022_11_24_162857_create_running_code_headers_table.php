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
        Schema::create('running_code_headers', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('module')->nullable();
            $table->string('name')->nullable();
            $table->text('pattern')->nullable();
            $table->set('reset', ['daily', 'month', 'year', 'never'])->default('never');
            $table->integer('leading_zero')->default(5);
            $table->timestamps();

            $table->index('module', 'running_code_header_module_index');
            $table->index('name', 'running_code_header_name_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('running_code_headers');
    }
};

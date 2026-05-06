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
    public function up()
    {
        Schema::create('glb_branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->boolean('CB_IsActive')->default(false);
            $table->string('CB_Code')->nullable();
            $table->string('CB_FullName')->nullable();
            $table->text('CB_Address')->nullable();
            $table->string('CB_Phone')->nullable();
            $table->string('CB_City')->nullable();
            $table->string('CB_WhCode')->nullable();
            $table->timestamps();
            
            $table->foreign('company_id')->references('id')->on('glb_companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tps_glb_branches');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReffCurencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('RX_Code',255)->nullable()->index();
            $table->boolean('RX_IsActive')->default(false)->index();
            $table->boolean('RX_IsSystem')->default(false);
            $table->string('RX_Symbol',255)->nullable();
            $table->string('RX_Desc',255)->nullable()->index();
            $table->string('RX_UnitName',255)->nullable();
            $table->string('RX_SubUnitName',255)->nullable();
            $table->integer('RX_SubUnitRatio')->nullable();
            $table->integer('RX_AutoVersion')->nullable();
            $table->string('RX_ISOSubUnitRatio',255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_currencies');
    }
}

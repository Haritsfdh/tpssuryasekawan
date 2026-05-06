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
        Schema::create('ref_unloco', function (Blueprint $table) {
            $table->id();
            $table->string('RL_Code',5)->nullable()->index();
            $table->boolean('RL_IsActive')->default(false)->index();
            $table->boolean('RL_IsSystem')->default(false);
            $table->boolean('RL_IsUpdatable')->default(false);
            $table->string('RL_PortName',45)->nullable()->index();
            $table->string('RL_NameWithDiacriticals',35)->nullable();
            $table->string('RL_IATA',3)->nullable();
            $table->boolean('RL_HasAirport')->default(false)->index();
            $table->boolean('RL_HasSeaport')->default(false)->index();
            $table->boolean('RL_HasRail')->default(false);
            $table->boolean('RL_HasRoad')->default(false);
            $table->boolean('RL_HasPost')->default(false);
            $table->boolean('RL_HasCustomsLodge')->default(false);
            $table->boolean('RL_HasUnload')->default(false);
            $table->boolean('RL_HasStore')->default(false);
            $table->boolean('RL_HasTerminal')->default(false);
            $table->boolean('RL_HasDischarge')->default(false);
            $table->boolean('RL_HasOutport')->default(false);
            $table->boolean('RL_HasBorderCrossing')->default(false);
            $table->string('RL_RN_NKCountryCode',2)->nullable()->index();
            $table->string('RL_IATARegionCode',3)->nullable();
            $table->string('RL_GeoLocation',255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_unloco');
    }
};

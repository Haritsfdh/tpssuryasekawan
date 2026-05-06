<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReffCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_countries', function (Blueprint $table) {
            $table->id();
            $table->integer('RN_AutoVersion')->length(2)->default(0);
            $table->string('RN_Code', 2)->nullable()->index();
            $table->boolean('RN_IsActive')->default(true)->index();
            $table->boolean('RN_IsSystem')->default(true);
            $table->string('RN_Desc', 35)->nullable();
            $table->string('RN_ValidationStatus', 3)->nullable();
            $table->string('RN_EconomicGrouping', 3)->nullable();
            $table->string('RN_CountryDialingCode', 3)->nullable();
            $table->string('RN_AddressFormattingRule', 3)->nullable();
            $table->string('RN_StateProvinceValidationRule', 3)->nullable();
            $table->string('RN_RX_NKLocalCurrency', 3)->nullable();
            $table->string('RN_RX_NKAirWaybillCurrency', 3)->nullable();
            $table->string('RN_IsoAlpha3Code', 3)->nullable();
            $table->string('RN_IsoNumericUNM49Code', 3)->nullable();
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
        Schema::dropIfExists('tps_ref_countries');
    }
}

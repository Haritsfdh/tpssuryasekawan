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
        Schema::create('glb_companies', function (Blueprint $table) {
            $table->id();
            $table->string('GC_Code')->nullable();
            $table->boolean('GC_IsActive')->default(false);
            $table->string('GC_Name')->nullable();
            $table->string('GC_BusinessRegNo')->nullable();
            $table->string('GC_BusinessRegNo2')->nullable();
            $table->string('GC_CustomsRegistrationNo')->nullable();
            $table->string('GC_TaxID')->nullable();
            $table->string('GC_Address1')->nullable();
            $table->string('GC_Address2')->nullable();
            $table->string('GC_City')->nullable();
            $table->string('GC_Phone')->nullable();
            $table->integer('GC_PostCode')->nullable();
            $table->string('GC_State')->nullable();
            $table->string('GC_Fax')->nullable();
            $table->string('GC_Email')->nullable();
            $table->string('GC_WebAddress')->nullable();
            $table->string('GC_RX_NKLocalCurrency')->nullable();
            $table->string('GC_RN_NKCountryCode')->nullable();
            $table->string('GC_AddressMap')->nullable();
            $table->string('GC_GeoLocation')->nullable();
            $table->string('GC_Logo')->nullable();
            $table->unsignedBigInteger('GC_ArControl')->nullable();
            $table->unsignedBigInteger('GC_ArControlOvs')->nullable();
            $table->unsignedBigInteger('GC_ApControl')->nullable();
            $table->unsignedBigInteger('GC_ApControlOvs')->nullable();
            $table->unsignedBigInteger('GC_WipControl')->nullable();
            $table->unsignedBigInteger('GC_AcrControl')->nullable();
            $table->unsignedBigInteger('GC_RevSuspense')->nullable();
            $table->unsignedBigInteger('GC_CostSuspense')->nullable();
            $table->unsignedBigInteger('GC_ForexGainRealized')->nullable();
            $table->unsignedBigInteger('GC_ForexLossRealized')->nullable();
            $table->unsignedBigInteger('GC_ForexGainUnrealized')->nullable();
            $table->unsignedBigInteger('GC_ForexLossUnrealized')->nullable();
            $table->unsignedBigInteger('GC_RetainedEarning')->nullable();
            $table->unsignedBigInteger('GC_CurrentEarning')->nullable();
            $table->unsignedBigInteger('GC_DiscountAccount')->nullable();
            $table->unsignedBigInteger('GC_OverpaymentAccount')->nullable();
            $table->unsignedBigInteger('GC_UnderpaymentAccount')->nullable();
            $table->unsignedBigInteger('GC_BankFee')->nullable();
            $table->unsignedBigInteger('GC_StampControl')->nullable();
            $table->unsignedBigInteger('GC_ARClearingAccount')->nullable();
            $table->unsignedBigInteger('GC_APClearingAccount')->nullable();
            $table->decimal('GC_OverpaymentLimit', 25, 2)->nullable();
            $table->decimal('GC_UnderpaymentLimit', 25, 2)->nullable();
            $table->boolean('GC_IsStampFee')->default(false);
            $table->string('GC_AirImportRecognitionDate')->nullable();
            $table->string('GC_AirExportRecognitionDate')->nullable();
            $table->string('GC_AirDomesticRecognitionDate')->nullable();
            $table->string('GC_SeaImportRecognitionDate')->nullable();
            $table->string('GC_SeaExportRecognitionDate')->nullable();
            $table->string('GC_SeaDomesticRecognitionDate')->nullable();
            $table->string('GC_RoadDomesticRecognitionDate')->nullable();
            $table->string('GC_IDR_BankName')->nullable();
            $table->string('GC_IDR_BankAddress')->nullable();
            $table->string('GC_IDR_BankSwift')->nullable();
            $table->string('GC_IDR_BankAccountName')->nullable();
            $table->string('GC_IDR_BankAccountNum')->nullable();
            $table->string('GC_USD_BankName')->nullable();
            $table->string('GC_USD_BankAddress')->nullable();
            $table->string('GC_USD_BankSwift')->nullable();
            $table->string('GC_USD_BankAccountName')->nullable();
            $table->string('GC_USD_BankAccountNum')->nullable();
            $table->boolean('GC_IsPjt')->default(false);            
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
        Schema::dropIfExists('glb_companies');
    }
};

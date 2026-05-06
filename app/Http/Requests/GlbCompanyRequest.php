<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GlbCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'GC_Code'=> 'nullable',
            'GC_IsActive'=> 'nullable',
            'GC_Name'=> 'required',
            'GC_BusinessRegNo'=> 'nullable',
            'GC_BusinessRegNo2'=> 'nullable',
            'GC_CustomsRegistrationNo'=> 'nullable',
            'GC_TaxID'=> 'nullable',
            'GC_Address1'=> 'required',
            'GC_Address2'=> 'nullable',
            'GC_City'=> 'required',
            'GC_Phone'=> 'required',
            'GC_PostCode'=> 'required',
            'GC_State'=> 'nullable',
            'GC_Fax'=> 'nullable',
            'GC_Email'=> 'nullable|email',
            'GC_WebAddress'=> 'nullable',
            'GC_RX_NKLocalCurrency'=> 'required',
            'GC_RN_NKCountryCode'=> 'required',
            'GC_AddressMap'=> 'nullable',
            'GC_GeoLocation'=> 'nullable',
            'GC_Logo'=> 'nullable|mimes:jpg,png',
            'GC_ArControl'=> 'nullable',
            'GC_ArControlOvs'=> 'nullable',
            'GC_ApControl'=> 'nullable',
            'GC_ApControlOvs'=> 'nullable',
            'GC_WipControl'=> 'nullable',
            'GC_AcrControl'=> 'nullable',
            'GC_RevSuspense'=> 'nullable',
            'GC_CostSuspense'=> 'nullable',
            'GC_ForexGainRealized'=> 'nullable',
            'GC_ForexLossRealized'=> 'nullable',
            'GC_ForexGainUnrealized'=> 'nullable',
            'GC_ForexLossUnrealized'=> 'nullable',
            'GC_RetainedEarning'=> 'nullable',
            'GC_CurrentEarning'=> 'nullable',
            'GC_DiscountAccount'=> 'nullable',
            'GC_OverpaymentAccount'=> 'nullable',
            'GC_UnderpaymentAccount'=> 'nullable',
            'GC_BankFee'=> 'nullable',
            'GC_StampControl'=> 'nullable',
            'GC_ARClearingAccount'=> 'nullable',
            'GC_APClearingAccount'=> 'nullable',
            'GC_OverpaymentLimit'=> 'nullable',
            'GC_UnderpaymentLimit'=> 'nullable',
            'GC_IsStampFee'=> 'nullable',
            'GC_AirImportRecognitionDate'=> 'nullable',
            'GC_AirExportRecognitionDate'=> 'nullable',
            'GC_AirDomesticRecognitionDate'=> 'nullable',
            'GC_SeaImportRecognitionDate'=> 'nullable',
            'GC_SeaExportRecognitionDate'=> 'nullable',
            'GC_SeaDomesticRecognitionDate'=> 'nullable',
            'GC_RoadDomesticRecognitionDate'=> 'nullable',
            'GC_IDR_BankName'=> 'nullable',
            'GC_IDR_BankAddress'=> 'nullable',
            'GC_IDR_BankSwift'=> 'nullable',
            'GC_IDR_BankAccountName'=> 'nullable',
            'GC_IDR_BankAccountNum'=> 'nullable',
            'GC_USD_BankName'=> 'nullable',
            'GC_USD_BankAddress'=> 'nullable',
            'GC_USD_BankSwift'=> 'nullable',
            'GC_USD_BankAccountName'=> 'nullable',
            'GC_USD_BankAccountNum'=> 'nullable',
            'GC_IsPjt'=> 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'GC_Name.required' => 'Company Name is required',
            'GC_Address1.required' => 'Company Address is required',
            'GC_RN_NKCountryCode.required' => 'Country is required',
            'GC_City.required' => 'City is required',
            'GC_PostCode.required' => 'Post Code is required',
            'GC_Phone.required' => 'Phone number is required',
            'GC_RX_NKLocalCurrency.required' => 'Local Currency is required',
            'GC_Logo.mimes' => 'Only jpg and png image types are allowed',
            'GC_Email.email' => 'Please input a valid email'
        ];
    }
}

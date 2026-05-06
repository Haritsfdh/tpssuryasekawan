<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManifestConsolidationRequest extends FormRequest
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
            'KPBC' => 'required',
            'mBRANCH' => 'required',
            'NPWP' => 'exclude',
            'AirlineCode' => 'required',
            'NM_SARANA_ANGKUT' => 'required',
            'FlightNo' => 'required',
            'DepartureDate' => 'nullable|date',
            'DepartureTime' => 'nullable',
            'ArrivalDate' => 'required|date',
            'ArrivalTime' => 'required',
            'Origin' => 'required',
            'Transit' => 'nullable',
            'Destination' => 'required',
            'ConsolNumber' => 'nullable',
            'MAWBNumber' => 'required|numeric',
            'MAWBDate' => 'required|date',
            'HAWBCount' => 'required|numeric',
            'mNoOfPackages' => 'nullable|numeric',
            'mGrossWeight' => 'nullable|numeric',
            'mChargeableWeight' => 'nullable|numeric',
            'Partial' => 'nullable',
            'PUNumber' => 'nullable',
            'POSNumber' => 'nullable',
            'PUDate' => 'nullable|date',
            'OriginWarehouse' => 'nullable',
            'MasukGudang' => 'nullable',
            'NO_SEGEL' => 'nullable',
        ];
    }
}

@push('styles')
<style>
    .tab-pane {
        display: none !important;
    }
    .tab-pane.active.show {
        display: block !important;
    }
</style>
@endpush


@extends('layouts.master')
@section('title')
    Main Data
@endsection
@section('page_name')
    Main Data
@endsection

@section('header')
  <style>
    ul, #myUL {
      list-style-type: none;
    }

    #myUL {
      margin: 0;
      padding: 0;
    }

    .caret {
      cursor: pointer;
      -webkit-user-select: none; /* Safari 3.1+ */
      -moz-user-select: none; /* Firefox 2+ */
      -ms-user-select: none; /* IE 10+ */
      user-select: none;
    }

    .caret::before {
      content: "\25B6";
      color: black;
      display: inline-block;
      margin-right: 6px;
    }

    .caret-down::before {
      -ms-transform: rotate(90deg); /* IE 9 */
      -webkit-transform: rotate(90deg); /* Safari */'
      transform: rotate(90deg);
    }

    .nested {
      display: none;
    }

    .active {
      display: block;
    }
    .keterangan{
      min-width: 400px !important;
    }
    .reason{
      /* width: 20px !important; */
      max-width: 200px !important;
    }
  </style>
@endsection

@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Main Data</h3>
                    </div>
                    <div class="card-body">
                        {{-- Tab List --}}
                        <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                            <li class="nav-item">
                                <a href="#main-data-content" class="nav-link active" id="main-data" role="tab"
                                   data-toggle="tab" aria-controls="main-data-content" aria-selected="true">
                                   Main Data
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-houses-content" class="nav-link" id="tab-houses" role="tab"
                                   data-toggle="tab" aria-controls="tab-houses-content" aria-selected="false">
                                   Houses
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-logs-content" class="nav-link" id="logs" role="tab"
                                   data-toggle="pill" aria-controls="tab-logs-content" aria-selected="false">
                                   Logs
                                </a>
                            </li>
                        </ul>

                        {{-- Tab Content --}}
                        <div class="tab-content" id="custom-content-above-tabContent">

                            {{-- Main Data Tab --}}
                            <div class="tab-pane fade show active" id="main-data-content" aria-labelledby="main-data">
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="card card-primary card-outline">
                                            <form id="formDetails"
                                                @if ($item->id)
                                                    action="{{ route('manifest.consolidation.update', ['consolidation' => \Crypt::encrypt($item->id)]) }}"
                                                    method="POST"
                                                @else
                                                    action="{{ route('manifest.consolidation.store') }}"
                                                    method="POST"
                                                @endif
                                                class="form-horizontal needs-validation" autocomplete="off" novalidate>
                                                @csrf
                                                @if ($item->id)
                                                    @method('PUT')
                                                @endif

                                                <div class="card-body">
                                                    <div class="form-group row">
                                                            {{-- KPBC SELECT --}}
                                                            <label for="KPBC" class="col-sm-2 col-lg-1 col-form-label">
                                                                KPBC @include('buttons.mandatory')</label>
                                                            <div class="col-12 col-lg-3">
                                                                <x-select2-ajax id="KPBC" name="KPBC"
                                                                    :route="route('kpbc.search')" placeholder="KPBC" width="100%"
                                                                    value="{{ old('KPBC') ?? ($item->KPBC ?? '') }}"
                                                                    required="required">
                                                                    @if ($item->KPBC)
                                                                        <option value="{{ $item->KPBC }}" selected>
                                                                            {{ $item->KPBC }} -
                                                                            {{ $item->customs->UrKdkpbc }}
                                                                        </option>
                                                                    @endif
                                                                </x-select2-ajax>
                                                            </div>
                                                            {{-- COMPANY SELECT --}}
                                                            <label for="mBRANCH" class="col-sm-3 col-lg-1 col-form-label">
                                                                Company @include('buttons.mandatory')</label>
                                                            <div class="col-12 col-lg-3">
                                                                <select name="mBRANCH" id="mBRANCH" style="width: 100%;"
                                                                    class="select2bs4" required>
                                                                    @forelse (auth()->user()->branches as $branch)
                                                                        <option value="{{ $branch->id }}"
                                                                            @selected($item->mBRANCH == $branch->id)
                                                                            data-npwp="{{ $branch->company->GC_TaxID }}">
                                                                            {{ $branch->company->GC_Name }} |
                                                                            {{ $branch->CB_Code }}
                                                                        </option>
                                                                    @empty
                                                                    @endforelse
                                                                </select>
                                                            </div>
                                                            {{-- END COMPANY --}}

                                                            {{-- NPWP --}}
                                                            <label for="NPWP" class="col-sm-3 col-lg-1 col-form-label">
                                                                NPWP @include('buttons.mandatory')</label>
                                                            <div class="col-12 col-lg-3">
                                                                <input type="text" name="NPWP" id="NPWP"
                                                                    class="form-control form-control-sm" placeholder="NPWP"
                                                                    readonly>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <!-- AirlineCode -->
                                                            <label for="AirlineCode"
                                                                class="col-sm-3 col-lg-1 col-form-label">
                                                                Airline @include('buttons.mandatory')</label>
                                                            <div class="col-12 col-lg-3">
                                                                <select name="AirlineCode" id="AirlineCode"
                                                                    style="width: 100%;" class="select2airline" required
                                                                    {{ $disabled }}>
                                                                    @if ($item->AirlineCode)
                                                                        <option value="{{ $item->AirlineCode }}"
                                                                            data-name="{{ $item->NM_SARANA_ANGKUT }}"
                                                                            data-code="{{ substr($item->MAWBNumber, 0, 3) }}"
                                                                            selected>
                                                                            {{ $item->AirlineCode }} -
                                                                            {{ $item->NM_SARANA_ANGKUT }}
                                                                        </option>
                                                                    @endif
                                                                </select>
                                                                <!-- NM_SARANA_ANGKUT -->
                                                                <input type="hidden" name="NM_SARANA_ANGKUT"
                                                                    id="NM_SARANA_ANGKUT"
                                                                    value="{{ old('NM_SARANA_ANGKUT') ?? ($item->NM_SARANA_ANGKUT ?? '') }}"
                                                                    {{ $disabled }}>
                                                            </div>
                                                            {{-- Flight NO --}}
                                                            <label for="FlightNo" class="col-sm-3 col-lg-1 col-form-label">
                                                                FlightNo @include('buttons.mandatory')</label>
                                                            <div class="col-12 col-lg-1">
                                                                <input type="text" name="FlightNo" id="FlightNo"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="Flight No"
                                                                    value="{{ old('FligtNo') ?? ($item->FlightNo ?? '') }}"
                                                                    required>
                                                            </div>
                                                            {{-- Departure Date --}}

                                                            <label for="departure" class="col-sm-3 col-lg-1 col-form-label">
                                                                Departure </label>
                                                            <div class="col-12 col-lg-2">
                                                                <div class="input-group input-group-sm date datetimemin" id="dt_dep" data-target-input="nearest">
                                                                    <input type="text" name="Departure" id="departure"
                                                                        class="form-control datetimepicker-input"
                                                                        placeholder="Departure Date"
                                                                        data-target="#dt_dep">
                                                                    <div class="input-group-append">
                                                                        <div class="input-group-text"
                                                                            data-target="#dt_dep"
                                                                            data-toggle="datetimepicker">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- DepartureDate -->
                                                            <input type="hidden" name="DepartureDate" id="DepartureDate"
                                                                value="{{ old('DepartureDate') ?? ($item->DepartureDate ?? '') }}"
                                                                {{ $disabled }}>
                                                            <!-- DepartureTime -->
                                                            <input type="hidden" name="DepartureTime" id="DepartureTime"
                                                                value="{{ old('DepartureTime') ?? ($item->DepartureTime ?? '') }}"
                                                                {{ $disabled }}>

                                                            {{-- Arrival --}}

                                                            <label for="Arrival"
                                                                class="col-sm-3 col-lg-1 col-form-label">
                                                                Arrival @include('buttons.mandatory')</label>
                                                            <div class="col-12 col-lg-2">
                                                                <div class="input-group input-group-sm date datetimemin" id="dt_arv" data-target-input="nearest">
                                                                    <input type="text" name="Arrival" id="Arrival"
                                                                        class="form-control datetimepicker-input"
                                                                        placeholder="Arrival Date" data-target="#dt_arv">
                                                                    <div class="input-group-append">
                                                                        <div class="input-group-text"
                                                                            data-target="#dt_arv"
                                                                            data-toggle="datetimepicker">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                    <!-- ArrivalDate -->
                                                                    <input type="hidden" name="ArrivalDate"
                                                                        id="ArrivalDate"
                                                                        value="{{ old('ArrivalDate') ?? ($item->ArrivalDate ?? '') }}"
                                                                        {{ $disabled }}>
                                                                    <!-- ArrivalTime -->
                                                                    <input type="hidden" name="ArrivalTime"
                                                                        id="ArrivalTime"
                                                                        value="{{ old('ArrivalTime') ?? ($item->ArrivalTime ?? '') }}"
                                                                        {{ $disabled }}>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">

                                                            {{-- Origin --}}
                                                            <label for="Origin"
                                                                class="col-sm-2 col-lg-1 col-form-label"> Origin
                                                                @include('buttons.mandatory')</label>
                                                            <div class="col-12 col-lg-3">
                                                                <x-select2-ajax id="Origin" name="Origin"
                                                                    :route="route('origin.search')" placeholder="Select.."
                                                                    width="100%" required="required">
                                                                    @if ($item->Origin)
                                                                        <option value="{{ $item->Origin }}" selected>
                                                                            {{ $item->Origin }} -
                                                                            {{ $item->unlocoOrigin->RL_PortName }}
                                                                            {{ '(' . $item->unlocoOrigin->RL_RN_NKCountryCode . ')' }}
                                                                        </option>
                                                                    @endif
                                                                </x-select2-ajax>
                                                            </div>
                                                            {{-- Transit --}}
                                                            <label for="Transit"
                                                                class="col-sm-2 col-lg-1 col-form-label"> Transit
                                                            </label>
                                                            <div class="col-12 col-lg-3">
                                                                <x-select2-ajax id="Transit" name="Transit"
                                                                    :route="route('origin.search')" placeholder="Select.."
                                                                    width="100%"
                                                                    value="{{ old('Transit') ?? ($item->Transit ?? '') }}">
                                                                    @if ($item->Transit)
                                                                        <option value="{{ $item->Transit }}" selected>
                                                                            {{ $item->Transit }} -
                                                                            {{ $item->unlocoTransit->RL_PortName }}
                                                                            {{ '(' . $item->unlocoTransit->RL_RN_NKCountryCode . ')' }}
                                                                        </option>
                                                                    @endif
                                                                </x-select2-ajax>
                                                            </div>
                                                            {{-- Destination --}}
                                                            <label for="Destination"
                                                                class="col-sm-2 col-lg-1 col-form-label"> Destination
                                                                @include('buttons.mandatory')</label>
                                                            <div class="col-12 col-lg-3">
                                                                <x-select2-ajax id="Destination" name="Destination"
                                                                    :route="route('origin.search')" placeholder="Select.."
                                                                    width="100%"
                                                                    value="{{ old('Destination') ?? ($item->Destination ?? '') }}"
                                                                    required="required">
                                                                    @if ($item->Destination)
                                                                        <option value="{{ $item->Destination }}" selected>
                                                                            {{ $item->Destination }} -
                                                                            {{ $item->unlocoDestination->RL_PortName }}
                                                                            {{ '(' . $item->unlocoDestination->RL_RN_NKCountryCode . ')' }}
                                                                        </option>
                                                                    @endif
                                                                </x-select2-ajax>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            {{-- Consolidation NO --}}
                                                            <label for="ConsolNumber"
                                                                class="col-sm-3 col-lg-1 col-form-label ">
                                                                Consolidation Number</label>
                                                            <div class="col-12 col-lg-3">
                                                                <input type="text" name="ConsolNumber"
                                                                    id="ConsolNumber" class="form-control form-control-sm"
                                                                    placeholder="Shipment Number">
                                                            </div>
                                                            {{-- MAWB Number --}}
                                                            <label for="MAWBNumber"
                                                                class="col-sm-3 col-lg-1 col-form-label ">
                                                                MAWB No @include('buttons.mandatory')</label>
                                                            <div class="col-12 col-lg-2">
                                                                <input type="text" name="MAWBNumber" id="MAWBNumber"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="MAWB Number"
                                                                    value="{{ old('MAWBNumber') ?? ($item->MAWBNumber ?? '') }}"
                                                                    required>
                                                            </div>
                                                            <label for="MAWBDate"
                                                                class="col-sm-3 col-lg-1 col-form-label">
                                                                MAWB Date </label>
                                                            <div class="col-12 col-lg-2">
                                                                <div class="input-group input-group-sm date onlydate" id="m_date" data-target-input="nearest">
                                                                    <input type="text" name="MAWBDate" id="MAWBDate"
                                                                        class="form-control datetimepicker-input"
                                                                        placeholder="MAWB Date" data-target="#m_date"
                                                                        value="{{ old('tglmawb')
                                                                            ?? $item->date_mawb
                                                                            ?? '' }}"
                                                                    {{ $disabled }}>
                                                                    <div class="input-group-append">
                                                                        <div class="input-group-text"
                                                                            data-target="#m_date"
                                                                            data-toggle="datetimepicker">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- HAWB Count --}}
                                                            <label for="HAWBCount"
                                                                class="col-sm-3 col-lg-1 col-form-label ">
                                                                HAWB Count @include('buttons.mandatory')</label>
                                                            <div class="col-12 col-lg-1">
                                                                <input type="text" name="HAWBCount" id="HAWBCount"
                                                                    class="form-control form-control-sm numeric"
                                                                    placeholder="HAWB Count"
                                                                    value="{{ old('HAWBCount') ?? ($item->HAWBCount ?? '') }}"
                                                                    required>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <!-- mNoOfPackages -->
                                                            <label for="mNoOfPackages"
                                                                class="col-sm-3 col-lg-1 col-form-label">
                                                                Total Collie</label>
                                                            <div class="col-12 col-lg-2">
                                                                <input type="text" name="mNoOfPackages"
                                                                    id="mNoOfPackages"
                                                                    class="form-control form-control-sm numeric"
                                                                    placeholder="Total Collie"
                                                                    value="{{ old('mNoOfPackages') ?? ($item->mNoOfPackages ?? 0) }}">
                                                            </div>
                                                            <!-- mGrossWeight -->
                                                            <label for="mGrossWeight"
                                                                class="col-sm-3 col-lg-1 col-form-label">
                                                                Gross Weight</label>
                                                            <div class="col-12 col-lg-2">
                                                                <input type="text" name="mGrossWeight"
                                                                    id="mGrossWeight"
                                                                    class="form-control form-control-sm desimal"
                                                                    placeholder="Gross Weight"
                                                                    value="{{ old('mGrossWeight') ?? ($item->mGrossWeight ?? 0) }}">
                                                            </div>
                                                            <!-- mChargeableWeight -->
                                                            <label for="mChargeableWeight"
                                                                class="col-sm-3 col-lg-1 col-form-label">
                                                                Chargable Weight</label>
                                                            <div class="col-12 col-lg-2">
                                                                <input type="text" name="mChargeableWeight"
                                                                    id="mChargeableWeight"
                                                                    class="form-control form-control-sm desimal"
                                                                    placeholder="Chargable Weight"
                                                                    value="{{ old('mChargeableWeight') ?? ($item->mChargeableWeight ?? 0) }}">
                                                            </div>
                                                            <!-- Partial -->
                                                            <label for="Partial"
                                                                class="col-sm-3 col-lg-1 col-form-label">
                                                                Partial</label>
                                                            <div class="col-12 col-lg-2">
                                                                <select name="Partial" id="Partial"
                                                                    class="custom-select custom-select-sm">
                                                                    <option value="0" @selected($item->Partial == false)>No
                                                                    </option>
                                                                    <option value="1" @selected($item->Partial == true)>
                                                                        Yes</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            {{-- NO BC --}}
                                                            <label for="PUNumber"
                                                                class="col-sm-3 col-lg-1 col-form-label">
                                                                BC 1.1</label>
                                                            <div class="col-12 col-lg-2">
                                                                <input type="text" name="PUNumber" id="PUNumber"
                                                                    class="form-control form-control-sm desimal"
                                                                    placeholder="Chargable Weight"
                                                                    value="{{ old('PUNumber') ?? ($item->PUNumber ?? 0) }}">
                                                            </div>
                                                            <label for="POSNumber"
                                                                class="col-sm-3 col-lg-1 col-form-label">
                                                                POS BC 1.1</label>
                                                            <div class="col-12 col-lg-3">
                                                                <input type="text" name="POSNumber" id="POSNumber"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="POS BC 1.1" maxlength="4"
                                                                    value="{{ old('POSNumber') ?? ($item->POSNumber ?? '') }}">
                                                            </div>
                                                            {{-- BC 1.1 Date --}}
                                                            <label for="PUDate"
                                                                class="col-sm-3 col-lg-1 col-form-label">
                                                                BC 1.1 Date </label>
                                                            <div class="col-12 col-lg-2">
                                                                <div class="input-group input-group-sm date onlydate" id="pu_date" data-target-input="nearest">
                                                                    <input type="text" name="PUDate" id="PUDate"
                                                                        class="form-control datetimepicker-input"
                                                                        placeholder="BC 1.1 Date" data-target="#pu_date"
                                                                        value="{{ $item->PUDate
                                                                            ?? '' }}"
                                                                    {{ $disabled }}>
                                                                    <div class="input-group-append">
                                                                        <div class="input-group-text"
                                                                            data-target="#pu_date"
                                                                            data-toggle="datetimepicker">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="form-group row">
                                                            {{-- Warehouse --}}
                                                            <label for="OriginWarehouse"
                                                                class="col-sm-2 col-lg-1 col-form-label">
                                                                Line 1 Warehouse
                                                            </label>
                                                            <div class="col-12 col-lg-3">
                                                                <x-select2-ajax id="OriginWarehouse"
                                                                    name="OriginWarehouse" :route="route('warehouse.search')"
                                                                    placeholder="Warehouse" width="100%"
                                                                    value="{{ old('OriginWarehouse') ?? ($item->OriginWarehouse ?? '') }}">
                                                                    @if ($item->OriginWarehouse)
                                                                        <option value="{{ $item->OriginWarehouse }}"
                                                                            selected>
                                                                            {{ $item->OriginWarehouse }} -
                                                                            {{ $item->warehouseLine1->company_name ?? '' }}
                                                                        </option>
                                                                    @endif
                                                                </x-select2-ajax>
                                                            </div>

                                                            <!-- Tanggal Masuk Gudang -->
                                                            <label for="tglmg"
                                                                class="col-sm-3 col-lg-1 col-form-label">
                                                                Tgl Masuk Gudang </label>
                                                            <div class="col-12 col-lg-3">
                                                                <div class="input-group input-group-sm date datetimemin"
                                                                    id="datetimegudang" data-target-input="nearest">
                                                                    <input type="text" id="tglmg" name="tglmg"
                                                                        class="form-control datetimepicker-input tgltime"
                                                                        placeholder="Masuk Gudang"
                                                                        data-target="#datetimegudang"
                                                                        data-ganti="MasukGudang"
                                                                        value="{{ old('tglmg') ?? ($item->date_mg ?? '') }}"
                                                                        {{ $disabled }}>
                                                                    <div class="input-group-append"
                                                                        data-target="#datetimegudang"
                                                                        data-toggle="datetimepicker">
                                                                        <div class="input-group-text">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="MasukGudang" id="MasukGudang"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ old('MasukGudang') ?? ($item->MasukGudang ?? '') }}"
                                                                    {{ $disabled }}>
                                                            </div>

                                                            {{-- No Segel PLP BC --}}
                                                            <label for="NO_SEGEL"
                                                                class="col-sm-3 col-lg-2 col-form-label">
                                                                No Segel PLP BC @include('buttons.mandatory')
                                                            </label>
                                                            <div class="col-12 col-lg-2">
                                                                <input type="text" name="NO_SEGEL" id="NO_SEGEL"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="No Segel PLP Bea Cukai"
                                                                    value="{{ old('NO_SEGEL') ?? ($item->NO_SEGEL ?? '') }}">
                                                            </div>
                                                        </div>
                                                </div>

                                                {{-- Footer --}}
                                                <div class="card-footer">
                                                    @if ($disabled != 'disabled')
                                                        <button type="submit" class="btn btn-sm btn-success elevation-2">
                                                            <i class="fas fa-save"></i> Save
                                                        </button>
                                                    @endif
                                                    <a href="{{ route('manifest.consolidation') }}"
                                                       class="btn btn-sm btn-default elevation-2 ml-2">Cancel</a>
                                                    @if ($item->id && $disabled != 'disabled')
                                                        <a href="{{ route('manifest.consolidation.create') }}"
                                                           class="btn btn-sm btn-info elevation-2 ml-2">
                                                            <i class="fas fa-plus"></i> New
                                                        </a>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Logs Tab --}}
                            <div class="tab-pane fade" id="tab-logs-content" role="tabpanel" aria-labelledby="tab-logs">
                                <div class="row mt-2">
                                    <div class="col-12">
                                        @include('pages.manifest.consolidation.logs')
                                    </div>
                                </div>
                            </div>

                            {{-- Houses Tab --}}
                            <div class="tab-pane fade" id="tab-houses-content" role="tabpanel" aria-labelledby="tab-houses">
                                <div class="row mt-2">
                                    <div class="col-12">
                                        @include('pages.manifest.consolidation.tab-house')
                                    </div>
                                </div>
                            </div>



                        </div> {{-- End .tab-content --}}
                    </div> {{-- End .card-body --}}
                </div> {{-- End .card --}}
            </div> {{-- End .col --}}
        </div> {{-- End .row --}}
    </div> {{-- End .container-fluid --}}
</section>
@endsection


@section('footer')
    <script>

        //  $(document).ready(function () {
        // $('#tab-logs-content').removeClass('show');
        // });

        $(document).ready(function(){
        $('#tblLogs').DataTable().destroy();

        let id = "{{ $item->id }}";
        $.ajax({
            url: "{{ route('logs.show', ':id') }}".replace(':id', id),
            type: "GET",
            data:{
            type: 'master',
            id: id,
            },
            success: function(msg){
            $('#tblLogs').DataTable({
                data:msg.data,
                pageLength: parseInt("{{ config('app.page_length') }}"),
                columns:[
                {data:"DT_RowIndex", name: "DT_RowIndex", searchable: false, className:"h-10"},
                {data:"created_at", name: "created_at"},
                {data:"user", name: "user"},
                {data:"keterangan", name: "keterangan", searchable: false},
                ]
            });
            }
        });
     });

        $(document).ready(function() {
            $('#formDetails').on('submit', function(e) {
                e.preventDefault();
                var action = $(this).attr('action');
                var method = $(this).attr('method');

                function convertToMysqlDate(dateStr) {
                    if (!dateStr) return '';
                    let parts = dateStr.split('-');
                    if (parts.length === 3) {
                        return `${parts[2]}-${parts[1]}-${parts[0]}`; // Y-m-d
                    }
                    return dateStr;
                }

                 $('#MAWBDate').val(convertToMysqlDate($('#MAWBDate').val()));
                 $('#PUDate').val(convertToMysqlDate($('#PUDate').val()));

                $.ajax({
                    url: action,
                    method: method,
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status == 'OK') {
                            showSuccess(response.message)
                        } else {
                            showError(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert("Terjadi kesalahan: " + xhr.responseText);
                    }
                });
            });
        });

        // Editable Column

        $(document).on('click', '.editcw', function(){
            var ini = $(this);
            var id = $(this).attr('data-pk');
            var url = $(this).attr('data-url');
            var name = $(this).attr('data-name');
            var title = $(this).attr('data-title');
            var placeholder = $(this).attr('data-placeholder');
            var val = $(this).attr('value');

            Swal.fire({
                title: title,
                input: "text",
                inputPlaceholder: placeholder,
                inputValue: val,
                inputAttributes: {
                    id: "myInput"
                },
                didOpen: function(el){
                    var container = $(el);
                    container.find('#myInput').inputmask('numeric', {
                                                groupSeparator:'.',
                                                rightAlign: false,
                                                allowMinus: false,
                                                autoUnmask: true,
                                                removeMaskOnSubmit: true
                                                });

                },
                inputValidator: (value) => {
                    if(!$.isNumeric(value)){
                        return "Please Input Numeric";
                    } else if (value == 0){
                        return "Minimal Input is 1.";
                    }
                }
            }).then((result) => {
                if(result.value){
                $.ajax({
                url : url,
                type: "POST",
                data:{
                    pk: id,
                    value:result.value,
                    name:name
                },
                success: function(msg){
                    if(msg.status == 'OK'){
                    toastr.success(msg.message, "Success!", {timeOut: 3000, closeButton: true,progressBar: true});
                    ini.text('').text(formatAsMoney(result.value, 2, '.'));
                    ini.attr('value', formatAsMoney(result.value, 2, '.'));
                    } else {
                    toastr.error(msg.message, "Failed!", {timeOut: 3000, closeButton: true,progressBar: true});
                    }
                },
                error: function (jqXHR, exception) {
                    jsonValue = jQuery.parseJSON( jqXHR.responseText );
                    toastr.error(jqXHR.status + ' || ' + jsonValue.message, "Failed!", {timeOut: 3000, closeButton: true,progressBar: true});
                }
                });
            }
            });
        })

        // Tabel Houses

        $(document).ready(function () {
            $('#tblHouses').DataTable({
            paging: true,
            info: true,
            processing: true,
            serverSide: true,
            searchDelay: 350,
            ajax: {
            url: "{{ route('houses.index') }}",
            type: "GET",
            data: function(d){
                var s = d.search.value;

                d.search.value = s.replace('-', '');

                d.id = '{{ $item->id }}';

                return d;
            }
        },
            columns: [
                @forelse ($headerHouse as $keys => $value)
                    @if ($keys == 'id')
                        {data: "DT_RowIndex", name: "DT_RowIndex", searchable: false, orderable: false},
                    @elseif ($keys == 'actions')
                        {data: "actions", searchable: false, orderable: false, className: "text-nowrap"},
                    @elseif ($keys == 'HAWB No')
                        {data: "{{ $keys }}"
                         render: function(data, type, row) {
                            return `<span class="hawb-no" data-id="${row.id}">${data}</span>`;
                            }
                        }
                     @elseif(in_array($keys, ['SCAN_IN_DATE', 'SCAN_OUT_DATE']))
                        {
                        data: "{{ $keys }}",
                        render: function (data, type, row) {
                            if (type === 'display') {
                            if(isNaN(data) && moment(data, 'YYYY-MM-DD HH:MM:SS', true).isValid())
                            {
                                return moment(data).format('DD-MM-YYYY HH:MM:SS');
                            }
                            }
                            return data;
                        },
                        className: 'text-nowrap'
                        },
                        @elseif ($keys == 'BC_STATUS')
                        {data: '{{$keys}}', name: '{{$keys}}', className: 'keterangan'},
                        @else
                        {data: '{{$keys}}', name: '{{$keys}}'},
                    @endif
                    @empty
                @endforelse
            ]
            // Gabisa pake serialize di tabel
            // data: digunakan untuk membuat custom parameter yang diinginkan
            // data: $(this).serialize(),success: function(response) {
            //             if (response.status == 'OK') {
            //                 showSuccess(response.message)
            //             } else {
            //                 showError(response.message);
            //             }
            //         },
            //         error: function(xhr) {
            //             alert("Terjadi kesalahan: " + xhr.responseText);
            //         };
            });
        });

        $(document).on('click', '.edit', function(){
            var target = $(this).attr('data-target');
            var id = $(this).attr('data-id');

            $('#'+target).removeClass('show');

            $.ajax({
                // Di Blade:
                // url: "{{ route('houses.show', ':id') }}".replace(':id', id),
                url: '/manifest/houses/'+id,
                type: "GET",
                success: function(msg){
                    console.log(msg);

                    $('#detailHouse').text('').text(msg.NO_HOUSE_BLAWB);


                    if(msg.SPPBDate){
                        var sppbdate = moment(msg.SPPBDate);

                        $('#tgglsppb').val(sppbdate.format('DD/MM/YY')).trigger('change');
                        $('#SPPBDate').val(sppbdate.format('YYYY-MM-DD')).trigger('change');
                    } else {
                        $('#tgglsppb').val('').trigger('change');
                        $('#SPPBDate').val('').trigger('change');
                    }

                    // BC 1.1 Date
                    if(msg.TGL_BC11){
                        var bcDate = moment(msg.TGL_BC11);

                        $('#TGL_BC11').val(bcDate.format('DD-MM-YY')).trigger('change');
                    } else {
                        $('#TGL_BC11').val('').trigger('change');
                    }

                    $('#NO_BC11').val(msg.NO_BC11);
                    $('#NO_POS_BC11').val(msg.NO_POS_BC11);
                    $('#NO_SUBPOS_BC11').val(msg.NO_SUBPOS_BC11);
                    $('#NO_SUBSUBPOSBC11').val(msg.NO_SUBSUBPOS_BC11);

                    $('#BCF15_Status').val((msg.BCF15_Status ?? 'N')).trigger('change');
                    $('#BCF15_Number').val(msg.BCF15_Number).trigger('change');

                    if(msg.BCF15_Date){
                        var bcfDate = moment(msg.BCF15_Date);

                        $('#tglbcf').val(bcfDate.format('DD/MM/YYYY')).trigger('change');
                        $('#BCF15_Date').val(bcfDate.format('YYYY-MM-DD')).trigger('change');
                    } else {
                        $('#tglbcf').val('').trigger('change');
                        $('#BCF15_Date').val('').trigger('change');
                    }

                    $('#NO_DAFTAR_PABEAN').val(msg.NO_DAFTAR_PABEAN).trigger('change');

                    if(msg.TGL_DAFTAR_PABEAN){
                    var tglpib = moment(msg.TGL_DAFTAR_PABEAN);

                    $('#tglpib').val(tglpib.format('DD/MM/YYYY')).trigger('change');
                    $('#TGL_DAFTAR_PABEAN').val(tglpib.format('YYYY-MM-DD')).trigger('change');
                    } else {
                    $('#tglpib').val('').trigger('change');
                    $('#TGL_DAFTAR_PABEAN').val('').trigger('change');
                    }

                    $('#SEAL_NO').val(msg.SEAL_NO).trigger('change');

                    if(msg.SEAL_DATE){
                    var sealdate = moment(msg.SEAL_DATE);

                    $('#tglseal').val(sealdate.format('DD/MM/YYYY')).trigger('change');
                    $('#SEAL_DATE').val(sealdate.format('YYYY-MM-DD')).trigger('change');
                    } else {
                    $('#tglseal').val('').trigger('change');
                    $('#SEAL_DATE').val('').trigger('change');
                    }

                    $('#TOTAL_PARTIAL').val(msg.TOTAL_PARTIAL).trigger('change');

                    $('#ShipmentNumber').val(msg.ShipmentNumber).trigger('change');
                    $('#CUS_PO').val(msg.CUS_PO).trigger('change');
                    $('#NO_HOUSE_BLAWB').val(msg.NO_HOUSE_BLAWB).trigger('change');

                    if(msg.TGL_HOUSE_BLAWB){
                    var houseDate = moment(msg.TGL_HOUSE_BLAWB);

                    $('#tglhouse').val(houseDate.format('DD/MM/YYYY')).trigger('change');
                    $('#TGL_HOUSE_BLAWB').val(houseDate.format('YYYY-MM-DD')).trigger('change');
                    } else {
                    $('#tglhouse').val('').trigger('change');
                    $('#TGL_HOUSE_BLAWB').val('').trigger('change');
                    }


                    // Airline Data

                    if(msg.KD_PEL_MUAT){
                    var optmuat = '<option value="'+ msg.KD_PEL_MUAT +'">'
                                        + msg.KD_PEL_MUAT
                                        + ' - ' + msg.unloco_origin.RL_PortName
                                        + ' - ' + msg.unloco_origin.RL_RN_NKCountryCode
                                        + '</option>';
                    $('#KD_PEL_MUAT').empty().append(optmuat);
                    } else {
                    $('#KD_PEL_MUAT').empty();
                    }

                    if(msg.KD_PEL_TRANSIT){
                    var optmuat = '<option value="'+ msg.KD_PEL_TRANSIT +'">'
                                        + msg.KD_PEL_TRANSIT
                                        + ' - ' + msg.unloco_origin.RL_PortName
                                        + ' - ' + msg.unloco_origin.RL_RN_NKCountryCode
                                        + '</option>';
                    $('#KD_PEL_TRANSIT').empty().append(optmuat);
                    } else {
                    $('#KD_PEL_TRANSIT').empty();
                    }

                    if(msg.KD_PEL_AKHIR){
                        var optmuat = '<option value="'+ msg.KD_PEL_AKHIR +'">'
                                        + msg.KD_PEL_AKHIR
                                        + ' - ' + msg.unloco_origin.RL_PortName
                                        + ' - ' + msg.unloco_origin.RL_RN_NKCountryCode
                                        + '</option>';

                        $('#KD_PEL_AKHIR').empty().append(optmuat);
                    } else {
                        $('#KD_PEL_AKHIR').empty()
                    }

                    if(msg.KD_PEL_BONGKAR){
                        var optmuat = '<option value="'+ msg.KD_PEL_BONGKAR +'">'
                                        + msg.KD_PEL_BONGKAR
                                        + ' - ' + msg.unloco_origin.RL_PortName
                                        + ' - ' + msg.unloco_origin.RL_RN_NKCountryCode
                                        + '</option>';

                        $('#KD_PEL_BONGKAR').empty().append(optmuat);
                    } else {
                        $('#KD_PEL_BONGKAR').empty()
                    }

                    if(msg.SCAN_IN_DATE){
                        $('#SCAN_IN_DATE').val(msg.SCAN_IN_DATE);
                    }
                    if(msg.SCAN_OUT_DATE){
                        $('#SCAN_OUT_DATE').val(msg.SCAN_OUT_DATE);
                    }
                    if(msg.TPS_GateInStatus){
                         $('#TPS_GateInStatus').val(msg.TPS_GateInStatus);
                    }
                    if(msg.TPS_GateOutStatus){
                         $('#TPS_GateOutStatus').val(msg.TPS_GateOutStatus);
                    }

                    if(msg.NM_PENGIRIM){
                    var optPengirim = '<option value="'+ msg.NM_PENGIRIM +'"'
                                        +'data-address="'+ msg.AL_PENGIRIM +'"'
                                        +'data-tax="" data-phone="">'
                                        + msg.NM_PENGIRIM + ' || ' + msg.AL_PENGIRIM +'</option>';
                    $('#NM_PENGIRIM').empty().append(optPengirim);
                    } else {
                    $('#NM_PENGIRIM').empty();
                    }

                    $('#AL_PENGIRIM').val(msg.AL_PENGIRIM).trigger('change');
                    $('#KD_NEG_PENGIRIM').val(msg.KD_NEG_PENGIRIM).trigger('change');

                    if(msg.NM_PENERIMA){
                    var optPengirim = '<option value="'+ msg.NM_PENERIMA +'"'
                                        +'data-address="'+ msg.AL_PENERIMA +'"'
                                        +'data-tax="'+ msg.NO_ID_PENERIMA +'"'
                                        +'data-phone="'+ msg.TELP_PENERIMA +'">'
                                        + msg.NM_PENERIMA + ' || ' + msg.AL_PENERIMA +'</option>';
                    $('#NM_PENERIMA').empty().append(optPengirim);
                    } else {
                    $('#NM_PENERIMA').empty()
                    }

                    $('#AL_PENERIMA').val(msg.AL_PENERIMA).trigger('change');
                    $('#NO_ID_PENERIMA').val(msg.NO_ID_PENERIMA).trigger('change');
                    $('#JNS_ID_PENERIMA').val((msg.JNS_ID_PENERIMA ?? 0)).trigger('change');
                    $('#TELP_PENERIMA').val(msg.TELP_PENERIMA).trigger('change');

                    $('#NM_PEMBERITAHU').val(msg.NM_PEMBERITAHU);
                    $('#NO_ID_PEMBERITAHU').val(msg.NO_ID_PEMBERITAHU);
                    $('#AL_PEMBERITAHU').val(msg.AL_PEMBERITAHU);

                    $('#NETTO').val(msg.NETTO).trigger('change');
                    $('#INCO').val(msg.INCO).trigger('change');
                    $('#BRUTO').val(msg.BRUTO).trigger('change');
                    $('#ChargeableWeight').val(msg.ChargeableWeight).trigger('change');
                    $('#CIF').val(msg.CIF);
                    $('#FOB').val(msg.FOB).trigger('change');
                    $('#FREIGHT').val(msg.FREIGHT).trigger('change');
                    $('#VOLUME').val(msg.VOLUME).trigger('change');

                    // if(msg.details.length > 0){
                    // $('#UR_BRG').val(msg.details[0].UR_BRG).trigger('change');
                    // } else {
                    // $('#UR_BRG').val('').trigger('change');
                    // }

                    $('#ASURANSI').val(msg.ASURANSI).trigger('change');
                    $('#JML_BRG').val(msg.JML_BRG).trigger('change');
                    $('#JNS_KMS').val(msg.JNS_KMS).trigger('change');
                    $('#MARKING').val(msg.MARKING).trigger('change');

                    $('#tariff_id').val(msg.tariff_id).trigger('change');
                    $('#NPWP_BILLING').val(msg.NPWP_BILLING).trigger('change');
                    $('#NAMA_BILLING').val(msg.NAMA_BILLING).trigger('change');
                    $('#NO_INVOICE').val(msg.NO_INVOICE).trigger('change');

                    if(msg.TGL_INVOICE){
                    var invDate = moment(msg.TGL_INVOICE);

                    $('#tglinv').val(invDate.format('DD/MM/YYYY')).trigger('change');
                    $('#TGL_INVOICE').val(invDate.format('YYYY-MM-DD')).trigger('change');
                    } else {
                    $('#tglinv').val('').trigger('change');
                    $('#TGL_INVOICE').val('').trigger('change');
                    }

                    $('#TOT_DIBAYAR').val(msg.TOT_DIBAYAR).trigger('change');
                    $('#NDPBM').val(msg.NDPBM).trigger('change');

                    $('#'+target).addClass('show');
                    gotoView(target);
                    console.log(msg);


                }
            });

            $('#formHouse').attr('action', '/manifest/houses/'+id);
            // $('#'+target).collapse('show');

        });

        $(document).on('click', '#hideHouse', function(){
        $('#collapseHouse').removeClass('show');
        });

        // Date

        $(function(){
            $('.datetimemin').datetimepicker({
            icons: { time: 'far fa-clock' },
            format: 'DD-MM-YYYY HH:mm',
            sideBySide: true,
            allowInputToggle: true,
            });

            $('.onlydate').datetimepicker({
            icons: { time: 'far fa-clock' },
            format: 'DD-MM-YYYY',
            allowInputToggle: true,
            });
        });
        // form Houses

        $(document).ready(function() {
            $('#formHouse').on('submit', function(e) {
                e.preventDefault();
                var action = $(this).attr('action');

                $.ajax({
                    url: action,
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status == 'OK') {
                            $('#detailHouse').text('').text(response.house);
                            showSuccess(response.message);
                            $(`.hawb-no[data-id="${msg.id}"]`).text(msg.NO_HOUSE_BLAWB);
                        } else {
                            showError(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert("Terjadi kesalahan: " + xhr.responseText);
                    }
                });
            });
        });

        // find NPWP
        function findNpwp() {
            var npwp = $('#mBRANCH').find(':selected').attr('data-npwp');

            $('#NPWP').val();
            @if ($item->id)
                $('#Kdkpbc').append('<option value="050100" selected>050100 - KPPBC Soekarno-Hatta</option>').trigger(
                    'change');
            @endif
        }

        $(document).on('input paste', '#departure', function() {
            var dept = moment($(this).val(), 'DD-MM-YYYY HH:mm');
            var tgl = $(this).val().split(' ');
            console.log(dept);
            $('#DepartureDate').val(moment(tgl[0], 'DD-MM-YYYY').format('YYYY-MM-DD'));
            $('#DepartureTime').val(tgl[1]);

        });



        $(document).on('input paste', '#Arrival', function() {
            var tgl = $(this).val().split(' ');

            $('#ArrivalDate').val(moment(tgl[0], 'DD-MM-YYYY').format('YYYY-MM-DD'));
            $('#ArrivalTime').val(tgl[1]);
        });

        // $('.datetimepicker-input').datetimepicker({
        //     format: 'YYYY-MM-DD HH:mm'
        // })



        $('.select2airline').select2({
            placeholder: 'Select...',
            allowClear: true,
            ajax: {
                url: "{{ route('airline.search') }}",
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.RM_TwoCharacterCode + " - " + item.RM_AirlineName1
                                    .toUpperCase(),
                                id: item.RM_TwoCharacterCode,
                                name: item.RM_AirlineName1,
                                code: item.RM_AccountingCode
                            }
                        })
                    };
                },
                cache: true
            },
            templateSelection: function(container) {
                $(container.element).attr("data-name", container.name)
                    .attr("data-code", container.code);
                return container.text;
            }
        });


        $('.select2airline').on('select2:select', function(e) {
            var data = e.params.data;

            console.log('Selected airline:', data);

            // Set nilai ke input
            if (data.code) {
                $('#MAWBNumber').val(data.code).trigger('change');
            }

            if (data.name) {
                $('#NM_SARANA_ANGKUT').val(data.name.toUpperCase());
            }
        });

        $(document).on('change', '#MAWBNumber', function() {
            var val = $(this).val().replace(/[^0-9]/gi, '');

            if (val.length == 11) {
                var end = val.substr(10, 1);
                var code = val.substr(3, 7);
                var divseven = code / 7;
                var substr = divseven.toString().split('.');
                console.log('substr: ' + substr[1]);
                var nbr = (0 + '.' + substr[1]) * 7;
                console.log('nbr:' + nbr);
                var checkNum = Math.round(nbr);
                console.log('check:' + checkNum);

                if (end != checkNum) {
                    alert('Please provide a valid MAWB Number!');
                }
            }
        });

        $('[data-toggle="tooltip"]').tooltip();

    </script>
@endsection

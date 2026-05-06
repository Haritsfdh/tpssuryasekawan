@extends('layouts.master')

@section('title') Company @endsection
@section('page_name') 
  @if($company->id && $company->GC_Logo)
    <img src="{{ $company->getLogo() }}" 
        alt="Logo Company"
        style="height: 25px; width: auto;">
  @else
  <i class="fas fa-building"></i> 
  @endif
  Company Data 
@endsection
@section('header')
  <style>
    label{
      margin-bottom: 0px !important;
    }
  </style>
@endsection
@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      @if (count($errors) > 0)
        <div class="row">
          <div class="col-12">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          </div>
        </div>
      @endif
      @php
        if ($company->id) {
          $url = route('admin.companies.update', ['company' => $company->id]);
        } else {
          $url = route('admin.companies.store');
        }
      @endphp      
      <form action="{{ $url }}" 
            method="post" 
            enctype="multipart/form-data"
            class="needs-validation"
            novalidate>
        @csrf

        @if($company->id)
          @method('PUT')
        @endif

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">@if($company->id != '') Edit @else New @endif Company</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <!-- Left Card -->
                  <div class="col-12 col-md-8">
                    <div class="card card-primary card-outline">
                      <div class="card-header">
                        <h3 class="card-title">Details</h3>
                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                          </button>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <!-- Company Name -->
                          <div class="col-12 col-md-9">
                            <div class="form-group form-group-sm">
                              <label for="GC_Name">Company Name</label>
                              <input type="text" name="GC_Name" id="GC_Name"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_Name') ?? $company->GC_Name ?? '' }}"
                                     placeholder="Company Name"
                                     required
                                     {{ $disabled }}>
                              <span class="invalid-feedback">This field is required</span>
                            </div>
                          </div> 
                          <!-- Company Code -->
                          <div class="col-12 col-md-3">
                            <div class="form-group form-group-sm">
                              <label for="GC_Code">Company Code</label>
                              <input type="text" name="GC_Code" id="GC_Code"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_Code') ?? $company->GC_Code ?? '' }}"
                                     placeholder="Company Code"
                                     required
                                     {{ $disabled }}>
                              <span class="invalid-feedback">This field is required</span>
                            </div>
                          </div>
                          <!-- Address 1 -->
                          <div class="col-12">                            
                            <div class="form-group form-group-sm">
                              <label for="GC_Address1">Address 1</label>
                              <input type="text" name="GC_Address1" id="GC_Address1"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_Address1') ?? $company->GC_Address1 ?? '' }}"
                                     placeholder="Address 1"
                                     required
                                     {{ $disabled }}>
                              <span class="invalid-feedback">This field is required</span>
                            </div>
                          </div> 
                          <!-- Company Name -->
                          <div class="col-12">                            
                            <div class="form-group form-group-sm">
                              <label for="GC_Address2">Address 2</label>
                              <input type="text" name="GC_Address2" id="GC_Address2"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_Address2') ?? $company->GC_Address2 ?? '' }}"
                                     placeholder="Address 2"                                     
                                     {{ $disabled }}>
                            </div>
                          </div>
                          <!-- Country -->
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_RN_NKCountryCode">Country</label>
                              <select name="GC_RN_NKCountryCode" id="GC_RN_NKCountryCode"
                                      style="width: 100%;"
                                      required
                                      {{ $disabled }}>
                                <option selected 
                                        value="{{ old('GC_RN_NKCountryCode') ?? $company->GC_RN_NKCountryCode ?? '' }}">
                                  {{ old('GC_RN_NKCountryCode') ?? $company->GC_RN_NKCountryCode ?? 'Select...' }}</option>
                              </select>
                              <span class="invalid-feedback">This field is required</span>
                            </div>
                          </div>
                          <!-- City -->
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_City">City</label>
                              <input type="text" name="GC_City" id="GC_City"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_City') ?? $company->GC_City ?? '' }}"
                                     placeholder="City Name"
                                     required
                                     {{ $disabled }}>
                              <span class="invalid-feedback">This field is required</span>
                            </div>
                          </div>
                          <!-- State -->
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_State">State</label>
                              <input type="text" name="GC_State" id="GC_State"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_State') ?? $company->GC_State ?? '' }}"
                                     placeholder="State"                                     
                                     {{ $disabled }}>
                            </div>
                          </div>
                          <!-- Post Code -->
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_PostCode">Post Code</label>
                              <input type="number" name="GC_PostCode" id="GC_PostCode"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_PostCode') ?? $company->GC_PostCode ?? '' }}"
                                     placeholder="Post Code"
                                     required
                                     {{ $disabled }}>
                              <span class="invalid-feedback">This field is required</span>
                            </div>
                          </div>
                          <!-- Phone -->
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_Phone">Phone</label>
                              <input type="text" name="GC_Phone" id="GC_Phone"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_Phone') ?? $company->GC_Phone ?? '' }}"
                                     placeholder="Phone"
                                     required
                                     {{ $disabled }}>
                              <span class="invalid-feedback">This field is required</span>
                            </div>
                          </div>
                          <!-- Fax -->
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_Fax">Fax</label>
                              <input type="text" name="GC_Fax" id="GC_Fax"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_Fax') ?? $company->GC_Fax ?? '' }}"
                                     placeholder="Fax No"
                                     {{ $disabled }}>
                            </div>
                          </div>
                          <!-- Email -->
                          <div class="col-12 col-md-6">
                            <div class="form-group form-group-sm">
                              <label for="GC_Email">Email</label>
                              <input type="email" name="GC_Email" id="GC_Email"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_Email') ?? $company->GC_Email ?? '' }}"
                                     placeholder="Email Address"
                                     {{ $disabled }}>
                            </div>
                          </div>
                          <!-- Web Address -->
                          <div class="col-12 col-md-6">
                            <div class="form-group form-group-sm">
                              <label for="GC_WebAddress">Web Address</label>
                              <input type="text" name="GC_WebAddress" id="GC_WebAddress"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_WebAddress') ?? $company->GC_WebAddress ?? '' }}"
                                     placeholder="Company Web Address"
                                     {{ $disabled }}>
                            </div>
                          </div>
                          <!-- Bussiness Reg 1 -->
                          <div class="col-12 col-md-6">
                            <div class="form-group form-group-sm">
                              <label for="GC_BusinessRegNo">Bussiness Reg No</label>
                              <input type="text" name="GC_BusinessRegNo" id="GC_BusinessRegNo"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_BusinessRegNo') ?? $company->GC_BusinessRegNo ?? '' }}"
                                     placeholder="Bussiness Registration Number"
                                     {{ $disabled }}>
                            </div>
                          </div>
                          <!-- Bussiness Reg 2 -->
                          <div class="col-12 col-md-6">
                            <div class="form-group form-group-sm">
                              <label for="GC_BusinessRegNo2">Bussiness Reg No 2</label>
                              <input type="text" name="GC_BusinessRegNo2" id="GC_BusinessRegNo2"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_BusinessRegNo2') 
                                                ?? $company->GC_BusinessRegNo2 ?? '' }}"
                                     placeholder="Additional Bussiness Registration Number"
                                     {{ $disabled }}>
                            </div>
                          </div>
                          <!-- Customs Reg -->
                          <div class="col-12 col-md-6">
                            <div class="form-group form-group-sm">
                              <label for="GC_CustomsRegistrationNo">Custom Reg No</label>
                              <input type="text" name="GC_CustomsRegistrationNo" id="GC_CustomsRegistrationNo"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_CustomsRegistrationNo') 
                                              ?? $company->GC_CustomsRegistrationNo ?? '' }}"
                                     placeholder="Customs Registration Number"
                                     {{ $disabled }}>
                            </div>
                          </div>
                          <!-- Tax ID -->
                          <div class="col-12 col-md-6">
                            <div class="form-group form-group-sm">
                              <label for="GC_TaxID">Tax ID</label>
                              <input type="text" name="GC_TaxID" id="GC_TaxID"
                                     class="form-control form-control-sm"
                                     value="{{ old('GC_TaxID') 
                                              ?? $company->GC_TaxID ?? '' }}"
                                     placeholder="Tax ID"
                                     {{ $disabled }}>
                            </div>
                          </div>
                          <!-- Currency -->
                          <div class="col-12 col-md-6">
                            <div class="form-group form-group-sm">
                              <label for="GC_RX_NKLocalCurrency">Local Currency</label>
                              <select name="GC_RX_NKLocalCurrency" id="GC_RX_NKLocalCurrency"
                                      style="width: 100%;"
                                      required
                                      {{ $disabled }}>
                                <option selected 
                                      value="{{ old('GC_RX_NKLocalCurrency') ?? $company->GC_RX_NKLocalCurrency ?? '' }}">
                                {{ old('GC_RX_NKLocalCurrency') ?? $company->GC_RX_NKLocalCurrency ?? 'Select...' }}</option>
                              </select>
                              <span class="invalid-feedback">This field is required</span>
                            </div>
                          </div>
                          <!-- Currency -->
                          <div class="col-12 col-md-6">
                            <div class="form-group form-group-sm">
                              <label for="GC_Logo">Company Logo</label>
                              <input type="file" 
                                     name="GC_Logo" 
                                     id="GC_Logo" 
                                     class="form-control form-control-sm"
                                     accept=".jpg,.png">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card card-danger card-outline">
                      <div class="card-header">
                        <h3 class="card-title">Accounting Control</h3>
                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                          </button>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_ArControl">AR Control Domestic</label>
                              <select name="GC_ArControl" id="GC_ArControl"
                                      class="select2control"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_ArControl') ?? $company->GC_ArControl ?? '' }}">
                                {{ old('GC_ArControl') ?? optional($company->arcontrol)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_ArControl_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_ArControlOvs">AR Control Overseas</label>
                              <select name="GC_ArControlOvs" id="GC_ArControlOvs"
                                      class="select2control"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_ArControlOvs') ?? $company->GC_ArControlOvs ?? '' }}">
                                {{ old('GC_ArControlOvs') ?? optional($company->arcontrolovs)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_ArControlOvs_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_ApControl">AP Control Domestic</label>
                              <select name="GC_ApControl" id="GC_ApControl"
                                      class="select2control"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_ApControl') ?? $company->GC_ApControl ?? '' }}">
                                {{ old('GC_ApControl') ?? optional($company->apcontrol)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_ApControl_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_ApControlOvs">AP Control Overseas</label>
                              <select name="GC_ApControlOvs" id="GC_ApControlOvs"
                                      class="select2control"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_ApControlOvs') ?? $company->GC_ApControlOvs ?? '' }}">
                                {{ old('GC_ApControlOvs') ?? optional($company->apcontrolovs)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_ApControlOvs_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_WipControl">WIP Control</label>
                              <select name="GC_WipControl" id="GC_WipControl"
                                      class="select2control"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_WipControl') ?? $company->GC_WipControl ?? '' }}">
                                {{ old('GC_WipControl') ?? optional($company->wipcontrol)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_WipControl_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_AcrControl">Acrual Control</label>
                              <select name="GC_AcrControl" id="GC_AcrControl"
                                      class="select2control"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_AcrControl') ?? $company->GC_AcrControl ?? '' }}">
                                {{ old('GC_AcrControl') ?? optional($company->acrualcontrol)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_AcrControl_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_RevSuspense">Revenue Suspense</label>
                              <select name="GC_RevSuspense" id="GC_RevSuspense"
                                      class="select2accounting"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_RevSuspense') ?? $company->GC_RevSuspense ?? '' }}">
                                {{ old('GC_RevSuspense') ?? optional($company->revenuesuspense)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_RevSuspense_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_CostSuspense">Cost Suspense</label>
                              <select name="GC_CostSuspense" id="GC_CostSuspense"
                                      class="select2accounting"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_CostSuspense') ?? $company->GC_CostSuspense ?? '' }}">
                                {{ old('GC_CostSuspense') ?? optional($company->costsuspense)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_CostSuspense_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_ForexGainRealized">Forex Gain Realized</label>
                              <select name="GC_ForexGainRealized" id="GC_ForexGainRealized"
                                      class="select2accounting"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_ForexGainRealized') 
                                        ?? $company->GC_ForexGainRealized ?? '' }}">
                                {{ old('GC_ForexGainRealized') 
                                    ?? optional($company->forexgainrealized)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_ForexGainRealized_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_ForexLossRealized">Forex Loss Realized</label>
                              <select name="GC_ForexLossRealized" id="GC_ForexLossRealized"
                                      class="select2accounting"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_ForexLossRealized') 
                                        ?? $company->GC_ForexLossRealized ?? '' }}">
                                {{ old('GC_ForexLossRealized') 
                                    ?? optional($company->forexlossrealized)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_ForexLossRealized_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_ForexGainUnrealized">Forex Gain Unrealized</label>
                              <select name="GC_ForexGainUnrealized" id="GC_ForexGainUnrealized"
                                      class="select2accounting"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_ForexGainUnrealized') 
                                        ?? $company->GC_ForexGainUnrealized ?? '' }}">
                                        {{ old('GC_ForexGainUnrealized') 
                                            ?? optional($company->forexgainunrealized)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_ForexGainUnrealized_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_ForexLossUnrealized">Forex Loss Unrealized</label>
                              <select name="GC_ForexLossUnrealized" id="GC_ForexLossUnrealized"
                                      class="select2accounting"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_ForexLossUnrealized') 
                                        ?? $company->GC_ForexLossUnrealized ?? '' }}">
                                        {{ old('GC_ForexLossUnrealized') 
                                            ?? optional($company->forexlossunrealized)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_ForexLossUnrealized_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_RetainedEarning">Retained Earning</label>
                              <select name="GC_RetainedEarning" id="GC_RetainedEarning"
                                      class="select2control"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_RetainedEarning') 
                                        ?? $company->GC_RetainedEarning ?? '' }}">
                                        {{ old('GC_RetainedEarning') 
                                            ?? optional($company->retainedearning)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_RetainedEarning_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_CurrentEarning">Current Earning</label>
                              <select name="GC_CurrentEarning" id="GC_CurrentEarning"
                                      class="select2control"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_CurrentEarning') 
                                        ?? $company->GC_CurrentEarning ?? '' }}">
                                        {{ old('GC_CurrentEarning') 
                                            ?? optional($company->currentearning)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_CurrentEarning_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_DiscountAccount">Discount Account</label>
                              <select name="GC_DiscountAccount" id="GC_DiscountAccount"
                                      class="select2accounting"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_DiscountAccount') 
                                        ?? $company->GC_DiscountAccount ?? '' }}">
                                        {{ old('GC_DiscountAccount') 
                                            ?? optional($company->discount)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_DiscountAccount_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_OverpaymentAccount">Overpayment Account</label>
                              <select name="GC_OverpaymentAccount" id="GC_OverpaymentAccount"
                                      class="select2accounting"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_OverpaymentAccount') 
                                        ?? $company->GC_OverpaymentAccount ?? '' }}">
                                        {{ old('GC_OverpaymentAccount') 
                                            ?? optional($company->overpayment)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_OverpaymentAccount_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_UnderpaymentAccount">Underpayment Account</label>
                              <select name="GC_UnderpaymentAccount" id="GC_UnderpaymentAccount"
                                      class="select2accounting"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_UnderpaymentAccount') 
                                        ?? $company->GC_UnderpaymentAccount ?? '' }}">
                                        {{ old('GC_UnderpaymentAccount') 
                                            ?? optional($company->underpayment)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_UnderpaymentAccount_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_BankFee">Bank Fee Account</label>
                              <select name="GC_BankFee" id="GC_BankFee"
                                      class="select2accounting"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_BankFee') 
                                        ?? $company->GC_BankFee ?? '' }}">
                                        {{ old('GC_BankFee') 
                                            ?? optional($company->bankfee)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_BankFee_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_StampControl">Stamp Fee Account</label>
                              <select name="GC_StampControl" id="GC_StampControl"
                                      class="select2accounting"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_StampControl') 
                                        ?? $company->GC_StampControl ?? '' }}">
                                        {{ old('GC_StampControl') 
                                            ?? optional($company->stamp)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_StampControl_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_ARClearingAccount">AR Clearing Account</label>
                              <select name="GC_ARClearingAccount" id="GC_ARClearingAccount"
                                      class="select2accounting"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_ARClearingAccount') 
                                        ?? $company->GC_ARClearingAccount ?? '' }}">
                                        {{ old('GC_ARClearingAccount') 
                                            ?? optional($company->arclearing)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_ARClearingAccount_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_APClearingAccount">AP Clearing Account</label>
                              <select name="GC_APClearingAccount" id="GC_APClearingAccount"
                                      class="select2accounting"
                                      style="width: 100%;"
                                      {{ $disabled }}>
                                <option selected value="{{ old('GC_APClearingAccount') 
                                        ?? $company->GC_APClearingAccount ?? '' }}">
                                        {{ old('GC_APClearingAccount') 
                                            ?? optional($company->apclearing)->AG_AccountNum ?? '' }}</option>
                              </select>                              
                              <small class="text-primary" id="GC_APClearingAccount_desc"></small>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_OverpaymentLimit">Overpayment Limit</label>
                              <input type="number" name="GC_OverpaymentLimit" id="GC_OverpaymentLimit"
                                     step="0.01" 
                                     class="form-control form-control-sm money"
                                     placeholder="Overpayment Limit"
                                     value="{{ old('GC_OverpaymentLimit') 
                                              ?? $company->GC_OverpaymentLimit ?? 0 }}"
                                     {{ $disabled }}>
                            </div>
                          </div>
                          <div class="col-6 col-md-4">
                            <div class="form-group form-group-sm">
                              <label for="GC_UnderpaymentLimit ">Underpayment Limit</label>
                              <input type="number" name="GC_UnderpaymentLimit " id="GC_UnderpaymentLimit "
                                     step="0.01" 
                                     class="form-control form-control-sm money"
                                     placeholder="Underpayment Limit"
                                     value="{{ old('GC_UnderpaymentLimit ') 
                                              ?? $company->GC_UnderpaymentLimit  ?? 0 }}"
                                     {{ $disabled }}>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card card-primary card-outline">
                      <div class="card-header">
                        <h3 class="card-title">Default Bank</h3>
                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                          </button>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-3 col-sm-auto text-primary text-bold"
                               data-toggle="collapse" href="#collapseIDR">IDR Account</div>
                          <div class="col"><hr class="w-100"></div>
                        </div>
                        <div id="collapseIDR" class="collapse show">
                          <div class="row">
                            <!-- Default Bank IDR Name -->
                            <div class="col-12 col-md-4">
                              <div class="form-group form-group-sm">
                                <label for="GC_IDR_BankName">Bank Name</label>
                                <input type="text" name="GC_IDR_BankName" id="GC_IDR_BankName"
                                       class="form-control form-control-sm"
                                       value="{{ old('GC_IDR_BankName') ?? $company->GC_IDR_BankName ?? '' }}"
                                       placeholder="Bank Name"
                                       {{ $disabled }}>
                              </div>
                            </div>
                            <div class="col-12 col-md-4">
                              <div class="form-group form-group-sm">
                                <label for="GC_IDR_BankAccountName">Account Name</label>
                                <input type="text" name="GC_IDR_BankAccountName" id="GC_IDR_BankAccountName"
                                       class="form-control form-control-sm"
                                       value="{{ old('GC_IDR_BankAccountName') ?? $company->GC_IDR_BankAccountName ?? '' }}"
                                       placeholder="Account Name"
                                       {{ $disabled }}>
                              </div>
                            </div>
                            <div class="col-12 col-md-2">
                              <div class="form-group form-group-sm">
                                <label for="GC_IDR_BankAccountNum">Account Number</label>
                                <input type="text" name="GC_IDR_BankAccountNum" id="GC_IDR_BankAccountNum"
                                       class="form-control form-control-sm"
                                       value="{{ old('GC_IDR_BankAccountNum') ?? $company->GC_IDR_BankAccountNum ?? '' }}"
                                       placeholder="Account Number"
                                       {{ $disabled }}>
                              </div>
                            </div>
                            <div class="col-12 col-md-2">
                              <div class="form-group form-group-sm">
                                <label for="GC_IDR_BankSwift">Swift Code</label>
                                <input type="text" name="GC_IDR_BankSwift" id="GC_IDR_BankSwift"
                                       class="form-control form-control-sm"
                                       value="{{ old('GC_IDR_BankSwift') ?? $company->GC_IDR_BankSwift ?? '' }}"
                                       placeholder="Swift Code"
                                       {{ $disabled }}>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group form-group-sm">
                                <label for="GC_IDR_BankAddress">Address</label>
                                <input type="text" name="GC_IDR_BankAddress" id="GC_IDR_BankAddress"
                                       class="form-control form-control-sm"
                                       value="{{ old('GC_IDR_BankAddress') ?? $company->GC_IDR_BankAddress ?? '' }}"
                                       placeholder="Bank Address"
                                       {{ $disabled }}>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-3 col-sm-auto text-primary text-bold"
                               data-toggle="collapse" href="#collapseUSD">USD Account</div>
                          <div class="col"><hr class="w-100"></div>
                        </div>
                        <div id="collapseUSD" class="collapse show">
                          <div class="row">
                            <!-- Default Bank IDR Name -->
                            <div class="col-12 col-md-4">
                              <div class="form-group form-group-sm">
                                <label for="GC_USD_BankName">Bank Name</label>
                                <input type="text" name="GC_USD_BankName" id="GC_USD_BankName"
                                       class="form-control form-control-sm"
                                       value="{{ old('GC_USD_BankName') ?? $company->GC_USD_BankName ?? '' }}"
                                       placeholder="Bank Name"
                                       {{ $disabled }}>
                              </div>
                            </div>
                            <div class="col-12 col-md-4">
                              <div class="form-group form-group-sm">
                                <label for="GC_USD_BankAccountName">Account Name</label>
                                <input type="text" name="GC_USD_BankAccountName" id="GC_USD_BankAccountName"
                                       class="form-control form-control-sm"
                                       value="{{ old('GC_USD_BankAccountName') ?? $company->GC_USD_BankAccountName ?? '' }}"
                                       placeholder="Account Name"
                                       {{ $disabled }}>
                              </div>
                            </div>
                            <div class="col-12 col-md-2">
                              <div class="form-group form-group-sm">
                                <label for="GC_USD_BankAccountNum">Account Number</label>
                                <input type="text" name="GC_USD_BankAccountNum" id="GC_USD_BankAccountNum"
                                       class="form-control form-control-sm"
                                       value="{{ old('GC_USD_BankAccountNum') ?? $company->GC_USD_BankAccountNum ?? '' }}"
                                       placeholder="Account Number"
                                       {{ $disabled }}>
                              </div>
                            </div>
                            <div class="col-12 col-md-2">
                              <div class="form-group form-group-sm">
                                <label for="GC_USD_BankSwift">Swift Code</label>
                                <input type="text" name="GC_USD_BankSwift" id="GC_USD_BankSwift"
                                       class="form-control form-control-sm"
                                       value="{{ old('GC_USD_BankSwift') ?? $company->GC_USD_BankSwift ?? '' }}"
                                       placeholder="Swift Code"
                                       {{ $disabled }}>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group form-group-sm">
                                <label for="GC_USD_BankAddress">Address</label>
                                <input type="text" name="GC_USD_BankAddress" id="GC_USD_BankAddress"
                                       class="form-control form-control-sm"
                                       value="{{ old('GC_USD_BankAddress') ?? $company->GC_USD_BankAddress ?? '' }}"
                                       placeholder="Bank Address"
                                       {{ $disabled }}>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Right Card -->
                  <div class="col-12 col-md-4">
                    <!-- Card Options -->
                    <div class="card card-success card-outline">
                      <div class="card-header">
                        <h3 class="card-title">Options</h3>
                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                          </button>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="form-group">
                          <div class="row"> 
                            <!-- Active Status -->                         
                            <div class="col-6 col-md-12">                              
                              <div class="form-check">
                                <input type="hidden" name="GC_IsActive" value="0"
                                        {{ $disabled }}>
                                <input class="form-check-input" type="checkbox"
                                        name="GC_IsActive" id="GC_IsActive"
                                        @if (old('GC_IsActive') == 1 || $company->GC_IsActive == 1)
                                          checked
                                        @endif
                                        value="1"
                                        {{ $disabled }}>
                                <label class="form-check-label" for="GC_IsActive">Is Active</label>
                              </div>
                            </div>
                            <!-- GST Registered -->
                            <div class="col-6 col-md-12">                              
                              <div class="form-check">
                                <input type="hidden" name="GC_IsGSTRegistered" value="0"
                                        {{ $disabled }}>
                                <input class="form-check-input" type="checkbox"
                                        name="GC_IsGSTRegistered" id="GC_IsGSTRegistered"
                                        @if (old('GC_IsGSTRegistered') == 1 || $company->GC_IsGSTRegistered == 1)
                                          checked
                                        @endif
                                        value="1"
                                        {{ $disabled }}>
                                <label class="form-check-label" for="GC_IsGSTRegistered">Is GST Registered</label>
                              </div>
                            </div>
                            <!-- GST Cash Basis -->
                            <div class="col-6 col-md-12">                              
                              <div class="form-check">
                                <input type="hidden" name="GC_IsGSTCashBasis" value="0"
                                        {{ $disabled }}>
                                <input class="form-check-input" type="checkbox"
                                        name="GC_IsGSTCashBasis" id="GC_IsGSTCashBasis"
                                        @if (old('GC_IsGSTCashBasis') == 1 || $company->GC_IsGSTCashBasis == 1)
                                          checked
                                        @endif
                                        value="1"
                                        {{ $disabled }}>
                                <label class="form-check-label" for="GC_IsGSTCashBasis">Is GST Cash Basis</label>
                              </div>
                            </div>
                            <!-- WHT Registered -->
                            <div class="col-6 col-md-12">                              
                              <div class="form-check">
                                <input type="hidden" name="GC_IsWHTRegistered" value="0"
                                        {{ $disabled }}>
                                <input class="form-check-input" type="checkbox"
                                        name="GC_IsWHTRegistered" id="GC_IsWHTRegistered"
                                        @if (old('GC_IsWHTRegistered') == 1 
                                              || $company->GC_IsWHTRegistered == 1)
                                          checked
                                        @endif
                                        value="1"
                                        {{ $disabled }}>
                                <label class="form-check-label" for="GC_IsWHTRegistered">Is WHT Registered</label>
                              </div>
                            </div>
                            <!-- WHT Cash Basis -->
                            <div class="col-6 col-md-12">
                              <div class="form-check">
                                <input type="hidden" name="GC_IsWHTCashBasis" value="0"
                                        {{ $disabled }}>
                                <input class="form-check-input" type="checkbox"
                                        name="GC_IsWHTCashBasis" id="GC_IsWHTCashBasis"
                                        @if (old('GC_IsWHTCashBasis') == 1 
                                              || $company->GC_IsWHTCashBasis == 1)
                                          checked
                                        @endif
                                        value="1"
                                        {{ $disabled }}>
                                <label class="form-check-label" for="GC_IsWHTCashBasis">Is WHT Cash Basis</label>
                              </div>
                            </div>
                            <!-- Reciprocal Status -->
                            <div class="col-6 col-md-12">
                              <div class="form-check">
                                <input type="hidden" name="GC_IsReciprocal" value="0"
                                        {{ $disabled }}>
                                <input class="form-check-input" type="checkbox"
                                        name="GC_IsReciprocal" id="GC_IsReciprocal"
                                        @if (old('GC_IsReciprocal') == 1 
                                              || $company->GC_IsReciprocal == 1)
                                          checked
                                        @endif
                                        value="1"
                                        {{ $disabled }}>
                                <label class="form-check-label" for="GC_IsReciprocal">Is Reciprocal</label>
                              </div>
                            </div>
                            <!-- Ask for Stamp Fee -->                         
                            <div class="col-6 col-md-12">                              
                              <div class="form-check">
                                <input type="hidden" name="GC_IsStampFee" value="0"
                                        {{ $disabled }}>
                                <input class="form-check-input" type="checkbox"
                                        name="GC_IsStampFee" id="GC_IsStampFee"
                                        @if (old('GC_IsStampFee') == 1 || $company->GC_IsStampFee == 1)
                                          checked
                                        @endif
                                        value="1"
                                        {{ $disabled }}>
                                <label class="form-check-label" for="GC_IsStampFee">Ask for Stamp Fee</label>
                              </div>
                            </div>
                            <!-- Has PJT -->                         
                            <div class="col-6 col-md-12">                              
                              <div class="form-check">
                                <input type="hidden" name="GC_IsPjt" value="0"
                                        {{ $disabled }}>
                                <input class="form-check-input" type="checkbox"
                                        name="GC_IsPjt" id="GC_IsPjt"
                                        @if (old('GC_IsPjt') == 1 || $company->GC_IsPjt == 1)
                                          checked
                                        @endif
                                        value="1"
                                        {{ $disabled }}>
                                <label class="form-check-label" for="GC_IsPjt">Has PJT</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Additional Info -->
                    <div class="card card-warning card-outline">
                      <div class="card-header">
                        <h3 class="card-title">Additional Info</h3>
                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                          </button>
                        </div>
                      </div>
                      <div class="card-body">
                        <!-- Ex Rate Display Mode -->
                        <div class="form-group form-group-sm">
                          <label for="GC_ExRateDisplayMode">Ex Rate Display Mode</label>
                          <input type="text" name="GC_ExRateDisplayMode" id="GC_ExRateDisplayMode" 
                                 class="form-control form-control-sm"
                                 value="{{ old('GC_ExRateDisplayMode') 
                                          ?? $company->GC_ExRateDisplayMode ?? ''}}"
                                 placeholder="Ex Rate Display Mode"
                                 {{ $disabled }}>
                        </div>
                        <!-- Ex Rate Decimals -->
                        <div class="form-group form-group-sm">
                          <label for="GC_ExRateDecimals">Ex Rate Decimals</label>
                          <input type="number" name="GC_ExRateDecimals" id="GC_ExRateDecimals" 
                                 class="form-control form-control-sm"
                                 value="{{ old('GC_ExRateDecimals') 
                                          ?? $company->GC_ExRateDecimals ?? ''}}"
                                 placeholder="Ex Rate Decimals"
                                 {{ $disabled }}>
                        </div>
                        <!-- No of Accounting Periods -->
                        <div class="form-group form-group-sm">
                          <label for="GC_NoOfAccountingPeriods">No of Accounting Periods</label>
                          <input type="number" name="GC_NoOfAccountingPeriods" id="GC_NoOfAccountingPeriods" 
                                 class="form-control form-control-sm"
                                 value="{{ old('GC_NoOfAccountingPeriods') 
                                          ?? $company->GC_NoOfAccountingPeriods ?? ''}}"
                                 placeholder="No of Accounting Periods"
                                 {{ $disabled }}>
                        </div>
                        <!-- Period Format -->
                        <div class="form-group form-group-sm">
                          <label for="GC_PeriodFormat">Period Format</label>
                          <input type="text" name="GC_PeriodFormat" id="GC_PeriodFormat" 
                                 class="form-control form-control-sm"
                                 value="{{ old('GC_PeriodFormat') 
                                          ?? $company->GC_PeriodFormat ?? ''}}"
                                 placeholder="Period Format"
                                 {{ $disabled }}>
                        </div>
                        <!-- Start Date -->
                        <div class="form-group form-group-sm">
                          <label for="GC_StartDate">Start Date</label>
                          <input type="date" name="GC_StartDate" id="GC_StartDate" 
                                 class="form-control form-control-sm"
                                 value="{{ old('GC_StartDate') 
                                          ?? $company->GC_StartDate ?? ''}}"
                                 placeholder="Start Date"
                                 {{ $disabled }}>
                        </div>
                        <!-- Period End Weekday -->
                        <div class="form-group form-group-sm">
                          <label for="GC_PeriodEndWeekDay">Period End Weekday</label>
                          <input type="text" name="GC_PeriodEndWeekDay" id="GC_PeriodEndWeekDay" 
                                 class="form-control form-control-sm"
                                 value="{{ old('GC_PeriodEndWeekDay') 
                                          ?? $company->GC_PeriodEndWeekDay ?? ''}}"
                                 placeholder="Period End Weekday"
                                 {{ $disabled }}>
                        </div>
                        <!-- GL Current Period -->
                        <div class="form-group form-group-sm">
                          <label for="GC_GLCurrentPeriod">GL Current Period</label>
                          <input type="number" name="GC_GLCurrentPeriod" id="GC_GLCurrentPeriod" 
                                 class="form-control form-control-sm"
                                 value="{{ old('GC_GLCurrentPeriod') 
                                          ?? $company->GC_GLCurrentPeriod ?? ''}}"
                                 placeholder="GL Current Period"
                                 {{ $disabled }}>
                        </div>
                        <!-- GL Closed Period -->
                        <div class="form-group form-group-sm">
                          <label for="GC_GLClosedPeriod">GL Closed Period</label>
                          <input type="number" name="GC_GLClosedPeriod" id="GC_GLClosedPeriod" 
                                 class="form-control form-control-sm"
                                 value="{{ old('GC_GLClosedPeriod') 
                                          ?? $company->GC_GLClosedPeriod ?? ''}}"
                                 placeholder="GL Closed Period"
                                 {{ $disabled }}>
                        </div>
                        <!-- ARAP Current Period -->
                        <div class="form-group form-group-sm">
                          <label for="GC_ARAPCurrentPeriod">ARAP Current Period</label>
                          <input type="number" name="GC_ARAPCurrentPeriod" id="GC_ARAPCurrentPeriod" 
                                 class="form-control form-control-sm"
                                 value="{{ old('GC_ARAPCurrentPeriod') 
                                          ?? $company->GC_ARAPCurrentPeriod ?? ''}}"
                                 placeholder="ARAP Current Period"
                                 {{ $disabled }}>
                        </div>
                        <!-- ARAP Closed Period -->
                        <div class="form-group form-group-sm">
                          <label for="GC_ARAPClosedPeriod">ARAP Closed Period</label>
                          <input type="number" name="GC_ARAPClosedPeriod" id="GC_ARAPClosedPeriod" 
                                 class="form-control form-control-sm"
                                 value="{{ old('GC_ARAPClosedPeriod') 
                                          ?? $company->GC_ARAPClosedPeriod ?? ''}}"
                                 placeholder="ARAP Closed Period"
                                 {{ $disabled }}>
                        </div> 
                        <!-- Air Export Recognition Date -->
                        <div class="form-group form-group-sm">
                          <label for="GC_AirExportRecognitionDate">Air Export Recognition Date</label>
                          <select name="GC_AirExportRecognitionDate"
                                  id="GC_AirExportRecognitionDate"
                                  class="custom-select custom-select-sm">
                            <option value="">Select...</option>
                            <option value="JS_E_DEP"
                              @if($company->GC_AirExportRecognitionDate == 'JS_E_DEP') selected @endif>Departure</option>
                            <option value="JS_E_ARV"
                              @if($company->GC_AirExportRecognitionDate == 'JS_E_ARV') selected @endif>Arrivals</option>
                          </select>
                        </div> 
                        <!-- Sea Export Recognition Date -->
                        <div class="form-group form-group-sm">
                          <label for="GC_SeaExportRecognitionDate">Sea Export Recognition Date</label>
                          <select name="GC_SeaExportRecognitionDate"
                                  id="GC_AirExportRecognitionDate"
                                  class="custom-select custom-select-sm">
                            <option value="">Select...</option>
                            <option value="JS_E_DEP"
                              @if($company->GC_SeaExportRecognitionDate == 'JS_E_DEP') selected @endif>Departure</option>
                            <option value="JS_E_ARV"
                              @if($company->GC_SeaExportRecognitionDate == 'JS_E_ARV') selected @endif>Arrivals</option>
                          </select>
                        </div>                         
                        <!-- Air Import Recognition Date -->
                        <div class="form-group form-group-sm">
                          <label for="GC_AirImportRecognitionDate">Air Import Recognition Date</label>
                          <select name="GC_AirImportRecognitionDate"
                                  id="GC_AirImportRecognitionDate"
                                  class="custom-select custom-select-sm">
                            <option value="">Select...</option>
                            <option value="JS_E_DEP"
                              @if($company->GC_AirImportRecognitionDate == 'JS_E_DEP') selected @endif>Departure</option>
                            <option value="JS_E_ARV"
                              @if($company->GC_AirImportRecognitionDate == 'JS_E_ARV') selected @endif>Arrivals</option>
                          </select>
                        </div>
                        <!-- Sea Import Recognition Date -->
                        <div class="form-group form-group-sm">
                          <label for="GC_SeaImportRecognitionDate">Sea Import Recognition Date</label>
                          <select name="GC_SeaImportRecognitionDate"
                                  id="GC_SeaImportRecognitionDate"
                                  class="custom-select custom-select-sm">
                            <option value="">Select...</option>
                            <option value="JS_E_DEP"
                              @if($company->GC_SeaImportRecognitionDate == 'JS_E_DEP') selected @endif>Departure</option>
                            <option value="JS_E_ARV"
                              @if($company->GC_SeaImportRecognitionDate == 'JS_E_ARV') selected @endif>Arrivals</option>
                          </select>
                        </div>
                        <!-- Air Domestic Recognition Date -->
                        <div class="form-group form-group-sm">
                          <label for="GC_AirDomesticRecognitionDate">Air Domestic Recognition Date</label>
                          <select name="GC_AirDomesticRecognitionDate"
                                  id="GC_AirDomesticRecognitionDate"
                                  class="custom-select custom-select-sm">
                            <option value="">Select...</option>
                            <option value="JS_E_DEP"
                              @if($company->GC_AirDomesticRecognitionDate == 'JS_E_DEP') selected @endif>Departure</option>
                            <option value="JS_E_ARV"
                              @if($company->GC_AirDomesticRecognitionDate == 'JS_E_ARV') selected @endif>Arrivals</option>
                          </select>
                        </div>
                        <!-- Sea Domestic Recognition Date -->
                        <div class="form-group form-group-sm">
                          <label for="GC_SeaDomesticRecognitionDate">Sea Domestic Recognition Date</label>
                          <select name="GC_SeaDomesticRecognitionDate"
                                  id="GC_SeaDomesticRecognitionDate"
                                  class="custom-select custom-select-sm">
                            <option value="">Select...</option>
                            <option value="JS_E_DEP"
                              @if($company->GC_SeaDomesticRecognitionDate == 'JS_E_DEP') selected @endif>Departure</option>
                            <option value="JS_E_ARV"
                              @if($company->GC_SeaDomesticRecognitionDate == 'JS_E_ARV') selected @endif>Arrivals</option>
                          </select>
                        </div>
                        <!-- Road Domestic Recognition Date -->
                        <div class="form-group form-group-sm">
                          <label for="GC_RoadDomesticRecognitionDate">Road Domestic Recognition Date</label>
                          <select name="GC_RoadDomesticRecognitionDate"
                                  id="GC_RoadDomesticRecognitionDate"
                                  class="custom-select custom-select-sm">
                            <option value="">Select...</option>
                            <option value="JS_E_DEP"
                              @if($company->GC_RoadDomesticRecognitionDate == 'JS_E_DEP') selected @endif>Departure</option>
                            <option value="JS_E_ARV"
                              @if($company->GC_RoadDomesticRecognitionDate == 'JS_E_ARV') selected @endif>Arrivals</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>                  
                </div>
              </div>
              <div class="card-footer">
                <div class="row">
                  @if($company->id != '' && $disabled == 'disabled')
                  <div class="col-3">
                    <a href="{{ url()->current() }}/edit" 
                       class="btn btn-warning btn-block elevation-2">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                  </div>
                  @else
                  <div class="col-3">
                    <button type="submit" class="btn btn-primary btn-block elevation-2">
                      <i class="fas fa-save"></i> Save
                    </button>
                  </div>
                  @endif                  
                  <div class="col-3">
                    <a href="{{ route('admin.companies') }}" class="btn btn-default btn-block elevation-2">
                      <i class="fas fa-back"></i> Cancel
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection

@section('footer')
  <script>
    jQuery(document).ready(function(){
      //Country Code Select2
      $('#GC_RN_NKCountryCode').select2({
        placeholder: 'Select...',
        ajax: {
          url: "{{ route('select2.setup.countries') }}",
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.RN_Code,
                        id: item.RN_Code,
                    }
                })
            };
          },          
          cache: true
        }        
      });
       //Currency Select2
      $('#GC_RX_NKLocalCurrency').select2({
        placeholder: 'Select...',
        ajax: {
          url: "{{ route('select2.setup.currency') }}",
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.RX_Code,
                        id: item.RX_Code,
                    }
                })
            };
          },          
          cache: true
        }
      });
      // Control Account Select2
      $('.select2control').select2({
        placeholder: 'Select...',
        ajax: {
          url: "{{ route('admin.select2.company.accounting') }}",
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.AG_AccountNum+' - '+item.AG_Description,
                        id: item.id,
                        desc: item.AG_Description
                    }
                })
            };
          },          
          cache: true
        },
        templateSelection: function(container) {
            $(container.element).attr("data-desc", container.desc);
            return container.text;
        }
      });
      // Gl Account Accounting
      $('.select2accounting').select2({
        placeholder: 'Select...',
        ajax: {
          url: "{{ route('admin.select2.company.accounting') }}",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            var query = {
              q: params.term,
              c: 1
            }

            // Query parameters will be ?search=[term]&type=public
            return query;
          },
          processResults: function (data) {
            return {
              results:  $.map(data, function (item) {
                    return {
                        text: item.AG_AccountNum+' - '+item.AG_Description,
                        id: item.id,
                        desc: item.AG_Description
                    }
                })
            };
          },          
          cache: true
        },
        templateSelection: function(container) {
            $(container.element).attr("data-desc", container.desc);
            return container.text;
        }
      });
      // Change desc after select
      $(document).on('select2:select', '.select2accounting', function(){        
        var description = $(this).find(':selected').data('desc');
        var id = $(this).attr('id');
        
        $('#'+id+"_desc").html(description);
        
      });
    });
  </script>
@endsection
@extends('layouts.master')

@section('title')
    {{ __('translation.Edit') . ' ' . __('translation.LogisticPartner') }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">@lang('translation.ManageVisa') /</span>
            <a href="{{ route('manage-visa.logistic-partners.index') }}" class="text-muted fw-light">@lang('translation.LogisticPartnerList')</a> /
            @lang('translation.Edit') @lang('translation.LogisticPartner')
        </h5>
        <div class="card">
            <div class="card-body">
                <form name="master_form" method="post"
                    action="{{ route('manage-visa.logistic-partners.update', $result->id) }}" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="country_id" class="form-label">@lang('translation.Country') <span
                                        class="text-danger">*</span> :</label>
                                <select name="country_id" id="country_id" class="form-control">
                                    <option value="">@lang('translation.SelectCountry')</option>
                                    @foreach ($countries as $key => $country)
                                        <option value="{{ $key }}"
                                            {{ $result->country_id == $key ? 'selected' : '' }}>
                                            {{ $country }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="city" class="form-label">@lang('translation.City') <span
                                        class="text-danger">*</span> :</label>
                                <input type="text" class="form-control" id="city" name="city"
                                    value="{{ $result->city }}" list="city-list" placeholder="@lang('translation.EnterCity')" required>
                                <datalist id="city-list">
                                    @foreach ($cities as $city)
                                        <option value="{{ $city }}">
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="office_name" class="form-label">@lang('translation.OfficeName') <span
                                        class="text-danger">*</span> :</label>
                                <input type="text" class="form-control" id="office_name" name="office_name"
                                    value="{{ $result->office_name }}" placeholder="@lang('translation.EnterOfficeName')" required>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="address" class="form-label">@lang('translation.Address') :</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="{{ $result->address }}" placeholder="@lang('translation.EnterAddress')">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="contact_number" class="form-label">@lang('translation.ContactNumber') <span
                                        class="text-danger">*</span> :</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number"
                                    value="{{ $result->contact_number }}" placeholder="@lang('translation.EnterContactNumber')" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="website" class="form-label">@lang('translation.Website') :</label>
                                <input type="text" class="form-control" id="website" name="website"
                                    value="{{ $result->website }}" placeholder="https://example.com">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">@lang('translation.Email') :</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    value="{{ $result->email }}" placeholder="@lang('translation.EnterEmail')">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <label class="form-label">@lang('translation.OpeningHours')</label>
                            <div class="row">
                                @php
                                    $days = [
                                        'monday',
                                        'tuesday',
                                        'wednesday',
                                        'thursday',
                                        'friday',
                                        'saturday',
                                        'sunday',
                                    ];
                                    $opening_hours = json_decode($result->opening_hours, true) ?? [];
                                @endphp
                                @foreach ($days as $day)
                                    @php $val = $opening_hours[$day] ?? '' @endphp
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label text-capitalize">{{ $day }}</label>
                                            <input type="text" class="form-control"
                                                name="opening_hours[{{ $day }}]" value="{{ $val }}"
                                                placeholder="09:00-17:00">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 col-sm-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('manage-visa.logistic-partners.index') }}"
                                    class="btn btn-danger">@lang('translation.Cancel')</a>
                                <button type="submit" class="btn btn-primary">@lang('translation.Update')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

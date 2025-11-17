@extends('layouts.master')

@section('title')
    {{ $page_title }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">@lang('translation.ManageVisa') /</span>
            <a href="{{ route('manage-visa.diplomatic-representations.index') }}"
                class="text-muted fw-light">@lang('translation.DiplomaticRepresentationList')</a> /
            @lang('translation.Add') @lang('translation.DiplomaticRepresentation')
        </h5>
        <div class="card">
            <div class="card-body">
                <form name="master_form" method="post" action="{{ $route_action }}" novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="country_id" class="form-label">@lang('translation.Country') <span
                                        class="text-danger">*</span> :</label>
                                <select name="country_id" id="country_id" class="form-control">
                                    @foreach ($countries as $key => $country)
                                        <option value="{{ $key }}">{{ $country }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="city" class="form-label">@lang('translation.City') <span
                                        class="text-danger">*</span> :</label>
                                <input type="text" class="form-control" id="city" name="city" list="city-list"
                                    placeholder="@lang('translation.EnterCity')" required>
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
                                    placeholder="@lang('translation.EnterOfficeName')" required>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="address" class="form-label">@lang('translation.Address') :</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="@lang('translation.EnterAddress')">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="contact_number1" class="form-label">@lang('translation.ContactNumber1') :</label>
                                <input type="text" class="form-control" id="contact_number1" name="contact_number1"
                                    placeholder="@lang('translation.EnterPhone')">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="contact_number2" class="form-label">@lang('translation.ContactNumber2') :</label>
                                <input type="text" class="form-control" id="contact_number2" name="contact_number2"
                                    placeholder="@lang('translation.EnterPhone')">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="fax_number" class="form-label">@lang('translation.FaxNumber') :</label>
                                <input type="text" class="form-control" id="fax_number" name="fax_number"
                                    placeholder="@lang('translation.FaxNumber')">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">@lang('translation.Email') :</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="@lang('translation.EnterEmail')">
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
                                @endphp
                                @foreach ($days as $day)
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label text-capitalize">{{ $day }}</label>
                                            <input type="text" class="form-control"
                                                name="opening_hours[{{ $day }}]" placeholder="09:00-17:00">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 col-sm-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('manage-visa.diplomatic-representations.index') }}"
                                    class="btn btn-danger">@lang('translation.Cancel')</a>
                                <button type="submit" class="btn btn-primary">@lang('translation.Save')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

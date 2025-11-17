@extends('layouts.master')

@section('title')
    {{ $page_title }}
@endsection
@section('css')
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            @lang('translation.Languages')
        @endslot
        @slot('title')
            @lang('translation.AddLanguage')
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between pb-0">
                    <h4 class="card-title">
                        @lang('translation.AddLanguage')
                    </h4>
                    <a href="{{ route('settings.index', ['tab' => 'language_setting']) }}"
                        class="btn btn-outline-dark ms-auto" title="Back">
                        @lang('translation.Back')
                    </a>
                </div>
                <div class="card-body">
                    <form name="language_form" method="post" action="{{ $route_action }}" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">
                                        @lang('translation.Language')
                                        <span class="text-danger">*</span> :</label>
                                    <select class="form-control" id="name" name="name" required>
                                        <option value="">@lang('translation.Select')</option>
                                        @foreach (config('languages') as $language)
                                            @if ($language['name'] != 'English' && !in_array($language['iso_code'], $added_languages))
                                                <option value="{{ $language['name'] }}">
                                                    {{ $language['name'] }} ({{ $language['symbol'] }})</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- Add Generate language file button --}}
                            <div class="col-md-4">
                                <div class="form-group mt-2">
                                    <button type="submit" class="btn btn-primary form-button"
                                        style="margin-top: 20px !important;"
                                        id="generate_language_file">@lang('translation.GenerateLanguageFile')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <section class="mt-3 d-none" id="language_file_section">


                    </section>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
@endsection
@section('script')
    <!-- Sweet alert init js-->
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        // Generate language file
        // Ajax request in JavaScript
        $(document).on('submit', "form[name=language_form1]", function(e) {
            e.preventDefault();

            // Your existing validation and Ajax call

            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#overlay").show();
                },
                success: function(data, status, xhr) {
                    if (xhr.status === 200) {
                        triggerToaster('success', data.message); // Display success message
                    }
                },
                complete: function() {
                    $("#overlay").hide();
                },
                error: function(err) {
                    // Handle errors
                }
            });
        });

        $(document).on('submit', "form[name=language_form]", function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            let fail = false;
            $('.has_error').remove();

            $(this).find('select, textarea, input').each(function() {
                if ($(this).prop('required') && !$(this).val()) {
                    fail = true;
                    const name = $(this).attr('name').replace(/\[\d+\]/g, '').replace(/_id/g, '').replace(
                        /_/g, ' ').toLowerCase();
                    const defaultMessage = `Please enter ${name}`;
                    $(this).closest('.form-group').append(
                        `<div class="text-danger has_error">${$(this).data('error') || defaultMessage}</div>`
                    );
                }
            });

            if (!fail) {
                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $("#overlay").show();
                    },
                    success: function(data, status, xhr) {

                        if (xhr.status === 200) {
                            triggerToaster('success', data.message);
                            let jobId = data.job_id;
                            checkJobStatus(jobId);
                            // $('#language_file_section').html(data);
                            // $('#language_file_section').removeClass('d-none');
                        }
                    },
                    complete: function() {
                        $("#overlay").hide();
                    }
                }).fail(function(err) {
                    if (err.status === 422) {
                        const errors = err.responseJSON.errors;
                        for (const key in errors) {
                            $(`[name=${key}]`).closest('.form-group').append(
                                `<div class="text-danger has_error">${errors[key][0]}</div>`
                            );
                        }
                    }
                    if (err.status === 500) {
                        triggerToaster('error', err.responseJSON.error);
                    }
                });
            } else {
                // Focus on first error field
                $(this).find('.has_error').first().prev().focus();
            }
        });
    </script>
@endsection

@extends('layouts.admin')
@section('page-title')
    {{ __('Settings') }}
@endsection
@php
    use App\Models\Utility;
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $logo_light = \App\Models\Utility::getValByName('company_logo_light');
    $logo_dark = \App\Models\Utility::getValByName('company_logo_dark');
    $company_favicon = \App\Models\Utility::getValByName('company_favicon');
    $EmailTemplates = App\Models\EmailTemplate::all();
    $setting = App\Models\Utility::settings();
    
@endphp


@push('script-page')
    <script type="text/javascript">
        $(".email-template-checkbox").click(function() {

            var chbox = $(this);
            $.ajax({
                url: chbox.attr('data-url'),
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    status: chbox.val()
                },
                type: 'post',
                success: function(response) {
                    if (response.is_success) {
                        -
                        // show_toastr('success', '{{ __('Link Copy on Clipboard') }}');
                        show_toastr('success', response.success, 'success');
                        if (chbox.val() == 1) {
                            $('#' + chbox.attr('id')).val(0);
                        } else {
                            $('#' + chbox.attr('id')).val(1);
                        }
                    } else {
                        show_toastr('error', response.error, 'error');
                    }
                },
                error: function(response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('error', response.error, 'error');
                    } else {
                        show_toastr('error', response, 'error');
                    }
                }
            })
        });
    </script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })

        var themescolors = document.querySelectorAll(".themes-color > a");
        for (var h = 0; h < themescolors.length; h++) {
            var c = themescolors[h];
            c.addEventListener("click", function(event) {
                var targetElement = event.target;
                if (targetElement.tagName == "SPAN") {
                    targetElement = targetElement.parentNode;
                }
                var temp = targetElement.getAttribute("data-value");
                removeClassByPrefix(document.querySelector("body"), "theme-");
                document.querySelector("body").classList.add(temp);
            });
        }

        function check_theme(color_val) {
            $('input[value="' + color_val + '"]').prop('checked', true);
            $('a[data-value]').removeClass('active_color');
            $('a[data-value="' + color_val + '"]').addClass('active_color');
        }

        if ($('#cust-darklayout').length > 0) {
            var custthemedark = document.querySelector("#cust-darklayout");
            custthemedark.addEventListener("click", function() {
                if (custthemedark.checked) {
                    $('#style').attr('href', '{{ env('APP_URL') }}' + '/public/assets/css/style-dark.css');

                    $('.dash-sidebar .main-logo a img').attr('src', '{{ $logo . $logo_light }}');

                } else {
                    $('#style').attr('href', '{{ env('APP_URL') }}' + '/public/assets/css/style.css');
                    $('.dash-sidebar .main-logo a img').attr('src', '{{ $logo . $logo_dark }}');

                }
            });
        }
        if ($('#cust-theme-bg').length > 0) {
            var custthemebg = document.querySelector("#cust-theme-bg");
            custthemebg.addEventListener("click", function() {
                if (custthemebg.checked) {
                    document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.add("transprent-bg");
                } else {
                    document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.remove("transprent-bg");
                }
            });
        }
    </script>

    <script>
        $(document).on("change", "select[name='invoice_template'], input[name='invoice_color']", function() {
            var template = $("select[name='invoice_template']").val();
            var color = $("input[name='invoice_color']:checked").val();
            $('#invoice_frame').attr('src', '{{ url('/invoices/preview') }}/' + template + '/' + color);
        });

        $(document).on("change", "select[name='proposal_template'], input[name='proposal_color']", function() {
            var template = $("select[name='proposal_template']").val();
            var color = $("input[name='proposal_color']:checked").val();
            $('#proposal_frame').attr('src', '{{ url('/proposal/preview') }}/' + template + '/' + color);
        });

        $(document).on("change", "select[name='bill_template'], input[name='bill_color']", function() {
            var template = $("select[name='bill_template']").val();
            var color = $("input[name='bill_color']:checked").val();
            $('#bill_frame').attr('src', '{{ url('/bill/preview') }}/' + template + '/' + color);
        });

        $(document).on("change", "select[name='retainer_template'], input[name='retainer_color']", function() {
            var template = $("select[name='retainer_template']").val();
            var color = $("input[name='retainer_color']:checked").val();
            $('#retainer_frame').attr('src', '{{ url('/retainer/preview') }}/' + template + '/' + color);
        });
    </script>

    <script>
        $(".list-group-item").click(function() {
            $('.list-group-item').filter(function() {
                return this.href == id;
            }).parent().removeClass('text-primary');
        });

        function check_theme(color_val) {
            $('#theme_color').prop('checked', false);
            $('input[value="' + color_val + '"]').prop('checked', true);
        }

        $(document).on('change', '[name=storage_setting]', function() {
            if ($(this).val() == 's3') {
                $('.s3-setting').removeClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').addClass('d-none');
            } else if ($(this).val() == 'wasabi') {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').removeClass('d-none');
                $('.local-setting').addClass('d-none');
            } else {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').removeClass('d-none');
            }
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            var checkBox = document.getElementById('tax_number');
            // Check if the element is selected/checked
            if (checkBox.checked) {
                $('#tax_checkbox_id').removeClass('d-none');
            } else {
                $('#tax_checkbox_id').addClass('d-none');
            }
            $(document).on('change', '#tax_number', function() {

                if ($(this).is(':checked') == true) {
                    $('#tax_checkbox_id').removeClass('d-none');
                } else {
                    $('#tax_checkbox_id').addClass('d-none');
                }
            });
        });
    </script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Settings') }}</li>
@endsection

@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#useradd-1"
                                class="list-group-item list-group-item-action border-0">{{ __('Brand Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-2"
                                class="list-group-item list-group-item-action border-0">{{ __('System Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-3"
                                class="list-group-item list-group-item-action border-0">{{ __('Company Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-4"
                                class="list-group-item list-group-item-action border-0">{{ __('Proposal Print Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-10"
                                class="list-group-item list-group-item-action border-0">{{ __('Retainer Print Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-5"
                                class="list-group-item list-group-item-action border-0">{{ __('Invoice Print Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-6"
                                class="list-group-item list-group-item-action border-0">{{ __('Bill Print Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-7"
                                class="list-group-item list-group-item-action border-0">{{ __('Payment Settings') }}
                                <div class="float-end "><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-8"
                                class="list-group-item list-group-item-action border-0">{{ __('Twilio Settings') }}
                                <div class="float-end "><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-9"
                                class="list-group-item list-group-item-action border-0">{{ __('Email Notification Settings') }}
                                <div class="float-end "><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-11"
                                class="list-group-item list-group-item-action border-0">{{ __('Webhook Settings') }}
                                <div class="float-end "><i class="ti ti-chevron-right"></i></div>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-xl-9">

                    <!--Business Setting-->
                    <div id="useradd-1" class="card">

                        {{ Form::model($settings, ['route' => 'business.setting', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="card-header">
                            <h5>{{ __('Brand Settings') }}</h5>
                            <small class="text-muted">{{ __('Edit your brand details') }}</small>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Logo dark') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class=" setting-card">
                                                <div class="logo-content mt-4">
                                                    <a href="{{ $logo . (isset($logo_dark) && !empty($logo_dark) ? $logo_dark : 'logo-dark.png') }}"
                                                        target="_blank">
                                                        <img id="blah" alt="your image"
                                                            src="{{ $logo . (isset($logo_dark) && !empty($logo_dark) ? $logo_dark : 'logo-dark.png') }}"
                                                            width="150px" class="big-logo">
                                                    </a>
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="company_logo">
                                                        <div class=" bg-primary company_logo_update m-auto"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="company_logo_dark" id="company_logo"
                                                            class="form-control file" data-filename="company_logo_update"
                                                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">

                                                        <!-- <input type="file" name="company_logo_dark" id="company_logo" class="form-control file" data-filename="company_logo_update"> -->
                                                    </label>
                                                </div>
                                                @error('company_logo')
                                                    <div class="row">
                                                        <span class="invalid-logo" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Logo Light') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class=" setting-card">
                                                <div class="logo-content mt-4">
                                                    <a href="{{ $logo . (isset($logo_light) && !empty($logo_light) ? $logo_light : 'logo-light.png') }}"
                                                        target="_blank">
                                                        <img id="blah1" alt="your image"
                                                            src="{{ $logo . (isset($logo_light) && !empty($logo_light) ? $logo_light : 'logo-light.png') }}"
                                                            width="150px" class="big-logo img_setting">
                                                    </a>
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="company_logo_light">
                                                        <div class=" bg-primary dark_logo_update m-auto"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="company_logo_light"
                                                            id="company_logo_light" class="form-control file"
                                                            data-filename="dark_logo_update"
                                                            onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])">


                                                        <!-- <input type="file" class="form-control file" name="company_logo_light" id="company_logo_light"
                                                                                                                                        data-filename="dark_logo_update"> -->
                                                    </label>
                                                </div>
                                                @error('company_logo_light')
                                                    <div class="row">
                                                        <span class="invalid-logo" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Favicon') }}</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class=" setting-card">
                                                <div class="logo-content mt-4">
                                                    <a href="{{ $logo . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
                                                        target="_blank">
                                                        <img id="blah2" alt="your image"
                                                            src="{{ $logo . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
                                                            width="50px" class="big-logo img_setting">
                                                    </a>

                                                    <!-- <img src="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}" width="50px"
                                                                                                                                    class="big-logo img_setting" width="150px"> -->
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="company_favicon">
                                                        <div class="bg-primary company_favicon_update m-auto"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="company_favicon" id="company_favicon"
                                                            class="form-control file"
                                                            data-filename="company_favicon_update"
                                                            onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])">


                                                        <!-- <input type="file" class="form-control file"  id="company_favicon" name="company_favicon"
                                                                                                                                        data-filename="company_favicon_update"> -->
                                                    </label>
                                                </div>
                                                @error('logo')
                                                    <div class="row">
                                                        <span class="invalid-logo" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {{ Form::label('title_text', __('Title Text'), ['class' => 'form-label']) }}
                                        {{ Form::text('title_text', null, ['class' => 'form-control', 'placeholder' => __('Title Text')]) }}
                                        @error('title_text')
                                            <span class="invalid-title_text" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>


                                    <div class="col-3 my-auto">
                                        <div class="form-group">
                                            <label class="text-dark mb-1" for="SITE_RTL">{{ __('Enable RTL') }}</label>
                                            <div class="">
                                                <input type="checkbox" name="SITE_RTL" id="SITE_RTL"
                                                    data-toggle="switchbutton"
                                                    {{ $settings['SITE_RTL'] == 'on' ? 'checked="checked"' : '' }}
                                                    data-onstyle="primary">
                                                <label class="form-check-labe" for="SITE_RTL"></label>
                                            </div>
                                        </div>
                                    </div>

                                </div>



                                <h4 class="small-title">{{ __('Theme Customizer') }}</h4>
                                <div class="setting-card setting-logo-box p-3">
                                    <div class="row">
                                        <div class="col-4 my-auto">
                                            <h6 class="mt-2">
                                                <i data-feather="credit-card"
                                                    class="me-2"></i>{{ __('Primary color settings') }}
                                            </h6>
                                            <hr class="my-2" />
                                            <div class="theme-color themes-color">
                                                <a href="#!"
                                                    class="{{ $settings['color'] == 'theme-1' ? 'active_color' : '' }}"
                                                    data-value="theme-1" onclick="check_theme('theme-1')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-1"
                                                    style="display: none;">
                                                <a href="#!"
                                                    class="{{ $settings['color'] == 'theme-2' ? 'active_color' : '' }} "
                                                    data-value="theme-2" onclick="check_theme('theme-2')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-2"
                                                    style="display: none;">
                                                <a href="#!"
                                                    class="{{ $settings['color'] == 'theme-3' ? 'active_color' : '' }}"
                                                    data-value="theme-3" onclick="check_theme('theme-3')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-3"
                                                    style="display: none;">
                                                <a href="#!"
                                                    class="{{ $settings['color'] == 'theme-4' ? 'active_color' : '' }}"
                                                    data-value="theme-4" onclick="check_theme('theme-4')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-4"
                                                    style="display: none;">
                                                <a href="#!"
                                                    class="{{ $settings['color'] == 'theme-5' ? 'active_color' : '' }}"
                                                    data-value="theme-5" onclick="check_theme('theme-5')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-5"
                                                    style="display: none;">
                                                <br>
                                                <a href="#!"
                                                    class="{{ $settings['color'] == 'theme-6' ? 'active_color' : '' }}"
                                                    data-value="theme-6" onclick="check_theme('theme-6')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-6"
                                                    style="display: none;">
                                                <a href="#!"
                                                    class="{{ $settings['color'] == 'theme-7' ? 'active_color' : '' }}"
                                                    data-value="theme-7" onclick="check_theme('theme-7')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-7"
                                                    style="display: none;">
                                                <a href="#!"
                                                    class="{{ $settings['color'] == 'theme-8' ? 'active_color' : '' }}"
                                                    data-value="theme-8" onclick="check_theme('theme-8')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-8"
                                                    style="display: none;">
                                                <a href="#!"
                                                    class="{{ $settings['color'] == 'theme-9' ? 'active_color' : '' }}"
                                                    data-value="theme-9" onclick="check_theme('theme-9')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-9"
                                                    style="display: none;">
                                                <a href="#!"
                                                    class="{{ $settings['color'] == 'theme-10' ? 'active_color' : '' }}"
                                                    data-value="theme-10" onclick="check_theme('theme-10')"></a>
                                                <input type="radio" class="theme_color" name="color"
                                                    value="theme-10" style="display: none;">
                                            </div>
                                        </div>

                                        <div class="col-4 ">
                                            <h6 class="mt-2">
                                                <i data-feather="layout" class="me-2"></i>{{ __('Sidebar settings') }}
                                            </h6>
                                            <hr class="my-2" />
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="cust-theme-bg"
                                                    name="cust_theme_bg"
                                                    {{ Utility::getValByName('cust_theme_bg') == 'on' ? 'checked' : '' }} />
                                                <label class="form-check-label f-w-600 pl-1"
                                                    for="cust-theme-bg">{{ __('Transparent layout') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-4 ">
                                            <h6 class="mt-2 ">
                                                <i data-feather="sun" class="me-2"></i>{{ __('Layout settings') }}
                                            </h6>
                                            <hr class="mt-1" />
                                            <div class="form-check form-switch mt-2">
                                                <input type="checkbox" class="form-check-input" id="cust-darklayout"
                                                    name="cust_darklayout"{{ Utility::getValByName('cust_darklayout') == 'on' ? 'checked' : '' }} />
                                                <label class="form-check-label f-w-600 pl-1"
                                                    for="cust-darklayout">{{ __('Dark Layout') }}</label>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <div class="card-footer text-end">
                                    <div class="form-group">
                                        <input class="btn btn-print-invoice btn-primary m-r-10" type="submit"
                                            value="{{ __('Save Changes') }}">
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                    <!--System Setting-->
                    <div id="useradd-2" class="card">
                        <div class="card-header">
                            <h5>{{ __('System Settings') }}</h5>
                            <small class="text-muted">{{ __('Edit your system details') }}</small>
                        </div>

                        {{ Form::model($settings, ['route' => 'system.settings', 'method' => 'post']) }}
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ Form::label('site_currency', __('Currency *'), ['class' => 'form-label']) }}
                                    {{ Form::text('site_currency', null, ['class' => 'form-control font-style']) }}
                                    @error('site_currency')
                                        <span class="invalid-site_currency" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('site_currency_symbol', __('Currency Symbol *'), ['class' => 'form-label']) }}
                                    {{ Form::text('site_currency_symbol', null, ['class' => 'form-control']) }}
                                    @error('site_currency_symbol')
                                        <span class="invalid-site_currency_symbol" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"
                                            for="example3cols3Input">{{ __('Currency Symbol Position') }}</label>
                                        <div class="row px-3">
                                            <div class="form-check col-md-6">
                                                <input class="form-check-input" type="radio"
                                                    name="site_currency_symbol_position" value="pre"
                                                    @if (@$settings['site_currency_symbol_position'] == 'pre') checked @endif id="flexCheckDefault"
                                                    checked>
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    {{ __('Pre') }}
                                                </label>
                                            </div>
                                            <div class="form-check col-md-6">
                                                <input class="form-check-input" type="radio"
                                                    name="site_currency_symbol_position" value="post"
                                                    @if (@$settings['site_currency_symbol_position'] == 'post') checked @endif id="flexCheckChecked">
                                                <label class="form-check-label" for="flexCheckChecked">
                                                    {{ __('Post') }}
                                                </label>
                                            </div>

                                            {{-- <div class="col-md-6">
                                            <div class="custom-control custom-radio mb-3">

                                                <input type="radio" id="customRadio5" name="site_currency_symbol_position" value="pre" class="custom-control-input" @if (@$settings['site_currency_symbol_position'] == 'pre') checked @endif>
                                                <label class="custom-control-label" for="customRadio5">{{__('Pre')}}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="custom-control custom-radio mb-3">
                                                <input type="radio" id="customRadio6" name="site_currency_symbol_position" value="post" class="custom-control-input" @if (@$settings['site_currency_symbol_position'] == 'post') checked @endif>
                                                <label class="custom-control-label" for="customRadio6">{{__('Post')}}</label>
                                            </div>
                                        </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="site_date_format" class="form-label">{{ __('Date Format') }}</label>
                                    <select type="text" name="site_date_format" class="form-control selectric"
                                        id="site_date_format">
                                        <option value="M j, Y"
                                            @if (@$settings['site_date_format'] == 'M j, Y') selected="selected" @endif>Jan 1,2015</option>
                                        <option value="d-m-Y"
                                            @if (@$settings['site_date_format'] == 'd-m-Y') selected="selected" @endif>dd-mm-yyyy</option>
                                        <option value="m-d-Y"
                                            @if (@$settings['site_date_format'] == 'm-d-Y') selected="selected" @endif>mm-dd-yyyy</option>
                                        <option value="Y-m-d"
                                            @if (@$settings['site_date_format'] == 'Y-m-d') selected="selected" @endif>yyyy-mm-dd</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="site_time_format" class="form-label">{{ __('Time Format') }}</label>
                                    <select type="text" name="site_time_format" class="form-control selectric"
                                        id="site_time_format">
                                        <option value="g:i A"
                                            @if (@$settings['site_time_format'] == 'g:i A') selected="selected" @endif>10:30 PM</option>
                                        <option value="g:i a"
                                            @if (@$settings['site_time_format'] == 'g:i a') selected="selected" @endif>10:30 pm</option>
                                        <option value="H:i"
                                            @if (@$settings['site_time_format'] == 'H:i') selected="selected" @endif>22:30</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('invoice_prefix', __('Invoice Prefix'), ['class' => 'form-label']) }}

                                    {{ Form::text('invoice_prefix', null, ['class' => 'form-control']) }}
                                    @error('invoice_prefix')
                                        <span class="invalid-invoice_prefix" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('invoice_starting_number', __('Invoice Starting Number'), ['class' => 'form-label']) }}
                                    {{ Form::text('invoice_starting_number', null, ['class' => 'form-control']) }}
                                    @error('invoice_starting_number')
                                        <span class="invalid-invoice_starting_number" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('proposal_prefix', __('Proposal Prefix'), ['class' => 'form-label']) }}
                                    {{ Form::text('proposal_prefix', null, ['class' => 'form-control']) }}
                                    @error('proposal_prefix')
                                        <span class="invalid-proposal_prefix" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('proposal_starting_number', __('Proposal Starting Number'), ['class' => 'form-label']) }}
                                    {{ Form::text('proposal_starting_number', null, ['class' => 'form-control']) }}
                                    @error('proposal_starting_number')
                                        <span class="invalid-proposal_starting_number" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('bill_prefix', __('Bill Prefix'), ['class' => 'form-label']) }}
                                    {{ Form::text('bill_prefix', null, ['class' => 'form-control']) }}
                                    @error('bill_prefix')
                                        <span class="invalid-bill_prefix" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('retainer_starting_number', __('Retainer Starting Number'), ['class' => 'form-label']) }}
                                    {{ Form::text('retainer_starting_number', null, ['class' => 'form-control']) }}
                                    @error('retainer_starting_number')
                                        <span class="invalid-proposal_starting_number" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('retainer_prefix', __('Retainer Prefix'), ['class' => 'form-label']) }}
                                    {{ Form::text('retainer_prefix', null, ['class' => 'form-control']) }}
                                    @error('retainer_prefix')
                                        <span class="invalid-bill_prefix" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('bill_starting_number', __('Bill Starting Number'), ['class' => 'form-label']) }}
                                    {{ Form::text('bill_starting_number', null, ['class' => 'form-control']) }}
                                    @error('bill_starting_number')
                                        <span class="invalid-bill_starting_number" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('customer_prefix', __('Customer Prefix'), ['class' => 'form-label']) }}
                                    {{ Form::text('customer_prefix', null, ['class' => 'form-control']) }}
                                    @error('customer_prefix')
                                        <span class="invalid-customer_prefix" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('vender_prefix', __('Vender Prefix'), ['class' => 'form-label']) }}
                                    {{ Form::text('vender_prefix', null, ['class' => 'form-control']) }}
                                    @error('vender_prefix')
                                        <span class="invalid-vender_prefix" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('footer_title', __('Invoice/Bill Footer Title'), ['class' => 'form-label']) }}
                                    {{ Form::text('footer_title', null, ['class' => 'form-control']) }}
                                    @error('footer_title')
                                        <span class="invalid-footer_title" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('decimal_number', __('Decimal Number Format'), ['class' => 'form-label']) }}
                                    {{ Form::number('decimal_number', null, ['class' => 'form-control']) }}
                                    @error('decimal_number')
                                        <span class="invalid-decimal_number" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('journal_prefix', __('Journal Prefix'), ['class' => 'form-label']) }}
                                    {{ Form::text('journal_prefix', null, ['class' => 'form-control']) }}
                                    @error('journal_prefix')
                                        <span class="invalid-journal_prefix" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <div class="form-group col-md-6">
                                    {{ Form::label('shipping_display', __('Display Shipping in Proposal / Invoice / Bill'), ['class' => 'form-label']) }}
                                    <div class=" form-switch form-switch-left">
                                        <input type="checkbox" class="form-check-input" name="shipping_display"
                                            id="email_tempalte_13"
                                            {{ $settings['shipping_display'] == 'on' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_tempalte_13"></label>
                                    </div>

                                    @error('shipping_display')
                                        <span class="invalid-shipping_display" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('footer_notes', __('Invoice/Bill Footer Notes'), ['class' => 'form-label']) }}
                                    {{ Form::textarea('footer_notes', null, ['class' => 'form-control', 'rows' => '3']) }}
                                    @error('footer_notes')
                                        <span class="invalid-footer_notes" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="form-group">
                                <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit"
                                    value="{{ __('Save Changes') }}">
                            </div>
                        </div>
                        {{ Form::close() }}

                    </div>

                    <!--Company Setting-->
                    <div id="useradd-3" class="card">
                        <div class="card-header">
                            <h5>{{ __('Company Settings') }}</h5>
                            <small class="text-muted">{{ __('Edit your company details') }}</small>
                        </div>
                        {{ Form::model($settings, ['route' => 'company.settings', 'method' => 'post']) }}
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{ Form::label('company_name *', __('Company Name *'), ['class' => 'form-label']) }}
                                    {{ Form::text('company_name', null, ['class' => 'form-control font-style']) }}
                                    @error('company_name')
                                        <span class="invalid-company_name" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('company_address', __('Address'), ['class' => 'form-label']) }}
                                    {{ Form::text('company_address', null, ['class' => 'form-control font-style']) }}
                                    @error('company_address')
                                        <span class="invalid-company_address" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('company_city', __('City'), ['class' => 'form-label']) }}
                                    {{ Form::text('company_city', null, ['class' => 'form-control font-style']) }}
                                    @error('company_city')
                                        <span class="invalid-company_city" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('company_state', __('State'), ['class' => 'form-label']) }}
                                    {{ Form::text('company_state', null, ['class' => 'form-control font-style']) }}
                                    @error('company_state')
                                        <span class="invalid-company_state" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('company_zipcode', __('Zip/Post Code'), ['class' => 'form-label']) }}
                                    {{ Form::text('company_zipcode', null, ['class' => 'form-control']) }}
                                    @error('company_zipcode')
                                        <span class="invalid-company_zipcode" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group  col-md-6">
                                    {{ Form::label('company_country', __('Country'), ['class' => 'form-label']) }}
                                    {{ Form::text('company_country', null, ['class' => 'form-control font-style']) }}
                                    @error('company_country')
                                        <span class="invalid-company_country" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('company_telephone', __('Telephone'), ['class' => 'form-label']) }}
                                    {{ Form::text('company_telephone', null, ['class' => 'form-control']) }}
                                    @error('company_telephone')
                                        <span class="invalid-company_telephone" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('company_email', __('System Email *'), ['class' => 'form-label']) }}
                                    {{ Form::text('company_email', null, ['class' => 'form-control']) }}
                                    @error('company_email')
                                        <span class="invalid-company_email" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('company_email_from_name', __('Email (From Name) *'), ['class' => 'form-label']) }}
                                    {{ Form::text('company_email_from_name', null, ['class' => 'form-control font-style']) }}
                                    @error('company_email_from_name')
                                        <span class="invalid-company_email_from_name" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('registration_number', __('Company Registration Number *'), ['class' => 'form-label']) }}
                                    {{ Form::text('registration_number', null, ['class' => 'form-control']) }}
                                    @error('registration_number')
                                        <span class="invalid-registration_number" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            {{ Form::label('tax_number', __('Tax Number'), ['class' => 'form-chech-label']) }}
                                            <div class="form-check form-switch custom-switch-v1 float-end">
                                                <input type="checkbox" class="form-check-input" name="tax_number"
                                                    id="tax_number"
                                                    {{ $settings['tax_number'] == 'on' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="vat_gst_number_switch"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6" id="tax_checkbox_id">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check form-check-inline form-group mb-3">
                                                    <input type="radio" id="customRadio8" name="tax_type"
                                                        value="VAT" class="form-check-input"
                                                        {{ $settings['tax_type'] == 'VAT' ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="customRadio8">{{ __('VAT Number') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check form-check-inline form-group mb-3">
                                                    <input type="radio" id="customRadio7" name="tax_type"
                                                        value="GST" class="form-check-input"
                                                        {{ $settings['tax_type'] == 'GST' ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="customRadio7">{{ __('GST Number') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        {{ Form::text('vat_number', null, ['class' => 'form-control', 'placeholder' => __('Enter VAT / GST Number')]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <div class="form-group">
                                    <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" id="addSig"
                                        value="{{ __('Save Changes') }}">
                                </div>
                            </div>
                            {{ Form::close() }}

                        </div>
                    </div>

                    <!--Proposal Print Setting-->
                    <div id="useradd-4" class="card">
                        <div class="card-header">
                            <h5>{{ __('Proposal Print Settings') }}</h5>
                            <small class="text-muted">{{ __('Edit your company proposal details') }}</small>
                        </div>

                        <div class="bg-none">
                            <div class="row company-setting">
                                <div class="col-md-4">
                                    <div class="card-header card-body">
                                        <form id="setting-form" method="post"
                                            action="{{ route('proposal.template.setting') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="address"
                                                    class="col-form-label">{{ __('Proposal Print Template') }}</label>
                                                <select class="form-control select2" name="proposal_template">
                                                    @foreach (App\Models\Utility::templateData()['templates'] as $key => $template)
                                                        <option value="{{ $key }}"
                                                            {{ isset($settings['proposal_template']) && $settings['proposal_template'] == $key ? 'selected' : '' }}>
                                                            {{ $template }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">{{ __('Color Input') }}</label>
                                                <div class="row gutters-xs">
                                                    @foreach (App\Models\Utility::templateData()['colors'] as $key => $color)
                                                        <div class="col-auto">
                                                            <label class="colorinput">
                                                                <input name="proposal_color" type="radio"
                                                                    value="{{ $color }}" class="colorinput-input"
                                                                    {{ isset($settings['proposal_color']) && $settings['proposal_color'] == $color ? 'checked' : '' }}>
                                                                <span class="colorinput-color"
                                                                    style="background: #{{ $color }}"></span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">{{ __('Proposal Logo') }}</label>


                                                <div class="choose-files mt-5 ">
                                                    <label for="proposal_logo">
                                                        <div class=" bg-primary proposal_logo_update"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <img id="blah4" class="mt-3" src=""
                                                            width="70%" />
                                                        <input type="file" class="form-control file"
                                                            name="proposal_logo" id="proposal_logo"
                                                            data-filename="proposal_logo_update"
                                                            onchange="document.getElementById('blah4').src = window.URL.createObjectURL(this.files[0])">
                                                        <!-- <input type="file" class="form-control file" name="proposal_logo" id="proposal_logo" data-filename="proposal_logo_update"> -->
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group mt-2 text-end">
                                                <input type="submit" value="{{ __('Save Changes') }}"
                                                    class="btn btn-print-invoice  btn-primary m-r-10">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    @if (isset($settings['proposal_template']) && isset($settings['proposal_color']))
                                        <iframe id="proposal_frame" class="w-100 h-100" frameborder="0"
                                            src="{{ route('proposal.preview', [$settings['proposal_template'], $settings['proposal_color']]) }}"></iframe>
                                    @else
                                        <iframe id="proposal_frame" class="w-100 h-100" frameborder="0"
                                            src="{{ route('proposal.preview', ['template1', 'fffff']) }}"></iframe>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                    <!--Retainer Print Setting-->
                    <div id="useradd-10" class="card">
                        <div class="card-header">
                            <h5>{{ __('Retainer Print Settings') }}</h5>
                            <small class="text-muted">{{ __('Edit your company retainer details') }}</small>
                        </div>

                        <div class="bg-none">
                            <div class="row company-setting">
                                <div class="col-md-4">
                                    <div class="card-header card-body">
                                        <form id="setting-form" method="post"
                                            action="{{ route('retainer.template.setting') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="address"
                                                    class="col-form-label">{{ __('Retainer Print Template') }}</label>
                                                <select class="form-control select2" name="retainer_template">
                                                    @foreach (App\Models\Utility::templateData()['templates'] as $key => $template)
                                                        <option value="{{ $key }}"
                                                            {{ isset($settings['retainer_template']) && $settings['retainer_template'] == $key ? 'selected' : '' }}>
                                                            {{ $template }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">{{ __('Color Input') }}</label>
                                                <div class="row gutters-xs">
                                                    @foreach (App\Models\Utility::templateData()['colors'] as $key => $color)
                                                        <div class="col-auto">
                                                            <label class="colorinput">
                                                                <input name="retainer_color" type="radio"
                                                                    value="{{ $color }}" class="colorinput-input"
                                                                    {{ isset($settings['retainer_color']) && $settings['retainer_color'] == $color ? 'checked' : '' }}>
                                                                <span class="colorinput-color"
                                                                    style="background: #{{ $color }}"></span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">{{ __('Retainer Logo') }}</label>
                                                <div class="choose-files mt-5 ">
                                                    <label for="retainer_logo">
                                                        <div class=" bg-primary proposal_logo_update"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <img id="blah5" class="mt-3" src=""
                                                            width="70%" />
                                                        <input type="file" class="form-control file"
                                                            name="retainer_logo" id="retainer_logo"
                                                            data-filename="retainer_logo_update"
                                                            onchange="document.getElementById('blah5').src = window.URL.createObjectURL(this.files[0])">
                                                        <!-- <input type="file" class="form-control file" name="retainer_logo" id="retainer_logo" data-filename="retainer_logo_update"> -->
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group mt-2 text-end">
                                                <input type="submit" value="{{ __('Save Changes') }}"
                                                    class="btn btn-print-invoice  btn-primary m-r-10">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    @if (isset($settings['retainer_template']) && isset($settings['retainer_color']))
                                        <iframe id="retainer_frame" class="w-100 h-100" frameborder="0"
                                            src="{{ route('retainer.preview', [$settings['retainer_template'], $settings['retainer_color']]) }}"></iframe>
                                    @else
                                        <iframe id="retainer_frame" class="w-100 h-100" frameborder="0"
                                            src="{{ route('retainer.preview', ['template1', 'fffff']) }}"></iframe>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                    <!--Invoice Setting-->
                    <div id="useradd-5" class="card">
                        <div class="card-header">
                            <h5>{{ __('Invoice Print Settings') }}</h5>
                            <small class="text-muted">{{ __('Edit your company invoice details') }}</small>
                        </div>

                        <div class="bg-none">
                            <div class="row company-setting">
                                <div class="col-md-4">
                                    <div class="card-header card-body">
                                        <form id="setting-form" method="post"
                                            action="{{ route('invoice.template.setting') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="address"
                                                    class="col-form-label">{{ __('Invoice Template') }}</label>
                                                <select class="form-control select2" name="invoice_template">
                                                    @foreach (Utility::templateData()['templates'] as $key => $template)
                                                        <option value="{{ $key }}"
                                                            {{ isset($settings['invoice_template']) && $settings['invoice_template'] == $key ? 'selected' : '' }}>
                                                            {{ $template }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">{{ __('Color Input') }}</label>
                                                <div class="row gutters-xs">
                                                    @foreach (Utility::templateData()['colors'] as $key => $color)
                                                        <div class="col-auto">
                                                            <label class="colorinput">
                                                                <input name="invoice_color" type="radio"
                                                                    value="{{ $color }}" class="colorinput-input"
                                                                    {{ isset($settings['invoice_color']) && $settings['invoice_color'] == $color ? 'checked' : '' }}>
                                                                <span class="colorinput-color"
                                                                    style="background: #{{ $color }}"></span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">{{ __('Invoice Logo') }}</label>
                                                <div class="choose-files mt-5 ">
                                                    <label for="invoice_logo">
                                                        <div class=" bg-primary invoice_logo_update"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <img id="blah6" class="mt-3" src=""
                                                            width="70%" />
                                                        <input type="file" class="form-control file"
                                                            name="invoice_logo" id="invoice_logo"
                                                            data-filename="invoice_logo_update"
                                                            onchange="document.getElementById('blah6').src = window.URL.createObjectURL(this.files[0])">
                                                        <!-- <input type="file" class="form-control file" name="invoice_logo" id="invoice_logo" data-filename="invoice_logo_update"> -->
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group mt-2 text-end">
                                                <input type="submit" value="{{ __('Save Changes') }}"
                                                    class="btn btn-print-invoice  btn-primary m-r-10">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    @if (isset($settings['invoice_template']) && isset($settings['invoice_color']))
                                        <iframe id="invoice_frame" class="w-100 h-100" frameborder="0"
                                            src="{{ route('invoice.preview', [$settings['invoice_template'], $settings['invoice_color']]) }}"></iframe>
                                    @else
                                        <iframe id="invoice_frame" class="w-100 h-100" frameborder="0"
                                            src="{{ route('invoice.preview', ['template1', 'fffff']) }}"></iframe>
                                    @endif
                                </div>
                            </div>
                        </div>


                    </div>

                    <!--Bill Setting-->
                    <div id="useradd-6" class="card">
                        <div class="card-header">
                            <h5>{{ __('Bill Print Settings') }}</h5>
                            <small class="text-muted">{{ __('Edit your company bill details') }}</small>
                        </div>

                        <div class="bg-none">
                            <div class="row company-setting">
                                <div class="col-md-4">
                                    <div class="card-header card-body">
                                        <form id="setting-form" method="post"
                                            action="{{ route('bill.template.setting') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="address"
                                                    class="form-label">{{ __('Bill Template') }}</label>
                                                <select class="form-control" name="bill_template">
                                                    @foreach (App\Models\Utility::templateData()['templates'] as $key => $template)
                                                        <option value="{{ $key }}"
                                                            {{ isset($settings['bill_template']) && $settings['bill_template'] == $key ? 'selected' : '' }}>
                                                            {{ $template }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">{{ __('Color Input') }}</label>
                                                <div class="row gutters-xs">
                                                    @foreach (Utility::templateData()['colors'] as $key => $color)
                                                        <div class="col-auto">
                                                            <label class="colorinput">
                                                                <input name="bill_color" type="radio"
                                                                    value="{{ $color }}" class="colorinput-input"
                                                                    {{ isset($settings['bill_color']) && $settings['bill_color'] == $color ? 'checked' : '' }}>
                                                                <span class="colorinput-color"
                                                                    style="background: #{{ $color }}"></span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">{{ __('Bill Logo') }}</label>
                                                <div class="choose-files mt-5 ">
                                                    <label for="bill_logo">
                                                        <div class=" bg-primary bill_logo_update"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <img id="blah7" class="mt-3" src=""
                                                            width="70%" />
                                                        <input type="file" class="form-control file" name="bill_logo"
                                                            id="bill_logo" data-filename="bill_logo_update"
                                                            onchange="document.getElementById('blah7').src = window.URL.createObjectURL(this.files[0])">
                                                        <!-- <input type="file" class="form-control file" name="bill_logo" id="bill_logo" data-filename="bill_logo_update"> -->
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group mt-2 text-end">
                                                <input type="submit" value="{{ __('Save Changes') }}"
                                                    class="btn btn-print-invoice  btn-primary m-r-10">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    @if (isset($settings['bill_template']) && isset($settings['bill_color']))
                                        <iframe id="bill_frame" class="w-100 h-100" frameborder="0"
                                            src="{{ route('bill.preview', [$settings['bill_template'], $settings['bill_color']]) }}"></iframe>
                                    @else
                                        <iframe id="bill_frame" class="w-100 h-100" frameborder="0"
                                            src="{{ route('bill.preview', ['template1', 'fffff']) }}"></iframe>
                                    @endif
                                </div>
                            </div>
                        </div>


                    </div>

                    <!--Payment Setting-->
                    <div id="useradd-7" class="card">
                        <div class="card-header">
                            <h5>{{ __('Payment Settings') }}</h5>
                            <small
                                class="text-muted">{{ __('These details will be used to collect invoice payments. Each invoice will have a payment button based on the below configuration.') }}</small>
                        </div>
                        <div class="card-body">
                            {{ Form::model($settings, ['route' => 'company.payment.settings', 'method' => 'POST']) }}

                            @csrf

                            <div class="faq justify-content-center">
                                <div class="col-sm-12 col-md-10 col-xxl-12">
                                    <!-- Strip -->
                                    <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                    aria-expanded="false" aria-controls="collapseOne">
                                                    <span class="d-flex align-items-center">
                                                        {{ __('Stripe') }}
                                                    </span>

                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_stripe_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_stripe_enabled" id="is_stripe_enabled"
                                                                {{ isset($company_payment_setting['is_stripe_enabled']) && $company_payment_setting['is_stripe_enabled'] == 'on' ? 'checked="checked"' : '' }}>

                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="stripe_key"
                                                                    class="col-form-label">{{ __('Stripe Key') }}</label>
                                                                <input class="form-control"
                                                                    placeholder="{{ __('Stripe Key') }}"
                                                                    name="stripe_key" type="text"
                                                                    value="{{ !isset($company_payment_setting['stripe_key']) || is_null($company_payment_setting['stripe_key']) ? '' : $company_payment_setting['stripe_key'] }}"
                                                                    id="stripe_key">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="stripe_secret"
                                                                    class="col-form-label">{{ __('Stripe Secret') }}</label>
                                                                <input class="form-control "
                                                                    placeholder="{{ __('Stripe Secret') }}"
                                                                    name="stripe_secret" type="text"
                                                                    value="{{ !isset($company_payment_setting['stripe_secret']) || is_null($company_payment_setting['stripe_secret']) ? '' : $company_payment_setting['stripe_secret'] }}"
                                                                    id="stripe_secret">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Paypal -->
                                    <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne2 "
                                                    aria-expanded="false" aria-controls="collapseOne2">
                                                    <span class="d-flex align-items-center">
                                                        {{ __('Paypal') }}
                                                    </span>

                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_paypal_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_paypal_enabled" id="is_paypal_enabled"
                                                                {{ isset($company_payment_setting['is_paypal_enabled']) && $company_payment_setting['is_paypal_enabled'] == 'on' ? 'checked="checked"' : '' }}>

                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseOne2" class="accordion-collapse collapse"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-md-12">
                                                            <label class="paypal-label col-form-label"
                                                                for="paypal_mode">{{ __('Paypal Mode') }}</label> <br>
                                                            <div class="d-flex">
                                                                <div class="mr-2" style="margin-right: 15px;">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label
                                                                                class="form-check-labe text-dark {{ isset($company_payment_setting['paypal_mode']) && $company_payment_setting['paypal_mode'] == 'sandbox' ? 'active' : '' }}">
                                                                                <input type="radio" name="paypal_mode"
                                                                                    value="sandbox"
                                                                                    class="form-check-input"
                                                                                    {{ isset($company_payment_setting['paypal_mode']) && $company_payment_setting['paypal_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>

                                                                                {{ __('Sandbox') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mr-2">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                <input type="radio" name="paypal_mode"
                                                                                    value="live"
                                                                                    class="form-check-input"
                                                                                    {{ isset($company_payment_setting['paypal_mode']) && $company_payment_setting['paypal_mode'] == 'live' ? 'checked="checked"' : '' }}>

                                                                                {{ __('Live') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paypal_client_id"
                                                                    class="col-form-label">{{ __('Client ID') }}</label>
                                                                <input type="text" name="paypal_client_id"
                                                                    id="paypal_client_id" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['paypal_client_id']) || is_null($company_payment_setting['paypal_client_id']) ? '' : $company_payment_setting['paypal_client_id'] }}"
                                                                    placeholder="{{ __('Client ID') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paypal_secret_key"
                                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                                <input type="text" name="paypal_secret_key"
                                                                    id="paypal_secret_key" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['paypal_secret_key']) || is_null($company_payment_setting['paypal_secret_key']) ? '' : $company_payment_setting['paypal_secret_key'] }}"
                                                                    placeholder="{{ __('Secret Key') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Paystack -->
                                    <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne3"
                                                    aria-expanded="false" aria-controls="collapseOne3">
                                                    <span class="d-flex align-items-center">

                                                        {{ __('Paystack') }}
                                                    </span>

                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_paystack_enabled" id="is_paystack_enabled"
                                                                {{ isset($company_payment_setting['is_paystack_enabled']) && $company_payment_setting['is_paystack_enabled'] == 'on' ? 'checked' : '' }}>

                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseOne3" class="accordion-collapse collapse"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paypal_client_id"
                                                                    class="col-form-label">{{ __('Public Key') }}</label>
                                                                <input type="text" name="paystack_public_key"
                                                                    id="paystack_public_key" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['paystack_public_key']) || is_null($company_payment_setting['paystack_public_key']) ? '' : $company_payment_setting['paystack_public_key'] }}"
                                                                    placeholder="{{ __('Public Key') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paystack_secret_key"
                                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                                <input type="text" name="paystack_secret_key"
                                                                    id="paystack_secret_key" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['paystack_secret_key']) || is_null($company_payment_setting['paystack_secret_key']) ? '' : $company_payment_setting['paystack_secret_key'] }}"
                                                                    placeholder="{{ __('Secret Key') }}">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- FLUTTERWAVE -->
                                    <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne4"
                                                    aria-expanded="false" aria-controls="collapseOne4">
                                                    <span class="d-flex align-items-center">

                                                        {{ __('Flutterware') }}
                                                    </span>

                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_flutterwave_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_flutterwave_enabled" id="is_flutterwave_enabled"
                                                                {{ isset($company_payment_setting['is_flutterwave_enabled']) && $company_payment_setting['is_flutterwave_enabled'] == 'on' ? 'checked' : '' }}>

                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseOne4" class="accordion-collapse collapse"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paypal_client_id"
                                                                    class="col-form-label">{{ __('Public Key') }}</label>
                                                                <input type="text" name="flutterwave_public_key"
                                                                    id="flutterwave_public_key" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['flutterwave_public_key']) || is_null($company_payment_setting['flutterwave_public_key']) ? '' : $company_payment_setting['flutterwave_public_key'] }}"
                                                                    placeholder="Public Key">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paystack_secret_key"
                                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                                <input type="text" name="flutterwave_secret_key"
                                                                    id="flutterwave_secret_key" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['flutterwave_secret_key']) || is_null($company_payment_setting['flutterwave_secret_key']) ? '' : $company_payment_setting['flutterwave_secret_key'] }}"
                                                                    placeholder="Secret Key">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Razorpay -->
                                    <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne5"
                                                    aria-expanded="false" aria-controls="collapseOne5">
                                                    <span class="d-flex align-items-center">

                                                        {{ __('Razorpay') }}
                                                    </span>

                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_razorpay_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_razorpay_enabled" id="is_razorpay_enabled"
                                                                {{ isset($company_payment_setting['is_razorpay_enabled']) && $company_payment_setting['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>

                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseOne5" class="accordion-collapse collapse"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paypal_client_id"
                                                                    class="col-form-label">{{ __('Public Key') }}</label>

                                                                <input type="text" name="razorpay_public_key"
                                                                    id="razorpay_public_key" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['razorpay_public_key']) || is_null($company_payment_setting['razorpay_public_key']) ? '' : $company_payment_setting['razorpay_public_key'] }}"
                                                                    placeholder="Public Key">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paystack_secret_key"
                                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                                <input type="text" name="razorpay_secret_key"
                                                                    id="razorpay_secret_key" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['razorpay_secret_key']) || is_null($company_payment_setting['razorpay_secret_key']) ? '' : $company_payment_setting['razorpay_secret_key'] }}"
                                                                    placeholder="Secret Key">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Mercado Pago -->
                                    <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne6"
                                                    aria-expanded="false" aria-controls="collapseOne6">
                                                    <span class="d-flex align-items-center">
                                                        {{ __('Mercado Pago') }}
                                                    </span>

                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_mercado_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_mercado_enabled" id="is_mercado_enabled"
                                                                {{ isset($company_payment_setting['is_mercado_enabled']) && $company_payment_setting['is_mercado_enabled'] == 'on' ? 'checked' : '' }}>

                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseOne6" class="accordion-collapse collapse"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-md-12 ">
                                                            <label class="coingate-label col-form-label"
                                                                for="mercado_mode">{{ __('Mercado Mode') }}</label> <br>
                                                            <div class="d-flex">
                                                                <div class="mr-2" style="margin-right: 15px;">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                <input type="radio"
                                                                                    name="mercado_mode" value="sandbox"
                                                                                    class="form-check-input"
                                                                                    {{ (isset($company_payment_setting['mercado_mode']) && $company_payment_setting['mercado_mode'] == '') || (isset($company_payment_setting['mercado_mode']) && $company_payment_setting['mercado_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                                {{ __('Sandbox') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mr-2">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                <input type="radio"
                                                                                    name="mercado_mode" value="live"
                                                                                    class="form-check-input"
                                                                                    {{ isset($company_payment_setting['mercado_mode']) && $company_payment_setting['mercado_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                {{ __('Live') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mercado_access_token"
                                                                    class="col-form-label">{{ __('Access Token') }}</label>
                                                                <input type="text" name="mercado_access_token"
                                                                    id="mercado_access_token" class="form-control"
                                                                    value="{{ isset($company_payment_setting['mercado_access_token']) ? $company_payment_setting['mercado_access_token'] : '' }}"
                                                                    placeholder="{{ __('Access Token') }}" />
                                                                @if ($errors->has('mercado_secret_key'))
                                                                    <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('mercado_access_token') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Paytm -->
                                    <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne7"
                                                    aria-expanded="false" aria-controls="collapseOne7">
                                                    <span class="d-flex align-items-center">
                                                        {{ __('Paytm') }}
                                                    </span>

                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_paytm_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_paytm_enabled" id="is_paytm_enabled"
                                                                {{ isset($company_payment_setting['is_paytm_enabled']) && $company_payment_setting['is_paytm_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseOne7" class="accordion-collapse collapse"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-md-12">
                                                            <label class="paypal-label col-form-label"
                                                                for="paypal_mode">{{ __('Paytm Environment') }}</label>
                                                            <br>
                                                            <div class="d-flex">
                                                                <div class="mr-2" style="margin-right: 15px;">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">

                                                                                <input type="radio" name="paytm_mode"
                                                                                    value="local"
                                                                                    class="form-check-input"
                                                                                    {{ !isset($company_payment_setting['paytm_mode']) || $company_payment_setting['paytm_mode'] == '' || $company_payment_setting['paytm_mode'] == 'local' ? 'checked="checked"' : '' }}>

                                                                                {{ __('Local') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mr-2">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                <input type="radio" name="paytm_mode"
                                                                                    value="production"
                                                                                    class="form-check-input"
                                                                                    {{ isset($company_payment_setting['paytm_mode']) && $company_payment_setting['paytm_mode'] == 'production' ? 'checked="checked"' : '' }}>

                                                                                {{ __('Production') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="paytm_public_key"
                                                                    class="col-form-label">{{ __('Merchant ID') }}</label>
                                                                <input type="text" name="paytm_merchant_id"
                                                                    id="paytm_merchant_id" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['paytm_merchant_id']) || is_null($company_payment_setting['paytm_merchant_id']) ? '' : $company_payment_setting['paytm_merchant_id'] }}"
                                                                    placeholder="Merchant ID">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="paytm_secret_key"
                                                                    class="col-form-label">{{ __('Merchant Key') }}</label>
                                                                <input type="text" name="paytm_merchant_key"
                                                                    id="paytm_merchant_key" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['paytm_merchant_key']) || is_null($company_payment_setting['paytm_merchant_key']) ? '' : $company_payment_setting['paytm_merchant_key'] }}"
                                                                    placeholder="Merchant Key">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="paytm_industry_type"
                                                                    class="col-form-label">{{ __('Industry Type') }}</label>
                                                                <input type="text" name="paytm_industry_type"
                                                                    id="paytm_industry_type" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['paytm_industry_type']) || is_null($company_payment_setting['paytm_industry_type']) ? '' : $company_payment_setting['paytm_industry_type'] }}"
                                                                    placeholder="Industry Type">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Mollie -->
                                    <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne8"
                                                    aria-expanded="false" aria-controls="collapseOne8">
                                                    <span class="d-flex align-items-center">
                                                        {{ __('Mollie') }}
                                                    </span>

                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_mollie_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_mollie_enabled" id="is_mollie_enabled"
                                                                {{ isset($company_payment_setting['is_mollie_enabled']) && $company_payment_setting['is_mollie_enabled'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseOne8" class="accordion-collapse collapse"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="mollie_api_key"
                                                                    class="col-form-label">{{ __('Mollie Api Key') }}</label>
                                                                <input type="text" name="mollie_api_key"
                                                                    id="mollie_api_key" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['mollie_api_key']) || is_null($company_payment_setting['mollie_api_key']) ? '' : $company_payment_setting['mollie_api_key'] }}"
                                                                    placeholder="Mollie Api Key">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="mollie_profile_id"
                                                                    class="col-form-label">{{ __('Mollie Profile ID') }}</label>
                                                                <input type="text" name="mollie_profile_id"
                                                                    id="mollie_profile_id" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['mollie_profile_id']) || is_null($company_payment_setting['mollie_profile_id']) ? '' : $company_payment_setting['mollie_profile_id'] }}"
                                                                    placeholder="Mollie Profile Id">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="mollie_partner_id"
                                                                    class="col-form-label">{{ __('Mollie Partner ID') }}</label>
                                                                <input type="text" name="mollie_partner_id"
                                                                    id="mollie_partner_id" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['mollie_partner_id']) || is_null($company_payment_setting['mollie_partner_id']) ? '' : $company_payment_setting['mollie_partner_id'] }}"
                                                                    placeholder="Mollie Partner Id">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Skrill -->
                                    <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne9"
                                                    aria-expanded="false" aria-controls="collapseOne9">
                                                    <span class="d-flex align-items-center">

                                                        {{ __('Skrill') }}
                                                    </span>

                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_skrill_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_skrill_enabled" id="is_skrill_enabled"
                                                                {{ isset($company_payment_setting['is_skrill_enabled']) && $company_payment_setting['is_skrill_enabled'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseOne9" class="accordion-collapse collapse"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mollie_api_key"
                                                                    class="col-form-label">{{ __('Skrill Email') }}</label>
                                                                <input type="text" name="skrill_email"
                                                                    id="skrill_email" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['skrill_email']) || is_null($company_payment_setting['skrill_email']) ? '' : $company_payment_setting['skrill_email'] }}"
                                                                    placeholder="Enter Skrill Email">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- CoinGate -->
                                    <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne10"
                                                    aria-expanded="false" aria-controls="collapseOne10">
                                                    <span class="d-flex align-items-center">
                                                        {{ __('CoinGate') }}
                                                    </span>

                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_coingate_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_coingate_enabled" id="is_coingate_enabled"
                                                                {{ isset($company_payment_setting['is_coingate_enabled']) && $company_payment_setting['is_coingate_enabled'] == 'on' ? 'checked' : '' }}>

                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseOne10" class="accordion-collapse collapse"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-md-12">
                                                            <label class="col-form-label"
                                                                for="coingate_mode">{{ __('CoinGate Mode') }}</label>
                                                            <br>
                                                            <div class="d-flex">
                                                                <div class="mr-2" style="margin-right: 15px;">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">

                                                                                <input type="radio"
                                                                                    name="coingate_mode" value="sandbox"
                                                                                    class="form-check-input"
                                                                                    {{ !isset($company_payment_setting['coingate_mode']) || $company_payment_setting['coingate_mode'] == '' || $company_payment_setting['coingate_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>

                                                                                {{ __('Sandbox') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mr-2">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                <input type="radio"
                                                                                    name="coingate_mode" value="live"
                                                                                    class="form-check-input"
                                                                                    {{ isset($company_payment_setting['coingate_mode']) && $company_payment_setting['coingate_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                {{ __('Live') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="coingate_auth_token"
                                                                    class="col-form-label">{{ __('CoinGate Auth Token') }}</label>
                                                                <input type="text" name="coingate_auth_token"
                                                                    id="coingate_auth_token" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['coingate_auth_token']) || is_null($company_payment_setting['coingate_auth_token']) ? '' : $company_payment_setting['coingate_auth_token'] }}"
                                                                    placeholder="CoinGate Auth Token">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- PaymentWall -->
                                    <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne11"
                                                    aria-expanded="false" aria-controls="collapseOne11">
                                                    <span class="d-flex align-items-center">

                                                        {{ __('PaymentWall') }}
                                                    </span>

                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_paymentwall_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_paymentwall_enabled"
                                                                id="is_paymentwall_enabled"
                                                                {{ isset($company_payment_setting['is_paymentwall_enabled']) && $company_payment_setting['is_paymentwall_enabled'] == 'on' ? 'checked' : '' }}>

                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseOne11" class="accordion-collapse collapse"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paymentwall_public_key"
                                                                    class="col-form-label">{{ __('Public Key') }}</label>
                                                                <input type="text" name="paymentwall_public_key"
                                                                    id="paymentwall_public_key" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['paymentwall_public_key']) || is_null($company_payment_setting['paymentwall_public_key']) ? '' : $company_payment_setting['paymentwall_public_key'] }}"
                                                                    placeholder="{{ __('Public Key') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paymentwall_secret_key"
                                                                    class="col-form-label">{{ __('Private Key') }}</label>
                                                                <input type="text" name="paymentwall_secret_key"
                                                                    id="paymentwall_secret_key" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['paymentwall_secret_key']) || is_null($company_payment_setting['paymentwall_secret_key']) ? '' : $company_payment_setting['paymentwall_secret_key'] }}"
                                                                    placeholder="{{ __('Private Key') }}">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Toyyibpay -->
                                    <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne12"
                                                    aria-expanded="false" aria-controls="collapseOne12">
                                                    <span class="d-flex align-items-center">

                                                        {{ __('Toyyibpay') }}
                                                    </span>

                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_toyyibpay_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_toyyibpay_enabled" id="is_toyyibpay_enabled"
                                                                {{ isset($company_payment_setting['is_toyyibpay_enabled']) && $company_payment_setting['is_toyyibpay_enabled'] == 'on' ? 'checked' : '' }}>

                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseOne12" class="accordion-collapse collapse"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row gy-4">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="toyyibpay_secret_key"
                                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                                <input type="text" name="toyyibpay_secret_key"
                                                                    id="toyyibpay_secret_key" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['toyyibpay_secret_key']) || is_null($company_payment_setting['toyyibpay_secret_key']) ? '' : $company_payment_setting['toyyibpay_secret_key'] }}"
                                                                    placeholder="{{ __('Secret Key') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="category_code"
                                                                    class="col-form-label">{{ __('Category Code') }}</label>
                                                                <input type="text" name="category_code"
                                                                    id="category_code" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['category_code']) || is_null($company_payment_setting['category_code']) ? '' : $company_payment_setting['category_code'] }}"
                                                                    placeholder="{{ __('Category Code') }}">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PayFast -->
                                    <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne13"
                                                    aria-expanded="false" aria-controls="collapseOne13">
                                                    <span class="d-flex align-items-center">
                                                        {{ __('PayFast') }}
                                                    </span>

                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">{{ __('Enable:') }}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_payfast_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_payfast_enabled" id="is_payfast_enabled"
                                                                {{ isset($company_payment_setting['is_payfast_enabled']) && $company_payment_setting['is_payfast_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseOne13" class="accordion-collapse collapse"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <label class="paypal-label col-form-label"
                                                            for="payfast_mode">{{ __('Payfast Mode') }}</label>
                                                        <div class="d-flex">
                                                            <div class="mr-2" style="margin-right: 15px;">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <label
                                                                            class="form-check-labe text-dark {{ isset($company_payment_setting['payfast_mode']) && $company_payment_setting['payfast_mode'] == 'sandbox' ? 'active' : '' }}">
                                                                            <input type="radio" name="payfast_mode"
                                                                                value="sandbox" class="form-check-input"
                                                                                {{ isset($company_payment_setting['payfast_mode']) && $company_payment_setting['payfast_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>

                                                                            {{ __('Sandbox') }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mr-2">
                                                                <div class="border card p-3">
                                                                    <div class="form-check">
                                                                        <label class="form-check-labe text-dark">
                                                                            <input type="radio" name="payfast_mode"
                                                                                value="live" class="form-check-input"
                                                                                {{ isset($company_payment_setting['payfast_mode']) && $company_payment_setting['payfast_mode'] == 'live' ? 'checked="checked"' : '' }}>

                                                                            {{ __('Live') }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="paytm_public_key"
                                                                    class="col-form-label">{{ __('Merchant ID') }}</label>
                                                                <input type="text" name="payfast_merchant_id"
                                                                    id="payfast_merchant_id" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['payfast_merchant_id']) || is_null($company_payment_setting['payfast_merchant_id']) ? '' : $company_payment_setting['payfast_merchant_id'] }}"
                                                                    placeholder="Merchant ID">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="paytm_secret_key"
                                                                    class="col-form-label">{{ __('Merchant Key') }}</label>
                                                                <input type="text" name="payfast_merchant_key"
                                                                    id="payfast_merchant_key" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['payfast_merchant_key']) || is_null($company_payment_setting['payfast_merchant_key']) ? '' : $company_payment_setting['payfast_merchant_key'] }}"
                                                                    placeholder="Merchant Key">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="payfast_signature"
                                                                    class="col-form-label">{{ __('Salt Passphrase') }}</label>
                                                                <input type="text" name="payfast_signature"
                                                                    id="payfast_signature" class="form-control"
                                                                    value="{{ !isset($company_payment_setting['payfast_signature']) || is_null($company_payment_setting['payfast_signature']) ? '' : $company_payment_setting['payfast_signature'] }}"
                                                                    placeholder="Industry Type">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <div class="form-group">
                                    <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit"
                                        value="{{ __('Save Changes') }}">
                                </div>
                            </div>
                            </form>
                        </div>


                    </div>

                    <!--Twilio Setting-->
                    <div id="useradd-8" class="card">
                        <div class="card-header">
                            <h5>{{ __('Twilio Settings') }}</h5>
                            <small class="text-muted">{{ __('Edit your company twilio setting details') }}</small>
                        </div>

                        <div class="card-body">
                            {{ Form::model($settings, ['route' => 'twilio.settings', 'method' => 'post']) }}
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('twilio_sid', __('Twilio SID '), ['class' => 'form-label']) }}
                                        {{ Form::text('twilio_sid', isset($settings['twilio_sid']) ? $settings['twilio_sid'] : '', ['class' => 'form-control w-100', 'placeholder' => __('Enter Twilio SID'), 'required' => 'required']) }}
                                        @error('twilio_sid')
                                            <span class="invalid-twilio_sid" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('twilio_token', __('Twilio Token'), ['class' => 'form-label']) }}
                                        {{ Form::text('twilio_token', isset($settings['twilio_token']) ? $settings['twilio_token'] : '', ['class' => 'form-control w-100', 'placeholder' => __('Enter Twilio Token'), 'required' => 'required']) }}
                                        @error('twilio_token')
                                            <span class="invalid-twilio_token" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('twilio_from', __('Twilio From'), ['class' => 'form-label']) }}
                                        {{ Form::text('twilio_from', isset($settings['twilio_from']) ? $settings['twilio_from'] : '', ['class' => 'form-control w-100', 'placeholder' => __('Enter Twilio From'), 'required' => 'required']) }}
                                        @error('twilio_from')
                                            <span class="invalid-twilio_from" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-12 mt-4 mb-2">
                                    <h5 class="small-title">{{ __('Module Settings') }}</h5>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <div class=" form-switch form-switch-right">
                                                <span>{{ __('New Customer') }}</span>
                                                {{ Form::checkbox('customer_notification', '1', isset($settings['customer_notification']) && $settings['customer_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'customer_notification']) }}
                                                <label class="form-check-label" for="customer_notification"></label>
                                            </div>

                                        </li>
                                        <li class="list-group-item">
                                            <div class=" form-switch form-switch-right">
                                                <span>{{ __('New Vendor') }}</span>
                                                {{ Form::checkbox('vender_notification', '1', isset($settings['vender_notification']) && $settings['vender_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'vender_notification']) }}
                                                <label class="form-check-label" for="vender_notification"></label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <div class=" form-switch form-switch-right">
                                                <span>{{ __('New Invoice') }}</span>
                                                {{ Form::checkbox('invoice_notification', '1', isset($settings['invoice_notification']) && $settings['invoice_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'invoice_notification']) }}
                                                <label class="form-check-label" for="invoice_notification"></label>
                                            </div>
                                        </li>

                                        <li class="list-group-item">
                                            <div class=" form-switch form-switch-right">
                                                <span>{{ __('New Revenue') }}</span>
                                                {{ Form::checkbox('revenue_notification', '1', isset($settings['revenue_notification']) && $settings['revenue_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'revenue_notification']) }}
                                                <label class="form-check-label" for="revenue_notification"></label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <div class=" form-switch form-switch-right">
                                                <span>{{ __('New Bill') }}</span>
                                                {{ Form::checkbox('bill_notification', '1', isset($settings['bill_notification']) && $settings['bill_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'bill_notification']) }}
                                                <label class="form-check-label" for="bill_notification"></label>
                                            </div>
                                        </li>

                                        <li class="list-group-item">
                                            <div class=" form-switch form-switch-right">
                                                <span>{{ __('New Proposal') }}</span>
                                                {{ Form::checkbox('proposal_notification', '1', isset($settings['proposal_notification']) && $settings['proposal_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'proposal_notification']) }}
                                                <label class="form-check-label" for="proposal_notification"></label>
                                            </div>
                                        </li>

                                    </ul>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <div class=" form-switch form-switch-right">
                                                <span>{{ __('New Payment') }}</span>
                                                {{ Form::checkbox('payment_notification', '1', isset($settings['payment_notification']) && $settings['payment_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'payment_notification']) }}
                                                <label class="form-check-label" for="payment_notification"></label>
                                            </div>
                                        </li>

                                        <li class="list-group-item">
                                            <div class=" form-switch form-switch-right">
                                                <span>{{ __('Invoice Reminder') }}</span>
                                                {{ Form::checkbox('reminder_notification', '1', isset($settings['reminder_notification']) && $settings['reminder_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'reminder_notification']) }}
                                                <label class="form-check-label" for="reminder_notification"></label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>



                            </div>
                            <div class="card-footer text-end">
                                <div class="form-group">
                                    <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit"
                                        value="{{ __('Save Changes') }}">
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>

                    </div>

                    <!--Email Notification Setting-->
                    {{-- <div id="useradd-9" class="card">
                        <!-- <form method="POST" action="{{ route('recaptcha.settings.store') }}" accept-charset="UTF-8">  -->
                        <!-- @csrf -->
                        <div class="col-md-12">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <h5>{{ __('Email Notification Settings') }}</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <!-- <div class=""> -->
                                    @foreach ($EmailTemplates as $EmailTemplate)
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            <div class="list-group">
                                                <div class="list-group-item form-switch form-switch-right">
                                                    <label class="form-label"
                                                        style="margin-left:5%;">{{ $EmailTemplate->name }}</label>

                                                    <input class="form-check-input email-template-checkbox"
                                                        id="email_tempalte_{{ !empty($EmailTemplate->template) ? $EmailTemplate->template->id : '' }}"
                                                        type="checkbox"
                                                        @if (!empty($EmailTemplate->template) ? $EmailTemplate->template->is_active : 0 == 1) checked="checked" @endif
                                                        type="checkbox"
                                                        value="{{ !empty($EmailTemplate->template) ? $EmailTemplate->template->is_active : 1 }}"
                                                        data-url="{{ route('status.email.language', [!empty($EmailTemplate->template) ? $EmailTemplate->template->id : '']) }}" />
                                                    <label class="form-check-label"
                                                        for="email_tempalte_{{ !empty($EmailTemplate->template) ? $EmailTemplate->template->id : '' }}"></label>



                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <!-- </div> -->
                                </div>
                                <!-- <div class="card-footer p-0">
                                                                                                            <div class="col-sm-12 mt-3 px-2">
                                                                                                                <div class="text-end">
                                                                                                                    <input class="btn btn-print-invoice  btn-primary " type="submit" value="{{ __('Save Changes') }}">
                                                                                                                </div>
                                                                                                            </div>

                                                                                                        </div> -->
                            </div>
                        </div>
                        <!-- </form>  -->
                    </div> --}}

                    <!--Email Notification Setting-->
                    <div id="useradd-9" class="card">

                        {{ Form::model($settings, ['route' => ['status.email.language'], 'method' => 'post']) }}
                        @csrf
                        <div class="col-md-12">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <h5>{{ __('Email Notification Settings') }}</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <!-- <div class=""> -->
                                    @foreach ($EmailTemplates as $EmailTemplate)
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            <div class="list-group">
                                                <div class="list-group-item form-switch form-switch-right">
                                                    <label class="form-label"
                                                        style="margin-left:5%;">{{ $EmailTemplate->name }}</label>

                                                    <input class="form-check-input" name='{{ $EmailTemplate->id }}'
                                                        id="email_tempalte_{{ $EmailTemplate->template->id }}"
                                                        type="checkbox"
                                                        @if ($EmailTemplate->template->is_active == 1) checked="checked" @endif
                                                        type="checkbox" value="1"
                                                        data-url="{{ route('status.email.language', [$EmailTemplate->template->id]) }}" />
                                                    <label class="form-check-label"
                                                        for="email_tempalte_{{ $EmailTemplate->template->id }}"></label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <!-- </div> -->
                                </div>
                                <div class="card-footer p-0">
                                    <div class="col-sm-12 mt-3 px-2">
                                        <div class="text-end">
                                            <input class="btn btn-print-invoice  btn-primary " type="submit"
                                                value="{{ __('Save Changes') }}">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>

                    <!--Webhook Setting-->
                    <div class="" id="useradd-11">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5>{{ __('Web Hook Settings') }}</h5>
                                <a href="#" data-size="md" data-url="{{ route('webhook.create') }}"
                                    data-ajax-popup="true" data-bs-toggle="tooltip"
                                    title="{{ __('Create New User') }}" class="btn btn-sm btn-primary">
                                    <i class="ti ti-plus"></i>
                                </a>
                            </div>
                            <div class="card-body table-border-style ">
                                <div class="table-responsive">
                                    <table class="table" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th> {{ __('Modules') }}</th>
                                                <th> {{ __('url') }}</th>
                                                <th> {{ __('Method') }}</th>
                                                <th width="200px"> {{ 'Action' }}</th>
                                            </tr>
                                        </thead>
                                        @php
                                            $webhooks = App\Models\Webhook::where('created_by', Auth::user()->id)->get();
                                        @endphp
                                        <tbody>
                                            @foreach ($webhooks as $webhook)
                                                <tr class="Action">
                                                    <td class="sorting_1">
                                                        {{ $webhook->module }}</td>
                                                    <td class="sorting_3">
                                                        {{ $webhook->url }}</td>
                                                    <td class="sorting_2">
                                                        {{ $webhook->method }}</td>
                                                    <td class="">
                                                        <div class="action-btn bg-info ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ route('webhook.edit', $webhook->id) }}"
                                                                data-size="md" data-ajax-popup="true"
                                                                data-title="{{ __('Edit Webhook') }}"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-original-title="{{ __('Edit') }}"
                                                                data-bs-placement="top" class="edit-icon"
                                                                data-original-title="{{ __('Edit') }}"><i
                                                                    class="ti ti-pencil text-white"></i></a>
                                                        </div>
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['webhook.destroy', $webhook->id],
                                                                'id' => 'delete-form-' . $webhook->id,
                                                            ]) !!}
                                                            <a href="#!" data-bs-toggle="tooltip"
                                                                data-bs-original-title="{{ __('Delete') }}"
                                                                data-bs-placement="top"
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                                title="{{ __('Delete') }}">
                                                                <i class="ti ti-trash text-white"></i></a>
                                                            {!! Form::close() !!}
                                                        </div>

                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    @endsection



    @push('script-page')
        <script>
            $(document).on('click', 'input[name="theme_color"]', function() {
                var eleParent = $(this).attr('data-theme');
                $('#themefile').val(eleParent);
                var imgpath = $(this).attr('data-imgpath');
                $('.' + eleParent + '_img').attr('src', imgpath);
            });

            $(document).ready(function() {
                setTimeout(function(e) {
                    var checked = $("input[type=radio][name='theme_color']:checked");
                    $('#themefile').val(checked.attr('data-theme'));
                    $('.' + checked.attr('data-theme') + '_img').attr('src', checked.attr('data-imgpath'));
                }, 300);
            });

            function check_theme(color_val) {

                $('.theme-color').prop('checked', false);
                $('input[value="' + color_val + '"]').prop('checked', true);
                $('#color_value').val(color_val);
            }
        </script>
    @endpush

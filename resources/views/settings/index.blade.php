@extends('layouts.admin')
@section('page-title')
    {{ __('Settings') }}
@endsection
@php
    use App\Models\Utility;
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $meta_image = \App\Models\Utility::get_file('uploads/metaevent/');
    
    $lang = \App\Models\Utility::getValByName('default_language');
    $file_type = config('files_types');
    $setting = App\Models\Utility::settings();
    $color = !empty($setting['color']) ? $setting['color'] : 'theme-3';
    $logo_light = \App\Models\Utility::getValByName('company_logo_light');
    $logo_dark = \App\Models\Utility::getValByName('company_logo_dark');
    
    $local_storage_validation = $setting['local_storage_validation'];
    $local_storage_validations = explode(',', $local_storage_validation);
    
    $s3_storage_validation = $setting['s3_storage_validation'];
    $s3_storage_validations = explode(',', $s3_storage_validation);
    
    $wasabi_storage_validation = $setting['wasabi_storage_validation'];
    $wasabi_storage_validations = explode(',', $wasabi_storage_validation);
@endphp




@push('script-page')
    @if ($color == 'theme-3')
        <style>
            .btn-check:checked+.btn-outline-success,
            .btn-check:active+.btn-outline-success,
            .btn-outline-success:active,
            .btn-outline-success.active,
            .btn-outline-success.dropdown-toggle.show {
                color: #ffffff;
                background-color: #6fd943 !important;
                border-color: #6fd943 !important;

            }

            .btn-outline-success:hover {
                color: #ffffff;
                background-color: #6fd943 !important;
                border-color: #6fd943 !important;
            }

            .btn.btn-outline-success {
                color: #6fd943;
                border-color: #6fd943 !important;
            }
        </style>
    @endif
    @if ($color == 'theme-2')
        <style>
            .btn-check:checked+.btn-outline-success,
            .btn-check:active+.btn-outline-success,
            .btn-outline-success:active,
            .btn-outline-success.active,
            .btn-outline-success.dropdown-toggle.show {
                color: #ffffff;
                background: linear-gradient(141.55deg, rgbisset($setting['cookie_consent']) && $setting['cookie_consent']=='on'? '' : 'disabled'a(240, 244, 243, 0) 3.46%, #4ebbd3 99.86%)#1f3996 !important;
                border-color: #1F3996 !important;

            }

            .btn-outline-success:hover {
                color: #ffffff;
                background: linear-gradient(141.55deg, rgba(240, 244, 243, 0) 3.46%, #4ebbd3 99.86%)#1f3996 !important;
                border-color: #1F3996 !important;
            }

            .btn.btn-outline-success {
                color: #1F3996;
                border-color: #1F3996 !important;
            }
        </style>
    @endif
    @if ($color == 'theme-4')
        <style>
            .btn-check:checked+.btn-outline-success,
            .btn-check:active+.btn-outline-success,
            .btn-outline-success:active,
            .btn-outline-success.active,
            .btn-outline-success.dropdown-toggle.show {
                color: #ffffff;
                background-color: #584ed2 !important;
                border-color: #584ed2 !important;

            }

            .btn-outline-success:hover {
                color: #ffffff;
                background-color: #584ed2 !important;
                border-color: #584ed2 !important;
            }

            .btn.btn-outline-success {
                color: #584ed2;
                border-color: #584ed2 !important;
            }
        </style>
    @endif
    @if ($color == 'theme-1')
        <style>
            .btn-check:checked+.btn-outline-success,
            .btn-check:active+.btn-outline-success,
            .btn-outline-success:active,
            .btn-outline-success.active,
            .btn-outline-success.dropdown-toggle.show {
                color: #ffffff;
                background: linear-gradient(141.55deg, rgba(81, 69, 157, 0) 3.46%, rgba(255, 58, 110, 0.6) 99.86%), #51459d !important;
                border-color: #51459d !important;

            }

            .btn-outline-success:hover {
                color: #ffffff;
                background: linear-gradient(141.55deg, rgba(81, 69, 157, 0) 3.46%, rgba(255, 58, 110, 0.6) 99.86%), #51459d !important;
                border-color: #51459d !important;
            }

            .btn.btn-outline-success {
                color: #51459d;
                border-color: #51459d !important;
            }
        </style>
    @endif
    
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
                $('#style').attr('href','{{env("APP_URL")}}'+'/public/assets/css/style-dark.css');

                $('.dash-sidebar .main-logo a img').attr('src','{{$logo.$logo_light}}');

            } else {
                $('#style').attr('href','{{env("APP_URL")}}'+'/public/assets/css/style.css');
                $('.dash-sidebar .main-logo a img').attr('src','{{$logo.$logo_dark}}');

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
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,
        })
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
    {{-- <script type="text/javascript">
        $(document).ready(function()
        {
            cookie_inputs();
        });
    </script>
    <script>
        function cookie_inputs() {
            if ($('#enable_cookie').prop('checked')==true)
            {
            $('.cookie_input').removeAttr("disabled");
            }
            else
            {
                $('.cookie_input').attr("disabled", "true");
            }
        }
    </script> --}}


    <script type="text/javascript">
        function enablecookie() {
            const element = $('#enable_cookie').is(':checked');
            $('.cookieDiv').addClass('disabledCookie');
            if (element == true) {
                $('.cookieDiv').removeClass('disabledCookie');
                $("#cookie_logging").attr('checked', true);
            } else {
                $('.cookieDiv').addClass('disabledCookie');
                $("#cookie_logging").attr('checked', false);
            }
        }
    </script>


    <script>
        $(document).on("click", '.send_email', function(e) {

            e.preventDefault();
            var title = $(this).attr('data-title');

            var size = 'md';
            var url = $(this).attr('data-url');
            if (typeof url != 'undefined') {
                $("#commonModal .modal-title").html(title);
                $("#commonModal .modal-dialog").addClass('modal-' + size);
                $("#commonModal").modal('show');

                $.post(url, {
                    _token: '{{ csrf_token() }}',
                    mail_driver: $("#mail_driver").val(),
                    mail_host: $("#mail_host").val(),
                    mail_port: $("#mail_port").val(),
                    mail_username: $("#mail_username").val(),
                    mail_password: $("#mail_password").val(),
                    mail_encryption: $("#mail_encryption").val(),
                    mail_from_address: $("#mail_from_address").val(),
                    mail_from_name: $("#mail_from_name").val(),
                }, function(data) {
                    $('#commonModal .body').html(data);
                });
            }
        });


        $(document).on('submit', '#test_email', function(e) {
            e.preventDefault();
            $("#email_sending").show();
            var post = $(this).serialize();
            var url = $(this).attr('action');
            $.ajax({
                type: "post",
                url: url,
                data: post,
                cache: false,
                beforeSend: function() {
                    $('#test_email .btn-create').attr('disabled', 'disabled');
                },
                success: function(data) {
                    if (data.is_success) {
                        show_toastr('success', data.message, 'success');
                    } else {
                        show_toastr('error', data.message, 'error');
                    }
                    $("#email_sending").hide();
                    $('#commonModal').modal('hide');
                },
                complete: function() {
                    $('#test_email .btn-create').removeAttr('disabled');
                },
            });
        });
    </script>
    {{-- <script>
        $(document).ready(function() {
            $('#cookie_logging').trigger('change');
        });
        $(document).on('change', '#cookie_logging', function(e) {
            $('.cookie_download').hide();
            if ($("#cookie_logging").prop('checked') == true) {
                $('.cookie_download').show();
            }
        });
    </script> --}}



    {{-- <script>
        $(document).ready(function() {
            $('#gdpr_cookie').trigger('change');
        });
        $(document).on('change', '#gdpr_cookie', function(e) {
            $('.gdpr_cookie_text').hide();
            if ($("#gdpr_cookie").prop('checked') == true) {
                $('.gdpr_cookie_text').show();
            }
        });
    </script> --}}
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
                                class="list-group-item list-group-item-action border-0">{{ __('Email Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-3"
                                class="list-group-item list-group-item-action border-0">{{ __('Payment Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-4"
                                class="list-group-item list-group-item-action border-0">{{ __('ReCaptcha Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-5"
                                class="list-group-item list-group-item-action border-0">{{ __('Storage Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-6"
                                class="list-group-item list-group-item-action border-0">{{ __('SEO Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-7"
                                class="list-group-item list-group-item-action border-0">{{ __('Cookie Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-8"
                                class="list-group-item list-group-item-action border-0">{{ __('Cache Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9">

                    <!--Site Setting-->
                    <div id="useradd-1" class="card">

                        <div class="card-header">
                            <h5>{{ __('Brand Settings') }}</h5>
                        </div>
                        {{ Form::model($settings, ['url' => 'settings', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}

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
                                                        {{-- <a href="{{$logo.(isset($logo_dark) && !empty($logo_dark)?$logo_dark:'logo-dark.png')}}" target="_blank"> --}}
                                                        <img id="blah" alt="your image"
                                                            src="{{ $logo . (isset($logo_dark) && !empty($logo_dark) ? $logo_dark : 'logo-dark.png') }}"
                                                            width="150px" class="big-logo">
                                                    </a>


                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="full_logo">
                                                        <div class=" bg-primary company_logo_update"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="logo_dark" id="full_logo"
                                                            class="form-control file" data-filename="full_logo"
                                                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">


                                                        <!-- <input type="file" name="logo_dark" id="full_logo" class="form-control file" data-filename="full_logo"> -->
                                                    </label>
                                                </div>
                                                @error('full_logo')
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

                                                    <!-- <img src="{{ $logo . 'logo-light.png' }}" class="big-logo img_setting" width="150px"> -->
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="logo_light">
                                                        <div class=" bg-primary dark_logo_update"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="logo_light" id="logo_light"
                                                            class="form-control file" data-filename="dark_logo_update"
                                                            onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])">


                                                        <!-- <input type="file" class="form-control file" name="logo_light" id="logo_light"
                                                                                   data-filename="logo_light"> -->
                                                    </label>
                                                </div>
                                                @error('logo_light')
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
                                                            src="{{ $logo . (isset($company_favicon) && !empty($company_favicon) ? $company_ficon : 'favicon.png') }}"
                                                            width="50px" class="big-logo img_setting">
                                                    </a>

                                                    <!-- <img src="{{ $logo . 'favicon.png' }}" width="150px" class="big-logo img_setting"> -->
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="favicon">
                                                        <div class="bg-primary company_favicon_update"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="favicon" id="favicon"
                                                            class="form-control file"
                                                            data-filename="company_favicon_update"
                                                            onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])">


                                                        <!-- <input type="file" class="form-control file"  id="favicon" name="favicon"
                                                                                   data-filename="favicon"> -->
                                                    </label>
                                                </div>
                                                @error('favicon')
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

                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('title_text', __('Title Text'), ['class' => 'form-label']) }}
                                            {{ Form::text('title_text', null, ['class' => 'form-control', 'placeholder' => __('Title Text')]) }}
                                            @error('title_text')
                                                <span class="invalid-title_text" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('footer_text', __('Footer Text'), ['class' => 'form-label']) }}
                                            {{ Form::text('footer_text', Utility::getValByName('footer_text'), ['class' => 'form-control', 'placeholder' => __('Enter Footer Text')]) }}
                                            @error('footer_text')
                                                <span class="invalid-footer_text" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('default_language', __('Default Language'), ['class' => 'form-label text-dark']) }}
                                            <div class="changeLanguage">
                                                <select name="default_language" id="default_language"
                                                    class="form-control select">
                                                    @foreach (\App\Models\Utility::languages() as $language)
                                                        <option @if ($lang == $language) selected @endif
                                                            value="{{ $language }}">
                                                            {{ Str::upper($language) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('default_language')
                                                <span class="invalid-default_language" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col">
                                        <div class="row">
                                            <div class="col-3 my-auto">
                                                <div class="form-group">
                                                    <label class="text-dark mb-1 mt-3"
                                                        for="SITE_RTL">{{ __('Enable RTL') }}</label>
                                                    <div class="">
                                                        <input type="checkbox" name="SITE_RTL" id="SITE_RTL"
                                                            data-toggle="switchbutton"
                                                            {{ $settings['SITE_RTL'] == 'on' ? 'checked="checked"' : '' }}
                                                            data-onstyle="primary">
                                                        <label class="form-check-labe" for="SITE_RTL"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3 my-auto">
                                                <div class="form-group">
                                                    <label class="text-dark mb-1 mt-3"
                                                        for="display_landing_page">{{ __('Enable Landing Page') }}</label>
                                                    <div class="">
                                                        <input type="checkbox" name="display_landing_page"
                                                            class="form-check-input gdpr_fulltime gdpr_type"
                                                            id="display_landing_page" data-toggle="switchbutton"
                                                            {{ Utility::getValByName('display_landing_page') == 'on' ? 'checked' : '' }}
                                                            data-onstyle="primary">
                                                        <label class="form-check-labe" for="display_landing_page"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3 my-auto">
                                                <div class="form-group">
                                                    <label class="text-dark mb-1 mt-3"
                                                        for="signup_button">{{ __('Enable Sign-Up Page') }}</label>
                                                    <div class="">
                                                        <input type="checkbox" name="enable_signup" id="enable_signup"
                                                            data-toggle="switchbutton"
                                                            {{ $settings['enable_signup'] == 'on' ? 'checked="checked"' : '' }}
                                                            data-onstyle="primary">
                                                        <label class="form-check-labe" for="enable_signup"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-4 my-auto">
                                                <div class="form-group ">
                                                    <label class="text-dark mb-1 mt-3"
                                                        for="gdpr_cookie">{{ __('GDPR Notification') }}</label>
                                                    <div class="">
                                                        <input type="checkbox" class="gdpr_fulltime gdpr_type"
                                                            name="gdpr_cookie" id="gdpr_cookie"
                                                            data-toggle="switchbutton"
                                                            {{ isset($settings['gdpr_cookie']) && $settings['gdpr_cookie'] == 'on' ? 'checked="checked"' : '' }}
                                                            data-onstyle="primary">
                                                        <label class="form-check-labe" for="gdpr_cookie"></label>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <div class="col-3 my-auto">
                                                <div class="form-group">
                                                    <label class="text-dark mb-1 mt-3"
                                                        for="email_verification">{{ __('Enable Email Verification') }}</label>
                                                    <div class="">
                                                        <input type="checkbox" name="email_verification"
                                                            id="email_verification" data-toggle="switchbutton"
                                                            {{ $settings['email_verification'] == 'on' ? 'checked="checked"' : '' }}
                                                            data-onstyle="primary">
                                                        <label class="form-check-labe" for="email_verification"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row">

                                    <div class="form-group col-12">
                                        {{ Form::label('cookie_text', __('GDPR Cookie Text'), ['class' => 'gdpr_cookie_text']) }}
                                        {!! Form::textarea(
                                            'cookie_text',
                                            isset($settings['cookie_text']) && $settings['cookie_text'] ? $settings['cookie_text'] : '',
                                            ['class' => 'form-control gdpr_cookie_text', 'style' => 'display: hidden;resize: none;', 'rows' => '4'],
                                        ) !!}
                                    </div>

                                </div> --}}
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
                                                <a href="#!" class="{{($settings['color'] == 'theme-1') ? 'active_color' : ''}}" data-value="theme-1" onclick="check_theme('theme-1')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-1" style="display: none;">
                                                <a href="#!" class="{{($settings['color'] == 'theme-2') ? 'active_color' : ''}} " data-value="theme-2" onclick="check_theme('theme-2')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-2" style="display: none;">
                                                <a href="#!" class="{{($settings['color'] == 'theme-3') ? 'active_color' : ''}}" data-value="theme-3" onclick="check_theme('theme-3')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-3" style="display: none;">
                                                <a href="#!" class="{{($settings['color'] == 'theme-4') ? 'active_color' : ''}}" data-value="theme-4" onclick="check_theme('theme-4')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-4" style="display: none;">
                                                <a href="#!" class="{{($settings['color'] == 'theme-5') ? 'active_color' : ''}}" data-value="theme-5" onclick="check_theme('theme-5')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-5" style="display: none;">
                                                <br>
                                                <a href="#!" class="{{($settings['color'] == 'theme-6') ? 'active_color' : ''}}" data-value="theme-6" onclick="check_theme('theme-6')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-6" style="display: none;">
                                                <a href="#!" class="{{($settings['color'] == 'theme-7') ? 'active_color' : ''}}" data-value="theme-7" onclick="check_theme('theme-7')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-7" style="display: none;">
                                                <a href="#!" class="{{($settings['color'] == 'theme-8') ? 'active_color' : ''}}" data-value="theme-8" onclick="check_theme('theme-8')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-8" style="display: none;">
                                                <a href="#!" class="{{($settings['color'] == 'theme-9') ? 'active_color' : ''}}" data-value="theme-9" onclick="check_theme('theme-9')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-9" style="display: none;">
                                                <a href="#!" class="{{($settings['color'] == 'theme-10') ? 'active_color' : ''}}" data-value="theme-10" onclick="check_theme('theme-10')"></a>
                                                <input type="radio" class="theme_color" name="color" value="theme-10" style="display: none;">
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
                    <!--Email Setting-->
                    <div id="useradd-2" class="card">
                        <div class="card-header">
                            <h5>{{ __('Email Settings') }}</h5>
                        </div>
                        <div class="card-body">
                            {{ Form::open(['route' => 'email.settings', 'method' => 'post']) }}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('mail_driver', __('Mail Driver'), ['class' => 'form-label']) }}
                                        {{ Form::text('mail_driver', env('MAIL_DRIVER'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Driver')]) }}
                                        @error('mail_driver')
                                            <span class="invalid-mail_driver" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('mail_host', __('Mail Host'), ['class' => 'form-label']) }}
                                        {{ Form::text('mail_host', env('MAIL_HOST'), ['class' => 'form-control ', 'placeholder' => __('Enter Mail Host')]) }}
                                        @error('mail_host')
                                            <span class="invalid-mail_driver" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('mail_port', __('Mail Port'), ['class' => 'form-label']) }}
                                        {{ Form::text('mail_port', env('MAIL_PORT'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Port')]) }}
                                        @error('mail_port')
                                            <span class="invalid-mail_port" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('mail_username', __('Mail Username'), ['class' => 'form-label']) }}
                                        {{ Form::text('mail_username', env('MAIL_USERNAME'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Username')]) }}
                                        @error('mail_username')
                                            <span class="invalid-mail_username" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('mail_password', __('Mail Password'), ['class' => 'form-label']) }}
                                        {{ Form::text('mail_password', env('MAIL_PASSWORD'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Password')]) }}
                                        @error('mail_password')
                                            <span class="invalid-mail_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'form-label']) }}
                                        {{ Form::text('mail_encryption', env('MAIL_ENCRYPTION'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Encryption')]) }}
                                        @error('mail_encryption')
                                            <span class="invalid-mail_encryption" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>



                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('mail_from_address', __('Mail From Address'), ['class' => 'form-label']) }}
                                        {{ Form::text('mail_from_address', env('MAIL_FROM_ADDRESS'), ['class' => 'form-control', 'placeholder' => __('Enter Mail From Address')]) }}
                                        @error('mail_from_address')
                                            <span class="invalid-mail_from_address" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('mail_from_name', __('Mail From Name'), ['class' => 'form-label']) }}
                                        {{ Form::text('mail_from_name', env('MAIL_FROM_NAME'), ['class' => 'form-control', 'placeholder' => __('Enter Mail From Name')]) }}
                                        @error('mail_from_name')
                                            <span class="invalid-mail_from_name" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                            </div>


                            <div class="card-footer ">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <a href="#" class="btn btn-primary  send_email"
                                            data-title="{{ __('Send Test Mail') }}" data-url="{{ route('test.mail') }}">
                                            {{ __('Send Test Mail') }}
                                        </a>
                                        {{-- <a href="#" data-url="{{ route('test.mail') }}" data-ajax-popup="true"
                                                data-title="{{ __('Send Test Mail') }}" class="btn btn-primary ">
                                                {{ __('Send Test Mail') }}
                                            </a> --}}
                                    </div>
                                    <div class="form-group col-md-6 text-end">
                                        <input class="btn btn-primary" type="submit" value="{{ __('Save Changes') }}">
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>


                    <!--Payment Setting-->
                    <div id="useradd-3" class="card">
                        <div class="card-header">
                            <h5>{{ __('Payment Settings') }}</h5>
                            <small
                                class="text-muted">{{ __('These details will be used to collect subscription plan payments. Each subscription plan will have a payment button based on the below configuration.') }}</small>
                        </div>
                        <div class="card-body">
                            {{ Form::open(['route' => 'payment.settings', 'method' => 'post']) }}
                            @csrf
                            <div class="faq justify-content-center">
                                <div class="col-sm-12 col-md-10 col-xxl-12">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('currency', __('Currency *'), ['class' => 'form-label']) }}
                                                {{ Form::text('currency', env('CURRENCY'), ['class' => 'form-control font-style', 'required', 'placeholder' => __('Enter Currency')]) }}
                                                <small>
                                                    {{ __('Note: Add currency code as per three-letter ISO code.') }}<br>
                                                    <a href="https://stripe.com/docs/currencies"
                                                        target="_blank">{{ __('you can find out here..') }}</a></small>
                                                <br>
                                                @error('currency')
                                                    <span class="invalid-currency" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('currency_symbol', __('Currency Symbol *'), ['class' => 'form-label']) }}
                                                {{ Form::text('currency_symbol', env('CURRENCY_SYMBOL'), ['class' => 'form-control', 'required', 'placeholder' => __('Enter Currency Symbol')]) }}
                                                @error('currency_symbol')
                                                    <span class="invalid-currency_symbol" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>


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
                                                        <span class="me-2">{{__('Enable:')}}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_stripe_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_stripe_enabled" id="is_stripe_enabled"
                                                                {{ isset($admin_payment_setting['is_stripe_enabled']) && $admin_payment_setting['is_stripe_enabled'] == 'on' ? 'checked="checked"' : '' }}>

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
                                                                    value="{{ !isset($admin_payment_setting['stripe_key']) || is_null($admin_payment_setting['stripe_key']) ? '' : $admin_payment_setting['stripe_key'] }}"
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
                                                                    value="{{ !isset($admin_payment_setting['stripe_secret']) || is_null($admin_payment_setting['stripe_secret']) ? '' : $admin_payment_setting['stripe_secret'] }}"
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
                                                        <span class="me-2">{{__('Enable:')}}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_paypal_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_paypal_enabled" id="is_paypal_enabled"
                                                                {{ isset($admin_payment_setting['is_paypal_enabled']) && $admin_payment_setting['is_paypal_enabled'] == 'on' ? 'checked="checked"' : '' }}>

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
                                                                                class="form-check-labe text-dark {{ isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == 'sandbox' ? 'active' : '' }}">
                                                                                <input type="radio" name="paypal_mode"
                                                                                    value="sandbox"
                                                                                    class="form-check-input"
                                                                                    {{ isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>

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
                                                                                    {{ isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == 'live' ? 'checked="checked"' : '' }}>

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
                                                                    value="{{ !isset($admin_payment_setting['paypal_client_id']) || is_null($admin_payment_setting['paypal_client_id']) ? '' : $admin_payment_setting['paypal_client_id'] }}"
                                                                    placeholder="{{ __('Client ID') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paypal_secret_key"
                                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                                <input type="text" name="paypal_secret_key"
                                                                    id="paypal_secret_key" class="form-control"
                                                                    value="{{ !isset($admin_payment_setting['paypal_secret_key']) || is_null($admin_payment_setting['paypal_secret_key']) ? '' : $admin_payment_setting['paypal_secret_key'] }}"
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
                                                        <span class="me-2">{{__('Enable:')}}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_paystack_enabled" id="is_paystack_enabled"
                                                                {{ isset($admin_payment_setting['is_paystack_enabled']) && $admin_payment_setting['is_paystack_enabled'] == 'on' ? 'checked' : '' }}>

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
                                                                    value="{{ !isset($admin_payment_setting['paystack_public_key']) || is_null($admin_payment_setting['paystack_public_key']) ? '' : $admin_payment_setting['paystack_public_key'] }}"
                                                                    placeholder="{{ __('Public Key') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paystack_secret_key"
                                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                                <input type="text" name="paystack_secret_key"
                                                                    id="paystack_secret_key" class="form-control"
                                                                    value="{{ !isset($admin_payment_setting['paystack_secret_key']) || is_null($admin_payment_setting['paystack_secret_key']) ? '' : $admin_payment_setting['paystack_secret_key'] }}"
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
                                                        <span class="me-2">{{__('Enable:')}}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_flutterwave_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_flutterwave_enabled" id="is_flutterwave_enabled"
                                                                {{ isset($admin_payment_setting['is_flutterwave_enabled']) && $admin_payment_setting['is_flutterwave_enabled'] == 'on' ? 'checked' : '' }}>

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
                                                                    value="{{ !isset($admin_payment_setting['flutterwave_public_key']) || is_null($admin_payment_setting['flutterwave_public_key']) ? '' : $admin_payment_setting['flutterwave_public_key'] }}"
                                                                    placeholder="Public Key">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paystack_secret_key"
                                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                                <input type="text" name="flutterwave_secret_key"
                                                                    id="flutterwave_secret_key" class="form-control"
                                                                    value="{{ !isset($admin_payment_setting['flutterwave_secret_key']) || is_null($admin_payment_setting['flutterwave_secret_key']) ? '' : $admin_payment_setting['flutterwave_secret_key'] }}"
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
                                                        <span class="me-2">{{__('Enable:')}}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_razorpay_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_razorpay_enabled" id="is_razorpay_enabled"
                                                                {{ isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>

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
                                                                    value="{{ !isset($admin_payment_setting['razorpay_public_key']) || is_null($admin_payment_setting['razorpay_public_key']) ? '' : $admin_payment_setting['razorpay_public_key'] }}"
                                                                    placeholder="Public Key">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paystack_secret_key"
                                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                                <input type="text" name="razorpay_secret_key"
                                                                    id="razorpay_secret_key" class="form-control"
                                                                    value="{{ !isset($admin_payment_setting['razorpay_secret_key']) || is_null($admin_payment_setting['razorpay_secret_key']) ? '' : $admin_payment_setting['razorpay_secret_key'] }}"
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
                                                        <span class="me-2">{{__('Enable:')}}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_mercado_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_mercado_enabled" id="is_mercado_enabled"
                                                                {{ isset($admin_payment_setting['is_mercado_enabled']) && $admin_payment_setting['is_mercado_enabled'] == 'on' ? 'checked' : '' }}>

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
                                                                                <input type="radio" name="mercado_mode"
                                                                                    value="sandbox"
                                                                                    class="form-check-input"
                                                                                    {{ (isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == '') || (isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                                {{ __('Sandbox') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mr-2">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                <input type="radio" name="mercado_mode"
                                                                                    value="live"
                                                                                    class="form-check-input"
                                                                                    {{ isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == 'live' ? 'checked="checked"' : '' }}>
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
                                                                    value="{{ isset($admin_payment_setting['mercado_access_token']) ? $admin_payment_setting['mercado_access_token'] : '' }}"
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
                                                        <span class="me-2">{{__('Enable:')}}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_paytm_enabled" value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_paytm_enabled" id="is_paytm_enabled"
                                                                {{ isset($admin_payment_setting['is_paytm_enabled']) && $admin_payment_setting['is_paytm_enabled'] == 'on' ? 'checked="checked"' : '' }}>
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
                                                                                    {{ !isset($admin_payment_setting['paytm_mode']) || $admin_payment_setting['paytm_mode'] == '' || $admin_payment_setting['paytm_mode'] == 'local' ? 'checked="checked"' : '' }}>

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
                                                                                    {{ isset($admin_payment_setting['paytm_mode']) && $admin_payment_setting['paytm_mode'] == 'production' ? 'checked="checked"' : '' }}>

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
                                                                    value="{{ !isset($admin_payment_setting['paytm_merchant_id']) || is_null($admin_payment_setting['paytm_merchant_id']) ? '' : $admin_payment_setting['paytm_merchant_id'] }}"
                                                                    placeholder="Merchant ID">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="paytm_secret_key"
                                                                    class="col-form-label">{{ __('Merchant Key') }}</label>
                                                                <input type="text" name="paytm_merchant_key"
                                                                    id="paytm_merchant_key" class="form-control"
                                                                    value="{{ !isset($admin_payment_setting['paytm_merchant_key']) || is_null($admin_payment_setting['paytm_merchant_key']) ? '' : $admin_payment_setting['paytm_merchant_key'] }}"
                                                                    placeholder="Merchant Key">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="paytm_industry_type"
                                                                    class="col-form-label">{{ __('Industry Type') }}</label>
                                                                <input type="text" name="paytm_industry_type"
                                                                    id="paytm_industry_type" class="form-control"
                                                                    value="{{ !isset($admin_payment_setting['paytm_industry_type']) || is_null($admin_payment_setting['paytm_industry_type']) ? '' : $admin_payment_setting['paytm_industry_type'] }}"
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
                                                        <span class="me-2">{{__('Enable:')}}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_mollie_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_mollie_enabled" id="is_mollie_enabled"
                                                                {{ isset($admin_payment_setting['is_mollie_enabled']) && $admin_payment_setting['is_mollie_enabled'] == 'on' ? 'checked' : '' }}>
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
                                                                    value="{{ !isset($admin_payment_setting['mollie_api_key']) || is_null($admin_payment_setting['mollie_api_key']) ? '' : $admin_payment_setting['mollie_api_key'] }}"
                                                                    placeholder="Mollie Api Key">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="mollie_profile_id"
                                                                    class="col-form-label">{{ __('Mollie Profile ID') }}</label>
                                                                <input type="text" name="mollie_profile_id"
                                                                    id="mollie_profile_id" class="form-control"
                                                                    value="{{ !isset($admin_payment_setting['mollie_profile_id']) || is_null($admin_payment_setting['mollie_profile_id']) ? '' : $admin_payment_setting['mollie_profile_id'] }}"
                                                                    placeholder="Mollie Profile Id">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="mollie_partner_id"
                                                                    class="col-form-label">{{ __('Mollie Partner ID') }}</label>
                                                                <input type="text" name="mollie_partner_id"
                                                                    id="mollie_partner_id" class="form-control"
                                                                    value="{{ !isset($admin_payment_setting['mollie_partner_id']) || is_null($admin_payment_setting['mollie_partner_id']) ? '' : $admin_payment_setting['mollie_partner_id'] }}"
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
                                                        <span class="me-2">{{__('Enable:')}}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_skrill_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_skrill_enabled" id="is_skrill_enabled"
                                                                {{ isset($admin_payment_setting['is_skrill_enabled']) && $admin_payment_setting['is_skrill_enabled'] == 'on' ? 'checked' : '' }}>
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
                                                                    value="{{ !isset($admin_payment_setting['skrill_email']) || is_null($admin_payment_setting['skrill_email']) ? '' : $admin_payment_setting['skrill_email'] }}"
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
                                                        <span class="me-2">{{__('Enable:')}}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_coingate_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_coingate_enabled" id="is_coingate_enabled"
                                                                {{ isset($admin_payment_setting['is_coingate_enabled']) && $admin_payment_setting['is_coingate_enabled'] == 'on' ? 'checked' : '' }}>

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

                                                                                <input type="radio" name="coingate_mode"
                                                                                    value="sandbox"
                                                                                    class="form-check-input"
                                                                                    {{ !isset($admin_payment_setting['coingate_mode']) || $admin_payment_setting['coingate_mode'] == '' || $admin_payment_setting['coingate_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>

                                                                                {{ __('Sandbox') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mr-2">
                                                                    <div class="border card p-3">
                                                                        <div class="form-check">
                                                                            <label class="form-check-labe text-dark">
                                                                                <input type="radio" name="coingate_mode"
                                                                                    value="live"
                                                                                    class="form-check-input"
                                                                                    {{ isset($admin_payment_setting['coingate_mode']) && $admin_payment_setting['coingate_mode'] == 'live' ? 'checked="checked"' : '' }}>
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
                                                                    value="{{ !isset($admin_payment_setting['coingate_auth_token']) || is_null($admin_payment_setting['coingate_auth_token']) ? '' : $admin_payment_setting['coingate_auth_token'] }}"
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
                                                        <span class="me-2">{{__('Enable:')}}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_paymentwall_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_paymentwall_enabled" id="is_paymentwall_enabled"
                                                                {{ isset($admin_payment_setting['is_paymentwall_enabled']) && $admin_payment_setting['is_paymentwall_enabled'] == 'on' ? 'checked' : '' }}>

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
                                                                    value="{{ !isset($admin_payment_setting['paymentwall_public_key']) || is_null($admin_payment_setting['paymentwall_public_key']) ? '' : $admin_payment_setting['paymentwall_public_key'] }}"
                                                                    placeholder="{{ __('Public Key') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paymentwall_private_key"
                                                                    class="col-form-label">{{ __('Private Key') }}</label>
                                                                <input type="text" name="paymentwall_private_key"
                                                                    id="paymentwall_private_key" class="form-control"
                                                                    value="{{ !isset($admin_payment_setting['paymentwall_private_key']) || is_null($admin_payment_setting['paymentwall_private_key']) ? '' : $admin_payment_setting['paymentwall_private_key'] }}"
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
                                                        <span class="me-2">{{__('Enable:')}}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_toyyibpay_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_toyyibpay_enabled" id="is_toyyibpay_enabled"
                                                                {{ isset($admin_payment_setting['is_toyyibpay_enabled']) && $admin_payment_setting['is_toyyibpay_enabled'] == 'on' ? 'checked' : '' }}>

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
                                                                    value="{{ !isset($admin_payment_setting['toyyibpay_secret_key']) || is_null($admin_payment_setting['toyyibpay_secret_key']) ? '' : $admin_payment_setting['toyyibpay_secret_key'] }}"
                                                                    placeholder="{{ __('Secret Key') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="category_code"
                                                                    class="col-form-label">{{ __('Category Code') }}</label>
                                                                <input type="text" name="category_code"
                                                                    id="category_code" class="form-control"
                                                                    value="{{ !isset($admin_payment_setting['category_code']) || is_null($admin_payment_setting['category_code']) ? '' : $admin_payment_setting['category_code'] }}"
                                                                    placeholder="{{ __('Private Key') }}">
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
                                                        <span class="me-2">{{__('Enable:')}}</span>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden" name="is_payfast_enabled"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="is_payfast_enabled" id="is_payfast_enabled"
                                                                {{ isset($admin_payment_setting['is_payfast_enabled']) && $admin_payment_setting['is_payfast_enabled'] == 'on' ? 'checked="checked"' : '' }}>
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
                                                                            class="form-check-labe text-dark {{ isset($admin_payment_setting['payfast_mode']) && $admin_payment_setting['payfast_mode'] == 'sandbox' ? 'active' : '' }}">
                                                                            <input type="radio" name="payfast_mode"
                                                                                value="sandbox" class="form-check-input"
                                                                                {{ isset($admin_payment_setting['payfast_mode']) && $admin_payment_setting['payfast_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>

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
                                                                                {{ isset($admin_payment_setting['payfast_mode']) && $admin_payment_setting['payfast_mode'] == 'live' ? 'checked="checked"' : '' }}>

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
                                                                    value="{{ !isset($admin_payment_setting['payfast_merchant_id']) || is_null($admin_payment_setting['payfast_merchant_id']) ? '' : $admin_payment_setting['payfast_merchant_id'] }}"
                                                                    placeholder="Merchant ID">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="paytm_secret_key"
                                                                    class="col-form-label">{{ __('Merchant Key') }}</label>
                                                                <input type="text" name="payfast_merchant_key"
                                                                    id="payfast_merchant_key" class="form-control"
                                                                    value="{{ !isset($admin_payment_setting['payfast_merchant_key']) || is_null($admin_payment_setting['payfast_merchant_key']) ? '' : $admin_payment_setting['payfast_merchant_key'] }}"
                                                                    placeholder="Merchant Key">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="payfast_signature"
                                                                    class="col-form-label">{{ __('Salt Passphrase') }}</label>
                                                                <input type="text" name="payfast_signature"
                                                                    id="payfast_signature" class="form-control"
                                                                    value="{{ !isset($admin_payment_setting['payfast_signature']) || is_null($admin_payment_setting['payfast_signature']) ? '' : $admin_payment_setting['payfast_signature'] }}"
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

                    <!--ReCaptcha Setting-->
                    <div id="useradd-4" class="card mb-3">
                        <form method="POST" action="{{ route('recaptcha.settings.store') }}"
                            accept-charset="UTF-8">
                            @csrf
                            <div class="card-header row d-flex justify-content-between">
                                <div class="col-auto">
                                    <h5>{{ __('ReCaptcha Settings') }}</h5>
                                    <small class="text-muted">
                                        <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/"
                                            target="_blank" class="text-blue">
                                            (How to Get Google reCaptcha Site and Secret key)
                                        </a>
                                    </small><br>
                                </div>
                                <div class="col-auto">
                                    <div class="form-switch form-switch-right"
                                        style="width: 86.1375px; height: 41.4px;">
                                        <input type="checkbox" class="form-check-input" name="recaptcha_module"
                                            data-toggle="switchbutton" id="recaptcha_module" value="yes"
                                            {{ env('RECAPTCHA_MODULE') == 'yes' ? 'checked="checked"' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="google_recaptcha_key"
                                                class="form-label">{{ __('Google Recaptcha Key') }}</label>
                                            <input class="form-control"
                                                placeholder="{{ __('Enter Google Recaptcha Key') }}"
                                                name="google_recaptcha_key" type="text"
                                                value="{{ env('NOCAPTCHA_SITEKEY') }}" id="google_recaptcha_key">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="google_recaptcha_secret"
                                                class="form-label">{{ __('Google Recaptcha Secret') }}</label>
                                            <input class="form-control"
                                                placeholder="{{ __('Enter Google Recaptcha Secret') }}"
                                                name="google_recaptcha_secret" type="text"
                                                value="{{ env('NOCAPTCHA_SECRET') }}" id="google_recaptcha_secret">
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer text-end">
                                    <div class="form-group">
                                        <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit"
                                            value="{{ __('Save Changes') }}">
                                    </div>
                                </div>

                            </div>
                            {{ Form::close() }}
                    </div>

                    <!--storage Setting-->
                    <div id="useradd-5" class="card mb-3">
                        {{ Form::open(['route' => 'storage.setting.store', 'enctype' => 'multipart/form-data']) }}
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-10">
                                    <h5 class="">{{ __('Storage Settings') }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="pe-2">
                                    <input type="radio" class="btn-check" name="storage_setting"
                                        id="local-outlined" autocomplete="off"
                                        {{ $setting['storage_setting'] == 'local' ? 'checked' : '' }} value="local"
                                        checked>
                                    <label class="btn btn-outline-primary"
                                        for="local-outlined">{{ __('Local') }}</label>
                                </div>
                                <div class="pe-2">
                                    <input type="radio" class="btn-check" name="storage_setting" id="s3-outlined"
                                        autocomplete="off" {{ $setting['storage_setting'] == 's3' ? 'checked' : '' }}
                                        value="s3">
                                    <label class="btn btn-outline-primary" for="s3-outlined">
                                        {{ __('AWS S3') }}</label>
                                </div>

                                <div class="pe-2">
                                    <input type="radio" class="btn-check" name="storage_setting"
                                        id="wasabi-outlined" autocomplete="off"
                                        {{ $setting['storage_setting'] == 'wasabi' ? 'checked' : '' }} value="wasabi">
                                    <label class="btn btn-outline-primary"
                                        for="wasabi-outlined">{{ __('Wasabi') }}</label>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div
                                    class="local-setting row {{ $setting['storage_setting'] == 'local' ? ' ' : 'd-none' }}">
                                    {{-- <h4 class="small-title">{{ __('Local Settings') }}</h4> --}}
                                    <div class="form-group col-8 switch-width">
                                        {{ Form::label('local_storage_validation', __('Only Upload Files'), ['class' => ' form-label']) }}
                                        <select name="local_storage_validation[]" class="select2"
                                            id="local_storage_validation" multiple>
                                            @foreach ($file_type as $f)
                                                <option @if (in_array($f, $local_storage_validations)) selected @endif>
                                                    {{ $f }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label"
                                                for="local_storage_max_upload_size">{{ __('Max upload size ( In KB)') }}</label>
                                            <input type="number" name="local_storage_max_upload_size"
                                                class="form-control"
                                                value="{{ !isset($setting['local_storage_max_upload_size']) || is_null($setting['local_storage_max_upload_size']) ? '' : $setting['local_storage_max_upload_size'] }}"
                                                placeholder="{{ __('Max upload size') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="s3-setting row {{ $setting['storage_setting'] == 's3' ? ' ' : 'd-none' }}">

                                    <div class=" row ">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="s3_key">{{ __('S3 Key') }}</label>
                                                <input type="text" name="s3_key" class="form-control"
                                                    value="{{ !isset($setting['s3_key']) || is_null($setting['s3_key']) ? '' : $setting['s3_key'] }}"
                                                    placeholder="{{ __('S3 Key') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="s3_secret">{{ __('S3 Secret') }}</label>
                                                <input type="text" name="s3_secret" class="form-control"
                                                    value="{{ !isset($setting['s3_secret']) || is_null($setting['s3_secret']) ? '' : $setting['s3_secret'] }}"
                                                    placeholder="{{ __('S3 Secret') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="s3_region">{{ __('S3 Region') }}</label>
                                                <input type="text" name="s3_region" class="form-control"
                                                    value="{{ !isset($setting['s3_region']) || is_null($setting['s3_region']) ? '' : $setting['s3_region'] }}"
                                                    placeholder="{{ __('S3 Region') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="s3_bucket">{{ __('S3 Bucket') }}</label>
                                                <input type="text" name="s3_bucket" class="form-control"
                                                    value="{{ !isset($setting['s3_bucket']) || is_null($setting['s3_bucket']) ? '' : $setting['s3_bucket'] }}"
                                                    placeholder="{{ __('S3 Bucket') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="s3_url">{{ __('S3 URL') }}</label>
                                                <input type="text" name="s3_url" class="form-control"
                                                    value="{{ !isset($setting['s3_url']) || is_null($setting['s3_url']) ? '' : $setting['s3_url'] }}"
                                                    placeholder="{{ __('S3 URL') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="s3_endpoint">{{ __('S3 Endpoint') }}</label>
                                                <input type="text" name="s3_endpoint" class="form-control"
                                                    value="{{ !isset($setting['s3_endpoint']) || is_null($setting['s3_endpoint']) ? '' : $setting['s3_endpoint'] }}"
                                                    placeholder="{{ __('S3 Bucket') }}">
                                            </div>
                                        </div>
                                        <div class="form-group col-8 switch-width">
                                            {{ Form::label('s3_storage_validation', __('Only Upload Files'), ['class' => ' form-label']) }}
                                            <select name="s3_storage_validation[]" class="select2"
                                                id="s3_storage_validation" multiple>
                                                @foreach ($file_type as $f)
                                                    <option @if (in_array($f, $s3_storage_validations)) selected @endif>
                                                        {{ $f }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="s3_max_upload_size">{{ __('Max upload size ( In KB)') }}</label>
                                                <input type="number" name="s3_max_upload_size" class="form-control"
                                                    value="{{ !isset($setting['s3_max_upload_size']) || is_null($setting['s3_max_upload_size']) ? '' : $setting['s3_max_upload_size'] }}"
                                                    placeholder="{{ __('Max upload size') }}">
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div
                                    class="wasabi-setting row {{ $setting['storage_setting'] == 'wasabi' ? ' ' : 'd-none' }}">
                                    <div class=" row ">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="s3_key">{{ __('Wasabi Key') }}</label>
                                                <input type="text" name="wasabi_key" class="form-control"
                                                    value="{{ !isset($setting['wasabi_key']) || is_null($setting['wasabi_key']) ? '' : $setting['wasabi_key'] }}"
                                                    placeholder="{{ __('Wasabi Key') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="s3_secret">{{ __('Wasabi Secret') }}</label>
                                                <input type="text" name="wasabi_secret" class="form-control"
                                                    value="{{ !isset($setting['wasabi_secret']) || is_null($setting['wasabi_secret']) ? '' : $setting['wasabi_secret'] }}"
                                                    placeholder="{{ __('Wasabi Secret') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="s3_region">{{ __('Wasabi Region') }}</label>
                                                <input type="text" name="wasabi_region" class="form-control"
                                                    value="{{ !isset($setting['wasabi_region']) || is_null($setting['wasabi_region']) ? '' : $setting['wasabi_region'] }}"
                                                    placeholder="{{ __('Wasabi Region') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="wasabi_bucket">{{ __('Wasabi Bucket') }}</label>
                                                <input type="text" name="wasabi_bucket" class="form-control"
                                                    value="{{ !isset($setting['wasabi_bucket']) || is_null($setting['wasabi_bucket']) ? '' : $setting['wasabi_bucket'] }}"
                                                    placeholder="{{ __('Wasabi Bucket') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="wasabi_url">{{ __('Wasabi URL') }}</label>
                                                <input type="text" name="wasabi_url" class="form-control"
                                                    value="{{ !isset($setting['wasabi_url']) || is_null($setting['wasabi_url']) ? '' : $setting['wasabi_url'] }}"
                                                    placeholder="{{ __('Wasabi URL') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="wasabi_root">{{ __('Wasabi Root') }}</label>
                                                <input type="text" name="wasabi_root" class="form-control"
                                                    value="{{ !isset($setting['wasabi_root']) || is_null($setting['wasabi_root']) ? '' : $setting['wasabi_root'] }}"
                                                    placeholder="{{ __('Wasabi Bucket') }}">
                                            </div>
                                        </div>
                                        <div class="form-group col-8 switch-width">
                                            {{ Form::label('wasabi_storage_validation', __('Only Upload Files'), ['class' => 'form-label']) }}

                                            <select name="wasabi_storage_validation[]" class="select2"
                                                id="wasabi_storage_validation" multiple>
                                                @foreach ($file_type as $f)
                                                    <option @if (in_array($f, $wasabi_storage_validations)) selected @endif>
                                                        {{ $f }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="wasabi_root">{{ __('Max upload size ( In KB)') }}</label>
                                                <input type="number" name="wasabi_max_upload_size"
                                                    class="form-control"
                                                    value="{{ !isset($setting['wasabi_max_upload_size']) || is_null($setting['wasabi_max_upload_size']) ? '' : $setting['wasabi_max_upload_size'] }}"
                                                    placeholder="{{ __('Max upload size') }}">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit"
                                    value="{{ __('Save Changes') }}">
                            </div>
                            {{ Form::close() }}
                        </div>



                    </div>

                    {{-- SEO Setting --}}
                    <div class="card mb-3" id="useradd-6">
                        {{ Form::open(['url' => route('seo.settings'), 'enctype' => 'multipart/form-data']) }}
                        <div class="card-header">
                            <h5>{{ __('SEO Settings') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('meta_keywords', __('Meta Keywords'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('meta_keywords', !empty($settings['meta_keywords']) ? $settings['meta_keywords'] : '', ['class' => 'form-control ', 'placeholder' => 'Meta Keywords']) }}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('meta_description', __('Meta Description'), ['class' => 'form-label']) }}
                                        {{ Form::textarea('meta_description', !empty($settings['meta_description']) ? $settings['meta_description'] : '', ['class' => 'form-control ', 'row' => 3, 'placeholder' => 'Enter Meta Description']) }}
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-group ">
                                        {{ Form::label('Meta Image', __('Meta Image'), ['class' => 'col-form-label ']) }}
                                        <div class="card-body pt-0">
                                            <div class="setting-card">
                                                <div class="logo-content">
                                                    <a href="{{ $meta_image . '/' . (isset($settings['meta_image']) && !empty($settings['meta_image']) ? $settings['meta_image'] : 'meta_image.png') }}"
                                                        target="_blank"> <img id="meta"
                                                            src="{{ $meta_image . '/' . (isset($settings['meta_image']) && !empty($settings['meta_image']) ? $settings['meta_image'] : 'meta_image.png') }}"
                                                            width="400px" class="img_setting"> </a>
                                                </div>
                                                <div class="choose-files mt-4">
                                                    <label for="meta_image">
                                                        <div class=" bg-primary logo"> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input style="margin-top: -40px;" type="file"
                                                            class="form-control file" name="meta_image"
                                                            id="meta_image" data-filename="meta_image"
                                                            onchange="document.getElementById('meta').src = window.URL.createObjectURL(this.files[0])">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        {{ Form::label('Meta Image', __('Meta Image'), ['class' => 'col-form-label ']) }}
                                    </div>
                                    <div class="setting-card">
                                        <div class="logo-content">
                                            <a href="{{ $meta_image . '/' . (isset($settings['meta_image']) && !empty($settings['meta_image']) ? $settings['meta_image'] : 'meta_image.png') }}"
                                                target="_blank"> <img id="meta"
                                                    src="{{ $meta_image . '/' . (isset($settings['meta_image']) && !empty($settings['meta_image']) ? $settings['meta_image'] : 'meta_image.png') }}"
                                                    width="400px" class="img_setting"> </a>
                                        </div>
                                        <div class="choose-files mt-4">
                                            <label for="meta_image">
                                                <div class=" bg-primary logo"> <i
                                                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                </div>
                                                <input style="margin-top: -40px;" type="file"
                                                    class="form-control file" name="meta_image"
                                                    id="meta_image" data-filename="meta_image"
                                                    onchange="document.getElementById('meta').src = window.URL.createObjectURL(this.files[0])">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <button class="btn-submit btn btn-primary" type="submit">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                        {{ Form::close() }}
                    </div>


                    {{-- Cookie Consent --}}
                    <div class="card" id="useradd-7">
                        {{ Form::model($settings, ['route' => 'cookie.setting', 'method' => 'post']) }}
                        <div
                            class="card-header flex-column flex-lg-row  d-flex align-items-lg-center gap-2 justify-content-between">
                            <h5>{{ __('Cookie Settings') }}</h5>
                            <div class="d-flex align-items-center">
                                {{ Form::label('enable_cookie', __('Enable cookie'), ['class' => 'col-form-label p-0 fw-bold me-3']) }}
                                <div class="custom-control custom-switch" onclick="enablecookie()">
                                    <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary"
                                        name="enable_cookie" class="form-check-input input-primary "
                                        id="enable_cookie" {{ $settings['enable_cookie'] == 'on' ? ' checked ' : '' }}>
                                    <label class="custom-control-label mb-1" for="enable_cookie"></label>
                                </div>
                            </div>
                        </div>
                        <div
                            class="card-body cookieDiv {{ $settings['enable_cookie'] == 'off' ? 'disabledCookie ' : '' }}">
                            <div class="row ">
                                <div class="col-md-6">
                                    <div class="form-check form-switch custom-switch-v1" id="cookie_log">
                                        <input type="checkbox" name="cookie_logging"
                                            class="form-check-input input-primary cookie_setting" id="cookie_logging"
                                            onclick="enableButton()"
                                            {{ $settings['cookie_logging'] == 'on' ? ' checked ' : '' }}>
                                        <label class="form-check-label"
                                            for="cookie_logging">{{ __('Enable logging') }}</label>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('cookie_title', __('Cookie Title'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('cookie_title', null, ['class' => 'form-control cookie_setting']) }}
                                    </div>
                                    <div class="form-group ">
                                        {{ Form::label('cookie_description', __('Cookie Description'), ['class' => ' form-label']) }}
                                        {!! Form::textarea('cookie_description', null, ['class' => 'form-control cookie_setting', 'rows' => '3']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch custom-switch-v1 ">
                                        <input type="checkbox" name="necessary_cookies"
                                            class="form-check-input input-primary" id="necessary_cookies" checked
                                            onclick="return false">
                                        <label class="form-check-label"
                                            for="necessary_cookies">{{ __('Strictly necessary cookies') }}</label>
                                    </div>
                                    <div class="form-group ">
                                        {{ Form::label('strictly_cookie_title', __(' Strictly Cookie Title'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('strictly_cookie_title', null, ['class' => 'form-control cookie_setting']) }}
                                    </div>
                                    <div class="form-group ">
                                        {{ Form::label('strictly_cookie_description', __('Strictly Cookie Description'), ['class' => ' form-label']) }}
                                        {!! Form::textarea('strictly_cookie_description', null, [
                                            'class' => 'form-control cookie_setting ',
                                            'rows' => '3',
                                        ]) !!}
                                    </div>
                                </div>
                                    <div class="col-12">
                                        <h5>{{ __('More Information') }}</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('more_information_description', __('Contact Us Description'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('more_information_description', null, ['class' => 'form-control cookie_setting']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('contactus_url', __('Contact Us URL'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('contactus_url', null, ['class' => 'form-control cookie_setting']) }}
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div
                            class="card-footer d-flex align-items-center gap-2 flex-sm-column flex-lg-row justify-content-between">
                            <div>
                                @if (isset($settings['cookie_logging']) && $settings['cookie_logging'] == 'on')
                                    <label for="file"
                                        class="form-label">{{ __('Download cookie accepted data') }}</label>
                                    <a href="{{ asset(Storage::url('uploads/sample')) . '/data.csv' }}"
                                        class="btn btn-primary mr-2 ">
                                        <i class="ti ti-download"></i>
                                    </a>
                                @endif
                            </div>
                            <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-primary">
                        </div>
                        {{ Form::close() }}
                    </div>

                    <!--cache Setting-->

                    <div id="useradd-8" class="card">
                        <div class="card-header">
                            <h5>{{ __('Cache Settings') }}</h5>
                            <small class="text-secondary font-weight-bold">
                                {{ __('This is a page meant for more advanced users, simply ignore it if you don`t understand what cache is.')}}
                            </small>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="input-group search-form">
                                    <input type="text" value="{{ Utility::GetCacheSize() }}"
                                        class="form-control">
                                    <span class="input-group-text bg-transparent">MB</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="{{ url('config-cache') }}"
                                class="btn btn-print-invoice btn-primary m-r-10">{{ __('Clear Cache') }}</a>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
        </div>
    @endsection

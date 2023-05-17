@extends('layouts.auth')
@php
    use App\Models\Utility;
    // $lang = \App\Models\Utility::getValByName('default_language');
    $logo = asset(Storage::url('uploads/logo/'));
    $company_logo = Utility::getValByName('company_logo');
    $settings = Utility::settings();
    // dd($lang);
    if(empty($lang))
    {
        $lang = Utility::getValByName('default_language');
    }

@endphp
@push('custom-scripts')
    @if(env('RECAPTCHA_MODULE') == 'yes')
        {!! NoCaptcha::renderJs() !!}
    @endif
@endpush
@section('page-title')
    {{__('Login')}}
@endsection

@section('auth-lang')
<select class="btn btn-primary my-1 me-2" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" id="language">
    @foreach(Utility::languages() as $language)
        <option class="" @if($lang == $language) selected @endif value="{{ route('verification.notice',$language) }}">{{Str::upper($language)}}</option>
    @endforeach
</select>   
@endsection

@section('content')
<div class="col-xl-12">
    <div class="">

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600 text-primary">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif


        <div class="mb-4 text-sm text-gray-600">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        

        <div class="mt-4 flex items-center justify-between">
            <div class="row">
                <div class="col-auto">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
        
                        <button type="submit" class="btn btn-primary btn-sm">     {{ __('Resend Verification Email') }}
                        </button>
                       
                    </form>
                </div>
                <div class="col-auto">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                       <button type="submit" class="btn btn-danger btn-sm">    {{ __('Logout') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
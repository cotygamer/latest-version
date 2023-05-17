<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\User;
use App\Models\Utility;
use App\Models\Vender;
use App\Models\LoginDetail;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $lang = 'en';
        return view('auth.login', compact('lang'));
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {

        //ReCpatcha
        if (env('RECAPTCHA_MODULE') == 'yes') {
            $validation['g-recaptcha-response'] = 'required|captcha';
        } else {
            $validation = [];
        }
        $this->validate($request, $validation);



        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        if ($user->delete_status == 0) {
            auth()->logout();
        }

        if ($user->is_active == 0) {
            auth()->logout();
        }

        $ip = $_SERVER['REMOTE_ADDR']; // your ip address here

        // $ip = '49.36.83.154'; // This is static ip address

        $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));

        if (isset($query['status']) &&  $query['status'] != 'fail') {
            $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
            if ($whichbrowser->device->type == 'bot') {
                return;
            }
            $referrer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : null;

            /* Detect extra details about the user */
            $query['browser_name'] = $whichbrowser->browser->name ?? null;
            $query['os_name'] = $whichbrowser->os->name ?? null;
            $query['browser_language'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
            $query['device_type'] = get_device_type($_SERVER['HTTP_USER_AGENT']);
            $query['referrer_host'] = !empty($referrer['host']);
            $query['referrer_path'] = !empty($referrer['path']);

            isset($query['timezone']) ? date_default_timezone_set($query['timezone']) : '';

            $json = json_encode($query);
            if ($user->type != 'company') {
                $login_detail = new LoginDetail();
                $login_detail->user_id = Auth::user()->id;
                $login_detail->ip = $ip;
                $login_detail->date = date('Y-m-d H:i:s');
                $login_detail->Details = $json;
                $login_detail->type = 'user';
                $login_detail->created_by = \Auth::user()->creatorId();
                $login_detail->save();
            }
        }

        if ($user->type == 'company') {
            $free_plan = Plan::where('price', '=', '0.0')->first();
            if ($user->plan != $free_plan->id) {
                if (date('Y-m-d') > $user->plan_expire_date) {
                    $user->plan             = $free_plan->id;
                    $user->plan_expire_date = null;
                    $user->save();

                    $users     = User::where('created_by', '=', \Auth::user()->creatorId())->get();
                    $customers = Customer::where('created_by', '=', \Auth::user()->creatorId())->get();
                    $venders   = Vender::where('created_by', '=', \Auth::user()->creatorId())->get();

                    if ($free_plan->max_users == -1) {
                        foreach ($users as $user) {
                            $user->is_active = 1;
                            $user->save();
                        }
                    } else {
                        $userCount = 0;
                        foreach ($users as $user) {
                            $userCount++;
                            if ($userCount <= $free_plan->max_users) {
                                $user->is_active = 1;
                                $user->save();
                            } else {
                                $user->is_active = 0;
                                $user->save();
                            }
                        }
                    }


                    if ($free_plan->max_customers == -1) {
                        foreach ($customers as $customer) {
                            $customer->is_active = 1;
                            $customer->save();
                        }
                    } else {
                        $customerCount = 0;
                        foreach ($customers as $customer) {
                            $customerCount++;
                            if ($customerCount <= $free_plan->max_customers) {
                                $customer->is_active = 1;
                                $customer->save();
                            } else {
                                $customer->is_active = 0;
                                $customer->save();
                            }
                        }
                    }

                    if ($free_plan->max_venders == -1) {
                        foreach ($venders as $vender) {
                            $vender->is_active = 1;
                            $vender->save();
                        }
                    } else {
                        $venderCount = 0;
                        foreach ($venders as $vender) {
                            $venderCount++;
                            if ($venderCount <= $free_plan->max_venders) {
                                $vender->is_active = 1;
                                $vender->save();
                            } else {
                                $vender->is_active = 0;
                                $vender->save();
                            }
                        }
                    }

                    return redirect()->route('dashboard')->with('error', 'Your plan expired limit is over, please upgrade your plan');
                }
            }
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }


    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function username()
    {
        return 'email';
    }


    public function showCustomerLoginForm($lang = '')
    {
        if ($lang == '') {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.customer_login', compact('lang'));
    }


    public function customerLogin(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]
        );



        if (\Auth::guard('customer')->attempt(
            [
                'email' => $request->email,
                'password' => $request->password,
            ],
            $request->get('remember')
        )) {
            if (\Auth::guard('customer')->user()->is_active == 0) {
                \Auth::guard('customer')->logout();
            }


            $ip = $_SERVER['REMOTE_ADDR']; // your ip address here

            // $ip = '49.36.83.154'; // This is static ip address

            $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));

            if (isset($query['status']) &&  $query['status'] != 'fail') {

                $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
                if ($whichbrowser->device->type == 'bot') {
                    return;
                }
                $referrer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : null;

                /* Detect extra details about the user */
                $query['browser_name'] = $whichbrowser->browser->name ?? null;
                $query['os_name'] = $whichbrowser->os->name ?? null;
                $query['browser_language'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
                $query['device_type'] = get_device_type($_SERVER['HTTP_USER_AGENT']);
                $query['referrer_host'] = !empty($referrer['host']);
                $query['referrer_path'] = !empty($referrer['path']);

                isset($query['timezone']) ? date_default_timezone_set($query['timezone']) : '';

                $json = json_encode($query);
                $Customer = Customer::where('email', $request->email)->first();
                $login_detail = new LoginDetail();
                $login_detail->user_id = $Customer->id;
                $login_detail->ip = $ip;
                $login_detail->date = date('Y-m-d H:i:s');
                $login_detail->Details = $json;
                $login_detail->type = 'customer';
                $login_detail->created_by = $Customer->created_by;
                $login_detail->save();
            }
            return redirect()->route('customer.dashboard');
        }

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('These credentials do not match our records.')],
        ]);
    }

    public function showVenderLoginForm($lang = '')
    {
        if ($lang == '') {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.vender_login', compact('lang'));
    }

    public function venderLogin(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]
        );
        if (\Auth::guard('vender')->attempt(
            [
                'email' => $request->email,
                'password' => $request->password,
            ],
            $request->get('remember')
        )) {
            if (\Auth::guard('vender')->user()->is_active == 0) {
                \Auth::guard('vender')->logout();
            }


            $ip = $_SERVER['REMOTE_ADDR']; // your ip address here

            // $ip = '49.36.83.154'; // This is static ip address

            $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
            if (isset($query['status']) &&  $query['status'] != 'fail') {
                $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
                if ($whichbrowser->device->type == 'bot') {
                    return;
                }
                $referrer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : null;

                /* Detect extra details about the user */
                $query['browser_name'] = $whichbrowser->browser->name ?? null;
                $query['os_name'] = $whichbrowser->os->name ?? null;
                $query['browser_language'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
                $query['device_type'] = get_device_type($_SERVER['HTTP_USER_AGENT']);
                $query['referrer_host'] = !empty($referrer['host']);
                $query['referrer_path'] = !empty($referrer['path']);

                isset($query['timezone']) ? date_default_timezone_set($query['timezone']) : '';

                $json = json_encode($query);
                $vender = Vender::where('email', $request->email)->first();
                $login_detail = new LoginDetail();
                $login_detail->user_id = $vender->id;
                $login_detail->ip = $ip;
                $login_detail->date = date('Y-m-d H:i:s');
                $login_detail->Details = $json;
                $login_detail->type = 'vender';
                $login_detail->created_by = $vender->created_by;
                $login_detail->save();
            }
            return redirect()->route('vender.dashboard');
        }
        

        return $this->sendFailedLoginResponse($request);
    }

    public function showLoginForm($lang = '')
    {

        if ($lang == '') {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.login', compact('lang'));
    }

    public function showLinkRequestForm($lang = '')
    {

        if ($lang == '') {
            $lang = Utility::getValByName('default_language');
        }


        \App::setLocale($lang);

        return view('auth.forgot-password', compact('lang'));
    }

    public function showCustomerLoginLang($lang = '')
    {
        if ($lang == '') {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.customer_login', compact('lang'));
    }

    public function showVenderLoginLang($lang = '')
    {
        if ($lang == '') {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.vender_login', compact('lang'));
    }

    //    ---------------------------------Customer ----------------q------------------_
    public function showCustomerLinkRequestForm($lang = '')
    {

        if ($lang == '') {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.customerEmail', compact('lang'));
    }

    public function postCustomerEmail(Request $request)
    {


        $request->validate(
            [
                'email' => 'required|email|exists:customers',
            ]
        );

        $token = \Str::random(60);

        DB::table('password_resets')->insert(
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );

        Mail::send(
            'auth.customerVerify',
            ['token' => $token],
            function ($message) use ($request) {
                $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
                $message->to($request->email);
                $message->subject('Reset Password Notification');
            }
        );

        return back()->with('status', 'We have e-mailed your password reset link!');
    }

    public function showResetForm(Request $request, $token = null)
    {

        $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
        $lang             = !empty($default_language) ? $default_language->value : 'en';

        \App::setLocale($lang);

        return view('auth.passwords.reset')->with(
            [
                'token' => $token,
                'email' => $request->email,
                'lang' => $lang,
            ]
        );
    }

    public function getCustomerPassword($token)
    {

        return view('auth.customerReset', ['token' => $token]);
    }

    public function updateCustomerPassword(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:customers',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required',

            ]
        );

        $updatePassword = DB::table('password_resets')->where(
            [
                'email' => $request->email,
                'token' => $request->token,
            ]
        )->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = Customer::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect('/login')->with('message', 'Your password has been changed.');
    }

    //    ----------------------------Vendor----------------------------------------------------
    public function showVendorLinkRequestForm($lang = '')
    {
        if ($lang == '') {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.vendorEmail', compact('lang'));
    }

    public function postVendorEmail(Request $request)
    {

        $request->validate(
            [
                'email' => 'required|email|exists:venders',
            ]
        );

        $token = \Str::random(60);

        DB::table('password_resets')->insert(
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );

        Mail::send(
            'auth.vendorVerify',
            ['token' => $token],
            function ($message) use ($request) {
                $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
                $message->to($request->email);
                $message->subject('Reset Password Notification');
            }
        );

        return back()->with('status', 'We have e-mailed your password reset link!');
    }

    public function getVendorPassword($token)
    {

        return view('auth.vendorReset', ['token' => $token]);
    }

    public function updateVendorPassword(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:venders',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required',

            ]
        );

        $updatePassword = DB::table('password_resets')->where(
            [
                'email' => $request->email,
                'token' => $request->token,
            ]
        )->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = Vender::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect('/login')->with('message', 'Your password has been changed.');
    }
}

function get_device_type($user_agent)
{
    $mobile_regex = '/(?:phone|windows\s+phone|ipod|blackberry|(?:android|bb\d+|meego|silk|googlebot) .+? mobile|palm|windows\s+ce|opera mini|avantgo|mobilesafari|docomo)/i';
    $tablet_regex = '/(?:ipad|playbook|(?:android|bb\d+|meego|silk)(?! .+? mobile))/i';
    if (preg_match_all($mobile_regex, $user_agent)) {
        return 'mobile';
    } else {
        if (preg_match_all($tablet_regex, $user_agent)) {
            return 'tablet';
        } else {
            return 'desktop';
        }
    }
}

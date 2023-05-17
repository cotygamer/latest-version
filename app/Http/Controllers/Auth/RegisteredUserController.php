<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Utility;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

       
       
        if(Utility::getValByName('email_verification')=='on')
        {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type'     => 'company',
            ]);


            Auth::login($user);
            
            try{
                event(new Registered($user));
                $role_r = Role::findByName('company');
                Utility::chartOfAccountData($user);
                $user->assignRole($role_r); 
                $user->userDefaultDataRegister($user->id);
            }catch(\Exception $e){
                $user->delete();

                return redirect('/register/lang?')->with('status', __('Email SMTP settings does not configure so please contact to your site admin.'));
            }    
            return view('auth.verify-email');
        }
        else
        {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type' => 'company',
                'lang' => \App\Models\Utility::getValByName('default_language'),
                'created_by' => 1,
            ]);
            $user->email_verified_at = date("H:i:s");
            $user->save();
            
            Auth::login($user);

            $role_r = Role::findByName('company');

            $user->assignRole($role_r);
            // event(new Registered($user));


            return redirect(RouteServiceProvider::HOME);
        }

    }
    public function showRegistrationForm($lang = '')
    {
        if($lang == '')
        {
            $lang = Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.register', compact('lang'));
    }
}

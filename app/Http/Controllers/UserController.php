<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\App;
use App\Exports\ExportUser;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Auth\Events\Registered;
use App\Models\Services;
use App\Models\Notification;
use App\Models\Tenant;
use Twilio\Rest\Client;


class UserController extends Controller
{
    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback(Request $request)
    {
        $userData = Socialite::driver('google')->user();
        $user = User::where('email', $userData->getEmail())->where('type_login', 'google')->first();
        if ($user) {
            Auth::login($user, true);
            return redirect()->route('home');
        } else {
            $user = new User();
            $user->name = $userData->getName();
            $user->email = $userData->getEmail();
            $user->password = Hash::make($userData->getId());
            $user->type_login = 'google';
            $user->role = 'landlords';
            $user->save();
            Auth::login($user, true);

            Services::create([
                'service_name' => 'Electricity',
                'price' => 0,
                'description' => 'Default and required electricity service',
                'user_id' => $user->id,
                'type_id' => 1,
            ]);
            Services::create([
                'service_name' => 'Water',
                'price' => 0,
                'description' => 'Default and required water service,',
                'user_id' => $user->id,
                'type_id' => 2,
            ]);

            // return redirect()->route('home');
            if (Auth::user()->role == 'landlords') {
                return redirect()->route('home')->with('success', 'Login successful!');
            } else {
                return back()->with('errors', 'You cannot access the system')->withInput($request->all());
            }
        }
    }
    //function return view login
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('home')->with('success', 'Login successful!');
        } else {
            return view('user.login')->with('title', 'Login');
        }
    }

    //function handle login
    public function login_action(Request $request)
    {
        // validate form
        $request->validate([
            'credential' => 'required',
            'password' => 'required|min:6'
        ]);

        $remember = $request->has('remember_me') || Cookie::has('remember_me');

        $credentials = [
            'password' => $request->password,
        ];

        if (filter_var($request->credential, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $request->credential;
        } else {
            $credentials['username'] = $request->credential;
        }

        if (Auth::attempt($credentials, $remember)) {
            // $request->session()->regenerate();
            $user = Auth::user();
            Auth::login($user, $remember);
            return redirect()->route('home')->with('success', 'Login successful!');
        }
        return back()->with('errorLogin', 'Incorrect username or password')->withInput($request->all());
    }

    //function return view register
    public function register()
    {
        return view('user.register')->with('title', 'Registration');
    }

    //function handle register
    public function register_action(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:tb_user',
            'password' => 'required|min:6',
            'email' => 'email|required|unique:tb_user',
            'confirmPassword' => 'required|same:password',
        ]);

        if (event(new Registered($user = $this->create($request->all())))) {
            Services::create([
                'service_name' => 'Electricity',
                'price' => 0,
                'description' => 'Default and required electricity service',
                'user_id' => $user->id,
                'type_id' => 1,
            ]);
            Services::create([
                'service_name' => 'Water',
                'price' => 0,
                'description' => 'Default and required water service',
                'user_id' => $user->id,
                'type_id' => 2,
            ]);
        }

        Auth::login($user, true);

        return redirect()->route('home')->with('success', 'Registration successful!');
    }

    public function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'email' => $data['email'],
        ]);
    }


    // profile page
    public function profile()
    {
        return view('user.profile')->with('user', auth()->user())->with('title', 'Profile');
    }

    // handle update profile
    public function updateProfile(Request $request)
    {
        switch ($request->btnSubmit) {
            case 'updateInformation': // if user click button update information
                // validate form
                $request->validate([
                    'phone' => 'required|numeric|digits_between:10,11',
                    'id_card' => 'required|numeric|digits_between:9,12',
                    'name' => 'required',
                    'dob' => 'required',
                    'gender' => 'required',
                ]);

                // check phone unique
                $phone = User::where('phone', $request->phone)->where('id', '!=', auth()->user()->id)->first();

                // check id card unique
                $idCard = User::where('id_card', $request->id_card)->where('id', '!=', auth()->user()->id)->first();

                if ($phone) {
                    return redirect()->back()->with('errorProfile', 'Phone already exists')->withInput($request->all());
                } else if ($idCard) {
                    return redirect()->back()->with('errorProfile', 'ID card already exists')->withInput($request->all());
                } else {
                    $user = User::find(auth()->user()->id);
                    $user->name = $request->name;
                    $user->phone = $request->phone;
                    $user->dob = $request->dob;
                    $user->id_card = $request->id_card;
                    $user->gender = $request->gender;

                    $generatedAvatarName = null;
                    if ($request->avatar != "") {
                        $request->validate([
                            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
                        ]);

                        $generatedAvatarName = 'avatar-' . time() . '.' . $request->avatar->extension();
                        $request->avatar->move(public_path('avatar'), $generatedAvatarName);

                        $user->avatar = $generatedAvatarName;
                    }

                    $user->save();
                }
                return redirect()->route('profile')->with('successProfile', 'Profile updated successfully');
                break;

            case 'changePassword': // if user click button change password
                $request->validate([
                    'currentPassword' => 'required',
                    'newPassword' => 'required',
                    'confirmNewPassword' => 'required|same:newPassword',
                ]);

                $currentPassword = Hash::check($request->currentPassword, auth()->user()->password);
                if ($currentPassword) {
                    User::findOrFail(Auth::user()->id)->update([
                        'password' => Hash::make($request->newPassword)
                    ]);
                    return redirect()->route('profile')->with('success', 'Password changed successfully');
                } else {
                    return redirect()->back()->with('error', 'Current password is incorrect')->withInput($request->all());
                }
                break;
        }
    }

    //function handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logout successful!');
    }

    public function clearNotification()
    {
        $notification = Notification::where('user_id', auth()->user()->id)->get();
        foreach ($notification as $item) {
            $item->delete();
        }
        return redirect()->back();
    }

    public function handleNotify($id)
    {
        $tenant = Tenant::find($id);
        $tenant->password = Hash::make('12345678');
        $tenant->save();

        // Because Twilio trial account can only send message to verified number
        // So we only send message to my number
        // Remove this if-else code when the account is upgraded
        if ($tenant->phone_number == '+84398371050') {
            $sid = env('TWILIO_SID');
            $token = env('TWILIO_TOKEN');
            $numberFrom = env('TWILIO_FROM');

            $client = new Client($sid, $token);

            $client->messages->create(
                $tenant->phone_number,
                [
                    'from' => $numberFrom,
                    'body' => chr(10) . 'Hello: ' . $tenant->fullname . chr(10) . chr(10) . // chr(10) is a new line
                        'Your password has been reset to default password' . chr(10) . chr(10) .
                        'Password: 12345678' . chr(10) . chr(10) .
                        'Please access to the system via: ' . url('/') . '/tenant/login'
                ]
            );
        }

        $notification = Notification::where('user_id', auth()->user()->id)->where('url', $id)->first();
        $notification->delete();

        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(User $user) {
        $this->user = $user;
    }

    public function index() {
        return view('pages.auth', ['headerColor' => true]);
    }

    public function postLogin(Request $request) {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            
            $user = Auth::user();
            $resArr = [];
            $resArr['token'] = $user->createToken('e-commerce')->accessToken;
            $resArr['name']  = $user->name;

            Log::info(['resArr' => $resArr]);

            if (session()->has('user_token')) {
                session()->put('user_token', $resArr['token']);
            } else {
                session([
                    'user_token' => $resArr['token']
                ]);
            }
            
            return redirect()->route('index');
        }

        return redirect()->back()->withErrors('Email / Password does not exist');
    }

    public function postRegister(Request $request) {
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);

        $user = $this->user->create($data);

        if ($user) {
            Auth::login($user);

            return redirect()->route('index');
        }

        return redirect()->back()->withErrors('Failed to register');
    }

    public function postLogout(Request $request) {
        Auth::logout();
        return redirect()->route('index');
    }
}

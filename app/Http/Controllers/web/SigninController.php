<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use App\Models\Role;

class SigninController extends Controller
{
    //
    public function index()
    {
        if (Auth::check()) {
            return redirect('/');
        } else {
            return view('signin');
        }
    }

    public function username()
    {
        $login = request()->input('username');
        if (is_numeric($login)) {
            $field = 'phone';
        } elseif (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } else {
            $field = 'username';
        }
        request()->merge([$field => $login]);
        return $field;
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect('/');
        } else {
            return view('signin');
        }
    }

    public function actionlogin(Request $request)
    {
        $this->validate($request, [
            'username'    => 'required',
            'password' => 'required',
        ]);

        $login_type = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $request->merge([
            $login_type => $request->input('username')
        ]);

        if (!Auth::attempt($request->only($login_type, 'password'))) {
            // return redirect()->intended($this->redirectPath());
            return response()->json([
                'message' => 'Unauthorized',
                'success' => false
            ], 401);
        }

        $field = $this->username();
        // var_dump($field); exit;

        $user = User::where($field, $request->username)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        $userRoles = $user->roles->pluck('id')->first();

        $authsession =  $user->toArray();
         

        $request->session()->put('auth', $authsession);

        return response()->json([
            'success' => true,
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function actionlogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

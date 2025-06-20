<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function storeUser(RegisterRequest $request){
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);
        Auth::login($user);
        return redirect('/register');
    }

    public function loginUser(LoginRequest $request){
        $credentials=$request->only('email', 'password');
        if(Auth::attempt($credentials)){
            return redirect('/login');
        }
    }

    public function logout(){
        return view('auth.login');
    }

    public function loginAdmin(AdminLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            // 管理者認証成功
            return redirect()->intended('/admin/dashboard');
        }

        // 失敗時はリダイレクトやエラー表示
        return back()->withErrors([
            'email' => '管理者の認証に失敗しました。',
        ])->withInput($request->only('email'));
    }

}
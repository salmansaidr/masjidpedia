<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Mail\MasjidPediaEmail;
use Illuminate\Support\Facades\Mail;
use Throwable;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create(array_merge($validatedData, ['role' => 'toko']));

        Mail::to($validatedData['email'])->send(new MasjidPediaEmail($validatedData['email']));

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['message' => 'silahkan cek email anda untuk melakukan verifikasi', 'user' => $user, 'access_token' => $accessToken]);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt(array_merge($loginData, ['role' => 'toko']))) {
            return response(['message' => 'Email atau Password Salah'], 401);
        }

        if(!auth()->user()->email_verified_at) {
            return response(['message' => 'Email belum terverifikasi'], 422);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);

    }

    public function resetPassword(Request $request) {
        $input = $request->all();

        \Validator::make($input, [
            'email' => 'email|required',
            'password' => 'required|confirmed'
        ],[
            'email.email' => 'Email tidak valid',
            'email.required' => 'Email wajib diinput',
            'password.required' => 'Password baru wajib diinput',
            'password.confirmed' => 'Password harus sama dengan konfirmasi password'
        ])->validate();

        if(User::where([['email', $input['email']], ['role', 'toko']])->count() == 0) {
            return response([
                'message' => 'Email anda belum terdaftar atau Akun anda bukan akun toko'
            ], 422);
        }

        $user = User::where('email', $input['email'])->first();
        $user->password = \Hash::make($input['password']);
        $user->save();

        return response(['message' => 'Password anda berhasil diubah'], 200);
    }

    public function verifyEmail(Request $request) {
        try {
            $email = \Crypt::decrypt($request->get('verif'));
            $user = User::where('email', $email)->first();
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->save();

            return "<h1>Selamat, email anda berhasil diverifikasi.</h1>";

        } catch(Throwable $e) {
            return redirect('/');
        }
    }
}
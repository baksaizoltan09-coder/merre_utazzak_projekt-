<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function regisztracio(Request $request)
    {
        $adat = $request->validate([
            'nev' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'jelszo' => 'required|string|min:8',
        ], [
            'nev.required' => 'Add meg a neved.',
            'email.required' => 'Add meg az e-mail címed.',
            'email.email' => 'Érvénytelen e-mail cím.',
            'email.unique' => 'Ezzel az e-mail címmel már regisztráltak.',
            'jelszo.required' => 'Adj meg egy jelszót.',
            'jelszo.min' => 'A jelszó legalább 8 karakter legyen.',
        ]);

        $user = User::create([
            'name' => $adat['nev'],
            'email' => $adat['email'],
            'password' => Hash::make($adat['jelszo']),
        ]);

        return response()->json([
            'token' => $user->createToken('kliens')->plainTextToken,
            'felhasznalo' => $user,
        ], 201);
    }

    public function bejelentkezes(Request $request)
    {
        $adat = $request->validate([
            'email' => 'required|email',
            'jelszo' => 'required|string',
        ]);

        $user = User::where('email', $adat['email'])->first();

        if (!$user || !Hash::check($adat['jelszo'], $user->password)) {
            return response()->json(['uzenet' => 'Hibás e-mail cím vagy jelszó.'], 422);
        }

        return response()->json([
            'token' => $user->createToken('kliens')->plainTextToken,
            'felhasznalo' => $user,
        ]);
    }

    public function kijelentkezes(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['uzenet' => 'Kijelentkeztél.']);
    }
}
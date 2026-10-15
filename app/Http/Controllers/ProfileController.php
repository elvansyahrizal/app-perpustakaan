<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        return view('profile.show', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        if ($request->has('current_password') && ! $request->has('password_lama')) {
            $request->merge(['password_lama' => $request->input('current_password')]);
        }
        if ($request->has('password') && ! $request->has('password_baru')) {
            $request->merge(['password_baru' => $request->input('password')]);
            $request->merge(['password_baru_confirmation' => $request->input('password_confirmation')]);
        }

        $validated = $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:8|confirmed',
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min' => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = $request->user();

        if (! Hash::check($validated['password_lama'], $user->password)) {
            return back()->withErrors([
                'password_lama' => 'Password lama tidak cocok dengan password saat ini.',
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['password_baru']),
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Password berhasil diperbarui.');
    }
}

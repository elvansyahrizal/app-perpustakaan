@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
    <h1>Profil Pengguna</h1>

    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; max-width: 500px; margin-bottom: 30px;">
        <h2 style="font-size: 18px; margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">Informasi Akun</h2>
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 120px; font-weight: bold; padding: 6px 0;">Nama</td>
                <td style="padding: 6px 0;">: {{ $user->name }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; padding: 6px 0;">Email</td>
                <td style="padding: 6px 0;">: {{ $user->email }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; padding: 6px 0;">Role</td>
                <td style="padding: 6px 0;">: <span class="badge badge-dipinjam">{{ ucfirst($user->role) }}</span></td>
            </tr>
        </table>
    </div>

    <div style="max-width: 500px;">
        <h2>Ganti Password</h2>
        <form action="{{ route('profile.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 12px;">
                <label for="password_lama" style="display: block; font-weight: bold; margin-bottom: 4px;">Password Lama</label>
                <input type="password" name="password_lama" id="password_lama" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 4px;" required>
                @error('password_lama')
                    <div style="color: #b91c1c; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 12px;">
                <label for="password_baru" style="display: block; font-weight: bold; margin-bottom: 4px;">Password Baru</label>
                <input type="password" name="password_baru" id="password_baru" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 4px;" required>
                @error('password_baru')
                    <div style="color: #b91c1c; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 16px;">
                <label for="password_baru_confirmation" style="display: block; font-weight: bold; margin-bottom: 4px;">Konfirmasi Password Baru</label>
                <input type="password" name="password_baru_confirmation" id="password_baru_confirmation" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 4px;" required>
            </div>

            <button type="submit" class="btn">Simpan Password Baru</button>
        </form>
    </div>
@endsection

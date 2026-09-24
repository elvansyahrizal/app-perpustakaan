@extends('layouts.app')

@section('title', 'Edit Anggota')

@section('content')
    <h1>Edit Anggota</h1>
    <p><a href="{{ route('members.index') }}">&larr; Kembali ke daftar anggota</a></p>

    <form action="{{ route('members.update', $member['id']) }}" method="POST" style="max-width: 500px;">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 16px;">
            <label style="display:block; font-weight:bold;">Nama</label>
            <input type="text" name="nama" value="{{ old('nama', $member['nama']) }}" style="width:100%; padding:8px;">
            @error('nama') <div style="color:red; font-size:14px;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 16px;">
            <label style="display:block; font-weight:bold;">NIM</label>
            <input type="text" name="nim" value="{{ old('nim', $member['nim']) }}" style="width:100%; padding:8px;">
            @error('nim') <div style="color:red; font-size:14px;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 16px;">
            <label style="display:block; font-weight:bold;">Email</label>
            <input type="email" name="email" value="{{ old('email', $member['email']) }}" style="width:100%; padding:8px;">
            @error('email') <div style="color:red; font-size:14px;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 16px;">
            <label style="display:block; font-weight:bold;">No. Telepon</label>
            <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $member['nomor_telepon']) }}" style="width:100%; padding:8px;">
            @error('nomor_telepon') <div style="color:red; font-size:14px;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 16px;">
            <label style="display:block; font-weight:bold;">Alamat</label>
            <textarea name="alamat" rows="3" style="width:100%; padding:8px;">{{ old('alamat', $member['alamat']) }}</textarea>
            @error('alamat') <div style="color:red; font-size:14px;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 16px;">
            <label style="display:block; font-weight:bold;">Status</label>
            <select name="status" style="width:100%; padding:8px;">
                <option value="aktif" @selected(old('status', $member['status']) == 'aktif')>Aktif</option>
                <option value="nonaktif" @selected(old('status', $member['status']) == 'nonaktif')>Nonaktif</option>
            </select>
            @error('status') <div style="color:red; font-size:14px;">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn">Perbarui</button>
    </form>
@endsection
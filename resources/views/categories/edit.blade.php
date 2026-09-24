@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <h1>Edit Kategori</h1>
    <p><a href="{{ route('categories.index') }}">&larr; Kembali ke daftar kategori</a></p>

    <form action="{{ route('categories.update', $category['id']) }}" method="POST" style="max-width: 500px;">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 16px;">
            <label for="nama_kategori" style="display:block; font-weight:bold;">Nama Kategori</label>
            <input type="text" name="nama_kategori" id="nama_kategori" value="{{ old('nama_kategori', $category['nama_kategori']) }}" style="width:100%; padding:8px;">
            @error('nama_kategori')
                <div style="color:red; font-size:14px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom: 16px;">
            <label for="deskripsi" style="display:block; font-weight:bold;">Deskripsi (opsional)</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" style="width:100%; padding:8px;">{{ old('deskripsi', $category['deskripsi']) }}</textarea>
            @error('deskripsi')
                <div style="color:red; font-size:14px;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn">Perbarui</button>
    </form>
@endsection
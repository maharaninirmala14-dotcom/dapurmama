@extends('layouts.app')

@section('content')
<div class="section" style="display: flex; align-items: center; min-height: 80vh;">
    <div class="container">
        <div class="form-card" style="max-width: 600px;">
            <h2 class="form-title"><i class="fa-solid fa-user-plus" style="color: var(--primary);"></i> Daftar Akun Baru</h2>
            
            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Nama Lengkap Anda">
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="contoh@gmail.com">
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">No. Telepon / WhatsApp (Opsional)</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                    @error('phone')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Alamat Lengkap (Opsional)</label>
                    <textarea id="address" name="address" class="form-control" rows="3" placeholder="Alamat pengiriman catering">{{ old('address') }}</textarea>
                    @error('address')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required placeholder="Ulangi password">
                    </div>
                </div>
                
                <button type="submit" class="form-btn">
                    Daftar Sekarang
                </button>
            </form>
            
            <div class="form-footer-link">
                Sudah memiliki akun? <a href="{{ route('login') }}">Masuk Disini</a>
            </div>
        </div>
    </div>
</div>
@endsection

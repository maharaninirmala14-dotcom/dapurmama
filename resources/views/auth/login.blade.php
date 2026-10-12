@extends('layouts.app')

@section('content')
<div class="section" style="display: flex; align-items: center; min-height: 70vh;">
    <!-- Lebar kontainer diperbesar dari 480px menjadi 560px agar card melebar -->
    <div class="container" style="max-width: 560px;">
        
        <div class="form-card" style="width: 100%; box-sizing: border-box; padding: 35px 40px;">

            <h2 class="form-title"><i class="fa-solid fa-right-to-bracket" style="color: var(--primary);"></i> Masuk</h2>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="contoh@gmail.com">
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required placeholder="Masukkan password Anda">
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" id="remember" name="remember" style="cursor: pointer; width: 16px; height: 16px;">
                        <label for="remember" style="font-size: 0.85rem; color: var(--text-muted); cursor: pointer; user-select: none;">Ingat saya di perangkat ini</label>
                    </div>
                    <a href="{{ route('password.request') }}" style="font-size: 0.85rem; color: var(--primary); text-decoration: none; font-weight: 600;">Lupa password?</a>
                </div>

                <button type="submit" class="form-btn">
                    Masuk Sekarang
                </button>
            </form>

            <div class="form-footer-link">
                Belum memiliki akun? <a href="{{ route('register') }}">Daftar Disini</a>
            </div>
        </div>
        
    </div>
</div>
@endsection
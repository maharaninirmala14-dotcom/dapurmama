<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Dapur Mama Delisha</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body style="font-family: 'Inter', sans-serif; background: #fdfbf7; margin: 0; display: flex; align-items: center; justify-content: center; min-height: 100vh;">

    <div style="background: #ffffff; width: 100%; max-width: 440px; padding: 32px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; box-sizing: border-box;">
        <h2 style="font-size: 1.3rem; font-weight: 700; color: #1e293b; margin-bottom: 8px; text-align: center;">Reset Password</h2>
        <p style="font-size: 0.85rem; color: #64748b; text-align: center; margin-bottom: 24px;">Masukkan email Anda dan buat password baru.</p>

        @if (session('status'))
            <div style="background: #dcfce7; color: #166534; padding: 10px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 16px;">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
            @csrf
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Alamat Email</label>
                <input type="email" name="email" required placeholder="nama@email.com" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.85rem; box-sizing: border-box; outline: none;">
                @error('email') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Password Baru</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.85rem; box-sizing: border-box; outline: none;">
                @error('password') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #334155; margin-bottom: 6px;">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi password baru" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.85rem; box-sizing: border-box; outline: none;">
            </div>

            <button type="submit" style="background-color: #1f3a38; color: white; border: none; padding: 12px; border-radius: 8px; font-size: 0.9rem; font-weight: 600; cursor: pointer; margin-top: 8px; transition: background 0.2s;">Reset Password</button>
        </form>

        <div style="text-align: center; margin-top: 20px; font-size: 0.85rem;">
            <a href="{{ route('login') }}" style="color: #1f3a38; text-decoration: none; font-weight: 600;">Kembali ke halaman Masuk</a>
        </div>
    </div>

</body>
</html>
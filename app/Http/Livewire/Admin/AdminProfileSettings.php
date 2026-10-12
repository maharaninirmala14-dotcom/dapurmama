<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileSettings extends Component
{
    public $name;
    public $email;
    public $phone;

    // Password fields
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->phone = $user->phone ?? '';
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        session()->flash('profile_message', 'Profil berhasil diperbarui!');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|same:new_password_confirmation',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.same' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();

        // Verifikasi password saat ini
        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Password saat ini salah.');
            return;
        }

        // Update password baru ke database
        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        // Reset form password
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('password_message', 'Password berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.admin.admin-settings')->layout('layouts.admin');
    }
}
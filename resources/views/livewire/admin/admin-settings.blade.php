@section('title', 'Pengaturan Akun')
<div class="container-fluid py-4">
    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold text-dark mb-1">Pengaturan Akun</h1>
            <p class="text-muted small mb-0">Kelola informasi profil dan keamanan akun administrator Anda.</p>
        </div>
    </div>

    <div class="row">
        <!-- CARD 1: INFORMASI PROFIL -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow-sm border-0 rounded-lg h-100">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="m-0 font-weight-bold text-success">
                        <i class="fa-solid fa-user-pen me-2"></i> Informasi Profil
                    </h5>
                </div>
                <div class="card-body pt-1">
                    @if (session()->has('profile_message'))
                        <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                            <i class="fa-solid fa-circle-check me-1"></i> {{ session('profile_message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="updateProfile">
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-secondary small">Nama Lengkap</label>
                            <input type="text" class="form-control" wire:model.defer="name">
                            @error('name') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-secondary small">Alamat Email</label>
                            <input type="email" class="form-control" wire:model.defer="email">
                            @error('email') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-weight-bold text-secondary small">Nomor Telepon / WhatsApp</label>
                            <input type="text" class="form-control" wire:model.defer="phone">
                            @error('phone') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success px-4 font-weight-bold">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- CARD 2: KEAMANAN & PASSWORD -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow-sm border-0 rounded-lg h-100">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="m-0 font-weight-bold text-success">
                        <i class="fa-solid fa-shield-halved me-2"></i> Keamanan & Password
                    </h5>
                </div>
                <div class="card-body pt-1">
                    @if (session()->has('password_message'))
                        <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                            <i class="fa-solid fa-circle-check me-1"></i> {{ session('password_message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="updatePassword">
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-secondary small">Password Saat Ini</label>
                            <input type="password" class="form-control" wire:model.defer="current_password">
                            @error('current_password') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-secondary small">Password Baru</label>
                            <input type="password" class="form-control" wire:model.defer="new_password">
                            @error('new_password') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-weight-bold text-secondary small">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" wire:model.defer="new_password_confirmation">
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-dark px-4 font-weight-bold">
                                <i class="fa-solid fa-key me-1"></i> Perbarui Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
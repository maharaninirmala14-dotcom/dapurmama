<?php $__env->startSection('content'); ?>
<div class="section" style="display: flex; align-items: center; min-height: 70vh;">
    <!-- Lebar kontainer diperbesar dari 480px menjadi 560px agar card melebar -->
    <div class="container" style="max-width: 560px;">
        
        <div class="form-card" style="width: 100%; box-sizing: border-box; padding: 35px 40px;">

            <h2 class="form-title"><i class="fa-solid fa-right-to-bracket" style="color: var(--primary);"></i> Masuk</h2>

            <form action="<?php echo e(route('login')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required autofocus placeholder="contoh@gmail.com">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="form-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required placeholder="Masukkan password Anda">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="form-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="form-group" style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" id="remember" name="remember" style="cursor: pointer; width: 16px; height: 16px;">
                        <label for="remember" style="font-size: 0.85rem; color: var(--text-muted); cursor: pointer; user-select: none;">Ingat saya di perangkat ini</label>
                    </div>
                    <a href="<?php echo e(route('password.request')); ?>" style="font-size: 0.85rem; color: var(--primary); text-decoration: none; font-weight: 600;">Lupa password?</a>
                </div>

                <button type="submit" class="form-btn">
                    Masuk Sekarang
                </button>
            </form>

            <div class="form-footer-link">
                Belum memiliki akun? <a href="<?php echo e(route('register')); ?>">Daftar Disini</a>
            </div>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\malaa\resources\views/auth/login.blade.php ENDPATH**/ ?>
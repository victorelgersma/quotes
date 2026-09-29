<?php $__env->startSection('content'); ?>
<div class="max-w-md mx-auto px-6 py-16">
    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
        <?php if(session('status') === 'login-link-sent'): ?>
            <p class="text-base font-semibold">Check your inbox</p>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                We've sent a link to log in. It expires in 15 minutes.
            </p>

            <a
                href="<?php echo e(route('login')); ?>"
                class="mt-6 inline-block text-sm font-medium text-gray-900 dark:text-gray-100 hover:underline"
            >
                Log in with a different email address
            </a>
        <?php else: ?>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Enter your email and we'll send you a link to log in.
            </p>

            <form method="POST" action="<?php echo e(route('login.link')); ?>" class="space-y-3">
                <?php echo csrf_field(); ?>

                <div class="absolute -left-[9999px]" aria-hidden="true">
                    <label for="website">Leave this field empty</label>
                    <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                </div>

                <input
                    type="email"
                    name="email"
                    value="<?php echo e(old('email')); ?>"
                    placeholder="you@example.com"
                    required
                    autofocus
                    autocomplete="username"
                    class="w-full px-3 py-2 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-gray-900 dark:focus:ring-gray-100"
                >

                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-sm text-red-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <button
                    type="submit"
                    class="w-full px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-medium hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-white transition-colors"
                >
                    Continue with email
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/victorelgersma/dev/quotes/resources/views/auth/login.blade.php ENDPATH**/ ?>
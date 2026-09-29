<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-6 py-10" x-data="{ search: '' }">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?php echo e(route('tags.index')); ?>" class="text-sm text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
            ← Tags
        </a>
        <h1 class="text-2xl font-semibold"><?php echo e($tag->name); ?></h1>
    </div>

    <?php if(session('status') === 'quote-updated'): ?>
        <div class="mb-4 text-sm text-green-600 dark:text-green-400">Updated.</div>
    <?php elseif(session('status') === 'quote-deleted'): ?>
        <div class="mb-4 text-sm text-green-600 dark:text-green-400">Deleted.</div>
    <?php endif; ?>

    <?php if($quotes->isEmpty()): ?>
        <div class="text-center text-gray-400 dark:text-gray-600 py-20">
            No quotes tagged "<?php echo e($tag->name); ?>" yet.
        </div>
    <?php else: ?>
        <div class="columns-1 sm:columns-2 lg:columns-3 gap-4">
            <?php $__currentLoopData = $quotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('quotes._card', ['quote' => $quote, 'availableTags' => $availableTags], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/victorelgersma/dev/quotes/resources/views/tags/show.blade.php ENDPATH**/ ?>
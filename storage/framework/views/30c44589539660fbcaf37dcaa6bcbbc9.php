<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-6 py-10">
    <h1 class="text-2xl font-semibold mb-6">Tags</h1>

    <?php if($tags->isEmpty()): ?>
        <div class="text-center text-gray-400 dark:text-gray-600 py-20">
            No tags yet — add some tags to your quotes.
        </div>
    <?php else: ?>
        <div class="flex flex-wrap gap-3">
            <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a
                    href="<?php echo e(route('tags.show', $tag)); ?>"
                    class="flex items-center gap-2 px-4 py-2 rounded-full border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 hover:border-gray-900 dark:hover:border-gray-100 transition-colors"
                >
                    <span class="font-medium"><?php echo e($tag->name); ?></span>
                    <span class="text-xs text-gray-400 dark:text-gray-600"><?php echo e($tag->quotes_count); ?></span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/victorelgersma/dev/quotes/resources/views/tags/index.blade.php ENDPATH**/ ?>
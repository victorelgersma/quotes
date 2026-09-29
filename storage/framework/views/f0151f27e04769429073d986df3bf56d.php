
<div
    x-data="tagInput(<?php echo \Illuminate\Support\Js::from($initialTags)->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($availableTags)->toHtml() ?>)"
    class="flex flex-wrap items-center gap-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-2 py-1.5 focus-within:ring-2 focus-within:ring-gray-900 dark:focus-within:ring-gray-100 <?php echo e($class ?? ''); ?>"
>
    <template x-for="(tag, index) in tags" :key="tag">
        <span class="inline-flex items-center gap-1 text-xs pl-2 pr-1 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
            <span x-text="tag"></span>
            <button type="button" @click="removeTag(index)" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-100" aria-label="Remove tag">&times;</button>
        </span>
    </template>
    <div class="relative flex-1 min-w-[5rem]">
        <input
            type="text"
            x-model="draft"
            @keydown.enter.prevent="selectHighlighted()"
            @keydown.comma.prevent="selectHighlighted()"
            @keydown.down.prevent="moveHighlight(1)"
            @keydown.up.prevent="moveHighlight(-1)"
            @keydown.escape="draft = ''; highlightedIndex = -1"
            @keydown.backspace="removeLastTag()"
            @blur="addTag()"
            placeholder="Tags…"
            class="w-full bg-transparent border-0 p-0 text-sm focus:ring-0 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-600"
            autocomplete="off"
        >
        <ul
            x-show="suggestions.length > 0"
            x-cloak
            class="absolute z-10 mt-1 w-48 max-h-40 overflow-auto rounded-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg text-sm"
        >
            <template x-for="(name, i) in suggestions" :key="name">
                <li
                    @mousedown.prevent="addTag(name)"
                    :class="i === highlightedIndex ? 'bg-gray-100 dark:bg-gray-700' : ''"
                    class="px-3 py-1.5 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200"
                    x-text="name"
                ></li>
            </template>
        </ul>
    </div>
    <input type="hidden" name="tags" :value="tags.join(',')">
</div>
<?php /**PATH /Users/victorelgersma/dev/quotes/resources/views/quotes/_tag-input.blade.php ENDPATH**/ ?>
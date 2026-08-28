<div class="space-y-3 text-sm">
    <div class="rounded-xl bg-gray-100 p-3 leading-6 text-gray-700 dark:bg-white/5 dark:text-gray-200">
        @if($handle)
            I found this TikTok from <strong>{{ '@'.$handle }}</strong>.
        @elseif($author)
            I found this TikTok from <strong>{{ $author }}</strong>.
        @else
            I found this TikTok.
        @endif
        Want it on the website?
    </div>
    @if(!empty($thumbnail))
        <img
            src="{{ $thumbnail }}"
            alt="TikTok cover"
            class="w-full max-h-72 rounded-xl object-cover object-top border border-gray-200 dark:border-white/10"
            referrerpolicy="no-referrer"
        >
    @endif
    @if($caption)
        <div class="rounded-xl border border-gray-200 bg-white p-3 leading-6 text-gray-800 dark:border-white/10 dark:bg-gray-900 dark:text-gray-100">
            <div class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500">Caption on that post</div>
            <p class="whitespace-pre-wrap m-0">{{ $caption }}</p>
        </div>
    @endif
    @if($error)
        <div class="rounded-xl bg-danger-50 p-3 text-danger-700 dark:bg-danger-500/10 dark:text-danger-300">
            {{ $error }}
        </div>
    @endif
</div>

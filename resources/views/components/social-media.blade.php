@if($items->isNotEmpty())
    <div class="hz-social-list">
        @foreach ($items as $item)
            <a
                href="{{ $item['link'] }}"
                target="_blank"
                rel="noopener noreferrer"
                title="{{ $item['title'] }}"
                aria-label="{{ $item['title'] }}"
            >
                <i class="{{ $item['icon'] }}"></i>
            </a>
        @endforeach
    </div>
@endif

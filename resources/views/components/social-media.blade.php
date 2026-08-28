<div class="d-inline-flex flex-wrap gap-2">
    @foreach ($socialRefs as $ref)
        <a href="{{ $ref->link }}" target="_blank" rel="noopener noreferrer" title="{{ $ref->title }}" aria-label="{{ $ref->title }}">
            <i class="{{ $ref->icon_class }}"></i>
        </a>
    @endforeach
</div>

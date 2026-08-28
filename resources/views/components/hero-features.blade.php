<div class="container">
    <div class="row">
        @foreach($features as $item)
        <div class="col-md-4 pbmit-column">
            <div class="pbmit-ihbox-style-4">
                <div class="pbmit-ihbox-box">
                    <div class="pbmit-ihbox-icon">
                        <div class="pbmit-ihbox-icon-wrapper pbmit-icon-type-icon">
                            {{-- Optionally display an icon if available --}}
                            @if(!empty($item['icon']))
                            <i class="{{ $item['icon'] }}"></i>
                            @endif
                        </div>
                    </div>
                    <div class="pbmit-ihbox-contents">
                        <h2 class="pbmit-element-title">{{ $item['title'] ?? '' }}</h2>
                        <div class="pbmit-heading-desc">{{ $item['description'] ?? '' }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>


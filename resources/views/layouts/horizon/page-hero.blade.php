<section class="hz-page-hero">
    <div class="container">
        <div class="eyebrow">@yield('eyebrow', $data['siteName'] ?? config('app.name'))</div>
        <h1>@yield('page_title')</h1>
        @hasSection('description')
            <p>@yield('description')</p>
        @endif
    </div>
</section>

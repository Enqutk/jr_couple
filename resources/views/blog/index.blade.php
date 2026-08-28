@extends('layouts.inner')

@section('title', 'Blog')
@section('eyebrow', 'Stories & tips')
@section('page_title', 'Blog')
@section('description', $data['metaDescription'] ?? 'News and tips from JR.')

@section('page')
<section class="hz-section">
    <div class="container">
        <div class="row g-4">
            @forelse($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <x-horizon.blog-card :post="$post" />
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted mb-0">Blog posts will appear here once published in admin (Website → Blog posts).</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

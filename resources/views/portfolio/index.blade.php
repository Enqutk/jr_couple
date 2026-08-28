@extends('layouts.inner')

@section('title', 'Portfolio')
@section('eyebrow', 'Selected work')
@section('page_title', 'Portfolio')
@section('description', $data['metaDescription'] ?? ($data['tagline'] ?? ''))

@section('page')
    @if($data['tagline'] ?? null)
        <div class="hz-section pb-0">
            <div class="container">
                <p class="hz-lead mb-0" style="max-width: 40rem;">
                    {{ $data['tagline'] }}
                </p>
            </div>
        </div>
    @endif

    <x-horizon.portfolio
        :projects="$projects"
        :categories="$categories"
        :show-filters="true"
        :show-header="false"
    />
@endsection

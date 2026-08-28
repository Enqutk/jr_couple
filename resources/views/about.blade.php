@extends('layouts.inner')

@section('title', 'About Us')
@section('eyebrow', 'Who we are')
@section('page_title', 'About ' . ($data['siteName'] ?? config('app.name')))
@section('description', $data['metaDescription'] ?? ($data['tagline'] ?? ''))

@section('page')
    <x-horizon.about
        :about="$data['aboutFeatures']"
        :features="$data['heroFeatures']"
        :show-link="false"
    />

    @if($data['aboutSection1'] || $data['aboutSection2'])
        <section class="hz-section hz-about-story bg-surface border-top border-bottom border-hz">
            <div class="container">
                <div class="row justify-content-center mb-4">
                    <div class="col-lg-8 text-center">
                        <p class="hz-eyebrow">Our story</p>
                        <h2 class="hz-title">How we work</h2>
                    </div>
                </div>
                <div class="row g-4">
                    @if($data['aboutSection1'])
                        <div class="col-lg-6">
                            <article class="hz-about-panel">
                                @if(!empty($data['aboutSection1']['image']))
                                    <div class="hz-about-panel-media">
                                        <img
                                            src="{{ $data['aboutSection1']['image'] }}"
                                            alt="{{ $data['aboutSection1']['title'] ?? 'Our practice' }}"
                                        >
                                    </div>
                                @endif
                                <div class="hz-about-panel-body">
                                    <h3 class="h4 mb-3">{{ $data['aboutSection1']['title'] ?? 'Our practice' }}</h3>
                                    <div class="hz-lead mb-0">{!! $data['aboutSection1']['description'] !!}</div>
                                </div>
                            </article>
                        </div>
                    @endif
                    @if($data['aboutSection2'])
                        <div class="col-lg-6">
                            <article class="hz-about-panel">
                                @if(!empty($data['aboutSection2']['image']))
                                    <div class="hz-about-panel-media">
                                        <img
                                            src="{{ $data['aboutSection2']['image'] }}"
                                            alt="{{ $data['aboutSection2']['title'] ?? 'Our approach' }}"
                                        >
                                    </div>
                                @endif
                                <div class="hz-about-panel-body">
                                    <h3 class="h4 mb-3">{{ $data['aboutSection2']['title'] ?? 'Our approach' }}</h3>
                                    <div class="hz-lead mb-0">{!! $data['aboutSection2']['description'] !!}</div>
                                </div>
                            </article>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <x-horizon.stats
        :stats="$data['stats']"
        :title="$data['statsTitle']"
        :subtitle="$data['statsSubtitle']"
    />
    <x-horizon.team :team="$team" :show-bios="true" />
    <x-horizon.clients :clients="$clients" />
@endsection

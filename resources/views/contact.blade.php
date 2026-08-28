@extends('layouts.inner')

@section('title', 'Contact Us')
@section('eyebrow', 'Get in touch')
@section('page_title', 'Contact Us')
@section('description', 'Reach ' . ($data['siteName'] ?? config('app.name')) . ($data['tagline'] ? ' — ' . $data['tagline'] : ''))

@php
    $dayLabels = [
        'mon' => 'Mon', 'tue' => 'Tue', 'wed' => 'Wed', 'thu' => 'Thu',
        'fri' => 'Fri', 'sat' => 'Sat', 'sun' => 'Sun',
    ];
    $recipient = $data['email'][0] ?? config('mail.from.address');
@endphp

@section('page')
<section class="hz-section hz-contact">
    <div class="container">
        <div class="row g-4 g-xl-5">
            <div class="col-lg-7">
                <p class="hz-lead mb-4">
                    {{ $data['tagline'] ?? 'Share a brief about your project. We’ll respond with next steps.' }}
                </p>

                @if(session('success'))
                    <div class="hz-alert hz-alert-success mb-4" role="status">
                        <i class="bi bi-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="hz-alert hz-alert-error mb-4" role="alert">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="hz-alert hz-alert-error mb-4" role="alert">
                        <i class="bi bi-exclamation-circle"></i>
                        <div>
                            <strong>Please fix the following:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form class="hz-form" method="POST" action="{{ route('contact.send', ['recipient' => $recipient]) }}" novalidate>
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="hz-label" for="contact-name">Full name</label>
                            <input id="contact-name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="hz-label" for="contact-email">Email</label>
                            <input id="contact-email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="hz-label" for="contact-phone">Phone</label>
                            <input id="contact-phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="tel">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="hz-label" for="contact-subject">Subject</label>
                            <input id="contact-subject" type="text" class="form-control @error('subject') is-invalid @enderror" name="subject" value="{{ old('subject') }}" required>
                            @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="hz-label" for="contact-message">Message</label>
                            <textarea id="contact-message" class="form-control @error('message') is-invalid @enderror" name="message" rows="7" required>{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <button class="btn-hz" type="submit">
                                Send message <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-5">
                <aside class="hz-contact-panel">
                    <h3>Contact details</h3>

                    <div class="hz-contact-item">
                        <div class="label"><i class="bi bi-envelope"></i> Email</div>
                        @forelse(($data['email'] ?? []) as $email)
                            <div><a href="mailto:{{ $email }}">{{ $email }}</a></div>
                        @empty
                            <div class="opacity-75">Not configured</div>
                        @endforelse
                    </div>

                    <div class="hz-contact-item">
                        <div class="label"><i class="bi bi-telephone"></i> Phone</div>
                        @forelse(($data['phone'] ?? []) as $phone)
                            <div><a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a></div>
                        @empty
                            <div class="opacity-75">Not configured</div>
                        @endforelse
                    </div>

                    <div class="hz-contact-item">
                        <div class="label"><i class="bi bi-geo-alt"></i> Address</div>
                        <div>{{ $data['address'] ?? 'Not configured' }}</div>
                    </div>

                    <div class="hz-contact-item">
                        <div class="label"><i class="bi bi-clock"></i> Working hours</div>
                        @forelse(($data['working_days'] ?? []) as $slot)
                            @if(isset($slot['days'], $slot['from'], $slot['to']))
                                @php
                                    $days = collect($slot['days'])->map(fn ($d) => $dayLabels[$d] ?? $d)->implode('–');
                                @endphp
                                <div>{{ $days }}: {{ substr($slot['from'], 0, 5) }} – {{ substr($slot['to'], 0, 5) }}</div>
                            @endif
                        @empty
                            <div class="opacity-75">Not configured</div>
                        @endforelse
                    </div>

                    <div class="hz-contact-item mb-0">
                        <div class="label">Social</div>
                        <div class="hz-social hz-social-on-dark">
                            <x-social-media />
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>

@if(!empty($data['map']))
<section class="hz-section pt-0">
    <div class="container">
        <p class="hz-eyebrow">Find us</p>
        <h2 class="h4 mb-3">Location</h2>
        <div class="hz-map">
            {!! $data['map'] !!}
        </div>
    </div>
</section>
@endif
@endsection

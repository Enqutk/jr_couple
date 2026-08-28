@extends('layouts.inner')

@section('title', 'Our Services')
@section('eyebrow', 'What we do')
@section('page_title', 'Our Services')
@section('description', $data['metaDescription'] ?? ($data['tagline'] ?? ''))

@section('page')
    <x-horizon.services :services="$services" :show-header="false" />
    <x-horizon.cta />
@endsection

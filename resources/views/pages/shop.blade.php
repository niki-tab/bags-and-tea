@extends('layouts.app')

@section('main-tag-class')
bg-background-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

@section('footer-desktop-class')
bg-background-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

<meta name="robots" content="noindex, nofollow" />
@section('content')
        <div class="bg-color-4">
                @livewire('shop', ['categorySlug' => $categorySlug ?? null])
        </div>
@endsection
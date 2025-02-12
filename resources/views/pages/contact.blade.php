@extends('layouts.app')
<meta name="robots" content="noindex, nofollow" />
@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold text-left mb-6 ml-4 mt-8 md:ml-16 md:mt-4">{{ trans('pages/contact.title-page') }}</h1>
        @livewire('contact-form')
</div>
@endsection
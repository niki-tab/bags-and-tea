@extends('layouts.app')
<meta name="robots" content="noindex, nofollow" />
@section('content')
        @livewire('product-detail', ['productSlug' => request()->route('productSlug')])
        @livewire('product-information-tab')
@endsection

@metadata


@extends('layouts.admin.app')

@section('title', 'Bag Search Queries')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="py-6">
        @livewire('admin.bag-search-queries.show-all')
    </div>
</div>
@endsection

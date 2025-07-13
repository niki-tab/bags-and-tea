@extends('layouts.admin.app')

@section('title', 'Categories Management')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="py-6">
        @livewire('admin.categories.show-all')
    </div>
</div>
@endsection
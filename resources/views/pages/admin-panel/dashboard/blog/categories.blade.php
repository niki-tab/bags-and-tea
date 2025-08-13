@extends('layouts.admin.app')

@section('title', 'Blog Categories Management')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="py-6">
        @livewire('admin.blog.categories.show-all')
    </div>
</div>
@endsection
@extends('layouts.admin.app')

@section('title', 'Edit Category')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @livewire('admin.categories.edit-form', ['categoryId' => $id])
        </div>
    </div>
</div>
@endsection
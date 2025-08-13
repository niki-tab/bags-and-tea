@extends('layouts.admin.app')

@section('page-title', 'Create Blog Category')

@section('content')
    <div class="py-6">
        @livewire('admin.blog.categories.create-form')
    </div>
@endsection
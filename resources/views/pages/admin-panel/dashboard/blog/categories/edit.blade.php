@extends('layouts.admin.app')

@section('page-title', 'Edit Blog Category')

@section('content')
    <div class="py-6">
        @livewire('admin.blog.categories.edit-form', ['categoryId' => $id])
    </div>
@endsection
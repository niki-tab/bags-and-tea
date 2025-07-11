@extends('layouts.admin.app')

@section('page-title', 'Create Category')

@section('content')
    <div class="py-6">
        @livewire('admin.categories.create-form')
    </div>
@endsection
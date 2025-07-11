@extends('layouts.admin.app')

@section('page-title', 'Create Attribute')

@section('content')
    <div class="py-6">
        @livewire('admin.attributes.create-form')
    </div>
@endsection
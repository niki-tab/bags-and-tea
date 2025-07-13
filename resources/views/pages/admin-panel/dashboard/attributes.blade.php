@extends('layouts.admin.app')

@section('title', 'Attributes Management')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="py-6">
        @livewire('admin.attributes.show-all')
    </div>
</div>
@endsection
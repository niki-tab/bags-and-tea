@extends('layouts.admin.app')

@section('page-title', 'Bag Supply Hunting')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="py-6">
        @livewire('admin.bag-supply-hunting.show-all')
    </div>
</div>
@endsection

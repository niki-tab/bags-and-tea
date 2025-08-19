@extends('layouts.admin.app')

@section('title', 'Orders Management')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="py-6">
        @livewire('admin.orders.order.show-all')
    </div>
</div>
@endsection
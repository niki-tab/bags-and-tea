@extends('layouts.admin.app')

@section('title', 'Edit Bag Search Query')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @livewire('admin.bag-search-queries.edit-form', ['queryId' => $id])
        </div>
    </div>
</div>
@endsection

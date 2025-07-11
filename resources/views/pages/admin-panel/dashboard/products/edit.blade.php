@extends('layouts.admin.app')

@section('page-title', 'Edit Product')

@section('content')
    @livewire('admin.products.product-form', ['productId' => $id])
@endsection
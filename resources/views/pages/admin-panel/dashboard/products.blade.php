@extends('layouts.admin.app')

@section('page-title', 'Products')

@section('content')

    @livewire('products/show', ['numberProduct' => null])

@endsection
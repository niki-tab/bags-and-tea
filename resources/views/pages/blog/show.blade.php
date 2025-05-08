@extends('layouts.app')

@section('content')

    @livewire('blog/show', ['numberArticles' => null])

@endsection

@metadata
@extends('layouts.app')

@section('content')
    @livewire('blog/articles/show', ['articleSlug' => request()->route('articleSlug')])
@endsection
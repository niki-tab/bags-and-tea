@extends('layouts.admin.app')

@section('page-title', 'Blog Articles')

@section('content')

    @livewire('admin.blog.articles.show-all', ['numberArticle' => null])

@endsection
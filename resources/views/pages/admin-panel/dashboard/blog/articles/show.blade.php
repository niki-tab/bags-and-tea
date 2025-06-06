@extends('layouts.admin.app')

@section('content')
    @livewire('admin.blog.articles.add-edit', ['id' => request()->route('id')])
@endsection
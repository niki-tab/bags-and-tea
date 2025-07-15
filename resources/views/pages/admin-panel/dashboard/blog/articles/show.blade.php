@extends('layouts.admin.app')

@section('content')
    @livewire('admin.blog.articles.add-edit', [
        'id' => request()->route('id'),
        'uuid' => request()->route('uuid'),
        'mode' => $mode ?? (request()->route('id') ? 'edit' : 'create')
    ])
@endsection
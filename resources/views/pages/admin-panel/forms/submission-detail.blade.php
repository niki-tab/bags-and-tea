@extends('layouts.admin.app')

@section('page-title', 'Form Submission Detail')

@section('content')

    @livewire('admin.crm.show-submission-detail', ['submissionId' => $submissionId])

@endsection
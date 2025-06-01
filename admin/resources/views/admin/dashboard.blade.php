@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
    @include('partials.dashboard.styles')

@endsection

@section('content')
@php
    use Illuminate\Support\Str;
    $questions = \App\Models\TravelRequestQuestion::where('status', 'active')->get();
@endphp

<div class="container-custom" style="margin-top: 80px;">
    @include('partials.dashboard.header')


    @include('partials.dashboard.calendar')

    @include('partials.dashboard.table_pending_requests', [
        'pendingRequests' => $pendingRequests,
        'questions' => $questions
    ])

    @include('partials.dashboard.table_local_forms', [
        'pendingLocalForms' => $pendingLocalForms,
        'questions' => $questions
    ])

    @include('partials.dashboard.table_overseas_forms', [
        'pendingOverseasForms' => $pendingOverseasForms,
        'questions' => $questions
    ])

    @include('partials.dashboard.calendar_modal', ['calendarEvents' => $calendarEvents])
</div>

@endsection

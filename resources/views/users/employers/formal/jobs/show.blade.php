@extends('layouts.app')

@section('content')
    <h1>{{ $job->title }}</h1>
    <p>{{ $job->description }}</p>
    <p>Posted by: {{ $job->employer->name }}</p>
@endsection
@extends('layouts.dashboard')
@extends('_layouts.master')

@section('title', 'Zu Mir')

@section('content')
    <h1>About</h1>

    <p>Mein Name ist {{ $page->owner->name }}</p>

    <h2>Links:</h2>

    <ul>        <li><a href="https://github.com/{{ $page->owner->github }}" target="_blank">GitHub</a></li>
    </ul>
@endsection

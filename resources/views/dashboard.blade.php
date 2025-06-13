@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h3 class="card-title">Welcome, {{ auth()->user()->name }}</h3>
        <p class="card-text">You are logged in</p>
        <a href="{{ url('/logout') }}" class="btn btn-outline-danger">Logout</a>
    </div>
</div>
@endsection

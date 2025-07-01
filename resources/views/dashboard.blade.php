@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-5">
    <div class="card shadow border-0">
        <div class="card-body text-center">
            @if(Auth::user()->avatar)
                <img src="{{ Auth::user()->avatar }}" class="rounded-circle mb-3" width="120" height="120" alt="Profile Picture">
            @else
                <img src="https://via.placeholder.com/120?text=No+Avatar" class="rounded-circle mb-3" width="120" height="120" alt="Profile Picture">
            @endif

            <h3 class="card-title mb-1">Welcome, {{ Auth::user()->name }}</h3>
            <p class="text-muted mb-3">You're successfully logged in{{ Auth::user()->socialite_id ? ' via Google' : '' }}.</p>

            <hr class="my-4">

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <ul class="list-group text-start">
                        <li class="list-group-item"><strong>Name:</strong> {{ Auth::user()->name }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ Auth::user()->email }}</li>
                        @if(Auth::user()->socialite_id)
                            <li class="list-group-item"><strong>Google ID:</strong> {{ Auth::user()->socialite_id }}</li>
                        @endif
                    </ul>
                </div>
            </div>

            <a href="{{ url('/logout') }}" class="btn btn-danger mt-4">Logout</a>
        </div>
    </div>
</div>
@endsection

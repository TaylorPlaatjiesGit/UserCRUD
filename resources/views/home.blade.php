@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="text-center">
        <h1 class="mb-4">Welcome {{ auth()->user()->name }}</h1>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger">
                Logout
            </button>
        </form>
    </div>
@endsection

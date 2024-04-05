@extends('layouts.dashboardLayout')
@section('content')


    @if (session('success'))
        <p>{{session('success')}}</p>
    @endif
    <button class="btn btn-primary mb-3"><a class="text-white" href="{{ route('transaction.create') }}">New Record<i
                class="bi bi-plus-circle"></i></a></button>
@endsection

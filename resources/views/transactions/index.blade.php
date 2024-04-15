@extends('layouts.dashboardLayout')
@section('content')


    @if (session('success'))
        <p>{{session('success')}}</p>
    @endif
    @can('create-transaction')
            <button class="btn btn-primary mb-3"><a class="text-white" href="{{ route('transaction.create') }}">New Record<i
                class="bi bi-plus-circle"></i></a></button>
    @endcan

    <table class="table">
        
    </table>
    @forelse ($records as $record)
        <p>{{$record->id}}</p>
        @forelse ($record->transaction as $transaction)
            <p>{{$transaction->amount}}</p>
        @empty
            <p>No transaction</p>
        @endforelse
    @empty
        <p>No records found</p>
    @endforelse


@endsection

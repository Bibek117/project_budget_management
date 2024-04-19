@extends('layouts.dashboardLayout')
@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Record Details</h5>
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <p>Project Name: {{ $record->project->title }}</p>
                        <p>Project Timeline : {{ $timeline[0]->title }}</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <p>Record Code : {{ $record->code }}</p>
                        <p>Created By : {{ $record->user->username }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <th>#</th>

                    <th>Chart of Account</th>
                    <th>Involved</th>
                    <th>Budget Head</th>
                    <th>Amount</th>
                </tr>
                @php
                    $total = 0.0000;
                @endphp
                @forelse ($transactionsInRecord as $transaction)
                    @php
                        $total +=$transaction->amount;
                    @endphp

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $transaction->COA }}</td>
                        <td>{{ $transaction->contact->user->username }}-{{ $transaction->contact->contacttype->name }}</td>
                        <td>{{ $transaction->budget->name ?? '-' }}</td>
                        <td>{{ $transaction->amount }}</td>
                    </tr>
                @empty
                @endforelse
                <tr>
                    <td colspan="4"></td>
                    <td colspan="2">Net Amount : {{ $total }}</td>
                </tr>
            </table>
            @include('partials.goback')
        </div>
    </div>
@endsection

@extends('layouts.dashboardLayout')
@section('content')
<div class="card">
    <div class="card-header">
        <h5>Record Details</h5>
    </div>
    <div class="card-body">
       <table class="table">
        <tr>
            <th>#</th>
            <th>T_ID</th>
            <th>Amount</th>
            <th>Chart of Account</th>
            <th>Involved</th>
            <th>Budget Head</th>
             <th>Project-Timeline</th>
        </tr>
        @forelse ($transactionsInRecord as $transaction)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$transaction->id}}</td>
                 <td>{{$transaction->amount}}</td>
                 <td>{{$transaction->COA}}</td>
                 <td>{{$transaction->contact->user->username}}-{{$transaction->contact->contacttype->name}}</td>
                 <td>{{$transaction->budget->name ?? "-"}}</td>
                 <td>
                    @if ($transaction->budget)
                        {{$transaction->budget->timeline->project->title}} -- {{$transaction->budget->timeline->title}}

                    @else
                        {{$transaction->record->project->title}}
                    @endif
                 </td>
            </tr>
        @empty
            
        @endforelse
       </table>
       @include('partials.goback')
    </div>
</div>
@endsection
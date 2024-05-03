@extends('layouts.dashboardLayout')
@section('content')
<div class="card">
    <div class="card-body">
        <ul>
            <li><a href="{{route('report.recordDetailCreate')}}">Record Detail Report</a></li>
            <li><a href="{{route('report.contactPayableReceivableCreate')}}">Contact Payable/Receiveable Report</a></li>
        </ul>
    </div>
</div>
@endsection
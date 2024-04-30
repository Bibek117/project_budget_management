@extends('layouts.dashboardLayout')
@section('content')
<div class="card">
    <div class="card-body">
        <ul>
            <li><a href="{{route('report.recordDetailCreate')}}">Record Detail Report</a></li>
        </ul>
    </div>
</div>
@endsection
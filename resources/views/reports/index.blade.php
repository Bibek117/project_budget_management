@extends('layouts.dashboardLayout')
@section('content')
     {{Breadcrumbs::render('report.index')}}
    <div class="card">
        <div class="card-header">
            <h3>Reports</h3>
        </div>
        <div class="card-body">
            <div class="w-50">
                <ul class="list-group text-success">
                    <li class="list-group-item d-flex justify-between">
                        <p>Balance Report</p><a class="text-2xl" href="{{ route('report.recordDetailCreate') }}"><i
                                class="bi bi-box-arrow-in-right text-danger"></i></a>
                    </li>
                    {{-- <li class="list-group-item  d-flex justify-between">
                        <p>Contact Payable/Receiveable Report </p><a
                        class="text-2xl"
                            href="{{ route('report.contactPayableReceivableCreate') }}"><i
                                class="bi bi-box-arrow-in-right text-danger"></i></a>
                    </li> --}}
                    <li class="list-group-item d-flex justify-between">
                        <p>Ageing Report</p><a class="text-2xl" href="{{ route('report.ageingReportCreate') }}"><i
                                class="bi bi-box-arrow-in-right text-danger"></i></a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
@endsection

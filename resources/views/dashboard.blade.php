@extends('layouts.dashboardLayout')
@section('content')
    @if (session('success'))
        @include('partials._successToast', ['message' => session('success')])
    @endif
    {{-- {{ Breadcrumbs::render('dashboard') }} --}}
    @if (auth()->user()->hasRole('Admin'))
        @php
            $timelineData = [];
            foreach ($activeProject->timeline as $timeline) {
                $timelineData[] = [
                    'name' => $timeline->title,
                    'start_date' => $timeline->start_date,
                    'end_date' => $timeline->end_date,
                ];
            }
            //dd($timelineData);
        @endphp

        <div class="container-fluid">
            <div class="row mb-3">
                @include('partials._statCard', [
                    'color' => 'success',
                    'text' => 'Users',
                    'stats' => $totalUsers[0]->count,
                    'route' => route('user.index'),
                ])
                @include('partials._statCard', [
                    'color' => 'danger',
                    'text' => 'Projects',
                    'stats' => $totalProjects[0]->count,
                    'route' => route('project.index'),
                ])
                @include('partials._statCard', [
                    'color' => 'info',
                    'text' => 'Transactions',
                    'stats' => $totalTransactions[0]->count,
                    'route' => route('record.index'),
                ])
                @include('partials._statCard', [
                    'color' => 'warning',
                    'text' => 'Transactions',
                    'stats' => $totalTransactions[0]->count,
                    'route' => route('record.index'),
                ])
            </div>
            <div class="row">
                <div class="card w-50">
                    <div class="card-header d-flex">
                        <h5>Payable Receiveable Chart</h5>
                        <form id="payreceiveChart" class="ml-5">
                            <div class="form-group">
                                <label for="sdate">Start Date</label>
                                <input class="input-change" type="date" id="sdate" name="start_date"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="edate">End Date</label>
                                <input type="date" class="input-change" id="edate" name="end_date"
                                    class="form-control">
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="mh-25 p-4 border border-3 border-success">
                            <canvas id="myChart" width="600" height="350"></canvas>
                        </div>
                    </div>
                </div>
                <div class="card w-50">
                    <div class="card-header">
                        <h5>Active Project Timelines</h5>
                        <p>{{ $activeProject->title }}</p>
                    </div>
                    <div class="card-body">
                        <div id="timelineChart">

                        </div>
                    </div>
                </div>


            </div>

        </div>
    @else
        <h2 class="text-center">Projects For You</h2>
        <div class="row mb-3">
            @if (count(auth()->user()->projects) != 0)
                @foreach (auth()->user()->projects as $project)
                    @include('partials._statCard', [
                        'color' => 'success',
                        'text' => $project->title,
                        'stats' => '',
                        'route' => route('project.show', $project->id),
                    ])
                @endforeach
            @else
                <p>No projects assigned for you</p>
            @endif

        </div>
    @endif
@endsection
@push('other-scripts')
    <script>
        $(document).ready(function() {
            let phpData = @json($timelineData);
            let preparedDataForTimeline = [];
            phpData.forEach((arr) => {
                preparedDataForTimeline.push({
                    "x": arr.name,
                    "y": [new Date(arr.start_date).getTime(), new Date(arr.end_date).getTime()]
                })
            })
            var options = {
                series: [{
                    data: preparedDataForTimeline
                }],
                chart: {
                    height: 350,
                    type: 'rangeBar'
                },
                plotOptions: {
                    bar: {
                        horizontal: true
                    }
                },
                xaxis: {
                    type: 'datetime'
                }
            };
            var chart = new ApexCharts(document.querySelector("#timelineChart"), options);
            chart.render();

            $(document).on('change', '.input-change', function() {
                let form = $('#payreceiveChart');
                if ($('#sdate').val() != '' && $('#edate').val() != '') {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('dashboard.payreceivechart') }}",
                        data: form.serialize(),
                        success: function(response) {
                            let res = response.result;
                            let labels = res.map(entry => entry.month);
                            let receivablesAmounts = res.map(entry => parseInt(entry
                                .receiveable));
                            let payablesAmounts = res.map(entry => parseInt(entry.payable));
                            let ctx = document.getElementById('myChart');
                            let myChart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Receivables',
                                        data: receivablesAmounts,
                                        borderColor: 'blue',
                                        fill: false
                                    }, {
                                        label: 'Payables',
                                        data: payablesAmounts,
                                        borderColor: 'red',
                                        fill: false
                                    }]
                                },
                                options: {
                                    title: {
                                        display: true,
                                        text: 'Payable Receivable chart'
                                    },
                                    responsive: false,
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero: true
                                            }
                                        }]
                                    }
                                }
                            });
                        },

                        error: function(xhr, status, error) {
                            console.log(xhr)
                        }
                    })
                }

            })
        })
    </script>
@endpush

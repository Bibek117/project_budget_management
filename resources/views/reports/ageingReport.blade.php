@extends('layouts.dashboardLayout')
@section('content')
    {{ Breadcrumbs::render('report.ageingReportCreate') }}
    <div class="card border border-3 border-success  mb-2">
        <div class="card-header">
            <h3>Ageing Report</h3>
        </div>
        <div class="card-body">
            <form id="ageingReportForm">
                @csrf
                <div class="form-row">
                    <div class="form-group col-sm-4">
                        <label for="interval">Number of Intervals <span class="text-danger">*</span></label>
                        <input type="number" name="interval" class="form-control" id="interval" placeholder="i.e 4">
                        <p class="text-sm text-danger" id="intervalError"></p>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="perDays">Days per each Interval <span class="text-danger">*</span></label>
                        <input type="number" name="perDays" class="form-control" id="perDays" placeholder="i.e 10">
                        <p class="text-sm text-danger" id="perdaysError"></p>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="untilDate">End Date <span class="text-danger">*</span></label>
                        <input type="date" max={{ date('Y-m-d') }} class="form-control" id="untilDate" name="untilDate">
                        <p class="text-sm text-danger" id="dateError"></p>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Generate Report</button>
            </form>
        </div>
    </div>
    <div class="card  border border-3 border-success" style="display:none" id="ageingReportDisplay">
        <div class="card-header d-flex justify-between">
            <div>
                <h2>Report</h2>
            </div>
            <div>
                <button id="closeReportDisplay" class="text-danger"><i class="text-2xl bi bi-x-circle"></i></button>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="thead-dark" id="displayThead">

                </thead>
                <tbody id="displayTbody">

                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('other-scripts')
    <script>
        $(document).ready(function() {
            $('#ageingReportForm').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('report.ageingReport') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: form.serialize(),
                    success: function(response) {
                        form.trigger('reset');
                        $('#displayThead').empty();
                        $("#displayTbody").empty();
                        $('#ageingReportDisplay').fadeOut();
                        $("#ageingReportDisplay").fadeIn(2000);
                        if (response.result.length < 1) {
                            $('#displayTbody').append(
                                '<tr><td colspan="5">No records available</td></tr>');
                            return;
                        }
                        $.each(response.result, function(index, res) {
                            var keys = Object.keys(res);
                            if (index === 0) {
                                var headHtml = '<tr><th>#</th>'
                                keys.forEach(function(key) {
                                    headHtml += `<th>${key}</th>`;
                                });
                                headHtml += '</tr>'
                                $('#displayThead').append(headHtml);
                            }

                            var rowHtml = `<tr><td>${index+1}</td>`;
                            keys.forEach(function(key) {
                                if(key === 'total' && res[key] != 0){
                                    rowHtml += res[key] > 0 ? `<td class="text-success">${res[key]}</td>` : `<td class="text-danger">${res[key]}</td>`;
                                }else{
                                    rowHtml += `<td>${res[key]}</td>`;
                                }
                            });
                            rowHtml += '</tr>'
                            $('#displayTbody').append(rowHtml);
                        });
                    },

                    error: function(xhr, status, error) {
                        let errorArr = xhr.responseJSON.errors;
                        $('#intervalError').html(errorArr['interval'][0]);
                        $('#perdaysError').html(errorArr['perDays'][0]);
                        $('#dateError').html(errorArr['untilDate'][0]);
                    }
                })
            })

            $('#closeReportDisplay').click(function() {
                $("#ageingReportDisplay").fadeOut(2000);
            })
        })
    </script>
@endpush

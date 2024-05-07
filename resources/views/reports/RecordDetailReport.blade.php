@extends('layouts.dashboardLayout')
@section('content')
{{Breadcrumbs::render('report.recordDetailCreate')}}
    <div class="card">
        <div class="card-header">
            <p>Transaction Detail Report</p>
        </div>
        <div class="card-body">
            <form id="record-detail-form" class="mb-5">
                @csrf
                <div class="d-flex">
                    <div class="form-group " style="margin-right: 50px">
                        <label for="contacttype">Contact Types</label><br>
                        <select class="contacttype" id="contacttype" name="contacttype_id">
                            <option disabled selected>Contact types</option>
                            @forelse ($contacttypes as $contacttype)
                                <option value="{{ $contacttype->id }}">{{ $contacttype->name }}</option>
                            @empty
                                <option disabled>No contacts available</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="coa">Chart of Account</label><br>
                        <select name="coa" id="coa">
                            <option value="" selected disabled>Chart of Account</option>
                            @forelse ($coaCategory as $coaCat)
                                <option value="{{$coaCat->id}}">{{$coaCat->name}}</option>
                            @empty
                                
                            @endforelse
                        </select>
                    </div>
                </div>

                <button class="btn btn-info" id="generate-report">Generate Report</button>
            </form>

            <table class="table d-none" id="detailReport" >
                <thead class="thead-light">
                    <th>#</th>
                    <th>Record Code</th>
                    <th>Contact</th>
                    <th>Chart of Account</th>
                     <th>Debit</th>
                     <th>Credit</th>
                     <th>Balance</th>
                </thead>
                <tbody id="tbody">

                </tbody>
            </table>
            @include('partials.goback')
        </div>
    </div>
@endsection

@push('other-scripts')
    <script>
        $(document).ready(function() {
            // $('#detailReport').DataTable();
            $('#generate-report').click(function(e) {
                e.preventDefault();
                $('#tbody').empty();
                $('#detailReport').addClass('d-none');
                let form = $('#record-detail-form');

                $.ajax({
                    type: "POST",
                    url: "{{route('report.recordDetailShow')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                     data : form.serialize(),
                    success: function(response) {
                        console.log(response.validate)
                        if(response.result.length < 1){
                             $('#detailReport').removeClass('d-none');
                             $('#tbody').append('<tr><td colspan="5">No records available</td></tr>');
                        }
                        $.each(response.result,function(index,res){
                            $('#detailReport').removeClass('d-none');
                            $('#tbody').append(`<tr>
                                 <td>${index+1}</td>
                                    <td>${res.code}</td>
                                     <td>${res.Contact}</td>
                                     <td>${res.ChartOfAcc}</td>
                                     <td>${res.debit == null?"-":res.debit  }</td>
                                     <td>${res.credit == null?"-":res.credit  }</td>
                                     <td>${res.balance}</td>
                                </tr>`);
                        })
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr);
                    }
                })
            })
        })
    </script>
@endpush

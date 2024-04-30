@extends('layouts.dashboardLayout')
@section('content')
    <div class="card">
        <div class="card-header">
            <p>Contact Payable/Receivable Report</p>
        </div>
        <div class="card-body">
            <form action="contact-payable-receivable-form">
                @csrf
                <div class="form-group">
                    <select class="contacttype" id="contacttype" name="contact_id">
                        <option disabled selected>Contact types</option>
                        @foreach ($contacttypes as $contacttype)
                            <optgroup label="{{ $contacttype->name }}">
                                @forelse ($contacttype->contact as $contact)
                                    <option value="{{ $contact->id }}">{{ $contact->user->username }}</option>
                                @empty
                                    <option disabled>No contacts available</option>
                                @endforelse
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <select name="chartOfAccount" id="coa">
                        <option value="" selected disabled>Chart of Account</option>
                        <option value="receive">Receivable</option>
                        <option value="payable">Payable</option>
                        <option value="bc">Bank/Cash</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>
                <button class="btn btn-info" id="generate-report">Generate</button>
            </form>
            @include('partials.goback')
        </div>
    </div>
@endsection
@push('other-scripts')
    <script>
        $(document).ready(function() {
            $('#generate-report').click(function() {
                let form = $('#contact-payable-receivable-form');

                $.ajax({
                    url: `reports/contactPayableReceivable`,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    // data : form.serialize(),
                    success: function(response) {
                        console.log(response)
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr);
                    }
                })
            })
        })
    </script>
@endpush


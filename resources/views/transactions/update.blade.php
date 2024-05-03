@extends('layouts.dashboardLayout')
@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Edit Record</h5>
        </div>
        {{Breadcrumbs::render('record.edit',$record)}}
        <div class="card-body">
            <form action="{{ route('record.update', $record->id) }}" method="post" id="update_form">
                @csrf
                @method('PUT')

                <div class="card mb-4">
                    <div class="card-body d-flex" style="overflow:auto;">
                        <div class="form-group">
                            <div class="card">
                                <div class="card-body">
                                    <label for="project">Selected Project</label><br>
                                    <p>{{ $record->project->title }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="ml-4 form-group">
                            <label for="timeline">Select a timeline</label><br>
                            <select id="timeline" class="form-control @error('timeline_id') is-invalid @enderror"
                                name="timeline_id">
                                <option value="" selected disabled>Timelines</option>
                                @forelse ($record->project->timeline as $timeline)
                                    <option value="{{ $timeline->id }}"
                                        {{ $timeline->id == old('timeline_id', $selectedTimeline->id) ? 'selected' : '' }}>
                                        {{ $timeline->title }}</option>
                                @empty
                                    <option value="" disabled selected>No timeline found</option>
                                @endforelse
                            </select>
                            @error('timeline_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="ml-4 form-group" id="exe_date">
                            <label for="execution_date">Transactions performed date</label><br>
                            <input type="date" class="form-control @error('execution_date') is-invalid @enderror"
                                value="{{ old('execution_date', $record->execution_date) }}"
                                min="{{ $selectedTimeline->start_date }}" max="{{ $selectedTimeline->end_date }}"
                                name="execution_date" id="execution_date">
                            @error('execution_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="ml-4 form-group" id="r_code">

                            <input type="hidden" value="" name="code" id="hidden_code">
                            <label for="code">Record Code</label><br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">rc-</span>
                                </div>
                                <input type="text" class="form-control @error('code') is-invalid @enderror"
                                    id="code" value="{{ explode('-', $record->code)[1] }}">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                <table class="table table-sm" id="edit-table">
                    <tr>
                        <th>#</th>
                        <th>Chart of Account</th>
                        <th>Contact Type</th>
                        <th>Budget Head</th>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                    @php
                        $totalTransaction = count($transactionsInRecord);
                    @endphp
                    @foreach ($transactionsInRecord as $transaction)
                        <input type="hidden" name="transactions[{{ $loop->iteration - 1 }}][id]"
                            value="{{ $transaction->id }}">
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>

                                <select name="transactions[{{ $loop->iteration - 1 }}][coa_id]">
                                    @foreach ($coaCategory as $coacat)
                                        {
                                        <optgroup label="{{ $coacat->name }}">
                                            @forelse ($coacat->accountsubcat as $subcat)
                                                <option value="{{ $subcat->id }}"
                                                    {{ $subcat->id == $transaction->id ? 'selected' : '' }}>
                                                    {{ $subcat->name }}</option>
                                            @empty
                                                <option value="" selected disabled>No chart of accounts</option>
                                            @endforelse
                                        </optgroup>
                                        }
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="contacttype"
                                        name="transactions[{{ $loop->iteration - 1 }}][contact_id]">
                                        <option value="" disabled selected>Contact types</option>
                                        @foreach ($contacttypes as $contacttype)
                                            <optgroup label="{{ $contacttype->name }}">
                                                @forelse ($contacttype->contact as $contact)
                                                    <option value="{{ $contact->id }}"
                                                        {{ $contact->id == old('transactions.' . ($loop->parent->parent->iteration - 1) . '.contact_id', $transaction->contact_id) ? 'selected' : '' }}>
                                                        {{ $contact->user->username }}
                                                    </option>
                                                @empty
                                                    <option disabled>No contacts available</option>
                                                @endforelse
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </td>

                            <td>
                                <div class="form-group">

                                    <select name="transactions[{{ $loop->iteration - 1 }}][budget_id]"
                                        class="budget_dropdown">
                                        @if ($transaction->budget_id == null)
                                            <option value="" selected disabled>No budget selected</option>
                                            @forelse ($selectedTimeline->budget as $budget)
                                                <option value="{{ $budget->id }}">
                                                    {{ $budget->name }}</option>
                                            @empty
                                                <option value="" disabled selected>No option available</option>
                                            @endforelse
                                        @else
                                            @forelse ($selectedTimeline->budget as $budget)
                                                <option value="{{ $budget->id }}"
                                                    {{ $budget->id == old('transactions.' . $loop->parent->iteration - 1 . '.budget_id', $transaction->budget_id) ? 'selected' : '' }}>
                                                    {{ $budget->name }}
                                                </option>
                                            @empty
                                                <option value="" disabled selected>No option available</option>
                                            @endforelse
                                        @endif
                                    </select>
                                </div>
                            </td>

                            <td>
                                <textarea
                                    class="form-control {{ $errors->has('transactions.' . ($loop->iteration - 1) . '.desc') ? 'is-invalid' : '' }}"
                                    name="transactions[{{ $loop->iteration - 1 }}][desc]" rows="2">{{ old('transactions.' . ($loop->iteration - 1) . '.desc', $transaction->desc) }} </textarea>
                                @if ($errors->has('transactions.' . ($loop->iteration - 1) . '.desc'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('transactions.' . ($loop->iteration - 1) . '.desc') }}</div>
                                @endif
                            </td>
                            <td>
                                <input type="number" step="0.0001"
                                    class="transaction_amount form-control {{ $errors->has('transactions.' . ($loop->iteration - 1) . '.amount') ? 'is-invalid' : '' }}"
                                    name="transactions[{{ $loop->iteration - 1 }}][amount]"
                                    value="{{ old('transactions.' . ($loop->iteration - 1) . '.amount', $transaction->amount) }}">
                                @if ($errors->has('transactions.' . ($loop->iteration - 1) . '.amount'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('transactions.' . ($loop->iteration - 1) . '.amount') }}</div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
                <button id="add-more" class="btn btn-danger">Add More + </button>
                <button class="btn btn-success">Save edits</button>
                @include('partials.goback')
            </form>
        </div>
    </div>
    @push('other-scripts')
        <script>
            $(document).ready(function() {
                $('#timeline').change(function() {
                    let timelineId = $(this).val();
                    $.ajax({
                        url: `/timelines/ajaxSingleTimeline/${timelineId}`,
                        type: 'GET',
                        success: function(response) {
                            $('.budget_dropdown').empty();
                            $('.budget_dropdown').append(
                                '<option value="" selected disabled>Budgets</option>');
                            if (response.timeline.budget.length > 0) {
                                $.each(response.timeline.budget, function(index, budget) {
                                    $('.budget_dropdown').append('<option value="' + budget
                                        .id + '">' + budget.name + '</option>');
                                });
                            } else {
                                $('#budget').append(
                                    '<option value="">No budget heads available</option>');
                            }
                        }
                    });
                })



                $('#timeline').change(function() {
                    let timelineId = $(this).val();
                    $.ajax({
                        url: `/timelines/ajaxSingleTimeline/${timelineId}`,
                        type: 'GET',
                        success: function(response) {
                            $('.budget_dropdown').empty();
                            $('.budget_dropdown').append(
                                '<option value="" disabled selected>Budgets</option>');
                            $('#execution_date').val('');
                            $('#execution_date').attr('min', response.timeline.start_date);
                            $('#execution_date').attr('max', response.timeline.end_date);
                            if (response.timeline.budget.length > 0) {
                                $.each(response.timeline.budget, function(index, budget) {
                                    $('.budget_dropdown').append('<option value="' + budget
                                        .id + '">' + budget.name + '</option>');
                                });
                                budgets = response;
                            } else {
                                $('#budget').append(
                                    '<option value="">No budget heads available</option>');
                            }
                        },
                    })
                })

                $('#update_form').on('submit', function(e) {
                    e.preventDefault();
                    let codeValue = $('#code').val();
                    if (codeValue.trim() === "") {
                        $('#hidden_code').val("");
                    } else {
                        $('#hidden_code').val(`rc-${codeValue}`);
                    }
                    this.submit();
                });

                var num = @json($totalTransaction);
                var editIndex = num - 1;
                $('#add-more').click(function(e) {
                    e.preventDefault();
                    editIndex++;
                    let editHtml = `<tr id="transactionEdit_${editIndex}" >
                            <td>${editIndex + 1}</td>
                            <td>

                                <select name="transactions[${editIndex}][coa_id]">
                                    @foreach ($coaCategory as $singleCoa)
                                   <optgroup label="{{$singleCoa->name}}">
                                    @foreach ($singleCoa->accountsubcat as $subcat)
                                        <option value={{$subcat->id}}>{{$subcat->name}}</option>
                                    @endforeach
                                    </optgroup>
                               @endforeach
                                </select>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="contacttype"
                                        name="transactions[${editIndex}][contact_id]">
                                        <option value="" disabled selected>Contact types</option>
                                        @foreach ($contacttypes as $contacttype)
                                            <optgroup label="{{ $contacttype->name }}">
                                                @forelse ($contacttype->contact as $contact)
                                                    <option value="{{ $contact->id }}"
                                                      >
                                                        {{ $contact->user->username }}
                                                    </option>
                                                @empty
                                                    <option disabled>No contacts available</option>
                                                @endforelse
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </td>

                            <td>
                                <div class="form-group">

                                    <select name="transactions[${editIndex}][budget_id]"
                                        class="budget_dropdown">
                                        @if ($transaction->budget_id == null)
                                            <option value="" selected disabled>No budget selected</option>
                                            @forelse ($selectedTimeline->budget as $budget)
                                                <option value="{{ $budget->id }}" >
                                                    {{ $budget->name }}</option>
                                            @empty
                                                <option value="" disabled selected>No option available</option>
                                            @endforelse
                                        @else
                                            @forelse ($selectedTimeline->budget as $budget)
                                                <option value="{{ $budget->id }}"
                                                  >
                                                    {{ $budget->name }}
                                                </option>
                                            @empty
                                                <option value="" disabled selected>No option available</option>
                                            @endforelse
                                        @endif
                                    </select>
                                </div>
                            </td>

                            <td>
                                <textarea class="form-control"
                                    name="transactions[${editIndex}][desc]" rows="2"> </textarea>
                                
                            </td>
                            <td>
                                <input type="number" step="0.0001"
                                    class="transaction_amount form-control "
                                    name="transactions[${editIndex}][amount]"
                                    >
                            </td>
                        </tr>`;

                    $('#edit-table').append(editHtml);
                })

            });
        </script>
    @endpush
@endsection

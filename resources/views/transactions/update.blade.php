@extends('layouts.dashboardLayout')
@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Edit Record</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('transaction.update') }}" method="post">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-body d-flex">
                        <div class="form-group">
                            <label for="project">Selected Project</label><br>
                            <p>{{ $record->project->title }}</p>
                        </div>

                        <div class="ml-4 form-group">
                            <label for="timeline">Select a timeline</label><br>
                            <select id="timeline" name="timeline_id">
                                <option value="" disabled selected>Timelines</option>
                                @forelse ($record->project->timeline as $timeline)
                                    <option value="{{ $timeline->id }}">{{ $timeline->title }}</option>
                                @empty
                                    <option value="" disabled selected>No timeline found</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>Chart of Account</th>
                        <th>Contact Type</th>
                        <th>Budget Head</th>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                    @foreach ($transactionsInRecord as $transaction)
                        <input type="hidden" name="transactions[{{ $loop->iteration - 1 }}][id]"
                            value="{{ $transaction->id }}">
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>

                                <select name="transactions[{{ $loop->iteration - 1 }}][COA]">
                                    <option value="{{ $transaction->COA }}" selected>{{ $transaction->COA }}</option>
                                    <option value="Utilities">Utilities</option>
                                    <option value="Supplies">Supplies</option>
                                    <option value="Travel">Travel</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Investments">Investments</option>
                                    <option value="Accounts Payable">Accounts Payable</option>
                                    <option value="Loans">Loans</option>
                                    <option value="Retained Earnings">Retained Earnings</option>
                                    <option value="Donor Restricted">Donor Restricted</option>
                                    <option value="Board Designated">Board Designated</option>
                                    <option value="General">General</option>
                                    <option value="Program Income">Program Income</option>
                                    <option value="Other Income">Other Income</option>
                                    <option value="Program Expenses">Program Expenses</option>
                                    <option value="Administrative Expenses">Administrative Expenses</option>

                                </select>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="contacttype"
                                        name="transactions[{{ $loop->iteration - 1 }}][contact_id]">
                                        <option disabled selected>Contact types</option>
                                        @foreach ($contacttypes as $contacttype)
                                            <optgroup label="{{ $contacttype->name }}">
                                                @forelse ($contacttype->contact as $contact)
                                                    <option value="{{ $contact->id }}">{{ $contact->user->username }}
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
                                    <select name="" id="" class="budget_dropdown">
                                       
                                    </select>
                                </div>
                            </td>

                            <td>
                                <textarea name="transactions[{{ $loop->iteration - 1 }}][desc]" rows="2">{{ $transaction->desc }}</textarea>
                            </td>
                            <td>
                                <input type="number" name="transactions[{{ $loop->iteration - 1 }}][amount]"
                                    value="{{ $transaction->amount }}">
                            </td>
                        </tr>
                    @endforeach



                </table>
                <button class="btn btn-success">Save edits</button>
                @include('partials.goback')
            </form>
        </div>
    </div>
    @push('other-scripts')
    <script>
        $(document).ready(function(){
            $('#timeline').change(function(){
                let timelineId = $(this).val();
                $.ajax({
                   url : `/timelines/ajaxSingleTimeline/${timelineId}`,
                   type: 'GET',
                   success : function(response){
                    $('.budget_dropdown').empty();
                    $('.budget_dropdown').append('<option value="" selected disabled>Budgets</option>');
                    if(response.timeline.budget.length > 0){
                        $.each(response.timeline.budget,function(index,budget){
                            $('.budget_dropdown').append('<option value="'+budget.id + '">'+budget.name+'</option>');
                        });
                    }else {
                                $('#budget').append(
                                    '<option value="">No budget heads available</option>');
                            }
                   }
                });
            })
        });
    </script>
        
    @endpush
@endsection

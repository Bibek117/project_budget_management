@extends('layouts.dashboardLayout')
@section('content')
    {{-- @php
        dd($projects->timeline[0]->budgets);
    @endphp --}}
    <h3 class="text-center">Create record</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('record.store') }}" method="POST">
        @csrf
        <div class="card mb-4">
            <div class="d-flex card-body">
                <div class="form-group">
                    <label for="project">Choose a project</label><br>
                    <select id="project" name="project_id">
                        <option value="" disabled selected>projects</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->title }}</option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p class="text-danger text-small">{{ $message }}</p>
                    @enderror
                </div>

                <div class="ml-4 form-group">
                    <label for="timeline">Select a timeline</label><br>
                    <select id="timeline" name="timeline">
                        <option value="" disabled selected>Timelines</option>
                    </select>
                </div>
                <div class="ml-4 form-group" id="r_code">
                    <input type="hidden" value="" name="code" id="hidden_code">
                    <label for="code">Record Code</label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">rc-</span>
                        </div>
                        <input type="text" id="code" placeholder="i.e 001">
                    </div>
                </div>
                <div class="ml-4 form-group d-none" id="exe_date">
                    <label for="execution_date">Transactions performed date</label><br>
                    <input type="date" name="execution_date" id="execution_date">
                </div>


            </div>

        </div>

        {{-- form --}}
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th scope="col">Charts of Accounts</th>
                    <th scope="col">Contact Type</th>
                    <th scope="col">Budget Head</th>
                    <th scope="col">Description</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody id="table_body">
                <tr>
                    <td>
                        <div class="form-group">
                            <select name="transactions[0][COA]" id="coa_0">
                                <option value="" disabled selected>Charts of Accounts</option>
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

                        </div>
                    </td>
                    <td>
                        {{-- <div class="form-group">
                            <select class="contacttype" id="contacttype_0" name="transactions[0][contacttype_id]">
                                <option value="" disabled selected>Contact types</option>
                                @foreach ($contacttypes as $contacttype)
                                    <option value="{{ $contacttype->id }}" disabled selected>{{ $contacttype->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        {{-- @php
                            dd($contacttypes[0]->contact[0]->user->username)
                        @endphp --}}
                        {{-- @foreach ($contacttypes as $contacttype)
                         <p>{{$contacttype->name}}</p>
                                     @foreach ($contacttype->contact as $contact)
                                      
                                             <p>{{$contact->user->username}}</p>
                                         
                                     @endforeach
                                @endforeach --}}
                        <div class="form-group">
                            <select class="contacttype" id="contacttype_0" name="transactions[0][contact_id]">
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

                    </td>
                    <td>
                        <div class="ml-4 form-group">
                            <select name="transactions[0][budget_id]" id="budge_0" class="budget_dropdown">
                                <option value="" disabled selected>Budgets</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <textarea name="transactions[0][desc]" id="description_0" rows="2" class="form-control"></textarea>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="number" class="form-control net_amount" step="0.0001"
                                name="transactions[0][amount]" id="amount_0">
                        </div>
                    </td>
                    <td>
                        <p class="text-danger">*Required</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <select name="transactions[1][COA]" id="coa_1">
                                <option value="" disabled selected>Charts of Accounts</option>
                                <option value="Expenses">Utilities</option>
                                <option value="Expenses">Supplies</option>
                                <option value="Expenses">Travel</option>
                                <option value="Assets">Cash</option>
                                <option value="Assets">Investments</option>
                                <option value="Liabilities">Accounts Payable</option>
                                <option value="Liabilities">Loans</option>
                                <option value="Equity">Retained Earnings</option>
                                <option value="Equity">Donor Restricted</option>
                                <option value="Equity">Board Designated</option>
                                <option value="Equity">General</option>
                                <option value="Income">Program Income</option>
                                <option value="Income">Other Income</option>
                                <option value="Expenses">Program Expenses</option>
                                <option value="Expenses">Administrative Expenses</option>

                            </select>

                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <select class="contacttype" id="contacttype_1" name="transactions[1][contact_id]">
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
                    </td>
                    <td>
                        <div class="ml-4 form-group">
                            <select name="transactions[1][budget_id]" id="budget_1" class="budget_dropdown">
                                <option value="" disabled selected>Budgets</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <textarea name="transactions[1][desc]" id="description_1" rows="2" class="form-control"></textarea>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="number" class="form-control net_amount" name="transactions[1][amount]"
                                id="amount_1" step="0.0001">
                        </div>
                    </td>
                    <td>
                        <p class="text-danger">*Required</p>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="2">Net Total : <span id="display_net">0</span></td>
                </tr>

            </tfoot>
        </table>
        <button id="add-more" class="btn btn-success">Add more + </button>
        <button type="submit" class="btn btn-primary">Submit</button>
        <button class="btn btn-secondary"><a class="text-white" href="{{ url()->previous() }}">Go back</a></button>
    </form>
    @push('other-scripts')
        <script>
            var budgets = [];
            $(document).ready(function() {
                //ajax fetch project timelines
                $('#project').change(function() {
                    var projectId = $(this).val();
                    $.ajax({
                        url: `/projects/ajaxSingleProject/${projectId}`,
                        type: 'GET',
                        success: function(response) {
                            $('#timeline').empty();
                            $('#budget').empty();
                            $('#budget').append('<option value="">Budgets</option>');
                            if (response.project.timeline.length > 0) {
                                $('#timeline').append(
                                    '<option value="" disabled selected>Timelines</option>');
                                $.each(response.project.timeline, function(index, timeline) {
                                    $('#timeline').append('<option value="' + timeline.id +
                                        '">' + timeline.title + '</option>');
                                });
                            } else {
                                $('#timeline').append(
                                    '<option value="">No timelines available</option>');
                            }
                        },
                    });
                });


                //ajax fetch project-timeline->budget heads

                $('#timeline').change(function() {
                    let timelineId = $(this).val();
                    $.ajax({
                        url: `/timelines/ajaxSingleTimeline/${timelineId}`,
                        type: 'GET',
                        success: function(response) {
                            $('.budget_dropdown').empty();
                            $('.budget_dropdown').append(
                                '<option value="" disabled selected>Budgets</option>');
                            $('#exe_date').removeClass('d-none');
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
                //ajax fetch for users 
                $(document).on('change', '.contacttype', function() {
                    let contacttypeId = $(this).val();
                    let usersDropdown = $(this).closest('tr').find('.users');
                    $.ajax({
                        url: `/transactions/ajaxUsers/${contacttypeId}`,
                        type: 'GET',
                        success: function(response) {
                            usersDropdown.empty();
                            usersDropdown.append(
                                '<option value="" disabled selected>Users</option>');
                            if (response.users.length > 0) {
                                $.each(response.users, function(index, user) {
                                    usersDropdown.append('<option value="' + user.id +
                                        '">' + user.username + '</option>');
                                });
                            } else {
                                usersDropdown.append('<option value="">No user available</option>');
                            }
                        }
                    });
                });



                //add more field
                var index = 1;
                $('#add-more').click(function(e) {
                    e.preventDefault();
                    index++;
                    let transactionHtml = `<tr id="transaction_${index}">
                          <td>
                        <div class="form-group">
                            <select name="transactions[${index}][COA]" id="coa_${index}">
                                <option value="" disabled selected>Charts of Accounts</option>
                                <option value="Expenses">Utilities</option>
                                <option value="Expenses">Supplies</option>
                                <option value="Expenses">Travel</option>
                                <option value="Assets">Cash</option>
                                <option value="Assets">Investments</option>
                                <option value="Liabilities">Accounts Payable</option>
                                <option value="Liabilities">Loans</option>
                                <option value="Equity">Retained Earnings</option>
                                <option value="Equity">Donor Restricted</option>
                                <option value="Equity">Board Designated</option>
                                <option value="Equity">General</option>
                                <option value="Income">Program Income</option>
                                <option value="Income">Other Income</option>
                                <option value="Expenses">Program Expenses</option>
                                <option value="Expenses">Administrative Expenses</option>

                            </select>

                        </div>
                    </td>
                    <td>
                    <div class="form-group">
                            <select class="contacttype" id="contacttype_${index}" name="transactions[${index}][contact_id]">
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
                    </td>
                    <td>
                        <div class="ml-4 form-group">
                            <select name="transactions[${index}][budget_id]" id="budget_${index}" class="budget_dropdown">
                                <option value="" disabled selected>Budgets</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <textarea name="transactions[${index}][desc]" id="description-${index}" rows="2" class="form-control"></textarea>
                        </div>
                    </td>
                     <td>
                        <div class="form-group">
                            <input type="number" class="form-control net_amount" name="transactions[${index}][amount]" id="amount_${index}"
                                placeholder="" step="0.0001" >
                        </div>
                    </td>
                     <td>  <button class="btn btn-danger remove-budget-btn" data-transaction-index="${index}"><i class="bi bi-trash3"></i></button></td>
                </tr>`;
                    $('#table_body').append(transactionHtml);
                    let budgetDropdown = $('#transaction_' + index).find('.budget_dropdown');
                    if (budgets.timeline.budget.length > 0) {
                        $.each(budgets.timeline.budget, function(index, budget) {
                            budgetDropdown.append('<option value="' + budget.id +
                                '">' + budget.name + '</option>');
                        });
                    } else {
                        budgetDropdown.append(
                            '<option value="">No budget heads available</option>');
                    }
                });

                // Remove budget card when trash button is clicked
                $(document).on('click', '.remove-budget-btn', function() {
                    var indexToRemove = $(this).data('transaction-index');
                    $('#transaction_' + indexToRemove).remove();
                });




                $('#table_body').on('input', '.net_amount', function() {
                    calculateNetAmount();
                });

                function calculateNetAmount() {
                    let total = 0;
                    $('.net_amount').each(function() {
                        let value = parseFloat($(this).val()) || 0;
                        total += value;
                    });
                    $('#display_net').text(total);
                }

                $('#code').on('input', function() {
                    $('#hidden_code').val(`rc-${$(this).val()}`)
                })

            });
        </script>
    @endpush
@endsection

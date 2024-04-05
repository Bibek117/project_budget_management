@extends('layouts.dashboardLayout')
@section('content')
    {{-- @php
        dd($projects->timeline[0]->budgets);
    @endphp --}}
    <h3 class="text-center">Create budget</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('budget.store') }}" method="post">
        @csrf

        <div class="card mb-4">
            <div class="d-flex card-body">
                <div class="form-group">
                    <label for="project">Choose a project</label><br>
                    <select id="project">
                        <option value="" disabled selected>projects</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="ml-4 form-group">
                    <label for="timeline">Select a timeline</label><br>
                    <select name="timeline_id" id="timeline">
                        <option value="">Select a timeline</option>
                    </select>
                </div>
            </div>

        </div>
        <div id="formWrapper">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Budget title</label>
                        <input type="text" class="form-control" name="budgets[0][name]" placeholder="Enter title">
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" name="budgets[0][amount]" min="1000"
                            placeholder="Enter amount">
                    </div>
                </div>
            </div>
        </div>
        <button type="button" id="add-budget-btn" class="btn btn-success">Add More Field</button>
        <button type="submit" class="btn btn-primary">Submit</button>
        <button class="btn btn-secondary"><a class="text-white" href="{{ url()->previous() }}">Go back</a></button>
    </form>
    @push('other-scripts')
        <script>
            $(document).ready(function() {
                //ajax fetch project timelines
                $('#project').change(function() {
                    var projectId = $(this).val();
                    $.ajax({
                        url: `/projects/ajaxSingleProject/${projectId}`,
                        type: 'GET',
                        success: function(response) {
                            $('#timeline').empty();
                            if (response.project.timeline.length > 0) {
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

                //add multiple field
                var budgetIndex = 0;

                $('#add-budget-btn').click(function() {
                    budgetIndex++;
                    var budgetHtml = `
            <div class="card mb-4" id="budget_${budgetIndex}">
                <div class=" justify-content-around card-body">
                    <div class="form-group">
                        <label for="title_${budgetIndex}">Budget title</label>
                        <input type="text" class="form-control" id="title_${budgetIndex}" name="budgets[${budgetIndex}][name]" placeholder="Enter title">
                    </div>
                    <div class="form-group">
                        <label for="amount_${budgetIndex}">Amount</label>
                        <input type="number" class="form-control" id="amount_${budgetIndex}" min="1000" name="budgets[${budgetIndex}][amount]" placeholder="Enter amount">
                    </div>
                    <div>
                        <button class="btn btn-danger remove-budget-btn" data-budget-index="${budgetIndex}"><i class="bi bi-trash3"></i></button>
                    </div>
                </div>
            </div>
        `;
                    $('#formWrapper').append(budgetHtml);
                });

                // Remove budget card when trash button is clicked
                $(document).on('click', '.remove-budget-btn', function() {
                    var budgetIndexToRemove = $(this).data('budget-index');
                    $('#budget_' + budgetIndexToRemove).remove();
                });

            });
        </script>
    @endpush
@endsection

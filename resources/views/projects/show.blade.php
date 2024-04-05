@extends('layouts.dashboardLayout')
@section('content')
    {{-- @php
    dd($project->timeline[0]->budget)
@endphp --}}



    <div class="card mb-3">
        <div class="card-body">
            <h5 class="text-center">Project Detail</h5>
            <p> Project Title : {{ $project->title }}</p>
            <p>Project Desc : {{ $project->desc }}</p>
            <p> Project Start Date : {{ $project->start_date }}</p>
            <p> Project End Date : {{ $project->end_date }}</p>
            <button id="create-timeline" class="btn btn-success">Create Timeline</button>
        </div>
    </div>

    {{-- form --}}
    <div id="create-timeline-form" class="card d-none bg-light">
         <div class="card-header">
              <h5 class="inline-block">Create Timeline</h5>
                <button type="button" class="close text-danger" id="close-timeline" >
                  <i class="bi bi-x-circle "></i>
                </button>
            </div>
        <div class="card-body">
            <form action="{{ route('timeline.store') }}" method="post">
                @csrf
            <table class="table">
                <thead>
                    <th>Title</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Action</th>
                </thead>
                <tbody id="table-body">
                    <tr>
                        <td> <div class="form-group">
                    <input type="text" class="form-control" id="title" name="timelines[0][title]" placeholder="Enter title"
                        value="{{ old('title') }}">
                </div></td>
                <td>
                     <div class="form-group">
                    <input type="date" min={{ date('Y-m-d') }} id="date" name="timelines[0][start_date]" class="form-control">
                </div>
                </td>
                <td>
                    <div class="form-group">
                    <input type="date" min={{ date('Y-m-d') }} id="date" name="timelines[0][end_date]" class="form-control">
                </div>
                </td>
                <td>
                    <p class="text-danger">*required</p>
                </td>
                    </tr>
                </tbody>
            </table>
            <button class="btn btn-primary">Create</button>
            <button id="add-more-timeline" class="btn btn-success">Add more + </button>
          </form>
        </div>
    </div>


    @if (session('success'))
        <p class="text-success">{{ session('success') }}</p>
    @endif
    <h5 class="text-center m-4">Timelines</h5>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Timeline Title</th>
                <th scope="col">Timeline Start Date</th>
                <th scope="col">Timeline End Date</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($project->timeline as $timeline)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $timeline->title }}</td>
                    <td>{{ $timeline->start_date }}</td>
                    <td>{{ $timeline->end_date }}</td>
                    <td>
                        <form action={{ route('timeline.destroy', $timeline->id) }} method="post">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="font-medium text-green-600 hover:underline show-timeline"
                                data-id="{{ $timeline->id }}">Show Budgets</button>
                            | <a href={{ route('timeline.edit', $timeline->id) }}
                                class="font-medium text-blue-600  hover:underline">Edit</a>
                            | <button type="submit" class="font-medium text-red-600  hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No timeline found</td>
                </tr>
            @endforelse
        </tbody>
    </table>


    <div id="display-timeline" class="d-none">
        <div class="card">
            <div class="card-body">
                @forelse ($project->timeline as $timeline)
                    <ul class="budgetList d-none" id="budgetList-{{ $timeline->id }}">
                        <li>
                            <h4 class="text-center">Timeline title : {{ $timeline->title }}</h4>
                        </li>
                        <li>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Budget Head</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($timeline->budget as $singleBudget)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $singleBudget->name }}</td>
                                            <td>Rs {{ $singleBudget->amount }}</td>
                                            <td>
                                                <form action={{ route('budget.destroy', $singleBudget->id) }}
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                                    <a href={{ route('budget.edit', $singleBudget->id) }}
                                                        class="font-medium text-blue-600  hover:underline">Edit</a>
                                                    | <button type="submit"
                                                        class="font-medium text-red-600  hover:underline">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">No Budget found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </li>
                    </ul>
                @empty
                @endforelse
            </div>
        </div>

    </div>
    @include('partials.goback')
    @push('other-scripts')
        <script>
            $(document).ready(function() {
                $('.show-timeline').click(function() {
                    var timelineId = $(this).data('id');
                    console.log(timelineId)
                    // Show the budget list for the selected timeline
                    $('.budgetList').addClass('d-none');
                    $('#display-timeline').removeClass('d-none');
                    $('#budgetList-' + timelineId).removeClass('d-none');
                });

                //show create timeline form
                $('#create-timeline').click(function() {
                    $(this).addClass('d-none');
                    $('#create-timeline-form').removeClass("d-none");
                })

                //close create timeline form
                $('#close-timeline').click(function(){
                    $('#create-timeline-form').addClass("d-none");
                    $('#create-timeline').removeClass('d-none');
                })

                //add more fileds
                var index = 0;
                $('#add-more-timeline').click(function(e){
                    e.preventDefault();
                    let formHtml = ` <tr id="timeline_${index}">
                        <td> <div class="form-group">
                    <input type="text" class="form-control" id="title" name="timelines[${index}][title]" placeholder="Enter title"
                        value="{{ old('title') }}">
                </div></td>
                <td>
                     <div class="form-group">
                    <input type="date" min={{ date('Y-m-d') }} id="date" name="timelines[${index}][start_date]" class="form-control">
                </div>
                </td>
                <td>
                    <div class="form-group">
                    <input type="date" min={{ date('Y-m-d') }} id="date" name="timelines[${index}][end_date]" class="form-control">
                </div>
                </td>
                <td>
                     <button class="btn btn-danger remove-timeline-btn" data-timeline-index="${index}"><i class="bi bi-trash3"></i></button>
                </td>
                    </tr>`;
                    $('#table-body').append(formHtml);
                })


                //remove filed trash
                $(document).on('click','.remove-timeline-btn',function(e){
                    e.preventDefault();
                    let indexToRemove = $(this).data('timeline-index');
                    $('#timeline_'+ indexToRemove).remove();
                })
            });
        </script>
    @endpush

@endsection

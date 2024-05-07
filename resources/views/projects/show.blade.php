@extends('layouts.dashboardLayout')
@section('content')
    {{Breadcrumbs::render('project.show',$project)}}
    @if (session('success'))
       @include('partials._successToast',['message'=>session('success')])
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="text-center">Project Detail</h5>
            <p> Project Title : {{ $project->title }}</p>
            <p>Project Desc : {{ $project->desc }}</p>
            <p> Project Start Date : {{ $project->start_date }}</p>
            <p> Project End Date : {{ $project->end_date }}</p>
            @can('create-timeline')
                  <button id="create-timeline" class="btn btn-success">Create Timeline</button>
            @endcan         
        </div>
    </div>

    {{-- form for timeline --}}
    @can('create-timeline')
          <div id="create-timeline-form" class="card d-none bg-light">
        <div class="card-header">
            <h5 class="inline-block">Create Timeline</h5>
            <button type="button" class="close text-danger" id="close-timeline">
                <i class="bi bi-x-circle "></i>
            </button>
        </div>
        <div class="card-body">
            <div id="success-msg-timeline">

            </div>
            {{-- method - post  action="{{ route('timeline.store') }}" --}}
            <form id="timeline_form">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <table class="table">
                    <thead>
                        <th>Title</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Action</th>
                    </thead>
                    <tbody id="table-body">
                        <tr>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="title" name="timelines[0][title]"
                                        placeholder="Enter title" value="{{ old('timelines.0.title') }}">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="date" min={{ date('Y-m-d') }} id="date"
                                        name="timelines[0][start_date]" class="form-control"
                                        value="{{ old('timelines.0.start_date') }}">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="date" min={{ date('Y-m-d') }} id="date"
                                        name="timelines[0][end_date]" class="form-control"
                                        value="{{ old('timelines.0.end_date') }}">
                                </div>
                            </td>
                            <td>
                                <p class="text-danger">*required</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button id="timeline_submit" class="btn btn-primary">Create</button>
                <button id="add-more-timeline" class="btn btn-success">Add more + </button>
            </form>
        </div>
    </div>
    @endcan
  


    {{-- timelines --}}
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
                            @can('create-budget')
                                   <button data-id="{{ $timeline->id }}" data-title= "{{ $timeline->title }}"
                                class="btn btn-success create-budget">Create Budget</button> |
                            @endcan
                            <button type="button" class="btn btn-warning show-timeline" data-id="{{ $timeline->id }}">Show
                                Budgets</button>
                                @can('edit-timeline') 
                                | <a href={{ route('timeline.edit', $timeline->id) }} class="btn btn-primary">Edit</a>
                                @endcan
                           @can('delete-timeline')
                                | <button type="submit" class="btn btn-danger">Delete</button>
                           @endcan
                           
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

    {{-- form for budget --}}
    @can('create-budget') 
    <div id="create-budget-form" class="card d-none bg-light">
        <div class="card-header">
            <h5 class="inline-block" id="create-budget-title"></h5>
            <button type="button" class="close text-danger" id="close-budget-form">
                <i class="bi bi-x-circle "></i>
            </button>
        </div>
        <div class="card-body">
            <div id="success-msg"></div>
            {{-- method post action="{{ route('budget.store') }}" --}}
            <form id="budget_form">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <input type="hidden" name="timeline_id" id="timelineId" value="">
                <table class="table">
                    <thead>
                        <th>Title</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </thead>
                    <tbody id="table-budget-body">
                        <tr>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="budget-title" name="budgets[0][name]"
                                        placeholder="Enter title" value="{{ old('budgets.0.name') }}">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="budget-amount" name="budgets[0][amount]"
                                        placeholder="Enter Amount" value="{{ old('budgets.0.amount') }}">
                                </div>
                            </td>
                            <td>
                                <p class="text-danger">*required</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button id="budget_submit" class="btn btn-primary">Create</button>
                <button id="add-more-budget" class="btn btn-success">Add more + </button>
            </form>
        </div>
    </div>
    @endcan
   

    {{-- dsiplay budgets --}}
    <div id="display-budget" class="d-none">
        <div class="card">
            <div class="card-body">
                <button type="button" class="close text-danger mt-3" id="close-budget">
                    <i class="bi bi-x-circle "></i>
                </button>
                @forelse ($project->timeline as $timeline)
                    <ul class="budgetList d-none" id="budgetList-{{ $timeline->id }}">
                        <li>
                            <div class="card-header">
                                <h5 class="inline-block">Timeline title : {{ $timeline->title }}</h5>
                            </div>
                        </li>
                        <li>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Budget Head</th>
                                        <th scope="col">Amount in Rs</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($timeline->budget as $singleBudget)
                                        <tr id="budgetRow_{{ $singleBudget->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td contenteditable="false" id="name_{{ $singleBudget->id }}">
                                                {{ $singleBudget->name }}</td>
                                            <td contenteditable="false" id="amount_{{ $singleBudget->id }}">
                                                {{ $singleBudget->amount }}</td>
                                            <td>
                                                @can('edit-budget')
                                                    <button onclick="editBudget({{ $singleBudget->id }})"
                                                        id="edit_{{ $singleBudget->id }}"
                                                        class="btn btn-primary">Edit</button>
                                                @endcan
                                                @can('edit-budget')
                                                    <button  onclick="saveBudget({{ $singleBudget->id }})"
                                                        id="save_{{ $singleBudget->id }}"
                                                        class="btn btn-success d-none">Save</button> 
                                                @endcan
                                                   @can('delete-budget')
                                                       <button onclick="deleteBudget({{$singleBudget->id}})" class="btn btn-danger">Delete</button>
                                                   @endcan             
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">No Budget found</td>
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
                    // console.log(timelineId)
                    // Show the budget list for the selected timeline
                    $('.budgetList').addClass('d-none');
                    $('#display-budget').removeClass('d-none');
                    $('#budgetList-' + timelineId).removeClass('d-none');
                });

                //close budget display
                $('#close-budget').click(function() {
                    $('#display-budget').addClass('d-none');
                })
                //show create timeline form
                $('#create-timeline').click(function() {
                    $(this).addClass('d-none');
                    $('#create-timeline-form').removeClass("d-none");
                })

                //close create timeline form
                $('#close-timeline').click(function() {
                    $('#create-timeline-form').addClass("d-none");
                    $('#create-timeline').removeClass('d-none');
                })

                //add more fileds for timeline
                var index = 0;
                $('#add-more-timeline').click(function(e) {
                    e.preventDefault();
                    index++;
                    let formHtml = ` <tr id="timeline_${index}">
                        <td> <div class="form-group">
                    <input type="text" class="form-control" id="title_${index}" name="timelines[${index}][title]" placeholder="Enter title"
                        value="{{ old('timelines.${index}.title') }}">
                </div></td>
                <td>
                     <div class="form-group">
                    <input type="date" min={{ date('Y-m-d') }} id="start_date_${index}" name="timelines[${index}][start_date]" class="form-control"   value="{{ old('timelines.${index}.start_date') }}">
                </div>
                </td>
                <td>
                    <div class="form-group">
                    <input type="date" min={{ date('Y-m-d') }} id="end_date_${index}" name="timelines[${index}][end_date]" class="form-control"   value="{{ old('timelines.${index}.end_date') }}">
                </div>
                </td>
                <td>
                     <button class="btn btn-danger remove-timeline-btn" data-timeline-index="${index}"><i class="bi bi-trash3"></i></button>
                </td>
                    </tr>`;
                    $('#table-body').append(formHtml);
                })


                //remove filed trash
                $(document).on('click', '.remove-timeline-btn', function(e) {
                    e.preventDefault();
                    let indexToRemove = $(this).data('timeline-index');
                    $('#timeline_' + indexToRemove).remove();
                });


                //timeline form submit
                $('#timeline_form').submit(function(e) {
                    e.preventDefault();
                    let form = $('#timeline_form');
                    $.ajax({
                        type: "POST",
                        url: "{{ route('timeline.store') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: form.serialize(),
                        success: function(response) {
                            $('#success-msg-timeline').html(
                                `<div class="alert alert-success" id="success-message-timeline">
                    ${response.message}
                </div>`
                            );
                            $('#timeline_form')[0].reset();
                            setTimeout(function() {
                                $('#success-message-timeline').fadeOut();
                            }, 3000);
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr)
                        }
                    })
                })

                //show budget create form
                $('.create-budget').click(function(event) {
                    event.preventDefault();
                    $('#create-budget-form').addClass('d-none');
                    $('.create-budget').removeClass('d-none');
                    let title = $(this).data('title');
                    let timelineId = $(this).data('id');
                    $('#timelineId').val(timelineId);
                    $(this).addClass('d-none');
                    $('#create-budget-form').removeClass('d-none');
                    $('#create-budget-title').html(`Create budgets for ${title}`)
                })

                $('#close-budget-form').click(function() {
                    $('#create-budget-form').addClass('d-none');
                    $('.create-budget').removeClass('d-none');
                });


                //add more fileds for timeline
                var budgetIndex = 0;
                $('#add-more-budget').click(function(e) {
                    e.preventDefault();
                    budgetIndex++;
                    let formHtml = ` <tr id="budget_${budgetIndex}">
                         <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="budget-title-${budgetIndex}" name="budgets[${budgetIndex}][name]"
                                        placeholder="Enter title"  value="{{ old('budgets.${budgetIndex}.name') }}">
                                </div>
                            </td>
                            <td>
                               <div class="form-group">
                                    <input type="text" class="form-control" id="budget-amount-${budgetIndex}" name="budgets[${budgetIndex}][amount]"
                                        placeholder="Enter Amount" value="{{ old('budgets.${budgetIndex}.amount') }}">
                                </div>
                            </td>
                <td>
                     <button class="btn btn-danger remove-budget-btn" data-budget-index="${budgetIndex}"><i class="bi bi-trash3"></i></button>
                </td>
                    </tr>`;
                    $('#table-budget-body').append(formHtml);
                })

                //remove filed trash
                $(document).on('click', '.remove-budget-btn', function(e) {
                    e.preventDefault();
                    let indexToRemove = $(this).data('budget-index');
                    $('#budget_' + indexToRemove).remove();
                });

                //budget form submit
                $('#budget_form').submit(function(e) {
                    e.preventDefault();
                    let form = $('#budget_form');
                    $.ajax({
                        type: "POST",
                        url: "{{ route('budget.store') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: form.serialize(),
                        success: function(response) {
                            $('#success-msg').html(
                                `<div class="alert alert-success" id="success-message">
                    ${response.message}
                </div>`
                            );
                            $('#budget_form')[0].reset();
                            setTimeout(function() {
                                $('#success-message').fadeOut();
                            }, 3000);
                        },
                        error: function(xhr, status, error) {
                            console.log(error)
                        }
                    })
                })
     });


     //functions 

      function editBudget(id) {
        $("#name_" + id).prop("contenteditable", true);
        $("#amount_" + id).prop("contenteditable", true);

        $("#edit_" + id).addClass('d-none');
        $("#save_" + id).removeClass('d-none');
    }

    function saveBudget(id) {
        $("#name_" + id).prop("contenteditable", false);
        $("#amount_" + id).prop("contenteditable", false);

        $("#edit_" + id).removeClass('d-none');
        $("#save_" + id).addClass('d-none');

        $.ajax({
            url: `/budgets/${id}`,
            type: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                name: $("#name_" + id).text(),
                amount: $("#amount_" + id).text(),
                project_id: {{ $project->id }}
            },
            success: function (response) {
                console.log(response);
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
      }

      function deleteBudget(id){
        $.ajax({
            url:`/budgets/${id}`,
            type: 'DELETE',
            headers:{
                'X-CSRF-TOKEN' : '{{ csrf_token()}}'
            },
            success : function(response){
                $("#budgetRow_"+ id).remove();
                alert(response.message);
            },
            error:function(xhr,status,error){
                console.log(error)
            }
        })
      }

        </script>
    @endpush

@endsection

@extends('layouts.dashboardLayout')
@section('content')
    <div class="p-9">
        <h1 class="text-center text-blue-600 text-[30px] mb-8 font-serif">Assign a project to user</h1>
         @if ($errors->any())
     <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
     </div>
 @endif
 <div class="card mb-4">
    <div class="card-body">
        <p>Username : {{$user->username}}</p>
        <p>User email : {{$user->email}}</p>
    </div>
 </div>
        <form action="{{ route('assign.project.user') }}" method="post">
            @csrf
            <input type="hidden" name="user_id" value="{{$user->id}}">
            <div class="form-group">
                <label for="per">Select projects to assign to user</label>
                <select class="form-control" name="project_id[]" multiple id="">
                @forelse ($projects as $project)
                    <option value="{{ $project->id }}"
                        {{ in_array($project->id, $assignedProjects?? []) ? 'selected' : '' }}>{{ $project->title }}
                    </option>
                @empty
                @endforelse
            </select>
            @error('project_id')
                <p class="text-red-600">{{ $message }}</p>
            @enderror
            </div>
            <button class="btn btn-success">Assign Project</button>
            @include('partials.goback')
        </form>
        <style>
            select[multiple] {
                height: auto;
            }

            select[multiple] option:checked {
                background-color: #d6f7e1;
                border-color: #48bb78;
            }
        </style>
        @push('other-scripts')
             <script>
            $(document).ready(function() {
                $('select[multiple]').mousedown(function(e) {
                    e.preventDefault();

                    var originalScrollTop = $(this).scrollTop();
                    $(this).focus().blur();

                    $(this).scrollTop(originalScrollTop);

                    var option = $(e.target);
                    option.prop('selected', !option.prop('selected'));
                });
            });
        </script>
        @endpush
    </div>
@endsection
@extends('layouts.dashboardLayout')
@section('content')
    <div class="p-9">
        <h1 class="text-center text-blue-600 text-[30px] mb-8 font-serif">Assign users to the project</h1>
        {{Breadcrumbs::render('project.assign.user.create',$project)}}
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
        <p>Project title : {{$project->title}}</p>
    </div>
 </div>
        <form action="{{ route('project.assign.user.store') }}" method="post">
            @csrf
            <input type="hidden" name="project_id" value="{{$project->id}}">
            <div class="form-group">
                <label for="per">Select users to assign to project</label>
                <select class="form-control" name="user_id[]" multiple id="">
                @forelse ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ in_array($user->id, $assignedUsers ?? []) ? 'selected' : '' }}>{{ $user->username }}
                    </option>
                @empty
                @endforelse
            </select>
            @error('user_id')
                <p class="text-red-600">{{ $message }}</p>
            @enderror
            </div>
            <button class="btn btn-success">Assign users</button>
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
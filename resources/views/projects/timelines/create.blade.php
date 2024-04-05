@extends('layouts.dashboardLayout')
@section('content')
 <h3 class="text-center">Create new Timeline</h3>

 @if ($errors->any())
     <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
     </div>
 @endif
<form action="{{route('timeline.store')}}" method="post">
    @csrf
    {{-- <input type="hidden" name="project_id" value="{{$project->id}}"> --}}
    <div class="form-group">
       <label for="project">Select a project</label><br>
       <select name="project_id" id="project">
        @foreach ($projects as $project)
        <option value="{{$project->id}}">{{$project->title}}</option>            
        @endforeach
       </select>
    </div>
  <div class="form-group">
    <label for="title">Timeline Title</label>
    <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" value="{{old('title')}}">
  </div>
   <div class="form-group">
    <label for="date">Start Date</label>
   <input type="date" min={{date('Y-m-d')}} id="date" name="start_date" class="form-control">
  </div>
  <div class="form-group">
    <label for="date">End Date</label>
   <input type="date"  min={{date('Y-m-d')}} id="date" name="end_date" class="form-control">
  </div>
  <button type="submit" class="btn btn-primary">Create Timeline</button>
</form>
@endsection
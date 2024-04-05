@extends('layouts.dashboardLayout')
@section('content')
 <h3 class="text-center">Update Project</h3>
 @if ($errors->any())
     <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
     </div>
 @endif
<form action="{{route('project.update',$project->id)}}" method="post">
    @csrf
    @method('PUT')
  <div class="form-group">
    <label for="title">Project Title</label>
    <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" value="{{$project->title}}">
  </div>
   <div class="form-group">
    <label for="desc">Project Description</label>
   <textarea name="desc" id="description" rows="5"  class="form-control">{{$project->desc}}</textarea>
  </div>
   <div class="form-group">
    <label for="date">Start Date</label>
   <input type="date" min={{date('Y-m-d')}} id="date" name="start_date" value="{{$project->start_date}}" class="form-control">
  </div>
  <div class="form-group">
    <label for="date">End Date</label>
   <input type="date"  min={{date('Y-m-d')}} id="date" value="{{$project->end_date}}" name="end_date" class="form-control">
  </div>
  <button type="submit" class="btn btn-success">Update Project</button>
  @include('partials.goback')
</form>
@endsection
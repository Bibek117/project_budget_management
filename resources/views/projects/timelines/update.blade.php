@extends('layouts.dashboardLayout')
@section('content')
 <h3 class="text-center">Update Timeline</h3>

 @if ($errors->any())
     <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
     </div>
 @endif
<form action="{{route('timeline.update',$timeline->id)}}" method="post">
    @csrf
    @method('PUT')
    <input type="hidden" name="project_id" value="{{$timeline->project_id}}">
  <div class="form-group">
    <label for="title">Timeline Title</label>
    <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" value="{{$timeline->title}}">
  </div>
   <div class="form-group">
    <label for="date">Start Date</label>
   <input type="date" min={{date('Y-m-d')}} id="date" value="{{$timeline->start_date}}" name="start_date" class="form-control">
  </div>
  <div class="form-group">
    <label for="date">End Date</label>
   <input type="date"  min={{date('Y-m-d')}} id="date" value="{{$timeline->end_date}}" name="end_date" class="form-control">
  </div>
  <button type="submit" class="btn btn-primary">Update Timeline</button>
  @include('partials.goback')
</form>
@endsection
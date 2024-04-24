{{-- @extends('layouts.dashboardLayout')
@section('content')

  
 <h3 class="text-center">Update user details</h3>
 @if ($errors->any())
     <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
     </div>
 @endif
<form action="{{route('user.update',$user->id)}}" method="post">
    @csrf
    @method('PUT')
  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" class="form-control" id="username" name="username"  value="{{$user->username}}">
  </div>
   <div class="form-group">
    <label for="email">Email address</label>
    <input type="email" class="form-control" name="email" id="email"  value="{{$user->email}}">
  </div>
  <div class="form-group">
  <label for="phone">Phone Number</label>
  <input type="tel" class="form-control" id="phone" name="phone" value="{{$user->phone}}" >
</div>
  <button type="submit" class="btn btn-success">Save edits</button>
    @include('partials.goback')
</form>
@endsection --}}
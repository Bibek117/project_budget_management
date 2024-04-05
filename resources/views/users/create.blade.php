@extends('layouts.dashboardLayout')
@section('content')
 <h3 class="text-center">Register new user</h3>
 @if ($errors->any())
     <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
     </div>
 @endif
<form action="{{route('user.register')}}" method="post">
    @csrf
  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" value="{{old('username')}}">
  </div>
   <div class="form-group">
    <label for="email">Email address</label>
    <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" value="{{old('email')}}">
  </div>
   <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password"  placeholder="Enter password" name="password">

  </div>
  <div class="form-group">
    <label for="password_confirm">Password Confirm</label>
    <input type="password" class="form-control" id="password_confrim"  name="password_confirmation" placeholder="Password">
  </div>
  <div class="form-group">
  <label for="phone">Phone Number</label>
  <input type="tel" class="form-control" id="phone" name="phone" value="{{old('phone')}}"placeholder="Enter contact number" >
</div>
  <button type="submit" class="btn btn-primary">Register User</button>
</form>
@endsection
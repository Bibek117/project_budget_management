@extends('layouts.dashboardLayout')
@section('content')
@foreach ($roles as $role)
<p>{{$role->name}}</p>
    
@endforeach
@endsection

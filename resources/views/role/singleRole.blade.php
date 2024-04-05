@extends('layouts.dashboardLayout')
@section('content')
<h3 class="text-center uppercase text-[20px]">Role Details page</h3>
    <div class="p-7 flex justify-evenly">
        <div>
            <p class="text-white p-3 inline-block bg-black ">Role Name</p>
            <h2 class='mt-3'>{{ $role->name }}</h2>
        </div>
        <div>
             <p class="text-white p-3 inline-block bg-black ">Assigned Permissions</p>
            @forelse ($permissions as $permission)
                <p class="mt-2">{{ $permission->name }}</p>
            @empty
            @endforelse
        </div>
          <button><a href="{{url()->previous()}}">Go back</a></button>
    </div>
@endsection

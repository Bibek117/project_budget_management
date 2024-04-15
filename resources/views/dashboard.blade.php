@extends('layouts.dashboardLayout')
@section('content')
    @if (auth()->user()->hasRole('Admin'))
        <p>This is a admin dashboard</p>
    @else
        Assigned Projects
        <ul >
            @forelse (auth()->user()->projects as $project)
            <li>
                {{$project->title}}
            </li>
            <li>
                <a href="{{route('project.show',$project->id)}}">Go to project</a>
            </li>
            @empty
             <p>No projects assigned for you</p>
            @endforelse
        </ul>
    @endif
@endsection

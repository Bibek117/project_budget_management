@extends('layouts.dashboardLayout')
@section('content')
    {{-- @php
    dd($projects)
@endphp --}}
    <h3 class="text-center">Projects</h3>
    <button class="btn btn-primary mb-3"><a class="text-white" href="{{ route('project.create') }}">Create a new Project <i
                class="bi bi-plus-circle"></i></a></button>
    @if (session('success'))
        <p class="text-success">{{ session('success') }}</p>
    @endif
    <table class="table" id="index_table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Project Title</th>
                <th scope="col">Project Start Date</th>
                <th scope="col">Project End Date</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($projects as $project)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $project->title }}</td>
                    <td>{{ $project->start_date }}</td>
                    <td>{{ $project->end_date }}</td>
                    <td>
                        <form action={{ route('project.destroy', $project->id) }} method="post">
                            @csrf
                            @method('DELETE')
                            @can('assign-user-to-project') 
                            <a href={{ route('project.assign.user.create', $project->id) }}
                                class="font-medium text-green-600  hover:underline">Assign Users</a> |
                            @endcan
                           
                            <a href={{ route('project.show', $project->id) }}
                                class="font-medium text-yellow-600  hover:underline">Show</a>
                                @can('edit-project') 
                                | <a href={{ route('project.edit', $project->id) }}
                                class="font-medium text-blue-600  hover:underline">Edit</a>
                                @endcan
                           
                                @can('delete-project')
                                 | <button type="submit" class="font-medium text-red-600  hover:underline">Delete</button>
                                @endcan
                           
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No projects found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- @push('other-scripts')

    <script>
        $(document).ready(function(){
            $('#index_table').DataTable();
        })
    </script>
    @endpush --}}
    {{-- {{$users->links()}} --}}
@endsection

{{-- @section('budget_timeline_create')
    <li >
        <a href={{ route('timeline.create') }}
            class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100  group">
            <i class="bi bi-calendar2-week"></i>
            <span class="flex-1 ms-3 whitespace-nowrap font-weight-lighter">Create Timeline</span>
        </a>
    </li>
    <li>
        <a href={{ route('budget.create') }}
            class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100  group">
            <i class="bi bi-cash-coin"></i>
            <span class="flex-1 ms-3 whitespace-nowrap">Create Budget</span>
        </a>
    </li>
@endsection --}}

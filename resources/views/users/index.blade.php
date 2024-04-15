@extends('layouts.dashboardLayout')
@section('content')
    <h3 class="text-center">User details</h3>
    @can('register-user')
          <button class="btn btn-primary mb-3"><a class="text-white" href="{{ route('user.create') }}">Create new User</a></button>
    @endcan
    @if (session('success'))
        <p class="text-success">{{ session('success') }}</p>
    @endif
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <form action={{ route('user.destroy', $user->id) }} method="post">
                            @csrf
                            @method('DELETE')
                            @can('assign-contact')  
                            <a href={{ route('user.assign.ctype.create', $user->id) }}
                                class="font-medium text-green-600  hover:underline">Assign Contact Type</a> | 
                            @endcan
                            @can('assign-project-to-user')  
                                <a href={{ route('user.assign.project.create', $user->id) }}
                                class="font-medium text-green-600  hover:underline">Assign Project</a> | 
                             @endcan
                           
                            <a href={{ route('user.show', $user->id) }}
                                class="font-medium text-yellow-600  hover:underline">Show</a>

                            @can('edit-user')
                                 | <a href={{ route('user.edit', $user->id) }}
                                class="font-medium text-blue-600  hover:underline">Edit</a>
                            @endcan
                                @can('delete-user')
                                     | <button type="submit" class="font-medium text-red-600  hover:underline">Delete</button>
                                @endcan
                           
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No users found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{$users->links()}}
@endsection

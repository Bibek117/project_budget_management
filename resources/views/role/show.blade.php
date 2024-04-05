@extends('layouts.dashboardLayout')
@section('content')
    <h1 class="text-center text-blue-600 text-[30px] mb-8 font-serif">Manage Roles</h1>
    @can('create-role')
        <button type="button"
            class="btn btn-primary">
            <a class="text-white" href={{ route('roles.create') }}>Create a new role</a>
        </button>
    @endcan
    @can('assign-role')
    <button type="button"
            class="btn btn-primary">
            <a class="text-white" href={{ route('role.assign') }}>Assign Role</a>
        </button>
    @endcan
    @if (session('success'))
        <h3>{{session('success')}}</h3>
    @endif
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 mb-4 mt-5">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        #
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)
                    <tr class="bg-white border-b ">
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $role->name }}
                        </td>
                        <td class="px-6 py-4">
                            <form action={{ route('roles.destroy', $role->id) }} method="post">
                                @csrf
                                @method('DELETE')
                                @if ($role->name != 'Super Admin')
                                 <a href={{ route('roles.show', $role->id) }}
                                    class="font-medium text-green-600  hover:underline">Show</a> 
                                    @can('edit-role')
                                        | <a href={{ route('roles.edit', $role->id) }}
                                            class="font-medium text-blue-600  hover:underline">Edit</a>
                                    @endcan
                                    @can('delete-role')
                                    @if (!(Auth::user()->hasRole($role->name)))
                                         | <button type="submit" class="font-medium text-red-600  hover:underline">Delete</button>
                                    @endif
                                    @endcan
                                @endif

                            </form>

                        </td>
                    </tr>
                @empty
                    <tr class="bg-white border-b ">
                        <td colspan="3">No roles found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-7">
            {{ $roles->links() }}
        </div>

    </div>
@endsection

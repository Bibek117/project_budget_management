@extends('layouts.dashboardLayout')
@section('content')
    <h3 class="text-center">Contact Types</h3>
    <button class="btn btn-primary mb-3"><a class="text-white" href="{{ route('contacttype.create') }}">Create new Contact
            Type</a></button>
    @if (session('success'))
        <p class="text-success">{{ session('success') }}</p>
    @endif
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($contacttypes as $contacttype)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td contenteditable="true">{{ $contacttype->name }}</td>
                    <td>
                        <form action={{ route('contacttype.destroy', $contacttype->id) }} method="post">
                            @csrf
                            @method('DELETE')
                            <a href={{ route('contacttype.edit', $contacttype->id) }}
                                class="font-medium text-blue-600  hover:underline">Edit</a>
                            | <button type="submit" class="font-medium text-red-600  hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No contacts type found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{-- {{$users->links()}} --}}
@endsection
@extends('layouts.dashboardLayout')
@section('content')
    <h3 class="text-center">Contact Types</h3>
    <button class="btn btn-primary mb-3"><a class="text-white" href="{{ route('contacttype.create') }}">Create</a></button>
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
                    <td>{{ $contacttype->name }}</td>
                    <td class="d-flex">
                        @can('edit-contacttype')
                            <a class="btn btn-success" href={{ route('contacttype.edit', $contacttype->id) }}
                                class="font-medium text-blue-600  hover:underline">Edit</a>
                        @endcan
                        @can('delete-contacttype')
                            <form action={{ route('contacttype.destroy', $contacttype->id) }} method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit"
                                    class="font-medium text-red-600  hover:underline">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No contacts type found</td>
                </tr>
            @endforelse
            <tr>
                <td>Showing 1 to 3 out of {{$totalContacttypes}} results</td>
                <td></td>
                <td>
                    <button id="prev" class="btn btn-primary">Prev</button>
                    <button id="next" class="btn btn-primary">Next</button>
                </td>
            </tr>
        </tbody>
    </table>
   
    @push('other-scripts')
        <script>
            $(document).ready(function(){
                var offset = 0;
                $('#next').click(function(){
                    offset +=3;
                    callTest(offset);
                })


                function callTest(offset){
                      $.ajax({
                    url : `/contacttypes/?offset=${offset}`,
                    type : 'GET',
                    success : function(response){
                        console.log(response)
                    },
                    error:function(xhr,status,error){
                        console.log(xhr)
                    }
                })
                }
            });
        </script>
    @endpush
@endsection

@extends('layouts.dashboardLayout')
@section('content')
    <h3 class="text-center">Contact Types</h3>
    {{Breadcrumbs::render('contacttype.index')}}
    <button class="btn btn-primary mb-3"><a class="text-white" href="{{ route('contacttype.create') }}">Create</a></button>
    @if (session('success'))
        @include('partials._successToast',['message'=>session('success')])
    @endif
    @php
        $allContactTypesCount = $totalContacttypes;
    @endphp
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody id="tableBody">
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

        </tbody>
        <tfoot id="tableFooter">
            <tr>
                <td id="paginationCount">Showing 1 to 3 out of {{ $allContactTypesCount }} results</td>
                <td></td>
                <td>
                    <button id="prev" class="btn btn-primary">Prev</button>
                    <button id="next" class="btn btn-primary">Next</button>
                </td>
            </tr>
        </tfoot>
    </table>

    @push('other-scripts')
        <script>
            function callNextPage(offset) {
                $.ajax({
                    url: `/contacttype/?offset=${offset}`,
                    type: 'GET',
                    success: function(response) {
                        $('#tableBody').empty();
                        $.each(response.contacttypes, function(index, contacttype) {
                            let test = 5
                            let htmlRow = ` <tr>
                    <td>${(index+offset)+1}</td>
                    <td> ${contacttype.name}</td>
                    <td class="d-flex">
                        @can('edit-contacttype')
                                <a class="btn btn-success" href="/contacttype/${contacttype.id}/edit" class="font-medium text-blue-600  hover:underline">Edit</a>
                        @endcan
                        @can('delete-contacttype')
                            <form action="/contacttype/${contacttype.id}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit"
                                    class="font-medium text-red-600  hover:underline">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>`
                            $('#tableBody').append(htmlRow)
                        });


                        $('#paginationCount').html(
                            `Showing ${offset + 1} to ${offset + response.contacttypes.length} out of ${@json($allContactTypesCount)} results`
                        );

                        if (offset + response.contacttypes.length >= @json($allContactTypesCount)) {
                            $('#next').prop('disabled', true);
                        } else {
                            $('#next').prop('disabled', false);
                        }

                        offset > 0 ? $('#prev').prop('disabled', false) : $('#prev').prop('disabled', true);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr)
                    }
                })
            }
            $(document).ready(function() {
                var offset = 0;
                $('#prev').prop('disabled', true);
                $('#next').click(function() {
                    offset += 3;
                    callNextPage(offset);
                });

                $('#prev').click(function() {
                    offset -= 3;
                    callNextPage(offset);
                })
            });
        </script>
    @endpush
@endsection

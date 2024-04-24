@extends('layouts.dashboardLayout')
@section('content')
    <h3 class="text-center">User details</h3>
    @can('register-user')
        <button class="btn btn-primary mb-3"><a class="text-white" href="{{ route('user.create') }}">Create new User</a></button>
    @endcan
    @if (session('success'))
        <p class="text-success">{{ session('success') }}</p>
    @endif
    <table class="table" id="userDataTable">
        <thead class="thead-light">
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
                                {{-- href={{ route('user.edit', $user->id) }} --}}
                                | <a class="font-medium text-blue-600  hover:underline userEdit" data-toggle="modal"
                                    data-target="#userEditModal" data-id="{{ $user->id }}">Edit</a>
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

    <!-- Modal -->
    <div class="modal fade" id="userEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit User Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" name="email" id="email">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="assignProject">Select projects to assign to user</label>
                            <select class="form-control" name="project_id[]" multiple id="assignProject">
                               
                            </select>
                        </div>
                         <div class="form-group">
                            <label for="assignContact">Select contacttypes to assign to user</label>
                            <select class="form-control" name="contacttype_id[]" multiple id="assignContact">
                                
                            </select>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    {{-- {{ $users->links() }} --}}

    <style>
        select[multiple] {
            height: auto;
        }

        select[multiple] option:checked {
            background-color: #d6f7e1;
            border-color: #48bb78;
        }
    </style>
@endsection
@push('other-scripts')
    <script>
        $(document).ready(function() {

            $('#userDataTable').DataTable();
            $('.userEdit').click(function() {
                let userId = $(this).data('id');
                $.ajax({
                    url: `users/${userId}/edit`,
                    type: "GET",
                    success: function(response) {
                        $('#username').val(response.user.username);
                        $('#email').val(response.user.email);

                        console.log(response.assignedContacts);
                        let selectContact = $('#assignContact');
                        let selectProject = $('#assignProject');

                        // selectBox.empty(); // Clear existing options
                        $.each(response.projects, function(index, project) {
                            let selected = response.assignedProjects.includes(project
                                .id) ? 'selected' : '';
                            selectProject.append(
                                `<option value="${project.id}" ${selected}>${project.title}</option>`
                            );
                        });

                        $.each(response.contacttypes,function(index,contacttype){
                            let selected = response.assignedContacts.includes(contacttype.id) ? "selected" : "";
                            selectContact.append(`<option value="${contacttype.id}" ${selected}> ${contacttype.name}</option> `)
                        })

                        // $('#editForm').append(assignProjectHtml);
                        console.log(response.user.username);
                    },
                    error: function(xhr, status, errror) {
                        console.log(xhr);
                    }
                })
            })
            $('#userEditModal').on('shown.bs.modal', function() {
                $('#username').trigger('focus');


            })

            $('select[multiple]').mousedown(function(e) {
                e.preventDefault();

                var originalScrollTop = $(this).scrollTop();
                $(this).focus().blur();

                $(this).scrollTop(originalScrollTop);

                var option = $(e.target);
                option.prop('selected', !option.prop('selected'));
            });
        })
    </script>
@endpush

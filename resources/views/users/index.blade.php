@extends('layouts.dashboardLayout')
@section('content')
    <h3 class="text-center">User details</h3>
    @can('register-user')
        <button class="btn btn-primary mb-3"><a class="text-white" href="{{ route('user.create') }}">Create new User</a></button>
    @endcan
    @if (session('success'))
        <p class="text-success">{{ session('success') }}</p>
    @endif 
        <p id="successMsg" class="text-success"></p>
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
                    <td class="d-flex">
                        <button class="btn btn-info mr-2">
                            <a href={{ route('user.show', $user->id) }}
                                class="font-medium text-yellow-600  hover:underline"><i
                                    class="bi text-white bi-eye"></i></a>
                        </button>
                        @can('edit-user')
                            <button class="btn btn-success mr-2">
                                <a class="font-medium text-blue-600  hover:underline userEdit" data-target="#userEditModal"
                                    data-id="{{ $user->id }}"> <i class="bi bi-pencil-square "></i></a>
                            </button>
                        @endcan
                        <form action={{ route('user.destroy', $user->id) }} method="post">
                            @csrf
                            @method('DELETE')
                            @can('delete-user')
                                <button class="btn btn-danger" type="submit" class="font-medium text-red-600  hover:underline">
                                    <i class="bi bi-trash3-fill"></i></button>
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
    <div class="modal fade" id="userEditModal" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <input type="hidden" id="user_id" value="">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username">
                            <p class="text-danger text-sm" id="error_username"></p>
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" name="email" id="email">
                             <p class="text-danger text-sm" id="error_email"></p>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                             <p class="text-danger text-sm" id="error_phone"></p>
                        </div>
                        @can('assign-project-to-user')
                            <div class="form-group">
                                <label for="assignProject">Select projects to assign to user</label>
                                <select class="form-control" name="project_id[]" multiple id="assignProject">
                                </select>
                            </div>
                        @endcan

                        @can('assign-contact')
                            <div class="form-group">
                                <label for="assignContact">Select contacttypes to assign to user</label>
                                <select class="form-control" name="contacttype_id[]" multiple id="assignContact">
                                </select>
                            </div>
                        @endcan
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" id="userModalSubmit">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    {{-- {{ $users->links() }} --}}
@endsection
@push('other-scripts')
    <script>
        $(document).ready(function() {

            $('#userDataTable').DataTable();

            
            $('.userEdit').click(function() {
                var userId = $(this).data('id');
                $.ajax({
                    url: `user/${userId}/edit`,
                    type: "GET",
                    success: function(response) {
                        $('#username').val(response.user.username);
                        $('#email').val(response.user.email);
                        $('#phone').val(response.user.phone);
                        $('#user_id').val(userId)

                        // console.log(response.assignedContacts);
                        let selectContact = $('#assignContact');
                        let selectProject = $('#assignProject');

                        selectContact.empty();
                        selectProject.empty();
                        $.each(response.projects, function(index, project) {
                            let selected = response.assignedProjects.includes(project
                                .id) ? 'selected' : '';
                            selectProject.append(
                                `<option value="${project.id}" ${selected}>${project.title}</option>`
                            );
                        });

                        $.each(response.contacttypes, function(index, contacttype) {
                            let selected = response.assignedContacts.includes(
                                contacttype.id) ? "selected" : "";
                            selectContact.append(
                                `<option value="${contacttype.id}" ${selected}> ${contacttype.name}</option> `
                            )
                        });

                        $('#userEditModal').modal('show');
                        // console.log(response.user.username);
                    },
                    error: function(xhr, status, errror) {
                        console.log(xhr);
                    }
                })
            });

               $('#userModalSubmit').click(function() {
            
                            let editForm = $('#editForm');
                            let userId = $('#user_id').val();
                            $.ajax({
                                url: `/user/${userId}`,
                                type: "POST",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: editForm.serialize(),
                                success : function(response){
                                    //console.log(response)
                                    $('#successMsg').html(response.message);
                                    $('#userEditModal').modal('hide');

                                },
                                error : function(xhr,status,error){
                                    console.log(xhr)
                                    if(xhr.responseJSON.errors?.username){
                                        console.log(xhr.responseJSON.errors.username[0])
                                        $('#error_username').html(xhr.responseJSON.errors.username[0]);
                                    }
                                     if(xhr.responseJSON.errors?.email){
                                        $('#error_email').html(xhr.responseJSON.errors.username[0]);
                                    }
                                     if(xhr.responseJSON.errors?.phone){
                                        $('#error_phone').html(xhr.responseJSON.errors.phone[0]);
                                    }
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

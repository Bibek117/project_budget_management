@extends('layouts.dashboardLayout')
@section('content')
    <div class="p-9">
        <h1 class="text-center text-blue-600 text-[30px] mb-8 font-serif">Edit the role</h1>
        <form action="{{ route('roles.update', $role->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Enter the role name</label>
                <input class="form-control" type="text" id="name" name="name" value="{{ $role->name }}"
                    id="">
                @error('name')
                    <p class="text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Permissions</label>
                <select class="form-control max-w-60" name="permissions[]" multiple id="">
                    @forelse ($permissions as $permission)
                        <option value="{{ $permission->id }}"
                            {{ in_array($permission->id, $roleAssignedPermissions ?? []) ? 'selected' : '' }}>
                            {{ $permission->name }}</option>
                    @empty
                    @endforelse
                </select>
                 @error('permissions')
                <p class="text-red-600">{{ $message }}</p>
            @enderror
            </div>
            <button class="btn btn-success">Save the edits</button>
            @include('partials.goback')
        </form>
        <style>
            select[multiple] {
                height: auto;
            }

            select[multiple] option:checked {
                background-color: #d6f7e1;
                border-color: #48bb78;
            }
        </style>

        <script>
            $(document).ready(function() {
                $('select[multiple]').mousedown(function(e) {
                    e.preventDefault();

                    var originalScrollTop = $(this).scrollTop();
                    $(this).focus().blur();

                    $(this).scrollTop(originalScrollTop);

                    var option = $(e.target);
                    option.prop('selected', !option.prop('selected'));
                });
            });
        </script>


    </div>
@endsection

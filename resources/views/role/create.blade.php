@extends('layouts.dashboardLayout')
@section('content')
    {{Breadcrumbs::render('roles.create')}}
    <div class="p-9">
        <h1 class="text-center text-blue-600 text-[30px] mb-8 font-serif">Create new Role</h1>
        <form action="{{ route('roles.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label>Enter the role name</label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" id="">
                @error('name')
                    <p class="text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="per">Assign permissions to the role</label>
                <select class="form-control min-w-60" name="permissions[]" multiple id="permission_select">
                    @forelse ($permissions as $permission)
                        <option value="{{ $permission->id }}"
                            {{ in_array($permission->id, old('permissions') ?? []) ? 'selected' : '' }}>
                            {{ $permission->name }}
                        </option>
                    @empty
                    @endforelse
                </select>
                @error('permissions')
                    <p class="text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button class="btn btn-success">Create new role</button>
            @include('partials.goback')
        </form>
        @push('other-scripts')
            <script>
                $(document).ready(function() {
                    $('#permission_select').select2({
                        closeOnSelect: false
                    });
                });
            </script>
        @endpush
    </div>
@endsection

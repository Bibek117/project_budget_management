@extends('layouts.dashboardLayout')
@section('content')
    <div class="card">
        <div class="card-body">
            <h4>Assign Role</h4>
            <div class="card">
                <div class="card-body">
                    <h6>User details</h6>
                    <p>Username : {{ $user->username }}</p>
                    <p>Email : {{ $user->email }}</p>
                </div>
            </div>

            <div class="card">
                <fiv class="card-body">
                    <form action="{{ route('roles.updateAssign') }}" method="post">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <div class="form-group">
                            <label for="">Assign role to user</label>
                            <select class="form-control" name="roles[]" id="" multiple>
                                @foreach ($roles as $role)
                                    <option value="{{$role->id}}" {{in_array($role->id,$assignedRoles??[])? 'selected' : ''}}>{{$role->name}}</option>
                                @endforeach
                            </select>
                            @error('roles')
                              <p class="text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button class="btn btn-success">Save Edits</button>
                        @include('partials.goback')
                    </form>
                </fiv>
            </div>

        </div>
    </div>
        <style>
          select[multiple] {
                height: auto;
            }
        
              select[multiple] option:checked {
                background-color: #d6f7e1;
                border-color: #48bb78;
            }
    </style>
      @push('other-scripts')
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
        @endpush
@endsection

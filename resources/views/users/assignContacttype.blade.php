@extends('layouts.dashboardLayout')
@section('content')
    <div class="p-9">
        <h1 class="text-center text-blue-600 text-[30px] mb-8 font-serif">Assign a contact type to the user</h1>
         @if ($errors->any())
     <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
     </div>
 @endif
 <div class="card mb-4">
    <div class="card-body">
        <p>Username : {{$user->username}}</p>
        <p>User email : {{$user->email}}</p>
         <p>Assigned contacts : </p>
        @forelse ($user->contact as $contact)
            <p >{{$contact->contacttype->name}}</p>
        @empty
            <p>No assigned contact type</p>
        @endforelse
    </div>
 </div>
        <form action="{{ route('user.assign.ctype.store') }}" method="post">
            @csrf
            <input type="hidden" name="user_id" value="{{$user->id}}">
            <div class="form-group">
                <label for="per">Select contact type to assign to user</label>
                <select class="form-control" name="contacttype_id[]" multiple id="">
                @forelse ($contacttypes as $contacttype)
                    <option value="{{ $contacttype->id }}"
                       class="{{ in_array($contacttype->id, $assignedContacts ?? []) ? 'd-none' : '' }}">{{ $contacttype->name }}
                    </option>
                @empty
                @endforelse
            </select>
            @error('contacttype_id')
                <p class="text-red-600">{{ $message }}</p>
            @enderror
            </div>
            <button class="btn btn-success">Assign ContactType</button>
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
    </div>
@endsection
@extends('layouts.dashboardLayout')
@section('content')
    <h3 class="text-center">Update Contact type</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('contacttype.update', $contacttype->id) }}" method="post">
        @csrf
        @method('PUT')
        <input type="hidden" name="timeline_id" value="{{ $contacttype->timeline_id }}">
        <div class="form-group">
            <label for="title">Contact Type title</label>
            <input type="text" class="form-control" id="title" name="name" placeholder="Enter title"
                value="{{ $contacttype->name }}">
        </div>
        <button type="submit" class="btn btn-primary">Update contacttype</button>
        @include('partials.goback')
    </form>
@endsection
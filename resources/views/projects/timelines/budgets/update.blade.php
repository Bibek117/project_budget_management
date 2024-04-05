@extends('layouts.dashboardLayout')
@section('content')
    <h3 class="text-center">Update Budget</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('budget.update', $budget->id) }}" method="post">
        @csrf
        @method('PUT')
        <input type="hidden" name="timeline_id" value="{{ $budget->timeline_id }}">
        <div class="form-group">
            <label for="title">Budget title</label>
            <input type="text" class="form-control" id="title" name="name" placeholder="Enter title"
                value="{{ $budget->name }}">
        </div>
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" class="form-control" name="amount" min="1000" value="{{$budget->amount}}">
        </div>
        <button type="submit" class="btn btn-primary">Update Budget</button>
        @include('partials.goback')
    </form>
@endsection

@extends('layouts.dashboardLayout')
@section('content')
    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif
    @can('create-transaction')
        <button class="btn btn-primary mb-3"><a class="text-white" href="{{ route('transaction.create') }}">New Record<i
                    class="bi bi-plus-circle"></i></a></button>
    @endcan

    <table class="table" id="recordDataTable">
        <thead class="thead-light">
            <th>Record Code</th>
            <th>Created By</th>
            <th>Amount</th>
            <th>Project</th>
            <th>Actions</th>
        </thead>
        @forelse ($records as $record)
            <tr>
                <td>{{ $record->code }}</td>
                <td>{{ $record->user->username }}</td>
                @php
                    $total = 0;
                    foreach ($record->transaction as $transaction) {
                        if ($transaction->amount > 0) {
                            $total += $transaction->amount;
                        }
                    }
                @endphp
                <td>{{ $total }}</td>
                <td>{{ $record->project->title }}</td>
                <td class="d-flex">
                    <button class="btn btn-info mr-2">
                        <a href="{{ route('record.show', $record->id) }}"><i class="bi text-white bi-eye"></i></a>
                    </button>
                    <button class="btn btn-success mr-2">
                        <a href="{{ route('record.edit', $record->id) }}" class="text-light"> <i
                                class="bi bi-pencil-square "></i></a>
                    </button>
                    <form action="{{ route('record.destroy', $record->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </form>
                </td>
            @empty
                <td colspan="4">No records found</td>
            </tr>
        @endforelse
    </table>
   
@endsection
@push('other-scripts')
    <script>
        $(document).ready(function(){
            $('#recordDataTable').DataTable();
        })
    </script>
@endpush

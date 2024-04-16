@extends('layouts.dashboardLayout')
@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Edit Record</h5>
        </div>

        <div class="card-body">
            <form action="{{route('transaction.update')}}" method="post">
                @csrf
                @method('PUT')
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>COA</th>
                        <th>Description</th>
                    </tr>
                    @foreach ($transactionsInRecord as $transaction)
                    <input type="hidden" name="transactions[{{($loop->iteration)-1}}][id]" value="{{$transaction->id}}">
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <input type="number" name="transactions[{{($loop->iteration)-1 }}][amount]"
                                    value="{{ $transaction->amount }}">
                            </td>
                            <td>

                                <select name="transactions[{{($loop->iteration) - 1 }}][COA]">
                                    <option value="{{ $transaction->COA }}" selected>{{ $transaction->COA }}</option>
                                    <option value="Utilities">Utilities</option>
                                    <option value="Supplies">Supplies</option>
                                    <option value="Travel">Travel</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Investments">Investments</option>
                                    <option value="Accounts Payable">Accounts Payable</option>
                                    <option value="Loans">Loans</option>
                                    <option value="Retained Earnings">Retained Earnings</option>
                                    <option value="Donor Restricted">Donor Restricted</option>
                                    <option value="Board Designated">Board Designated</option>
                                    <option value="General">General</option>
                                    <option value="Program Income">Program Income</option>
                                    <option value="Other Income">Other Income</option>
                                    <option value="Program Expenses">Program Expenses</option>
                                    <option value="Administrative Expenses">Administrative Expenses</option>

                                </select>
                            </td>
                            <td>
                                <textarea name="transactions[{{($loop->iteration) - 1 }}][desc]" cols="7" rows="3">{{ $transaction->desc }}</textarea>
                            </td>
                        </tr>
                    @endforeach



                </table>
                <button class="btn btn-success">Save edits</button>
                @include('partials.goback')
            </form>
        </div>
    </div>
@endsection

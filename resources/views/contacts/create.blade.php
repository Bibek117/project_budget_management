@extends('layouts.dashboardLayout')
@section('content')
    {{-- @php
        dd($projects->timeline[0]->contacts);
    @endphp --}}
    <h3 class="text-center">Create contact type</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('contacttype.store') }}" method="post">
        @csrf
        <div id="formWrapper">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Contact type title</label>
                        <input type="text" class="form-control" name="contacttypes[0][name]" placeholder="Enter title">
                    </div>
                </div>
            </div>
        </div>
        <button type="button" id="add-contact-btn" class="btn btn-success">Add More Field</button>
        <button type="submit" class="btn btn-primary">Submit</button>
        <button class="btn btn-secondary"><a class="text-white" href="{{ url()->previous() }}">Go back</a></button>
    </form>
    @push('other-scripts')
        <script>
            $(document).ready(function() {
                //add multiple field
                var contactIndex = 0;

                $('#add-contact-btn').click(function() {
                    contactIndex++;
                    var contactHtml = `
            <div class="card mb-4" id="contact_${contactIndex}">
                <div class=" justify-content-around card-body">
                    <div class="form-group">
                        <label for="title_${contactIndex}">Contact Type title</label>
                        <input type="text" class="form-control" id="title_${contactIndex}" name="contacttypes[${contactIndex}][name]" placeholder="Enter title">
                    </div>
                    <div>
                        <button class="btn btn-danger remove-contact-btn" data-contact-index="${contactIndex}"><i class="bi bi-trash3"></i></button>
                    </div>
                </div>
            </div>
        `;
                    $('#formWrapper').append(contactHtml);
                });

                // Remove contact card when trash button is clicked
                $(document).on('click', '.remove-contact-btn', function() {
                    var contactIndexToRemove = $(this).data('contact-index');
                    $('#contact_' + contactIndexToRemove).remove();
                });

            });
        </script>
    @endpush
@endsection

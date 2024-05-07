@extends('layouts.dashboardLayout')
@section('content')
    {{ Breadcrumbs::render('report.ageingReportCreate') }}
    <div class="card border border-3 border-success w-50">
        <div class="card-header">
            <h3>Ageing Report</h3>
        </div>
        <div class="card-body">
            <form id="ageingReportForm" action="{{route('report.ageingReport')}}" method="post">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="interval">Number of Intervals <span class="text-danger">*</span></label>
                        <input type="number" name="interval" class="form-control" id="interval" placeholder="i.e 4">
                        @error('interval')
                          <p class="text-sm text-danger">{{$message}}</p>    
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="perDays">Days per each Interval  <span class="text-danger">*</span></label>
                        <input type="number" name="perDays" class="form-control" id="perDays" placeholder="i.e 10">
                         @error('perDays')
                          <p class="text-sm text-danger">{{$message}}</p>    
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="untilDate">End Date  <span class="text-danger">*</span></label>
                    <input type="date" max={{date('Y-m-d')}} class="form-control" id="untilDate" name="untilDate">
                     @error('untilDate')
                          <p class="text-sm text-danger">{{$message}}</p>    
                        @enderror
                </div>
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </form>
        </div>
    </div>
@endsection
@push('other-scripts')
  <script>
     $(document).ready(function(){

        
        $.ajax({

        })
     })
    </script>    
@endpush

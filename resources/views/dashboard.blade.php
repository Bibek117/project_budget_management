@extends('layouts.dashboardLayout')
@section('content')
    {{ Breadcrumbs::render('dashboard') }}
    @if (auth()->user()->hasRole('Admin'))
        <div class="container-fluid">
            <div class="row mb-3">
                @include('partials._statCard',['color'=>'success','text'=>'Users','stats'=>$totalUsers[0]->count,'route'=>route('user.index')])
               @include('partials._statCard',['color'=>'danger','text'=>'Projects','stats'=>$totalProjects[0]->count,'route'=>route('project.index')])
               @include('partials._statCard',['color'=>'info','text'=>'Transactions','stats'=>$totalTransactions[0]->count,'route'=>route('record.index')])
            </div>
        </div>
    @else
        <h2 class="text-center">Projects For You</h2>
        <div class="row mb-3">
            @forelse (auth()->user()->projects as $project)
                <div class="col-xl-3 col-sm-6 py-2">
                    <div class="card text-white bg-success h-100">
                        <div class="card-body bg-success">
                            <h2 class="text-uppercase">{{ $project->title }}</h2>
                            <button class="btn btn-warning"><a class="text-white"
                                    href="{{ route('project.show', $project->id) }}">Go to project</a></button>
                        </div>
                    </div>
                </div>
            @empty
                <p>No projects assigned for you</p>
            @endforelse
        </div>
    @endif
@endsection

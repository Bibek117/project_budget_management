@extends('layouts.dashboardLayout')
@section('content')
    @if (session('success'))
      @include('partials._successToast',['message'=>session('success')])    
    @endif
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
              @include('partials._statCard',['color'=>'success','text'=>$project->title,'route'=>route('project.show',$product->id)])
            @empty
                <p>No projects assigned for you</p>
            @endforelse
        </div>
    @endif
@endsection

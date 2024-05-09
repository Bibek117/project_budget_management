@extends('layouts.dashboardLayout')
@section('content')
    @if (session('success'))
        @include('partials._successToast', ['message' => session('success')])
    @endif
    {{ Breadcrumbs::render('dashboard') }}
    @if (auth()->user()->hasRole('Admin'))
        <div class="container-fluid">
            <div class="row mb-3">
                @include('partials._statCard', [
                    'color' => 'success',
                    'text' => 'Users',
                    'stats' => $totalUsers[0]->count,
                    'route' => route('user.index'),
                ])
                @include('partials._statCard', [
                    'color' => 'danger',
                    'text' => 'Projects',
                    'stats' => $totalProjects[0]->count,
                    'route' => route('project.index'),
                ])
                @include('partials._statCard', [
                    'color' => 'info',
                    'text' => 'Transactions',
                    'stats' => $totalTransactions[0]->count,
                    'route' => route('record.index'),
                ])
            </div>
        </div>
    @else
        <h2 class="text-center">Projects For You</h2>
        <div class="row mb-3">
            @if (count(auth()->user()->projects) != 0)
                @foreach (auth()->user()->projects as $project)
                    @include('partials._statCard', [
                        'color' => 'success',
                        'text' => $project->title,
                        'stats' => '',
                        'route' => route('project.show', $project->id),
                    ])
                @endforeach
            @else
                <p>No projects assigned for you</p>
            @endif

        </div>
    @endif
@endsection

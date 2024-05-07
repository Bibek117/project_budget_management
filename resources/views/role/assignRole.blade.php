@extends('layouts.dashboardLayout')
@section('content') 
 <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
     <h1 class="text-center text-blue-600 text-[30px] mb-8 font-serif">Assign a role</h1>
     {{Breadcrumbs::render('role.assign')}}
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 mb-4 mt-5">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        #
                    </th>
                    <th scope="col" class="px-6 py-3">
                        User Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                       Assigned Roles
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($userAssociatedRoles as $userAssociate)
                    <tr class="bg-white border-b ">
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $userAssociate->username }}
                        </td>
                        <td class="px-6 py-4">
                           @if ($userAssociate->roles == "")
                            <p>No roles assigned</p>
                            @else
                            {{$userAssociate->roles}}   
                           @endif
                        </td>
                         <td class="px-6 py-4">
                            <a href="{{route('roles.editAssign',$userAssociate->id)}}" class="font-medium text-blue-500 hover:underline">Assign/Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include('partials.goback')
    </div>
@endsection
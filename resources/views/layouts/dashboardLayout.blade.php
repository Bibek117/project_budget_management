 <!doctype html>
 <html class="h-full bg-white">

 <head>
     <!-- ... --->
     <meta charset="UTF-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <link href="{{ asset('css/app.css') }}" rel="stylesheet">

     {{-- boostrap cdn --}}
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
         integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
     {{-- icons --}}
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
     <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css" />
     <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">

     {{-- multiple select --}}
     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



     {{-- fonts --}}
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link
         href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
         rel="stylesheet">
     <style>
         * {
             font-family: "Roboto", sans-serif;
         }

         .activeSection {
             color: white;
             background-color: rgb(49, 189, 49);
         }

         div.dt-container select.dt-input {
             width: 60px;
         }

         select[multiple] {
             height: 200px;
         }

         select[multiple] option:checked {
             background-color: #d6f7e1;
             border-color: #48bb78;
             padding: 2px;
         }
     </style>

 </head>
 <!-- ... --->

 <body>
     <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
         <div class="px-3 py-2 lg:px-5 lg:pl-3">
             <div class="flex items-center justify-between">
                 <div class="flex items-center justify-content-around rtl:justify-end">

                     <a href="#" class="flex ms-2 md:me-24 hover:no-underline">
                         <span class="self-center text-xl  sm:text-2xl whitespace-nowrap">Manage</span>
                     </a>
                 </div>
                 <div class="nav-item dropdown no-arrow">
                     <a class="nav-link dropdown-toggle text-dark" href="#" id="userDropdown" role="button"
                         data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         Hello {{ Auth::user()->username ?? 'Unknown user' }} |
                         <i class="bi bi-person-circle text-dark ml-2 text-[20px]"></i>
                     </a>
                     <!-- Dropdown - User Information -->
                     <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                         aria-labelledby="userDropdown">
                         <a class="dropdown-item" href="#">
                             <i class="bi bi-person-circle  mr-2 text-gray-400"></i>
                             <span class="text-sm">Profile</span>
                         </a>
                         <a class="dropdown-item" href="#">
                             <i class="bi bi-gear  mr-2 text-gray-400"></i>
                             <span class="text-sm">Settings</span>
                         </a>
                         <div class="dropdown-divider"></div>
                         <a class="dropdown-item" href="{{ route('user.logout') }}">
                             <i class="bi bi-box-arrow-right mr-2 text-gray-400"></i>
                             <span class="text-sm">Logout</span>
                         </a>
                     </div>
                 </div>
             </div>
         </div>
     </nav>

     <aside id="logo-sidebar"
         class="fixed top-0 left-0 z-40 w-63 border h-screen pt-20 transition-transform translate-x-full bg-white border-r border-gray-200 sm:translate-x-0"
         aria-label="Sidebar">
         <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
             <ul class="space-y-2 font-medium">
                 <li>
                     <a href={{ route('dashboard') }}
                         class="flex text-[14px] items-center p-2 text-gray-900 rounded-lg transform transition duration-500 hover:bg-green-100 hover:no-underline hover:text-green-500 hover:scale-110  group {{ request()->routeIs('dashboard') ? 'activeSection' : '' }}">
                         <i class="bi bi-speedometer"></i>
                         <span class="flex-1 ms-3 whitespace-nowrap">Dashboard</span>
                     </a>
                 </li>
                 @if (auth()->user()->can('view-user') || auth()->user()->can('register-user'))
                     <li>
                         <a href={{ route('user.index') }}
                             class="flex text-[14px] items-center p-2 text-gray-900 rounded-lg duration-500 hover:scale-110 hover:bg-green-100 hover:no-underline hover:text-green-500 group  {{ request()->routeIs('user.*') ? 'activeSection' : '' }}">
                             <i class="bi bi-person-circle"></i>
                             <span class="flex-1 ms-3 whitespace-nowrap">Users</span>
                         </a>
                     </li>
                 @endif
                 @if (auth()->user()->can('create-project'))
                     {{-- <div class="accordion" id="accprdinProject"> --}}
                     {{-- <li>
                    <a
                        class="flex text-[14px] items-center p-2 text-gray-900 transform transition duration-500 hover:scale-110 hover:bg-green-100 hover:no-underline hover:text-green-500 rounded-lg " data-toggle="collapse" data-target="#collapseProject" aria-expanded="true" aria-controls="collapseProject"
                       >
                        <i class="bi bi-folder-fill"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Projects</span>
                    </a>
                </li> --}}
                     {{-- </div> --}}
                     {{-- <div id="collapseProject" class="collapse show"> --}}
                     <li>
                         <a href="{{ route('project.index') }}"
                             class="flex text-[14px] items-center p-2 text-gray-900 transform transition duration-500 hover:scale-110 hover:bg-green-100 hover:no-underline hover:text-green-500 rounded-lg {{ request()->routeIs('project.*') ? 'activeSection' : '' }} ">
                             <i class="bi bi-folder-fill"></i>
                             <span class="flex-1 ms-3 whitespace-nowrap">Projects</span>
                         </a>
                     </li>
                     @can('create-timeline')
                         <li>
                             <a href={{ route('timeline.create') }}
                                 class="flex text-[14px] items-center p-2 text-gray-900 rounded-lg transform transition duration-500 hover:scale-110 hover:bg-green-100 hover:no-underline hover:text-green-500 group {{ request()->routeIs('timeline.*') ? 'activeSection' : '' }}">
                                 <i class="bi bi-calendar2-week"></i>
                                 <span class="flex-1 ms-3 whitespace-nowrap font-weight-lighter">Create
                                     Timeline</span>
                             </a>
                         </li>
                     @endcan
                     @can('create-budget')
                         <li>
                             <a href={{ route('budget.create') }}
                                 class="flex text-[14px] items-center p-2 text-gray-900 rounded-lg transform transition duration-500 hover:scale-110 hover:bg-green-100 hover:no-underline hover:text-green-500 group {{ request()->routeIs('budget.*') ? 'activeSection' : '' }}">
                                 <i class="bi bi-cash-coin"></i>
                                 <span class="flex-1 ms-3 whitespace-nowrap">Create Budget</span>
                             </a>
                         </li>
                     @endcan
                     {{-- </div> --}}
                 @endif
                 @if (auth()->user()->can('create-role') || auth()->user()->can('view-role'))
                     <li>
                         <a href={{ route('roles.index') }}
                             class="flex text-[14px] items-center p-2 text-gray-900 rounded-lg transform transition duration-500 hover:scale-110 hover:bg-green-100 hover:no-underline hover:text-green-500 group {{ request()->routeIs('roles.*') ? 'activeSection' : '' }}">
                             <i class="bi bi-person-fill-gear"></i>
                             <span class="flex-1 ms-3 whitespace-nowrap">Roles</span>
                         </a>
                     </li>
                 @endif
                 @can('create-contacttype')
                     <li>
                         <a href={{ route('contacttype.index') }}
                             class="flex text-[14px] items-center p-2 text-gray-900 rounded-lg transform transition duration-500 hover:scale-110 hover:bg-green-100 hover:no-underline hover:text-green-500 group {{ request()->routeIs('contacttype.*') ? 'activeSection' : '' }}">
                             <i class="bi bi-person-lines-fill"></i>
                             <span class="flex-1 ms-3 whitespace-nowrap">Contact Types</span>
                         </a>
                     </li>
                 @endcan

                 {{-- TODO  --}}
                 <li>
                     <a href="{{ route('report.index') }}"
                         class="flex text-[14px] items-center p-2 text-gray-900 rounded-lg transform transition duration-500 hover:scale-110 hover:bg-green-100 hover:no-underline hover:text-green-500 group {{ request()->routeIs('report.*') ? 'activeSection' : '' }}">
                         <i class="bi bi-file-earmark-bar-graph"></i>
                         <span class="flex-1 ms-3 whitespace-nowrap">Report</span>
                     </a>
                 </li>

                 @if (auth()->user()->can('view-transaction') || auth()->user()->can('create-transaction'))
                     <li>
                         <a href="{{ route('record.index') }}"
                             class="flex text-[14px] items-center p-2 text-gray-900 rounded-lg transform transition duration-500 hover:scale-110 hover:bg-green-100 hover:no-underline hover:text-green-500 group {{ request()->routeIs('record.*') ? 'activeSection' : '' }}">
                             <i class="bi bi-receipt-cutoff"></i>
                             <span class="flex-1 ms-3 whitespace-nowrap">Records</span>
                         </a>
                     </li>
                 @endif
             </ul>
         </div>
     </aside>

     <div class="pt-4 mt-[65px] ml-[190px] mr-2">
         @yield('content')
     </div>

     {{-- bootstrap --}}
     <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
         integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
     </script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"
         integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous">
     </script>

     {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
     <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>

     <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
     @stack('other-scripts')
       <script>
         $(document).ready(function() {
             $('.toast').toast('show');
         })
     </script>
 </body>

 </html>

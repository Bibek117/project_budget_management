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
     <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">


     {{-- fonts --}}
   <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
     <style>
         * {
             font-family: "Roboto", sans-serif;
         }
     </style>


     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
       <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
 </head>
 <!-- ... --->

 <body>
     <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 ">
         <div class="px-3 py-3 lg:px-5 lg:pl-3">
             <div class="flex items-center justify-between">
                 <div class="flex items-center justify-start rtl:justify-end">
                     <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                         aria-controls="logo-sidebar" type="button"
                         class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                         <span class="sr-only">Open sidebar</span>
                         <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                             <path clip-rule="evenodd" fill-rule="evenodd"
                                 d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                             </path>
                         </svg>
                     </button>
                     <a href="https://flowbite.com" class="flex ms-2 md:me-24">
                         <img src="https://flowbite.com/docs/images/logo.svg" class="h-8 me-3" alt="FlowBite Logo" />
                         <span class="self-center text-xl  sm:text-2xl whitespace-nowra font-mono">Manage</span>
                     </a>
                 </div>

             </div>
     </nav>

     <aside id="logo-sidebar"
         class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0"
         aria-label="Sidebar">
         <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
             <ul class="space-y-2 font-medium">
                 <li">
                     <a href={{ route('dashbaord') }}
                         class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                         <i class="bi bi-speedometer"></i>
                         <span class="flex-1 ms-3 whitespace-nowrap">Dashboard</span>
                     </a>
                     </li>

                     <li>
                         <a href={{ route('user.index') }}
                             class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100 group">
                             <i class="bi bi-person-circle"></i>
                             <span class="flex-1 ms-3 whitespace-nowrap">Users</span>
                         </a>
                     </li>
                     <li>
                         <a href="{{ route('project.index') }}"
                             class="d-flex align-items-center p-2 text-dark hover:bg-gray-100 rounded-lg ">
                             <i class="bi bi-folder-fill"></i>
                             <span class="flex-1 ms-3 whitespace-nowrap">Projects</span>
                         </a>
                     </li>
                     {{-- @yield('budget_timeline_create') --}}
                     <li>
                         <a href={{ route('timeline.create') }}
                             class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100  group">
                             <i class="bi bi-calendar2-week"></i>
                             <span class="flex-1 ms-3 whitespace-nowrap font-weight-lighter">Create Timeline</span>
                         </a>
                     </li>
                     <li>
                         <a href={{ route('budget.create') }}
                             class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100  group">
                             <i class="bi bi-cash-coin"></i>
                             <span class="flex-1 ms-3 whitespace-nowrap">Create Budget</span>
                         </a>
                     </li>
                     <li>
                         <a href={{ route('roles.index') }}
                             class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100  group">
                             <i class="bi bi-person-fill-gear"></i>
                             <span class="flex-1 ms-3 whitespace-nowrap">Roles</span>
                         </a>
                     </li>
                     <li>
                         <a href={{ route('contacttype.index') }}
                             class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100  group">
                             <i class="bi bi-person-lines-fill"></i>
                             <span class="flex-1 ms-3 whitespace-nowrap">Contact Types</span>
                         </a>
                     </li>
                     <li>
                         <a href=""
                             class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100  group">
                             <i class="bi bi-file-earmark-bar-graph"></i>
                             <span class="flex-1 ms-3 whitespace-nowrap">Report</span>
                         </a>
                     </li>
                     <li>
                         <a href="{{ route('transaction.index') }}"
                             class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100  group">
                             <i class="bi bi-receipt-cutoff"></i>
                             <span class="flex-1 ms-3 whitespace-nowrap">Records</span>
                         </a>
                     </li>
                     <li>
                         <a href={{ route('logout') }}
                             class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100  group">
                             <i class="bi bi-box-arrow-right"></i>
                             <span class="flex-1 ms-3 whitespace-nowrap">Logout</span>
                         </a>
                     </li>
             </ul>
         </div>
     </aside>

     <div class="p-4 mt-[65px] sm:ml-64">
         @yield('content')
     </div>
     {{-- bootstrap --}}
     <script src="https://code.jquery.com/jquery-3.1.1.min.js">
         < script src = "https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
         integrity = "sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
         crossorigin = "anonymous" >
     </script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
         integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
     </script>

     @stack('other-scripts')
 </body>

 </html>

 
 <!doctype html>
 <html class="h-full bg-white" >
  <head>
    <!-- ... --->
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  </head>
  <!-- ... --->
<body>
  @php
    $routeName = request()->route()->getName();
  @endphp
   <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    {{-- <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company"> --}}
    <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
      @if ($routeName == 'login')
        Login to the System
      @endif
    </h2>
  </div>
  @yield('login_form')
</div>
</body>
</html>
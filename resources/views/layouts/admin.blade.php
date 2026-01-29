<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/x-icon" href="{{ asset('images/rapidplast.ico') }}">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Inter', sans-serif; }
    .plastic-red {
        background: linear-gradient(145deg, #ef4444, #b91c1c);
        box-shadow: 
            inset 0 1px 0 rgba(255,255,255,0.4), 
            inset 0 -1px 0 rgba(0,0,0,0.1),    
            0 4px 6px -1px rgba(185, 28, 28, 0.5); 
    }
    
    .plastic-btn {
        transition: all 0.2s ease;
        box-shadow: 
            inset 0 1px 0 rgba(255,255,255,0.3),
            0 2px 4px rgba(0,0,0,0.1);
    }
    .plastic-btn:active {
        transform: translateY(1px);
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body class="bg-gray-100 antialiased text-gray-800">
  <nav class="plastic-red sticky top-0 z-50" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center">
          <a class="flex-shrink-0 text-white font-bold text-xl tracking-wide drop-shadow-md flex items-center gap-2" href="{{ route('admin.dashboard') }}">
            <svg class="w-6 h-6 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            Admin WI
          </a>
          <div class="hidden md:block">
            <div class="ml-10 flex items-baseline space-x-4">
              <a href="{{ route('admin.wi.index') }}" class="text-white hover:bg-white/20 hover:text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 border border-transparent hover:border-white/10 shadow-sm">
                Work Instructions
              </a>
            </div>
          </div>
        </div>
        <div class="hidden md:block">
          <div class="ml-4 flex items-center md:ml-6 gap-4">
            @auth
              <span class="text-white font-medium text-sm drop-shadow-sm">
                {{ auth()->user()->name }}
              </span>
              <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button class="plastic-btn bg-white text-red-700 hover:bg-red-50 px-4 py-1.5 rounded-full text-sm font-bold border border-red-100">
                  Logout
                </button>
              </form>
            @endauth
          </div>
        </div>
        <div class="-mr-2 flex md:hidden">
          <button @click="open = !open" type="button" class="bg-red-800 inline-flex items-center justify-center p-2 rounded-md text-red-100 hover:text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-800 focus:ring-white shadow-inner" aria-controls="mobile-menu" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg x-show="!open" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg x-show="open" x-cloak class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>
    <div x-show="open" x-cloak class="md:hidden bg-red-800 shadow-inner border-t border-red-700" id="mobile-menu">
      <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
        <a href="{{ route('admin.wi.index') }}" class="text-gray-100 hover:bg-red-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
            Work Instructions
        </a>
      </div>
      @auth
      <div class="pt-4 pb-4 border-t border-red-700">
        <div class="flex items-center px-5">
          <div class="ml-3">
            <div class="text-base font-medium leading-none text-white">{{ auth()->user()->name }}</div>
            <div class="text-sm font-medium leading-none text-red-200 mt-1">{{ auth()->user()->email ?? '' }}</div>
          </div>
        </div>
        <div class="mt-3 px-2 space-y-1">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-gray-100 hover:text-white hover:bg-red-700">
                  Logout
                </button>
            </form>
        </div>
      </div>
      @endauth
    </div>
  </nav>
  <main class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
            <div class="p-6 bg-white border-b border-gray-200">
                @yield('content')
            </div>
        </div>
    </div>
  </main>
  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
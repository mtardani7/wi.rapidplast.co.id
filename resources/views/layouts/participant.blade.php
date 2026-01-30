<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/x-icon" href="{{ asset('images/rapidplast.ico') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              50: '#fef2f2',
              100: '#fee2e2',
              200: '#fecaca',
              300: '#fca5a5',
              400: '#f87171',
              500: '#ef4444',
              600: '#dc2626',
              700: '#b91c1c',
              800: '#991b1b',
              900: '#7f1d1d',
            }
          },
          fontFamily: {
            'sans': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif'],
          },
          animation: {
            'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            'bounce-slow': 'bounce 2s infinite',
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <style>
    .navbar-shadow {
      box-shadow: 0 2px 10px rgba(185, 28, 28, 0.08);
    }
    
    .badge-pulse {
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0%, 100% {
        opacity: 1;
      }
      50% {
        opacity: 0.7;
      }
    }
    
    .glass-effect {
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.95);
    }
  </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-primary-50 min-h-screen font-sans">
  <nav class="glass-effect navbar-shadow sticky top-0 z-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex items-center">
          <a href="{{ route('wi.index') }}" class="flex items-center space-x-3 group">
            <div class="relative">
              <div class="rounded-lg flex items-center transition-all duration-300 transform group-hover:scale-105">
                <img src="{{ asset('images/logo3.png') }}" alt="Rapid Plast Icon" class="w-13 h-12"/>
              </div>
            </div>
          </a>
        </div>
        <div class="flex items-center space-x-4">
          @if(session('participant_nik'))
            <div class="relative group">
              <div class="flex items-center space-x-2 px-4 py-2 rounded-full bg-gradient-to-r from-primary-50 to-primary-100 border border-primary-200 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-white border border-primary-300 flex items-center justify-center">
                  <i class="fas fa-id-card text-primary-600 text-sm"></i>
                </div>
                <div class="flex flex-col">
                  <span class="text-xs text-gray-500 font-medium">Participant NIK</span>
                  <span class="text-sm font-bold text-primary-800 tracking-wider">
                    {{ session('participant_nik') }}
                  </span>
                </div>
                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
              </div>
              <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 p-3 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                <div class="flex items-center space-x-2 mb-2">
                  <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
                    <i class="fas fa-user text-primary-600"></i>
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-800">Participant Mode</p>
                    <p class="text-xs text-gray-500">Akses instruksi kerja</p>
                  </div>
                </div>
                <div class="text-xs text-gray-600">
                  Anda dapat mengakses semua instruksi kerja yang tersedia dengan NIK ini.
                </div>
              </div>
            </div>
            <form method="POST" action="{{ route('participant.logout') }}" class="m-0">
              @csrf
              <button type="submit" class="relative flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium text-white bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 group">
                <i class="fas fa-power-off"></i>
                <span>Logout</span>
                <span class="absolute -top-1 -right-1">
                  <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
              </button>
            </form>
            <button type="button" class="md:hidden flex items-center justify-center w-10 h-10 rounded-lg text-gray-600 hover:bg-primary-50 hover:text-primary-700 transition-colors duration-200">
              <i class="fas fa-ellipsis-v text-lg"></i>
            </button>
          @else
            <div class="flex items-center space-x-2 px-4 py-2 rounded-lg bg-yellow-50 border border-yellow-200">
              <i class="fas fa-exclamation-triangle text-yellow-500"></i>
              <span class="text-sm font-medium text-yellow-700">NIK belum dimasukkan</span>
            </div>
          @endif
        </div>
      </div>
      @if(session('participant_nik'))
        <div class="md:hidden border-t border-gray-100 mt-2 pt-3 pb-2">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center justify-between px-2 py-2 rounded-lg bg-primary-50">
              <div class="flex items-center space-x-2">
                <i class="fas fa-id-card text-primary-600"></i>
                <span class="text-sm font-medium text-gray-700">NIK:</span>
                <span class="font-bold text-primary-800">{{ session('participant_nik') }}</span>
              </div>
              <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
            </div>
            
            <form method="POST" action="{{ route('participant.logout') }}" class="w-full">
              @csrf
              <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium text-white bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 shadow">\
                <i class="fas fa-power-off"></i>
                <span>Logout</span>
              </button>
            </form>
          </div>
        </div>
      @endif
    </div>
  </nav>

  <main class="py-6">
    @yield('content')
  </main>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const mobileMenuButton = document.querySelector('button[class*="md:hidden"]:not(form button)');
      const mobileMenu = document.querySelector('.md\\:hidden.border-t');
      
      if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
          mobileMenu.classList.toggle('hidden');
          const icon = this.querySelector('i');
          if (mobileMenu.classList.contains('hidden')) {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-ellipsis-v');
          } else {
            icon.classList.remove('fa-ellipsis-v');
            icon.classList.add('fa-times');
          }
        });
      }
      const navItems = document.querySelectorAll('nav > div > div > *');
      navItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
          item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
          item.style.opacity = '1';
          item.style.transform = 'translateY(0)';
        }, 100 + (index * 100));
      });

      const changeNikButton = document.querySelector('form[action*="logout"] button');
      if (changeNikButton) {
        changeNikButton.addEventListener('mouseenter', function() {
          const icon = this.querySelector('i');
          icon.style.transform = 'rotate(180deg)';
          icon.style.transition = 'transform 0.3s ease';
        });
        
        changeNikButton.addEventListener('mouseleave', function() {
          const icon = this.querySelector('i');
          icon.style.transform = 'rotate(0deg)';
        });
      }
    });
  </script>
</body>
</html>
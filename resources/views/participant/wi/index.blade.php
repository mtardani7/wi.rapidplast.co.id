@extends('layouts.participant')
@section('title', 'Work Instruction')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-primary-50 py-6 px-4 sm:px-6 lg:px-8">
  <div class="max-w-7xl mx-auto">
    
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">
          Work Instruction System
        </h1>
        <p class="text-gray-600 text-sm sm:text-base">
          Pilih materi untuk mulai belajar dan meningkatkan kompetensi
        </p>
        <div class="mt-2 flex items-center space-x-2">
          <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
          <span class="text-xs text-gray-500 font-medium">Live - PT. Rapid Plast Indonesia</span>
        </div>
      </div>

      <form method="POST" action="{{ route('participant.logout') }}">
        @csrf
        <button type="submit" 
                class="group relative flex items-center space-x-2 px-4 py-2.5 rounded-lg text-sm font-medium text-white bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
          <i class="fas fa-exchange-alt text-sm"></i>
          <span>Ganti NIK</span>
          <span class="absolute -top-1 -right-1">
            <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
          </span>
        </button>
      </form>
    </div>

    {{-- Info Peserta Card --}}
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl shadow-lg mb-8 overflow-hidden">
      <div class="p-5">
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
              <i class="fas fa-user-graduate text-white text-xl"></i>
            </div>
            <div>
              <h3 class="text-lg font-bold text-white">Informasi Peserta</h3>
              <p class="text-primary-100 text-sm">Detail akun pembelajaran Anda</p>
            </div>
          </div>
          <div class="hidden sm:block">
            <div class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm">
              <span class="text-xs font-medium text-white">Active Session</span>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
          <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center">
                <i class="fas fa-id-card text-primary-600"></i>
              </div>
              <div>
                <p class="text-xs text-primary-100 font-medium">NIK Aktif</p>
                <p class="text-white font-bold text-lg">{{ session('participant_nik') ?? '-' }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center">
                <i class="fas fa-user-tag text-primary-600"></i>
              </div>
              <div>
                <p class="text-xs text-primary-100 font-medium">Nama Peserta</p>
                <p class="text-white font-bold text-lg">{{ session('participant_name') ?? '-' }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center">
                <i class="fas fa-calendar-alt text-primary-600"></i>
              </div>
              <div>
                <p class="text-xs text-primary-100 font-medium">Plan Training</p>
                <p class="text-white font-bold text-lg">{{ session('participant_plan') ?? '-' }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Header List WI --}}
    <div class="flex items-center justify-between mb-6">
      <div>
        <h2 class="text-xl font-bold text-gray-800">Available Materials</h2>
        <p class="text-gray-600 text-sm">Work Instruction yang dapat Anda pelajari</p>
      </div>
      <div class="text-sm text-gray-500">
        Total: <span class="font-bold text-primary-700">{{ count($wis) }} Work Instruction</span>
      </div>
    </div>

    {{-- List WI --}}
    @if(count($wis) > 0)
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($wis as $wi)
          @php
            $videos = $wi->videos ?? collect();
            $firstVideo = $videos->first();
            $totalVideo = $videos->count();

            $completedCount = 0;
            foreach($videos as $vv){
              $p = $progressMap[$vv->id] ?? null;
              if($p && $p->completed_at) $completedCount++;
            }

            $percent = $totalVideo > 0 ? round(($completedCount / $totalVideo) * 100) : 0;
            
            // Warna progress bar berdasarkan persentase
            $progressColor = $percent == 100 ? 'bg-green-500' : 
                            ($percent >= 50 ? 'bg-yellow-500' : 'bg-primary-500');
          @endphp

          <div class="group bg-white rounded-xl shadow-md hover:shadow-xl border border-gray-100 overflow-hidden transition-all duration-300 transform hover:-translate-y-1">
            {{-- Card Header --}}
            <div class="p-5 border-b border-gray-100">
              <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                  <div class="flex items-center space-x-2 mb-2">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-primary-100 to-primary-50 flex items-center justify-center">
                      <i class="fas fa-file-invoice text-primary-600"></i>
                    </div>
                    <div>
                      <span class="text-xs font-medium text-gray-500">WI-{{ str_pad($wi->id, 3, '0', STR_PAD_LEFT) }}</span>
                    </div>
                  </div>
                  
                  <h3 class="font-bold text-gray-900 text-lg group-hover:text-primary-700 transition-colors duration-200 line-clamp-2">
                    {{ $wi->title }}
                  </h3>
                  
                  <p class="text-gray-600 text-sm mt-2 line-clamp-2">
                    {{ $wi->description ?? 'Tidak ada deskripsi' }}
                  </p>
                </div>
              </div>

              {{-- Tags --}}
              <div class="flex flex-wrap gap-2 mt-3">
                <span class="px-2 py-1 bg-primary-50 text-primary-700 text-xs font-medium rounded-full">
                  <i class="fas fa-video mr-1"></i> {{ $totalVideo }} Video
                </span>
                <span class="px-2 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full">
                  <i class="fas fa-check-circle mr-1"></i> {{ $completedCount }} Selesai
                </span>
              </div>
            </div>

            {{-- Progress Section --}}
            <div class="p-5 border-b border-gray-100">
              <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-700">Progress Pembelajaran</span>
                <span class="text-sm font-bold {{ $percent == 100 ? 'text-green-600' : 'text-primary-600' }}">
                  {{ $percent }}%
                </span>
              </div>
              
              <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="h-2.5 rounded-full {{ $progressColor }} transition-all duration-500" 
                     style="width: {{ $percent }}%"></div>
              </div>
              
              <div class="flex justify-between mt-2">
                <span class="text-xs text-gray-500">{{ $completedCount }} dari {{ $totalVideo }} video</span>
                <span class="text-xs font-medium {{ $percent == 100 ? 'text-green-600' : 'text-gray-600' }}">
                  @if($percent == 100)
                    <i class="fas fa-trophy mr-1"></i> Lengkap
                  @elseif($percent > 0)
                    <i class="fas fa-spinner mr-1 animate-spin"></i> Dalam Proses
                  @else
                    <i class="fas fa-clock mr-1"></i> Belum Dimulai
                  @endif
                </span>
              </div>
            </div>

            {{-- Action Button --}}
            <div class="p-5">
              @if($firstVideo)
                <a href="{{ route('wi.video.play', $firstVideo->id) }}"
                   class="group/btn w-full flex items-center justify-center space-x-2 px-4 py-3 rounded-lg text-white font-medium bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                  @if($percent > 0)
                    <i class="fas fa-play-circle"></i>
                    <span>{{ $percent == 100 ? 'Tinjau Kembali' : 'Lanjutkan Belajar' }}</span>
                  @else
                    <i class="fas fa-play"></i>
                    <span>Mulai Belajar</span>
                  @endif
                  <i class="fas fa-arrow-right group-hover/btn:translate-x-1 transition-transform duration-200"></i>
                </a>
              @else
                <button class="w-full flex items-center justify-center space-x-2 px-4 py-3 rounded-lg text-gray-400 font-medium bg-gray-100 cursor-not-allowed" disabled>
                  <i class="fas fa-exclamation-circle"></i>
                  <span>Belum ada video</span>
                </button>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    @else
      {{-- Empty State --}}
      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center">
        <div class="max-w-md mx-auto">
          <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-r from-gray-100 to-gray-200 flex items-center justify-center mb-6">
            <i class="fas fa-file-alt text-gray-400 text-3xl"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-800 mb-2">Belum ada Work Instruction</h3>
          <p class="text-gray-600 mb-6">
            Saat ini belum ada materi work instruction yang tersedia untuk dipelajari.
          </p>
          <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center justify-center space-x-2">
              <i class="fas fa-info-circle text-yellow-500"></i>
              <span class="text-yellow-700 text-sm">Silakan hubungi supervisor untuk informasi lebih lanjut</span>
            </div>
          </div>
        </div>
      </div>
    @endif

    {{-- Stats Footer --}}
    <div class="mt-10 pt-6 border-t border-gray-200">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-lg bg-primary-50 flex items-center justify-center">
              <i class="fas fa-book-open text-primary-600 text-xl"></i>
            </div>
            <div>
              <p class="text-sm text-gray-500">Total Dipelajari</p>
              <p class="text-2xl font-bold text-gray-900">
                {{ $completedCountTotal ?? 0 }} <span class="text-lg text-gray-500">video</span>
              </p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
              <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div>
              <p class="text-sm text-gray-500">WI Terselesaikan</p>
              <p class="text-2xl font-bold text-gray-900">
                {{ $completedWI ?? 0 }} <span class="text-lg text-gray-500">dari {{ count($wis) }}</span>
              </p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
              <i class="fas fa-clock text-blue-600 text-xl"></i>
            </div>
            <div>
              <p class="text-sm text-gray-500">Rata-rata Waktu</p>
              <p class="text-2xl font-bold text-gray-900">
                15 <span class="text-lg text-gray-500">menit/wi</span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<style>
  /* Custom styles */
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  
  .glass-effect {
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.7);
  }
  
  .progress-bar-gradient {
    background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
  }
  
  /* Smooth transitions */
  .transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
  }
  
  /* Card hover effects */
  .group:hover .group-hover\:text-primary-700 {
    color: #b91c1c;
  }
  
  /* Custom scrollbar */
  ::-webkit-scrollbar {
    width: 8px;
    height: 8px;
  }
  
  ::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }
  
  ::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #ef4444, #dc2626);
    border-radius: 4px;
  }
  
  ::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #dc2626, #b91c1c);
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Animasi saat card muncul
    const cards = document.querySelectorAll('.group.bg-white');
    cards.forEach((card, index) => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      
      setTimeout(() => {
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
      }, 100 + (index * 100));
    });
    
    // Efek hover pada progress bar
    const progressBars = document.querySelectorAll('.h-2\\.5.rounded-full');
    progressBars.forEach(bar => {
      bar.addEventListener('mouseenter', function() {
        this.style.transform = 'scaleY(1.5)';
        this.style.transition = 'transform 0.2s ease';
      });
      
      bar.addEventListener('mouseleave', function() {
        this.style.transform = 'scaleY(1)';
      });
    });
    
    // Tambahkan tooltip untuk persentase
    const progressTexts = document.querySelectorAll('.text-sm.font-bold');
    progressTexts.forEach(text => {
      text.title = 'Persentase penyelesaian materi';
    });
  });
</script>
@endsection
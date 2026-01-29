@extends('layouts.app')
@section('title', $video->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-primary-50 py-6 px-4 sm:px-6 lg:px-8">
  <div class="max-w-7xl mx-auto">
    <div class="mb-6">
      <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-6">
        <div class="flex-1">
          <div class="flex items-center space-x-2 mb-2">
            <a href="{{ url()->previous() }}" class="text-primary-600 hover:text-primary-800">
              <i class="fas fa-arrow-left"></i>
            </a>
            <span class="text-sm text-gray-500">Kembali ke materi</span>
          </div>
          
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">{{ $video->title }}</h1>
              <div class="flex flex-wrap gap-2 mb-3">
                <span class="px-3 py-1 bg-primary-100 text-primary-800 text-xs font-medium rounded-full">
                  <i class="fas fa-play-circle mr-1"></i> Video Materi
                </span>
                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                  <i class="fas fa-clock mr-1"></i> {{ gmdate("i:s", $video->duration_seconds ?? 0) }}
                </span>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                  <i class="fas fa-layer-group mr-1"></i> {{ $video->workInstruction->title ?? 'Work Instruction' }}
                </span>
              </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-3 border border-gray-200">
              <div class="text-center">
                <div class="text-xs text-gray-500 mb-1">Progress Terakhir</div>
                <div class="text-lg font-bold text-primary-700">
                  {{ floor(($progress->last_time_seconds ?? 0) / ($video->duration_seconds ?? 1) * 100) }}%
                </div>
                <div class="text-xs text-gray-500 mt-1">
                  {{ floor(($progress->last_time_seconds ?? 0) / 60) }}:{{ str_pad(($progress->last_time_seconds ?? 0) % 60, 2, '0', STR_PAD_LEFT) }}
                </div>
              </div>
            </div>
          </div>
          @if($video->description)
            <div class="mt-4 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
              <div class="flex items-center space-x-2 mb-2">
                <i class="fas fa-align-left text-primary-600"></i>
                <h3 class="font-semibold text-gray-800">Deskripsi Materi</h3>
              </div>
              <p class="text-gray-600 text-sm">{{ $video->description }}</p>
            </div>
          @endif
        </div>
      </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2">
        <div class="bg-black rounded-2xl overflow-hidden shadow-2xl relative">
          <video 
            id="player" 
            controls 
            preload="metadata" 
            class="w-full h-auto"
            poster="{{ $video->thumbnail_url ?? 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80' }}"
          >
            <source src="{{ $video->video_url }}" type="video/mp4">
            Your browser does not support the video tag.
          </video>
          <div class="absolute bottom-4 left-4 right-4 bg-gradient-to-t from-black/80 to-transparent p-4">
            <div class="flex items-center justify-between">
              <div class="text-white text-sm">
                <span class="font-medium">PT. Rapid Plast Indonesia</span>
                <span class="mx-2">•</span>
                <span>Work Instruction System</span>
              </div>
              <div class="flex items-center space-x-2">
                <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
                <span class="text-white text-xs">Sedang diputar</span>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-4 bg-white rounded-xl p-4 shadow-sm border border-gray-200">
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="text-center p-3 bg-gray-50 rounded-lg">
              <div class="text-2xl font-bold text-primary-600 mb-1">{{ count($events) }}</div>
              <div class="text-xs text-gray-600">Quiz Interaktif</div>
            </div>
            <div class="text-center p-3 bg-gray-50 rounded-lg">
              <div class="text-2xl font-bold text-green-600 mb-1">
                {{ floor(($progress->last_time_seconds ?? 0) / ($video->duration_seconds ?? 1) * 100) }}%
              </div>
              <div class="text-xs text-gray-600">Progress Anda</div>
            </div>
            <div class="text-center p-3 bg-gray-50 rounded-lg">
              <div class="text-2xl font-bold text-blue-600 mb-1">
                {{ gmdate("i", $video->duration_seconds ?? 0) }}:{{ gmdate("s", $video->duration_seconds ?? 0) }}
              </div>
              <div class="text-xs text-gray-600">Durasi Total</div>
            </div>
            <div class="text-center p-3 bg-gray-50 rounded-lg">
              <div class="text-2xl font-bold text-purple-600 mb-1">{{ $video->order ?? 1 }}</div>
              <div class="text-xs text-gray-600">Video ke-{{ $video->order ?? 1 }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 sticky top-6">
          <h3 class="font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-list-check text-primary-600 mr-2"></i>
            Timeline Materi
          </h3>
          
          {{-- Progress Bar --}}
          <div class="mb-6">
            <div class="flex justify-between text-sm mb-1">
              <span class="text-gray-600">Progress Materi</span>
              <span class="font-semibold text-primary-700">
                {{ floor(($progress->last_time_seconds ?? 0) / ($video->duration_seconds ?? 1) * 100) }}%
              </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
              <div class="bg-gradient-to-r from-primary-500 to-primary-700 h-2.5 rounded-full" 
                   style="width: {{ ($progress->last_time_seconds ?? 0) / ($video->duration_seconds ?? 1) * 100 }}%"></div>
            </div>
          </div>

          {{-- Events Timeline --}}
          <div class="space-y-4">
            <h4 class="font-semibold text-gray-700 text-sm flex items-center">
              <i class="fas fa-question-circle text-blue-600 mr-2"></i>
              Titik Kuis Interaktif
            </h4>
            
            @foreach($events as $event)
              <div class="event-item p-3 border border-gray-200 rounded-lg hover:border-primary-300 hover:bg-primary-50 cursor-pointer transition-colors duration-200"
                   data-time="{{ $event->time_seconds }}"
                   onclick="jumpToTime({{ $event->time_seconds }})">
                <div class="flex items-start space-x-3">
                  <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                      <i class="fas fa-question text-blue-600 text-sm"></i>
                    </div>
                  </div>
                  <div class="flex-1">
                    <div class="flex justify-between items-start">
                      <h5 class="font-medium text-gray-800 text-sm">{{ $event->question }}</h5>
                      <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                        {{ floor($event->time_seconds / 60) }}:{{ str_pad($event->time_seconds % 60, 2, '0', STR_PAD_LEFT) }}
                      </span>
                    </div>
                    @if(isset($progress->events[$event->id]))
                      <div class="mt-1">
                        <span class="text-xs px-2 py-1 rounded-full {{ $progress->events[$event->id]['is_correct'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                          <i class="fas fa-{{ $progress->events[$event->id]['is_correct'] ? 'check' : 'times' }} mr-1"></i>
                          {{ $progress->events[$event->id]['is_correct'] ? 'Benar' : 'Salah' }}
                        </span>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
            
            @if(count($events) === 0)
              <div class="text-center py-6 text-gray-500">
                <i class="fas fa-info-circle text-2xl mb-2"></i>
                <p class="text-sm">Tidak ada kuis interaktif dalam video ini</p>
              </div>
            @endif
          </div>

          <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex justify-between">
              @if($prevVideo)
                <a href="{{ route('wi.video.play', $prevVideo->id) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm font-medium transition-colors duration-200">
                  <i class="fas fa-arrow-left mr-2"></i>Sebelumnya
                </a>
              @else
                <div></div>
              @endif
              
              @if($nextVideo)
                <a href="{{ route('wi.video.play', $nextVideo->id) }}" 
                   class="px-4 py-2 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-lg hover:from-primary-700 hover:to-primary-800 text-sm font-medium transition-colors duration-200">
                  Selanjutnya<i class="fas fa-arrow-right ml-2"></i>
                </a>
              @else
                <a href="{{ route('wi.index') }}" 
                   class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 text-sm font-medium transition-colors duration-200">
                  Selesaikan Materi<i class="fas fa-check ml-2"></i>
                </a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="quizModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden animate-fadeIn">
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 p-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
            <i class="fas fa-brain text-white"></i>
          </div>
          <div>
            <h3 class="text-xl font-bold text-white">Kuis Interaktif</h3>
            <p class="text-primary-100 text-sm">Uji pemahaman Anda</p>
          </div>
        </div>
        <button onclick="closeQuizModal()" class="text-white hover:text-gray-200">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
    </div>

    <div class="p-6">
      <div class="mb-6">
        <div class="flex items-center space-x-2 mb-2">
          <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
            <span class="text-primary-700 font-bold">Q</span>
          </div>
          <h4 id="quizQuestion" class="text-lg font-semibold text-gray-800"></h4>
        </div>
        <div class="text-sm text-gray-600 ml-10">
          Pilih jawaban yang paling tepat untuk melanjutkan video
        </div>
      </div>

      <div id="quizOptions" class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6"></div>
      <div id="quizExplanation" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
        <div class="flex items-start space-x-3">
          <i class="fas fa-lightbulb text-blue-600 mt-1"></i>
          <div>
            <h5 class="font-medium text-blue-800 mb-1">Penjelasan</h5>
            <p id="explanationText" class="text-blue-700 text-sm"></p>
          </div>
        </div>
      </div>
      <div class="flex justify-end space-x-3">
        <button id="btnContinue" onclick="continueVideo()" 
                class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-lg hover:from-primary-700 hover:to-primary-800 font-medium transition-all duration-200 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
          <span id="continueText">Lanjutkan Video</span>
          <i class="fas fa-play ml-2"></i>
        </button>
      </div>
    </div>
  </div>
</div>

<script>
const video = document.getElementById('player');
const events = @json($events);
const shownEventIds = new Set();
let activeEvent = null;
const quizModal = document.getElementById('quizModal');
const quizQuestion = document.getElementById('quizQuestion');
const quizOptions = document.getElementById('quizOptions');
const quizExplanation = document.getElementById('quizExplanation');
const explanationText = document.getElementById('explanationText');
const btnContinue = document.getElementById('btnContinue');
const continueText = document.getElementById('continueText');

video.addEventListener('loadedmetadata', () => {
  const last = {{ $progress->last_time_seconds ?? 0 }};
  if (last > 0 && last < video.duration) {
    video.currentTime = last;
  }
});

let lastSaved = 0;
setInterval(() => {
  if (!video.paused) {
    const t = Math.floor(video.currentTime);
    if (t - lastSaved >= 5) {
      lastSaved = t;
      
      fetch("{{ route('wi.video.progress', $video->id) }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ last_time_seconds: t })
      });
    }
  }
}, 1000);

video.addEventListener('timeupdate', () => {
  const current = Math.floor(video.currentTime);
  
  for (const ev of events) {
    if (shownEventIds.has(ev.id)) continue;
    
    if (current >= ev.time_seconds) {
      shownEventIds.add(ev.id);
      showQuiz(ev);
      break;
    }
  }
});

function jumpToTime(seconds) {
  video.currentTime = seconds;
  video.play();
}

function showQuiz(ev) {
  activeEvent = ev;
  video.pause();
  
  quizQuestion.textContent = ev.question;
  quizOptions.innerHTML = '';
  quizExplanation.classList.add('hidden');
  btnContinue.disabled = true;
  continueText.textContent = 'Lanjutkan Video';
  
  const options = Array.isArray(ev.options) ? ev.options : JSON.parse(ev.options);
  
  options.forEach((opt, idx) => {
    const optionDiv = document.createElement('div');
    optionDiv.className = 'option-item p-4 border border-gray-300 rounded-lg hover:border-primary-400 hover:bg-primary-50 cursor-pointer transition-all duration-200';
    optionDiv.innerHTML = `
      <div class="flex items-center">
        <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center mr-3 option-radio">
          <div class="w-3 h-3 rounded-full bg-primary-600 hidden"></div>
        </div>
        <span class="text-gray-800">${opt}</span>
      </div>
    `;
    
    optionDiv.onclick = () => selectOption(idx, optionDiv);
    quizOptions.appendChild(optionDiv);
  });
  
  quizModal.classList.remove('hidden');
  document.body.style.overflow = 'hidden';
}

function selectOption(selectedIndex, selectedElement) {
  document.querySelectorAll('.option-item').forEach(item => {
    item.classList.remove('bg-primary-50', 'border-primary-500');
    item.querySelector('.option-radio').classList.remove('border-primary-500');
    item.querySelector('.option-radio > div').classList.add('hidden');
  });
  
  selectedElement.classList.add('bg-primary-50', 'border-primary-500');
  selectedElement.querySelector('.option-radio').classList.add('border-primary-500');
  selectedElement.querySelector('.option-radio > div').classList.remove('hidden');
  
  fetch(`/wi/video/{{ $video->id }}/event/${activeEvent.id}/answer`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": "{{ csrf_token() }}"
    },
    body: JSON.stringify({ selected_index: selectedIndex })
  })
  .then(r => r.json())
  .then(res => {
    quizExplanation.classList.remove('hidden');
    explanationText.textContent = res.explanation || (res.is_correct ? "Jawaban Anda benar! Anda dapat melanjutkan video." : "Jawaban Anda kurang tepat.");
    if (res.is_correct) {
      btnContinue.disabled = false;
      btnContinue.classList.remove('from-yellow-600', 'to-yellow-700');
      btnContinue.classList.add('from-primary-600', 'to-primary-700');
      continueText.textContent = 'Lanjutkan Video';
    } else {
      btnContinue.disabled = false;
      if (res.is_required) {
        btnContinue.classList.remove('from-primary-600', 'to-primary-700');
        btnContinue.classList.add('from-yellow-600', 'to-yellow-700');
        continueText.textContent = 'Ulangi Bagian Ini';
      } else {
        btnContinue.classList.remove('from-yellow-600', 'to-yellow-700');
        btnContinue.classList.add('from-primary-600', 'to-primary-700');
        continueText.textContent = 'Lanjutkan Video';
      }
    }
  });
}

function continueVideo() {
  if (!activeEvent) return;
  
  quizModal.classList.add('hidden');
  document.body.style.overflow = 'auto';

  fetch(`/wi/video/{{ $video->id }}/event/${activeEvent.id}/status`)
    .then(r => r.json())
    .then(res => {
      if (!res.is_correct && res.is_required) {
        const rewindTo = res.rewind_to_seconds || activeEvent.time_seconds;
        video.currentTime = rewindTo;
      }
      video.play();
    });
}

function closeQuizModal() {
  quizModal.classList.add('hidden');
  document.body.style.overflow = 'auto';
  video.play();
}

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && !quizModal.classList.contains('hidden')) {
    closeQuizModal();
  }
  
  if (e.key === ' ' && !quizModal.classList.contains('hidden')) {
    e.preventDefault();
    if (!btnContinue.disabled) {
      continueVideo();
    }
  }
});

window.addEventListener('beforeunload', () => {
  const t = Math.floor(video.currentTime);
  navigator.sendBeacon("{{ route('wi.video.progress', $video->id) }}", 
    JSON.stringify({ last_time_seconds: t }));
});
</script>

<style>
.animate-fadeIn {
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.option-item.selected {
  border-color: #ef4444;
  background-color: #fef2f2;
}

.video-controls-overlay {
  background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
}

.sticky-sidebar {
  position: sticky;
  top: 1.5rem;
}

.event-item:hover {
  transform: translateX(4px);
  transition: transform 0.2s ease;
}

#quizModal {
  animation: modalFadeIn 0.3s ease;
}

@keyframes modalFadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

#quizOptions {
  max-height: 300px;
  overflow-y: auto;
}

#quizOptions::-webkit-scrollbar {
  width: 6px;
}

#quizOptions::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

#quizOptions::-webkit-scrollbar-thumb {
  background: linear-gradient(to bottom, #ef4444, #dc2626);
  border-radius: 3px;
}
</style>
@endsection
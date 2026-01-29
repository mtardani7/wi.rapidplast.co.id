@extends('layouts.participant')
@section('title', $video->title)

@section('content')
@php
  $scoreNow = (int)($progress->score ?? 0);
  $resumeTime = (int)($progress->last_time_seconds ?? 0);
  $isPassed = $scoreNow >= 70;
@endphp

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-primary-50 py-6 px-4 sm:px-6 lg:px-8">
  <div class="max-w-6xl mx-auto">
    
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
      <div>
        <h5 class="mb-0 text-xl font-bold text-gray-900">{{ $video->title }}</h5>
        <small class="text-gray-600">{{ $video->description ?? '-' }}</small>
      </div>

      <a href="{{ route('wi.index') }}" class="inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm font-medium transition-colors duration-200">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali</span>
      </a>
    </div>

    {{-- Video --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
      <div class="p-6">
        @if($video->video_source_type === 'youtube')
          <iframe 
            id="wiVideo" 
            width="100%" 
            height="500" 
            src="https://www.youtube.com/embed/{{ $video->extractYoutubeId($video->video_url) }}" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen 
            class="rounded-lg"
          ></iframe>
        @elseif($video->video_source_type === 'vimeo')
          <iframe 
            id="wiVideo" 
            src="https://player.vimeo.com/video/{{ $video->extractVimeoId($video->video_url) }}" 
            width="100%" 
            height="500" 
            frameborder="0" 
            allow="autoplay; fullscreen; picture-in-picture" 
            allowfullscreen 
            class="rounded-lg"
          ></iframe>
        @else
          <video 
            id="wiVideo" 
            controls 
            playsinline 
            preload="metadata"
            class="w-full rounded-lg"
          >
            <source src="{{ $video->video_url }}" type="video/mp4">
            Browser kamu tidak support video.
          </video>
        @endif

        {{-- Score --}}
        <div class="mt-6">
          <div class="flex justify-between items-center mb-4">
            <div class="text-gray-600 text-sm">
              Score saat ini:
              <span id="scoreBadge" class="ml-2 px-3 py-1 bg-primary-100 text-primary-800 rounded-full font-bold">{{ $scoreNow }}</span>
            </div>

            @if($scoreNow >= 70)
              <span id="scoreStatus" class="px-3 py-1 bg-green-100 text-green-800 rounded-full font-medium">LULUS</span>
            @else
              <span id="scoreStatus" class="px-3 py-1 bg-red-100 text-red-800 rounded-full font-medium">BELUM LULUS (min 70)</span>
            @endif
          </div>

          <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
            <div 
              id="scoreBar" 
              class="h-2 rounded-full bg-primary-500 transition-all duration-300"
              style="width: {{ min($scoreNow, 100) }}%"
            ></div>
          </div>

          @if($scoreNow < 70)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
              <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                <p class="text-yellow-700 text-sm">
                  Nilai kamu masih <b>{{ $scoreNow }}</b>. Kamu harus mengulang sampai minimal <b>70</b>.
                </p>
              </div>
            </div>
          @endif

          <div class="text-gray-500 text-sm flex items-center">
            <i class="fas fa-save mr-2"></i>
            Progress tersimpan otomatis setiap 5 detik.
          </div>
        </div>
      </div>
    </div>

  </div>
</div>


{{-- ========================= --}}
{{-- MODAL QUIZ --}}
{{-- ========================= --}}
<div class="modal hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" id="quizModal">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl">
    
    <div class="border-b border-gray-200 p-6">
      <h5 class="text-lg font-bold text-gray-900">Quiz</h5>
    </div>

    <div class="p-6">
      <div id="quizQuestion" class="font-semibold text-gray-800 mb-4 text-lg">...</div>

      <div class="space-y-2 mb-6" id="quizOptions"></div>

      <div id="quizFeedback" class="mt-4 text-sm"></div>
    </div>

    <div class="border-t border-gray-200 p-6">
      <button 
        id="btnContinue" 
        type="button" 
        class="w-full px-4 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
        disabled
      >
        Lanjut Video
      </button>
    </div>

  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", async function () {
  const video = document.getElementById("wiVideo");

  const quizModalEl = document.getElementById("quizModal");
  const quizQuestion = document.getElementById("quizQuestion");
  const quizOptions = document.getElementById("quizOptions");
  const quizFeedback = document.getElementById("quizFeedback");
  const btnContinue = document.getElementById("btnContinue");

  const scoreBadge = document.getElementById("scoreBadge");
  const scoreStatus = document.getElementById("scoreStatus");
  const scoreBar = document.getElementById("scoreBar");

  const csrf = "{{ csrf_token() }}";
  const resumeTime = {{ $resumeTime }};

  let events = [];
  let triggered = new Set(); // event id yang sudah muncul
  let activeEvent = null;
  let lastAttemptCorrect = null;

  // ==========================
  // RESUME PROGRESS
  // ==========================
  video.addEventListener("loadedmetadata", function () {
    if (resumeTime > 0 && resumeTime < video.duration) {
      video.currentTime = resumeTime;
    }
  });

  // ==========================
  // LOAD EVENTS (QUIZ)
  // ==========================
  async function loadEvents() {
    const res = await fetch("{{ route('wi.video.events', $video->id) }}", {
      headers: { "Accept": "application/json" }
    });
    events = await res.json();

    // normalize options
    events = (events || []).map(e => {
      if (typeof e.options === "string") {
        try { e.options = JSON.parse(e.options); } catch (err) { e.options = []; }
      }
      if (!Array.isArray(e.options)) e.options = [];
      return e;
    });
  }

  await loadEvents();

  // ==========================
  // AUTO SAVE PROGRESS 5 detik
  // ==========================
  setInterval(async () => {
    if (!video || video.paused || video.ended) return;

    const sec = Math.floor(video.currentTime);

    try {
      await fetch("{{ route('wi.video.progress', $video->id) }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrf,
          "Accept": "application/json",
        },
        body: JSON.stringify({ last_time_seconds: sec }),
      });
    } catch (e) {
      console.log("Progress save failed", e);
    }
  }, 5000);

  // ==========================
  // OPEN QUIZ MODAL
  // ==========================
  function openQuiz(eventObj) {
    activeEvent = eventObj;
    lastAttemptCorrect = null;

    btnContinue.disabled = true;
    quizFeedback.innerHTML = "";
    quizOptions.innerHTML = "";

    quizQuestion.textContent = eventObj.question ?? "Pertanyaan";

    const opts = eventObj.options || [];

    if (opts.length === 0) {
      quizOptions.innerHTML = `<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-yellow-700 text-sm">Quiz belum punya pilihan jawaban.</div>`;
      btnContinue.disabled = false;
      return;
    }

    opts.forEach((text, idx) => {
      const btn = document.createElement("button");
      btn.type = "button";
      btn.className = "w-full text-left px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200";
      btn.textContent = text;

      btn.onclick = () => submitAnswer(idx);

      quizOptions.appendChild(btn);
    });

    quizModalEl.classList.remove("hidden");
    document.body.style.overflow = "hidden";
  }

  async function submitAnswer(selectedIndex) {
    if (!activeEvent) return;
    Array.from(quizOptions.querySelectorAll("button")).forEach(b => b.disabled = true);

    try {
      const res = await fetch(`{{ url('/wi/video') }}/{{ $video->id }}/events/${activeEvent.id}/attempt`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrf,
          "Accept": "application/json",
        },
        body: JSON.stringify({ selected_index: selectedIndex }),
      });

      const data = await res.json();

      lastAttemptCorrect = !!data.is_correct;
      if (data.score !== undefined && scoreBadge) {
        scoreBadge.textContent = data.score;
        scoreBar.style.width = Math.min(data.score, 100) + "%";

        if (parseInt(data.score) >= 70) {
          scoreStatus.className = "px-3 py-1 bg-green-100 text-green-800 rounded-full font-medium";
          scoreStatus.textContent = "LULUS";
        } else {
          scoreStatus.className = "px-3 py-1 bg-red-100 text-red-800 rounded-full font-medium";
          scoreStatus.textContent = "BELUM LULUS (min 70)";
        }
      }

      if (data.is_correct) {
        quizFeedback.innerHTML = `<div class="bg-green-50 border border-green-200 rounded-lg p-3 text-green-700">Jawaban benar ✅</div>`;
        btnContinue.disabled = false;
      } else {
        quizFeedback.innerHTML = `<div class="bg-red-50 border border-red-200 rounded-lg p-3 text-red-700">Jawaban salah ❌</div>`;

        if (data.explanation) {
          quizFeedback.innerHTML += `<div class="text-gray-600 mt-2">${data.explanation}</div>`;
        }

        if (data.rewind_to_seconds !== null && data.rewind_to_seconds !== undefined) {
          quizFeedback.innerHTML += `<div class="text-gray-600 mt-2">Video akan diulang sedikit...</div>`;
        }

        btnContinue.disabled = false;
      }

    } catch (e) {
      quizFeedback.innerHTML = `<div class="bg-red-50 border border-red-200 rounded-lg p-3 text-red-700">Gagal submit jawaban.</div>`;
      btnContinue.disabled = false;
    }
  }

  btnContinue.addEventListener("click", function () {
    quizModalEl.classList.add("hidden");
    document.body.style.overflow = "auto";

    // kalau salah + ada rewind
    if (activeEvent && lastAttemptCorrect === false) {
      const rewindTo = activeEvent.rewind_to_seconds;
      if (rewindTo !== null && rewindTo !== undefined) {
        video.currentTime = rewindTo;
      }
    }

    activeEvent = null;
    lastAttemptCorrect = null;

    video.play();
  });

  // Close modal on ESC key
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && !quizModalEl.classList.contains("hidden")) {
      quizModalEl.classList.add("hidden");
      document.body.style.overflow = "auto";
      video.play();
    }
  });

  // Check for quiz events
  setInterval(() => {
    if (video.paused || video.ended) return;
    if (!events || events.length === 0) return;
    if (activeEvent) return; // sedang quiz

    const now = Math.floor(video.currentTime);

    for (const e of events) {
      if (!triggered.has(e.id) && now >= e.time_seconds) {
        triggered.add(e.id);
        video.pause();
        openQuiz(e);
        break;
      }
    }
  }, 500);

  video.addEventListener("ended", function () {
    const score = parseInt(scoreBadge?.textContent || "0");

    if (score < 70) {
      if (confirm("Score kamu masih di bawah 70. Kamu harus mengulang video dan quiz sampai minimal 70.")) {
        video.currentTime = 0;
        video.play();
      }
    }
  });

});
</script>

<style>
  /* Smooth transitions */
  .transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
  }

  /* Custom scrollbar */
  ::-webkit-scrollbar {
    width: 8px;
  }

  ::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }

  ::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #ef4444, #dc2626);
    border-radius: 4px;
  }

  /* Modal animation */
  .modal > div {
    animation: modalIn 0.3s ease-out;
  }

  @keyframes modalIn {
    from {
      opacity: 0;
      transform: scale(0.95);
    }
    to {
      opacity: 1;
      transform: scale(1);
    }
  }

  /* Video styling */
  video {
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
  }

  /* Progress bar animation */
  #scoreBar {
    transition: width 0.5s ease-in-out;
  }
</style>
@endsection
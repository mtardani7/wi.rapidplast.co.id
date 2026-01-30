@extends('layouts.participant')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-primary-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <div class="flex justify-center mb-4">
        <div class="relative">
        </div>
      </div>
    </div>
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 transform transition-all duration-300 hover:shadow-2xl">
      <div class="bg-gradient-to-r from-primary-600 to-primary-800 py-5 px-6">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
            <i class="fas fa-id-card text-white text-lg"></i>
          </div>
          <div>
            <h3 class="text-lg font-bold text-white">Masuk Work Instruction</h3>
            <p class="text-primary-100 text-sm">Participant</p>
          </div>
        </div>
      </div>
      <div class="p-6">
        <div class="mb-6">
          <div class="flex items-center space-x-2 text-gray-600 mb-2">
            <i class="fas fa-info-circle text-primary-500"></i>
            <p class="text-sm">
              Masukkan <span class="font-bold text-primary-700">NIK</span> Anda untuk mengakses materi pembelajaran
            </p>
          </div>
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
            <div class="flex items-start space-x-2">
              <i class="fas fa-lightbulb text-blue-500 mt-0.5"></i>
              <div>
                <p class="text-xs text-blue-800 font-medium">Tips:</p>
                <p class="text-xs text-blue-700">
                  NIK biasanya terdiri dari 8-10 digit angka yang tertera pada ID card perusahaan Anda.
                </p>
              </div>
            </div>
          </div>
        </div>

        <form method="POST" action="{{ route('nik.submit') }}" id="nikForm">
          @csrf
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <div class="flex items-center">
                <i class="fas fa-fingerprint text-primary-600 mr-2"></i>
                Nomor Induk Karyawan (NIK)
                <span class="text-red-500 ml-1">*</span>
              </div>
            </label>
            
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-user-tag text-gray-400"></i>
              </div>
              
              <input
                type="text"
                name="nik"
                id="nikInput"
                class="pl-10 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('nik') border-red-500 @enderror transition-all duration-200"
                placeholder="Contoh: 12345678"
                value="{{ old('nik') }}"
                required
                autofocus
                maxlength="10"
                pattern="[0-9]*"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
              />
              
              @if(old('nik'))
                <button type="button" onclick="document.getElementById('nikInput').value = ''" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                  <i class="fas fa-times text-gray-400 hover:text-gray-600"></i>
                </button>
              @endif
            </div>

            <div class="flex justify-between items-center mt-1">
              <div class="text-xs text-gray-500">
                Masukkan angka NIK Anda
              </div>
              <div class="text-xs text-gray-400">
                <span id="charCount">0</span>/10 digit
              </div>
            </div>

            @error('nik')
              <div class="mt-2 flex items-center space-x-1 text-sm text-red-600">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $message }}</span>
              </div>
            @enderror
          </div>

          <button type="submit" id="submitButton"class="group relative w-full flex justify-center items-center space-x-2 py-3 px-4 border border-transparent rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
            <div id="loadingSpinner" class="hidden">
              <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </div>
            
            <span id="buttonText" class="flex items-center space-x-2">
              <i class="fas fa-sign-in-alt"></i>
              <span>Lanjut ke Materi</span>
            </span>
            
            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-200 ml-1"></i>
          </button>
        </form>
      </div>
    </div>

    <div class="mt-6 text-center">
      <p class="text-xs text-gray-400 mt-4">
        &copy; {{ date('Y') }} PT. Rapid Plast Indonesia. All rights reserved.
      </p>
    </div>
  </div>
</div>

<style>
  input[type="text"] {
    transition: all 0.3s ease;
  }
  
  input[type="text"]:focus {
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
  }
  
  .group:hover .group-hover\:translate-x-1 {
    transform: translateX(0.25rem);
  }
  
  @keyframes slideUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .card-entrance {
    animation: slideUp 0.5s ease-out;
  }
  
  ::-webkit-scrollbar {
    width: 6px;
  }
  
  ::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
  }
  
  ::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #ef4444, #dc2626);
    border-radius: 3px;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.bg-white.rounded-2xl').classList.add('card-entrance');
    const nikInput = document.getElementById('nikInput');
    const charCount = document.getElementById('charCount');
    if (nikInput && charCount) {
      nikInput.addEventListener('input', function() {
        charCount.textContent = this.value.length;
        if (this.value.length >= 8) {
          charCount.classList.remove('text-gray-400', 'text-yellow-500');
          charCount.classList.add('text-green-500', 'font-medium');
        } else if (this.value.length >= 5) {
          charCount.classList.remove('text-gray-400', 'text-green-500');
          charCount.classList.add('text-yellow-500', 'font-medium');
        } else {
          charCount.classList.remove('text-yellow-500', 'text-green-500', 'font-medium');
          charCount.classList.add('text-gray-400');
        }
      });
      charCount.textContent = nikInput.value.length;
      nikInput.dispatchEvent(new Event('input'));
    }
    const nikForm = document.getElementById('nikForm');
    const submitButton = document.getElementById('submitButton');
    const buttonText = document.getElementById('buttonText');
    const loadingSpinner = document.getElementById('loadingSpinner');
    if (nikForm && submitButton) {
      nikForm.addEventListener('submit', function(e) {
        const nikValue = nikInput.value.trim();
        if (nikValue.length < 5) {
          e.preventDefault();
          const errorDiv = document.createElement('div');
          errorDiv.className = 'mt-2 flex items-center space-x-1 text-sm text-red-600 animate-pulse';
          errorDiv.innerHTML = `
            <i class="fas fa-exclamation-circle"></i>
            <span>NIK harus terdiri dari minimal 5 digit angka</span>
          `;
          const existingError = nikInput.parentElement.nextElementSibling;
          if (existingError && existingError.classList.contains('text-red-600')) {
            existingError.remove();
          }
          
          nikInput.parentElement.after(errorDiv);
          nikInput.focus();
          return;
        }
        if (buttonText && loadingSpinner) {
          buttonText.classList.add('hidden');
          loadingSpinner.classList.remove('hidden');
          submitButton.disabled = true;
          submitButton.classList.add('opacity-90', 'cursor-not-allowed');
          submitButton.classList.remove('hover:-translate-y-0.5');
          setTimeout(() => {
            if (buttonText.querySelector('span')) {
              buttonText.querySelector('span').textContent = 'Memproses...';
            }
          }, 300);
        }
      });
    }
    nikInput.addEventListener('keydown', function(e) {
      if (!/^\d$/.test(e.key) && 
          !['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'].includes(e.key)) {
        e.preventDefault();
        const warning = document.createElement('div');
        warning.className = 'absolute top-full left-0 mt-1 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded shadow-md animate-pulse';
        warning.textContent = 'Hanya angka yang diperbolehkan';
        this.parentElement.appendChild(warning);
        
        setTimeout(() => {
          warning.remove();
        }, 1500);
      }
    });
    nikInput.addEventListener('blur', function() {
      const value = this.value.replace(/\D/g, '');
      if (value.length >= 8) {
      }
    });
    nikInput.addEventListener('focus', function() {
      this.parentElement.classList.add('ring-2', 'ring-primary-200', 'ring-opacity-50');
    });
    
    nikInput.addEventListener('blur', function() {
      this.parentElement.classList.remove('ring-2', 'ring-primary-200', 'ring-opacity-50');
    });
  });
</script>
@endsection
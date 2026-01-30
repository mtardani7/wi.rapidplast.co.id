@extends('layouts.participant')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-primary-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
  <div class="w-full max-w-lg">
    <div class="text-center mb-8">
      <div class="flex justify-center mb-4">
      </div>
    </div>
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
      <div class="bg-gradient-to-r from-primary-600 to-primary-800 py-5 px-6">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
            <i class="fas fa-user-plus text-white text-lg"></i>
          </div>
          <div>
            <h3 class="text-lg font-bold text-white">Lengkapi Data Peserta</h3>
            <p class="text-primary-100 text-sm">Informasi baru terdeteksi</p>
          </div>
        </div>
      </div>
      <div class="p-6">
        <div class="mb-6">
          <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
            <div class="flex items-start space-x-3">
              <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-lg mt-0.5"></i>
              </div>
              <div>
                <p class="text-sm font-medium text-blue-800 mb-1">NIK Baru Terdeteksi</p>
                <p class="text-sm text-blue-700">
                  NIK <span class="font-bold bg-blue-100 px-2 py-0.5 rounded">{{ $nik }}</span> belum terdaftar dalam sistem. 
                  Silakan lengkapi data berikut untuk melanjutkan.
                </p>
              </div>
            </div>
          </div>
          
          <div class="flex items-center space-x-2 text-gray-600">
            <i class="fas fa-shield-alt text-primary-500"></i>
            <p class="text-sm">
              Data yang Anda isi akan disimpan untuk keperluan progress pembelajaran.
            </p>
          </div>
        </div>

        <form method="POST" action="{{ route('participant.register.submit') }}" id="registrationForm">
          @csrf
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <div class="flex items-center">
                <i class="fas fa-fingerprint text-primary-600 mr-2"></i>
                Nomor Induk Karyawan (NIK)
              </div>
            </label>
            
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-id-card text-gray-400"></i>
              </div>
              
              <input
                type="text"
                value="{{ $nik }}"
                disabled
                class="pl-10 block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-700 cursor-not-allowed"
              />
            </div>
            
            <div class="mt-2 flex items-center space-x-1 text-xs text-gray-500">
              <i class="fas fa-info-circle"></i>
              <span>NIK ini akan digunakan untuk menyimpan progress pembelajaran Anda</span>
            </div>
          </div>
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <div class="flex items-center">
                <i class="fas fa-user text-primary-600 mr-2"></i>
                Nama Lengkap
                <span class="text-red-500 ml-1">*</span>
              </div>
            </label>
            
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-signature text-gray-400"></i>
              </div>
              
              <input
                type="text"
                name="name"
                id="nameInput"
                class="pl-10 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 @enderror transition-all duration-200"
                placeholder="Masukkan nama lengkap Anda"
                value="{{ old('name') }}"
                required
                autofocus
                maxlength="100"
              />
            
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <span class="text-xs text-gray-400">
                  <span id="nameCharCount">{{ old('name') ? strlen(old('name')) : 0 }}</span>/100
                </span>
              </div>
            </div>
            
            @error('name')
              <div class="mt-2 flex items-center space-x-1 text-sm text-red-600 animate-pulse">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $message }}</span>
              </div>
            @enderror
            
            <div class="mt-2 text-xs text-gray-500">
              Gunakan nama lengkap sesuai dengan dokumen identitas perusahaan
            </div>
          </div>

          {{-- Plan Field --}}
          <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              <div class="flex items-center">
                <i class="fas fa-calendar-alt text-primary-600 mr-2"></i>
                Plan Training
                <span class="text-red-500 ml-1">*</span>
              </div>
            </label>
            
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                <i class="fas fa-tasks text-gray-400"></i>
              </div>
              
              <select
                name="plan"
                id="planSelect"
                class="pl-10 appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('plan') border-red-500 @enderror transition-all duration-200"
                required
              >
                <option value="" disabled selected>-- Pilih Plan Training --</option>
                @foreach($plans as $p)
                  <option value="{{ $p }}" {{ old('plan') === $p ? 'selected' : '' }}>
                    {{ strtoupper($p) }}
                  </option>
                @endforeach
              </select>
              
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <i class="fas fa-chevron-down text-gray-400"></i>
              </div>
            </div>
            
            @error('plan')
              <div class="mt-2 flex items-center space-x-1 text-sm text-red-600 animate-pulse">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $message }}</span>
              </div>
            @enderror
          <button type="submit" id="submitButton" class="group mt-8 relative w-full flex justify-center items-center space-x-2 py-3 px-4 border border-transparent rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 mb-4">
            <div id="loadingSpinner" class="hidden">
              <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </div>
            <span id="buttonText" class="flex items-center space-x-2">
              <i class="fas fa-save"></i>
              <span>Simpan & Lanjut ke Materi</span>
            </span>
            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-200 ml-1"></i>
          </button>
          <div class="text-center">
            <a href="{{ route('nik.form') }}" 
               class="inline-flex items-center space-x-1 text-sm text-primary-600 hover:text-primary-800 hover:underline transition-colors duration-200">
              <i class="fas fa-arrow-left"></i>
              <span>Kembali ke halaman sebelumnya</span>
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<style>
  .plan-option {
    transition: all 0.3s ease;
  }
  
  .plan-option.selected {
    border-color: #ef4444;
    background-color: #fef2f2;
  }
  
  .plan-option.selected .plan-radio {
    border-color: #ef4444;
  }
  
  .plan-option.selected .plan-radio > div {
    display: flex;
  }
  
  select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
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
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.bg-white.rounded-2xl').classList.add('card-entrance');
    const nameInput = document.getElementById('nameInput');
    const nameCharCount = document.getElementById('nameCharCount');
    
    if (nameInput && nameCharCount) {
      nameInput.addEventListener('input', function() {
        nameCharCount.textContent = this.value.length;
        if (this.value.length >= 3) {
          nameCharCount.classList.remove('text-gray-400', 'text-yellow-500');
          nameCharCount.classList.add('text-green-500', 'font-medium');
        } else {
          nameCharCount.classList.remove('text-green-500', 'font-medium');
          nameCharCount.classList.add('text-yellow-500');
        }
      });
      nameCharCount.textContent = nameInput.value.length;
      nameInput.dispatchEvent(new Event('input'));
    }
    const planSelect = document.getElementById('planSelect');
    const planOptions = document.querySelectorAll('.plan-option');
    if (planSelect.value) {
      const selectedPlan = planSelect.value;
      planOptions.forEach(option => {
        if (option.dataset.plan === selectedPlan) {
          option.classList.add('selected');
          option.querySelector('.plan-radio > div').classList.remove('hidden');
        }
      });
    }
    planOptions.forEach(option => {
      option.addEventListener('click', function() {
        const planValue = this.dataset.plan;
        planSelect.value = planValue;
        planOptions.forEach(opt => {
          opt.classList.remove('selected');
          opt.querySelector('.plan-radio > div').classList.add('hidden');
        });
        
        this.classList.add('selected');
        this.querySelector('.plan-radio > div').classList.remove('hidden');
        planSelect.dispatchEvent(new Event('change'));
      });
    });
    const registrationForm = document.getElementById('registrationForm');
    const submitButton = document.getElementById('submitButton');
    const buttonText = document.getElementById('buttonText');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    if (registrationForm && submitButton) {
      registrationForm.addEventListener('submit', function(e) {
        const nameValue = nameInput.value.trim();
        const planValue = planSelect.value;
        if (!nameValue || nameValue.length < 2) {
          e.preventDefault();
          showValidationError('Nama harus terdiri dari minimal 2 karakter');
          nameInput.focus();
          return;
        }
        
        if (!planValue) {
          e.preventDefault();
          showValidationError('Silakan pilih plan training');
          planSelect.focus();
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
              buttonText.querySelector('span').textContent = 'Menyimpan data...';
            }
          }, 300);
        }
      });
    }
    function showValidationError(message) {
      const existingError = document.querySelector('.validation-error');
      if (existingError) existingError.remove();
      const errorDiv = document.createElement('div');
      errorDiv.className = 'mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 flex items-center space-x-2 animate-pulse validation-error';
      errorDiv.innerHTML = `
        <i class="fas fa-exclamation-triangle"></i>
        <span>${message}</span>
      `;
      registrationForm.parentElement.insertBefore(errorDiv, registrationForm);
      setTimeout(() => {
        if (errorDiv.parentElement) {
          errorDiv.remove();
        }
      }, 5000);
    }
    nameInput.addEventListener('blur', function() {
      if (this.value) {
        this.value = this.value.toLowerCase().replace(/\b\w/g, function(char) {
          return char.toUpperCase();
        });
      }
    });
    const focusableElements = [nameInput, planSelect];
    focusableElements.forEach(el => {
      el.addEventListener('focus', function() {
        this.parentElement.classList.add('ring-2', 'ring-primary-200', 'ring-opacity-50');
      });
      
      el.addEventListener('blur', function() {
        this.parentElement.classList.remove('ring-2', 'ring-primary-200', 'ring-opacity-50');
      });
    });
  });
</script>
@endsection
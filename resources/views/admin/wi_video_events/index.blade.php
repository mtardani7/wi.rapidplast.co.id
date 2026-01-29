@extends('layouts.admin')
@section('title', 'Admin - Quiz Events')

@section('content')
<div x-data="quizEventManager()" class="min-h-screen">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 font-medium mb-1">
                <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-600 border border-gray-200">WI: {{ $wi->title }}</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                <span class="text-red-600 font-bold truncate max-w-xs">{{ $video->title }}</span>
            </div>
            <h1 class="text-3xl font-black text-gray-800 tracking-tight drop-shadow-sm">
                Quiz Events
            </h1>
            <p class="text-gray-500 mt-1 text-sm">
                Atur pertanyaan kuis yang muncul pada detik tertentu dalam video.
            </p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.wi.videos.index', $wi->id) }}" class="group inline-flex items-center px-4 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:text-red-600 hover:border-red-200 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>

            <button 
                @click="openAddModal()"
                class="relative inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white transition-all bg-red-600 rounded-xl hover:bg-red-700 shadow-lg hover:shadow-red-500/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600"
            >
                <div class="absolute inset-0 w-full h-full bg-gradient-to-b from-white/20 to-transparent rounded-xl pointer-events-none"></div>
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Quiz
            </button>
        </div>
    </div>
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-green-50 rounded-xl border border-green-100 shadow-sm flex items-center justify-between">
            <div class="flex items-center text-green-700 font-medium">
                <svg class="w-5 h-5 mr-3 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('success') }}
            </div>
            <button @click="show = false" class="text-green-400 hover:text-green-600">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 rounded-xl border border-red-100 shadow-sm">
            <div class="flex">
                <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <h3 class="text-sm font-bold text-red-800">Validasi Error</h3>
                    <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden relative">
        <div class="h-1.5 w-full bg-gradient-to-r from-gray-800 to-gray-600"></div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Waktu (Menit:Detik)</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Pertanyaan & Opsi</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Wajib?</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($events as $i => $e)
                        @php
                            $m = floor($e->time_seconds / 60);
                            $s = $e->time_seconds % 60;
                            $timeLabel = sprintf('%02d:%02d', $m, $s);
                        @endphp
                        <tr class="group hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 font-mono">{{ $i+1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded bg-gray-900 text-white font-mono text-sm font-bold shadow-md shadow-gray-300">
                                    {{ $timeLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900 mb-1">{{ $e->question }}</div>
                                <div class="text-xs text-gray-500 grid grid-cols-2 gap-x-4 gap-y-1">
                                    <span class="{{ $e->correct_index == 0 ? 'text-green-600 font-bold' : '' }}">A: {{ $e->options[0] ?? '-' }}</span>
                                    <span class="{{ $e->correct_index == 1 ? 'text-green-600 font-bold' : '' }}">B: {{ $e->options[1] ?? '-' }}</span>
                                    <span class="{{ $e->correct_index == 2 ? 'text-green-600 font-bold' : '' }}">C: {{ $e->options[2] ?? '-' }}</span>
                                    <span class="{{ $e->correct_index == 3 ? 'text-green-600 font-bold' : '' }}">D: {{ $e->options[3] ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($e->is_required)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        Yes
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-500">
                                        No
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($e->is_active)
                                    <span class="w-2.5 h-2.5 bg-green-500 rounded-full inline-block mr-1"></span>
                                    <span class="text-xs font-medium text-green-700">Active</span>
                                @else
                                    <span class="w-2.5 h-2.5 bg-gray-300 rounded-full inline-block mr-1"></span>
                                    <span class="text-xs font-medium text-gray-500">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2 opacity-80 group-hover:opacity-100">
                                    <button 
                                        @click="openEditModal({{ json_encode($e) }}, '{{ route('admin.wi.videos.events.update', [$wi->id, $video->id, $e->id]) }}')"
                                        class="p-2 text-gray-500 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>

                                    <form action="{{ route('admin.wi.videos.events.destroy', [$wi->id, $video->id, $e->id]) }}" method="POST" onsubmit="return confirm('Hapus quiz ini?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3 text-gray-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">Belum ada quiz event untuk video ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <template x-teleport="body">
        <div x-show="addModalOpen" class="relative z-50" style="display: none;">
            <div x-show="addModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div x-show="addModalOpen" @click.away="addModalOpen = false" x-transition class="relative w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all">
                        <form method="POST" action="{{ route('admin.wi.videos.events.store', [$wi->id, $video->id]) }}">
                            @csrf
                            <div class="bg-gradient-to-r from-red-600 to-red-800 px-6 py-4 flex justify-between items-center shadow-md relative z-10">
                                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Tambah Quiz Baru
                                </h3>
                                <button type="button" @click="addModalOpen = false" class="text-white/70 hover:text-white">&times;</button>
                            </div>
                            <div class="px-6 py-6 max-h-[75vh] overflow-y-auto bg-gray-50/50">
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                    <div class="md:col-span-7 space-y-5">
                                        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                                            <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-4 border-b pb-2">Timing & Konten</h4>
                                            
                                            <div class="grid grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Menit</label>
                                                    <input type="number" name="minute" min="0" value="0" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-200 text-center font-mono font-bold text-lg" required>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Detik</label>
                                                    <input type="number" name="second" min="0" max="59" value="0" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-200 text-center font-mono font-bold text-lg" required>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">Pertanyaan</label>
                                                <textarea name="question" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-200" required placeholder="Contoh: Apa langkah pertama dalam proses ini?"></textarea>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">Penjelasan Jawaban (Opsional)</label>
                                                <textarea name="explanation" rows="2" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-200" placeholder="Penjelasan akan muncul setelah user menjawab..."></textarea>
                                            </div>
                                        </div>

                                        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex gap-6">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" name="is_required" value="1" class="w-5 h-5 rounded border-gray-300 text-red-600 focus:ring-red-500 transition">
                                                <span class="ml-2 text-sm font-medium text-gray-700">Wajib Dijawab (Required)</span>
                                            </label>
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500 transition">
                                                <span class="ml-2 text-sm font-medium text-gray-700">Aktif</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="md:col-span-5 bg-white p-5 rounded-xl border border-gray-100 shadow-sm h-fit">
                                        <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-4 border-b pb-2">Pilihan Jawaban</h4>
                                        
                                        <div class="space-y-3">
                                            @foreach(['option_a', 'option_b', 'option_c', 'option_d'] as $idx => $name)
                                                <div class="flex items-center gap-2">
                                                    <span class="w-8 h-8 flex items-center justify-center rounded bg-gray-100 text-gray-600 font-bold text-sm">{{ chr(65 + $idx) }}</span>
                                                    <input type="text" name="{{ $name }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-200 text-sm" placeholder="Opsi {{ chr(65 + $idx) }}..." required>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="mt-6 pt-4 border-t border-gray-100">
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Jawaban Benar</label>
                                            <div class="grid grid-cols-4 gap-2">
                                                @foreach(['A', 'B', 'C', 'D'] as $idx => $opt)
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="correct_index" value="{{ $idx }}" class="peer sr-only" {{ $idx == 0 ? 'checked' : '' }}>
                                                        <div class="text-center py-2 rounded-lg border border-gray-200 peer-checked:bg-green-100 peer-checked:text-green-800 peer-checked:border-green-500 hover:bg-gray-50 transition-all font-bold text-sm">
                                                            {{ $opt }}
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-200">
                                <button type="button" @click="addModalOpen = false" class="px-5 py-2 text-sm font-bold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">Batal</button>
                                <button type="submit" class="plastic-btn px-6 py-2 text-sm font-bold text-white bg-red-600 rounded-lg shadow-md hover:bg-red-700 hover:shadow-lg border-t border-red-400">Simpan Quiz</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div x-show="editModalOpen" class="relative z-50" style="display: none;">
            <div x-show="editModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div x-show="editModalOpen" @click.away="editModalOpen = false" x-transition class="relative w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all">
                        <form method="POST" :action="editForm.action">
                            @csrf @method('PUT')
                            
                            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4 flex justify-between items-center shadow-md relative z-10">
                                <h3 class="text-lg font-bold text-white flex items-center gap-2">Edit Quiz Event</h3>
                                <button type="button" @click="editModalOpen = false" class="text-white/70 hover:text-white">&times;</button>
                            </div>

                            <div class="px-6 py-6 max-h-[75vh] overflow-y-auto bg-gray-50/50">
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                    <div class="md:col-span-7 space-y-5">
                                        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                                            <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-4 border-b pb-2">Timing & Konten</h4>
                                            <div class="grid grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Menit</label>
                                                    <input type="number" name="minute" x-model="editForm.minute" min="0" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-200 text-center font-mono font-bold text-lg" required>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Detik</label>
                                                    <input type="number" name="second" x-model="editForm.second" min="0" max="59" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-200 text-center font-mono font-bold text-lg" required>
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">Pertanyaan</label>
                                                <textarea name="question" x-model="editForm.question" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-200" required></textarea>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-1">Penjelasan</label>
                                                <textarea name="explanation" x-model="editForm.explanation" rows="2" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-200"></textarea>
                                            </div>
                                        </div>
                                        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex gap-6">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" name="is_required" value="1" x-model="editForm.is_required" class="w-5 h-5 rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                                                <span class="ml-2 text-sm font-medium text-gray-700">Wajib Dijawab</span>
                                            </label>
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" name="is_active" value="1" x-model="editForm.is_active" class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                                <span class="ml-2 text-sm font-medium text-gray-700">Aktif</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="md:col-span-5 bg-white p-5 rounded-xl border border-gray-100 shadow-sm h-fit">
                                        <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-4 border-b pb-2">Pilihan Jawaban</h4>
                                        <div class="space-y-3">
                                            <template x-for="(opt, idx) in ['option_a', 'option_b', 'option_c', 'option_d']">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-8 h-8 flex items-center justify-center rounded bg-gray-100 text-gray-600 font-bold text-sm" x-text="opt.charAt(opt.length - 1)"></span>
                                                    <input type="text" :name="opt" x-model="editForm[opt]" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-200 text-sm" required>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="mt-6 pt-4 border-t border-gray-100">
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Jawaban Benar</label>
                                            <div class="grid grid-cols-4 gap-2">
                                                <template x-for="(opt, idx) in ['A', 'B', 'C', 'D']">
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="correct_index" :value="idx" x-model="editForm.correct_index" class="peer sr-only">
                                                        <div class="text-center py-2 rounded-lg border border-gray-200 peer-checked:bg-green-100 peer-checked:text-green-800 peer-checked:border-green-500 hover:bg-gray-50 transition-all font-bold text-sm" x-text="opt"></div>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-200">
                                <button type="button" @click="editModalOpen = false" class="px-5 py-2 text-sm font-bold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">Batal</button>
                                <button type="submit" class="plastic-btn px-6 py-2 text-sm font-bold text-white bg-yellow-500 rounded-lg shadow-md hover:bg-yellow-600 hover:shadow-lg border-t border-yellow-300">Update Quiz</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>

</div>

<script>
function quizEventManager() {
    return {
        addModalOpen: false,
        editModalOpen: false,
        
        editForm: {
            action: '',
            minute: 0,
            second: 0,
            question: '',
            explanation: '',
            is_required: false,
            is_active: true,
            option_a: '',
            option_b: '',
            option_c: '',
            option_d: '',
            correct_index: 0
        },

        openAddModal() {
            this.addModalOpen = true;
        },

        openEditModal(data, actionUrl) {
            this.editForm = {
                action: actionUrl,
                minute: Math.floor(data.time_seconds / 60),
                second: data.time_seconds % 60,
                question: data.question,
                explanation: data.explanation || '',
                is_required: !!data.is_required,
                is_active: !!data.is_active,
                option_a: data.options[0] || '',
                option_b: data.options[1] || '',
                option_c: data.options[2] || '',
                option_d: data.options[3] || '',
                correct_index: data.correct_index
            };
            this.editModalOpen = true;
        }
    }
}
</script>
@endsection
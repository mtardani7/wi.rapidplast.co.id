@extends('layouts.admin')
@section('title', 'Admin - Work Instructions')

@section('content')
<div x-data="wiListManager()" class="min-h-screen">
    <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-800 tracking-tight drop-shadow-sm">
                Work Instructions
            </h1>
            <p class="text-gray-500 font-medium mt-1">
                Kelola panduan kerja dan video tutorial interaktif.
            </p>
        </div>

        <button 
            @click="openAddWiModal()" 
            class="group relative inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white transition-all duration-200 bg-red-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 shadow-lg hover:shadow-red-500/50 hover:-translate-y-1 active:translate-y-0"
        >
            <div class="absolute top-0 left-0 w-full h-1/2 bg-gradient-to-b from-white/20 to-transparent rounded-t-xl pointer-events-none"></div>
            
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah WI
        </button>
    </div>
    <div class="space-y-4 mb-8">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="flex items-center p-4 bg-green-50 rounded-xl border border-green-100 shadow-sm">
                <div class="flex-shrink-0 text-green-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="ml-3 text-green-700 font-medium">{{ session('success') }}</div>
                <button @click="show = false" class="ml-auto text-green-400 hover:text-green-600">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-red-50 rounded-xl border border-red-100 shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-red-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-800">Terdapat kesalahan input:</h3>
                        <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                            @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden relative">
        <div class="h-1.5 w-full bg-gradient-to-r from-red-500 via-red-600 to-red-800"></div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Judul & Deskripsi</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Video</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($wis as $i => $wi)
                        <tr class="group transition-all duration-200 hover:bg-red-50/20">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 font-mono">
                                {{ $i+1 }}
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900 group-hover:text-red-700 transition-colors">{{ $wi->title }}</div>
                                <div class="text-xs text-gray-500 mt-1 max-w-md truncate">{{ $wi->description ?? '-' }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($wi->is_published)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200 shadow-sm">
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                        Draft
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="inline-flex items-center px-2.5 py-1 rounded-md bg-gray-800 text-white text-xs font-bold shadow-md">
                                    {{ $wi->videos_count ?? 0 }} Video
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.wi.videos.index', $wi->id) }}" class="plastic-btn inline-flex items-center px-3 py-1.5 text-xs font-bold text-white bg-blue-600 rounded-lg shadow-md border-t border-blue-400 hover:bg-blue-700 hover:shadow-lg transition-all">
                                        Kelola
                                    </a>
                                    <button 
                                        @click="openAddVideoModal('{{ route('admin.wi.videos.store', $wi->id) }}', '{{ addslashes($wi->title) }}')"
                                        class="plastic-btn inline-flex items-center px-3 py-1.5 text-xs font-bold text-white bg-green-600 rounded-lg shadow-md border-t border-green-400 hover:bg-green-700 hover:shadow-lg transition-all"
                                    >
                                        + Link
                                    </button>
                                    <button 
                                        @click="openEditWiModal({{ json_encode($wi) }}, '{{ route('admin.wi.update', $wi->id) }}')"
                                        class="text-gray-400 hover:text-yellow-600 transition-colors p-1.5 hover:bg-yellow-50 rounded-md" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <form action="{{ route('admin.wi.destroy', $wi->id) }}" method="POST" onsubmit="return confirm('Hapus WI ini? Semua video akan ikut terhapus.')">
                                        @csrf @method('DELETE')
                                        <button class="text-gray-400 hover:text-red-600 transition-colors p-1.5 hover:bg-red-50 rounded-md" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900">Belum ada Work Instruction</h3>
                                    <button @click="openAddWiModal()" class="text-red-600 hover:text-red-800 font-bold text-sm mt-2">
                                        + Buat WI Pertama
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6 flex items-start gap-3 p-4 rounded-xl bg-blue-50 border border-blue-100 text-blue-800 text-sm shadow-sm">
        <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <strong>Catatan Teknis:</strong> Video link Google Drive/OneDrive cocok untuk testing internal. Untuk produksi (kuis berbasis detik), disarankan menggunakan link direct MP4 atau hosting streaming.
        </div>
    </div>
    <template x-teleport="body">
        <div x-show="addWiModalOpen" class="relative z-50" style="display: none;">
            <div x-show="addWiModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div x-show="addWiModalOpen" @click.away="addWiModalOpen = false" x-transition class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl">
                        <form method="POST" action="{{ route('admin.wi.store') }}">
                            @csrf
                            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 border-b border-red-500 shadow-sm">
                                <h3 class="text-lg font-bold text-white">Tambah Work Instruction</h3>
                            </div>
                            <div class="px-6 py-6 space-y-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Judul</label>
                                    <input type="text" name="title" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-200" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                                    <textarea name="description" rows="3" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-200"></textarea>
                                </div>
                                <div class="flex items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
                                    <input id="add_pub" name="is_published" type="checkbox" value="1" checked class="h-4 w-4 rounded text-red-600 focus:ring-red-500 border-gray-300">
                                    <label for="add_pub" class="ml-3 text-sm font-medium text-gray-700">Langsung Publish</label>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                                <button type="button" @click="addWiModalOpen = false" class="px-4 py-2 text-sm font-bold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">Batal</button>
                                <button type="submit" class="plastic-btn px-6 py-2 text-sm font-bold text-white bg-red-600 rounded-lg shadow-md border-t border-red-400 hover:bg-red-700">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <template x-teleport="body">
        <div x-show="editWiModalOpen" class="relative z-50" style="display: none;">
            <div x-show="editWiModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div x-show="editWiModalOpen" @click.away="editWiModalOpen = false" x-transition class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl">
                        <form method="POST" :action="editWiForm.action">
                            @csrf @method('PUT')
                            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4 border-b border-yellow-400 shadow-sm">
                                <h3 class="text-lg font-bold text-white">Edit Work Instruction</h3>
                            </div>
                            <div class="px-6 py-6 space-y-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Judul</label>
                                    <input type="text" name="title" x-model="editWiForm.title" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-200" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                                    <textarea name="description" x-model="editWiForm.description" rows="3" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-200"></textarea>
                                </div>
                                <div class="flex items-center p-3 bg-yellow-50/50 rounded-xl border border-yellow-100">
                                    <input id="edit_pub" name="is_published" type="checkbox" value="1" x-model="editWiForm.is_published" class="h-4 w-4 rounded text-yellow-600 focus:ring-yellow-500 border-gray-300">
                                    <label for="edit_pub" class="ml-3 text-sm font-medium text-gray-700">Status Publish</label>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                                <button type="button" @click="editWiModalOpen = false" class="px-4 py-2 text-sm font-bold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">Batal</button>
                                <button type="submit" class="plastic-btn px-6 py-2 text-sm font-bold text-white bg-yellow-500 rounded-lg shadow-md border-t border-yellow-300 hover:bg-yellow-600">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <template x-teleport="body">
        <div x-show="addVideoModalOpen" class="relative z-50" style="display: none;">
            <div x-show="addVideoModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div x-show="addVideoModalOpen" @click.away="addVideoModalOpen = false" x-transition class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl">
                        <form method="POST" :action="addVideoForm.action">
                            @csrf
                            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 border-b border-green-500 shadow-sm">
                                <h3 class="text-lg font-bold text-white">Tambah Video Link</h3>
                                <p class="text-xs text-green-100 mt-1">Ke: <span x-text="addVideoForm.wi_title" class="font-bold"></span></p>
                            </div>
                            <div class="px-6 py-6 space-y-4">
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Video</label>
                                        <input type="text" name="title" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-200" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Urutan</label>
                                        <input type="number" name="sort_order" value="1" min="1" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-200 text-center" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                                    <textarea name="description" rows="2" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-200"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Video URL</label>
                                    <input type="url" name="video_url" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-200" placeholder="https://..." required>
                                </div>

                                <div class="flex items-center p-3 bg-green-50 rounded-xl border border-green-100">
                                    <input id="vid_active" name="is_active" type="checkbox" value="1" checked class="h-4 w-4 rounded text-green-600 focus:ring-green-500 border-gray-300">
                                    <label for="vid_active" class="ml-3 text-sm font-medium text-gray-700">Status Aktif</label>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                                <button type="button" @click="addVideoModalOpen = false" class="px-4 py-2 text-sm font-bold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">Batal</button>
                                <button type="submit" class="plastic-btn px-6 py-2 text-sm font-bold text-white bg-green-600 rounded-lg shadow-md border-t border-green-400 hover:bg-green-700">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function wiListManager() {
    return {
        addWiModalOpen: false,
        editWiModalOpen: false,
        addVideoModalOpen: false,

        editWiForm: {
            action: '',
            title: '',
            description: '',
            is_published: false
        },

        addVideoForm: {
            action: '',
            wi_title: ''
        },
    
        openAddWiModal() {
            this.addWiModalOpen = true;
        },

        openEditWiModal(data, url) {
            this.editWiForm = {
                action: url,
                title: data.title,
                description: data.description || '',
                is_published: !!data.is_published
            };
            this.editWiModalOpen = true;
        },

        openAddVideoModal(url, wiTitle) {
            this.addVideoForm = {
                action: url,
                wi_title: wiTitle
            };
            this.addVideoModalOpen = true;
        }
    }
}
</script>
@endsection
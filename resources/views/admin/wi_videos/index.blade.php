@extends('layouts.admin')
@section('title', 'Admin - Video WI')

@section('content')
<div x-data="videoManager()" class="min-h-screen">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 font-medium mb-1">
                <a href="{{ route('admin.wi.index') }}" class="hover:text-red-600 transition-colors">Work Instructions</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                <span class="text-red-600 font-bold truncate max-w-xs">{{ $wi->title }}</span>
            </div>
            <h1 class="text-3xl font-black text-gray-800 tracking-tight drop-shadow-sm">
                Video Playlist
            </h1>
            <p class="text-gray-500 mt-1 text-sm">
                Upload materi video MP4 dan atur urutan tayangnya.
            </p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.wi.index') }}" class="group inline-flex items-center px-4 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:text-red-600 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>

            <button 
                @click="openAddModal()"
                class="relative inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white transition-all bg-red-600 rounded-xl hover:bg-red-700 shadow-lg hover:shadow-red-500/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600"
            >
                <div class="absolute inset-0 w-full h-full bg-gradient-to-b from-white/20 to-transparent rounded-xl pointer-events-none"></div>
                
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Upload Video
            </button>
        </div>
    </div>
    <div class="space-y-4 mb-8">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="flex items-center p-4 bg-green-50 rounded-xl border border-green-100 shadow-sm">
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
                    <svg class="w-6 h-6 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-800">Validasi Error:</h3>
                        <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                            @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden relative">
        <div class="h-1.5 w-full bg-gradient-to-r from-blue-600 via-blue-500 to-blue-800"></div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Urutan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Detail Video</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Preview</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi & Quiz</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($videos as $i => $v)
                        <tr class="group hover:bg-blue-50/20 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-600 font-bold text-sm border border-gray-200 shadow-inner">
                                    {{ $v->sort_order }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900 group-hover:text-blue-700 transition-colors">{{ $v->title }}</div>
                                <div class="text-xs text-gray-500 mt-1 max-w-xs truncate">{{ $v->description ?? '-' }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($v->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ $v->video_url }}" target="_blank" class="inline-flex items-center px-3 py-1 rounded-lg bg-gray-50 text-gray-600 text-xs font-medium border border-gray-200 hover:bg-gray-100 transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Play
                                </a>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.wi.videos.events.index', [$wi->id, $v->id]) }}" class="plastic-btn relative inline-flex items-center px-3 py-1.5 text-xs font-bold text-white bg-indigo-500 rounded-lg shadow-md hover:bg-indigo-600 border-t border-indigo-300">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        Quiz
                                    </a>

                                    <div class="h-4 w-px bg-gray-300 mx-1"></div>
                                    <button 
                                        @click="openEditModal({{ json_encode($v) }}, '{{ route('admin.wi.videos.update', [$wi->id, $v->id]) }}')"
                                        class="text-gray-400 hover:text-yellow-600 transition-colors p-1" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <form action="{{ route('admin.wi.videos.destroy', [$wi->id, $v->id]) }}" method="POST" onsubmit="return confirm('Hapus video ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-gray-400 hover:text-red-600 transition-colors p-1" title="Hapus">
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
                                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-3 text-blue-200">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">Belum ada video yang diupload.</p>
                                    <button @click="openAddModal()" class="text-blue-600 hover:text-blue-800 text-sm font-bold mt-2">+ Upload Video Sekarang</button>
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
                    <div x-show="addModalOpen" @click.away="addModalOpen = false" x-transition class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl">
                        <form method="POST" action="{{ route('admin.wi.videos.store', $wi->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 border-b border-red-500">
                                <h3 class="text-lg font-bold text-white">Upload Video MP4</h3>
                            </div>
                            
                            <div class="px-6 py-6 space-y-4">
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Video</label>
                                        <input type="text" name="title" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-200" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Urutan</label>
                                        <input type="number" name="sort_order" value="1" min="1" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-200 text-center" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                                    <textarea name="description" rows="2" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-200"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">File MP4</label>
                                    <input type="file" name="video_file" accept="video/mp4" class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-red-50 file:text-red-700
                                      hover:file:bg-red-100
                                    " required>
                                    <p class="mt-1 text-xs text-gray-500">Maksimal 500MB (MP4 Only).</p>
                                </div>

                                <div class="flex items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
                                    <input id="add_active" name="is_active" type="checkbox" value="1" checked class="h-4 w-4 rounded text-red-600 focus:ring-red-500 border-gray-300">
                                    <label for="add_active" class="ml-3 text-sm font-medium text-gray-700">Status Aktif</label>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                                <button type="button" @click="addModalOpen = false" class="px-4 py-2 text-sm font-bold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">Batal</button>
                                <button type="submit" class="plastic-btn px-6 py-2 text-sm font-bold text-white bg-red-600 rounded-lg shadow-md border-t border-red-400 hover:bg-red-700">Upload</button>
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
                    <div x-show="editModalOpen" @click.away="editModalOpen = false" x-transition class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl">
                        {{-- Form Edit (Multipart) --}}
                        <form method="POST" :action="editForm.action" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4 border-b border-yellow-400">
                                <h3 class="text-lg font-bold text-white">Edit Video</h3>
                            </div>
                            
                            <div class="px-6 py-6 space-y-4">
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Video</label>
                                        <input type="text" name="title" x-model="editForm.title" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-200" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Urutan</label>
                                        <input type="number" name="sort_order" x-model="editForm.sort_order" min="1" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-200 text-center" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                                    <textarea name="description" x-model="editForm.description" rows="2" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-200"></textarea>
                                </div>

                                <div class="bg-yellow-50 p-3 rounded-xl border border-yellow-100">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Ganti File MP4 (Opsional)</label>
                                    <input type="file" name="video_file" accept="video/mp4" class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-yellow-100 file:text-yellow-700
                                      hover:file:bg-yellow-200
                                    ">
                                    <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengganti video.</p>
                                </div>

                                <div class="flex items-center p-3 bg-white rounded-xl border border-gray-200">
                                    <input id="edit_active" name="is_active" type="checkbox" value="1" x-model="editForm.is_active" class="h-4 w-4 rounded text-yellow-600 focus:ring-yellow-500 border-gray-300">
                                    <label for="edit_active" class="ml-3 text-sm font-medium text-gray-700">Status Aktif</label>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                                <button type="button" @click="editModalOpen = false" class="px-4 py-2 text-sm font-bold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">Batal</button>
                                <button type="submit" class="plastic-btn px-6 py-2 text-sm font-bold text-white bg-yellow-500 rounded-lg shadow-md border-t border-yellow-300 hover:bg-yellow-600">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function videoManager() {
    return {
        addModalOpen: false,
        editModalOpen: false,
        
        editForm: {
            action: '',
            title: '',
            sort_order: 1,
            description: '',
            is_active: true
        },

        openAddModal() {
            this.addModalOpen = true;
        },

        openEditModal(data, actionUrl) {
            this.editForm = {
                action: actionUrl,
                title: data.title,
                sort_order: data.sort_order,
                description: data.description || '',
                is_active: !!data.is_active
            };
            this.editModalOpen = true;
        }
    }
}
</script>
@endsection
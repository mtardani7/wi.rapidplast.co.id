@extends('layouts.admin')
@section('title', 'Detail Hasil Peserta')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0">Detail Peserta</h4>
      <small class="text-muted">
        {{ $participant->name }} | NIK: {{ $participant->nik }} | Plan: {{ $participant->plan }}
      </small>
    </div>

    <a href="{{ route('admin.results.index') }}" class="btn btn-outline-secondary btn-sm">
      ← Kembali
    </a>
  </div>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-striped mb-0 align-middle">
        <thead>
          <tr>
            <th>WI</th>
            <th>Video</th>
            <th>Last Time (detik)</th>
            <th>Score</th>
            <th>Completed</th>
            <th>Updated</th>
          </tr>
        </thead>
        <tbody>
          @forelse($progress as $p)
            <tr>
              <td>{{ $p->video?->workInstruction?->title ?? '-' }}</td>
              <td>{{ $p->video?->title ?? '-' }}</td>
              <td>{{ $p->last_time_seconds ?? 0 }}</td>
              <td><span class="badge bg-primary">{{ $p->score ?? 0 }}</span></td>
              <td>
                @if($p->completed_at)
                  <span class="badge bg-success">Selesai</span>
                @else
                  <span class="badge bg-secondary">Belum</span>
                @endif
              </td>
              <td class="small text-muted">{{ $p->updated_at }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-4">
                Belum ada progress.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection

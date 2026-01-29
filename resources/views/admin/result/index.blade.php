@extends('layouts.admin')
@section('title', 'Admin - Hasil Peserta')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="mb-0">Hasil Peserta</h4>
      <small class="text-muted">Score otomatis total 100 berdasarkan quiz required</small>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-striped mb-0 align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Peserta</th>
            <th>Plan</th>
            <th>WI</th>
            <th>Video</th>
            <th>Progress (detik)</th>
            <th>Score</th>
            <th>Updated</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $i => $r)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>
                <div class="fw-semibold">{{ $r->participant->name ?? '-' }}</div>
                <div class="text-muted small">NIK: {{ $r->participant->nik ?? '-' }}</div>
              </td>
              <td>{{ $r->participant->plan ?? '-' }}</td>
              <td>{{ $r->video?->workInstruction?->title ?? '-' }}</td>
              <td>{{ $r->video?->title ?? '-' }}</td>
              <td>{{ $r->last_time_seconds ?? 0 }}</td>
              <td>
                <span class="badge bg-primary">{{ $r->score ?? 0 }}</span>
              </td>
              <td class="small text-muted">{{ $r->updated_at }}</td>
              <td>
                @if($r->participant)
                  <a href="{{ route('admin.results.show', $r->participant->id) }}"
                     class="btn btn-sm btn-outline-dark">
                    Detail
                  </a>
                @else
                  -
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center text-muted py-4">
                Belum ada hasil.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection

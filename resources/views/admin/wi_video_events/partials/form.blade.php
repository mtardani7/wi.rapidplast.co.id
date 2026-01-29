<div class="row g-3">

  <div class="col-md-6">
    <label class="form-label">Menit</label>
    <input id="minute" type="number" name="minute" class="form-control" min="0" value="0" required>
  </div>

  <div class="col-md-6">
    <label class="form-label">Detik</label>
    <input id="second" type="number" name="second" class="form-control" min="0" max="59" value="0" required>
  </div>

  <div class="col-12">
    <label class="form-label">Pertanyaan</label>
    <input id="question" type="text" name="question" class="form-control" required>
  </div>

  <div class="col-md-6">
    <label class="form-label">Option A</label>
    <input id="option_a" type="text" name="option_a" class="form-control" required>
  </div>

  <div class="col-md-6">
    <label class="form-label">Option B</label>
    <input id="option_b" type="text" name="option_b" class="form-control" required>
  </div>

  <div class="col-md-6">
    <label class="form-label">Option C</label>
    <input id="option_c" type="text" name="option_c" class="form-control" required>
  </div>

  <div class="col-md-6">
    <label class="form-label">Option D</label>
    <input id="option_d" type="text" name="option_d" class="form-control" required>
  </div>

  <div class="col-md-4">
    <label class="form-label">Jawaban Benar</label>
    <select id="correct_index" name="correct_index" class="form-select" required>
      <option value="0">A</option>
      <option value="1">B</option>
      <option value="2">C</option>
      <option value="3">D</option>
    </select>
  </div>

  <div class="col-md-8">
    <label class="form-label">Penjelasan (opsional)</label>
    <input id="explanation" type="text" name="explanation" class="form-control">
  </div>

  <div class="col-md-6">
    <label class="form-label">Rewind (opsional)</label>
    <div class="d-flex gap-2">
      <input type="number" name="rewind_minute" class="form-control" min="0" placeholder="Menit">
      <input type="number" name="rewind_second" class="form-control" min="0" max="59" placeholder="Detik">
    </div>
    <div class="form-text">Jika salah, video mundur ke waktu ini.</div>
  </div>

  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      <input id="is_required" class="form-check-input" type="checkbox" name="is_required" value="1" checked>
      <label class="form-check-label">Required</label>
    </div>
  </div>

  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      <input id="is_active" class="form-check-input" type="checkbox" name="is_active" value="1" checked>
      <label class="form-check-label">Active</label>
    </div>
  </div>

</div>

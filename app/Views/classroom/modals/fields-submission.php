<div class="form-group">
    <label>File Tugas</label>
    <input type="file" name="file" class="form-control" required>
    <p class="help-block">Maksimal 2 MB. Tipe: <?= e(implode(', ', (array) config('uploads.allowed_extensions'))) ?>. Mengumpulkan ulang akan mengganti file sebelumnya.</p>
</div>

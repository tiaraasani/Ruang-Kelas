<?php
/** @var bool $withFile */
$withFile = $withFile ?? false;
?>
<div class="form-group">
    <label>Judul Tugas</label>
    <input type="text" name="title" class="form-control" required minlength="3" maxlength="200">
</div>
<div class="form-group">
    <label>Deskripsi Tugas</label>
    <textarea name="description" class="form-control" rows="4" maxlength="2000" required></textarea>
</div>
<div class="form-group">
    <label>Deadline</label>
    <input type="date" name="deadline" class="form-control">
</div>
<?php if ($withFile): ?>
    <div class="form-group">
        <label>File Tugas</label>
        <input type="file" name="file" class="form-control" required>
        <p class="help-block">Maksimal 2 MB. Tipe: <?= e(implode(', ', (array) config('uploads.allowed_extensions'))) ?>.</p>
    </div>
<?php endif; ?>

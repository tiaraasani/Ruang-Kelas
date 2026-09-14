<?php
/**
 * @var array<string, mixed> $classroom
 * @var array<int, array<string, mixed>> $announcements
 * @var bool $isOwner
 */
?>
<?php if ($isOwner): ?>
    <form method="POST" action="<?= url('/classrooms/' . $classroom['id'] . '/announcements') ?>" class="m-b-lg">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="announcement-content">Isi Pengumuman</label>
            <textarea id="announcement-content" name="content" class="form-control" rows="3" maxlength="2000" required><?= e(old('content')) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa fa-bullhorn" aria-hidden="true"></i> Kirim Pengumuman</button>
    </form>
<?php endif; ?>

<?php if ($announcements === []): ?>
    <div class="empty-state">
        <i class="fa fa-bullhorn" aria-hidden="true"></i>
        <p>Belum ada pengumuman.</p>
    </div>
<?php else: ?>
    <?php foreach ($announcements as $announcement): ?>
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="well"><?= nl2br(e($announcement['content'])) ?></div>

                <div class="meta-list">
                    <span class="meta-item"><i class="fa fa-clock-o" aria-hidden="true"></i> <?= e(format_datetime($announcement['published_at'])) ?></span>
                    <?php if (!empty($announcement['author_name'])): ?>
                        <span class="meta-item"><i class="fa fa-user" aria-hidden="true"></i> <?= e($announcement['author_name']) ?></span>
                    <?php endif; ?>
                </div>

                <?php if ($isOwner): ?>
                    <div class="btn-row">
                        <button type="button" class="btn btn-white btn-sm"
                                data-toggle="modal" data-target="#modal-announcement-edit" data-modal-fill
                                data-form-action="<?= url('/announcements/' . $announcement['id'] . '/update') ?>"
                                data-field-content="<?= e($announcement['content']) ?>">
                            <i class="fa fa-pencil" aria-hidden="true"></i> Edit
                        </button>
                        <span class="spacer"></span>
                        <form method="POST" action="<?= url('/announcements/' . $announcement['id'] . '/delete') ?>"
                              data-confirm="Apakah Anda yakin ingin menghapus pengumuman ini?">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

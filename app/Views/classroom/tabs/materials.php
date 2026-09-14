<?php
/**
 * @var array<int, array<string, mixed>> $materials
 * @var bool $isOwner
 */
?>
<?php if ($materials === []): ?>
    <div class="empty-state">
        <i class="fa fa-book" aria-hidden="true"></i>
        <p>Belum ada materi yang diunggah.</p>
    </div>
<?php else: ?>
    <?php foreach ($materials as $material): ?>
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5 class="font-weight-bold"><?= e($material['title']) ?></h5>
            </div>
            <div class="ibox-content">
                <div class="well"><?= nl2br(e($material['description'])) ?></div>

                <div class="meta-list">
                    <span class="meta-item"><i class="fa fa-user" aria-hidden="true"></i> <?= e($material['author_name']) ?></span>
                    <span class="meta-item"><i class="fa fa-paperclip" aria-hidden="true"></i> <?= e(file_display_name($material['file_path'])) ?></span>
                </div>

                <div class="btn-row">
                    <a href="<?= url('/files/materials/' . $material['id']) ?>" class="btn btn-white btn-sm">
                        <i class="fa fa-download" aria-hidden="true"></i> Unduh Materi
                    </a>
                    <?php if ($isOwner): ?>
                        <button type="button" class="btn btn-white btn-sm"
                                data-toggle="modal" data-target="#modal-material-edit" data-modal-fill
                                data-form-action="<?= url('/materials/' . $material['id'] . '/update') ?>"
                                data-field-title="<?= e($material['title']) ?>"
                                data-field-description="<?= e($material['description']) ?>">
                            <i class="fa fa-pencil" aria-hidden="true"></i> Edit
                        </button>
                        <span class="spacer"></span>
                        <form method="POST" action="<?= url('/materials/' . $material['id'] . '/delete') ?>"
                              data-confirm="Apakah Anda yakin ingin menghapus materi ini?">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

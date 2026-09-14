<?php
/**
 * @var array<int, array<string, mixed>> $assignments
 * @var bool $isOwner
 * @var array<int, array<string, mixed>> $mySubmissions
 */
?>
<?php if ($assignments === []): ?>
    <div class="empty-state">
        <i class="fa fa-tasks" aria-hidden="true"></i>
        <p>Belum ada tugas yang diunggah.</p>
    </div>
<?php else: ?>
    <?php foreach ($assignments as $assignment): ?>
        <?php
        $submission = $mySubmissions[(int) $assignment['id']] ?? null;
        $comments = $assignment['comments'] ?? [];
        $hasDeadline = !empty($assignment['deadline']);
        ?>
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5 class="font-weight-bold"><?= e($assignment['title']) ?></h5>
                <?php if ($hasDeadline): ?>
                    <span class="badge-soft badge-amber card-badge">
                        <i class="fa fa-clock-o" aria-hidden="true"></i> <?= e(format_datetime($assignment['deadline'], 'd M Y')) ?>
                    </span>
                <?php endif; ?>
            </div>
            <div class="ibox-content">
                <div class="well"><?= nl2br(e($assignment['description'])) ?></div>

                <div class="meta-list">
                    <span class="meta-item"><i class="fa fa-user" aria-hidden="true"></i> <?= e($assignment['author_name']) ?></span>
                    <span class="meta-item"><i class="fa fa-paperclip" aria-hidden="true"></i> <?= e(file_display_name($assignment['file_path'])) ?></span>
                </div>

                <?php if (!$isOwner): ?>
                    <?php if ($submission === null): ?>
                        <div class="submission-status is-missing">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                            <div>
                                <div class="s-title">Belum dikumpulkan</div>
                                <div class="s-meta">Anda belum mengumpulkan tugas ini.</div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="submission-status is-submitted">
                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                            <div>
                                <div class="s-title">Sudah dikumpulkan</div>
                                <div class="s-meta">
                                    <a href="<?= url('/files/submissions/' . $submission['id']) ?>"><?= e(file_display_name($submission['file_path'])) ?></a>
                                    &middot; <?= e(format_datetime($submission['submitted_at'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="btn-row">
                    <a href="<?= url('/files/assignments/' . $assignment['id']) ?>" class="btn btn-white btn-sm">
                        <i class="fa fa-download" aria-hidden="true"></i> Unduh Tugas
                    </a>
                    <?php if (!$isOwner): ?>
                        <button type="button" class="btn btn-primary btn-sm"
                                data-toggle="modal" data-target="#modal-submission" data-modal-fill
                                data-form-action="<?= url('/assignments/' . $assignment['id'] . '/submissions') ?>">
                            <i class="fa fa-upload" aria-hidden="true"></i> <?= $submission === null ? 'Kumpul Tugas' : 'Kumpul Ulang' ?>
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-white btn-sm"
                                data-toggle="modal" data-target="#modal-assignment-edit" data-modal-fill
                                data-form-action="<?= url('/assignments/' . $assignment['id'] . '/update') ?>"
                                data-field-title="<?= e($assignment['title']) ?>"
                                data-field-description="<?= e($assignment['description']) ?>"
                                data-field-deadline="<?= e(substr((string) $assignment['deadline'], 0, 10)) ?>">
                            <i class="fa fa-pencil" aria-hidden="true"></i> Edit
                        </button>
                        <span class="spacer"></span>
                        <form method="POST" action="<?= url('/assignments/' . $assignment['id'] . '/delete') ?>"
                              data-confirm="Menghapus tugas juga menghapus semua pengumpulan, nilai, dan komentarnya. Lanjutkan?">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</button>
                        </form>
                    <?php endif; ?>
                </div>

                <?php $this->insert('classroom/partials/discussion', ['assignment' => $assignment, 'comments' => $comments]) ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php
/**
 * @var array<string, mixed> $classroom
 * @var array<int, array<string, mixed>> $assignments
 * @var array<string, mixed>|null $selectedAssignment
 * @var array<int, array<string, mixed>> $submissions
 */
?>
<?php if ($assignments === []): ?>
    <div class="empty-state">
        <i class="fa fa-list-ol" aria-hidden="true"></i>
        <p>Belum ada tugas untuk dinilai.</p>
    </div>
<?php else: ?>
    <div class="btn-group m-b-md">
        <button data-toggle="dropdown" class="btn btn-white dropdown-toggle" type="button">
            <i class="fa fa-tasks" aria-hidden="true"></i>
            <?= $selectedAssignment === null ? 'Pilih Tugas' : e($selectedAssignment['title']) ?>
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <?php foreach ($assignments as $assignment): ?>
                <li>
                    <a href="<?= url('/classrooms/' . $classroom['id'], ['assignment' => $assignment['id']]) ?>#tab-grades"><?= e($assignment['title']) ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php if ($selectedAssignment === null): ?>
        <div class="empty-state">
            <i class="fa fa-hand-o-up" aria-hidden="true"></i>
            <p>Pilih tugas di atas untuk mulai menilai.</p>
        </div>
    <?php elseif ($submissions === []): ?>
        <div class="empty-state">
            <i class="fa fa-inbox" aria-hidden="true"></i>
            <p>Belum ada siswa yang mengumpulkan tugas ini.</p>
        </div>
    <?php else: ?>
        <form id="form-grades" method="POST" action="<?= url('/assignments/' . $selectedAssignment['id'] . '/grades') ?>">
            <?= csrf_field() ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="col-xs-1">No</th>
                            <th>Nama Siswa</th>
                            <th>Tanggal Kumpul</th>
                            <th>File</th>
                            <th class="col-md-2">Nilai (0-100)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($submissions as $index => $submission): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= e($submission['student_name']) ?></td>
                                <td><?= e(format_datetime($submission['submitted_at'])) ?></td>
                                <td>
                                    <a href="<?= url('/files/submissions/' . $submission['id']) ?>"><?= e(file_display_name($submission['file_path'])) ?></a>
                                </td>
                                <td>
                                    <input type="number" class="form-control input-sm" name="grades[<?= (int) $submission['id'] ?>]"
                                           value="<?= e($submission['grade']) ?>" min="0" max="100" step="1" placeholder="Belum dinilai">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </form>

        <div class="btn-row">
            <button type="submit" form="form-grades" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Simpan Nilai</button>
            <span class="spacer"></span>
            <form method="POST" action="<?= url('/assignments/' . $selectedAssignment['id'] . '/grades/delete') ?>"
                  data-confirm="Hapus semua nilai untuk tugas ini?">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i> Hapus Semua Nilai</button>
            </form>
        </div>
    <?php endif; ?>
<?php endif; ?>

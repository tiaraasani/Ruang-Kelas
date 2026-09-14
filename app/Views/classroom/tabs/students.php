<?php
/** @var array<int, array{username: string, name: string}> $students */
?>
<?php if ($students === []): ?>
    <div class="empty-state">
        <i class="fa fa-users" aria-hidden="true"></i>
        <p>Belum ada siswa yang bergabung.</p>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="col-xs-1">No</th>
                    <th>Nama Siswa</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $index => $student): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= e($student['name']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

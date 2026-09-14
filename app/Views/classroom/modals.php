<?php
/**
 * Modal dialogs for the classroom page. Edit and submission modals get their
 * form action and field values from the triggering button (see app.js).
 *
 * @var array<string, mixed> $classroom
 * @var bool $isOwner
 */
?>
<?php if ($isOwner): ?>
    <?php
    $this->insert('partials/modal-form', [
        'id' => 'modal-material-create',
        'title' => 'Tambah Materi',
        'submit' => 'Posting',
        'action' => url('/classrooms/' . $classroom['id'] . '/materials'),
        'multipart' => true,
        'body' => 'classroom/modals/fields-material',
        'bodyData' => ['withFile' => true],
    ]);

    $this->insert('partials/modal-form', [
        'id' => 'modal-material-edit',
        'title' => 'Edit Materi',
        'submit' => 'Perbarui',
        'body' => 'classroom/modals/fields-material',
    ]);

    $this->insert('partials/modal-form', [
        'id' => 'modal-assignment-create',
        'title' => 'Tambah Tugas',
        'submit' => 'Posting',
        'action' => url('/classrooms/' . $classroom['id'] . '/assignments'),
        'multipart' => true,
        'body' => 'classroom/modals/fields-assignment',
        'bodyData' => ['withFile' => true],
    ]);

    $this->insert('partials/modal-form', [
        'id' => 'modal-assignment-edit',
        'title' => 'Edit Tugas',
        'submit' => 'Perbarui',
        'body' => 'classroom/modals/fields-assignment',
    ]);

    $this->insert('partials/modal-form', [
        'id' => 'modal-announcement-edit',
        'title' => 'Edit Pengumuman',
        'submit' => 'Perbarui',
        'body' => 'classroom/modals/fields-announcement',
    ]);
    ?>
<?php else: ?>
    <?php
    $this->insert('partials/modal-form', [
        'id' => 'modal-submission',
        'title' => 'Kumpul Tugas',
        'submit' => 'Kumpulkan',
        'multipart' => true,
        'body' => 'classroom/modals/fields-submission',
    ]);
    ?>
<?php endif; ?>

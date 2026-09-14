<?php
/**
 * Reusable Bootstrap modal wrapping a POST form. The body fields come from a
 * separate partial named by $body.
 *
 * @var string $id
 * @var string $title
 * @var string $submit
 * @var string $body
 * @var string $action
 * @var bool $multipart
 * @var array<string, mixed> $bodyData
 */
$action = $action ?? '';
$multipart = $multipart ?? false;
$submit = $submit ?? 'Simpan';
$bodyData = $bodyData ?? [];
?>
<div class="modal fade" id="<?= e($id) ?>" tabindex="-1" role="dialog" aria-labelledby="<?= e($id) ?>-title">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= e($action) ?>"<?= $multipart ? ' enctype="multipart/form-data"' : '' ?>>
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h4 class="modal-title" id="<?= e($id) ?>-title"><?= e($title) ?></h4>
                </div>
                <div class="modal-body">
                    <?php $this->insert($body, $bodyData) ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><?= e($submit) ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

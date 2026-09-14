<?php
/**
 * One-time status messages flashed by the previous request.
 */

$session = app()->session;
$success = $session->getFlash('success');
$error = $session->getFlash('error');
$errors = $session->getFlash('errors');
$errors = is_array($errors) ? $errors : [];

if ($success === null && $error === null && $errors === []) {
    return;
}
?>
<div class="flash-container">
    <?php if ($success !== null): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>

    <?php if ($error !== null): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ($errors !== []): ?>
        <div class="alert alert-danger">
            <ul class="list-unstyled m-b-none">
                <?php foreach ($errors as $message): ?>
                    <li><?= e($message) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>

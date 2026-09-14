<?php
$assignment = $assignment ?? [];
$comments = $comments ?? [];
$return = $return ?? '';
?>
<div class="discussion">
    <h5 class="discussion-title">
        <i class="fa fa-comments-o" aria-hidden="true"></i> Diskusi
        <span class="count"><?= count($comments) ?></span>
    </h5>

    <?php if ($comments === []): ?>
        <p class="text-muted">Belum ada komentar. Jadilah yang pertama menulis.</p>
    <?php else: ?>
        <ul class="comment-list">
            <?php foreach ($comments as $comment): ?>
                <li class="comment">
                    <span class="comment-avatar"><?= e(strtoupper(mb_substr((string) $comment['author_name'], 0, 1))) ?></span>
                    <div class="comment-body">
                        <div class="comment-head">
                            <span class="comment-author"><?= e($comment['author_name']) ?></span>
                            <span class="comment-date"><?= e(format_datetime($comment['created_at'])) ?></span>
                        </div>
                        <div class="comment-text"><?= nl2br(e($comment['content'])) ?></div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="<?= url('/assignments/' . $assignment['id'] . '/comments') ?>" class="comment-form">
        <?= csrf_field() ?>
        <?php if ($return !== ''): ?>
            <input type="hidden" name="return" value="<?= e($return) ?>">
        <?php endif; ?>
        <div class="input-group">
            <input type="text" name="content" class="form-control" placeholder="Tulis komentar untuk kelas ini" required maxlength="1000">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane" aria-hidden="true"></i> Kirim</button>
            </span>
        </div>
        <p class="help-block"><i class="fa fa-users" aria-hidden="true"></i> Komentar dapat dilihat oleh guru dan semua siswa di kelas ini.</p>
    </form>
</div>

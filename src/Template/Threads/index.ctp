<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><a href="/boards">板の一覧へ戻る</a></li>
        <li class="heading"><?= __('スレッドの一覧') ?></li>
        <?php foreach ($threads as $thread) : ?>
            <li><a href="/boards/view/<?= h($thread->id) ?>"><?= h($thread->name) ?></a></li>
        <?php endforeach; ?>
    </ul>
</nav>


<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><a href="/plaza"><?= __('広場に戻る') ?></a></li>
        <li class="heading"><?= __('広場の板一覧') ?></li>
        <?php foreach ($boards as $board) : ?>
            <li><a href="/boards/view/<?= h($board->id) ?>"><?= h($board->name) ?></a></li>
        <?php endforeach; ?>
    </ul>
</nav>
<div class="boards index large-9 medium-8 columns content">
	<h3><?= __('広場の板一覧') ?></h3>
    <p><?= __('') ?></p>
</div>


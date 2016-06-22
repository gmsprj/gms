<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('板一覧') ?></li>
        <?php foreach ($boards as $board) : ?>
            <li><a href="/boards/view/<?= h($board->id) ?>"><?= h($board->name) ?></a></li>
        <?php endforeach; ?>
    </ul>
</nav>
<div class="boards index large-9 medium-8 columns content">
	<h3><?= __('板一覧') ?></h3>
</div>


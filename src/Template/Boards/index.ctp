<ul>
<?php foreach ($boards as $board) : ?>
	<li><a href="/boards/view/<?= h($board->id) ?>"><?= h($board->name) ?></a></li>
<?php endforeach; ?>
</ul>


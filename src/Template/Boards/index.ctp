<ul>
<?php foreach ($boards as $board) : ?>
	<li><a href="/boards/board/<?= $board->id ?>"><?= $board->name ?></a></li>
<?php endforeach; ?>
</ul>


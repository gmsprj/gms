<ul>
<?php foreach ($boards as $board) : ?>
	<li><a href="/boards/view/<?= $board->id ?>"><?= $board->name ?></a></li>
<?php endforeach; ?>
</ul>


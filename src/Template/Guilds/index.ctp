<div class="boards index large-9 medium-8 columns content">
	<h3><?= __(h($site->name)) ?></h3>
    <p><?= __(h($site->description)) ?></p>

	<h3><?= __('ギルド一覧') ?></h3>
    <ul>
    <?php foreach ($guilds as $el): ?>
       <li><a href="/guilds/view/<?= $el->id ?>"><?= __(h($el->name)) ?></a></li> 
    <?php endforeach; ?>
    </ul>

    <?= $board->name ?>
</div>


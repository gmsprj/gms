<h3><?= h($guild->name) ?><?= __('ギルド') ?></h3>
<p><?= h($guild->name) ?><?= __('ギルド') ?>へようこそ。</p>
<ul>
<?php foreach ($threads as $thread) : ?>
    <li><a href="/threads/view/<?= h($thread->id) ?>"><?= h($thread->name) ?></a></li>
<?php endforeach; ?>
</ul>


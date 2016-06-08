<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><a href="/plaza"><?= __('広場に戻る') ?></a></li>
        <li class="heading"><?= __('ギルド一覧') ?></li>
        <?php foreach ($guilds as $el) : ?>
            <li>
                <?= $this->Html->link($el->name, ['controller' => 'Guilds', 'action' => 'view', $el->id]) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<div class="boards index large-9 medium-8 columns content">
	<h3><?= __('ギルド一覧') ?></h3>
    <p><?= __('各ギルドではメンバーによる議論や投票、決定等が可能。') ?></p>
</div>


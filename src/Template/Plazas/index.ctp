<?php
/**
 * 左のサイドメニュー。
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><a href="/boards"><?= __('広場の板一覧') ?></a></li>
        <?php foreach ($boards as $el) : ?>
            <li>
                <?= $this->Html->link($el->name, ['controller' => 'Boards', 'action' => 'view', $el->id]) ?>
            </li>
        <?php endforeach; ?>
        <li class="heading"><hr/></li>

        <li class="heading"><?= __('ロビー板のスレッド一覧') ?></li>
        <?php foreach ($threads as $el) : ?>
            <?php $name = $el->name . ' (' . $el->countPosts() . ')' ?>
            <li>
                <?= $this->Html->link($name, ['controller' => 'Threads', 'action' => 'view', $el->id]) ?>
            </li>
        <?php endforeach; ?>
        <li class="heading"><hr/></li>

        <li class="heading"><?= __('ギルド一覧') ?></li>
        <?php foreach ($guilds as $el) : ?>
            <li>
                <?= $this->Html->link($el->name, ['controller' => 'Guilds', 'action' => 'view', $el->id]) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php
/**
 * 右のコンテンツ。
 */
?>
<div class="boards index large-9 medium-8 columns content">
	<h3><?= __('広場') ?></h3>
    <p><?= __('広場。ユーザーが入り乱れてワイワイガヤガヤする所。') ?></p>
    <p><?= __('広場のスレッドはメンバー/ゲスト共に読み書き可能。') ?></p>

	<?php /* 閲覧中スレッド */ ?>
	<h4><?= h($board->name) ?> &gt; <?= h($thread->name) ?></h4>
    <ul style="list-style:none;">
    <?php $i = 1; foreach ($posts as $el) : ?>
        <?= $el->render(['index' => $i++]) ?>
    <?php endforeach; ?>
    </ul>

	<?php /* TODO: Threads/thread.ctp と重複 */ ?>
	<?= $this->Form->create(null, [
		'type' => 'post',
		'url' => ['controller' => 'Posts', 'action' => 'add']]
	) ?>
	<?= $this->Form->label('name', __('名前：')) ?>
	<?= $this->Form->hidden('threadId', ['value' => h($thread->id)]) ?>
	<?= $this->Form->text('name', ['value' => h($postName)]) ?>
	<?= $this->Form->label('name', __('内容：')) ?>
	<?= $this->Form->textarea('content', ['value' => __('内容なし')]) ?>
	<?= $this->Form->submit(__('投稿')) ?>
	<?= $this->Form->end() ?>
</div>


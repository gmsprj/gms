<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><a href="/boards"><?= __('広場の板一覧') ?></a></li>
        <?php foreach ($boards as $el) : ?>
            <li>
                <?= $this->Html->link($el->name, ['controller' => 'Boards', 'action' => 'view', $el->id]) ?>
            </li>
        <?php endforeach; ?>
        <li class="heading">----</li>

        <li class="heading"><?= __('ロビー板のスレッド一覧') ?></li>
        <?php foreach ($dispThreads as $el) : ?>
            <?php $name = $el->name . ' (' . $el->countPosts() . ')' ?>
            <li>
                <?= $this->Html->link($name, ['controller' => 'Threads', 'action' => 'view', $el->id]) ?>
            </li>
        <?php endforeach; ?>
        <li class="heading">----</li>

        <li class="heading"><?= __('ギルド一覧') ?></li>
        <?php foreach ($dispGuilds as $el) : ?>
            <li>
                <?= $this->Html->link($el->name, ['controller' => 'Guilds', 'action' => 'view', $el->id]) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<div class="boards index large-9 medium-8 columns content">
	<h3><?= __('広場') ?></h3>
    <p><?= __('広場。ユーザーが入り乱れてワイワイガヤガヤする所。') ?></p>

	<!-- 閲覧中スレッド -->
	<h4><?= $dispBoard->name ?> &gt; <?= $dispThread->name ?></h4>
    <ul style="list-style:none;">
    <?php $i = 1; foreach ($dispPosts as $el) : ?>
        <li>
        <?php
            echo '<div>';
            echo '<p style="margin:0;">' . $i++ . ': ' . h($el->name) . ': ' . h($el->created->i18nFormat('YYYY/MM/dd HH:mm:ss')) . ': ' . '</p>';
            echo '<p>' . h($el->content) . '</p>';
            echo '</div>';
        ?>
        </li>
    <?php endforeach; ?>
    </ul>

	<?php /* TODO: Threads/thread.ctp と重複 */ ?>
	<?= $this->Form->create(null, [
		'type' => 'post',
		'url' => ['controller' => 'Plaza', 'action' => 'post']]
	) ?>
	<?= $this->Form->label('name', '名前：') ?>
	<?= $this->Form->hidden('threadId', ['value' => h($dispThread->id)]) ?>
	<?= $this->Form->text('name', ['value' => $postName]) ?>
	<?= $this->Form->label('name', '内容：') ?>
	<?= $this->Form->textarea('content', ['value' => '内容なし']) ?>
	<?= $this->Form->submit('投稿') ?>
	<?= $this->Form->end() ?>
</div>


<div class="container">
	<!-- 板のリスト -->
	<b><a href="/boards">板のリスト</a></b>
	<ul>
	<?php foreach ($boards as $el) : ?>
		<li>
			<?= $this->Html->link($el->name, ['controller' => 'Boards', 'action' => 'view', $el->id]) ?>
		</li>
	<?php endforeach; ?>
	</ul>

	<?php /* TODO: Boards/board.ctp と重複 */ ?>
	<!-- スレッドリスト -->
	<b><?= $dispBoard->name ?>板のスレッドリスト</b>
	<ul>
	<?php foreach ($dispThreads as $el) : ?>
		<li>
			<?= $this->Html->link($el->name, ['controller' => 'Threads', 'action' => 'view', $el->id]) ?>
			(<?= $el->countPosts() ?>)
		</li>
	<?php endforeach; ?>
	</ul>

	<!-- 閲覧中スレッド -->
	<b>「<?= $dispThread->name ?>」スレッドのポストリスト</b>
	<ul>
	<?php $i = 1; foreach ($dispPosts as $el) : ?>
		<li>
		<?php
			echo $i++ . ': ' . h($el->name) . ': ' . h($el->created->i18nFormat('YYYY/MM/dd HH:mm:ss')) . ': ' . h($el->content);
		?>
		</li>
	<?php endforeach; ?>
	</ul>

	<?php /* TODO: Threads/thread.ctp と重複 */ ?>
	<!-- 投稿フォーム -->
	<b>「<?= h($dispThread->name) ?>」スレッドへの投稿フォーム</b>
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


<div class="container">
	<!-- 板のリスト -->
	<b><a href="/boards">板のリスト</a></b>
	<ul>
	<?php foreach ($boards as $el) : ?>
		<li>
			<a href="boards/board/<?= h($el->id) ?>"><?= h($el->name) ?></a>
		</li>
	<?php endforeach; ?>
	</ul>

	<?php /* TODO: Boards/board.ctp と重複 */ ?>
	<!-- スレッドリスト -->
	<b><?= $dispBoard->name ?>板のスレッドリスト</b>
	<ul>
	<?php foreach ($dispThreads as $el) : ?>
		<li><a href="threads/thread/<?= h($el->id) ?>"><?= h($el->name) ?></a></li>
	<?php endforeach; ?>
	</ul>

	<?php /* TODO: Threads/thread.ctp と重複 */ ?>
	<!-- 閲覧中スレッド -->
	<b>「<?= $dispThread->name ?>」スレッドのポストリスト</b>
	<ul>
	<?php $i = 1; foreach ($dispPosts as $el) : ?>
		<li>
		<?php
			echo $i++ . ': ' . h($el->name) . ': ' . h($el->created) . ': ' . h($el->content);
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
	<?= $this->Form->text('name', ['value' => '名無しさん']) ?>
	<?= $this->Form->label('name', '内容：') ?>
	<?= $this->Form->textarea('content', ['value' => '内容なし']) ?>
	<?= $this->Form->submit('投稿') ?>
	<?= $this->Form->end() ?>
</div>


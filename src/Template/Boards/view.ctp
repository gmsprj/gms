<h3><?= h($board->name) ?></h3>

<!-- スレッドリスト -->
<ul>
<?php foreach ($threads as $el) : ?>
	<li>
		<?= $this->Html->link($el->name, ['controller' => 'Threads', 'action' => 'view', $el->id]) ?>
		(<?= $el->countPosts() ?>)
	</li>
<?php endforeach; ?>
</ul>

<!-- 新規スレッド投稿フォーム -->
<h3>新規スレッド</h3>
<?= $this->Form->create(null, [
	'type' => 'post',
	'url' => ['controller' => 'Boards', 'action' => 'post']]
) ?>
<?= $this->Form->hidden('boardId', ['value' => h($board->id)]) ?>
<?= $this->Form->label('threadName', __('スレッド名：')) ?>
<?= $this->Form->text('threadName', ['value' => __('スレッド名')]) ?>
<?= $this->Form->label('postName', __('名前：')) ?>
<?= $this->Form->text('postName', ['value' => h($postName)]) ?>
<?= $this->Form->label('postContent', __('内容：')) ?>
<?= $this->Form->textarea('postContent', ['value' => __('内容なし')]) ?>
<?= $this->Form->submit(__('投稿')) ?>
<?= $this->Form->end() ?>


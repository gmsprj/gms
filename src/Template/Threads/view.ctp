<h3><?= $board->name ?> &gt; <?= $thread->name ?></h3>

<ul>
<?php $i = 1; foreach ($posts as $el) : ?>
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

<?php /* TODO: Plaza/index.ctp と重複 */ ?>
<!-- 投稿フォーム -->
<?= $this->Form->create(null, [
	'type' => 'post',
	'url' => ['controller' => 'Threads', 'action' => 'post']]
) ?>
<?= $this->Form->label('name', __('名前：')) ?>
<?= $this->Form->hidden('threadId', ['value' => h($thread->id)]) ?>
<?= $this->Form->text('name', ['value' => h($postName)]) ?>
<?= $this->Form->label('name', __('内容：')) ?>
<?= $this->Form->textarea('content', ['value' => __('内容なし')]) ?>
<?= $this->Form->submit(__('投稿')) ?>
<?= $this->Form->end() ?>


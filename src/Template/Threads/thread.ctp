<h3><?= $board->name ?> &gt; <?= $thread->name ?></h3>

<?php /* TODO: Plaza/index.ctp と重複 */ ?>
<ul>
<?php $i = 1; foreach ($posts as $el) : ?>
	<li>
	<?php
		echo $i++ . ': ' . $el->name . ': ' . $el->created . ': ' . $el->content;
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
<?= $this->Form->label('name', '名前：') ?>
<?= $this->Form->hidden('threadId', ['value' => $thread->id]) ?>
<?= $this->Form->text('name', ['value' => '名無しさん']) ?>
<?= $this->Form->label('name', '内容：') ?>
<?= $this->Form->textarea('content', ['value' => '内容なし']) ?>
<?= $this->Form->submit('投稿') ?>
<?= $this->Form->end() ?>


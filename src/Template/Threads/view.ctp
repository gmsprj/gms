<div>
    <h3><?= $board->name ?> &gt; <?= $thread->name ?></h3>
    <hr/>

    <!-- ポスト -->
    <ul>
    <?php $i = 1; foreach ($posts as $el) : ?>
        <?= $el->render(['index' => $i++]) ?>
    <?php endforeach; ?>
    </ul>

    <!-- 投稿フォーム -->
    <?php if ($board->parent_name != 'guilds' || $user) : ?>
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
    <?php endif; ?>
</div>


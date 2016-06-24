<div ng-app="boards">
    <boards-view></boards-view>

    <!-- 新規スレッド投稿フォーム -->
    <?php if ($board->parent_name != 'guilds' || $user) : ?>
        <h4><?= __('新規スレッド') ?></h4>
        <?= $this->Form->create(null, [
            'type' => 'post',
            'url' => ['controller' => 'Threads', 'action' => 'add']]
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
    <?php endif; ?>
</div>


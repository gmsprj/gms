<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <?php if ($board->parent_name == 'guilds') : ?>
            <li class="heading"><a href="/plaza"><?= __('広場に出る') ?></a></li>
            <li class="heading"><a href="/guilds/view/<?= h($board->parent_id) ?>"><?= __('ギルドに戻る') ?></a></li>
            <li class="heading"><hr/></li>

            <li class="heading"><?= h($board->name) ?><?= __('ギルドのスレッド一覧') ?></li>
        <?php else: ?>
            <li class="heading"><a href="/plaza"><?= __('広場に戻る') ?></a></li>
            <li class="heading"><a href="/boards"><?= __('板の一覧に戻る') ?></a></li>
            <li class="heading"><hr/></li>

            <li class="heading"><?= h($board->name) ?><?= __('のスレッド一覧') ?></li>
        <?php endif; ?>

        <?php foreach ($threads as $el) : ?>
            <li>
                <?= $this->Html->link($el->name, ['controller' => 'Threads', 'action' => 'view', $el->id]) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<div class="boards index large-9 medium-8 columns content">
    <h3><?= h($board->name) ?></h3>
    <p><?= h($board->description) ?></p>

    <!-- 新規スレッド投稿フォーム -->
    <?php if ($board->parent_name != 'guilds' || $user) : ?>
        <h4><?= __('新規スレッド') ?></h4>
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
    <?php endif; ?>
</div>


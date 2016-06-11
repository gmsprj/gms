<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <?php if ($board->parent_name == 'guilds') : ?>
            <li class="heading"><a href="/plazas"><?= __('広場に出る') ?></a></li>
            <li class="heading"><a href="/guilds/view/<?= $board->parent_id ?>"><?= __('ギルドに戻る') ?></a></li>
            <li class="heading"><a href="/boards/view/<?= $board->id ?>"><?= h($board->name) ?><?= __('のトップに戻る') ?></a></li>
            <li class="heading"><hr/></li>
            <li class="heading"><?= h($board->name) ?><?= __('ギルドのスレッド一覧') ?></li>
            <?php foreach ($threads as $el) : ?>
                <li>
                    <?= $this->Html->link($el->name, ['controller' => 'Threads', 'action' => 'view', $el->id]) ?>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="heading"><a href="/plazas"><?= __('広場に戻る') ?></a></li>
            <li class="heading"><a href="/boards"><?= __('板の一覧に戻る') ?></a></li>
            <li class="heading"><a href="/boards/view/<?= $board->id ?>"><?= h($board->name) ?><?= __('のトップに戻る') ?></a></li>
            <li class="heading"><hr/></li>
            <li class="heading"><?= h($board->name) ?><?= __('板のスレッド一覧') ?></li>
            <?php foreach ($threads as $el) : ?>
                <li>
                    <?= $this->Html->link($el->name, ['controller' => 'Threads', 'action' => 'view', $el->id]) ?>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</nav>
<div class="boards index large-9 medium-8 columns content">
    <h3><?= $board->name ?> &gt; <?= $thread->name ?></h3>

    <ul style="list-style:none;">
    <?php $i = 1; foreach ($posts as $el) : ?>
        <?= $el->render(['index' => $i++]) ?>
    <?php endforeach; ?>
    </ul>

    <?php /* TODO: Plazas/index.ctp と重複 */ ?>
    <!-- 投稿フォーム -->
    <?php if ($board->parent_name != 'guilds' || $user) : ?>
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
    <?php endif; ?>
</div>


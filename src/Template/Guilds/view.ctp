<div ng-app="guilds">
    <guilds-view></guilds-view>
</div>
<?= $this->Html->script('fwu/guilds.js') ?>

<div>
    <h3><?= h($guild->name) ?></h3>
    <p><?= h($guild->description) ?></p>

    <hr/>

    <h4><?= __('入会受付') ?></h4>
    <?php if ($user && $user['guild_id'] != $guild->id) : ?>
        <p><?= __('') ?></p>
        <?= $this->Form->create(null, [
            'type' => 'post',
            'url' => ['controller' => 'Guilds', 'action' => 'entry']]
        ) ?>
        <?= $this->Form->hidden('userId', ['value' => h($user['id'])]) ?>
        <?= $this->Form->hidden('guildId', ['value' => h($guild->id)]) ?>
        <?= $this->Form->submit(__('このギルドに入会する')) ?>
        <?= $this->Form->end() ?>

    <?php elseif ($user['guild_id'] == $guild->id) : ?>
        <p><?= __('入会中。') ?></p>
    
    <?php else : ?>
        <p><?= __('入会には') ?><a href="/users/signin"><?= __('サインイン') ?></a><?= __('が必要です。') ?></p>

    <?php endif; ?>

    <hr/>

    <h4><?= __('ギルドのスレッド一覧') ?></h4>
    <ul>
    <?php foreach ($threads as $el) : ?>
        <li><a href="/threads/view/<?= $el->id ?>"><?= $el->name ?></a></li>
    <?php endforeach; ?>
    </ul>
</div>

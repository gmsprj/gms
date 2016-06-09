<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><a href="/plaza"><?= __('広場に出る') ?></a></li>
        <li class="heading"><a href="/guilds"><?= __('ギルド一覧に戻る') ?></a></li>
        <li class="heading"><hr/></li>

        <li class="heading"><?= __('ギルド専用板') ?></li>
        <li><a href="/boards/view/<?= $board->id ?>"><?= $board->name ?></a></li>
        <li class="heading"><hr/></li>

        <li class="heading"><?= __('ギルド専用板のスレッド一覧') ?></li>
    <?php foreach ($threads as $el) : ?>
        <li><a href="/threads/view/<?= $el->id ?>"><?= $el->name ?></a></li>
    <?php endforeach; ?>
    </ul>
</nav>
<div class="boards index large-9 medium-8 columns content">
    <h3><?= h($guild->name) ?></h3>
    <p><?= h($guild->description) ?></p>

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
</div>

<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($site->name) ?> - <?= $this->fetch('title') ?></title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <!-- test -->
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="top-bar expanded" data-topbar role="navigation">
        <ul class="title-area large-3 medium-4 columns">
            <li class="name">
                <h1><a href="/plazas"><?= h($site->name) ?></a></h1>
            </li>
        </ul>
        <div class="top-bar-section">
            <ul class="right">
                <?php if ($user) : ?>
                    <li><a href="/users/view/<?= h($user['id']) ?>"><?= __('ようこそ ') ?><?= h($user['name']) ?><?= __(' さん') ?></a></li>
                <?php endif; ?>
                <li><a href="/plazas"><?= __('広場') ?></a></li>
                <li><a href="/guilds"><?= __('ギルド一覧') ?></a></li>
                <li><a href="/users/signup"><?= __('サインアップ') ?></a></li>
                <li><a href="/users/signin"><?= __('サインイン') ?></a></li>
                <li><a href="/users/signout"><?= __('サインアウト') ?></a></li>
                <li><a href="/entrance"><?= __('トップページ') ?></a></li>
            </ul>
        </div>
    </nav>
    <?= $this->Flash->render() ?>
    <div class="container clearfix">
        <?= $this->fetch('content') ?>
    </div>
    <footer>
    </footer>
</body>
</html>


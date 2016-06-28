<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($site->name) ?> - <?= $this->fetch('title') ?></title>
    <?= $this->Html->meta('icon') ?>

    <!-- css/ライブラリ -->
    <?= $this->Html->css('lib/bootstrap/bootstrap-theme.min.css') ?>
    <?= $this->Html->css('lib/bootstrap/bootstrap.min.css') ?>

    <!-- css/gm -->
    <?= $this->Html->css('gm/style.css') ?>

    <!-- js/ライブラリ -->
    <?= $this->Html->script('lib/jquery/jquery.min.js') ?>
    <?= $this->Html->script('lib/bootstrap/bootstrap.min.js') ?>
    <?= $this->Html->script('lib/angular/angular.min.js') ?>
    <?= $this->Html->script('lib/angular/angular-route.js') ?>

    <!-- js/gm -->
    <?= $this->Html->script('gm/gm.js') ?>
    <?= $this->Html->script('gm/component/navbar.component.js') ?>
    <?= $this->Html->script('gm/component/threads.component.js') ?>
    <?= $this->Html->script('gm/component/boards.component.js') ?>
    <?= $this->Html->script('gm/component/guilds.component.js') ?>
    <?= $this->Html->script('gm/component/users.component.js') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <!-- test -->
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/"><?= $site->name ?></a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="/guilds"><?= __('ギルド一覧') ?></a></li>
            </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <?php if ($user): ?>
                    <?= h($user['name']) ?>
                <?php else: ?>
                    <?= __('ゲスト') ?>
                <?php endif; ?>
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <?php if ($user): ?>
                    <li><a href="/users/view/<?= h($user['id']) ?>"><?= __('マイページ') ?></a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="/users/signout"><?= __('サインアウト') ?></a></li>
                <?php else: ?>
                    <li><a href="/users/signup"><?= __('サインアップ') ?></a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="/users/signin"><?= __('サインイン') ?></a></li>
                <?php endif; ?>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div ng-app="gmGuilds">
        <gm-navbar></gm-navbar>
    </div>

    <?= $this->Flash->render() ?>
    <!--<?= $this->fetch('content') ?>-->

    <footer>
    </footer>
</body>
</html>


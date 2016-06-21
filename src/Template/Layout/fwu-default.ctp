<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($site->name) ?> - <?= $this->fetch('title') ?></title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->script('jquery.min.js') ?>
    <?= $this->Html->script('bootstrap/bootstrap.min.js') ?>
    <?= $this->Html->css('bootstrap/bootstrap-theme.min.css') ?>
    <?= $this->Html->css('bootstrap/bootstrap.min.css') ?>
    <?= $this->Html->css('fwu/bootstrap.css') ?>
    <?= $this->Html->css('fwu/style.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <!-- test -->
    <?= $this->fetch('script') ?>
</head>
<body>

    <!-- Copy from http://getbootstrap.com/components/ (Navbar) -->
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><?= $site->name ?></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="/plazas"><?= __('広場') ?></a></li>
                <li><a href="/guilds"><?= __('ギルド一覧') ?></a></li>
                <li role="separator" class="divider"></li>
                <li><a href="/entrances"><?= __('トップページ') ?></a></li>
            </ul>
            <!--
              <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Search">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
              </form>
              -->
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
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>

    <?= $this->Flash->render() ?>
    <div class="container">
        <?= $this->fetch('content') ?>
    </div>
    <footer>
    </footer>
</body>
</html>


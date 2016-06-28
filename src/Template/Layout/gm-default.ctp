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
    <?= $this->Html->css('style.css') ?>

    <!-- js/ライブラリ -->
    <?= $this->Html->script('lib/angular/angular.min.js') ?>
    <?= $this->Html->script('lib/angular/angular-route.js') ?>

    <!-- js/gm -->
    <?= $this->Html->script('app.js') ?>
    <?= $this->Html->script('component/sites.component.js') ?>
    <?= $this->Html->script('component/guilds.component.js') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <!-- test -->
    <?= $this->fetch('script') ?>
</head>
<body>
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
</body>
</html>


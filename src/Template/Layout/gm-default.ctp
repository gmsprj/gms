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
    <!--<?= $this->Html->script('lib/jquery/jquery.min.js') ?>-->
    <!--<?= $this->Html->script('lib/bootstrap/bootstrap.min.js') ?>-->
    <?= $this->Html->script('lib/angular/angular.min.js') ?>
    <?= $this->Html->script('lib/angular/angular-route.js') ?>

    <!-- js/gm -->
    <?= $this->Html->script('gm/gm.js') ?>
    <?= $this->Html->script('gm/component/sites.component.js') ?>
    <?= $this->Html->script('gm/component/guilds.component.js') ?>
    <?= $this->Html->script('gm/component/boards.component.js') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <!-- test -->
    <?= $this->fetch('script') ?>
</head>
<body ng-app="gm">
    <gm-sites-header></gm-sites-header>
    <hr/>
    <gm-guilds-header></gm-guilds-header>
    <hr/>
    <gm-guilds-list></gm-guilds-list>
    <hr/>
    <gm-sites-footer></gm-sites-footer>
</body>
</html>


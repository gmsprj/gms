<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMS</title>
    <?= $this->Html->meta('icon') ?>

    <!-- css/lib -->
    <?= $this->Html->css('lib/bootstrap/bootstrap-theme.min.css') ?>
    <?= $this->Html->css('lib/bootstrap/bootstrap.min.css') ?>

    <!-- css -->
    <?= $this->Html->css('style.css') ?>

    <!-- js/lib -->
    <?= $this->Html->script('lib/jquery/jquery.min.js') ?>
    <?= $this->Html->script('lib/bootstrap/bootstrap.min.js') ?>
    <?= $this->Html->script('lib/angular/angular.min.js') ?>
    <?= $this->Html->script('lib/angular/ui-bootstrap.min.js') ?>

    <!-- js -->
    <?= $this->Html->script('app.js') ?>
    <?= $this->Html->script('component/sites.component.js') ?>
    <?= $this->Html->script('component/docs.component.js') ?>
    <?= $this->Html->script('component/guilds.component.js') ?>
    <?= $this->Html->script('component/sites.component.js') ?>
    <?= $this->Html->script('component/boards.component.js') ?>
    <?= $this->Html->script('component/threads.component.js') ?>
    <?= $this->Html->script('component/users.component.js') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <!-- test -->
    <?= $this->fetch('script') ?>
</head>
<body>
    <div class="gm-background">
        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
    </div>
</body>
</html>


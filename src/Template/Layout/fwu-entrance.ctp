<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        FreelanceWorkersUnion（仮） - <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('bootstrap/bootstrap.min.css') ?>
    <?= $this->Html->css('bootstrap/bootstrap-theme.min.css') ?>
    <?= $this->Html->script('jquery.min.js') ?>
    <?= $this->Html->script('bootstrap/bootstrap.min.js') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <!-- test -->
    <?= $this->fetch('script') ?>
</head>
<body>
    <?= $this->Flash->render() ?>
    <div class="container clearfix">
        <?= $this->fetch('content') ?>
    </div>
</body>
</html>


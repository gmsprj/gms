<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><a href="/plaza"><?= __('広場に出る') ?></a></li>
        <li class="heading"><a href="/guilds"><?= __('ギルドの一覧に戻る') ?></a></li>
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
    <h3><?= h($guild->name) ?><?= __('ギルド') ?></h3>
    <p><?= h($guild->name) ?><?= __('ギルドへようこそ！') ?></p>
</div>

<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><a href="/guilds"><?= __('ギルド一覧に戻る') ?></a></li>
    </ul>
</nav>
<div class="boards index large-9 medium-8 columns content">
    <ul>
        <li><?= __('ID: ') ?><?= h($user['id']) ?></li>
        <li><?= __('名前: ') ?><?= h($user['name']) ?></li>
    </ul>
</div>


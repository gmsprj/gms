<h3>Guilds</h3>
<ul>
<?php foreach  ($guilds as $guild) : ?>
    <li><a href="/guilds/view/<?= h($guild->id) ?>"><?= h($guild->name) ?></a></li>    
<?php endforeach; ?>
</ul>


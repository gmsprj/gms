<h3>Guilds</h3>
<ul>
<?php foreach  ($guilds as $guild) : ?>
    <li><a href="/guilds/view/<?= $guild->id ?>"><?= $guild->name ?></a></li>    
<?php endforeach; ?>
</ul>


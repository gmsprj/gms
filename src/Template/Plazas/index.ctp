<div class="row">
    <div class="col-md-12">
        <h3><?= __(h($plaza->name)) ?></h3>
        <p><?= __(h($plaza->description)) ?></p>
    </div>
</div>

<div class="row">

    <!-- col -->
    <div class="col-md-2">
        <h4><a href="/boards"><?= __('広場の板一覧') ?></a></h4>
        <ul class="side-nav">
            <?php foreach ($boards as $el) : ?>
                <li>
                    <?= $this->Html->link($el->name, ['controller' => 'Boards', 'action' => 'view', $el->id]) ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <hr/>

        <h4><a href="/guilds"><?= __('ギルド一覧') ?></a></h4>
        <ul>
            <?php foreach ($guilds as $el) : ?>
                <li>
                    <?= $this->Html->link($el->name, ['controller' => 'Guilds', 'action' => 'view', $el->id]) ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($user): ?>
            <hr/>

            <h4><?= __('ギルドの板一覧') ?></h4>
            <ul>
                <?php foreach ($guilds as $el) : ?>
                    <li>
                        <?= $this->Html->link($el->name, ['controller' => 'Guilds', 'action' => 'view', $el->id]) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <!-- col -->
    <div class="col-md-3">
        <h4><?= __('ロビー板のスレッド一覧') ?></h4>
        <?php foreach ($threads as $el) : ?>
            <?php $name = $el->name . ' (' . $el->countPosts() . ')' ?>
            <li>
                <?= $this->Html->link($name, ['controller' => 'Threads', 'action' => 'view', $el->id]) ?>
            </li>
        <?php endforeach; ?>
    </div>

    <!-- col -->
    <div class="col-md-7">

        <!-- 閲覧中スレッド -->
        <h4><?= h($board->name) ?> &gt; <?= h($thread->name) ?></h4>

        <ul class="fwu-posts">
        <?php $i = 1; foreach ($posts as $el) : ?>
            <?= $el->render(['index' => $i++]) ?>
        <?php endforeach; ?>
        </ul>

        <form method="post" class="form-horizontal" accept-charset="utf-8" action="/posts/add">
            <div style="display:none;">
                <input type="hidden" name="_method" value="POST"/>
                <input type="hidden" name="_csrfToken" value="399a48582bbdc5419a16e69fdd9efddd45eb027a57c687bacef6dca0c303f77dfd0317a0b8448624cec75819bf12488027fd299d12150d556e16cd4e84b28b68"/>
                <input type="hidden" name="threadId" value="1"/>
            </div>

            <div class="form-group">
                <label class="col-sm-1 control-label" for="name">名前</label>
                <div class="col-sm-11">
                    <input name="name" type="text" class="form-control" id="InputEmail" placeholder="名前" value="<?= h($postName) ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label" for="InputPassword">内容</label>
                <div class="col-sm-11">
                    <textarea name="content" class="form-control" id="InputPassword" placeholder="内容" rows="5"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-1 col-sm-12">
                    <button type="submit" class="btn btn-default">書き込む</button>
                </div>
            </div>
        </form>

    </div>
</div>



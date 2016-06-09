<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><a href="/plaza"><?= __('広場に出る') ?></a></li>
    </ul>
</nav>
<div class="boards index large-9 medium-8 columns content">
    <div class="users form">
        <?= $this->Flash->render('auth') ?>
        <?= $this->Form->create() ?>
        <fieldset>
            <legend><?= __('登録したメールアドレスとパスワードを入力してください。') ?></legend>
            <?= $this->Form->input('email') ?>
            <?= $this->Form->input('password') ?>
        </fieldset>
        <?= $this->Form->button(__('Signin')); ?>
        <?= $this->Form->end() ?>
    </div>
</div>

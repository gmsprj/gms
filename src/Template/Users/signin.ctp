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

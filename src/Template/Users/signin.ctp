<div>
    <h3><?= __('サインイン') ?></h3>
    <hr/>

    <div>
        <?= $this->Flash->render('auth') ?>
        <?= $this->Form->create() ?>
        <fieldset>
            <legend><a href="/users/signup"><?= __('サインアップ') ?></a><?= __('で登録したメールアドレスとパスワードを入力してください。') ?></legend>
            <?= $this->Form->input('email') ?>
            <?= $this->Form->input('password') ?>
        </fieldset>
        <?= $this->Form->button(__('Signin')); ?>
        <?= $this->Form->end() ?>
    </div>
</div>


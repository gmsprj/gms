<div ng-app="gm">
    <gm-sites-header></gm-sites-header>
    <div class="container">
        <h3><?= __('サインイン') ?></h3>
        <hr/>

        <div>
            <?= $this->Flash->render('auth') ?>
            <?= $this->Form->create() ?>
            <fieldset>
                <legend><a target="_self" href="/users/signup"><?= __('サインアップ') ?></a><?= __('で登録したメールアドレスとパスワードを入力してください。') ?></legend>
                <?= $this->Form->input('email') ?>
                <?= $this->Form->input('password') ?>
            </fieldset>
            <?= $this->Form->button(__('Signin')); ?>
            <?= $this->Form->end() ?>
        </div>
    </div>

    <gm-sites-footer></gm-sites-footer>
</div>


<div ng-app="gm">
    <sites-header></sites-header>
    <div class="container">
        <div class="boards index large-9 medium-8 columns content">
            <h3><?= __('サインアップ') ?></h3>

            <div class="users form">
                <?= $this->Flash->render('auth') ?>
                <?= $this->Form->create() ?>
                <fieldset>
                <legend><?= __('登録する名前とメールアドレス、パスワードを入力してください。') ?></legend>
                <?= $this->Form->input('name') ?>
                <?= $this->Form->input('email') ?>
                <?= $this->Form->input('password') ?>
                </fieldset>
                <?= $this->Form->button(__('Signup')); ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    <sites-footer></sites-footer>
</div>

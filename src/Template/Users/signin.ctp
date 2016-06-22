<div class="boards index large-9 medium-8 columns content">
    <h3><?= __('サインイン') ?></h3>
    <!-- TODO: debug -->
    <p>皆には内緒やけどこのデバッグ用のユーザーでサインインできるで。</p>
    <ul>
        <li>Email: aaa@aaa.com</li>
        <li>Password: aaa</li>
    </ul>
    <ul>
        <li>Email: bbb@bbb.com</li>
        <li>Password: bbb</li>
    </ul>
    <div class="users form">
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

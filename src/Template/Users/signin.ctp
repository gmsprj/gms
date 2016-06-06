<div class="users form">
    <?= $this->Flash->render('auth') ?>
    <?= $this->Form->create() ?>
    <fieldset>
    <legend><?= __('Please enter your username and password') ?></legend>
    <?= $this->Form->input('name') ?>
    <?= $this->Form->input('password') ?>
    </fieldset>
    <?= $this->Form->button(__('Signin')); ?>
    <?= $this->Form->end() ?>
</div>

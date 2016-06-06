<div class="users form">
    <?= $this->Flash->render('auth') ?>
    <?= $this->Form->create() ?>
    <fieldset>
    <legend><?= __('Please enter your username and email and password') ?></legend>
    <?= $this->Form->input('name') ?>
    <?= $this->Form->input('email') ?>
    <?= $this->Form->input('password') ?>
    </fieldset>
    <?= $this->Form->button(__('Signup')); ?>
    <?= $this->Form->end() ?>
</div>

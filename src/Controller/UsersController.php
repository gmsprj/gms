<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table;
use Cake\Log\Log;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;

class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
        $this->viewBuilder()->layout('fwu-default');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow([
            'signup',
            'signin',
            'signout',
            'signinRedirect',
        ]);
    }

    public function signup()
    {
        if ($this->request->is('post')) {
            // 登録時のギルドIDは 1
            $this->request->data['guild_id'] = 1;

            $user = $this->Users->newEntity();
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('サインアップしました。'));
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('サインアップに失敗しました。もう一度トライしてください。'));
            Log::write('error', json_encode($user->errors()));
        }
    }

    public function signin()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                $this->Flash->success(__('サインインしました。'));
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('サインインに失敗しました。もう一度トライしてください。'));
        }
    }

    public function signout()
    {
        $this->Flash->success(__('サインアウトました。'));
        return $this->redirect($this->Auth->logout());
    }

    public function signinRedirect()
    {
        $user = $this->Auth->user();
        if (!$user) {
            return $this->redirect(['controller' => 'Users', 'action' => 'signin']);
        }

        return $this->redirect(['controller' => 'Guilds', 'action' => 'view', $user['guild_id']]);
    }

    public function view($id = null)
    {
        if (!$this->request->is('get')) {
                return $this->redirect(['controller' => 'Plazas', 'action' => 'index']);
        }

        $user = $this->Auth->user();
        if (!$user) {
            return $this->redirect(['controller' => 'Plazas', 'action' => 'index']);
        }
    }
}

?>


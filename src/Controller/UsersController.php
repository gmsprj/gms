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
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['signup', 'signin', 'signout']);
    }

    public function signup()
    {
        if (!$this->request->is('post')) {
            return;
        }

        $name = $this->request->data('name');
        $email = $this->request->data('email');

        // TODO: 平文パスワードの即ハッシュ化
        $password = $this->request->data('password');
        $hasher = new DefaultPasswordHasher();
        $hashedPwd = $hasher->hash($password);

        // ユーザーの作成
        $usersTable = TableRegistry::get('Users');
        $newUser = $usersTable->newEntity([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'state' => 0,
        ]);

        if ($newUser->errors()) {
            $this->Flash->error(__('入力が不正です。'));
            Log::write('error', $newUser->toString());
            return;    
        }

        if ($usersTable->save($newUser)) {
            Log::write('debug', $newUser->toString());
            $this->redirect([
                'controller' => 'Plaza',
                'action' => 'index'
            ]);
        } else {
            $this->Flash->error(__('登録に失敗しました。'));
            Log::write('error', $newUser->toString());
        }
    }

    public function signin()
    {
        if (!$this->request->is('post')) {
            return;
        }

        // Post されたユーザー名とパスワードを元に DB からユーザーを検索
        $user = $this->Auth->identify();

        if ($user) {
            // ログイン処理
            $this->Auth->setUser($user);
            return $this->Auth->redirectUrl();
        } else {
            // 該当ユーザーなし
            $this->Flash->error(__('ユーザー名かパスワードが不正です。'));
        }
    }

    public function signout()
    {
        $this->request->session()->destroy();
        return $this->redirect($this->Auth->logout());
    }
}

?>


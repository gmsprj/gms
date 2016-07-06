<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table;
use Cake\Log\Log;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

/**
 * Users Controller
 *
 * ユーザーを管理するアプリケーション。
 *
 * @see src/Controller/Guilds.php
 *
 * @property \App\Model\Table\BoardsTable $Boards
 */
class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Csrf');
        $this->viewBuilder()->layout('gm-default');
        $this->Auth->allow([
            'signup',
            'signin',
            'signout',
            'signinRedirect',
        ]);
        $this->loadModel('Cells');
    }

    /**
     * サインアップする。
     *
     * @see src/Controller/AppController.php
     */
    public function signup()
    {
        if ($this->request->is('post')) {
            $user = $this->Users->newEntity();
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('サインアップしました。'));
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('サインアップに失敗しました。もう一度トライしてください。'));
            Log::error(json_encode($user->errors()));
        }
    }

    /**
     * サインインする。
     *
     * @see src/Controller/AppController.php
     */
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

    /**
     * サインアウトする。
     *
     * @see src/Controller/AppController.php
     */
    public function signout()
    {
        $this->Flash->success(__('サインアウトました。'));
        $this->request->session()->destroy();
        return $this->redirect($this->Auth->logout());
    }

    /**
     * サインイン時のリダイレクト先の定義。
     *
     * @see src/Controller/AppController.php
     */
    public function signinRedirect()
    {
        $user = $this->Auth->user();
        if (!$user) {
            return $this->redirect(['controller' => 'Users', 'action' => 'signin']);
        }

        return $this->redirect(['controller' => 'Users', 'action' => 'view', $user['id']]);
    }

    /**
     * Index method
     *
     */
    public function index()
    {
        if ($this->request->is('get')) {
            if (isset($this->request->query['auth'])) {
                $authUser = $this->Auth->user();

                $this->set('authUser', $authUser);
                $this->set('_serialize', [
                    'authUser',
                ]);
                return;
            }
        }
    }

    /**
     * View method
     *
     * @param string|null $id Users ID.
     */
    public function view($id = null)
    {
        if ($this->request->is('get')) {
        }
    }
}

?>


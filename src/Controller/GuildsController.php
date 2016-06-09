<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;

/**
 * Guilds Controller
 *
 * @property \App\Model\Table\GuildsTable $Guilds
 */
class GuildsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
        $this->viewBuilder()->layout('fwu-default');
        $this->Auth->allow(['entry']);
        $this->loadModel('Boards');
        $this->loadModel('Threads');
        $this->loadModel('Users');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->set('guilds', $this->Guilds->find('all'));
    }

    /**
     * View method
     *
     * @param string|null $id Guild id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $guild = $this->Guilds->get($id);
        $board = $this->Boards->find()
            ->where(['parent_name' => 'guilds', 'parent_id' => $guild->id])
            ->first();
        $threads = $this->Threads->find('all')
            ->where(['board_id' => $board->id]);

        $this->set('guild', $guild);
        $this->set('board', $board);
        $this->set('threads', $threads);
    }

    public function entry()
    {
        $userId = $this->request->data('userId');
        $guildId = $this->request->data('guildId');

        $usersTable = TableRegistry::get('Users');
        $user = $usersTable->get($userId);
        $user->guild_id = $guildId;
        $usersTable->save($user);
        $this->Auth->setUser($user->toArray()); // セッションの更新

        $this->Flash->success('入会しました。');
        return $this->redirect(['action' => 'view', $guildId]);
    }
}
